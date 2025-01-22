<?php

namespace App\Http\Controllers;

use App\Helpers\ConstantHelper;
use Illuminate\Support\Facades\Validator;

use App\Models\Book;
use App\Models\BookType;
use Illuminate\Http\Request;
use App\Models\CostCenter;
use Illuminate\Support\Facades\DB;
use App\Models\ApprovalWorkflow;
use App\Models\VoucherReference;
use App\Models\Voucher;
use App\Models\Ledger;
use App\Models\ItemDetail;
use App\Models\NumberPattern;
use App\Models\UserOrganizationMapping;
use App\Models\Organization;
use Auth;
use App\Helpers\Helper;
use App\Models\DocumentApproval;
use App\Models\VoucherHistory;
use Carbon\Carbon;
use Exception;
use Hamcrest\Arrays\IsArray;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Models\OrganizationService;
use App\Models\Group;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Vendor;


class VoucherController extends Controller
{
    public function getLedgerVouchers(Request $request)
    {
        if($request->type=="receipts")
        $ledger= Customer::where('customer_code',$request->partyCode)->value('id');
    else
        $ledger= Vendor::where('vendor_code',$request->partyCode)->value('id');

        $data = Voucher::where("organization_id", Helper::getAuthenticatedUser()->organization_id);
            if ($request->date) {
                $data->whereDate('date', date('Y-m-d',strtotime($request->date)));

            }
            $data = $data->whereHas('items', function($i) use($ledger, $request){
                $i->where('ledger_id',$ledger);
                if ($request->type=="payments") {
                    $i->where('credit_amt','>',0);
                } else {
                    $i->where('debit_amt','>',0);
                }
            });


            if ($request->book_code) {
                $data = $data->whereHas('series', function($b) use($request){
                    $b->where('book_code',$request->book_code);
                });
            }

            if ($request->document_no) {
                $data = $data->where('voucher_no','like',"%". $request->document_no ."%");
            }

        $data=$data->with(['series'=>function($s){
            $s->select('id','book_code');
        }])->select('id','amount','book_id','date','voucher_name','voucher_no')
        ->orderBy('id','desc')->get()->map(function($voucher) use($request,$ledger){
            $voucher->date=date('d/m/Y',strtotime($voucher->date));
            $balance=VoucherReference::where('voucher_id',$voucher->id)->where('party_id',$ledger);
                if ($request->id) {
                    $balance->where('payment_voucher_id','!=',$request->id);
                }
            $balance=$balance->sum('amount');
            $voucher->balance=$voucher->amount - $balance;

            return $voucher;
        });
        return response()->json(['data'=>$data,'ledgerId'=>$ledger]);
    }

