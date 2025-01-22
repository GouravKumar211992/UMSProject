<?php

namespace App\Http\Controllers;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Compliance;
use App\Models\Currency;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Group;
use App\Models\BankInfo;
use App\Models\PaymentTerm;
use App\Models\OrganizationType;
use App\Models\CustomerAddress;
use App\Models\ErpAddress;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest; 
use Illuminate\Support\Facades\Validator;
use App\Services\CommonService;
use App\Helpers\ConstantHelper;
use App\Models\UserOrganizationMapping;
use App\Helpers\FileUploadHelper; 
use App\Helpers\Helper; 
use App\Models\Organization;
use App\Models\CustomerItem;
use App\Models\Contact;
use Auth;

class CustomerController extends Controller
{
    protected $commonService;
    protected $fileUploadHelper;

    public function __construct(CommonService $commonService,FileUploadHelper $fileUploadHelper)
    {
        $this->commonService = $commonService;
        $this->fileUploadHelper = $fileUploadHelper;
    }

    // public function index()
    // {

    //     $user = Helper::getAuthenticatedUser();
    //     $organizationId = $user->organization_id;
    //     $customers = Customer::with(['erpOrganizationType', 'category', 'subcategory'])
    //     ->where('organization_id', $organizationId)
    //     ->get();
    //     return view('procurement.customer.index', compact('customers'));

    //     // $user = Auth::guard('web')->user();
    //     // $mappings = UserOrganizationMapping::where('user_id', $user->id)
    //     //     ->with('organization')
    //     //     ->get();
    //     // $organization_id = $user->organization_id;
    //     // return view('customer.index', compact("mappings", 'organization_id'));
    // }

        public function index(Request $request)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first(); 
        $organizationId = $organization?->id ?? null;
        $companyId = $organization?->company_id ?? null;

