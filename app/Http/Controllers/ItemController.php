<?php

namespace App\Http\Controllers;
use Yajra\DataTables\DataTables;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\SubType;
use App\Models\Hsn;
use App\Models\Unit;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Helpers\ConstantHelper;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\AlternateUOM;
use App\Models\ProductSpecification;
use App\Models\CustomerItem;
use App\Models\VendorItem;
use App\Models\ItemAttribute;
use App\Models\AlternateItem;
use App\Helpers\Helper; 
use App\Imports\ItemImport;
use App\Services\CommonService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Helpers\ItemHelper;
use Auth;


class ItemController extends Controller
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }
  
    public function index()
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first(); 
        $organizationId = $organization?->id ?? null;
        $companyId = $organization?->company_id ?? null;
    
        if (request()->ajax()) {
            $query = Item::WithDefaultGroupCompanyOrg()
                ->with(['uom', 'hsn', 'category', 'subcategory', 'subTypes']);
            if ($status = request('status')) {
                $query->where('status', $status);
            }
    
            if ($hsnId = request('hsn_id')) {
                $query->where('hsn_id', $hsnId);
            }
    
            if ($categoryId = request('category_id')) {
                $query->where('category_id', $categoryId);
            }
    
            if ($subcategoryId = request('subcategory_id')) {
                $query->where('subcategory_id', $subcategoryId);
            }
    
            if ($type = request('type')) {
                $query->where('type', $type);
            }

            $items = $query->get();
            return DataTables::of($items)
                ->addIndexColumn()
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
                ->addColumn('uom', function ($item) {
                    return $item->uom ? $item->uom->name : 'N/A';
                })
                ->addColumn('action', function ($item) {
                    return '
                        <div class="dropdown">
                            <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                <i data-feather="more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="' . route('item.edit', $item->id) . '">
                                    <i data-feather="edit-3" class="me-50"></i>
                                    <span>Edit</span>
                                </a>
                            </div>
                        </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);

        }
    
        $hsns = Hsn::where('organization_id', $organizationId)
            ->where('status', ConstantHelper::ACTIVE)
            ->get();
    
        $categories = Category::where('organization_id', $organizationId)
            ->where('status', ConstantHelper::ACTIVE)
            ->whereNull('parent_id')
            ->get();
    
        $types = ConstantHelper::ITEM_TYPES;
    
        return view('procurement.item.index', compact('hsns', 'categories', 'types'));
    }
    

    public function create()
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first();
        $organizationId = $organization->id;
        $subTypes = SubType::where('status', ConstantHelper::ACTIVE)->get();
        $hsns = Hsn::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $units = Unit::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $organizations = Organization::where('status', ConstantHelper::ACTIVE)->get();
        $categories = Category::where('status', ConstantHelper::ACTIVE)->whereNull('parent_id')->WithDefaultGroupCompanyOrg()->get();
        $vendors = Vendor::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $customers = Customer::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $attributeGroups = AttributeGroup::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $allItems = Item::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $types = ConstantHelper::ITEM_TYPES;
        $status = ConstantHelper::STATUS;
        $service = ConstantHelper::IS_SERVICE;
        $options = ConstantHelper::STOP_OPTIONS;
        $specificationGroups = ProductSpecification::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();

        return view('procurement.item.create', [
            'hsns' => $hsns,
            'units' => $units,
            'categories' => $categories,
            'vendors' => $vendors,
            'customers' => $customers,
            'types' => $types,
            'status' => $status,
            'service'=>$service,
            'options'=>$options,
            'organizations'=>$organizations,
            'subTypes'=>$subTypes,
            'attributeGroups'=>$attributeGroups,
            'allItems'=>$allItems,
            'specificationGroups'=>$specificationGroups,

        ]);
    }

    public function generateItemCode(Request $request)
    {
        $itemName = $request->input('item_name');
        $itemId = $request->input('item_id');
        $subType = $request->input('sub_type');
        $categoryInitials = $request->input('cat_initials');
        $subCategoryInitials = $request->input('sub_cat_initials');
        $itemInitials = $request->input('item_initials');
        $prefix = $request->input('prefix', ''); 
        $baseCode =  $prefix .$subType . $subCategoryInitials . $itemInitials;

        $authUser = Helper::getAuthenticatedUser();
        $organizationId = $authUser->organization_id;
        if ($itemId) {
            $existingItem = Item::withDefaultGroupCompanyOrg()->find($itemId);
            if ($existingItem) {
                $existingItemCode = $existingItem->item_code;
                $currentBaseCode = substr($existingItemCode, 0, strlen($baseCode));
                if ($currentBaseCode === $baseCode) {
                    return response()->json(['item_code' => $existingItemCode]);
                }
            }
        }
        $lastSimilarItem = Item::where('item_code', 'like', "{$baseCode}%")
            ->where('organization_id', $organizationId) 
            ->orderBy('item_code', 'desc')->first();
    
        $nextSuffix = '001';
        if ($lastSimilarItem) {
            $lastSuffix = intval(substr($lastSimilarItem->item_code, -3));
            $nextSuffix = str_pad($lastSuffix + 1, 3, '0', STR_PAD_LEFT);
        }
        $finalItemCode = $baseCode . $nextSuffix;
    
        return response()->json(['item_code' => $finalItemCode]);
    }
    

    public function store(ItemRequest $request)
    {
      DB::beginTransaction();
     try {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $validatedData = $request->validated();
        $validatedData['group_id'] = $organization->group_id;
        $validatedData['company_id'] = $organization->company_id;
        $validatedData['organization_id'] = $organization->id;
        if ($request->document_status === 'submitted') {
            $validatedData['status'] = $validatedData['status'] ?? ConstantHelper::ACTIVE; 
        } else {
            $validatedData['status'] = ConstantHelper::DRAFT;
        }
        $item = Item::create($validatedData);
        if ($request->has('sub_types')) {
            $item->subTypes()->attach($request->input('sub_types'));
        }
        if ($request->has('alternate_uoms')) {
            foreach ($request->input('alternate_uoms') as $uomData) {
                if (isset($uomData['uom_id']) && !empty($uomData['uom_id']) &&
                    isset($uomData['conversion_to_inventory']) && !empty($uomData['conversion_to_inventory'])) {
                    $item->alternateUOMs()->create([
                        'uom_id' => $uomData['uom_id'],
                        'conversion_to_inventory' => $uomData['conversion_to_inventory'],
                        'cost_price' => $uomData['cost_price'],
                        'sell_price' => $uomData['sell_price'],
                        'is_selling' => isset($uomData['is_selling']) && $uomData['is_selling'] == '1',
                        'is_purchasing' => isset($uomData['is_purchasing']) && $uomData['is_purchasing'] == '1',
                    ]);
                }
            }
        }
        
        if ($request->has('approved_customer')) {
           
            foreach ($request->input('approved_customer') as $approvedCustomerData) {
        
                if (isset($approvedCustomerData['customer_id']) && !empty($approvedCustomerData['customer_id'])) {
                    $item->approvedCustomers()->create([
                        'customer_id' => $approvedCustomerData['customer_id'],
                        'customer_code' => $approvedCustomerData['customer_code'] ?? null,
                        'item_code' => $approvedCustomerData['item_code'] ?? null, 
                        'item_name' => $approvedCustomerData['item_name'] ?? null, 
                        'item_details' => $approvedCustomerData['item_details'] ?? null,
                        'sell_price' => $approvedCustomerData['sell_price']?? null,
                        'uom_id' => $approvedCustomerData['uom_id']?? null,
                    ]);
                }
            }
        }
        
        if ($request->has('approved_vendor')) {
            $item->approvedVendors()->delete();
            foreach ($request->input('approved_vendor') as $approvedVendorData) {
                if (isset($approvedVendorData['vendor_id']) && !empty($approvedVendorData['vendor_id'])) {
                    $item->approvedVendors()->create([
                        'vendor_id' => $approvedVendorData['vendor_id'],
                        'vendor_code' => $approvedVendorData['vendor_code'] ?? null, 
                        'cost_price' => $approvedVendorData['cost_price'] ?? null, 
                        'uom_id' => $approvedVendorData['uom_id']?? null,
                    ]);
                }
            }
        }

        if ($request->has('attributes')) {
            foreach ($request->input('attributes') as $attributeGroupData) {
                $attributeGroupId = $attributeGroupData['attribute_group_id'] ?? null;
                $attributeIds = $attributeGroupData['attribute_id'] ?? [];
                $requiredBom = isset($attributeGroupData['required_bom']) ? (int) $attributeGroupData['required_bom'] : 0;
                $allChecked = isset($attributeGroupData['all_checked']) ? (int) $attributeGroupData['all_checked'] : 0;
                if ($attributeGroupId && ($attributeIds || $allChecked)) {
                    $item->itemAttributes()->create([
                        'attribute_group_id' => $attributeGroupId,
                        'attribute_id' => $attributeIds,
                        'required_bom' => $requiredBom, 
                        'all_checked' => $allChecked 
                    ]);
                }
            }
        }

        if ($request->has('alternateItems')) {
            foreach ($request->input('alternateItems') as $alternateItemData) {
                if (isset($alternateItemData['item_code']) && !empty($alternateItemData['item_code']) &&
                    isset($alternateItemData['item_name']) && !empty($alternateItemData['item_name'])) {
                    $item->alternateItems()->create([
                        'item_code' => $alternateItemData['item_code'],
                        'item_name' => $alternateItemData['item_name'],
                    ]);
                }
            }
        }

        if ($request->has('item_specifications')) {
            foreach ($request->input('item_specifications') as $specificationData) {
                if (isset($specificationData['specification_name']) && !empty($specificationData['specification_name'])) {
                    $item->specifications()->create([
                        'group_id' => $specificationData['group_id'] ?? null,
                        'specification_id' => $specificationData['specification_id'] ?? null,
                        'specification_name' => $specificationData['specification_name'],
                        'value' => $specificationData['value'] ?? null,
                    ]);
                }
            }
        }

        $notesData = $validatedData['notes'] ?? [];
        if (!empty($notesData)) {
            $this->commonService->createNote($notesData, $item, $user);
        }
        
        
        DB::commit();

        return response()->json([
            'message' => 'Record created successfully',
            'data' => $item,
        ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create record',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Item $item)
    {
        // You can implement this if needed
    }
    public function showImportForm()
    {
        return view('procurement.item.import');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048', 
        ]);

        try {
            Excel::import(new ItemImport, $request->file('file'));
            return back()->with('success', 'Items imported successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to import items: ' . $e->getMessage());
        }
    }

  
    public function edit($id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first();
        $organizationId = $organization->id;
        $item = Item::findOrFail($id);
        $hsns = Hsn::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $units = Unit::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $categories = Category::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->whereNull('parent_id')  ->get();
        $vendors = Vendor::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $customers = Customer::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $types = ConstantHelper::ITEM_TYPES;
        $status = ConstantHelper::STATUS;
        $options = ConstantHelper::STOP_OPTIONS;
        $service = ConstantHelper::IS_SERVICE;
        $organizations = Organization::where('status', ConstantHelper::ACTIVE)->get();
        $subTypes = SubType::where('status', ConstantHelper::ACTIVE)->get();
        $attributeGroups = AttributeGroup::with('attributes')->WithDefaultGroupCompanyOrg()->get();
        $allItems = Item::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();
        $specificationGroups = ProductSpecification::where('status', ConstantHelper::ACTIVE)->WithDefaultGroupCompanyOrg()->get();

        return view('procurement.item.edit', [
            'item' => $item,
            'hsns' => $hsns,
            'units' => $units,
            'categories' => $categories,
            'vendors' => $vendors,
            'customers' => $customers,
            'types' => $types,
            'status' => $status,
            'options'=>$options,
            'organizations'=>$organizations,
            'subTypes'=>$subTypes,
            'attributeGroups'=>$attributeGroups,
            'allItems'=>$allItems,
            'service'=>$service,
            'specificationGroups'=>$specificationGroups,
        ]);
    }

    public function update(ItemRequest $request, $id = null)
    {
        DB::beginTransaction();
    try {
        $user = Helper::getAuthenticatedUser();
        $organization = $user->organization;
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        $validatedData = $request->validated();
        $validatedData['group_id'] = $organization->group_id;
        $validatedData['company_id'] = $organization->company_id;
        $validatedData['organization_id'] = $organization->id;
    
        if ($request->input('document_status') === 'submitted') {
            $validatedData['status'] = $validatedData['status'] ?? ConstantHelper::ACTIVE; 
        } else {
            $validatedData['status'] = ConstantHelper::DRAFT;
        }
    
        $item->fill($validatedData);
        $item->save();
        if ($request->type === 'Goods') {
            if ($request->has('sub_types')) {
                $item->subTypes()->sync($request->input('sub_types'));
            }
        } else {
            $item->subTypes()->detach();
        }
    
        if ($request->has('alternate_uoms')) {
            $existingUOMs = $item->alternateUOMs()->pluck('id')->toArray();
            $newUOMs = [];
            foreach ($request->input('alternate_uoms') as $uomData) {
                if (isset($uomData['uom_id']) && !empty($uomData['uom_id']) && 
                isset($uomData['conversion_to_inventory']) && !empty($uomData['conversion_to_inventory'])) {
                if (isset($uomData['id']) && in_array($uomData['id'], $existingUOMs)) {
                    $item->alternateUOMs()->where('id', $uomData['id'])->update([
                        'uom_id' => $uomData['uom_id'],
                        'conversion_to_inventory' => $uomData['conversion_to_inventory'] ?? null,
                        'cost_price' => $uomData['cost_price']?? null,
                        'sell_price' => $uomData['sell_price']?? null,
                        'is_selling' => isset($uomData['is_selling']) && $uomData['is_selling'] == '1',
                        'is_purchasing' => isset($uomData['is_purchasing']) && $uomData['is_purchasing'] == '1',
                    ]);
                    $newUOMs[] = $uomData['id'];
                } else {
                    $newUOM = $item->alternateUOMs()->create([
                        'uom_id' => $uomData['uom_id'],
                        'conversion_to_inventory' => $uomData['conversion_to_inventory'] ?? null,
                        'cost_price' => $uomData['cost_price']?? null,
                        'sell_price' => $uomData['sell_price']?? null,
                        'is_selling' => isset($uomData['is_selling']) && $uomData['is_selling'] == '1',
                        'is_purchasing' => isset($uomData['is_purchasing']) && $uomData['is_purchasing'] == '1',
                    ]);
                  
                    $newUOMs[] = $newUOM->id;
                }
              }
            }
            $item->alternateUOMs()->whereNotIn('id', $newUOMs)->delete();
        }else {
            $item->alternateUOMs()->delete();
        }

        if ($request->has('approved_customer')) {
            $existingCustomers = $item->approvedCustomers()->pluck('id')->toArray();
            $newCustomers = [];
            foreach ($request->input('approved_customer') as $customerData) {
                if (isset($customerData['customer_id']) && !empty($customerData['customer_id'])) {
                if (isset($customerData['id']) && in_array($customerData['id'], $existingCustomers)) {
                    $item->approvedCustomers()->where('id', $customerData['id'])->update([
                        'customer_id' => $customerData['customer_id'],
                        'customer_code' => $customerData['customer_code'] ?? null,
                        'item_code' => $customerData['item_code'] ?? null,
                        'item_name' => $customerData['item_name'] ?? null,
                        'item_details' => $customerData['item_details'] ?? null,
                        'sell_price' => $customerData['sell_price']?? null,
                        'uom_id' => $customerData['uom_id']?? null,
                    ]);
                    $newCustomers[] = $customerData['id'];
                } else {
                    $newCustomer = $item->approvedCustomers()->create([
                        'customer_id' => $customerData['customer_id'],
                        'customer_code' => $customerData['customer_code'] ?? null,
                        'item_code' => $customerData['item_code'] ?? null,
                        'item_name' => $customerData['item_name'] ?? null,
                        'item_details' => $customerData['item_details'] ?? null,
                        'sell_price' => $customerData['sell_price']?? null,
                        'uom_id' => $customerData['uom_id']?? null,
                    ]);
                    $newCustomers[] = $newCustomer->id;
                }
             }
            }
    
            $item->approvedCustomers()->whereNotIn('id', $newCustomers)->delete();
        }else {
            $item->approvedCustomers()->delete();
        }
    
        if ($request->has('approved_vendor')) {
            $existingVendors = $item->approvedVendors()->pluck('id')->toArray();
            $newVendors = [];
    
            foreach ($request->input('approved_vendor') as $vendorData) {
                if (isset($vendorData['vendor_id']) && !empty($vendorData['vendor_id'])) {
                if (isset($vendorData['id']) && in_array($vendorData['id'], $existingVendors)) {
                    $item->approvedVendors()->where('id', $vendorData['id'])->update([
                        'vendor_id' => $vendorData['vendor_id'],
                        'vendor_code' => $vendorData['vendor_code'] ?? null,
                        'cost_price' => $vendorData['cost_price']?? null,
                        'uom_id' => $vendorData['uom_id']?? null,
                        
                    ]);
                    $newVendors[] = $vendorData['id'];
                } else {
                    $newVendor = $item->approvedVendors()->create([
                        'vendor_id' => $vendorData['vendor_id'],
                        'vendor_code' => $vendorData['vendor_code'] ?? null,
                        'cost_price' => $vendorData['cost_price']?? null,
                        'uom_id' => $vendorData['uom_id']?? null,
                    ]);
                    $newVendors[] = $newVendor->id;
                }
              }
            }
            $item->approvedVendors()->whereNotIn('id', $newVendors)->delete();
        }else {
            $item->approvedVendors()->delete();
        }
    
        if ($request->has('attributes')) {
            $existingAttributes = $item->itemAttributes()->pluck('id')->toArray();
            $newAttributes = [];
            foreach ($request->input('attributes') as $attributeData) {
                $attributeId = $attributeData['attribute_id'] ?? null;
                $attributeGroupId = $attributeData['attribute_group_id'] ?? null;
                $requiredBom = isset($attributeData['required_bom']) ? (int) $attributeData['required_bom'] : 0;
                $allChecked = isset($attributeData['all_checked']) ? (int) $attributeData['all_checked'] : 0;
                if ($attributeGroupId && ($attributeId || $allChecked)) {
                if (isset($attributeData['id'])) {
                    if ($attributeGroupId || $attributeId) {
                        $item->itemAttributes()->where('id', $attributeData['id'])->update([
                            'attribute_id' => $attributeId,
                            'attribute_group_id' => $attributeGroupId,
                            'required_bom' => $requiredBom,
                            'all_checked' => $allChecked,
                        ]);
                        $newAttributes[] = $attributeData['id'];
                    } else {
                        return response()->json(['error' => 'Missing attribute_id or attribute_group_id for existing attribute.'], 400);
                    }
                } else {
                    if ($attributeGroupId || $attributeId) {
                        $newAttribute = $item->itemAttributes()->create([
                            'attribute_id' => $attributeId,
                            'attribute_group_id' => $attributeGroupId,
                            'required_bom' => $requiredBom,
                            'all_checked' => $allChecked,
                        ]);
                        $newAttributes[] = $newAttribute->id;
                    } else {
                        return response()->json(['error' => 'Missing attribute_id or attribute_group_id for new attribute.'], 400);
                    }
                }
                
             }
            }
            $item->itemAttributes()->whereNotIn('id', $newAttributes)->delete();
        }else {
            $item->itemAttributes()->delete();
        }
    
        if ($request->has('alternateItems')) {
            $existingAlternateItems = $item->alternateItems()->pluck('id')->toArray();
            $newAlternateItems = [];
      
            foreach ($request->input('alternateItems') as $altItemData) {
                if (isset($altItemData['item_code']) && !empty($altItemData['item_code']) &&
                isset($altItemData['item_name']) && !empty($altItemData['item_name'])) {
                if (isset($altItemData['id']) && in_array($altItemData['id'], $existingAlternateItems)) {
                    $item->alternateItems()->where('id', $altItemData['id'])->update([
                        'item_name' => $altItemData['item_name'], 
                        'item_code' => $altItemData['item_code'], 
                    ]);
                    $newAlternateItems[] = $altItemData['id'];
                } else {
                    $newAltItem = $item->alternateItems()->create([
                        'item_name' => $altItemData['item_name'],
                        'item_code' => $altItemData['item_code'], 
                    ]);
                    $newAlternateItems[] = $newAltItem->id;
                }
             }
            }
            $item->alternateItems()->whereNotIn('id', $newAlternateItems)->delete();
        }else {
            $item->alternateItems()->delete();
        }
    
        if ($request->has('item_specifications')) {
            $specifications = $request->input('item_specifications');
            $item->specifications()->delete();
            foreach ($specifications as $specificationData) {
                if (isset($specificationData['specification_name']) && !empty($specificationData['specification_name'])) {
                    $item->specifications()->create([
                        'group_id' => $specificationData['group_id'] ?? null,
                        'specification_id' => $specificationData['specification_id'] ?? null,
                        'specification_name' => $specificationData['specification_name'],
                        'value' => $specificationData['value'] ?? null,
                    ]);
                }
            }
        }else {
            $item->specifications()->delete();
        }
        $notesData = $validatedData['notes'] ?? [];
        if (!empty($notesData)) {
            $this->commonService->createNote($notesData,$item,$user); 
        }
        DB::commit();
        return response()->json(['message' => 'Item updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteAlternateUOM($id)
    {
        DB::beginTransaction();
        try {
            $uom = AlternateUOM::find($id);
            if ($uom) {
                $result = $uom->deleteWithReferences();
                if (!$result['status']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => $result['message'],
                        'referenced_tables' => $result['referenced_tables'] ?? []
                    ], 400);
                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'UOM not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    

    public function deleteApprovedCustomer($id)
    {
        DB::beginTransaction();
        try {
            $customer = CustomerItem::find($id);
            if ($customer) {
                $result = $customer->deleteWithReferences();
                if (!$result['status']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => $result['message']], 400);
                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'Approved customer not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    public function deleteApprovedVendor($id)
    {
        DB::beginTransaction();
        try {
            $vendor = VendorItem::find($id);
            if ($vendor) {
                $result = $vendor->deleteWithReferences();
                if (!$result['status']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => $result['message']], 400);
                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'Approved vendor not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    public function deleteAttribute($id)
    {
        DB::beginTransaction();
        try {
            $attribute = ItemAttribute::find($id);
            if ($attribute) {
                $result = $attribute->deleteWithReferences();
                if (!$result['status']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => $result['message']], 400);
                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'Attribute not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    
    public function deleteAlternateItem($id)
    {
        DB::beginTransaction();
        try {
            $alternateItem = AlternateItem::find($id);
            if ($alternateItem) {
                $result = $alternateItem->deleteWithReferences();
                if (!$result['status']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => $result['message']], 400);
                }
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Record deleted successfully']);
            }
            return response()->json(['success' => false, 'message' => 'Alternate item not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = Item::findOrFail($id);
            $referenceTables = [
                'erp_item_attributes' => ['item_id'],
                'erp_item_specifications' => ['item_id'],
                'erp_item_subtypes' => ['item_id'],
                'erp_customer_items' => ['item_id'],
                'erp_vendor_items' => ['item_id'],
                'erp_alternate_items' => ['item_id'],
                'erp_alternate_uoms' => ['item_id'],
            ];
            $result = $item->deleteWithReferences($referenceTables);
            if (!$result['status']) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => $result['message'],
                    'referenced_tables' => $result['referenced_tables'] ?? []
                ], 400);
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Record deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getItem(Request $request)
    {
        $searchTerm = $request->input('term', ''); 
        $items = Item::withDefaultGroupCompanyOrg() 
            ->where('item_name', 'like', "%{$searchTerm}%")
            ->where('status', ConstantHelper::ACTIVE)
            ->limit(10)
            ->get(['id', 'item_name', 'item_code']);
        if ($items->isEmpty()) {
            $items = Item::withDefaultGroupCompanyOrg()
                ->where('status', ConstantHelper::ACTIVE)
                ->limit(10)
                ->get(['id', 'item_name', 'item_code']);
        }
        $formattedItems = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'label' => $item->item_name,
                'value' => $item->item_name,
                'code' => $item->item_code,
            ];
        });
    
        return response()->json($formattedItems);
    }
    
    public function getUOM(Request $request)
    {
      
        $selectedUOMIds = $request->input('selectedUOMIds');
        $selectedUOMTypes = $request->input('selectedUOMTypes');
        return response()->json([
            'selectedUOMIds' => $selectedUOMIds,
            'selectedUOMTypes' => $selectedUOMTypes,
            'message' => 'UOM types received successfully',
        ]);
    }

    # Get item rate
    public function getItemCost(Request $request)
    {
        $itemId = $request->item_id;
        $attributes = null;
        $uomId = $request->uom_id;
        $currencyId = $request->currency_id;
        $transactionDate = $request->transaction_date ?? date('Y-m-d');
        $vendorId = $request->vendor_id;
        $a = ItemHelper::getItemCostPrice($itemId, $attributes, $uomId, $currencyId, $transactionDate, $vendorId);
        return response()->json(['data' => ['cost' => $a], 'message' => 'get item cost', 'status' => 200]);
    }
}
