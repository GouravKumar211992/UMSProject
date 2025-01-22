<?php

namespace App\Http\Controllers\LoanProgress;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ErpLoanAppraisal;
use App\Models\LoanApproval;
use App\Models\LoanReject;
use App\Models\LoanReturn;
use DB;
use App\Models\LoanDisbursement;
use App\Models\RecoveryLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Book;
use App\Models\HomeLoan;
use App\Models\InterestRateScore;
use App\Models\LoanAssessment;
use App\Models\LoanManagement;
use App\Models\TermLoanPromoter;
use App\Models\VehicleLoan;

class ApprovalController extends Controller
{
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
            $loans = ErpLoanAppraisal::with('loan')
                ->join('erp_home_loans as loan', 'erp_loan_appraisals.loan_id', '=', 'loan.id');
            //orderBy('loan.id', 'desc');
            $loans->where('loan.approvalStatus', 'assessment');

            if ($request->ledger) {
                $loans->where('loan.name', 'like', '%' . $request->ledger . '%');
            }
            if ($request->status) {
                $loans->where('loan.approvalStatus', $request->status);
            }
            if ($request->type) {
                $loans->where('loan.type', $request->type);
            }
            if ($request->date) {
                $dates = explode(' to ', $request->date);
                $start = date('Y-m-d', strtotime($dates[0]));
                $end = date('Y-m-d', strtotime($dates[1]));
                $loans->whereDate('loan.created_at', '>=', $start)->whereDate('created_at', '<=', $end);
            }
            if (!empty($organization_id)) {
                $loans->where('loan.organization_id', $organization_id);
            }
            $loans = $loans->get();
            $sr_no = 1;

            return DataTables::of($loans)
                ->addColumn('sr_no', function () use (&$sr_no) {
                    return $sr_no++; // Increment and return the serial number
                })
                ->addColumn('appli_no', function ($loan) {
                    return $loan->loan->appli_no ? $loan->loan->appli_no : '-';
                })
                ->addColumn('ref_no', function ($loan) {
                    return $loan->loan->ref_no ? $loan->loan->ref_no : '-';
                })
                ->addColumn('proceed_date', function ($loan) {
                    return $loan->loan->proceed_date ? \Carbon\Carbon::parse($loan->loan->proceed_date)->format('d-m-Y') : '-';
                })
                ->addColumn('name', function ($loan) {
                    return $loan->loan->name ? $loan->loan->name : '-';
                })
                ->addColumn('email', function ($loan) {
                    return $loan->loan->email ? $loan->loan->email : '-';
                })
                ->addColumn('mobile', function ($loan) {
                    return $loan->loan->mobile ? $loan->loan->mobile : '-';
                })
                ->addColumn('type', function ($loan) {
                    if ($loan->loan->type == 1) {
                        $type = 'Home';
                    } elseif ($loan->loan->type == 2) {
                        $type = 'Vehicle';
                    } else {
                        $type = 'Term';
                    }
                    return $type;
                })
                ->addColumn('loan_amount', function ($loan) {
                    return $loan->term_loan ? Helper::formatIndianNumber($loan->term_loan) : '-';
                })
                ->addColumn('action', function ($loan) {
                    $view_route = '';
                    if ($loan->loan->type == 1) {
                        $view_route = 'loanApproval.viewHomeLoan';
                    } elseif ($loan->loan->type == 2) {
                        $view_route = 'loanApproval.viewVehicleLoan';
                    } else {
                        $view_route = 'loanApproval.viewTermLoan';
                    }
                    $view_url = route($view_route, $loan->loan->id);

                    return '
                    <a class="dropdown-item" href="' . $view_url . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye me-50"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        } else {
            $loans = ErpLoanAppraisal::with('loan')
                ->select('erp_loan_appraisals.id', 'loan.name')
                ->join('erp_home_loans as loan', 'erp_loan_appraisals.loan_id', '=', 'loan.id')
                ->where('loan.organization_id', $organization_id)
                ->get();
        }

        $loans = ErpLoanAppraisal::with('loan')->orderByDesc('id')->get();

        return view('loanProgress.approval.list', compact('loans'));
    }

