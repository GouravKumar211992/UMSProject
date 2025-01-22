<?php

namespace App\Http\Controllers\LoanManagement;

use App\Models\ErpLoanAppraisalRecovery;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\LoanDisbursement;
use App\Models\HomeLoan;
use App\Models\Organization;
use App\Models\RecoveryLoan;
use Illuminate\Http\Request;
use App\Models\NumberPattern;
use App\Models\LoanManagement;
use App\Helpers\ConstantHelper;
use App\Models\RecoveryLoanDoc;
use App\Models\Bank;
use App\Models\LoanApplicationLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ErpLoanAppraisal;
use App\Models\RecoveryScheduleLoan;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Employee;
use App\Models\User;
use App\Models\OrganizationBookParameter;
use App\Helpers\ServiceParametersHelper;
use App\Helpers\FinancialPostingHelper;
use Exception;
class LoanRecoveryController extends Controller
{
    public static $user_id;
    public function __construct()
    {
        self::$user_id = parent::authUserId();
    }

    public function recovery(Request $request)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }


        if ($request->ajax()) {
            $loans = RecoveryLoan::with('homeLoan.loanAppraisal')
                //->whereIn('approvalStatus', ['Submitted', 'Rejected', 'Approved'])
                ->whereNotNull('book_id')
                ->where('organization_id', $organization_id)
                ->select('erp_recovery_loans.*')
                ->whereHas('homeLoan', function ($query) {
                    $query->whereIn('approvalStatus', ['Disbursed', 'completed']);
                })->orderBy('id', 'desc');

            if ($request->ledger || $request->type || $request->keyword) {
                $loans->leftJoin('erp_home_loans', 'erp_home_loans.id', '=', 'erp_recovery_loans.home_loan_id');
            }

            if ($request->has('keyword')) {
                $keyword = trim($request->keyword);
                if ($request->ledger || $request->type || $request->keyword) {
                    $loans->where(function ($query) use ($keyword) {
                        $query->where('erp_home_loans.appli_no', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_recovery_loans.document_no', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_home_loans.name', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_home_loans.mobile', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_home_loans.loan_amount', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_home_loans.ass_recom_amnt', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_recovery_loans.recovery_amnnt', 'like', '%' . $keyword . '%');

                        if (strtolower($keyword) === 'home') {
                            $query->orWhere('erp_home_loans.type', 1);
                        } elseif (strtolower($keyword) === 'vehicle') {
                            $query->orWhere('erp_home_loans.type', 2);
                        } elseif (strtolower($keyword) === 'term') {
                            $query->orWhere('erp_home_loans.type', 3);
                        }
                    });
                }
            }

            if ($request->ledger) {
                $loans->where('erp_home_loans.name', 'like', '%' . $request->ledger . '%');
            }
            if ($request->type) {
                $loans->where('erp_home_loans.type', $request->type);
            }
            if ($request->status) {
                $loans->where('erp_recovery_loans.approvalStatus', $request->status);
            }
            if ($request->date) {
                $dates = explode(' to ', $request->date);
                $start = date('Y-m-d', strtotime($dates[0]));
                $end = date('Y-m-d', strtotime($dates[1]));
                $loans->whereDate('erp_recovery_loans.created_at', '>=', $start)->whereDate('erp_recovery_loans.created_at', '<=', $end);
            }
            if (!empty($organization_id)) {
                $loans->where('erp_recovery_loans.organization_id', $organization_id);
            }
            $loans = $loans->get();

            return DataTables::of($loans)
                ->addColumn('appli_no', function ($loan) {
                    return $loan->homeLoan->appli_no ? $loan->homeLoan->appli_no : '-';
                })
                ->addColumn('document_no', function ($loan) {
                    return $loan->document_no ? $loan->document_no : '-';
                })
                ->addColumn('payment_date', function ($loan) {
                    return $loan->payment_date ? \Carbon\Carbon::parse($loan->payment_date)->format('d-m-Y') : '-';
                })
                ->addColumn('name', function ($loan) {
                    return $loan->homeLoan->name ? $loan->homeLoan->name : '-';
                })
                ->addColumn('mobile', function ($loan) {
                    return $loan->homeLoan->mobile ? $loan->homeLoan->mobile : '-';
                })
                ->addColumn('type', function ($loan) {
                    if ($loan->homeLoan->type == 1) {
                        $type = 'Home';
                    } elseif ($loan->homeLoan->type == 2) {
                        $type = 'Vehicle';
                    } else {
                        $type = 'Term';
                    }


                    return $type;
                })
                ->addColumn('loan_amount', function ($loan) {
                    return $loan->homeLoan->loanAppraisal->term_loan ? Helper::formatIndianNumber($loan->homeLoan->loanAppraisal->term_loan) : '-';
                })
                ->addColumn('recovery_amnnt', function ($loan) {
                    return Helper::formatIndianNumber($loan->rec_principal_amnt);
                })
                ->addColumn('rec_principal_amnt', function ($loan) {
                    return Helper::formatIndianNumber($loan->balance_amount);
                })
                ->addColumn('status', function ($loan) {
                    if ($loan->approvalStatus == "Rejected" || $loan->approvalStatus == "rejected")
                        $status = '<span class="badge rounded-pill badge-light-danger badgeborder-radius">' . $loan->approvalStatus . '</span>';
                    else if ($loan->approvalStatus == "Approved" || $loan->approvalStatus == "approved" || $loan->approvalStatus == "approval_not_required" ||   $loan->approvalStatus == "partially_approved")
                        $status = '<span class="badge rounded-pill badge-light-success badgeborder-radius">' . $loan->approvalStatus . '</span>';
                    else
                        $status = '<span class="badge rounded-pill badge-light-info badgeborder-radius">' . $loan->approvalStatus . '</span>';

                    return $status;
                })
                ->editColumn('created_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
                    return $formatedDate;
                })
                ->addColumn('action', function ($loan) {
                    return '<td><a href="' . route('loan.recovery_view', ['id' => $loan->id]) . '"><i data-feather="eye"></i></a></td>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        } else {
            $loans = RecoveryLoan::with('homeLoan')->where('organization_id', $organization_id)->orderBy('id', 'desc');
        }
        $customer_names = HomeLoan::select('id', 'name')->orderBy('id', 'desc')->get();

        return view('loan.recovery', compact('loans', 'customer_names'));
    }

    public function addRecovery()
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }

        $applicants = HomeLoan::with([
            'loanAppraisal.recovery' => function ($query) {
                $query->orderBy('repayment_amount', 'asc'); // Order by repayment_amount in ascending order
            },
            'loanDisbursements',
            'recoveryLoan'
        ])->whereHas('series')
            ->withwhereHas('loanDisbursements')
            ->where('approvalStatus', 'Disbursed')
            ->whereHas('loanAppraisal.recovery')
            ->where('organization_id', $organization_id)->get();

           
            $parentURL = request() -> segments()[1];
        
            $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
            if (count($servicesBooks['services']) == 0) {
               return redirect() -> route('/');
           }
           $firstService = $servicesBooks['services'][0];
           $book_type = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
            
           //dd($book_type);
        $banks = Bank::where('status', 'active')->get();

        return view('loan.add_recovery', compact('banks', 'applicants', 'book_type'));
    }

    public function viewRecovery($id)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }

        $data = RecoveryLoan::find($id);

        $applicants = HomeLoan::with([
            'loanAppraisal.recovery' => function ($query) {
                $query->orderBy('repayment_amount', 'asc'); // Order by repayment_amount in ascending order
            },
            'loanDisbursements',
            'recoveryLoan'
        ])

            ->withwhereHas('loanDisbursements', function ($query) {
                $query->where('approvalStatus', 'Disbursed');
            })->get();



        $loan = HomeLoan::find($data->home_loan_id);

        $parentURL = request() -> segments()[1];
        
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $book_type = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        
       $banks = Bank::where('status', 'active')->get();
        $disbursementIds = json_decode($data->dis_id, true) ?? null;
        if ($disbursementIds)
            $disburse = LoanDisbursement::whereIn('id', $disbursementIds)->get();
        else
            $disburse = "";

            $type = $data->loanable_type==='App\Models\Employee'?'employee':'user';

        $buttons = Helper::actionButtonDisplayForLoan($data->book_id, $data->document_status,$data->id,$data->recovery_amnnt,$data->approval_level,$data->loanable_id,$type);

        return view('loan.view_recovery', compact('loan', 'disburse', 'data', 'banks', 'applicants', 'book_type', 'buttons'));
    }

    public function getPrincipalInterest(Request $request)
    {
        $loan_id = $request->applicants;
        $intrest_amount = 0;

        if ($request->has('exceed_days') && $request->exceed_days !== null) {
            $exceed_days = $request->exceed_days;
            $amount = $request->dis_amount;
            $intrest_rate = HomeLoan::where('id', $loan_id)->with('loanAppraisal', function ($query) use ($loan_id) {
                $query->where('loan_id', $loan_id);
            })->first();
            $intrest_rate = $intrest_rate->loanAppraisal->interest_rate;


            $intrest_amount = ($intrest_rate / 365) * ($exceed_days);

            $intrest_amount = round($amount * ($intrest_amount / 100), 2);
        }

        return response()->json(['amount' => $intrest_amount]);
    }

    public function recoveryAddUpdate(Request $request)
    {

        $disbursementData = $request->input('disbursementData');

        $request->validate([
            'recovery_amnnt' => 'required',
            'rec_intrst' => 'required',
            'ref_no' => 'required',
            'document_no' => ['required'],
            'cus_tomer' => 'required',
            'loan_type' => 'required',
            'loan_amount' => 'required',
            'balance_amount' => 'required',
            'bal_intrst_amnt' => 'required',
        ], [
            'recovery_amnnt.required' => 'The Recovery amount is required.',
            'recovery_amnnt.numeric' => 'The Recovery amount must be a number.',
            'rec_interest_amnt.required' => 'The Received Interest Amount is required.',
            'rec_interest_amnt.numeric' => 'The Received Interest Amount must be a number.',
            'ref_no.required' => 'The Reference No. is required.',
            'ref_no.numeric' => 'The Reference No. must be a number.',
            'document_no.required' => 'The Document number is required.',
            'cus_tomer.required' => 'The Customer is required.',
            'loan_type.required' => 'The Loan Type is required.',
            'loan_amount.required' => 'The Loan Amount is required.',
            'balance_amount.required' => 'The Balance Principal is required.',
            'bal_intrst_amnt.required' => 'The Balance Interest is required.'
        ]);

        $fieldsToSanitize = [
            "recovery_remain",
            "current_settled",
            "loan_amount",
            "dis_amount",
            "rec_amnt",
            "rec_intrst",
            "balance_amount",
            "blnc_amnt",
            "bal_intrst_amnt",
            "recovery_amnnt",
            "settled_amnt",
        ];

        foreach ($fieldsToSanitize as $field) {
            if (isset($request[$field])) {
                $request[$field] = Helper::removeCommas($request[$field]);
            }
        }

        $disbursementData = $request->get('disbursementData');

        if (is_array($disbursementData)) {
            foreach ($disbursementData as $key => $disbursement) {
                foreach ($disbursement as $subKey => $value) {
                    if (is_string($value)) {
                        $disbursementData[$key][$subKey] = Helper::removeCommas($value);
                    }
                }
            }
            // Update the `disbursementData` in the request
            $request->merge(['disbursementData' => $disbursementData]);
        }

        $home_loan = HomeLoan::find($request->application_no);

        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }


        $status = ConstantHelper::ASSESSED;


        $finalJsonData = null;
        $userData = Helper::userCheck();
        $home_loan = HomeLoan::find($request->application_no);
        if ($request->blnc_amnt > $request->recovery_amnnt)
            $balance_amount = $home_loan->settle_status == 1 ? ($request->blnc_amnt ?? 0) - ($request->recovery_amnnt ?? 0) : ($request->balance_amount ?? 0);
        else
            $balance_amount = $home_loan->settle_status == 1 ? 0 : ($request->balance_amount ?? 0);
        $updatedDisbursements = [];
        if ($home_loan->settle_status != 1) {
            $disburse[] = "";


            foreach ($disbursementData as $data) {
                $recoveryStatus = $data['balance_amount'] > 0 ? 'partial_recover' : 'fully_recovered';
                $disbur = LoanDisbursement::find($data['dis_id']); // Find the loan disbursement by its ID
                $disbur->recovery_status = $recoveryStatus;
                $disbur->balance = $data['balance_amount'];
                $disbur->recovered = $data['recovered_amount'];
                $disbur->interest = $data['interest_amount'];
                $disbur->settled_interest = $data['settled_interest'];
                $disbur->settled_principal = $data['settled_principal'];
                $disbur->remaining = $data['remaining'];
                $disbur->recovery_date = $request->payment_date;

                // Optionally save the changes
                $disbur->save();
                $updatedDisbursements[] = [
                    'dis_id' => $data['dis_id'],
                    'disbursed' => $data['disbursed'],
                    'recovery_status' => $recoveryStatus,
                    'balance' => $data['balance_amount'],
                    'recovered' => $data['recovered_amount'],
                    'interest' => $data['interest_amount'],
                    'settled_interest' => $data['settled_interest'],
                    'settled_principal' => $data['settled_principal'],
                    'remaining' => $data['remaining'],
                    'recovery_date' => $request->payment_date,
                ];

                $disburse[] = $disbur;
            }
            $finalJsonData = json_encode($updatedDisbursements);
        }
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;

        $data = RecoveryLoan::create([
            'home_loan_id' => $request->application_no ?? null,
            'book_id' => $request->book_id ?? null,
            'settle_status' => $home_loan->settle_status ?? 0,

            'group_id' => $group_id,
            'company_id' => $company_id,

            'document_date' => Carbon::now()->format('Y-m-d'),
            'document_no' => $request->input('document_no'),
            'doc_number_type' => $request->input('doc_number_type'),
            'doc_reset_pattern' => $request->input('doc_reset_pattern'),
            'doc_prefix' => $request->input('doc_prefix'),
            'doc_suffix' => $request->input('doc_suffix'),
            'doc_no' => $request->input('doc_no'),

            'rec_principal_amnt' => $request->rec_amnt ?? null,
            'rec_interest_amnt' => $request->rec_intrst ?? null,

            'settled_principal' => $request->settled_principal ?? null,
            'settled_interest' => $request->settled_interest ?? null,

            'balance_amount' => $balance_amount ?? null,
            'bal_interest_amnt' => $request->bal_intrst_amnt ?? null,
            'account_number' => $request->account_number ?? null,

            'settled_blnc_amnt' => $request->settled_blnc_amnt ?? 0,
            'settled_amnt' => $request->settled_amnt ?? 0,
            'settled_rec_amnt' => $request->settled_rec_amnt ?? 0,

            'dis_amount' => $request->dis_amount ?? null,
            'dis_detail' => $finalJsonData,
            'dis_id' => json_encode($request->dis_id),
            'application_no' => $request->application_no ?? null,
            'recovery_amnnt' => $request->recovery_amnnt ?? null,
            'payment_date' => $request->payment_date ?? null,
            'payment_mode' => $request->payment_mode ?? null,
            'ref_no' => $request->ref_no ?? null,
            'bank_name' => $request->bank_name ?? null,
            'remarks' => $request->remarks ?? null,
            'approvalStatus' => $status,
            'approvalLevel'=>1,
            'organization_id' => $organization_id,
            'loanable_id' => $userData['user_id'],
            'loanable_type' => $userData['user_type']
        ]);

        $data = RecoveryLoan::find($data->id);
        $status = ConstantHelper::ASSESSED ? Helper::checkApprovalLoanRequired($data->book_id) : ConstantHelper::ASSESSED;
        $data->approvalStatus = $status;
        $data->save();

        if($status == ConstantHelper::ASSESSED){
            if ($data->approvelworkflow->count() > 0) { // Check if the relationship has records
                foreach ($data->approvelworkflow as $approver) {
                    if ($approver->user) { // Check if the related user exists
                        $approver_user = $approver->user;
                        LoanNotificationController::notifyLoanRecoverSubmission($approver_user, $data);
                    }
                }
            }
        }




        $home_loan->recovery_pa = Helper::removeCommas($request->rec_amnt) ?? 0;
        $home_loan->recovery_ia = Helper::removeCommas($request->rec_intrst) ?? 0;
        $home_loan->recovery_total = (Helper::removeCommas($request->rec_amnt) ?? 0) + (Helper::removeCommas($request->rec_intrst) ?? 0);
        if ($request->balance_amount <= 0)
            $home_loan->approvalStatus = "completed";

        if ($home_loan->settle_status != 1)
            $home_loan->recovery_loan_amount = (round($home_loan->recovery_loan_amount, 2) ?? 0) + ($request->current_settled ?? 0);
        else
            $home_loan->recovery_loan_amount = (round($home_loan->recovery_loan_amount, 2) ?? 0) + ($request->recovery_amnnt ?? 0);



        $home_loan->save();
        /*
        $recovery_schedule_loan = RecoveryScheduleLoan::find($request->recoveryScheduleID);
        $recovery_schedule_loan->recovery_status = 1;
        $recovery_schedule_loan->save();*/

        if ($request->hasFile('recovery_docs')) {
            $files = $request->file('recovery_docs');

            foreach ($files as $file) {
                $filePath = $file->store('loan_documents', 'public');

                RecoveryLoanDoc::create([
                    'recovery_loan_id' => $data->id,
                    'doc' => $filePath
                ]);
            }
        }


        $loan_type = explode(' ', $request->loan_type);
        $loanApplicationLog = LoanApplicationLog::create([
            'loan_application_id' => $request->application_no,
            'loan_type' => strtolower($loan_type[0]),
            'action_type' => 'recover',
            'user_id' => self::$user_id,
            'remarks' => null
        ]);

        $organization = Organization::getOrganization();
        $book_type = (int) $request->book_id;
        if ($organization) {
            NumberPattern::incrementIndex($organization->id, $book_type);
        }
        //dd($data,$home_loan);

        return redirect()->route('loan.recovery')->with('success', 'Recovery Added Successfully!');
    }
    public function RecoveryApprReject(Request $request)
    {
        if (empty($request->checkedData)) {
            return redirect("loan/recovery")->with('error', 'No Data found for Approve/Reject');
        }
        $app_rej = $request->checkedData;

        $multi_files = [];
        $data = RecoveryLoan::find($app_rej);
        $store_rec_appr_docData = $request->store_rec_appr_doc;


        if ($request->hasFile('rc_appr_doc') && !$request->has('store_rec_appr_doc')) {
            if ($request->hasFile('rc_appr_doc')) {
                $files = $request->file('rc_appr_doc');
                foreach ($files as $file) {
                    $filePath = $file->store('loan_documents', 'public');
                    $multi_files[] = $filePath;
                }
            }
            $store_rec_appr_docData = (count($multi_files) > 0) ? json_encode($multi_files) : '[]';
        }
        $approveDocument = Helper::approveDocument($data->book_id, $data->id, 0, $request->approve_remarks, $multi_files, $data->approvalLevel, $request->rc_appr_status);


        $data = RecoveryLoan::updateOrCreate([
            'id' => $app_rej
        ], [
            'rec_appr_doc' => $store_rec_appr_docData,
            'rec_appr_remark' => $request->rc_appr_remark ?? null,
            'approvalStatus' => $approveDocument['approvalStatus'],
            'approvalLevel' => $approveDocument['nextLevel'] ?? $data->approvalLevel
        ]);
        $creator_type = $data->loanable_type;
        $created_by = $data->loanable_id;
        $creator = null;

        if ($creator_type != null) {
            switch ($creator_type) {
                case 'employee':
                    $creator = Employee::find($created_by);
                    break;

                case 'user':
                    $creator = User::find($created_by);
                    break;

                default:
                    $creator = $creator_type::find($created_by);
                    break;
            }
        }



        if ($request->rc_appr_status == "approve") {
            LoanNotificationController::notifyLoanRecoverApproved($creator, $data);
            return redirect("loan/recovery")->with('success', 'Approved Successfully!');
        } else {
            LoanNotificationController::notifyLoanRecoverReject($creator, $data);
            return redirect("loan/recovery")->with('success', 'Rejected Successfully!');
        }
    }
    public function fetchRecoveryApprove(Request $request)
    {
        $loan_recovery = RecoveryLoan::where('id', $request->recovery_id)->first();
        $html = '';
        if ($loan_recovery->rec_appr_doc) {
            $rec_appr_doc_data = json_decode($loan_recovery->rec_appr_doc, true);
            $store_rec_appr_doc = [];
            if (count($rec_appr_doc_data) > 0) {
                foreach ($rec_appr_doc_data as $key => $val) {
                    $fileExtension = pathinfo($val, PATHINFO_EXTENSION);
                    $formattedExtension = ucfirst(strtolower($fileExtension));
                    $html .= '<a href="' . asset('storage/' . $val) . '" style="color:green; font-size:12px;" target="_blank" download>' . $formattedExtension . ' File</a></p>';
                    $store_rec_appr_doc[] = $val;
                }
                $jsonEncodedFiles = json_encode($store_rec_appr_doc);
                $html .= '<input type="hidden" name="store_rec_appr_doc" value=\'' . $jsonEncodedFiles . '\' class="form-control" />';
            }
        }
        if ($loan_recovery) {
            return response()->json(['success' => 1, 'msg' => 'Loan Recovery Approved Successfully!', 'loan_recovery' => $loan_recovery, 'html' => $html]);
        } else {
            return response()->json(['success' => 0, 'msg' => 'Loan Recovery Not Found!', 'loan_recovery' => $loan_recovery]);
        }
    }
    public function loanGetCustomer(Request $request)
    {
        $id = $request->id;


        $customer_record = HomeLoan::where('id', $id)
            ->with([
                'loanAppraisal.recovery',
                'recoveryLoan',
                'loanSettlement',
                'loanDisbursements' => function ($query) {
                    $query->where('approvalStatus', 'Disbursed')
                        ->orderBy('created_at', 'asc');
                }
            ])
            ->first();





        return response()->json(['customer_record' => $customer_record]);
    }
    public function getPostingDetails(Request $request)
    {
            try{
                $data = FinancialPostingHelper::loanRecoverVoucherPosting($request->book_id ?? 0, $request->document_id ?? 0, "get",$request->remarks);
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
    public function postPostingDetails(Request $request)
    {
        try{
            $data = FinancialPostingHelper::loanRecoverVoucherPosting($request->book_id ?? 0, $request->document_id ?? 0, "post",$request->remarks);
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
}
