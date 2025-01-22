<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Compliance;
use App\Models\Currency;
use App\Models\Country;
use App\Models\State;
use App\Models\Item;
use App\Models\Unit;
use App\Models\City;
use App\Models\BankInfo;
use App\Models\VendorAddress;
use App\Models\ErpAddress;
use App\Models\PaymentTerm;
use App\Models\OrganizationType;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Http\Requests\VendorRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\CommonService;
use App\Helpers\ConstantHelper;
use App\Helpers\FileUploadHelper; 
use App\Helpers\Helper;
use App\Models\Organization;
use App\Models\VendorItem;
use App\Models\Group;
use App\Models\Contact;
use App\Models\User;
use App\Models\VendorPortalBook;
use App\Models\VendorPortalUser;
use App\Models\Book;
use Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
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
            $organization = Organization::where('id', $user->organization_id)->first(); 
            $organizationId = $organization?->id ?? null;
            $companyId = $organization?->company_id ?? null;
        
            if ($request->ajax()) {
                $query = Vendor::with(['erpOrganizationType', 'category', 'subcategory'])
                ->withDefaultGroupCompanyOrg();
        
                if ($request->has('vendor_type') && !empty($request->vendor_type)) {
                    $query->where('vendor_type', $request->vendor_type);
                }
        
                if ($categoryId = request('category_id')) {
                    $query->where('category_id', $categoryId);
                }
        
                if ($subcategoryId = request('subcategory_id')) {
                    $query->where('subcategory_id', $subcategoryId);
                }
        
                if ($request->has('status') && !empty($request->status)) {
                    $query->where('status', $request->status);
                }
        
                $vendors = $query->orderBy('id', 'ASC')->get();
        
                return DataTables::of($vendors)
                    ->addIndexColumn()
                    ->addColumn('vendor_code', function ($row) {
                        return $row->vendor_code ?? 'N/A';
                    })
                    ->addColumn('company_name', function ($row) {
                        return $row->company_name ?? 'N/A';
                    })
                    ->addColumn('vendor_type', function ($row) {
                        return $row->vendor_type ?? 'N/A';
                    })
                    ->addColumn('phone', function ($row) {
                        return $row->phone ?? 'N/A';
                    })
                    ->addColumn('email', function ($row) {
                        return $row->email ?? 'N/A';
                    })
                    ->addColumn('status', function ($row) {
                        $statusClass = 'badge-light-secondary';
                        if ($row->status == 'active') {
                            $statusClass = 'badge-light-success';
                        } elseif ($row->status == 'inactive') {
                            $statusClass = 'badge-light-danger';
                        } elseif ($row->status == 'draft') {
                            $statusClass = 'badge-light-warning';
                        }
        
                        return '<span class="badge rounded-pill ' . $statusClass . ' badgeborder-radius">'
                            . ucfirst($row->status ?? 'Unknown') . '</span>';
                    })
                    ->addColumn('action', function ($row) {
                        return '
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="' . route('vendor.edit', $row->id) . '">
                                        <i data-feather="edit-3" class="me-50"></i>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </div>';
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }
        
            $categories = Category::where('organization_id', $organizationId)
                ->where('status', ConstantHelper::ACTIVE)
                ->whereNull('parent_id') 
                ->get();
        
            return view('procurement.vendor.index', compact('categories'));
        }
    

        public function create()
        {
            $organizationTypes = OrganizationType::where('status', ConstantHelper::ACTIVE)->get();
            $categories = Category::where('status', ConstantHelper::ACTIVE)->whereNull('parent_id')->withDefaultGroupCompanyOrg()->get();
            $currencies = Currency::where('status', ConstantHelper::ACTIVE)->get();
            $paymentTerms = PaymentTerm::where('status', ConstantHelper::ACTIVE)->withDefaultGroupCompanyOrg()->get();
            $titles = ConstantHelper::TITLES;
            $status = ConstantHelper::STATUS;
            $options = ConstantHelper::STOP_OPTIONS;
            $vendorTypes = ConstantHelper::VENDOR_TYPES;
            $addressTypes = ConstantHelper::ADDRESS_TYPES;
            $countries = Country::where('status', 'active')->get();

            $supplierUsers = User::where('user_type','IAM-SUPPLIER')->get();
            $serviceAlias = ConstantHelper::SUPPLIER_INVOICE_SERVICE_ALIAS;
            $books = Helper::getBookSeries($serviceAlias)->get();
            return view('procurement.vendor.create', [
                'organizationTypes' => $organizationTypes,
                'categories' => $categories,
                'titles' => $titles,
                'currencies' => $currencies,
                'paymentTerms' => $paymentTerms,
                'status'=>$status,
                'options'=>$options,
                'vendorTypes'=>$vendorTypes,
                'countries'=>$countries,
                'addressTypes'=>$addressTypes,
                'supplierUsers' => $supplierUsers,
                'books' => $books
            ]);
        }

        public function store(VendorRequest $request)
        {
            DB::beginTransaction();
          try {
            $user = Helper::getAuthenticatedUser();
            $organization = $user->organization;
            $validatedData = $request->validated();
            $validatedData['related_party'] = isset($validatedData['related_party']) ? 'Yes' : 'No';
            $validatedData['organization_id'] = $organization->id;
            $validatedData['group_id'] = $organization->group_id;
            $validatedData['company_id'] = $organization->company_id;
            if ($request->document_status === 'submitted') {
                $validatedData['status'] =  $validatedData['status'] ?? ConstantHelper::ACTIVE;
            } else {
                $validatedData['status'] = ConstantHelper::DRAFT;
            }
            $vendor = Vendor::create($validatedData);
            $fileConfigs = [
                'pan_attachment' => ['folder' => 'pan_attachments', 'clear_existing' => true],
                'tin_attachment' => ['folder' => 'tin_attachments', 'clear_existing' => true],
                'aadhar_attachment' => ['folder' => 'aadhar_attachments', 'clear_existing' => true],
                'other_documents' => ['folder' => 'other_documents', 'clear_existing' => true],
            ];
            
            $this->fileUploadHelper->handleFileUploads($request, $vendor, $fileConfigs);
            
            
            $bankInfoData = $validatedData['bank_info'] ?? [];
            if (!empty($bankInfoData)) {
                $this->commonService->createBankInfo($bankInfoData, $vendor);
            }
            // Handle notes
            $notesData = $validatedData['notes'] ?? [];
            if (!empty($notesData)) {
                $this->commonService->createNote($notesData, $vendor, $user);
            }
            
            $contacts = $validatedData['contacts'] ?? [];
            if (!empty($contacts)) {
                $this->commonService->createContact($contacts, $vendor);
            }
        

            $addresses = $validatedData['addresses'] ?? [];
            if (!empty($addresses)) {
                $this->commonService->createAddress($addresses, $vendor);
            }

            $compliance = $validatedData['compliance'] ?? [];
            if (!empty($compliance)) {
                $this->commonService->createCompliance($compliance, $vendor);
            }

            if ($request->has('vendor_item')) {
                foreach ($request->input('vendor_item') as $vendorItemData) {
                    if (!empty($vendorItemData['item_code']) && !empty($vendorItemData['item_name'])) {
                        $vendor->approvedItems()->create([
                            'item_id' => $vendorItemData['item_id'],
                            'item_code' => $vendorItemData['item_code'] ?? null, 
                            'cost_price' => $vendorItemData['cost_price'] ?? null, 
                            'uom_id' => $vendorItemData['uom_id']?? null,
                        ]);
                    }
                }
            }
            
              // Step 7: Synchronize Vendor Books
            $bookIds = $request->book_id ?? [];
            if (!empty($bookIds)) {
                VendorPortalBook::where('vendor_id', $vendor->id)
                    ->whereNotIn('book_id', $bookIds)
                    ->delete();
                foreach ($bookIds as $bookId) {
                    $book = Book::find($bookId);
                    if ($book) {
                        VendorPortalBook::updateOrCreate(
                            ['vendor_id' => $vendor->id, 'book_id' => $bookId],
                            ['service_id' => $book->service_id]
                        );
                    }
                }
            } else {
                VendorPortalBook::where('vendor_id', $vendor->id)->delete();
            }

            // Step 8: Synchronize Vendor Users
            $userIds = $request->user_id ?? [];
            if (!empty($userIds)) {
                VendorPortalUser::where('vendor_id', $vendor->id)
                    ->whereNotIn('user_id', $userIds)
                    ->delete();
                foreach ($userIds as $userId) {
                    VendorPortalUser::updateOrCreate(
                        ['vendor_id' => $vendor->id, 'user_id' => $userId]
                    );
                }
            } else {
                VendorPortalUser::where('vendor_id', $vendor->id)->delete();
            }

            DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Record created successfully',
            'data' => $vendor,
        ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Failed to create record',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
        
        public function show($id)
        {
        //
        }

        public function edit($id)
        {
            $vendor = Vendor::findOrFail($id);
            $organizationTypes = OrganizationType::where('status', ConstantHelper::ACTIVE)->get();
            $categories = Category::where('status', ConstantHelper::ACTIVE)->whereNull('parent_id')->withDefaultGroupCompanyOrg()->get();
            $subcategories = Category::where('status', ConstantHelper::ACTIVE)->whereNotNull('parent_id')->withDefaultGroupCompanyOrg()->get();
            $currencies = Currency::where('status', ConstantHelper::ACTIVE)->get();
            $paymentTerms = PaymentTerm::where('status', ConstantHelper::ACTIVE)->withDefaultGroupCompanyOrg()->get();
            $titles = ConstantHelper::TITLES;
            $notificationData = $vendor? $vendor->notification : [];
            $notifications = is_array($notificationData) ? $notificationData : json_decode($notificationData, true);
            $notifications = $notifications ?? [];
            $status = ConstantHelper::STATUS;
            $options = ConstantHelper::STOP_OPTIONS;
            $vendorTypes = ConstantHelper::VENDOR_TYPES;
            $addressTypes = ConstantHelper::ADDRESS_TYPES;
            $countries = Country::where('status', 'active')->get();
            $supplierUsers = User::where('user_type','IAM-SUPPLIER')->get();
            $serviceAlias = ConstantHelper::SUPPLIER_INVOICE_SERVICE_ALIAS;
            $books = Helper::getBookSeries($serviceAlias)->get();
            $ledgerGroups = Group::where('status',1)->get();
            return view('procurement.vendor.edit', [
                'vendor' => $vendor,
                'organizationTypes' => $organizationTypes,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'titles' => $titles,
                'currencies' => $currencies,
                'paymentTerms' => $paymentTerms,
                'notifications' => $notifications,
                'status'=>$status,
                'options'=>$options,
                'vendorTypes'=>$vendorTypes,
                'countries'=>$countries,
                'addressTypes'=>$addressTypes,
                'supplierUsers' => $supplierUsers,
                'ledgerGroups' => $ledgerGroups,
                'books' => $books
            ]);
        }


        public function update(VendorRequest $request, $id)
        {
            DB::beginTransaction();

            try {
            $user = Helper::getAuthenticatedUser();
            $organization = $user->organization;
            $validatedData = $request->validated();
            $validatedData['related_party'] = isset($validatedData['related_party']) ? 'Yes' : 'No';
            $validatedData['organization_id'] = $organization->id;
            $validatedData['group_id'] = $organization->group_id;
            $validatedData['company_id'] = $organization->company_id;
            $vendor = Vendor::findOrFail($id);
            if ($request->input('document_status') === 'submitted') {
                $validatedData['status'] =  $validatedData['status'] ?? ConstantHelper::ACTIVE;
            } else {
                $validatedData['status'] = ConstantHelper::DRAFT;
            }
            $vendor->update($validatedData);

            $fileConfigs = [
                'pan_attachment' => ['folder' => 'pan_attachments', 'clear_existing' => true],
                'tin_attachment' => ['folder' => 'tin_attachments', 'clear_existing' => true],
                'aadhar_attachment' => ['folder' => 'aadhar_attachments', 'clear_existing' => true],
                'other_documents' => ['folder' => 'other_documents', 'clear_existing' => true],
            ];

            $this->fileUploadHelper->handleFileUploads($request, $vendor, $fileConfigs);

            $bankInfoData = $validatedData['bank_info'] ?? [];
            if (!empty($bankInfoData)) {
             $this->commonService->updateBankInfo($bankInfoData, $vendor);
            }
            
            $notesData = $validatedData['notes'] ?? [];
            if (!empty($notesData)) {
                $this->commonService->createNote($notesData,$vendor,$user); 
            }

            $contacts = $validatedData['contacts'] ?? [];
            if (!empty($contacts)) {
                $this->commonService->updateContact($contacts, $vendor);
            }

            $addresses = $validatedData['addresses'] ?? [];
            if (!empty($addresses)) {
                $this->commonService->updateAddress($addresses, $vendor);
            }

            $compliance = $validatedData['compliance'] ?? [];
            if (!empty($compliance)) {
                $this->commonService->updateCompliance($compliance, $vendor);
            }

            if ($request->has('vendor_item')) {
                $existingVendorItems = $vendor->approvedItems()->pluck('id')->toArray();
                $newItems = [];
                foreach ($request->input('vendor_item') as $vendorItemData) {
                    if (!empty($vendorItemData['item_code']) && !empty($vendorItemData['item_name'])) {
                       
                        if (isset($vendorItemData['id']) && !empty($vendorItemData['id'])) {
                            $existingItem = $vendor->approvedItems()->where('id', $vendorItemData['id'])->first();
                            if ($existingItem) {
                                $updateData = [
                                    'item_id' => $vendorItemData['item_id'] ?? null,
                                    'item_code' => $vendorItemData['item_code'] ?? null,
                                    'cost_price' => $vendorItemData['cost_price'] ?? null, 
                                    'uom_id' => $vendorItemData['uom_id']?? null,
                                ];
                                if (isset($vendorItemData['item_id']) && !empty($vendorItemData['item_id'])) {
                                    $updateData['item_id'] = $vendorItemData['item_id'];
                                }
                                $existingItem->update($updateData);
                                $newItems[] = $existingItem->id;
                            }
                        } else {
                            $newItem = $vendor->approvedItems()->create([
                                'item_id' => $vendorItemData['item_id'] ?? null,
                                'item_code' => $vendorItemData['item_code'],
                                'cost_price' => $vendorItemData['cost_price'] ?? null, 
                                'uom_id' => $vendorItemData['uom_id']?? null,
                            ]);
                            $newItems[] = $newItem->id;
                        }
                    } 
                }
                $itemsToDelete = array_diff($existingVendorItems, $newItems);
                if ($itemsToDelete) {
                    $vendor->approvedItems()->whereIn('id', $itemsToDelete)->delete();
                }
            } else {
                $vendor->approvedItems()->delete();
            }

              // Step 7: Synchronize Vendor Books
              $bookIds = $request->book_id ?? [];
              if (!empty($bookIds)) {
                  VendorPortalBook::where('vendor_id', $vendor->id)
                      ->whereNotIn('book_id', $bookIds)
                      ->delete();
                  foreach ($bookIds as $bookId) {
                      $book = Book::find($bookId);
                      if ($book) {
                          VendorPortalBook::updateOrCreate(
                              ['vendor_id' => $vendor->id, 'book_id' => $bookId],
                              ['service_id' => $book->service_id]
                          );
                      }
                  }
              } else {
                  VendorPortalBook::where('vendor_id', $vendor->id)->delete();
              }
  
              // Step 8: Synchronize Vendor Users
              $userIds = $request->user_id ?? [];
              if (!empty($userIds)) {
                  VendorPortalUser::where('vendor_id', $vendor->id)
                      ->whereNotIn('user_id', $userIds)
                      ->delete();
                  foreach ($userIds as $userId) {
                      VendorPortalUser::updateOrCreate(
                          ['vendor_id' => $vendor->id, 'user_id' => $userId]
                      );
                  }
              } else {
                  VendorPortalUser::where('vendor_id', $vendor->id)->delete();
              }
              
              DB::commit();

              return response()->json([
                  'status' => true,
                  'message' => 'Record updated successfully',
                  'data' => $vendor,
              ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to update record',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        public function deleteAddress($id)
        {
            DB::beginTransaction();
            try {
                $address = ErpAddress::find($id);

                if ($address) {
                    $result = $address->deleteWithReferences();
                    if (!$result['status']) {
                        DB::rollBack();
                        return response()->json(['status' => false, 'message' => $result['message']], 400);
                    }
                    DB::commit();
                    return response()->json(['status' => true, 'message' => 'Record deleted successfully']);
                }
                return response()->json(['status' => false, 'message' => 'Record not found'], 404);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
            }
        }

        public function deleteContact($id)
        {
            DB::beginTransaction();
            try {
                $contact = Contact::find($id);
                if ($contact) {
                    $result = $contact->deleteWithReferences();
                    if (!$result['status']) {
                        DB::rollBack();
                        return response()->json(['status' => false, 'message' => $result['message']], 400);
                    }
                    DB::commit();
                    return response()->json(['status' => true, 'message' => 'Record deleted successfully']);
                }

                return response()->json(['status' => false, 'message' => 'Record not found'], 404);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
            }
        }

        public function deleteVendorItem($id)
        {
            DB::beginTransaction();
            try {
                $vendorItem = VendorItem::find($id);

                if ($vendorItem) {
                    $result = $vendorItem->deleteWithReferences();

                    if (!$result['status']) {
                        DB::rollBack();
                        return response()->json(['status' => false, 'message' => $result['message']], 400);
                    }

                    DB::commit();
                    return response()->json(['status' => true, 'message' => 'Record deleted successfully']);
                }

                return response()->json(['status' => false, 'message' => 'Vendor item not found'], 404);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
            }
        }


        public function deleteBankInfo($id)
        {
            DB::beginTransaction();
            try {
                $bankInfo = BankInfo::find($id);
                if ($bankInfo) {
                    $result = $bankInfo->deleteWithReferences();

                    if (!$result['status']) {
                        DB::rollBack();
                        return response()->json(['status' => false, 'message' => $result['message']], 400);
                    }

                    DB::commit();
                    return response()->json(['status' => true, 'message' => 'Record deleted successfully']);
                }

                return response()->json(['status' => false, 'message' => 'Record not found'], 404);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
            }
        }

        public function destroy($id)
        {
            try {
                $vendor = Vendor::findOrFail($id);
        
                $referenceTables = [
                    'erp_addresses' => ['addressable_id'],
                    'erp_contacts' => ['contactable_id'],
                    'erp_bank_infos' => ['morphable_id'],
                    'erp_notes' => ['noteable_id'],
                    'erp_vendor_items' => ['vendor_id'],
                    'erp_compliances' => ['morphable_id'],
                ];
        
                $result = $vendor->deleteWithReferences($referenceTables);
        
                if (!$result['status']) {
                    return response()->json([
                        'status' => false,
                        'message' => $result['message'],
                        'referenced_tables' => $result['referenced_tables'] ?? []
                    ], 400);
                }
        
                return response()->json([
                    'status' => true,
                    'message' => $result['message']
                ], 200);
        
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while deleting the vendor: ' . $e->getMessage()
                ], 500);
            }
        }
        
        public function getStates($country_id) {
            $states = State::where('country_id', $country_id)->get();
            return response()->json($states);
        }

        public function getCities($state_id) {
            $cities = City::where('state_id', $state_id)->get();
            return response()->json($cities);
        }
        
        public function getVendor(Request $request)
        {
            $searchTerm = $request->input('q', '');
            $vendors = Vendor::withDefaultGroupCompanyOrg() 
            ->where(function ($query) use ($searchTerm) {
                    $query->where('company_name', 'like', "%{$searchTerm}%")
                        ->orWhere('vendor_code', 'like', "%{$searchTerm}%");  
                })
                ->where('status', ConstantHelper::ACTIVE)
                ->limit(10)
                ->get(['id', 'company_name','vendor_code']);
            if ($vendors->isEmpty()) {
                $vendors = Vendor::withDefaultGroupCompanyOrg()
                    ->where('status', ConstantHelper::ACTIVE)
                    ->limit(10)
                    ->get(['id', 'company_name','vendor_code']);
            }
        
            return response()->json($vendors);
        } 
        
        public function users(Request $req)
        {
            $user = Helper::getAuthenticatedUser();
            $organization = Organization::where('id', $user->organization_id)->first(); 
            $organizationId = $organization?->id ?? null;
            $companyId = $organization?->company_id ?? null;
            $type = 'IAM-SUPPLIER';
            $email = $req->input('email');
            $name = $req->input('name');

            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $name = htmlspecialchars($name);

            if (!$email || !$name) {
                return response()->json(['error' => 'Email and name are required'], 400);
            }

            $user = User::firstOrNew([
                'email' => $email,
                'user_type' => $type
            ]);
        
            if (!isset($user->id)) {
                $user->password = Hash::make('Admin@123');
            }
            $user->name = $name;
            $user->user_type = $type;
            $user->organization_id = $organizationId;
            $user->save();

            return response()->json([
                'message' => 'User saved successfully',
                'user' => $user
            ], 200);
        }

        public function getUOM(Request $request)
        {
            // Step 1: Get the item ID from the request
            $itemId = $request->get('item_id');
           
            // Step 2: Retrieve the item and its UOM ID
            $item = Item::find($itemId);
        
            if (!$item) {
                return response()->json(['error' => 'Item not found'], 404);
            }
        
            // Get the UOM ID of the item
            $itemUOM = $item->uom_id;
        
            // Step 3: Fetch the alternate UOMs using the relationship
            $alternateUOMs = $item->alternateUOMs; // Assuming a relationship exists between Item and AlternateUOM
            
            // Step 4: Merge the primary UOM with the alternate UOMs
            // First, make sure the UOM IDs from the item and alternate UOMs are unique
            $uoms = collect([$itemUOM])->merge($alternateUOMs->pluck('uom_id'))->unique();
           
        
            // Fetch the UOM details based on the IDs
            $uomDetails = Unit::whereIn('id', $uoms)->get(); // Assuming you have a UOM model
        
            // Prepare the response
            $response = [
                'uom_id' => $itemUOM,
                'uom_name' => $item->uom->name, // Assuming you have a `name` attribute in your UOM model
                'alternate_uoms' => $uomDetails->map(function($uom) {
                    return [
                        'id' => $uom->id,
                        'name' => $uom->name,
                    ];
                }),
            ];
        
            return response()->json($response);
        }
        
        
}
