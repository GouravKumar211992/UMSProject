<?php

namespace App\Http\Controllers\Land;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Item;
use GuzzleHttp\Client;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\Location;
use App\Models\LandParcel;
use App\Models\ErpLandCategory;
use App\Models\LandParcelHistory;
use App\Models\ErpDocument;
use Illuminate\Http\Request;
use App\Http\Requests\LandParcelRequest;
use App\Helpers\ConstantHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\CostCenter;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\LandNotificationController;
use App\Models\ItemAttribute;
use App\Models\ErpAttribute;
class LandParcelController extends Controller
{
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = new Client();
    }

    public function index()
    {
        $data = LandParcel::with("documentType")
            ->whereHas("documentType", function ($query) {
                $query->where("book_code", ConstantHelper::LAND_PARCEL);
            })
            ->withDefaultGroupCompanyOrg()
            ->withDraftListingLogic()
            ->orderByDesc("id");
        $lands = $data->get();
        $selectedDateRange = "";
        $dataCollection = $data->get();
        $pincode = $dataCollection->pluck("pincode")->unique();
        $land_no = $dataCollection->pluck("id");
        $selectedStatus = $dataCollection->pluck("document_status")->unique();
        $khasra = "";
        $plot = "";

        return view(
            "land.land-parcel.index",
            compact(
                "lands",
                "selectedDateRange",
                "pincode",
                "land_no",
                "selectedStatus",
                "khasra",
                "plot"
            )
        ); // Return the 'land.index' view
    }

    public function create()
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;
        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
        $locations = Location::where("land_parcel_id", "1")->get();
        $items = Item::where("organization_id", $organization_id)
            ->where("type", "service")
            ->with(["uom", "category","itemAttributes"])
            ->get();
        $doc_type = ErpDocument::where("organization_id", $organization_id)
            ->where("service", "land")
            ->where("status", "active")
            ->get();
        $items_array = Item::where("organization_id", $organization_id)
            ->where("type", "service")
            ->with(["uom", "category"]);
        $categories = ErpLandCategory::where("status", "1")->get();

       foreach($items as $item)
       {
            $itemId = $item->id;

            if (isset($itemId)) {
                $itemAttributes = ItemAttribute::where('item_id', $itemId) -> get();
            } else {
                $itemAttributes = [];
            }
            $processedData = [];
            foreach ($itemAttributes as $key => $attribute) {
                $attributesArray = array();
                $attribute_group_id = $attribute->attribute_group_id;
                $attribute->group_name = $attribute->group?-> name;

                $attributeValueData = ErpAttribute::whereIn('id', $attribute->attribute_id) -> select('id', 'value') -> where('status', 'active') -> get();

            $attribute->values_data = $attributeValueData;
            $attribute = $attribute -> only(['id','group_name', 'values_data', 'attribute_group_id']);

            array_push($processedData, ['id' => $attribute['id'], 'group_name' => $attribute['group_name'], 'values_data' => $attributeValueData, 'attribute_group_id' => $attribute['attribute_group_id']]);
            }
            $processedData = collect($processedData);

            $item->attributes = $processedData;
        }

        return view(
            "land.land-parcel.add",
            compact("categories", "series", "locations", "items", "doc_type")
        ); // Return the 'land.add' view
    }

    public function save(LandParcelRequest $request)
    {
        // Start transaction
        DB::beginTransaction();
        try {
            $json = [];
            if ($request->has("documentname")) {
                $i = 0;
                foreach ($request->documentname as $key => $names) {
                    $json[$i]["name"] = $names; // Store the document name in the array

                    if (isset($request->attachments[$key])) {
                        foreach (
                            $request->attachments[$key]
                            as $key1 => $document
                        ) {
                            $documentName =
                                time() .
                                "-" .
                                $document->getClientOriginalName();

                            // Move the document to public/documents folder
                            $document->move(
                                public_path("documents"),
                                $documentName
                            );

                            // Store the file name in the 'files' array for the current document
                            $json[$i]["files"][$key1] = $documentName;
                        }
                    }
                    $i++;
                }
            }

            $status = $request->status_val;
            $serviceItems = $request->input("service_item");

            $filteredItems = array_filter($serviceItems, function ($item) {
                return !(
                    is_null($item["'servicetype'"]) &&
                    is_null($item["'servicecode'"]) &&
                    is_null($item["'servicename'"]) &&
                    is_null($item["'ledger_code'"]) && 
                    is_null($item["'ledger_id'"]) && 
                    is_null($item["'ledger_group_id'"])
                );
            });


            $encodedItems = json_encode($filteredItems);
            $user = Helper::getAuthenticatedUser();
            $userData = Helper::userCheck();
            $organization = $user->organization;

            // Save land parcel data
            $landParcel = LandParcel::create([
                'organization_id' => $request->input('organization_id', $organization->id),
                'group_id' => $request->input('group_id', $organization->group_id),
                'company_id' => $request->input('company_id', $organization->company_id),
                'created_by' => $request->input('created_by', $user->id),
                'type' => $request->input('type', $userData['user_type']),
                "book_id" => $request->input("series"),
                "series_id" => $request->input("series"),
                "document_date" => Carbon::now()->format("Y-m-d"),
                "document_no" => $request->input("document_no"),
                "doc_number_type" => $request->input("doc_number_type"),
                "doc_reset_pattern" => $request->input("doc_reset_pattern"),
                "doc_prefix" => $request->input("doc_prefix"),
                "doc_suffix" => $request->input("doc_suffix"),
                "doc_no" => $request->input("doc_no"),
                "name" => $request->input("name"),
                "description" => $request->input("description"),
                "latitude" => $request->input("latitude"),
                "longitude" => $request->input("longitude"),
                "surveyno" => $request->input("surveyno"),
                "status" => $request->input("status"),
                "khasara_no" => $request->input("khasara_no"),
                "plot_area" => $request->input("plot_area"),
                "area_unit" => $request->input("area_unit"),
                "dimension" => $request->input("dimension"),
                "land_valuation" => $request->input("land_valuation") ?? 0.0,
                "address" => $request->input("address"),
                "district" => $request->input("district"),
                "state" => $request->input("state"),
                "country" => $request->input("country"),
                "pincode" => $request->input("pincode"),
                "remarks" => $request->input("remarks"),
                "handoverdate" => $request->input("handoverdate"),
                "attachments" => json_encode($json),
                "service_item" => $encodedItems,
                "document_status" => $status,
            ]);
            $update = LandParcel::find($landParcel->id);

//            $document_status = $request->status_val;
            if ($status == ConstantHelper::SUBMITTED) {
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remarks;
                $attachments = $request->file("attachments");
                $currentLevel = $update->approval_level;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = "submit"; // Approve // reject // submit
                $approveDocument = Helper::approveDocument(
                    $bookId,
                    $docId,
                    $revisionNumber,
                    $remarks,
                    $attachments,
                    $currentLevel,
                    $actionType
                );
                $document_status = Helper::checkApprovalRequired(
                    $request->book_id
                );
                $update->document_status = $document_status;
                $update->save();

                if($document_status==ConstantHelper::SUBMITTED){

                    if ($update->approvelworkflow->count() > 0) { // Check if the relationship has records
                        foreach ($update->approvelworkflow as $approver) {
                            if ($approver->user) { // Check if the related user exists
                                $approver_user = $approver->user;
                                LandNotificationController::notifyLandParcelSubmission($approver_user, $update);
                            }
                        }
                    }
            }
            } else {
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remarks;
                $attachments = $request->file("attachments");
                $currentLevel = $update->approval_level;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = "draft"; // Approve // reject // submit
                $approveDocument = Helper::approveDocument(
                    $bookId,
                    $docId,
                    $revisionNumber,
                    $remarks,
                    $attachments,
                    $currentLevel,
                    $actionType
                );
            }

            // Handle Geofence CSV upload and parse it
            if ($request->hasFile("geofence")) {
                $file = $request->file("geofence");
                $csvData = array_map("str_getcsv", file($file->getRealPath()));

                foreach ($csvData as $key => $row) {
                    if ($key != 0) {
                        Location::create([
                            "land_parcel_id" => $landParcel->id,
                            "name" => "-",
                            "latitude" => $row[0],
                            "longitude" => $row[1],
                        ]);
                    }
                }
            }

            // Commit transaction
            DB::commit();

            return redirect("/land-parcel")->with(
                "success",
                "Land Parcel information saved successfully."
            );
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;

        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
       $locations = Location::where("land_parcel_id", $id)->get();

        $data = LandParcel::where("id", $id)->first();
        $items = Item::where("organization_id", $organization_id)
            ->where("type", "service")
            ->with(["uom", "category"])
            ->get();

            $items_array = Item::where("organization_id", $organization_id)
            ->where("type", "service")
            ->with(["uom", "category"]);
        $categories = ErpLandCategory::where("status", "1")->get();

       foreach($items as $item)
       {
            $itemId = $item->id;

            if (isset($itemId)) {
                $itemAttributes = ItemAttribute::where('item_id', $itemId) -> get();
            } else {
                $itemAttributes = [];
            }
            $processedData = [];
            foreach ($itemAttributes as $key => $attribute) {
                $attributesArray = array();
                $attribute_group_id = $attribute->attribute_group_id;
                $attribute->group_name = $attribute->group?-> name;

                $attributeValueData = ErpAttribute::whereIn('id', $attribute->attribute_id) -> select('id', 'value') -> where('status', 'active') -> get();

            $attribute->values_data = $attributeValueData;
            $attribute = $attribute -> only(['id','group_name', 'values_data', 'attribute_group_id']);

            array_push($processedData, ['id' => $attribute['id'], 'group_name' => $attribute['group_name'], 'values_data' => $attributeValueData, 'attribute_group_id' => $attribute['attribute_group_id']]);
            }
            $processedData = collect($processedData);

            $item->attributes = $processedData;
        }


        $creatorType = Helper::userCheck()["type"];
        $amount = $data->land_valutaion == null ? 0 : $data->land_valutaion;
        $buttons = Helper::actionButtonDisplay(
            $data->book_id,
            $data->document_status,
            $data->id,
            $amount,
            $data->approval_level,
            $data->created_by,
            $creatorType
        );
        $history = Helper::getApprovalHistory($data->book_id, $id, 0);
        $page = "edit";
        $doc_type = ErpDocument::where("organization_id", $organization_id)
            ->where("service", "land")
            ->where("status", "active")
            ->get();

        return view(
            "land.land-parcel.edit",
            compact(
                "categories",
                "items",
                "series",
                "doc_type",
                "locations",
                'items_array',
                "data",
                "items",
                "buttons",
                "history",
                "page"
            )
        ); // Return the 'land.add' view
    }
    public function amendment(Request $request, $id)
    {
        $land_id = LandParcel::find($id);
        if (!$land_id) {
            return response()->json([
                "data" => [],
                "message" => "Land Parcel not found.",
                "status" => 404,
            ]);
        }

        $revisionData = [
            [
                "model_type" => "header",
                "model_name" => "LandParcel",
                "relation_column" => "",
            ],
        ];

        $a = Helper::documentAmendment($revisionData, $id);
        DB::beginTransaction();
        try {
            if ($a) {
                Helper::approveDocument(
                    $land_id->book_id,
                    $land_id->id,
                    $land_id->revision_number,
                    "Amendment",
                    $request->file("attachment"),
                    $land_id->approval_level,
                    "amendment"
                );

                $land_id->document_status = ConstantHelper::DRAFT;
                $land_id->revision_number = $land_id->revision_number + 1;
                $land_id->revision_date = now();
                $land_id->save();
            }

            DB::commit();
            return response()->json([
                "data" => [],
                "message" => "Amendment done!",
                "status" => 200,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Amendment Submit Error: " . $e->getMessage());
            return response()->json([
                "data" => [],
                "message" => "An unexpected error occurred. Please try again.",
                "status" => 500,
            ]);
        }
    }

    public function view(Request $r, $id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;



        $parentURL = request() -> segments()[0];
        

        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
           return redirect() -> route('/');
       }
       $firstService = $servicesBooks['services'][0];
       $series = Helper::getBookSeriesNew($firstService -> alias, $parentURL)->get();
       
       $locations = Location::where("land_parcel_id", $id)->get();
        $currNumber = $r->revisionNumber;
        $data = null;
        if ($currNumber != "") {
            $data = LandParcelHistory::where("source_id", $id)->first();
            $history = Helper::getApprovalHistory(
                $data->book_id,
                $id,
                $data->revision_number
            );

            if ($data != null) {
                $data["attachments"] = json_encode($data["attachments"]);
            }
        } else {
            $data = LandParcel::where("id", $id)->first();
            $history = Helper::getApprovalHistory(
                $data->book_id,
                $id,
                $data->revision_number
            );
        }
        $items = Item::where("organization_id", $organization_id)
            ->where("type", "service")
            ->with(["uom", "category"])
            ->get();
            foreach($items as $item)
            {


                 $itemId = $item->id;

                 if (isset($itemId)) {
                     $itemAttributes = ItemAttribute::where('item_id', $itemId) -> get();
                 } else {
                     $itemAttributes = [];
                 }
                 $processedData = [];
                 foreach ($itemAttributes as $key => $attribute) {
                     $attributesArray = array();
                     $attribute_group_id = $attribute->attribute_group_id;
                     $attribute->group_name = $attribute->group?-> name;

                     $attributeValueData = ErpAttribute::whereIn('id', $attribute->attribute_id) -> select('id', 'value') -> where('status', 'active') -> get();

                 $attribute->values_data = $attributeValueData;
                 $attribute = $attribute -> only(['id','group_name', 'values_data', 'attribute_group_id']);

                 array_push($processedData, ['id' => $attribute['id'], 'group_name' => $attribute['group_name'], 'values_data' => $attributeValueData, 'attribute_group_id' => $attribute['attribute_group_id']]);
                 }
                 $processedData = collect($processedData);

                 $item->attributes = $processedData;
             }


        if ($data != null) {
            //dd($data->approvelworkflow[0]->user);

            $creatorType = Helper::userCheck()["type"];
            $amount = $data->land_valutaion == null ? 0 : $data->land_valutaion;
            $buttons = Helper::actionButtonDisplay(
                $data->book_id,
                $data->document_status,
                $data->id,
                $amount,
                $data->approval_level,
                $data->created_by,
                $creatorType
            );

            $page = "edit";
            $doc_type = ErpDocument::where("organization_id", $organization_id)
                ->where("service", "land")
                ->where("status", "active")
                ->get();

            $revisionNumbers = $history
                ->pluck("revision_number")
                ->unique()
                ->values()
                ->all();
            $cost_centers = CostCenter::where(
                "organization_id",
                Helper::getAuthenticatedUser()->organization_id
            )
                ->select("id as value", "name as label")
                ->get()
                ->toArray();
            $books = Book::where("booktype_id", $data->book_id)
                ->select("id", "book_code as code", "book_name")
                ->get();

            $serviceAlias = ["land-parcel"];
            $bookTypes = Helper::getBookTypes($serviceAlias)->get();
            $categories = ErpLandCategory::where("status", "1")->get();

            return view(
                "land.land-parcel.view",
                compact(
                    "categories",
                    "books",
                    "bookTypes",
                    "doc_type",
                    "cost_centers",
                    "revisionNumbers",
                    "currNumber",
                    "series",
                    "locations",
                    "data",
                    "items",
                    "buttons",
                    "history",
                    "page"
                )
            ); // Return the 'land.add' view
        }
    }

    public function update(LandParcelRequest $request)
    {
        try{
        $id = $request->input("id");
        // Start transaction
        DB::beginTransaction();

         // Load the existing land parcel record
            $landParcel = LandParcel::findOrFail($id);

            $user = Helper::getAuthenticatedUser();
            $organization = $user->organization;
            $organization_id = $organization->id;
            $group_id = $organization->group_id;
            $company_id = $organization->company_id;
            $json = [];

            if ($request->has("documentname")) {
                $i = 0;
                foreach ($request->documentname as $key => $names) {
                    $json[$i]["name"] = $names; // Store the document name

                    // Handle new attachments
                    if (isset($request->attachments[$key])) {
                        foreach (
                            $request->attachments[$key]
                            as $key1 => $document
                        ) {
                            $documentName =
                                time() .
                                "-" .
                                $document->getClientOriginalName();

                            // Move the document to the public/documents folder
                            $document->move(
                                public_path("documents"),
                                $documentName
                            );

                            // Append the new file to the 'files' array for this document
                            $json[$i]["files"][] = $documentName;
                        }
                    }

                    // Handle old attachments
                    if (isset($request->oldattachments[$key])) {
                        foreach (
                            $request->oldattachments[$key]
                            as $key1 => $document1
                        ) {
                            // Append the old file to the 'files' array for this document
                            $json[$i]["files"][] = $document1;
                        }
                    }

                    $i++;
                }
            }
            $status = $request->status_val;
            $document_status = $request->status_val;
            $update = $landParcel;
            if ($status == ConstantHelper::SUBMITTED) {
                $bookId = $landParcel->book_id;
                $docId = $landParcel->id;
                $remarks = $landParcel->remarks;
                $attachments = $request->file("attachments");
                $currentLevel = $landParcel->approval_level;
                $revisionNumber = $landParcel->revision_number ?? 0;
                $actionType = "submit"; // Approve // reject // submit
                $approveDocument = Helper::approveDocument(
                    $bookId,
                    $docId,
                    $revisionNumber,
                    $remarks,
                    $attachments,
                    $currentLevel,
                    $actionType
                );
                $document_status = Helper::checkApprovalRequired(
                    $request->book_id
                );
            } else {
                $bookId = $update->book_id;
                $docId = $update->id;
                $remarks = $update->remarks;
                $attachments = $request->file("attachments");
                $currentLevel = $update->approval_level;
                $revisionNumber = $update->revision_number ?? 0;
                $actionType = "draft"; // Approve // reject // submit
                $approveDocument = Helper::approveDocument(
                    $bookId,
                    $docId,
                    $revisionNumber,
                    $remarks,
                    $attachments,
                    $currentLevel,
                    $actionType
                );
            }

            $serviceItems = $request->input("service_item");

            // Filter the items as needed
            $filteredItems = array_filter($serviceItems, function ($item) {
                return !(
                    is_null($item["'servicetype'"]) &&
                    is_null($item["'servicecode'"]) &&
                    is_null($item["'servicename'"])
                );
            });

            // Reindex the array to remove the numeric keys
            $filteredItems = array_values($filteredItems);

            // Encode the reindexed array into JSON
            $encodedItems = json_encode($filteredItems);

            // Update land parcel data
            $landParcel->update([
                "name" => $request->input("name"),
                "description" => $request->input("description"),
                "latitude" => $request->input("latitude"),
                "longitude" => $request->input("longitude"),
                "surveyno" => $request->input("surveyno"),
                "status" => $request->input("status"),
                "khasara_no" => $request->input("khasara_no"),
                "plot_area" => $request->input("plot_area"),
                "area_unit" => $request->input("area_unit"),
                "dimension" => $request->input("dimension"),
                "land_valuation" => $request->input("land_valuation"),
                "address" => $request->input("address"),
                "district" => $request->input("district"),
                "state" => $request->input("state"),
                "country" => $request->input("country"),
                "pincode" => $request->input("pincode"),
                "remarks" => $request->input("remarks"),
                "handoverdate" => $request->input("handoverdate"),
                "organization_id" => $organization_id,
                "attachments" => json_encode($json),
                "service_item" => $encodedItems,
                "document_status" => $document_status,
            ]);
            if($document_status==ConstantHelper::SUBMITTED){

                if ($update->approvelworkflow->count() > 0) { // Check if the relationship has records
                    foreach ($update->approvelworkflow as $approver) {
                        if ($approver->user) { // Check if the related user exists
                            $approver_user = $approver->user;
                            LandNotificationController::notifyLandParcelSubmission($approver_user, $update);
                        }
                    }
                }
        }

            // Handle Geofence CSV upload and parse it
            if ($request->hasFile("geofence")) {
                // Delete existing geofence records for this land parcel if necessary
                Location::where("land_parcel_id", $landParcel->id)->delete();

                $file = $request->file("geofence");
                $csvData = array_map("str_getcsv", file($file->getRealPath()));

                foreach ($csvData as $key => $row) {
                    if ($key != 0) {
                        Location::create([
                            "land_parcel_id" => $landParcel->id,
                            "latitude" => $row[0],
                            "longitude" => $row[1],
                        ]);
                    }
                }
            }

            // Commit transaction
            DB::commit();

            return redirect("/land-parcel")->with(
                "success",
                "Land Parcel information Updated successfully."
            );
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
   }
    }
    public function filter(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $organization_id = $organization->id;
        $group_id = $organization->group_id;
        $company_id = $organization->company_id;
        $userData = Helper::userCheck();
        $type = $userData['user_type'];

        // Fetch data related to lands and return the view
        $query = LandParcel::where("organization_id", $organization_id)
            ->where("created_by", $user->id)
            ->where("type", $type)
            ->orderby("id", "desc");
        // Apply filters based on the request

        if ($request->filled("date_range")) {
            $dates = explode(" to ", $request->input("date_range"));
            if (!empty($dates[1])) {
                $query->whereBetween("created_at", [
                    $dates[0] . " 00:00:00",
                    $dates[1] . " 23:59:59",
                ]);
            } else {
                $query->whereDate("created_at", $dates[0]);
            }
        }

        if ($request->filled("land_no")) {
            $query->where("id", $request->input("land_no"));
        }

        if ($request->filled("pincode")) {
            $query->where("pincode", $request->input("pincode"));
        }

        if ($request->filled("selectedStatus")) {
            $query->where("document_status", $request->input("selectedStatus"));
        }

        $selectedDateRange = "";
        $pincode = $query->distinct()->pluck("pincode");
        $land_no = $query->pluck("id");
        $selectedStatus = $query->distinct()->pluck("document_status");
        $khasra = "";
        $plot = "";
        $lands = $query->get();

        return view(
            "land.land-parcel.index",
            compact(
                "lands",
                "selectedDateRange",
                "pincode",
                "land_no",
                "selectedStatus",
                "khasra",
                "plot"
            )
        ); // Return the 'land.index' view
    }
    public function ApprReject(Request $request)
    {
        $attachments = null;
        $user = Helper::getAuthenticatedUser()->id;
        $approver = Helper::userCheck()['user_type'];
        $approver= $approver::find($user);

        if ($request->has("appr_rej_doc")) {
            $path = $request
                ->file("appr_rej_doc")
                ->store("land_parcel_documents", "public");
            $attachments = $path;
        } elseif ($request->has("stored_appr_rej_doc")) {
            $attachments = $request->stored_appr_rej_doc;
        } else {
            $attachments = null;
        }

        $update = LandParcel::find($request->appr_rej_land_id);
        $creator_type = $update->type;
        $created_by = $update->created_by;
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



        $approveDocument = Helper::approveDocument(
            $update->book_id,
            $update->id,
            $update->revision_number,
            $request->appr_rej_remarks,
            $attachments,
            $update->approval_level ??0,
            $request->appr_rej_status
        );
        $update->approval_level = $approveDocument["nextLevel"];
        $update->document_status = $approveDocument["approvalStatus"];
        $update->appr_rej_recom_remark = $request->appr_rej_remarks ?? null;
        $update->appr_rej_doc = $attachments;
        $update->appr_rej_behalf_of = $request->appr_rej_behalf_of
            ? json_encode($request->appr_rej_behalf_of)
            : null;

        $update->save();

        if ($request->appr_rej_status =='approve') {
            LandNotificationController::notifyLandParcelApproved($creator,$update,$approver);
            return redirect("land-parcel")->with(
                "success",
                "Approved Successfully!"
            );
        } else {
            LandNotificationController::notifyLandParcelReject($creator,$update,$approver);

            return redirect("land-parcel")->with(
                "success",
                "Rejected Successfully!"
            );
        }
    }
}
