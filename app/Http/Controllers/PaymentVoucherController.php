<?php

namespace App\Http\Controllers;

use App\Helpers\ConstantHelper;
use App\Helpers\Helper;
use App\Helpers\FinancialPostingHelper;
use App\Helpers\SaleModuleHelper;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Book;
use App\Models\BookType;
use App\Models\CostCenter;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Group;
use App\Models\Ledger;
use App\Models\NumberPattern;
use App\Models\Organization;
use App\Models\PaymentVoucher;
use App\Models\VoucherReference;
use App\Models\PaymentVoucherDetails;
use App\Models\PaymentVoucherHistory;
use App\Models\UserOrganizationMapping;
use App\Models\Vendor;
use App\Models\Voucher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;
use Carbon\Carbon;

class PaymentVoucherController extends Controller
{
    public function amendment(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $voucher = PaymentVoucher::find($id);
            if (!$voucher) {
                return response()->json(['data' => [], 'message' => "Payment Voucher not found.", 'status' => 404]);
            }

            $revisionData = [
                ['model_type' => 'header', 'model_name' => 'PaymentVoucher', 'relation_column' => ''],
                ['model_type' => 'detail', 'model_name' => 'PaymentVoucherDetails', 'relation_column' => 'payment_voucher_id'],
                ['model_type' => 'sub_detail', 'model_name' => 'VoucherReference', 'relation_column' => 'voucher_details_id']
            ];

            $a = Helper::documentAmendment($revisionData, $id);
            if ($a) {
                Helper::approveDocument($voucher->book_id, $voucher->id, $voucher->revision_number, 'Amendment', $request->file('attachment'), $voucher->approval_level, 'amendment');

                $voucher->approvalStatus = ConstantHelper::DRAFT;
                $voucher->document_status = ConstantHelper::DRAFT;

                $voucher->revision_number = $voucher->revision_number + 1;
                $voucher->revision_date = now();
                $voucher->save();
            }

            DB::commit();
            return response()->json(['data' => [], 'message' => "Amendment done!", 'status' => 200]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Amendment Submit Error: ' . $e->getMessage());
            return response()->json(['data' => [], 'message' => "An unexpected error occurred. Please try again.", 'status' => 500]);
        }
    }

