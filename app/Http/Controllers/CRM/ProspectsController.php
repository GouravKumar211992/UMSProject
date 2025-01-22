<?php

namespace App\Http\Controllers\CRM;

use App\Helpers\ConstantHelper;
use App\Helpers\GeneralHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CRM\ErpDiary;
use App\Models\CRM\ErpIndustry;
use App\Models\CRM\ErpMeetingStatus;
use App\Models\ErpCustomer;
use Carbon\Carbon;
use App\Exports\crm\csv\ProspectsExport;
use Illuminate\Http\Request;
use DB;
use PHPUnit\TextUI\Configuration\Constant;

class ProspectsController extends Controller
{
    public function index(Request $request){
        $pageLengths = ConstantHelper::PAGE_LENGTHS;
        $length = $request->length ? $request->length : ConstantHelper::PAGE_LENGTH_10;
        $user = Helper::getAuthenticatedUser();

        $customers = ErpCustomer::with(['meetingStatus','industry','latestDiary' => function($q){
                        $q->select('customer_code','created_at');
                    }])
                    ->select('id','lead_status','sales_figure','company_name','industry_id','customer_code')
                    ->where('organization_id', $user->organization_id)
                    ->where(function($query) use($request){
                        GeneralHelper::applyUserFilter($query,'ErpCustomer');
                    });

                    if ($request->search) {
                        $customers->where(function($q) use ($request) {
                            $q->where('erp_customers.customer_code', 'like', '%' . $request->search . '%')
                                ->orWhere('erp_customers.company_name', 'like', '%' . $request->search . '%')
                                ->orWhereHas('meetingStatus', function ($q) use ($request) {
                                    $q->Where('title', 'like', '%' . $request->search . '%');
                                })
                                ->orWhereHas('industry', function ($q) use ($request) {
                                    $q->Where('name', 'like', '%' . $request->search . '%');
                                });
                        });
                    }

                    if ($request->status) {
                        $customers->where('erp_customers.lead_status', $request->status);
                    }

                    $customers = $customers->orderby('id','DESC')->paginate($length);

        return view('crm.prospects.index',[
            'customers' => $customers,
            'pageLengths' => $pageLengths,
        ]);
    }
    
    public function dashboard(Request $request){
        $user = Helper::getAuthenticatedUser();
        $prospectsData = $this->prospectsData($user,$request);
        return view('crm.prospects.dashboard',[
            'prospectsData' => $prospectsData
        ]);
    }

    private function prospectsData($user, $request){
        $meetingStatus = ErpMeetingStatus::where('erp_meeting_status.organization_id', $user->organization_id)
                        ->where('status',ConstantHelper::ACTIVE)
                        ->get();
        $customers = ErpCustomer::where('organization_id', $user->organization_id)
                        ->where(function($query) use($request){
                            GeneralHelper::applyUserFilter($query,'ErpCustomer');
                            GeneralHelper::applyDateFilter($query,$request,'created_at');
                            if ($request->has('customer_code')) {
                                $query->where('customer_code', $request->customer_code);
                            }
                        })
                        ->get();
        $totalSalesFigure = $customers->sum('sales_figure');
        $statusData = [];
        foreach ($meetingStatus as $status) {
            $customerCount = $customers->where('lead_status',$status->alias)->count();
            $salesFigureSum = $customers->where('lead_status',$status->alias)->sum('sales_figure');
            $status->prospects_count = $customerCount;
            $status->sales_figure_sum = $salesFigureSum;
            $status->sales_percentage = $totalSalesFigure > 0 ? (($salesFigureSum / $totalSalesFigure) * 100) : 0;
            $statusData[] = $status;
        }
        $statusData = collect($statusData)->sortBy('prospects_count')->values()->all();

        $limit = 5;
        $topProspects = ErpDiary::with(['customer' => function($q){
                                $q->select('id','company_name','customer_code');
                            },'industry'])
                            ->where(function($query)use($request){
                                GeneralHelper::applyDiaryFilter($query);
                            })
                            ->select('customer_id','customer_code','industry_id', \DB::raw('SUM(sales_figure) as sales_figure'))
                            ->whereNotNull('industry_id')
                            ->orderBy('sales_figure', 'desc') 
                            ->groupBy('industry_id') 
                            ->limit($limit)
                            ->get();
        $salesFigureSum = $topProspects->sum('sales_figure');

        $lostProspects = ErpDiary::with(['customer' => function($q){
                                $q->select('id','company_name');
                            },'meetingStatus'])
                            ->where(function($query)use($request){
                                GeneralHelper::applyDiaryFilter($query);
                            })
                            ->select('customer_id','industry_id','description','meeting_status_id', 'sales_figure')
                            ->whereHas('meetingStatus',function($q) use($user){
                                $q->where('alias','lost')
                                ->where('organization_id', $user->organization_id);
                            })
                            ->whereNotNull('meeting_status_id')
                            ->orderBy('id','desc')
                            ->limit($limit)
                            ->get();
        return [
            'statusData' => $statusData,
            'topProspects' => $topProspects,
            'totalSalesFigure' => $totalSalesFigure,
            'salesFigureSum' => $salesFigureSum,
            'lostProspects' => $lostProspects,
            'limit' => $limit,
        ];
    }

