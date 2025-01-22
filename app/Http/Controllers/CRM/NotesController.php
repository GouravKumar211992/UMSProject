<?php

namespace App\Http\Controllers\CRM;

use App\Helpers\ConstantHelper;
use Auth;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\ErpCustomer;
use App\Models\CRM\ErpDiary;
use App\Helpers\FileUploadHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Lib\Validation\ErpDiary as Validator;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\CRM\ErpDiaryTagPeople;
use App\Models\CRM\ErpMeetingObjective;
use App\Models\CRM\ErpMeetingStatus;
use App\Models\ErpAddress;
use App\Models\ErpDiaryAttachment;
use App\Models\State;
use App\Services\CommonService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use App\Exceptions\ApiGenericException;
use App\Helpers\GeneralHelper;
use App\Models\CRM\ErpIndustry;

class NotesController extends Controller
{
    protected $commonService;
    protected $fileUploadHelper;

    public function __construct(CommonService $commonService, FileUploadHelper $fileUploadHelper)
    {
        $this->commonService = $commonService;
        $this->fileUploadHelper = $fileUploadHelper;
    }

    public function index(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        
        $customers = ErpCustomer::where(function($query){
                            GeneralHelper::applyUserFilter($query,'ErpCustomer');
                        })
                        ->get();

        $erpDiaries = ErpDiary::with(['customer','attachments','createdByEmployee' => function($q){
            $q->select('id','name');
        },'createdByUser' => function($q){
            $q->select('id','name');
        }])
            ->where(function($query) use($request){
                GeneralHelper::applyDiaryFilter($query);
                if($request->customer_id){
                    $query->where('customer_id',$request->customer_id);
                }
            })
            ->whereDate('created_at', date('Y-m-d'))
            ->orderBy('id','desc')
            ->paginate(ConstantHelper::PAGE_LENGTH_10);

        $meetingObjectives = ErpMeetingObjective::where('status',ConstantHelper::ACTIVE)->get();
        
        return view('crm.notes.index', [
            'customers' => $customers,
            'erpDiaries' => $erpDiaries,
            'meetingObjectives' => $meetingObjectives,
        ] );
    }

    public function renderDiaries(Request $request){

        $user = Helper::getAuthenticatedUser();
        $erpDiaries = ErpDiary::with(['customer','attachments','createdByEmployee' => function($q){
            $q->select('id','name');
        },'createdByUser' => function($q){
            $q->select('id','name');
        }])
            ->where(function($query){
                GeneralHelper::applyDiaryFilter($query);
            });

            if($request->objective){
                $erpDiaries->where('meeting_objective_id',$request->objective);
            }

            if($request->daterange && $request->daterange == 'today'){
                $erpDiaries->whereDate('created_at',date('Y-m-d'));
            }

            if($request->daterange && $request->daterange == 'this week'){
                $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
                $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
                $erpDiaries->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            }

            if($request->daterange && $request->daterange == 'this month'){
                $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
                $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
                $erpDiaries->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }

            if($request->daterange && $request->daterange == 'this year'){
                $startOfYear = Carbon::now()->startOfYear()->toDateString();
                $endOfYear = Carbon::now()->endOfYear()->toDateString();
                $erpDiaries->whereBetween('created_at', [$startOfYear, $endOfYear]);
            }

            if($request->date){
                $erpDiaries->whereDate('created_at', date('Y-m-d', strtotime($request->date)));
            }

            if($request->customer_id){
                $erpDiaries->where('customer_id',$request->customer_id);
            }

            $erpDiaries = $erpDiaries->orderBy('id','desc')
                            ->paginate(ConstantHelper::PAGE_LENGTH_10);

            return [
                'data' => view('crm.notes.diary-list', ['erpDiaries' => $erpDiaries])->render(),
                'message' => 'HTML render',
            ];
    }

    public function create()
    {
        $user = Helper::getAuthenticatedUser();
        $type = GeneralHelper::loginUserType();
        $teamsIds = GeneralHelper::getTeam($user);

        $customers = ErpCustomer::where(function($query){
                            GeneralHelper::applyUserFilter($query,'ErpCustomer');
                        })->get();
        
        $salePersons = Employee::where(function($query) use($type,$user,$teamsIds){
                            if($type == 'employee'){
                                $query->whereIn('id', $teamsIds);
                            }else{
                                $query->where('organization_id', $user->organization_id);
                            }
                        })->get();

        $meetingStatus = ErpMeetingStatus::where('organization_id', $user->organization_id)->where('status', ConstantHelper::ACTIVE)->get();
        $industries = ErpIndustry::where('organization_id', $user->organization_id)->where('status', ConstantHelper::ACTIVE)->get();
        $meetingObjectives = ErpMeetingObjective::where('organization_id', $user->organization_id)->where('status', ConstantHelper::ACTIVE)->get();
        $countries = Country::select('id','code','name')->get();
        $states = State::get();
        return view('crm.notes.create',[
            'customers' => $customers,
            'salePersons' => $salePersons,
            'meetingStatus' => $meetingStatus,
            'meetingObjectives' => $meetingObjectives,
            'states' => $states,
            'industries' => $industries,
            'countries' => $countries,
        ] );
    }