    public function approvePaymentVoucher(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $update = PaymentVoucher::find($request->id);
            $attachments = $request->file('attachment');
            $approveDocument = Helper::approveDocument($update->book_id, $update->id, $update->revision_number, $request->remarks, $attachments, $update->approval_level, $request->action_type);
            $update->approval_level = $approveDocument['nextLevel'];
            $update->approval_level = $approveDocument['nextLevel'];
            $update->approvalStatus = $approveDocument['approvalStatus'];
            $update->document_status = $approveDocument['approvalStatus'];
            $update->save();

            DB::commit();
            return response()->json([
                'message' => __('message.approved', ['module' => 'Payment Voucher']),
                'data' => $update,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('message.approve_failed', ['module' => 'Payment Voucher']),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getParties(Request $r)
    {
        if ($r->type == ConstantHelper::RECEIPTS_SERVICE_ALIAS) {
            $data = Customer::where('display_name', 'like', '%' . $r->keyword . '%')->select('id as value', 'display_name as label', 'customer_code as code');
        } else {
            $data = Vendor::where('display_name', 'like', '%' . $r->keyword . '%')->select('id as value', 'display_name as label', 'vendor_code as code');
        }

        if ($r->ids) {
            $data->whereNotIn('id', $r->ids);
        }
        $data = $data->where('status', 'active')->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->get()->toArray();
        return response()->json($data);
    }

    public function index(Request $request, $type = "Payment")
    {
        $user = Helper::getAuthenticatedUser();
        $userId = $user->id;
        $organizationId = $user->organization_id;

        $organizations = [];

        $parentURL = request() -> segments()[0];
        
         $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
         if (count($servicesBooks['services']) == 0) {
            return redirect() -> route('/');
        }


        $createRoute = route('payments.create');
        $editRouteString = 'payments.edit';
        if ($parentURL === 'payments') {
            $type = ConstantHelper::PAYMENTS_SERVICE_ALIAS;
            $createRoute = route('payments.create');
            $editRouteString = 'payments.edit';
        }
        if ($parentURL === 'receipts') {
            $type = ConstantHelper::RECEIPTS_SERVICE_ALIAS;
            $createRoute = route('receipts.create');
            $editRouteString = 'receipts.edit';
        }
        request() -> merge(['type' => $type]);

        // Check if `filter_organization` is set and push values to `$organizations`
        if ($request->filter_organization && is_array($request->filter_organization)) {
            foreach ($request->filter_organization as $value) {
                $organizations[] = $value;
            }
        }

        // If no organizations are selected, use the authenticated user's organization
        if (count($organizations) == 0) {
            $organizations[] = $organizationId;
        }

        // Retrieve vouchers based on organization_id and include series with levels
        $data = PaymentVoucher::whereIn("organization_id", $organizations)
            ->with([
                'series' => function ($d) {
                    $d->select('id', 'book_name');
                },
                'bank' => function ($d) {
                    $d->select('id', 'bank_name as name');
                },
                'ledger' => function ($d) {
                    $d->select('id', 'name');
                },
                'currency' => function ($d) {
                    $d->select('id', 'name', 'short_name');
                }
            ]);

        // Apply filters based on the request
        // if ($request->document_type) {
        $data = $data->where('document_type', $type);
        // }

        if ($request->document_no) {
            $data = $data->where('voucher_no', 'like', "%" . $request->document_no . "%");
        }

        if ($request->bank_id) {
            $data = $data->where('bank_id', $request->bank_id);
        }

        if ($request->ledger_id) {
            $data = $data->where('ledger_id', $request->ledger_id);
        }

        if ($request->date) {
            $dates = explode(' to ', $request->date);
            $start = date('Y-m-d', strtotime($dates[0]));
            $end = date('Y-m-d', strtotime($dates[1]));
            $data = $data->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end);
        }

        $data = $data->orderBy('id', 'desc')->paginate(20);

        // return response()->json($data);

        $mappings = UserOrganizationMapping::where('user_id', $user->id)
            ->with(['organization' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();

        $book_type = $request->book_type;
        $date = $request->date;
        $document_no = $request->document_no;
        $bank_id = $request->bank_id;
        $ledger_id = $request->ledger_id;
        $document_type = $request->document_type;
        $banks = Bank::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->get();
        $groupId = Group::where('name', 'Stock-in-Hand')->value('id');

        $ledgers = Ledger::where(function ($query) use ($groupId) {
            $query->whereJsonContains('ledger_group_id', $groupId)
                ->orWhere('ledger_group_id', $groupId);
        })->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        return view('paymentVoucher.paymentVouchers', compact('mappings', 'banks', 'ledgers', 'bank_id', 'ledger_id', 'organizationId', 'data', 'book_type', 'date', 'document_no', 'document_type', 'type', 'createRoute', 'editRouteString'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $r)
    {
        // $serviceAlias = [ConstantHelper::PAYMENT_VOUCHER_RECEIPT];
        $parentURL = request() -> segments()[0];
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
             return redirect() -> route('/');
         }
        // $redirectUrl = route('payments.index');
        $storeUrl = route('payments.store');
        if ($parentURL === 'payments') {
            $type = ConstantHelper::PAYMENTS_SERVICE_ALIAS;
            $redirectUrl = route('payments.index');
            $storeUrl = route('payments.store');
        }
        if ($parentURL === 'receipts') {
            $type = ConstantHelper::RECEIPTS_SERVICE_ALIAS;
            $redirectUrl = route('receipts.index');
            $storeUrl = route('receipts.store');
        }
        request() -> merge(['type' => $type]);
         $firstService = $servicesBooks['services'][0];
         $books = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();

        $banks = Bank::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->with('bankDetails')->get();
        $groupId = Group::where('name', 'Stock-in-Hand')->value('id');

        $ledgers = Ledger::where(function ($query) use ($groupId) {
            $query->whereJsonContains('ledger_group_id', $groupId)
                ->orWhere('ledger_group_id', $groupId);
        })->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        $currencies = Currency::where('status', ConstantHelper::ACTIVE)->select('id', 'name', 'short_name')->get();

        $orgCurrency = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->value('currency_id');
        return view('paymentVoucher.createPaymentVoucher', compact('books', 'banks', 'ledgers', 'currencies', 'orgCurrency', 'type', 'storeUrl', 'redirectUrl'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $organization = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->first();

        $voucher = new PaymentVoucher();

        $book = Book::find($request->book_id);
        $voucher->book_id = $request->book_id;
        $voucher->bookCode = $book->book_code;

        $voucher->voucher_no = $request->voucher_no;
        $voucher->document_type = $request->document_type;
        $voucher->date = $request->date;
        $voucher->payment_type = $request->payment_type;

        $voucher->bank_id = $request->bank_id;
        if ($request->payment_type==="Bank") {
            $bank = Bank::find($request->bank_id);
            $voucher->ledger_id = $bank->ledger_id;
            $voucher->ledger_group_id = $bank->ledger_group_id;
            $voucher->bankCode = $bank->bank_code;
            if ($request->account_id) {
                $account = BankDetail::find($request->account_id);
                $voucher->accountNo = $account->account_number;
                $voucher->account_id = $request->account_id;

            }
        }
        else{
            $groupId = Group::where('name', 'Stock-in-Hand')->value('id');
            $voucher->ledger_id = $request->ledger_id;
            $voucher->ledger_group_id = $groupId;

        }

        $voucher->payment_date = $request->payment_date;
        $voucher->payment_mode = $request->payment_mode;
        $voucher->reference_no = $request->reference_no;
        $voucher->revision_number = 0;




        $voucher->currency_id = $request->currency_id;
        $currency = Currency::find($request->currency_id);
        $voucher->currencyCode = $currency->short_name;

        $voucher->org_currency_id = $request->org_currency_id;
        $voucher->org_currency_code = $request->org_currency_code;
        $voucher->org_currency_exg_rate = $request->org_currency_exg_rate;

        $voucher->comp_currency_id = $request->comp_currency_id;
        $voucher->comp_currency_code = $request->comp_currency_code;
        $voucher->comp_currency_exg_rate = $request->comp_currency_exg_rate;

        $voucher->group_currency_id = $request->group_currency_id;
        $voucher->group_currency_code = $request->group_currency_code;
        $voucher->group_currency_exg_rate = $request->group_currency_exg_rate;

        $voucher->amount = $request->totalAmount;
        $voucher->organization_id = $organization->id;
        $voucher->group_id = $organization->group_id;
        $voucher->company_id = $organization->group_id;

        $voucher->document_date = $request->date;
        $voucher->doc_no = $request->doc_no;
        $voucher->doc_number_type = $request->doc_number_type;
        $voucher->doc_reset_pattern = $request->doc_reset_pattern;
        $voucher->doc_prefix = $request->doc_prefix;
        $voucher->doc_suffix = $request->doc_suffix;






        $voucher->remarks = $request->remarks;

        if ($request->status == ConstantHelper::SUBMITTED) {
            $voucher->approvalStatus = Helper::checkApprovalRequired($request->book_id);
            $voucher->document_status = Helper::checkApprovalRequired($request->book_id);
        } else {
            $voucher->approvalStatus = $request->status;
            $voucher->document_status = $request->status;
        }

        if ($request->hasFile('document')) {
            $fileName = time() . '_' . $request->file('document')->getClientOriginalName();
            $destinationPath = public_path('voucherPaymentDocuments');
            $request->file('document')->move($destinationPath, $fileName);
            $voucher->document = $fileName;
        }

        $userData = Helper::userCheck();
        $voucher->user_id = $userData['user_id'];
        $voucher->user_type = $userData['type'];
        //dd($request->all());
        $voucher->save();

        /*Create document submit log*/
        if ($request->status == ConstantHelper::SUBMITTED) {
            Helper::approveDocument($request->book_id, $voucher->id, $voucher->revision_number, $voucher->remarks, $request->file('attachment'), $voucher->approval_level, 'submit');
        }

        NumberPattern::where('organization_id', $organization->id)->where('book_id', $request->book_id)->orderBy('id', 'DESC')->first()->increment('current_no');

        // Add payment voucher details
        foreach ($request->party_id as $index => $party) {
            $details = new PaymentVoucherDetails();
            $details->payment_voucher_id = $voucher->id;
            if ($request->document_type == ConstantHelper::RECEIPTS_SERVICE_ALIAS) {
                $customer = Customer::find($party);
                $details->party()->associate($customer);
                $details->type = "customer";
                $details->partyCode = $customer->customer_code;
            } else {
                $customer = Vendor::find($party);
                $details->party()->associate($customer);
                $details->type = "vendor";
                $details->partyCode = $customer->vendor_code;
            }
            $details->currentAmount = $request->amount[$index];
            $details->orgAmount = $request->amount_exc[$index];
            $details->reference = $request->reference[$index];
            $details->save();
            if ($request->reference[$index]=="Invoice") {
                foreach (json_decode($request->party_vouchers[$index]) as $reference) {
                    $insertRef=new VoucherReference();
                    $insertRef->payment_voucher_id=$voucher->id;
                    $insertRef->voucher_details_id=$details->id;
                    $insertRef->party_id=$reference->party_id;
                    $insertRef->voucher_id=$reference->voucher_id;
                    $insertRef->amount=$reference->amount;
                    $insertRef->save();
                }
            }
        }
        if ($voucher -> document_type === ConstantHelper::PAYMENTS_SERVICE_ALIAS) {
            return redirect()->route("payments.index")->with('success', __('message.created', ['module' => 'Payment Voucher']));
        } else {
            return redirect()->route("receipts.index")->with('success', __('message.created', ['module' => 'Payment Voucher']));
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $r, $payment)
    {
        $id = $payment;
        $parentURL = request() -> segments()[0];
        $editUrl = 'payments.update';
        $indexUrl = route('payments.index');
        $editUrlString = 'payments.edit';
        if ($parentURL === 'payments') {
            $editUrl = 'payments.update';
            $indexUrl = route('payments.index');
            $editUrlString = 'payments.edit';
        }
        if ($parentURL === 'receipts') {
            $editUrl = 'receipts.update';
            $indexUrl = route('receipts.index');
            $editUrlString = 'receipts.edit';
        }
        $currNumber = $r->revisionNumber;
        if ($currNumber) {
            $data = PaymentVoucherHistory::with(['details.party'])->where('source_id', $id)->where('revision_number', $currNumber)->first();
        } else {
            $data = PaymentVoucher::with('details.party')->find($id);
        }

        // $serviceAlias = [ConstantHelper::PAYMENT_VOUCHER_RECEIPT];
        $serviceAlias = Helper::getAccessibleServicesFromMenuAlias($parentURL)['services'];
        $books = Helper::getBookSeriesNew(count($serviceAlias) > 0 ? $serviceAlias[0] -> alias : '', $parentURL, true)->get();
        $buttons = Helper::actionButtonDisplay($data->book_id, $data->approvalStatus, $data->id, $data->amount, $data->approvalLevel, $data->user_id, $data->user_type);
        $history = Helper::getApprovalHistory($data->book_id, $id, $data->revision_number);

        $banks = Bank::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->with('bankDetails')->get();
        $groupId = Group::where('name', 'Stock-in-Hand')->value('id');

        $ledgers = Ledger::where(function ($query) use ($groupId) {
            $query->whereJsonContains('ledger_group_id', $groupId)
                ->orWhere('ledger_group_id', $groupId);
        })->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id', 'name')->get();
        $currencies = Currency::where('status', ConstantHelper::ACTIVE)->select('id', 'name', 'short_name')->get();
        $orgCurrency = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->value('currency_id');
        $revisionNumbers = $history->pluck('revision_number')->unique()->values()->all();

        return view('paymentVoucher.editPaymentVoucher', compact('data', 'books', 'buttons', 'history', 'banks', 'ledgers', 'currencies', 'orgCurrency', 'revisionNumbers', 'currNumber', 'editUrl', 'indexUrl', 'editUrlString'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $voucher = PaymentVoucher::find($id);

        // $voucher_no = $request->voucher_no;
        // if (PaymentVoucher::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->where('book_id', $request->book_id)->where('voucher_no', $voucher_no)->count() > 0) {
        //     if (NumberPattern::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->where('book_id', $request->book_id)->value('series_numbering') == "Auto") {
        //         $get_no = Helper::generateVoucherNumber($request->book_id);
        //         $voucher_no = $get_no['voucher_no'];
        //     } else {
        //         $voucher_no = $voucher_no . '1';
        //     }
        // }
        // $voucher->voucher_no = $voucher_no;


        // $organization = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->first();

        // $voucher->book_id = $request->book_id;
        $voucher->document_type = $request->document_type;
        $voucher->date = $request->date;
        $voucher->payment_type = $request->payment_type;

        if ($voucher->payment_type == "Bank") {
            $voucher->bank_id = $request->bank_id;
            $bank = Bank::find($request->bank_id);
            $voucher->bankCode = $bank->bank_code;

            $voucher->account_id = $request->account_id;
            $account = BankDetail::find($request->account_id);
            $voucher->accountNo = $account->account_number;
            $voucher->payment_mode = $request->payment_mode;
            $voucher->reference_no = $request->reference_no;
            $voucher->ledger_id = $bank->ledger_id;
            $voucher->ledger_group_id = $bank->ledger_group_id;
        } else {
            $groupId = Group::where('name', 'Stock-in-Hand')->value('id');
            $voucher->ledger_id = $request->ledger_id;
            $voucher->ledger_group_id = $groupId;
            $voucher->account_id = null;
            $voucher->bankCode = null;
            $voucher->accountNo = null;
            $voucher->payment_mode = null;
            $voucher->reference_no = null;
        }
        $voucher->payment_mode = $request->payment_mode;


        $voucher->payment_date = $request->payment_date;

        $voucher->currency_id = $request->currency_id;
        $currency = Currency::find($request->currency_id);
        $voucher->currencyCode = $currency->short_name;

        $voucher->org_currency_id = $request->org_currency_id;
        $voucher->org_currency_code = $request->org_currency_code;
        $voucher->org_currency_exg_rate = $request->org_currency_exg_rate;

        $voucher->comp_currency_id = $request->comp_currency_id;
        $voucher->comp_currency_code = $request->comp_currency_code;
        $voucher->comp_currency_exg_rate = $request->comp_currency_exg_rate;

        $voucher->group_currency_id = $request->group_currency_id;
        $voucher->group_currency_code = $request->group_currency_code;
        $voucher->group_currency_exg_rate = $request->group_currency_exg_rate;

        $voucher->amount = $request->totalAmount;
        $voucher->remarks = $request->remarks;




        if ($request->status == ConstantHelper::SUBMITTED) {
            $voucher->approvalStatus = Helper::checkApprovalRequired($voucher->book_id);
            $voucher->document_status = Helper::checkApprovalRequired($voucher->book_id);

            //$voucher->document_status = Helper::checkApprovalRequired($voucher->book_id);
        } else {
            $voucher->approvalStatus = $request->status;
            $voucher->document_status = Helper::checkApprovalRequired($voucher->book_id);

            // $voucher->document_status = Helper::checkApprovalRequired($voucher->book_id);
        }

        if ($request->hasFile('document')) {
            $fileName = time() . '_' . $request->file('document')->getClientOriginalName();
            $destinationPath = public_path('voucherPaymentDocuments');
            $request->file('document')->move($destinationPath, $fileName);
            $voucher->document = $fileName;
        }

        $voucher->save();

        /*Create document submit log*/
        if ($request->status == ConstantHelper::SUBMITTED) {
            Helper::approveDocument($voucher->book_id, $voucher->id, $voucher->revision_number, $voucher->remarks, $request->file('attachment'), $voucher->approval_level, 'submit');
        }

        // NumberPattern::where('organization_id', $organization->id)->where('book_id', $request->book_id)->orderBy('id', 'DESC')->first()->increment('current_no');

        // Remove existing details
        VoucherReference::whereIn('voucher_details_id',PaymentVoucherDetails::where('payment_voucher_id', $id)->pluck('id')->toArray())->delete();
        PaymentVoucherDetails::where('payment_voucher_id', $id)->delete();


        // Add payment voucher details
        foreach ($request->party_id as $index => $party) {
            $details = new PaymentVoucherDetails();
            $details->payment_voucher_id = $voucher->id;
            if ($request->document_type == ConstantHelper::RECEIPTS_SERVICE_ALIAS) {
                $customer = Customer::find($party);
                $details->party()->associate($customer);
                $details->type = "customer";
                $details->partyCode = $customer->customer_code;
            } else {
                $customer = Vendor::find($party);
                $details->party()->associate($customer);
                $details->type = "vendor";
                $details->partyCode = $customer->vendor_code;
            }
            $details->currentAmount = $request->amount[$index];
            $details->orgAmount = $request->amount_exc[$index];
            $details->reference = $request->reference[$index];
            $details->save();
            if ($request->reference[$index]=="Invoice") {
                foreach (json_decode($request->party_vouchers[$index]) as $reference) {
                    $insertRef=new VoucherReference();
                    $insertRef->payment_voucher_id=$voucher->id;
                    $insertRef->voucher_details_id=$details->id;
                    $insertRef->party_id=$reference->party_id;
                    $insertRef->voucher_id=$reference->voucher_id;
                    $insertRef->amount=$reference->amount;
                    $insertRef->save();
                }
            }
        }

        if ($voucher -> document_type === ConstantHelper::PAYMENTS_SERVICE_ALIAS) {
            return redirect()->route("payments.index")->with('success', __('message.created', ['module' => 'Payment Voucher']));
        } else {
            return redirect()->route("receipts.index")->with('success', __('message.created', ['module' => 'Payment Voucher']));
        }
    }
    public function testPostingDetails($request)
    {
            try{
                $data = FinancialPostingHelper::receiptVoucherPosting($request->book_id ?? 0, $request->id ?? 0, "get",$request->remarks??"No Remarks here...");
            return response() -> json([
                'status' => 'success',
                'data' => $data
            ]);
        }
        catch(Exception $e){
                return response() -> json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }

    }
public function getPostingDetails(Request $request)
    {
            try{

            if($request->type==ConstantHelper::RECEIPTS_SERVICE_ALIAS){
                $data = FinancialPostingHelper::receiptVoucherPosting($request->book_id ?? 0, $request->document_id ?? 0, "get",$request->remarks);
                return response() -> json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }else if($request->typeConstantHelper::PAYMENTS_SERVICE_ALIAS){
                $data = FinancialPostingHelper::paymentVoucherPosting($request->book_id ?? 0, $request->document_id ?? 0, "get",$request->remarks);
                return response() -> json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }
            else{
                return response() -> json([
                    'status' => 'error',
                    'message' => 'Type not set'
                ]);
            }
        }
        catch(Exception $e){
                return response() -> json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }

    }
    public function postPostingDetails(Request $request)
    {
        try{
            if($request->type==ConstantHelper::RECEIPTS_SERVICE_ALIAS){
            $data = FinancialPostingHelper::receiptVoucherPosting($request->book_id ?? 0, $request->document_id ?? 0, "post",$request->remarks??"No Remarks Here..");
            return response() -> json([
                'status' => 'success',
                'data' => $data
            ]);
        }else if($request->type==ConstantHelper::PAYMENTS_SERVICE_ALIAS){
            $data = FinancialPostingHelper::paymentVoucherPosting($request->book_id ?? 0, $request->document_id ?? 0, "post",$request->remarks??"No Remarks Here..");
            return response() -> json([
                'status' => 'success',
                'data' => $data
            ]);
        }

        else{
            return response() -> json([
                'status' => 'error',
                'message' => 'Type not set'
            ]);
        }
        }
        catch(Exception $e){
            return response() -> json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

    }
}
