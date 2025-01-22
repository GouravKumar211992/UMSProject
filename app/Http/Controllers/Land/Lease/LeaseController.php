<?php

namespace App\Http\Controllers\Land\Lease;

use Carbon\Carbon;
use App\Models\Land;
use App\Models\Lease;
use App\Models\State;
use App\Helpers\Helper;
use App\Helpers\TaxHelper;
use App\Models\Country;
use App\Models\Customer;
use App\Models\LandLease;
use App\Models\LandLeaseAction;
use App\Models\LandLeaseHistory;
use App\Models\CurrencyExchange;
use App\Models\LandParcel;
use App\Models\ErpDocument;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\LandLeasePlot;
use App\Models\NumberPattern;
use App\Helpers\CurrencyHelper;
use App\Models\LandLeaseAddress;
use App\Models\LandLeaseDocument;
use App\Helpers\ConstantHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LandLeaseOtherCharges;
use Illuminate\Support\Facades\Response;
use App\Models\LandLeaseScheduler;
use App\Http\Requests\Lease\CreateLeaseRequest;
use App\Http\Controllers\LandNotificationController;
use App\Models\Employee;
use App\Models\User;
use App\Models\ErpItem;

class LeaseController extends Controller
{
    public function index()
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;
        $userData = Helper::userCheck();
        $type = $userData['type'];
        $user_type = $userData['user_type'];


        $leasesQuery = LandLease::with('series', 'plots.land', 'customer')
            ->where('organization_id', $organization_id)
            ->where('leaseable_id',$user->id)
            ->where('leaseable_type',$user_type);

        // Fetch distinct values without altering the main query
        $document_no = (clone $leasesQuery)->distinct()->pluck('document_no');
        $selectedStatus = (clone $leasesQuery)->distinct()->pluck('approvalStatus');

        // Fetch all leases with ordering
        $leases = $leasesQuery->orderby('id', 'desc')->get();