    public function viewHomeLoan($id)
    {

        $user = Helper::getAuthenticatedUser();
        $homeLoan = HomeLoan::fetchRecord($id);
        if ($homeLoan && $homeLoan->loanApplicationLog) {
            $logs = $homeLoan->loanApplicationLog->sortByDesc('id');
            $logsGroupedByStatus = $logs->groupBy('action_type');
        } else {
            $logsGroupedByStatus = [];
        }
        $occupation = DB::table('erp_loan_occupations')->get();
        $user = Helper::getAuthenticatedUser();
        $series = Book::where('organization_id', $user->organization_id)->select('id', 'book_name')->get();
        $book_type = LoanManagement::getBookType('home-loan');
        $view_detail = 1;
        $interest_rate = '';
        if (!empty($homeLoan->ass_cibil)) {
            $interest_rate = InterestRateScore::where('cibil_score_min', '<=', $homeLoan->ass_cibil)
                ->where('cibil_score_max', '>=', $homeLoan->ass_cibil)
                ->select('interest_rate')
                ->first();
        }
        $page = "view_detail";

        $behalf_users = Employee::all();

        $loan_disbursement = LoanDisbursement::with('homeLoan')->where('home_loan_id', $id)->get();
        $recovery_loan = RecoveryLoan::with('homeLoan')->where('home_loan_id', $id)->get();

        $overview = ErpLoanAppraisal::with('loan', 'disbursal', 'recovery', 'dpr')->where('loan_id', $id)->first();
        if (!$overview) {
            $overview = (object) [
                'created_at' => 'N/A',
                'unit_name' => 'N/A',
                'proprietor_name' => 'N/A',
                'address' => 'N/A',
                'cibil_score' => 'N/A',
                'project_cost' => 'N/A',
                'term_loan' => 'N/A',
                'promotor_contribution' => 'N/A',
                'interest_rate' => 'N/A',
                'loan_period' => 'N/A',
                'repayment_type' => 'N/A',
                'no_of_installments' => 'N/A',
                'repayment_start_after' => 'N/A',
                'repayment_start_period' => 'N/A',
                'dpr' => [],
                'disbursal' => [],
                'recovery' => [],
            ];
        }

        $document_listing = Helper::documentListing($id);

        $buttons = Helper::actionButtonDisplayForLoan($homeLoan->series, $homeLoan->approvalStatus, $homeLoan->id, $homeLoan->loan_amount);
        $logs = Helper::getLogs($id);
        return view('loanProgress.viewHomeLoan', compact('homeLoan', 'occupation', 'series', 'book_type', 'logsGroupedByStatus', 'view_detail', 'interest_rate', 'page', 'behalf_users', 'overview', 'document_listing', 'loan_disbursement', 'recovery_loan', 'buttons', 'logs'));
    }

    public function viewVehicleLoan($id)
    {

        $user = Helper::getAuthenticatedUser();
        $vehicleLoan = VehicleLoan::fetchRecord($id);
        $user = Helper::getAuthenticatedUser();
        $series = Book::where('organization_id', $user->organization_id)->select('id', 'book_name')->get();
        $book_type = LoanManagement::getBookType('vehicle-loan');
        if ($vehicleLoan && $vehicleLoan->loanApplicationLog) {
            $logs = $vehicleLoan->loanApplicationLog->sortByDesc('id');
            $logsGroupedByStatus = $logs->groupBy('action_type');
        } else {
            $logsGroupedByStatus = [];
        }
        $view_detail = 1;
        $interest_rate = '';
        if (!empty($vehicleLoan->ass_cibil)) {
            $interest_rate = InterestRateScore::where('cibil_score_min', '<=', $vehicleLoan->ass_cibil)
                ->where('cibil_score_max', '>=', $vehicleLoan->ass_cibil)
                ->select('interest_rate')
                ->first();
        }
        $page = "view_detail";
        $behalf_users = Employee::all();

        $loan_disbursement = LoanDisbursement::with('homeLoan')->where('home_loan_id', $id)->get();
        $recovery_loan = RecoveryLoan::with('homeLoan')->where('home_loan_id', $id)->get();

        $overview = ErpLoanAppraisal::with('loan', 'disbursal', 'recovery', 'dpr')->where('loan_id', $id)->first();
        if (!$overview) {
            $overview = (object) [
                'created_at' => 'N/A',
                'unit_name' => 'N/A',
                'proprietor_name' => 'N/A',
                'address' => 'N/A',
                'cibil_score' => 'N/A',
                'project_cost' => 'N/A',
                'term_loan' => 'N/A',
                'promotor_contribution' => 'N/A',
                'interest_rate' => 'N/A',
                'loan_period' => 'N/A',
                'repayment_type' => 'N/A',
                'no_of_installments' => 'N/A',
                'repayment_start_after' => 'N/A',
                'repayment_start_period' => 'N/A',
                'dpr' => [],
                'disbursal' => [],
                'recovery' => [],
            ];
        }

        $document_listing = Helper::documentListing($id);

        $buttons = Helper::actionButtonDisplayForLoan($vehicleLoan->series, $vehicleLoan->approvalStatus, $vehicleLoan->id, $vehicleLoan->loan_amount);
        $logs = Helper::getLogs($id);
        return view('loanProgress.viewVehicleLoan', compact('vehicleLoan', 'series', 'book_type', 'logsGroupedByStatus', 'view_detail', 'interest_rate', 'page', 'behalf_users', 'overview', 'document_listing', 'loan_disbursement', 'recovery_loan', 'buttons', 'logs'));
    }



