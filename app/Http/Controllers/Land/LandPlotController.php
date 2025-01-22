<?php

namespace App\Http\Controllers\Land;
use App\Models\NumberPattern;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use App\Models\LandPlot;
use App\Models\LandPlotHistory;
use App\Models\LandParcel;
use App\Models\Land;
use App\Models\Book;
use App\Models\BookType;
use App\Models\ErpDocument;
use App\Models\Lease;
use App\Models\Employee;
use App\Exports\LandExport;
use Illuminate\Http\Request;
use App\Models\LandScheduler;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Helpers\ConstantHelper;
use App\Models\Location;
use App\Models\PlotLocation;
use App\Models\Recovery;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandNotificationController;

class LandPlotController extends Controller
{

    public function index()
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
            $utype = 'user';
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
            $utype = 'employee';
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 1;
            $utype = 'user';
        }

        if (!empty($type) && $type == 1)
        {
            $lands = LandPlot::where('organization_id', $organization_id)
                ->where('user_id', $user_id)
                ->where('type', $type)
                ->orwhereHas('approvelworkflow', function ($query) use ($user_id, $utype) {
                    $query->where('user_id', $user_id)  // Match user_id in approvelworkflow with authenticated user
                        ->where('user_type', $utype);       // Match type in approvelworkflow
                })
                ->orderBy('created_at', 'desc')->with('landParcel:id,name');;
        }
        elseif (!empty($type) && $type == 2)
        {
            $lands = LandPlot::where('organization_id', $organization_id)
                // Legals created by the user
                ->where('user_id', $user_id)
                ->where('type', $type)
                ->orwhereHas('approvelworkflow', function ($query) use ($user_id, $utype) {
                    $query->where('user_id', $user_id)  // Match user_id in approvelworkflow with authenticated user
                        ->where('user_type', $utype);       // Match type in approvelworkflow
                })

                // Order by creation date
                ->orderBy('created_at', 'desc')->with('landParcel:id,name');;
        }
        else {
            $lands = LandPlot::where('organization_id', $organization_id)
            // LandParcels created by the user
            ->orderby('id', 'desc')->with('landParcel:id,name');// Example: fetching all lands from the database
        }


        $selectedDateRange = '';
        $pincode = '';
        $land_no = '';
        $selectedStatus = '';
        $khasra = '';
        $plot = '';

        $dataCollection = $lands->get();
        $pincode = $dataCollection->pluck('pincode')->unique();
        $land_no = $dataCollection->pluck('id');
        $selectedStatus = $dataCollection->pluck('approvalStatus')->unique();

        $lands=$lands->get();


        return view('land.land-plot.index', compact('lands', 'selectedDateRange', 'pincode', 'land_no', 'selectedStatus', 'khasra', 'plot')); // Return the 'land.index' view
    }
    public function filter(Request $request)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
            $utype = 'user';
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
            $utype = 'employee';
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 1;
            $utype = 'user';
        }
        // Fetch data related to lands and return the view
        $query = LandPlot::where('organization_id', $organization_id)
            // LandParcels created by the user
            ->where('user_id', $user_id)
            ->where('type', $type)->orderby('id', 'desc');
        // Apply filters based on the request

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->input('date_range'));
            if (!empty($dates[1])) {
                $query->whereBetween('created_at', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            } else {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        if ($request->filled('land_no')) {
            $query->where('id', $request->input('land_no'));
        }

        if ($request->filled('pincode')) {
            $query->where('pincode', $request->input('pincode'));
        }

        if ($request->filled('selectedStatus')) {
            $query->where('approvalStatus', $request->input('selectedStatus'));

        }



        $selectedDateRange = '';
        $pincode = $query->distinct()->pluck('pincode');
        $land_no = $query->pluck('id');
        $selectedStatus = $query->distinct()->pluck('approvalStatus');
        $khasra = '';
        $plot = '';
        $lands = $query->get();



        return view('land.land-plot.index', compact('lands', 'selectedDateRange', 'pincode', 'land_no', 'selectedStatus', 'khasra', 'plot')); // Return the 'land.index' view
    }

    public function create()
    {

        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 2;
        }

        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
       // $leasedLandNumbers = Lease::pluck('land_no')->toArray();
        $lands = LandParcel::with('plot')
        ->where('organization_id', $organization_id)
            ->where('created_by', $user_id)
            ->where('document_status','!=','draft')
            // ->whereNotIn('id', $leasedLandNumbers)
            ->get();
            $doc_type = ErpDocument::where('organization_id', $organization_id)
            ->where('service', 'land')->where('status','active')
            ->get();


        return view('land.land-plot.add', compact('series','lands','doc_type')); // Return the 'land.add' view
    }
    public function search(Request $request)
{
    $query = LandParcel::query();

    if ($request->has('district') && $request->district != '') {
        $query->where('district', $request->district);
    }

    if ($request->has('state') && $request->state != '') {
        $query->where('state', $request->state);
    }

    if ($request->has('country') && $request->country != '') {
        $query->where('country', $request->country);
    }

    $lands = $query->get();

    return response()->json(['lands' => $lands]);
}

    public function save(Request $request)
    {
        // Validation
        $messages = [
            'series.required' => 'The series field is required.',
            'land_id.required' => 'The land ID field is required.',
            'plot_name.required' => 'The plot name field is required.',
            'document_no.required' => 'The document number field is required.',
            'area_unit.required' => 'The area unit field is required.',
            'khasara_no.string' => 'The khasara number must be a string.',

            'plot_area.required' => 'The plot area field is required.',
            'plot_area.numeric' => 'The plot area must be a number.',
            'plot_area.min' => 'The plot area must be at least 0.01.',

            'land_location.required' => 'The land location field is required.',
            'land_location.string' => 'The land location must be a string.',

            'dimension.required' => 'The dimension field is required.',
            // 'dimension.numeric' => 'The dimension must be a number.',
            // 'dimension.min' => 'The dimension must be at least 0.01.',

            'address.required' => 'The address field is required.',
            'pincode.required' => 'The pincode field is required.',

            'latitude.numeric' => 'The latitude must be a number.',
            'longitude.numeric' => 'The longitude must be a number.',

            'status.required' => 'The status field is required.',
            'remarks.string' => 'The remarks must be a string.',

            'type_of_usage.required' => 'The type of usage field is required.',
            'type_of_usage.string' => 'The type of usage must be a string.',

            'land_size.required' => 'The land size field is required.',

            'plot_valuation.numeric' => 'The plot valuation must be a number.',
            'plot_valuation.min' => 'The plot valuation must be at least 0.01.',
        ];

        if ($request->status_val != 'draft') {
            $validatedData = $request->validate([
                'series' => 'required',
                'land_id' => 'required|exists:erp_land_parcels,id', // Validates the ID exists in erp_land_parcels
                'plot_name' => 'required',
                'document_no' => 'required',
                'area_unit' => 'required',
                'khasara_no' => 'nullable',
                'plot_area' => 'required|numeric|min:0.01',
                'land_location' => 'required|string',
                'dimension' => 'required',
                'address' => 'required',
                'pincode' => 'required',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'status' => 'required',
                'remarks' => 'nullable|string',
                'type_of_usage' => 'required|string',
                'land_size' => 'required',
                'plot_valuation' => 'sometimes|nullable|numeric|min:0.01',
            ], $messages);
        } else {
            $validatedData = $request->validate([
                'series' => 'required',
                'land_id' => 'required|exists:erp_land_parcels,id', // Validates the ID exists in erp_land_parcels
                'plot_name' => 'required',
                'document_no' => 'required',
                'land_location' => 'required|string',
                'land_size' => 'required',
                'dimension' => 'sometimes',
                'plot_area' => 'sometimes|nullable|numeric|min:0.01',
                'plot_valuation' => 'sometimes|nullable|numeric|min:0.01',
            ], $messages);
        }


        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 2;
        }
        $json = [];
            if ($request->has('documentname')) {
                $i = 0;
                foreach ($request->documentname as $key => $names) {
                    $json[$i]['name'] = $names; // Store the document name in the array

                    if (isset($request->attachments[$key])) {
                        foreach ($request->attachments[$key] as $key1 => $document) {
                            $documentName = time() . '-' . $document->getClientOriginalName();

                            // Move the document to public/documents folder
                            $document->move(public_path('documents'), $documentName);

                            // Store the file name in the 'files' array for the current document
                            $json[$i]['files'][$key1] = $documentName;
                        }
                    }
                    $i++;
                }
            }




        do {
            $document_no = Helper::reGenerateDocumentNumber($request->series);
            $existingLoan = LandPlot::where('document_no', $document_no)->first();
        } while ($existingLoan !== null);
        //dd('here', $document_no);
        try {
            $validatedData = array_merge($validatedData, [
                'organization_id' => $organization_id,
                'user_id' => $user_id,
                'type' => $type,
                'document_no' => $document_no,
            ]);

            $geofence="";
            $lat="";
            $lang="";

            $numberPattern = NumberPattern::where('book_id', $request->series)->first();
            $user = Helper::getAuthenticatedUser();
            $organization = $user->organization;
            $organization_id= $organization->id;
            $group_id= $organization->group_id;
            $company_id=  $organization->company_id;


            if (!empty($numberPattern)) {

                $number = $numberPattern->current_no + 1;
                $numberPattern->current_no = $number;
                $numberPattern->save();
            }
            $status = $request->status_val;
            $userData = Helper::userCheck();

            // Save the data to the database
            $landPlot = LandPlot::create([
                'attachments' => json_encode($json),
                'book_id' => $request->input('series'),
                'series_id' => $request->input('series'),
                'group_id'=>$group_id,
                'company_id'=>$company_id,
                'document_date'=> Carbon::now()->format('Y-m-d'),
                'document_no' => $request->input('document_no'),
                'doc_number_type'=>$request->input('doc_number_type'),
                'doc_reset_pattern'=>$request->input('doc_reset_pattern'),
                'doc_prefix'=>$request->input('doc_prefix'),
                'doc_suffix'=>$request->input('doc_suffix'),
                'doc_no'=>$request->input('doc_no'),
                'plot_name' => $request->input('plot_name'),
                'geofence_file'=>$geofence,
                'land_id' => $request->input('land_id'),
                'land_size' => trim(preg_replace('/\s+/', ' ', preg_replace('/[^\d.]+/', ' ', $request->input('land_size')))),
                'land_location' => $request->input('land_location'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'status' => $request->input('status'),
                'khasara_no' => $request->input('khasara_no'),
                'dimension' => $request->input('dimension'),
                'plot_valuation' => $request->input('plot_valuation'),
                'address' => $request->input('address'),
                'pincode' => $request->input('pincode'),
                'plot_area' => $request->input('plot_area')==null?"":$request->input('plot_area'),
                'area_unit' => $request->input('area_unit')==null?"":$request->input('area_unit'),
                'type_of_usage' => $request->input('type_of_usage')==null?"":$request->input('type_of_usage'),
                'remarks' => $request->input('remarks'),
                'organization_id' => $organization_id, // Assuming you have org_id
                'user_id' => $user_id,
                'type' => $type,
                'approvalStatus' => $status,
                'landable_id' => $userData['user_id'],
                'landable_type' => $userData['user_type']


            ]);
            $update = LandPlot::find($landPlot->id);

            $document_status= $request->status_val;
            if ($status == ConstantHelper::SUBMITTED) {
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remarks;
                $attachments = $request->file('attachments');
                $currentLevel = $update->approvalLevel;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = 'submit'; // Approve // reject // submit
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType);
                $document_status = Helper::checkApprovalRequired($bookId);
                $update->approvalStatus = $document_status;
                $update->save();
                if($document_status==ConstantHelper::SUBMITTED){

                    if ($update->approvelworkflow->count() > 0) { // Check if the relationship has records
                        foreach ($update->approvelworkflow as $approver) {
                            if ($approver->user) { // Check if the related user exists
                                $approver_user = $approver->user;
                                LandNotificationController::notifyLandPlotSubmission($approver_user, $update);
                            }
                        }
                    }
            }
            }
            else{
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remarks;
                $attachments = $request->file('attachments');
                $currentLevel = $update->approvalLevel;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = 'draft'; // Approve // reject // submit
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType);
            }

            if ($request->hasFile('geofence')) {
                $file = $request->file('geofence');
                $csvData = array_map('str_getcsv', file($file->getRealPath()));
                $geofence= $file->getRealPath();

                foreach ($csvData as $key => $row)
                {
                    if($key  != 0)
                    {
                        PlotLocation::create([
                            'land_plot_id' => $landPlot->id,
                            'latitude' => $row[0],
                            'longitude' => $row[1],
                        ]);
                    }

                }
            }



            return redirect('/land-plot')->with('success', 'Land Plot Added successfully.');
        } catch (\Exception $e) {

            // Redirect back with input data and an error message if something goes wrong
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }}

    public function edit($id)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 2;
        }

        // $leasedLandNumbers = Lease::pluck('land_no')->toArray();
        $lands = LandParcel::with('plot')
        ->where('organization_id', $organization_id)
        ->where('created_by', $user_id)
        ->where('document_status','!=','draft')
            // ->whereNotIn('id', $leasedLandNumbers)
            ->get();
            $doc_type = ErpDocument::where('organization_id', $organization_id)
            ->where('service', 'land')->where('status','active')
            ->get();


        $books = BookType::where('status', 'Active')->whereHas('service', function ($query) {
            $query->where('alias', 'land-plot');
        })
            ->where('organization_id', $organization_id)
            ->pluck('id');

            $parentURL = request() -> segments()[0];
        

            $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
            if (count($servicesBooks['services']) == 0) {
               return redirect() -> route('/');
           }
           $firstService = $servicesBooks['services'][0];
           $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
           
        $data = LandPlot::where('organization_id', $organization_id)
        ->where('user_id', $user_id)
        ->where('type', $type)->orderby('id', 'desc')
        ->with('landParcel:id,name')->find($id);
        //dd($data->approvelworkflow->count());

       $locations = PlotLocation::where('land_plot_id', $id)->get();


        $locations = PlotLocation::where('land_plot_id', $id)->get();


        $creatorType = explode("\\", $data->landable_type);
        $amount = $data->plot_valutaion==null?0:$data->plot_valutaion;
        $buttons = Helper::actionButtonDisplay($data->book_id, $data->approvalStatus, $id, $amount, $data->approvalLevel, $data->landable_id, strtolower(end($creatorType)));
        $history = Helper::getApprovalHistory($data->book_id, $id, 0);
        $page = "edit";


        return view('land.land-plot.edit', compact('series', 'data','lands','doc_type','creatorType','locations','buttons','history')); // Return the 'land.add' view
    }
    public function view(Request $r,$id)
    {
        if (!empty(Auth::guard('web')->user())) {
            $organization_id = Auth::guard('web')->user()->organization_id;
            $user_id = Auth::guard('web')->user()->id;
            $type = 1;
        } elseif (!empty(Auth::guard('web2')->user())) {
            $organization_id = Auth::guard('web2')->user()->organization_id;
            $user_id = Auth::guard('web2')->user()->id;
            $type = 2;
        } else {
            $organization_id = 1;
            $user_id = 1;
            $type = 2;
        }

        $leasedLandNumbers = Lease::pluck('land_no')->toArray();
        $lands = LandParcel::with('plot')
        ->where('organization_id', $organization_id)
            ->where('created_by', $user_id)
            ->where('document_status','!=','draft')
            ->whereNotIn('id', $leasedLandNumbers)
            ->get();
            $doc_type = ErpDocument::where('organization_id', $organization_id)
            ->where('service', 'land')->where('status','active')
            ->get();


            $parentURL = request() -> segments()[0];
        

            $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
            if (count($servicesBooks['services']) == 0) {
               return redirect() -> route('/');
           }
           $firstService = $servicesBooks['services'][0];
           $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
           
        $currNumber=$r->revisionNumber;

        if ($currNumber!="") {
        $data = LandPlotHistory::where('organization_id', $organization_id)->where('source_id',$id)->first();
        $history = Helper::getApprovalHistory($data->book_id, $id, $data->revision_number);

        $locations = PlotLocation::where('land_plot_id', $id)->get();
        $data['attachments']=json_encode($data['attachments']);
    }
        else
        {
            $data = LandPlot::where('organization_id', $organization_id)
            ->with('landParcel:id,name')->find($id);
            $locations = PlotLocation::where('land_plot_id', $id)->get();
            $history = Helper::getApprovalHistory($data->book_id, $id, $data->revision_number);
        }


        $creatorType = explode("\\", $data->landable_type);

        $amount = $data->plot_valutaion==null?0:$data->plot_valutaion;
        $buttons = Helper::actionButtonDisplay($data->book_id, $data->approvalStatus, $id, $amount, $data->approvalLevel, $data->landable_id, strtolower(end($creatorType)));

        $page = "edit";
        $revisionNumbers = $history->pluck('revision_number')->unique()->values()->all();

        return view('land.land-plot.view', compact('currNumber','revisionNumbers','series', 'data','lands','doc_type','creatorType','locations','buttons','history','page')); // Return the 'land.add' view
    }
    public function update(Request $request)
{
    $id= $request->input('id');
    // Validation
    $validatedData=[];
    $messages = [
        'land_id.required' => 'The land ID field is required.',
        'plot_name.required' => 'The plot name field is required.',
        'document_no.required' => 'The document number field is required.',
        'area_unit.required' => 'The area unit field is required.',
        'khasara_no.string' => 'The khasara number must be a string.',

        'plot_area.required' => 'The plot area field is required.',
        'plot_area.numeric' => 'The plot area must be a number.',
        'plot_area.min' => 'The plot area must be at least 0.01.',

        'land_location.required' => 'The land location field is required.',
        'land_location.string' => 'The land location must be a string.',

        'dimension.required' => 'The dimension field is required.',

        'address.required' => 'The address field is required.',
        'pincode.required' => 'The pincode field is required.',

        'latitude.numeric' => 'The latitude must be a number.',
        'longitude.numeric' => 'The longitude must be a number.',

        'status.required' => 'The status field is required.',
        'remarks.string' => 'The remarks must be a string.',

        'type_of_usage.required' => 'The type of usage field is required.',
        'type_of_usage.string' => 'The type of usage must be a string.',

        'land_size.required' => 'The land size field is required.',

        'plot_valuation.numeric' => 'The plot valuation must be a number.',
        'plot_valuation.min' => 'The plot valuation must be at least 0.01.',
    ];

    if ($request->status_val != 'draft') {
        $validatedData = $request->validate([
            'land_id' => 'required|exists:erp_land_parcels,id', // Validates the ID exists in erp_land_parcels
            'plot_name' => 'required',
            'document_no' => 'required',
            'area_unit' => 'required',
            'khasara_no' => 'nullable',
            'plot_area' => 'required|numeric|min:0.01',
            'land_location' => 'required|string',
            'dimension' => 'required',
            'address' => 'required',
            'pincode' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required',
            'remarks' => 'nullable|string',
            'type_of_usage' => 'required|string',
            'land_size' => 'required',
            'plot_valuation' => 'sometimes|nullable|numeric|min:0.01',
        ], $messages);
    } else {
        $validatedData = $request->validate([
            'land_id' => 'required|exists:erp_land_parcels,id', // Validates the ID exists in erp_land_parcels
            'plot_name' => 'required',
            'document_no' => 'required',
            'land_location' => 'required|string',
            'land_size' => 'required',
            'dimension' => 'sometimes|nullable|numeric|min:0.01',
            'plot_area' => 'sometimes|nullable|numeric|min:0.01',
            'plot_valuation' => 'sometimes|nullable|numeric|min:0.01',
        ], $messages);
    }

// Fetch the existing record
    $landPlot = LandPlot::findOrFail($id); // Use findOrFail to get the record or fail with a 404

    // Retrieve user and organization information
    if (!empty(Auth::guard('web')->user())) {
        $organization_id = Auth::guard('web')->user()->organization_id;
        $user_id = Auth::guard('web')->user()->id;
        $type = 1;
    } elseif (!empty(Auth::guard('web2')->user())) {
        $organization_id = Auth::guard('web2')->user()->organization_id;
        $user_id = Auth::guard('web2')->user()->id;
        $type = 2;
    } else {
        $organization_id = 1;
        $user_id = 1;
        $type = 2;
    }

    // Handle document attachments
            $json = [];

            if ($request->has('documentname')) {
                $i = 0;
                foreach ($request->documentname as $key => $names) {
                    $json[$i]['name'] = $names; // Store the document name

                    // Handle new attachments
                    if (isset($request->attachments[$key])) {
                        foreach ($request->attachments[$key] as $key1 => $document) {
                            $documentName = time() . '-' . $document->getClientOriginalName();

                            // Move the document to the public/documents folder
                            $document->move(public_path('documents'), $documentName);

                            // Append the new file to the 'files' array for this document
                            $json[$i]['files'][] = $documentName;
                        }
                    }

                    // Handle old attachments
                    if (isset($request->oldattachments[$key])) {
                        foreach ($request->oldattachments[$key] as $key1 => $document1) {
                            // Append the old file to the 'files' array for this document
                            $json[$i]['files'][] = $document1;
                        }
                    }

                    $i++;
                }
            }
            $status = $request->status_val;

    try {
        // Update the existing record
        $landPlot->update([
            'attachments' => json_encode($json),
            'plot_name' => $request->input('plot_name'),
            'land_id' => $request->input('land_id'),
            'land_size' => trim(preg_replace('/\s+/', ' ', preg_replace('/[^\d.]+/', ' ', $request->input('land_size')))),
            'land_location' => $request->input('land_location'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'status' => $request->input('status'),
            'khasara_no' => $request->input('khasara_no'),
            'dimension' => $request->input('dimension'),
            'plot_valuation' => $request->input('plot_valuation'),
            'address' => $request->input('address'),
            'pincode' => $request->input('pincode'),
            'remarks' => $request->input('remarks'),
            'organization_id' => $organization_id,
            'user_id' => $user_id,
            'type' => $type,
            'approvalStatus' => $status,
            'plot_area' => $request->input('plot_area')==null?"":$request->input('plot_area'),
                'area_unit' => $request->input('area_unit')==null?"":$request->input('area_unit'),
                'type_of_usage' => $request->input('type_of_usage')==null?"":$request->input('type_of_usage'),

        ]);

        $update = $landPlot;

            $document_status= $request->status_val;
            if ($status == ConstantHelper::SUBMITTED) {
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remarks;
                $attachments = $request->file('attachments');
                $currentLevel = $update->approvalLevel;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = 'submit'; // Approve // reject // submit
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType);
                $document_status = Helper::checkApprovalRequired($bookId);
                $update->approvalStatus = $document_status;
                $update->save();
                if($document_status==ConstantHelper::SUBMITTED){

                    if ($update->approvelworkflow->count() > 0) { // Check if the relationship has records
                        foreach ($update->approvelworkflow as $approver) {
                            if ($approver->user) { // Check if the related user exists
                                $approver_user = $approver->user;
                                LandNotificationController::notifyLandPlotSubmission($approver_user, $update);
                            }
                        }
                    }
            }
            }
            else{
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remarks;
                $attachments = $request->file('attachments');
                $currentLevel = $update->approvalLevel;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = 'draft'; // Approve // reject // submit
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType);
            }



        // Handle geofence file if it exists
        if ($request->hasFile('geofence')) {
            $file = $request->file('geofence');
            $csvData = array_map('str_getcsv', file($file->getRealPath()));


            // Clear existing plot locations if needed
            PlotLocation::where('land_plot_id', $landPlot->id)->delete();

            foreach ($csvData as $key => $row) {
                if ($key != 0) { // Skip the header row
                    PlotLocation::create([
                        'land_plot_id' => $landPlot->id,
                        'latitude' => $row[0],
                        'longitude' => $row[1],
                    ]);
                }
            }
        }

        return redirect('/land-plot')->with('success', 'Land Plot updated successfully.');
    } catch (\Exception $e) {
        // Handle exceptions and redirect back with errors
        return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred while updating the data.'.$e->getMessage()]);
    }
}