    public function store(Request $request)
    {
        $validator = (new Validator($request))->store();

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            \DB::beginTransaction();
            $user = Helper::getAuthenticatedUser();
            // dd($request->all());

            if($request->customer_type == 'New'){
                $meetingStatus = ErpMeetingStatus::find($request->meeting_status_id); 
                $erpCustomer = new ErpCustomer();
                $erpCustomer->company_name = $request->customer_name;
                $erpCustomer->mobile = $request->phone_no;
                $erpCustomer->phone = $request->phone_no;
                $erpCustomer->contact_person = $request->contact_person;
                $erpCustomer->sales_person_id = $request->sales_representative_id;
                $erpCustomer->email = $request->email_id;
                $erpCustomer->organization_id = $user->organization_id;
                $erpCustomer->customer_type = ConstantHelper::INDIVIDUAL;
                $erpCustomer->status = $meetingStatus->alias == ConstantHelper::WON ? ConstantHelper::ACTIVE : ConstantHelper::PENDING;
                $erpCustomer->industry_id = $request->industry_id;
                $erpCustomer->lead_status = $meetingStatus->alias;
                $erpCustomer->sales_figure = $request->sales_figure ? $request->sales_figure : 0;
                $erpCustomer->customer_address = $request->address;
                $erpCustomer->state_id = $request->state_id;
                $erpCustomer->country_id = $request->country_id;
                $erpCustomer->city_id = $request->city_id;
                $erpCustomer->customer_pincode = $request->zip_code;
                $erpCustomer->save();
                
                $customerId = $erpCustomer->id;                
                $erpCustomer->customer_code = $customerId;
                $erpCustomer->save();
            }else{
                $erpCustomer = ErpCustomer::with('address')
                ->select('id','company_name','industry_id','sales_figure','customer_address','customer_pincode','state_id','city_id','country_id','customer_code','email','contact_person')
                ->where('customer_code',@$request->customer_code)
                ->first();

                $customerId = @$erpCustomer->id;
            }

            $diary = ErpDiary::where('customer_code', $customerId)
            ->where('organization_id', $user->organization_id)
            ->latest()
            ->first();

            $erpDiary = new ErpDiary();
            $erpDiary->fill($request->all());
            $erpDiary->organization_id = $user->organization_id;
            $erpDiary->sales_figure = $erpCustomer->sales_figure;
            $erpDiary->customer_id = $customerId;
            $erpDiary->customer_code = $erpCustomer->customer_code;
            $erpDiary->customer_name = $erpCustomer->company_name;
            $erpDiary->contact_person = $erpCustomer->contact_person;
            $erpDiary->email = $erpCustomer->email;
            $erpDiary->industry_id = $erpCustomer->industry_id;
            $erpDiary->location = $erpCustomer->full_address;
            $erpDiary->subject = $request->meeting_objective;
            $erpDiary->meeting_objective_id = $request->meeting_objective_id;
            $erpDiary->meeting_status_id = $request->meeting_status_id ? $request->meeting_status_id : @$diary->meeting_status_id;
            $erpDiary->created_by = $user->id;
            $erpDiary->created_by_type = GeneralHelper::loginUserType();
            $erpDiary->save();

            $documentPath = '';

            if ($request->hasFile('attachment')) {
                $attachments = $request->file('attachment');
                foreach ($attachments as $key => $attachment) {
                    $documentName = time() . '-' . $attachment->getClientOriginalName();
                    $attachment->move(public_path('attachments/note_attchments'), $documentName);
                    $documentPath = 'attachments/note_attchments/'.$documentName;

                    $erpDiaryAttachment = new ErpDiaryAttachment();
                    $erpDiaryAttachment->erp_diary_id = $erpDiary->id;
                    $erpDiaryAttachment->document_path = $documentPath;
                    $erpDiaryAttachment->save();
                }
            }

            
            \DB::commit();
            return [
                "data" => $erpDiary,
                "message" => "Note added successfully!"
            ];

        } catch (\Exception $e) {
            \DB::rollback();
            throw new ApiGenericException($e->getMessage());
        }
    }

    public function getLocations($customerId)
    {
        $data = [];
        if($customerId){
                $data = ErpAddress::where('addressable_type', 'App\Models\ErpCustomer' )
                ->where('addressable_id', $customerId)
                ->get();
        }
        return response()->json($data);
    }

    public function getCustomers($customerId)
    {
        $user = Helper::getAuthenticatedUser();
        $customer = ErpCustomer::with('salesRepresentative')
            ->where('customer_code', $customerId)
            ->where('erp_customers.organization_id', $user->organization_id)
            ->first();
        if(!$customer)
        {
            return response()->json([
                'message' => 'Customer not found.',
                'status' => false
            ]);
        }
        $diary = ErpDiary::with('industry')
            ->where('customer_code', $customerId)
            ->where('organization_id', $user->organization_id)
            ->latest()
            ->first();

        return response()->json([
            'customer' => $customer,
            'diary' => $diary
        ]);
    }


}