    public function viewTermLoan($id)
    {

        $user = Helper::getAuthenticatedUser();
        $termLoan = TermLoanPromoter::fetchRecord($id);
        $user = Helper::getAuthenticatedUser();
        $series = Book::where('organization_id', $user->organization_id)->select('id', 'book_name')->get();
        $book_type = LoanManagement::getBookType('term-loan');
        if ($termLoan && $termLoan->loanApplicationLog) {
            $logs = $termLoan->loanApplicationLog->sortByDesc('id');
            $logsGroupedByStatus = $logs->groupBy('action_type');
        } else {
            $logsGroupedByStatus = [];
        }
        $view_detail = 1;
        $interest_rate = '';
        if (!empty($termLoan->ass_cibil)) {
            $interest_rate = InterestRateScore::where('cibil_score_min', '<=', $termLoan->ass_cibil)
                ->where('cibil_score_max', '>=', $termLoan->ass_cibil)
                ->select('interest_rate')
                ->first();
        }
        $page = "view_detail";

        $behalf_users = Employee::all();

        $loan_disbursement = LoanDisbursement::with('homeLoan')->where('home_loan_id', $id)->get();
        $recovery_loan = RecoveryLoan::with('homeLoan')->where('home_loan_id', $id)->get();

        $overview = ErpLoanAppraisal::with('loan', 'disbursal', 'recovery', 'dpr')->where('loan_id', $id)->first();
        if (!$overview) {
            $overview = (object) [
                'created_at' => 'N/A',
                'unit_name' => 'N/A',
                'proprietor_name' => 'N/A',
                'address' => 'N/A',
                'cibil_score' => 'N/A',
                'project_cost' => 'N/A',
                'term_loan' => 'N/A',
                'promotor_contribution' => 'N/A',
                'interest_rate' => 'N/A',
                'loan_period' => 'N/A',
                'repayment_type' => 'N/A',
                'no_of_installments' => 'N/A',
                'repayment_start_after' => 'N/A',
                'repayment_start_period' => 'N/A',
                'dpr' => [],
                'disbursal' => [],
                'recovery' => [],
            ];
        }

        $document_listing = Helper::documentListing($id);

        $buttons = Helper::actionButtonDisplayForLoan($termLoan->series, $termLoan->approvalStatus, $termLoan->id);
        $logs = Helper::getLogs($id);

        return view('loanProgress.approval.viewTermLoan', compact('termLoan', 'series', 'book_type', 'logsGroupedByStatus', 'view_detail', 'interest_rate', 'page', 'behalf_users', 'overview', 'document_listing', 'loan_disbursement', 'recovery_loan', 'buttons', 'logs', 'buttons'));
    }