public function ApprReject(Request $request)
    {
        $attachments = null;
        if ($request->has('appr_rej_doc')) {
            $path = $request->file('appr_rej_doc')->store('land_plot_documents', 'public');
            $attachments = $path;
        } elseif ($request->has('stored_appr_rej_doc')) {
            $attachments = $request->stored_appr_rej_doc;
        } else {
            $attachments = null;
        }

        $update = LandPlot::find($request->appr_rej_land_id);
        $approveDocument = Helper::approveDocument($update->book_id, $update->id, $update->revision_number ,$request->appr_rej_remarks, $attachments, $update->approvalLevel, $request->appr_rej_status);
        $update->approvalLevel = $approveDocument['nextLevel'];
        $update->approvalStatus = $approveDocument['approvalStatus'];
        $update->appr_rej_recom_remark = $request->appr_rej_remarks ?? null;
        $update->appr_rej_doc = $attachments;
        $update->appr_rej_behalf_of = $request->appr_rej_behalf_of ? json_encode($request->appr_rej_behalf_of) : null;

        $update->save();


        $creator_type = $update->landable_type;
        $created_by = $update->landable_id;
        $creator=null;

        if ($creator_type!=null) {
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

        $user = Helper::getAuthenticatedUser()->id;
        $approver = Helper::userCheck()['user_type'];
        $approver= $approver::find($user);


        if ($request->appr_rej_status =='approve') {
            LandNotificationController::notifyLandPlotApproved($creator,$update,$approver);
            return redirect("land-plot")->with(
                "success",
                "Approved Successfully!"
            );
        } else {
            LandNotificationController::notifyLandPlotReject($creator,$update,$approver);

            return redirect("land-plot")->with(
                "success",
                "Rejected Successfully!"
            );
        }


    }
    public function amendment(Request $request, $id)
    {
        $land_id = LandPlot::find($id);
            if (!$land_id) {
                return response()->json(['data' => [], 'message' => "Land Plot not found.", 'status' => 404]);
            }

            $revisionData = [
                ['model_type' => 'header', 'model_name' => 'LandPlot', 'relation_column' => ''],
            ];

            $a = Helper::documentAmendment($revisionData, $id);
       DB::beginTransaction();
        try {
            if ($a) {
                Helper::approveDocument($land_id->book_id, $land_id->id, $land_id->revision_number , 'Amendment', $request->file('attachment'), $land_id->approvalLevel, 'amendment');

                $land_id->approvalStatus = ConstantHelper::DRAFT;
                $land_id->revision_number = $land_id->revision_number + 1;
                $land_id->revision_date = now();
                $land_id->save();
            }

            DB::commit();
            return response()->json(['data' => [], 'message' => "Amendment done!", 'status' => 200]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Amendment Submit Error: ' . $e->getMessage());
            return response()->json(['data' => [], 'message' => "An unexpected error occurred. Please try again.", 'status' => 500]);
        }
    }

}