    public function amendment(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $voucher = Voucher::find($id);
            if (!$voucher) {
                return response()->json(['data' => [], 'message' => "Payment Voucher not found.", 'status' => 404]);
            }

            $revisionData = [
                ['model_type' => 'header', 'model_name' => 'Voucher', 'relation_column' => ''],
                ['model_type' => 'detail', 'model_name' => 'ItemDetail', 'relation_column' => 'voucher_id']
            ];

            $a = Helper::documentAmendment($revisionData, $id);
            if ($a) {
                Helper::approveDocument($voucher->book_id, $voucher->id, $voucher->revision_number, 'Amendment', $request->file('attachment'), $voucher->approvalLevel, 'amendment');

                $voucher->approvalStatus = ConstantHelper::DRAFT;
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

    public function approveVoucher(Request $request)
    {
        $request->validate([
            'remarks' => 'nullable',
            'attachment' => 'nullable'
        ]);
        DB::beginTransaction();
        try {
            $update = Voucher::find($request->id);
            $attachments = $request->file('attachment');
            $approveDocument = Helper::approveDocument($update->book_id, $update->id, $update->revision_number, $request->remarks, $attachments, $update->approvalLevel, $request->action_type);
            $update->approvalLevel = $approveDocument['nextLevel'];
            $update->approvalStatus = $approveDocument['approvalStatus'];
            $update->save();

            DB::commit();
            if($request->action_type=="approve")
            return response()->json([
                'message' => __('message.approved', ['module' => 'Voucher']),
                'data' => $update,
            ]);
            else if($request->action_type=='reject')
            return response()->json([
                'message' => __('message.reject', ['module' => 'Voucher']),
                'data' => $update,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => __('message.approve_failed', ['module' => 'Voucher']),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function ledgers_search(Request $r)
    {
        $data = Ledger::where('name', 'like', '%' . $r->keyword . '%');
        // if ($r->ids) {
        //     $data->whereNotIn('id', $r->ids);
        // }
        $data = $data->where('status', 1)->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id as value', 'name as label', 'cost_center_id')->get()->toArray();
        return response()->json($data);
    }

    public function get_voucher_no($book_id)
    {
        $data = Helper::generateVoucherNumber($book_id);
        return response()->json($data);
    }

    public function index(Request $request)
    {
        $parentURL = request() -> segments()[0];
         $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
         if (count($servicesBooks['services']) == 0) {
            return redirect() -> route('/');
        }

        $user = Helper::getAuthenticatedUser();
        $userId = $user->id;
        $organizationId = $user->organization_id;

        $organizations = [];

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
        $data = Voucher::whereIn("organization_id", $organizations)
            ->with([
                'documents' => function ($d) {
                    $d->select('id', 'name');
                }
            ]);

        // Apply filters based on the request
        if ($request->book_type) {
            $data = $data->where('book_type_id', $request->book_type);
        }

        if ($request->voucher_no) {
            $data = $data->where('voucher_no', 'like', "%" . $request->voucher_no . "%");
        }

        if ($request->voucher_name) {
            $data = $data->where('voucher_name', 'like', "%" .  $request->voucher_name . "%");
        }

        if ($request->date) {
            $dates = explode(' to ', $request->date);
            $start = date('Y-m-d', strtotime($dates[0]));
            $end = date('Y-m-d', strtotime($dates[1]));
            $data = $data->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end);
        }

        $data = $data->orderBy('id', 'desc')->paginate(20);

        $bookTypes = BookType::where('status', 'Active')->select('id', 'name')->get();
        $mappings = UserOrganizationMapping::where('user_id', $user->id)
            ->with(['organization' => function ($query) {
                $query->select('id', 'name');
            }])
            ->get();

        $book_type = $request->book_type;
        $date = $request->date;
        $voucher_no = $request->voucher_no;
        $voucher_name = $request->voucher_name;
        return view('voucher.view_vouchers', compact('bookTypes', 'mappings', 'organizationId', 'data', 'book_type', 'date', 'voucher_no', 'voucher_name'));
    }

    public function create()
    {
        $parentUrl = request() -> segments()[0];
        $cost_centers = CostCenter::where('status', 'active')->where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id as value', 'name as label')->get()->toArray();
        // $serviceAlias = [ConstantHelper::PURCHASE_VOUCHER, ConstantHelper::SALES_VOUCHER, ConstantHelper::RECEIPT_VOUCHER, ConstantHelper::PAYMENT_VOUCHER, ConstantHelper::DEBIT_Note, ConstantHelper::CREDIT_Note, ConstantHelper::JOURNAL_VOUCHER, ConstantHelper::CONTRA_VOUCHER];
        
        $serviceAlias = Helper::getAccessibleServicesFromMenuAlias($parentUrl);
        if (count($serviceAlias['services']) == 0) {
            return redirect() -> route('/');
        }

        $bookTypes = $serviceAlias['services'];

        $books = [];
        // Loop through each alias and collect the series
        foreach ($bookTypes as $alias) {
            $books[] = Helper::getBookSeriesNew($alias -> alias, $parentUrl)->get(); // Keep the structure as it is
        }
        
        $lastVoucher = Voucher::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->orderBy('id', 'desc')->select('book_type_id', 'book_id')->first();
        $currencies = Currency::where('status', ConstantHelper::ACTIVE)->select('id', 'name', 'short_name')->get();
        $orgCurrency = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->value('currency_id');
        return view('voucher.create_voucher', compact('currencies', 'orgCurrency', 'cost_centers', 'bookTypes', 'lastVoucher'));
    }

    function get_series($id)
    {
        // $parentURL = request() -> segments()[0];
        $service = OrganizationService::find($id);
        return response()->json(Helper::getBookSeriesNew($service->alias,"vouchers")->get());
    }
    public function edit(Request $r, $id)
    {
        $currNumber = $r->revisionNumber;
        if ($currNumber) {
            $data = VoucherHistory::with(['items.ledgers.groups'])->where('source_id', $id)->where('revision_number', $currNumber)->first();
        } else {
            $data = Voucher::with(['items'])->find($id);
        }
        $parentUrl = request() -> segments()[0];
        $cost_centers = CostCenter::where('organization_id', Helper::getAuthenticatedUser()->organization_id)->select('id as value', 'name as label')->get()->toArray();
        $serviceAlias = [ConstantHelper::PURCHASE_VOUCHER, ConstantHelper::SALES_VOUCHER, ConstantHelper::RECEIPT_VOUCHER, ConstantHelper::PAYMENT_VOUCHER, ConstantHelper::DEBIT_Note, ConstantHelper::CREDIT_Note, ConstantHelper::JOURNAL_VOUCHER, ConstantHelper::CONTRA_VOUCHER];
        // $bookTypes = OrganizationService::withDefaultGroupCompanyOrg()->whereIn('alias', $serviceAlias)->where('status', ConstantHelper::ACTIVE)->get();
        $serviceAlias = Helper::getAccessibleServicesFromMenuAlias($parentUrl);
        if (count($serviceAlias['services']) == 0) {
            return redirect() -> route('/');
        }
        
        $bookTypes = $serviceAlias['services'];
        $books = [];
        // Loop through each alias and collect the series
        foreach ($bookTypes as $alias) {
            $books[] = Helper::getBookSeriesNew($alias -> alias, $parentUrl)->get(); // Keep the structure as it is
        }
        //dd($books);

        //$books = Book::where('booktype_id', $data->book_type_id)->select('id', 'book_code as code', 'book_name')->get();
        $creatorType = explode("\\", $data->voucherable_type);
        $buttons = Helper::actionButtonDisplay($data->book_id, $data->approvalStatus, $data->id, $data->amount, $data->approvalLevel, $data->voucherable_id, strtolower(end($creatorType)));
        try {
            $history = Helper::getApprovalHistory($data->book_id, $id, $data->revision_number);
            $revisionNumbers = $history->pluck('revision_number')->unique()->values()->all();
        } catch (Exception $e) {
            $history = null;
            $revisionNumbers = null;
        }
        $currencies = Currency::where('status', ConstantHelper::ACTIVE)->select('id', 'name', 'short_name')->get();
        $orgCurrency = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->value('currency_id');
        $groups = Group::all();

        // return response()->json($history);
        return view('voucher.edit_voucher', compact('groups', 'orgCurrency', 'currencies', 'cost_centers', 'bookTypes', 'data', 'books', 'buttons', 'history', 'revisionNumbers', 'currNumber'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'voucher_name' => 'required|string',
            'date' => 'required|date',
            'document' => 'nullable|array', 
            'document.*' => 'file',
            'debit_amt' => 'required|array',
            'credit_amt' => 'required|array',
            'voucher_no' => 'required|string',
            'ledger_id' => 'required|array',
            'ledger_id.*' => 'required|numeric|min:1',
            'parent_ledger_id' => 'required|array',
            'parent_ledger_id.*' => 'required|numeric|min:1',  //parent_ledger_id
        ], [
            // Custom error messages
            'voucher_name.required' => 'The voucher name is required.',
            'voucher_name.string' => 'The voucher name must be a string.',
            'date.required' => 'The date is required.',
            'date.date' => 'The date must be a valid date format.',
            'document.array' => 'The document field must be an array.',
            'document.*.file' => 'Each document must be a valid file.',
            'debit_amt.required' => 'The debit amount is required.',
            'debit_amt.array' => 'The debit amount must be an array.',
            'credit_amt.required' => 'The credit amount is required.',
            'credit_amt.array' => 'The credit amount must be an array.',
            'voucher_no.required' => 'The voucher number is required.',
            'voucher_no.string' => 'The voucher number must be a string.',
            'ledger_id.required' => 'The ledger ID field is required.',
            'ledger_id.array' => 'The ledger ID must be an array.',
            'ledger_id.*.required' => 'Each ledger ID is required.',
            'parent_ledger_id.array' => 'The ledger Group ID must be an array.',
            'parent_ledger_id.*.required' => 'Each ledger Group is required.',
            'parent_ledger_id.required' => 'Ledger Group is required.',
        
        
        ]);
        

if ($validator->fails()) {
    return redirect()
        ->route('vouchers.create') 
        ->withInput() // Pass the input data back to the session
        ->withErrors($validator); // Pass the validation errors back to the session
}

// Continue with logic if validation passes

        $voucher_no = $request->voucher_no;

        $organization = Organization::where('id', Helper::getAuthenticatedUser()->organization_id)->first();

        // Create a new voucher
        $voucher = new Voucher();
        $voucher->voucher_no = $voucher_no;
        $voucher->voucher_name = $request->voucher_name;
        // $voucher->book_type_id = $request->book_type_id;
        $voucher->book_id = $request->book_id;

        //currency_related_fileds
        $voucher->currency_id = $request->currency_id;
        $voucher->currency_code = $request->currency_code;
        $voucher->org_currency_exg_rate = $request->orgExchangeRate;
        $voucher->org_currency_id = $request->org_currency_id;
        $voucher->org_currency_code = $request->org_currency_code;
        $voucher->org_currency_exg_rate = $request->org_currency_exg_rate;
        $voucher->comp_currency_id = $request->comp_currency_id;
        $voucher->comp_currency_code = $request->comp_currency_code;
        $voucher->comp_currency_exg_rate = $request->comp_currency_exg_rate;
        $voucher->group_currency_id = $request->group_currency_id;
        $voucher->group_currency_code = $request->group_currency_code;
        $voucher->group_currency_exg_rate = $request->group_currency_exg_rate;

        $voucher->date = $request->date;
        $voucher->remarks = $request->remarks;
        $voucher->amount = $request->amount;
        $voucher->organization_id = $organization->id;
        $voucher->group_id = $organization->group_id;
        $voucher->company_id = $organization->group_id;
        $voucher->revision_number = 0;

        $voucher->document_date = $request->date;
        $voucher->doc_no = $request->doc_no;
        $voucher->doc_number_type = $request->doc_number_type;
        $voucher->doc_reset_pattern = $request->doc_reset_pattern;
        $voucher->doc_prefix = $request->doc_prefix;
        $voucher->doc_suffix = $request->doc_suffix;


        if ($request->status == ConstantHelper::SUBMITTED) {
            $voucher->approvalStatus = Helper::checkApprovalRequired($request->book_id);
        } else {
            $voucher->approvalStatus = $request->status;
        }

        if ($request->hasFile('document')) {
            $files = $request->file('document'); // 'document' should be an array of files
            $fileNames = [];
            
            foreach ($files as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('voucherDocuments');
                $file->move($destinationPath, $fileName);
                
                // Store the file name in an array (you can save it in the database if needed)
                $fileNames[] = $fileName;
            }
            
            // If you want to save multiple filenames in the database
            $voucher->document = json_encode($fileNames); // Save file names as a JSON string
        }
        $userData = Helper::userCheck();
        $voucher->voucherable_id = $userData['user_id'];
        $voucher->voucherable_type = $userData['user_type'];

        $voucher->save();

        /*Create document submit log*/
        if ($request->status == ConstantHelper::SUBMITTED) {
            Helper::approveDocument($request->book_id, $voucher->id, $voucher->revision_number, $voucher->remarks, $request->file('attachment'), $voucher->approvalLevel, 'submit');
        }

        //NumberPattern::where('organization_id', $organization->id)->where('book_id', $request->book_id)->orderBy('id', 'DESC')->first()->increment('current_no');

        $voucherId = $voucher->id;

        // Process item details
        $debitAmts = $request->input('debit_amt');
        $creditAmts = $request->input('credit_amt');

        $debitAmtsComp = $request->input('comp_debit_amt');
        $creditAmtsComp = $request->input('comp_credit_amt');

        $debitAmtsOrg = $request->input('org_debit_amt');
        $creditAmtsOrg = $request->input('org_credit_amt');

        $itemRemarks = $request->input('item_remarks');


        $debitAmtsGroup = $request->input('group_debit_amt');
        $creditAmtsGroup = $request->input('group_credit_amt');

        $parentLedger = $request->input('parent_ledger_id');



        foreach ($debitAmts as $index => $debitAmount) {
            if(isset($request->ledger_id[$index]) && isset($parentLedger[$index])){
            
            $notename = "notes" . ($index + 1);
            $ledger_id = $request->ledger_id[$index];
            $cost_center_id = "cost_center_id" . ($index + 1);

            $debit = $debitAmts[$index] ?? 0;
            $credit = $creditAmts[$index] ?? 0;

            $debitComp = $debitAmtsComp[$index] ?? 0;
            $creditComp = $creditAmtsComp[$index] ?? 0;

            $debitGroup = $debitAmtsGroup[$index] ?? 0;
            $creditGroup = $creditAmtsGroup[$index] ?? 0;

            $debitOrg = $debitAmtsOrg[$index] ?? 0;
            $creditOrg = $creditAmtsOrg[$index] ?? 0;

            $parent_ledger_id = $parentLedger[$index];

            $item_remarks = $itemRemarks[$index] ?? "";

            $opening = 0;
            $closing = 0;
            $openingType = null;
            $closingType = null;

            // Calculate the new Closing Balance and Closing Type
            if ($debit > $credit) {
                $closingType = 'Dr';
            } else {
                $closingType = 'Cr';
            }
            $closing = $debit - $credit;
            $closing = $closing < 0 ? -$closing : $closing;


            // Check if Ledger has already some transactions then calculate closing balance, closing type, opening balance and opening type with old transactions
            $lastItemDetail = ItemDetail::where('ledger_id', $ledger_id)->orderBy('date', 'desc')->first();
            if ($lastItemDetail) {

                // Calculate the new opening balance and type
                $opening = $lastItemDetail->closing;
                $openingType = $lastItemDetail->closing_type;

                // Calculate the new closing type and balance
                if ($lastItemDetail->closing > 0) {
                    if ($lastItemDetail->closing_type == $closingType) {
                        $closing = $closing + $lastItemDetail->closing;
                    } else {
                        $difference = $closing - $lastItemDetail->closing;
                        if ($difference != 0) {
                            $difference = $difference > 0 ? $difference : -$difference;
                            if ($closing < $lastItemDetail->closing) {
                                $closingType = $lastItemDetail->closing_type;
                            }
                            $closing = $difference;
                        } else {
                            $closing = 0;
                            $closingType = null;
                        }
                    }
                }
            }

            // Insert the new ItemDetail record
            ItemDetail::create([
                'voucher_id' => $voucherId,
                'ledger_id' => $ledger_id,
                'debit_amt' => $debit,
                'credit_amt' => $credit,
                'debit_amt_org' => $debitOrg,
                'credit_amt_org' => $creditOrg,
                'debit_amt_comp' => $debitComp,
                'credit_amt_comp' => $creditComp,
                'debit_amt_group' => $debitGroup,
                'credit_amt_group' => $creditGroup,
                'ledger_parent_id' => $parent_ledger_id,
                'cost_center_id' => $request->$cost_center_id,
                'notes' => $request->$notename,
                'date' => $request->date,
                'opening' => $opening,
                'closing' => $closing,
                'opening_type' => $openingType,
                'closing_type' => $closingType,
                'organization_id' => $organization->id,
                'group_id' => $organization->group_id,
                'company_id' => $organization->group_id,
                'remarks' => $item_remarks
            ]);
        }
    }

        return redirect()->route("vouchers.index")->with('success', 'Voucher created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'voucher_name' => 'required|string',
            'date' => 'required|date',
            'document' => 'nullable|array', 
            'document.*' => 'file',
            'debit_amt' => 'required|array',
            'credit_amt' => 'required|array',
            'voucher_no' => 'required|string',
            'ledger_id' => 'required|array',
            'ledger_id.*' => 'required|numeric|min:1',
            'parent_ledger_id' => 'required|array',
            'parent_ledger_id.*' => 'required|numeric|min:1',  //parent_ledger_id
        ], [
            // Custom error messages
            'voucher_name.required' => 'The voucher name is required.',
            'voucher_name.string' => 'The voucher name must be a string.',
            'date.required' => 'The date is required.',
            'date.date' => 'The date must be a valid date format.',
            'document.array' => 'The document field must be an array.',
            'document.*.file' => 'Each document must be a valid file.',
            'debit_amt.required' => 'The debit amount is required.',
            'debit_amt.array' => 'The debit amount must be an array.',
            'credit_amt.required' => 'The credit amount is required.',
            'credit_amt.array' => 'The credit amount must be an array.',
            'voucher_no.required' => 'The voucher number is required.',
            'voucher_no.string' => 'The voucher number must be a string.',
            'ledger_id.required' => 'The ledger ID field is required.',
            'ledger_id.array' => 'The ledger ID must be an array.',
            'ledger_id.*.required' => 'Each ledger ID is required.',
            'parent_ledger_id.array' => 'The ledger Group ID must be an array.',
            'parent_ledger_id.*.required' => 'Each ledger Group is required.',
            'parent_ledger_id.required' => 'Ledger Group is required.',
        
        ]);
        
        if ($validator->fails()) {
            return redirect()
                ->route('vouchers.edit',$id) 
                ->withInput() // Pass the input data back to the session
                ->withErrors($validator); // Pass the validation errors back to the session
        }
        $voucher = Voucher::find($id);
        $voucher->remarks = $request->remarks;
        $voucher->amount = $request->amount;

        //currency_related_fields
        $voucher->currency_id = $request->currency_id;
        $voucher->currency_code = $request->currency_code;
        $voucher->org_currency_exg_rate = $request->orgExchangeRate;
        $voucher->org_currency_id = $request->org_currency_id;
        $voucher->org_currency_code = $request->org_currency_code;
        $voucher->org_currency_exg_rate = $request->org_currency_exg_rate;
        $voucher->comp_currency_id = $request->comp_currency_id;
        $voucher->comp_currency_code = $request->comp_currency_code;
        $voucher->comp_currency_exg_rate = $request->comp_currency_exg_rate;
        $voucher->group_currency_id = $request->group_currency_id;
        $voucher->group_currency_code = $request->group_currency_code;
        $voucher->group_currency_exg_rate = $request->group_currency_exg_rate;

        if ($request->status == ConstantHelper::SUBMITTED) {
            $voucher->approvalStatus = Helper::checkApprovalRequired($voucher->book_id);
        } else {
            $voucher->approvalStatus = $request->status;
        }

        if ($request->hasFile('document')) {
            $files = $request->file('document'); // 'document' should be an array of files
            $fileNames = [];
            
            foreach ($files as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $destinationPath = public_path('voucherDocuments');
                $file->move($destinationPath, $fileName);
                
                // Store the file name in an array (you can save it in the database if needed)
                $fileNames[] = $fileName;
            }
            
            // If you want to save multiple filenames in the database
            $voucher->document = json_encode($fileNames); // Save file names as a JSON string
        }
        $voucher->save();

        /*Create document submit log*/
        if ($request->status == ConstantHelper::SUBMITTED) {
            Helper::approveDocument($voucher->book_id, $voucher->id, $voucher->revision_number, $voucher->remarks, $request->file('attachment'), $voucher->approvalLevel, 'submit');
        }

        // check existing records
        $itemsDetails = ItemDetail::where('voucher_id', $id);
        $lastDate = $itemsDetails->value('date');
        $ledgers = $itemsDetails->pluck('ledger_id')->toArray();

        ItemDetail::where('voucher_id', $id)->delete();

        // Process item details
        $debitAmts = $request->input('debit_amt');
        $creditAmts = $request->input('credit_amt');

        $debitAmtsComp = $request->input('comp_debit_amt');
        $creditAmtsComp = $request->input('comp_credit_amt');

        $debitAmtsOrg = $request->input('org_debit_amt');
        $creditAmtsOrg = $request->input('org_credit_amt');

        $itemRemarks = $request->input('item_remarks');


        $debitAmtsGroup = $request->input('group_debit_amt');
        $creditAmtsGroup = $request->input('group_credit_amt');

        $parentLedger = $request->input('parent_ledger_id');

        foreach ($debitAmts as $index => $debitAmount) {
            if(isset($request->ledger_id[$index]) && isset($parentLedger[$index])){
            $notename = "notes" . $index + 1;
            $ledger_id = $request->ledger_id[$index];
            $cost_center_id = "cost_center_id" . $index + 1;
            $debitComp = $debitAmtsComp[$index] ?? 0;
            $creditComp = $creditAmtsComp[$index] ?? 0;

            $debitGroup = $debitAmtsGroup[$index] ?? 0;
            $creditGroup = $creditAmtsGroup[$index] ?? 0;

            $debitOrg = $debitAmtsOrg[$index] ?? 0;
            $creditOrg = $creditAmtsOrg[$index] ?? 0;

            $parent_ledger_id = $parentLedger[$index];

            $item_remarks = $itemRemarks[$index] ?? "";

            $ledgers[] = $ledger_id;
            $update = new ItemDetail;

            $update->voucher_id = $id;
            $update->ledger_id = $ledger_id;
            $update->debit_amt = $debitAmts[$index] ?? 0;
            $update->credit_amt = $creditAmts[$index] ?? 0;
            $update->cost_center_id = $request->$cost_center_id;
            $update->notes = $request->$notename;
            $update->date = $request->date;
            $update->debit_amt_org = $debitOrg;
            $update->credit_amt_org = $creditOrg;
            $update->debit_amt_comp = $debitComp;
            $update->credit_amt_comp = $creditComp;
            $update->debit_amt_group = $debitGroup;
            $update->credit_amt_group = $creditGroup;
            $update->ledger_parent_id = $parent_ledger_id;
            $update->remarks = $item_remarks;

            $update->opening = 0;
            $update->closing = 0;
            $update->opening_type = null;
            $update->closing_type = null;
            $update->organization_id = $voucher->id;
            $update->group_id = $voucher->group_id;
            $update->company_id = $voucher->group_id;
            $update->save();
        }
        }

        // Get the date after which we need to update the transactions
        $carbonDate1 = Carbon::parse($lastDate);
        $carbonDate2 = Carbon::parse($request->date);
        // Compare the dates and get the older one
        $olderDate = $carbonDate1->lt($carbonDate2) ? $carbonDate1 : $carbonDate2;

        // Output the older date
        $lastDate = $olderDate->format('Y-m-d');

        // Update opening and closing after update
        foreach ($ledgers as $ledger) {
            ItemDetail::where('ledger_id', $ledger)->orderBy('date', 'asc')->whereDate('date', '>=', $lastDate)->each(function ($items) use ($ledger) {

                $debit = $items->debit_amt;
                $credit = $items->credit_amt;

                $opening = 0;
                $closing = 0;
                $openingType = null;
                $closingType = null;

                // Calculate the new Closing Balance and Closing Type
                if ($debit > $credit) {
                    $closingType = 'Dr';
                } else {
                    $closingType = 'Cr';
                }
                $closing = $debit - $credit;
                $closing = $closing < 0 ? -$closing : $closing;

                // Retrieve the latest ItemDetail for the current ledger_id
                $lastItemDetail = ItemDetail::where('ledger_id', $ledger)->whereDate('date', '<', $items->date)->orderBy('date', 'desc')->first();
                if ($lastItemDetail) {
                    // Calculate the new opening balance and type
                    $opening = $lastItemDetail->closing;
                    $openingType = $lastItemDetail->closing_type;

                    // Calculate the new closing type and balance
                    if ($lastItemDetail->closing > 0) {
                        if ($lastItemDetail->closing_type == $closingType) {
                            $closing = $closing + $lastItemDetail->closing;
                        } else {
                            $difference = $closing - $lastItemDetail->closing;
                            if ($difference != 0) {
                                $difference = $difference > 0 ? $difference : -$difference;
                                if ($closing < $lastItemDetail->closing) {
                                    $closingType = $lastItemDetail->closing_type;
                                }
                                $closing = $difference;
                            } else {
                                $closing = 0;
                                $closingType = null;
                            }
                        }
                    }
                }

                $items->update([
                    'opening' => $opening,
                    'closing' => $closing,
                    'opening_type' => $openingType,
                    'closing_type' => $closingType,
                ]);
            });
        }

        return redirect()->route("vouchers.index")->with('success', 'Voucher updated successfully.');
    }
    public function getLedgerGroups(Request $request)
    {
        $ledgerId = $request->input('ledger_id');
        $ledger = Ledger::find($ledgerId);
        $excludeIds = $request->ids;

        if ($ledger) {
            $groups = $ledger->group();

            if ($groups && $groups instanceof \Illuminate\Database\Eloquent\Collection) {
                $groupItems = $groups->map(function ($group) {
                    return ['id' => $group->id, 'name' => $group->name];
                });
            } else if ($groups) {
                $groupItems = [
                    ['id' => $groups->id, 'name' => $groups->name],
                ];
            } else {
                $groupItems = [];
            }
            $filteredItems=$groupItems;
            if ($excludeIds) {
                if ($filteredItems instanceof \Illuminate\Support\Collection) {
                    // If it's a collection, use filter
                    $filteredItems = $filteredItems->filter(function ($item) use ($excludeIds) {
                        return !in_array($item['id'], $excludeIds);
                    });
                } else {
                    // If it's an array, use array_filter
                    $filteredItems = array_filter($filteredItems, function ($item) use ($excludeIds) {
                        return !in_array($item['id'], $excludeIds);
                    });
                    // Reindex the array to avoid sparse keys
                    $filteredItems = array_values($filteredItems);
                }
            }

            return response()->json($filteredItems);
        }

        return response()->json([], 404);
    }
}