        $selectedDateRange = '';
        $pincode = '';
        $land_no = '';
        return view('land.lease.index', compact('leases', 'selectedDateRange', 'document_no', 'land_no', 'selectedStatus')); // Return the 'land.onlease' view
    }

    public function show(Request $r, $id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;
        $userData = Helper::userCheck();
        $type = $userData['type'];
        $user_type = $userData['user_type'];

        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $book_type = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
       $user = Helper::getAuthenticatedUser();
        $organizationId = $user->organization_id;
        $customers = Customer::with(['erpOrganizationType', 'category', 'subcategory', 'addresses', 'currency'])
            ->where('organization_id', $organizationId)
            ->get();
        //$currencies = Currency::where('organization_id', $organizationId)->get();
        $countries = Country::where('id', '101')->get();
        $states = State::where('country_id', '101')->get();
        $doc_type = ErpDocument::where('organization_id', $organization_id)
            ->where('service', 'land')->where('status', 'active')
            ->get();
        $lands = LandParcel::where('organization_id', $organization_id)
            ->where('created_by', $user->id)
            ->where('type', $type)
            ->get();


        $currNumber = $r->revisionNumber;
        if ($currNumber != "") {
            $lease = LandLeaseHistory::with('series', 'currency', 'exchange', 'plots.land', 'plots.plot', 'customer', 'otherCharges', 'address.state', 'address.city', 'document')->where('organization_id', $organization_id)
                // Legals created by the user
                ->where('leaseable_id', $user->id)
                ->where('leaseable_type', $user_type)
                ->orderby('id', 'desc')->where('source_id', $id)->first(); // Fetch all leases




        } else {

            $lease = LandLease::with('actions', 'series', 'currency', 'exchange', 'plots.land', 'plots.plot', 'customer', 'otherCharges', 'address.state', 'address.city', 'document')->where('organization_id', $organization_id)
                // Legals created by the user
                ->where('leaseable_id', $user->id)
                ->where('leaseable_type', $user_type)
                ->orderby('id', 'desc')->findOrFail($id); // Fetch all leases



        }
        if (isset($lease->leaseable_type)) {
            $creatorType = explode("\\", $lease->leaseable_type);
            $creatorType = strtolower(end($creatorType));
        }
        $creatorType = "";



        $buttons = Helper::actionButtonDisplay(
            $lease->book_id,
            $lease->approvalStatus,
            $id,
            $lease->total_amount,
            $lease->approvalLevel,
            $lease->leaseable_id,
            $creatorType,
            $lease->revision_number
        );

        $history = Helper::getApprovalHistory($lease->book_id, $id, $lease->revision_number, $lease->total_amount);

        $revisionNumbers = $history->pluck('revision_number')->unique()->values()->all();


        $page = 'view_detail';



        return view('land.lease.show', compact('currNumber', 'page', 'revisionNumbers', 'buttons', 'history', 'lease', 'book_type', 'customers', 'countries', 'states', 'doc_type', 'lands')); // Return the 'land.onlease' view
    }
    public function destroy($id)
    {
        try {
            $bank = LandLease::findOrFail($id);
            $referenceTables = [
                'erp_land_lease_plots' => ['lease_id'],
                'erp_land_lease_other_charges' => ['lease_id'],
                'land_lease_addresses' => ['lease_id'],
                'erp_land_lease_documents' => ['lease_id'],
                'erp_land_leases_actions' => ['source_id'],
            ];
            $result = $bank->deleteWithReferences($referenceTables);

            if (!$result['status']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'referenced_tables' => $result['referenced_tables'] ?? []
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => 'Record deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the bank: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function edit($id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;
        $userData = Helper::userCheck();
        $type = $userData['type'];
        $user_type = $userData['user_type'];

        $lease = LandLease::with('series', 'currency', 'exchange', 'plots.land', 'plots.plot', 'schedule', 'customer', 'otherCharges', 'address.state', 'address.city', 'document')->where('organization_id', $organization_id)
            // Legals created by the user
            ->where('leaseable_id', $user->id)
            ->where('leaseable_type', $user_type)
            ->orderby('id', 'desc')->findOrFail($id); // Fetch all leases
            $parentURL = request() -> segments()[0];
        

            $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
            if (count($servicesBooks['services']) == 0) {
               return redirect() -> route('/');
           }
           $firstService = $servicesBooks['services'][0];
           $book_type = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
           
           $user = Helper::getAuthenticatedUser();
        $organizationId = $user->organization_id;
        $customers = Customer::with(['erpOrganizationType', 'category', 'subcategory', 'addresses', 'currency'])
            ->where('organization_id', $organizationId)
            ->get();
        //$currencies = Currency::where('organization_id', $organizationId)->get();
        $countries = Country::where('id', '101')->get();
        $states = State::where('country_id', '101')->get();
        $doc_type = ErpDocument::where('organization_id', $organization_id)
            ->where('service', 'land')->where('status', 'active')
            ->get();
        $lands = LandParcel::where('organization_id', $organization_id)
            ->where('created_by', $user->id)
            ->where('type', $type)
            ->get();

        $creatorType = explode("\\", $lease->leaseable_type);
        $creatorType = strtolower(end($creatorType));


        $buttons = Helper::actionButtonDisplay(
            $lease->book_id,
            $lease->approvalStatus,
            $id,
            $lease->total_amount,
            $lease->approvalLevel,
            $lease->leaseable_id,
            $creatorType,
            $lease->revision_number
        );


        return view('land.lease.edit', compact('buttons', 'lease', 'book_type', 'customers', 'countries', 'states', 'doc_type', 'lands')); // Return the 'land.onlease' view
    }
    public function taxCalculation(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $firstAddress = $organization->addresses->first();

        if ($firstAddress) {
            $companyCountryId = $firstAddress->country_id;
            $companyStateId = $firstAddress->state_id;
        } else {
            return response()->json(['error' => 'No address found for the organization.'], 404);
        }
        $price = $request->input('price', 6000);
        $landid = LandParcel::find($request->landid);

        $item = (json_decode($landid->service_item));

        $item_id = $request->input('item_id', 3);
        $hsnId = null;
        $itemdetail = '';

        // if (!empty($item[0]->{"'servicecode'"})) {
        //     $itemdetail = $item[0]->{"'servicecode'"};
        // } else if (!empty($item[1]->{"'servicecode'"})) {
        //     $itemdetail = $item[1]->{"'servicecode'"};
        if (!empty($item)) {
            $serviceCodes = array_filter($item, function ($items) {
                return in_array($items->{"'servicetype'"}, ConstantHelper::LEASE_SERVICE_TYPE);
            });

            // Extract service codes from the filtered data
            $itemdetail = array_map(function ($items) {
                return $items->{"'servicecode'"};
            }, $serviceCodes);
        } else {
            $itemdetail = '';
        }
        $item = Item::where('item_code', $itemdetail[0])->first();
        if (isset($item)) {
            $hsnId = $item->hsn_id;
        } else {
            return response()->json(['error' => 'Invalid Item'], 500);
        }
        $transactionType = $request->input('transaction_type', 'sale');
        if ($transactionType === "sale") {
            $fromCountry = $companyCountryId;
            $fromState = $companyStateId;
            $upToCountry = $request->input('party_country_id', $companyCountryId);
            $upToState = $request->input('party_state_id', $companyStateId);
        } else {
            $fromCountry = $request->input('party_country_id', $companyCountryId);
            $fromState = $request->input('party_state_id', $companyStateId);
            $upToCountry = $companyCountryId;
            $upToState = $companyStateId;
        }

        try {
            $taxDetails = TaxHelper::calculateTax($hsnId, $price, $fromCountry, $fromState, $upToCountry, $upToState, $transactionType);
            $rowCount = intval($request->rowCount) ?? 1;
            $itemPrice = intval($request->price) ?? 0;

            return response()->json($taxDetails);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLandParcelData($land_id)
    {
        $land_data = LandParcel::with('plot')->where('id', $land_id)->get();

        return response()->json([
            'status' => 200,
            'data' => $land_data
        ]);
    }

    public function create(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;
        $userData = Helper::userCheck();
        $type = $userData['type'];

        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $book_type = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
       $lands = LandParcel::with([
            'plots' => function ($query) {
                $query->whereDoesntHave('leasePlots');
            },
            'countryRelation',
            'stateRelation'
        ])
            ->whereRaw('(SELECT COUNT(*) FROM erp_land_plots WHERE erp_land_plots.land_id = erp_land_parcels.id) >
                    (SELECT COUNT(*) FROM erp_land_lease_plots WHERE erp_land_lease_plots.land_parcel_id = erp_land_parcels.id)')
            ->where('organization_id', $organization_id)
            ->where('created_by', $user->id)
            ->where('type', $type)
            ->get();
        $user = Helper::getAuthenticatedUser();
        
        $organizationId = $user->organization_id;
        $customers = Customer::with(['erpOrganizationType', 'category', 'subcategory', 'addresses', 'currency'])
            ->where('organization_id', $organizationId)
            ->get();
        //$currencies = Currency::where('organization_id', $organizationId)->get();
        $countries = Country::where('id', '101')->get();
        $states = State::where('country_id', '101')->get();
        $doc_type = ErpDocument::where('organization_id', $organization_id)
            ->where('service', 'land')->where('status', 'active')
            ->get();


        return view('land.lease.create', compact('book_type', 'lands', 'customers', 'countries', 'states', 'doc_type')); // Redirect back to the on lease page
    }

    public function store(CreateLeaseRequest $request)
    {

        // Attempt to save the data
        try {
            DB::beginTransaction();


            $lease = LandLease::createUpdateLease(request: $request);

            if ($lease) {
                //dd($lease);
                LandLeaseAddress::createUpdateAddress(request: $request, lease: $lease);
                //LandLeaseDocument::createUpdateDocument(request: $request, lease: $lease);
                LandLeasePlot::createPlot(request: $request, lease: $lease);
                if (!empty($request->other_charges) && count($request->other_charges) > 0) {
                    LandLeaseOtherCharges::createOtherCharges(request: $request, lease: $lease);
                }

                if (!empty($request->sc) && count($request->sc) > 0) {
                    LandLeaseScheduler::createUpdateScheduler($request, $lease->id);
                }



                DB::commit();
                return redirect()->route('lease.index')->with('success', 'Lease information saved successfully.');
            } else {
                return redirect()->route('lease.create')->with('error', 'Something went wrong.');
            }
        } catch (\Exception $e) {

            DB::rollBack();
            // Redirect back with input data and an error message if something goes wrong
            return redirect()->back()->withInput()->withErrors(['error' => "An error occurred while saving the data. " . $e->getMessage()]);
        }
    }
    public function update(CreateLeaseRequest $request)
    {
        // Attempt to save the data
        $edit_id = $request->input('edit_id');
        try {
            DB::beginTransaction();

            $lease = LandLease::createUpdateLease(request: $request, edit_id: $edit_id);
            if ($lease) {
                LandLeaseAddress::createUpdateAddress(request: $request, lease: $lease, edit_lease_id: $edit_id);
                //LandLeaseDocument::createUpdateDocument(request: $request, lease: $lease);
                LandLeasePlot::createPlot(request: $request, lease: $lease, edit_lease_id: $edit_id);
                if (!empty($request->other_charges) && count($request->other_charges) > 0) {
                    LandLeaseOtherCharges::createOtherCharges(request: $request, lease: $lease, edit_lease_id: $edit_id);
                }
                if (!empty($request->sc) && count($request->sc) > 0) {
                    LandLeaseScheduler::createUpdateScheduler($request, $lease->id);
                }


                DB::commit();
                return redirect()->route('lease.index')->with('success', 'Lease information saved successfully.');
            } else {
                return redirect()->route('lease.edit', $edit_id)->with('error', 'Something went wrong.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // Redirect back with input data and an error message if something goes wrong
            return redirect()->back()->withInput()->withErrors(['error' => "An error occurred while saving the data. " . $e->getMessage()]);
        }
    }
    public function amendment(Request $request, $id)
    {
        $land_id = LandLease::find($id);
        if (!$land_id) {
            return response()->json(['data' => [], 'message' => "Land Lease not found.", 'status' => 404]);
        }

        $revisionData = [
            ['model_type' => 'header', 'model_name' => 'LandLease', 'relation_column' => ''],
        ];

        $a = Helper::documentAmendment($revisionData, $id);
        DB::beginTransaction();
        try {
            if ($a) {
                Helper::approveDocument($land_id->book_id, $land_id->id, $land_id->revision_number, 'Amendment', $request->file('attachment'), $land_id->approvalLevel, 'amendment', $land_id->total_amount);

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


    public function getExchangeRate($id)
    {

        $date = Carbon::now();
        try {
            $exchangeRate = CurrencyHelper::getCurrencyExchangeRates($id, $date);

            $data = array(
                'currency_id' => $exchangeRate['data']['org_currency_id'],
                'currency_code' => $exchangeRate['data']['org_currency_code'],
                'exchange_rate_id' => $exchangeRate['data']['org_currency_exg_rate'],
                'exchange_rate' => $exchangeRate['data']['org_currency_exg_rate']
            );

            return response()->json($data);
        } catch (\Exception $e) {
            return 'error';
        }
    }

    public function customerAddressStore(Request $request)
    {

        // Validate the request
        $validatedData = $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'customer_id' => 'required|exists:erp_customers,id',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'required|string|max:255',
            'postalcode' => 'required|string|max:10',
        ]);

        // Return a success message or a redirect
        return response()->json([
            'message' => 'Customer address saved successfully!',
            'data' => '', //$customerAddress
        ]);
    }

    public function leaseFilterLand(Request $request)
    {
        $land_id = $request->query('landId');
        $plot_id = $request->query('plotId');
        $districted_id = $request->query('districtId');
        $state_id = $request->query('stateId');

        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;
        $userData = Helper::userCheck();
        $type = $userData['type'];

        $query = LandParcel::with([
            'plots' => function ($query) {
                $query->whereDoesntHave('leasePlots');
            },
            'countryRelation',
            'cityRelation',
            'stateRelation'
        ])
            ->whereRaw('(SELECT COUNT(*) FROM erp_land_plots WHERE erp_land_plots.land_id = erp_land_parcels.id) > (SELECT COUNT(*) FROM erp_land_lease_plots WHERE erp_land_lease_plots.land_parcel_id = erp_land_parcels.id)')
            ->where('organization_id', $organization_id)
            ->where('created_by', $user->id)
            ->where('type', $type);

        if ($land_id) {
            $query->where('id', $land_id);
        }
        if ($plot_id) {
            $query->whereHas('plots', function ($q) use ($plot_id) {
                $q->where('id', $plot_id);
            });
        }
        if ($districted_id) {
            $query->where('id', $districted_id);
        }
        if ($state_id) {
            $query->where('state', $state_id);
        }
        $land_filter_list = $query->get();

        return Response::json(compact('land_filter_list'));
    }
    public function leasefilter(Request $request)
    {    $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;
        $userData = Helper::userCheck();
        $type = $userData['type'];
        $user_type = $userData['user_type'];


        $query = LandLease::with('series', 'plots.land', 'customer')->where('organization_id', $organization_id)
            // Legals created by the user
            ->where('leaseable_id', $user->id)
            ->where('leaseable_type', $user_type)
            ->orderby('id', 'desc');
        $document_no = $query->distinct()->pluck('document_no');
        $selectedStatus = $query->distinct()->pluck('approvalStatus');
        // Filter by date range
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $start_date = Carbon::createFromFormat('Y-m-d', $dates[0])->startOfDay();
                $end_date = Carbon::createFromFormat('Y-m-d', $dates[1])->endOfDay();
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }
        }

        // Filter by pincode (from the `land` table)
        if ($request->filled('document_no')) {
            $query->where('document_no', 'like', '%' . $request->document_no . '%');
        }

        // Filter by status
        if ($request->filled('selectedStatus')) {
            $query->where('approvalStatus', $request->selectedStatus);
        }



        $leases = $query->get();

        $selectedDateRange = '';
        $pincode = '';
        $land_no = '';
        return view('land.lease.index', compact('leases', 'selectedDateRange', 'document_no', 'land_no', 'selectedStatus')); // Return the 'land.onlease' view

    }
    public function ApprReject(Request $request)
    {
        $attachments = null;
        if ($request->has('appr_rej_doc')) {
            $path = $request->file('appr_rej_doc')->store('lease_documents', 'public');
            $attachments = $path;
        } elseif ($request->has('stored_appr_rej_doc')) {
            $attachments = $request->stored_appr_rej_doc;
        } else {
            $attachments = null;
        }

        $update = LandLease::find($request->appr_rej_lease_id);
        $approveDocument = Helper::approveDocument($update->book_id, $update->id, $update->revision_number, $request->appr_rej_remarks, $attachments, $update->approvalLevel, $request->appr_rej_status);
        $update->approvalLevel = $approveDocument['nextLevel'];
        $update->approvalStatus = $approveDocument['approvalStatus'];
        $update->appr_rej_recom_remark = $request->appr_rej_remarks ?? null;
        $update->appr_rej_doc = $attachments;
        $update->appr_rej_behalf_of = $request->appr_rej_behalf_of ? json_encode($request->appr_rej_behalf_of) : null;

        $update->save();

        $creator_type = $update->leaseable_type;
        $created_by = $update->leaseable_id;
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
            LandNotificationController::notifyLeaseApproved($creator,$update,$approver);
            return redirect("lease.index")->with(
                "success",
                "Approved Successfully!"
            );
        } else {
            LandNotificationController::notifyLeaseReject($creator,$update,$approver);

            return redirect("lease.index")->with(
                "success",
                "Rejected Successfully!"
            );
        }

    }
    public function action(Request $request)
    {
        // Validate request data
        $request->validate([
            'source_id' => 'required|exists:erp_land_leases,id', // Ensure source_id exists in the leases table
            'action' => 'required|string|in:terminate,close,renew,reminder',
            'comment' => 'nullable|string',
            'attachments' => 'nullable',
            'action_date' => 'nullable|date'
        ]);

        // Find the lease action (if needed, based on your logic)

        // Pass the action and the entire request to performAction
        $result = LandLeaseAction::performAction($request);

        if ($result['type'] == "success") {
            $update = LandLease::find($request->source_id);
            $bookId = $update->book_id;
            $docId = $update->id;
            $remarks = $request->comment;
            $attachments = $request->file('attachments');
            $currentLevel = $update->approvalLevel;
            $revisionNumber = $update->revision_number ?? 0;
            $actionType = $request->action; // Approve // reject // submit
            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber, $remarks, $attachments, $currentLevel, $actionType);

            return redirect()->route('lease.index')->with('success', $result['message']);
        } else
            return redirect()->back()->with('error', $result['message']);
    }
}
