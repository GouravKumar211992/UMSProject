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
use App\Models\User;
use App\Models\Employee;
use App\Models\ErpLoanAppraisal;
use App\Models\RecoveryScheduleLoan;
use Illuminate\Support\Facades\Auth;
use App\Models\LoanSettlement;
use App\Models\LoanSettlementSchedule;
use App\Models\LoanSettlementDoc;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use App\Helpers\FinancialPostingHelper;

class LoanSettlementController extends Controller
{
    public static $user_id;
    public function __construct()
    {
        self::$user_id = parent::authUserId();
    }
    public function index(Request $request)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }

        if ($request->ajax()) {

            $loans = LoanSettlement::with('homeLoan.loanAppraisal')
            ->whereHas('homeLoan', function ($query) {
                $query->whereNotNull('settle_status');
            })->withwhereHas('homeLoan.loanDisbursements', function ($query) {
                $query->where('approvalStatus', 'Disbursed');
            })->whereHas('homeLoan.loanAppraisal')->select('erp_loan_settlements.*')->orderBy('id', 'desc');
            if ($request->ledger || $request->type || $request->keyword) {
                $loans->leftJoin('erp_home_loans', 'erp_home_loans.id', '=', 'erp_loan_settlements.home_loan_id');
            }

            if ($request->has('keyword')) {
                $keyword = trim($request->keyword);
                if ($request->ledger || $request->type || $request->keyword) {
                    $loans->where(function ($query) use ($keyword) {
                        $query->where('erp_home_loans.appli_no', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_loan_settlements.settle_document_no', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_home_loans.name', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_home_loans.mobile', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_home_loans.loan_amount', 'like', '%' . $keyword . '%')
                            ->orWhere('erp_loan_settlements.settle_amnnt', 'like', '%' . $keyword . '%');

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
                $loans->where('erp_loan_settlements.status', $request->status);
            }
            if ($request->date) {
                $dates = explode(' to ', $request->date);
                $start = date('Y-m-d', strtotime($dates[0]));
                $end = date('Y-m-d', strtotime($dates[1]));
                $loans->whereDate('erp_loan_settlements.created_at', '>=', $start)->whereDate('erp_loan_settlements.created_at', '<=', $end);
            }
            if (!empty($organization_id)) {
                $loans->where('erp_loan_settlements.organization_id', $organization_id);
            }
            $loans = $loans->get();

            return DataTables::of($loans)
                ->addColumn('appli_no', function ($loan) {
                    return $loan->homeLoan->appli_no ? $loan->homeLoan->appli_no : '-';
                })
                ->addColumn('settle_document_no', function ($loan) {
                    return $loan->settle_document_no ? $loan->settle_document_no : '-';
                })
                ->addColumn('created_at', function ($loan) {
                    return $loan->created_at ? $loan->created_at : '-';
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
                ->addColumn('recovery_amnt', function ($loan) {
                    return Helper::formatIndianNumber($loan->rec_amnt) ?? 0;
                })
                ->addColumn('bal_amnt', function ($loan) {
                    return Helper::formatIndianNumber($loan->settle_bal_loan_amnnt) ?? 0;
                })

                ->addColumn('settle_amnnt', function ($loan) {
                    return $loan->settle_amnnt ? Helper::formatIndianNumber($loan->settle_amnnt) : '-';
                })
                ->addColumn('status', function ($loan) {
                    if ($loan->document_status == "assessed" ||  $loan->document_status == "Assessed") {
                        $status = '<span class="badge rounded-pill badge-light-info badgeborder-radius">'.$loan->document_status.'</span>';
                    } elseif ($loan->document_status == 'approved' || $loan->document_status == 'approval_not_required' || $loan->document_status == 'partially_approved' || $loan->document_status == 'Approved' ) {
                        $status = '<span class="badge rounded-pill badge-light-success badgeborder-radius">'.$loan->document_status.'</span>';
                    } elseif ($loan->document_status == "rejected" || $loan->document_status == "Rejected" ) {
                        $status = '<span class="badge rounded-pill badge-light-danger badgeborder-radius">'.$loan->document_status.'</span>';
                    } else {
                        $status = '<span class="badge rounded-pill badge-light-warning badgeborder-radius">'.$loan->document_status.'</span>';
                    }
                    return $status;
                })
                ->editColumn('created_at', function ($data) {
                    $formatedDate = Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d-m-Y');
                    return $formatedDate;
                })
                ->addColumn('action', function ($loan) {
                    $view_route = '';
                    if ($loan->homeLoan->type == 1) {
                        $view_route = 'loan.view_all_detail';
                    } elseif ($loan->homeLoan->type == 2) {
                        $view_route = 'loan.view_vehicle_detail';
                    } else {
                        $view_route = 'loan.view_term_detail';
                    }
                    $view_url = route($view_route, $loan->homeLoan->id);
                    return '<td><a href="' . route('loan.settlement.view', ['id' => $loan->id]) . '"><i data-feather="eye"></i></a></td>';;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        } else {
            $loans = LoanSettlement::with('homeLoan')->where('organization_id', $organization_id)->orderBy('id', 'desc');
        }
        $customer_names = HomeLoan::select('id', 'name')->orderBy('id', 'desc')->get();

        return view('loan.settlement.index', compact('loans', 'customer_names'));
    }

    public function add()
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }
        $applicants = HomeLoan::with(['loanAppraisal.recovery', 'loanDisbursements', 'recoveryLoan'])
            ->withwhereHas('loanDisbursements', function ($query) {
                $query->where('approvalStatus', 'Disbursed');
            })
            ->whereHas('loanAppraisal.recovery')
            ->where('status', '!=', 3)
            ->where('approvalStatus','Disbursed')
            ->where('organization_id', $organization_id)->get();

            $parentURL = request() -> segments()[1];
        
            $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
            if (count($servicesBooks['services']) == 0) {
               return redirect() -> route('/');
           }
           $firstService = $servicesBooks['services'][0];
           $book_type = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        
           return view('loan.settlement.add', compact('applicants', 'book_type'));
    }
    public function view($id)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }
        $applicants = HomeLoan::with(['loanAppraisal.recovery' => function ($query) {
            $query->orderBy('repayment_amount', 'asc'); // Order by repayment_amount in ascending order
        }, 'loanDisbursements', 'recoveryLoan'])
            ->withwhereHas('loanDisbursements', function ($query) {
                $query->where('approvalStatus', 'Disbursed');
            })
            ->whereHas('loanAppraisal.recovery')
            ->where('status', '!=', 3)
            ->where('organization_id', $organization_id)->get();



        $data = LoanSettlement::with('homeLoan','schedules')->find($id);

        $parentURL = request() -> segments()[1];
        
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $book_type = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
        $type = $data->loanable_type === 'App\Models\Employee' || $data->loanable_type === 'employee'
        ? 'employee'
        : 'user';


        $buttons = Helper::actionButtonDisplayForLoan($data->book_id, $data->document_status,$data->id,$data->settle_amnnt,$data->approval_level,$data->loanable_id,$type);


   return view('loan.settlement.view', compact('data', 'applicants', 'book_type','buttons'));
    }


    public function save(Request $request)
    {
        $user_id = Helper::getAuthenticatedUser()->id;
        $u_type = Helper::userCheck()['type'];
        $request->validate([
            'settle_document_no' => ['required'],
            'settle_customer' => 'required',
            'settle_loan_type' => 'required',
            'settle_bal_loan_amnnt' => 'required',
            'settle_wo_amnnt' => 'required',
            'settle_amnnt' => 'required'
        ], [
            'settle_document_no.required' => 'The Document number is required.',
            'settle_customer.required' => 'The Customer is required.',
            'settle_loan_type.required' => 'The Loan Type is required.',
            'settle_bal_loan_amnnt.required' => 'The Balance Loan Amount is required.',
            'settle_wo_amnnt.required' => 'The Write off Amount is required.',
            'settle_amnnt.required' => 'Settlement Amount is Required'
        ]);

        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
        } else {
            $organization_id = 1;
        }
        // Calculate the sum of all schedule amounts
        $settlement_schedule = $request->input('Settlement', []);
        $settle_amnt = $request->input('settle_amnnt', 0); // Get the settlement amount

        // Ensure the sum of all schedule amounts does not exceed the settlement amount
        $total_schedule_amnt = array_sum($settlement_schedule['schedule_amnt']);

        if ($total_schedule_amnt > $settle_amnt) {
            return redirect()->route('loan.settlement.add')->with('error', 'The total of schedule amounts cannot be greater than the settlement amount!');
        }
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id =  $organization->company_id;

        $data = LoanSettlement::create([
            'home_loan_id' => $request->settle_application_no ?? null,
            'book_id' => $request->book_id ?? null,
            'group_id' => $group_id,
            'company_id' => $company_id,

            'document_date' => Carbon::now()->format('Y-m-d'),
            'doc_number_type' => $request->input('doc_number_type'),
            'doc_reset_pattern' => $request->input('doc_reset_pattern'),
            'doc_prefix' => $request->input('doc_prefix'),
            'doc_suffix' => $request->input('doc_suffix'),
            'doc_no' => $request->input('doc_no'),
            'loanable_id'=>$user_id,
            'loanable_type'=>$u_type,
            'settle_document_no' => $request->settle_document_no ?? null,
            'settle_application_no' => $request->settle_application_no ?? null,
            'rec_amnt' => $request->rec_amnt ?? 0,
            'rec_intrst' => $request->rec_intrst ?? 0,
            'settle_bal_loan_amnnt' => $request->settle_bal_loan_amnnt ?? null,
            //'settle_prin_bal_amnnt' => $request->settle_prin_bal_amnnt ?? null,
            'settle_intr_bal_amnnt' => $request->settle_intr_bal_amnnt ?? null,
            'settle_amnnt' => $request->settle_amnnt ?? null,
            'settle_wo_amnnt' => $request->settle_wo_amnnt ?? null,
            'remarks' => $request->remarks ?? null,
            'status' => 0,
            'document_status'=>ConstantHelper::ASSESSED,
            'organization_id' => $organization_id
        ]);

        $data = LoanSettlement::find($data->id);
        $status = ConstantHelper::ASSESSED ? Helper::checkApprovalLoanRequired($data->book_id) : ConstantHelper::ASSESSED;
        $data->document_status = $status;
        $data->save();
        if($status==ConstantHelper::ASSESSED){
            if ($data->approvelworkflow->count() > 0) { // Check if the relationship has records
                foreach ($data->approvelworkflow as $approver) {
                    if ($approver->user) { // Check if the related user exists
                        $approver_user = $approver->user;
                        LoanNotificationController::notifyLoanSettleSubmission($approver_user, $data);
                    }
                }
            }
        }
        if ($request->hasFile('st_docs')) {
            $files = $request->file('settle_docs');

            foreach ($files as $file) {
                $filePath = $file->store('loan_documents', 'public');

                LoanSettlementDoc::create([
                    'loan_settlement_id' => $data->id,
                    'doc' => $filePath
                ]);
            }
        }

        if ($data) {
            $settlement_schedule = $request->input('Settlement', []);
            if (count($settlement_schedule) > 0) {
                LoanSettlementSchedule::where('loan_settlement_id', $data->id)->delete();
                foreach ($settlement_schedule['schedule_date'] as $index => $schedule_date) {

                    $schedule = LoanSettlementSchedule::create([
                        'loan_settlement_id' => $data->id,
                        'schedule_date' => $settlement_schedule['schedule_date'][$index] ?? null,
                        'schedule_amnt' => $settlement_schedule['schedule_amnt'][$index] ?? null
                    ]);
                }
            }
        }

        $loan_type = explode(' ', $request->settle_loan_type);
        $loanApplicationLog = LoanApplicationLog::create([
            'loan_application_id' => $request->settle_application_no,
            'loan_type' => strtolower($loan_type[0]),
            'action_type' => 'settlement',
            'user_id' => self::$user_id,
            'remarks' => null
        ]);
//dd($request->settle_amnnt);
        $home_loan = HomeLoan::find($request->settle_application_no);
        //dd($home_loan->recovery_loan_amount);
        $home_loan->recovery_loan_amount = round((int)(Helper::removeCommas($home_loan->recovery_loan_amount??0))+ (Helper::removeCommas($request->settle_amnnt)),2);
        $home_loan->settle_status = 1;
        if(((int) Helper::removeCommas($request->settle_amnnt))<=0)
        $home_loan->approvalStatus="completed";
        $home_loan->save();


        $organization = Organization::getOrganization();
        $book_type = (int) $request->book_id;
        if ($organization) {
            NumberPattern::incrementIndex($organization->id, $book_type);
        }

        return redirect()->route('loan.settlement')->with('success', 'Settlement Added Successfully!');
    }
    public function ApprReject(Request $request)
    {
        if (empty($request->checkedData)) {
            return redirect("loan/settlement")->with('error', 'No Data found for Approve/Reject');
        }
        $app_rej = $request->checkedData;


        $multi_files = [];
        $data = LoanSettlement::find($app_rej);
        $store_settle_appr_docData = $request->store_settle_appr_doc;


        if ($request->hasFile('st_appr_doc') && !$request->has('store_settle_appr_doc')) {
            if ($request->hasFile('st_appr_doc')) {
                $files = $request->file('st_appr_doc');
                foreach ($files as $file) {
                    $filePath = $file->store('loan_documents', 'public');
                    $multi_files[] = $filePath;
                }
            }
            $store_settle_appr_docData = (count($multi_files) > 0) ? json_encode($multi_files) : '[]';
        }
        $approveDocument = Helper::approveDocument($data->book_id, $data->id, 0, $request->st_appr_remark, $multi_files, $data->approval_level, $request->st_appr_status);


        $data = LoanSettlement::updateOrCreate([
            'id' => $app_rej
        ], [
            'settle_appr_doc' => $store_settle_appr_docData,
            'settle_appr_remark' => $request->st_appr_remark ?? null,
            'document_status' => $approveDocument['approvalStatus'],
            'approval_level' => $approveDocument['nextLevel'] ?? $data->approvalLevel
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

        if ($request->st_appr_status == "approve") {
            LoanNotificationController::notifyLoanSettleApproved($creator, $data);
            return redirect("loan/settlement")->with('success', 'Approved Successfully!');
        } else {
            LoanNotificationController::notifyLoanSettleReject($creator, $data);
            return redirect("loan/settlement")->with('success', 'Rejected Successfully!');
        }
    }

    public function loanGetCustomer(Request $request)
    {
        $id = $request->id;


        $customer_record = HomeLoan::where('id', $id)
            ->whereHas('loanAppraisal.recovery')
            ->with([
                'loanAppraisal.recovery',
                'loanSettlement',
                'recoveryLoan',
                'loanDisbursements' => function ($query) {
                    $query->where('approvalStatus', 'Disbursed')
                        ->orderBy('created_at', 'asc');
                }
            ])->whereIn('type', [1, 2, 3])

            ->first();





        return response()->json(['customer_record' => $customer_record]);
    }
    public function PostingDetails($id)
    {
        $request = LoanSettlement::findOrFail($id);
            $data = FinancialPostingHelper::loanSettleVoucherPosting($request->book_id ?? 0, $request->id ?? 0, "get",$request->remarks??"");
             return response() -> json([
                'status' => 'success',
                'data' => $data
            ]);


    }

    public function getPostingDetails(Request $request)
    {
            try{
                $data = FinancialPostingHelper::loanSettleVoucherPosting($request->book_id ?? 0, $request->document_id ?? 0, "get",$request->remarks);
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
            $data = FinancialPostingHelper::loanSettleVoucherPosting($request->book_id ?? 0, $request->document_id ?? 0, "post",$request->remarks);
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