        if ($request->ajax()) {
            $query = Customer::with(['salesPerson', 'erpOrganizationType', 'category', 'subcategory', 'sales_person'])
                ->withDefaultGroupCompanyOrg();

            if ($request->filled('customer_type')) {
                $query->where('customer_type', $request->customer_type);
            }

            if ($categoryId = request('category_id')) {
                $query->where('category_id', $categoryId);
            }

            if ($subcategoryId = request('subcategory_id')) {
                $query->where('subcategory_id', $subcategoryId);
            }

            if ($request->filled('sales_person')) {
                $query->whereHas('salesPerson', function($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->sales_person}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $customers = $query->orderBy('id', 'ASC')->get();

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('customer_code', function ($row) {
                    return $row->customer_code ?? 'N/A';
                })
                ->addColumn('company_name', function ($row) {
                    return $row->company_name ?? 'N/A';
                })
                ->addColumn('customer_type', function ($row) {
                    return $row->customer_type ?? 'N/A';
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
                    $editUrl = route('customer.edit', $row->id);
                    return '<div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                    <i data-feather="more-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="' . $editUrl . '">
                                        <i data-feather="edit-3" class="me-50"></i>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $salesPersons = Employee::where('organization_id', $organizationId)->pluck('name', 'id');
        $categories = Category::where('organization_id', $organizationId)
            ->withDefaultGroupCompanyOrg()
            ->where('status', ConstantHelper::ACTIVE)
            ->whereNull('parent_id') 
            ->get();

        return view('procurement.customer.index', compact('salesPersons', 'categories'));
    }


    public function updateOrganization(Request $request)
    {
        $user = Auth::guard('web')->user();
        $organizationId = $request->input('organization_id');
        $request->validate([
            'organization_id' => 'required|exists:organizations,id'
        ]);

        $user->organization_id = $organizationId;
        $user->save();
        return redirect()->back()->with('success', 'Organization updated successfully!');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $organizationTypes = OrganizationType::where('status', ConstantHelper::ACTIVE)->get();
        $categories = Category::where('status', ConstantHelper::ACTIVE)->whereNull('parent_id')->withDefaultGroupCompanyOrg()->get();
        $currencies = Currency::where('status', ConstantHelper::ACTIVE)->get();
        $paymentTerms = PaymentTerm::where('status', ConstantHelper::ACTIVE)->withDefaultGroupCompanyOrg()->get();
        $titles = ConstantHelper::TITLES;
        $status = ConstantHelper::STATUS;
        $options = ConstantHelper::STOP_OPTIONS;
        $customerTypes = ConstantHelper::CUSTOMER_TYPES;
        $addressTypes = ConstantHelper::ADDRESS_TYPES;
        $countries = Country::where('status', 'active')->get();
        return view('procurement.customer.create', [
            'organizationTypes' => $organizationTypes,
            'categories' => $categories,
            'titles' => $titles,
            'currencies' => $currencies,
            'paymentTerms' => $paymentTerms,
            'status' => $status,
            'options' => $options,
            'customerTypes' => $customerTypes,
            'countries' => $countries,
            'addressTypes' => $addressTypes
        ]);
    }

    public function store(CustomerRequest $request)
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
        $customer = Customer::create($validatedData);
        $fileConfigs = [
            'pan_attachment' => ['folder' => 'pan_attachments', 'clear_existing' => true],
            'tin_attachment' => ['folder' => 'tin_attachments', 'clear_existing' => true],
            'aadhar_attachment' => ['folder' => 'aadhar_attachments', 'clear_existing' => true],
            'other_documents' => ['folder' => 'other_documents', 'clear_existing' => true],
        ];
        
        $this->fileUploadHelper->handleFileUploads($request, $customer, $fileConfigs);

        $bankInfoData = $validatedData['bank_info'] ?? [];
        if (!empty($bankInfoData)) {
            $this->commonService->createBankInfo($bankInfoData, $customer);
        }
        // Handle notes
        $notesData = $validatedData['notes'] ?? [];
        if (!empty($notesData)) {
            $this->commonService->createNote($notesData, $customer, $user);
        }

        $contacts = $validatedData['contacts'] ?? [];
        if (!empty($contacts)) {
            $this->commonService->createContact($contacts, $customer);
        }

        $addresses = $validatedData['addresses'] ?? [];
        if (!empty($addresses)) {
            $this->commonService->createAddress($addresses, $customer);
        }

        $compliance = $validatedData['compliance'] ?? [];
         if (!empty($compliance)) {
             $this->commonService->createCompliance($compliance, $customer);
         }

        // Handling Customer Items

        if ($request->has('customer_item')) {
            foreach ($request->input('customer_item') as $customerItemData) {
                if (!empty($customerItemData['item_code']) && !empty($customerItemData['item_name'])) {
                    $customer->approvedItems()->create([
                        'item_id' => $customerItemData['item_id'],
                        'item_code' => $customerItemData['item_code'] ?? null, 
                        'item_name' => $customerItemData['item_name'] ?? null, 
                        'item_details' => $customerItemData['item_details'] ?? null, 
                        'sell_price' => $customerItemData['sell_price'] ?? null, 
                        'uom_id' => $customerItemData['uom_id']?? null,
                    ]);
                } 
            }
        }
        
        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Record created successfully',
            'data' => $customer,
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
        // Implement the logic if needed
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $organizationTypes = OrganizationType::where('status', ConstantHelper::ACTIVE)->get();
        $categories = Category::where('status', ConstantHelper::ACTIVE)->whereNull('parent_id')->withDefaultGroupCompanyOrg()->get();
        $subcategories = Category::where('status', ConstantHelper::ACTIVE)->whereNotNull('parent_id')->withDefaultGroupCompanyOrg()->get();
        $currencies = Currency::where('status', ConstantHelper::ACTIVE)->get();
        $paymentTerms = PaymentTerm::where('status', ConstantHelper::ACTIVE)->withDefaultGroupCompanyOrg()->get();
        $titles = ConstantHelper::TITLES;
        $notificationData = $customer? $customer->notification : [];
        $notifications = is_array($notificationData) ? $notificationData : json_decode($notificationData, true);
        $notifications = $notifications ?? [];
        $status = ConstantHelper::STATUS;
        $options = ConstantHelper::STOP_OPTIONS;
        $customerTypes = ConstantHelper::CUSTOMER_TYPES;
        $addressTypes = ConstantHelper::ADDRESS_TYPES;
        $countries = Country::where('status', 'active')->get();
        $ledgerGroups = Group::where('status',1)->get();
        return view('procurement.customer.edit', [
            'customer' => $customer,
            'organizationTypes' => $organizationTypes,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'titles' => $titles,
            'currencies' => $currencies,
            'paymentTerms' => $paymentTerms,
            'notifications' => $notifications,
            'status' => $status,
            'options' => $options,
            'customerTypes' => $customerTypes,
            'countries' => $countries,
            'addressTypes' => $addressTypes,
            'ledgerGroups' => $ledgerGroups
        ]);
    }

    public function update(CustomerRequest $request, $id)
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
        if ($request->input('document_status') === 'submitted') {
            $validatedData['status'] =  $validatedData['status'] ?? ConstantHelper::ACTIVE;
        } else {
            $validatedData['status'] = ConstantHelper::DRAFT;
        }
        $customer = Customer::findOrFail($id);
        $customer->update($validatedData);
       
         $fileConfigs = [
            'pan_attachment' => ['folder' => 'pan_attachments', 'clear_existing' => true],
            'tin_attachment' => ['folder' => 'tin_attachments', 'clear_existing' => true],
            'aadhar_attachment' => ['folder' => 'aadhar_attachments', 'clear_existing' => true],
            'other_documents' => ['folder' => 'other_documents', 'clear_existing' => true],
        ];

        $this->fileUploadHelper->handleFileUploads($request, $customer, $fileConfigs);

        
        $bankInfoData = $validatedData['bank_info'] ?? [];
        if (!empty($bankInfoData)) {
            $this->commonService->updateBankInfo($bankInfoData, $customer);
        }
        
        $notesData = $validatedData['notes'] ?? [];
        if (!empty($notesData['remark'])) {
            $this->commonService->createNote($notesData, $customer,$user);
        }

        $contacts = $validatedData['contacts'] ?? [];
        if (!empty($contacts)) {
            $this->commonService->updateContact($contacts, $customer);
        }

        $addresses = $validatedData['addresses'] ?? [];
        if (!empty($addresses)) {
            $this->commonService->updateAddress($addresses, $customer);
        }

        $compliance = $validatedData['compliance'] ?? [];
        if (!empty($compliance)) {
            $this->commonService->updateCompliance($compliance, $customer);
        }
        // for items
        if ($request->has('customer_item')) {
            $existingCustomerItems = $customer->approvedItems()->pluck('id')->toArray();
            $newItems = [];
            foreach ($request->input('customer_item') as $customerItemData) {
                if (!empty($customerItemData['item_code']) && !empty($customerItemData['item_name'])) {
                    if (isset($customerItemData['id']) && !empty($customerItemData['id'])) {
                        $existingItem = $customer->approvedItems()->where('id', $customerItemData['id'])->first();
                        if ($existingItem) {
                            $updateData = [
                                'item_code' => $customerItemData['item_code'],
                                'item_name' => $customerItemData['item_name'],
                                'item_details' => $customerItemData['item_details'] ?? null,
                                'sell_price' => $customerItemData['sell_price'] ?? null, 
                                'uom_id' => $customerItemData['uom_id']?? null,
                            ];
                            if (isset($customerItemData['item_id']) && !empty($customerItemData['item_id'])) {
                                $updateData['item_id'] = $customerItemData['item_id'];
                            }
                            $existingItem->update($updateData);
                            $newItems[] = $existingItem->id;
                        }
                    } else {
                        $newItem = $customer->approvedItems()->create([
                            'item_id' => $customerItemData['item_id'] ?? null,
                            'item_code' => $customerItemData['item_code'],
                            'item_name' => $customerItemData['item_name'],
                            'item_details' => $customerItemData['item_details'] ?? null,
                            'sell_price' => $customerItemData['sell_price'] ?? null, 
                            'uom_id' => $customerItemData['uom_id']?? null,
                        ]);
                        $newItems[] = $newItem->id;
                    }
                }
            }
        
            $itemsToDelete = array_diff($existingCustomerItems, $newItems);
            if ($itemsToDelete) {
                $customer->approvedItems()->whereIn('id', $itemsToDelete)->delete();
            }
        } else {
            $customer->approvedItems()->delete();
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Record updated successfully',
            'data' => $customer,
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

    public function deleteCustomerItem($id)
    {
        DB::beginTransaction();
        try {
            $customerItem = CustomerItem::find($id);

            if ($customerItem) {
                $result = $customerItem->deleteWithReferences();

                if (!$result['status']) {
                    DB::rollBack();
                    return response()->json(['status' => false, 'message' => $result['message']], 400);
                }

                DB::commit();
                return response()->json(['status' => true, 'message' => 'Record deleted successfully']);
            }

            return response()->json(['status' => false, 'message' => 'Customer item not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
    
            $referenceTables = [
                'erp_addresses' => ['addressable_id'],
                'erp_contacts' => ['contactable_id'],
                'erp_bank_infos' => ['morphable_id'],
                'erp_notes' => ['noteable_id'],
                'erp_customer_items' => ['customer_id'],
                'erp_compliances' => ['morphable_id'],
                
            ];
    
            $result = $customer->deleteWithReferences($referenceTables);
    
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
                'message' => 'An error occurred while deleting the customer: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getStates($country_id) 
    {
        $states = State::where('country_id', $country_id)->get();
        return response()->json($states);
    }

    public function getCities($state_id) 
    {
        $cities = City::where('state_id', $state_id)->get();
        return response()->json($cities);
    }

    public function getComplianceByCountry($customerId, $countryId)
    {
        $compliances = Compliance::where('customer_id', $customerId)
            ->where('country_id', $countryId)
            ->get();

        return response()->json([
            'compliances' => $compliances
        ]);
    }
    
    public function getComplianceById($id)
    {
        $compliance = Compliance::with('media')->find($id);
        
        if (!$compliance) {
            return response()->json(['error' => 'Compliance not found'], 404);
        }
    
        return response()->json($compliance);
    }

    public function getCustomer(Request $request)
    {
        $searchTerm = $request->input('q', '');
        $customers = Customer::withDefaultGroupCompanyOrg() 
            ->where(function ($query) use ($searchTerm) {
                $query->where('company_name', 'like', "%{$searchTerm}%")
                    ->orWhere('customer_code', 'like', "%{$searchTerm}%");
            })
            ->where('status', ConstantHelper::ACTIVE)
            ->get(['id', 'company_name','customer_code']);

        if ($customers->isEmpty()) {
            $customers = Customer::withDefaultGroupCompanyOrg()
                ->where('status', ConstantHelper::ACTIVE)
                ->limit(10)
                ->get(['id', 'company_name','customer_code']);
        }
        return response()->json($customers);
    } 
}