    public function prospectsCsv(Request $request)
    {
        $type = GeneralHelper::loginUserType();
        $user = Helper::getAuthenticatedUser();
        $customers = ErpCustomer::with(['meetingStatus','industry','latestDiary' => function($q){
                        $q->select('customer_code','created_at');
                    }])
                    ->select('id','lead_status','sales_figure','company_name','industry_id','customer_code')
                    ->where('organization_id', $user->organization_id)
                    ->where(function($query) use($request){
                        GeneralHelper::applyUserFilter($query,'ErpCustomer');
                    });

                    if ($request->search) {
                        $customers->where(function($q) use ($request) {
                            $q->where('erp_customers.customer_code', 'like', '%' . $request->search . '%')
                                ->orWhere('erp_customers.company_name', 'like', '%' . $request->search . '%')
                                ->orWhereHas('meetingStatus', function ($q) use ($request) {
                                    $q->Where('title', 'like', '%' . $request->search . '%');
                                })
                                ->orWhereHas('industry', function ($q) use ($request) {
                                    $q->Where('name', 'like', '%' . $request->search . '%');
                                });
                        });
                    }

                    if ($request->status) {
                        $customers->where('erp_customers.lead_status', $request->status);
                    }

        $customers = $customers->orderBy('id', 'DESC');

        $prospectsCsv = new ProspectsExport();
        $fileName = "temp/crm/csv/prospects.csv";
        $prospectsCsv->export($fileName,$customers);

        return redirect($fileName);
    }

    public function view($customerCode,Request $request){
        $user = Helper::getAuthenticatedUser();
        $request->merge(['customer_code' => $customerCode]);
        $customer = ErpCustomer::with('meetingStatus')->where('customer_code',$customerCode)->first();
        $diariesData = $this->diariesData($user,$request);
        $meetingStatuses = ErpMeetingStatus::where('status',ConstantHelper::ACTIVE)->where('organization_id', $user->organization_id)->get();
        return view('crm.prospects.view',[
            'customer' => $customer,
            'diariesData' => $diariesData,
            'meetingStatuses' => $meetingStatuses,
        ]);
    }

    private function diariesData($user, $request){
        $meetingStatus = ErpMeetingStatus::where('erp_meeting_status.organization_id', $user->organization_id)
                        ->where('status',ConstantHelper::ACTIVE)
                        ->get();
        $diaries = ErpDiary::where('organization_id', $user->organization_id)
                        ->where('customer_code',$request->customer_code)
                        ->orderBy('id','DESC')
                        ->get();

        $totalDiaries = $diaries->count();

        foreach ($meetingStatus as $status) {
            $diariesCount = $diaries->where('meeting_status_id',$status->id)->count();
            $status->diaries_count = $diariesCount;
            $status->diaries_percentage = $totalDiaries > 0 ?  (($diariesCount/$totalDiaries)*100) : 0;
           
        }
        $latestDiaries = $diaries->take(5);
        return [
            'meetingStatus' => $meetingStatus,
            'latestDiaries' => $latestDiaries,
        ];

    }

    public function updateLeadStatus($id,Request $request){
        $erpcustomer = ErpCustomer::find($id);
        $erpcustomer->lead_status = $request->status ? $request->status : NULL;
        $erpcustomer->status = $request->status == 'won' ? ConstantHelper::ACTIVE : ConstantHelper::PENDING;
        $erpcustomer->save();

        if($request->status){
            $meetingStatus = $meetingStatus = ErpMeetingStatus::where('alias',$request->status)->first();
            $erpDiary = ErpDiary::where('customer_id',$id)->first();
            $erpDiary->meeting_status_id = $meetingStatus->id;
            $erpDiary->save();
        }

        return response()->json([
            'message' => 'Status updated successfully.',
            'status' => true
        ]);

    }
}