    function loanApprove(Request $request)
    {

        $request->validate([
            'loan_application_id' => 'required',
            'document' => 'required',
            'behalf_users' => 'required',
            'remarks' => 'required'
        ]);

        $docs = [];
        if ($request->hasFile('document')) {
            // Define the storage path
            $directoryPath = 'loan_documents/approval';

            // Check if the directory exists; if not, create it
            if (!Storage::disk('public')->exists($directoryPath)) {
                Storage::disk('public')->makeDirectory($directoryPath);
            }
            // Store the file in the 'loan_documents/assessment' folder
            $documentPath = $request->file('document')->store($directoryPath, 'public');
            $attachments = $request->file('document');

            $update = HomeLoan::find($request->loan_application_id);
            $approveDocument = Helper::approveDocument($update->series, $update->id, 0, $request->remarks, $attachments, $update->approvalLevel, 'approve', $update->loan_amount);

            $update->approvalLevel = $approveDocument['nextLevel'];
            $update->approvalStatus = $approveDocument['approvalStatus'];
            $update->save();

            if ($update->approvalStatus == 'Approved') {
                LoanApproval::create([
                    'loan_application_id' => $request->loan_application_id,
                    'status' => 'Approved',
                    'on_behalf' => json_encode($request->behalf_users),
                    'doc' => $documentPath,
                    'remarks' => $request->remarks
                ]);
            }

            $docs[] = $documentPath;
        }

        $loan_data = HomeLoan::find($request->loan_application_id)->first();

        Helper::logs(
            $loan_data->series,
            $loan_data->appli_no,
            $request->loan_application_id,
            $loan_data->organization_id,
            'Approval',
            $request->remarks,
            $loan_data->loanable_id,
            $docs,
            $loan_data->loanable_type,
            0,
            $loan_data->created_at,
            $loan_data->approvalStatus
        );

        return redirect()->route('loanApproval.index');
    }

    function loanReturn(Request $request)
    {

        $request->validate([
            'loan_application_id' => 'required',
            'document' => 'required',
            'behalf_users' => 'required',
            'remarks' => 'required'
        ]);

        $docs = [];
        if ($request->hasFile('document')) {
            // Define the storage path
            $directoryPath = 'loan_documents/return';

            // Check if the directory exists; if not, create it
            if (!Storage::disk('public')->exists($directoryPath)) {
                Storage::disk('public')->makeDirectory($directoryPath);
            }

            // Store the file in the 'loan_documents/assessment' folder
            $documentPath = $request->file('document')->store($directoryPath, 'public');

            HomeLoan::find($request->loan_application_id)->update(['approvalStatus' => 'draft']);

            LoanReturn::create([
                'loan_application_id' => $request->loan_application_id,
                'status' => 'draft',
                'on_behalf' => json_encode($request->behalf_users),
                'doc' => $documentPath,
                'remarks' => $request->remarks
            ]);
            $docs[] = $documentPath;
        }

        $loan_data = HomeLoan::find($request->loan_application_id)->first();

        Helper::logs(
            $loan_data->series,
            $loan_data->appli_no,
            $request->loan_application_id,
            $loan_data->organization_id,
            'Approval',
            $request->remarks,
            $loan_data->loanable_id,
            $docs,
            $loan_data->loanable_type,
            0,
            $loan_data->created_at,
            $loan_data->approvalStatus
        );

        return redirect()->route('loanApproval.view' . ucwords($request->loan_type) . 'Loan', [$request->loan_application_id]);
    }

    function loanReject(Request $request)
    {

        $request->validate([
            'loan_application_id' => 'required',
            'document' => 'required',
            'behalf_users' => 'required',
            'remarks' => 'required'
        ]);

        $docs = [];
        if ($request->hasFile('document')) {
            // Define the storage path
            $directoryPath = 'loan_documents/reject';

            // Check if the directory exists; if not, create it
            if (!Storage::disk('public')->exists($directoryPath)) {
                Storage::disk('public')->makeDirectory($directoryPath);
            }

            // Store the file in the 'loan_documents/assessment' folder
            $documentPath = $request->file('document')->store($directoryPath, 'public');

            HomeLoan::find($request->loan_application_id)->update(['approvalStatus' => 'rejected']);

            LoanReject::create([
                'loan_application_id' => $request->loan_application_id,
                'status' => 'rejected',
                'on_behalf' => json_encode($request->behalf_users),
                'doc' => $documentPath,
                'remarks' => $request->remarks
            ]);
            $docs[] = $documentPath;
        }

        $loan_data = HomeLoan::find($request->loan_application_id)->first();

        Helper::logs(
            $loan_data->series,
            $loan_data->appli_no,
            $request->loan_application_id,
            $loan_data->organization_id,
            'Approval',
            $request->remarks,
            $loan_data->loanable_id,
            $docs,
            $loan_data->loanable_type,
            0,
            $loan_data->created_at,
            $loan_data->approvalStatus
        );

        return redirect()->route('loanApproval.view' . ucwords($request->loan_type) . 'Loan', [$request->loan_application_id]);
    }

    public function view()
    {
        return view('loanProgress.approval.view');
    }
}
