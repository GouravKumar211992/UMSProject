<?php

namespace App\Http\Controllers;

use App\Helpers\ConstantHelper;
use App\Helpers\Helper;
use App\Helpers\ServiceParametersHelper;
use App\Models\Book;
use App\Models\Category;
use App\Models\DiscountMaster;
use App\Models\Employee;
use App\Models\ErpBin;
use App\Models\ErpCustomer;
use App\Models\ErpRack;
use App\Models\ErpSaleInvoice;
use App\Models\ErpSaleOrder;
use App\Models\ErpShelf;
use App\Models\ErpStore;
use App\Models\ExpenseMaster;
use App\Models\Hsn;
use App\Models\Item;
use App\Models\LandLease;
use App\Models\LandParcel;
use App\Models\LandPlot;
use App\Models\Ledger;
use App\Models\Group;
use App\Models\Organization;
use App\Models\OrganizationService;
use App\Models\ProductSection;
use App\Models\ProductSectionDetail;
use App\Models\ProductSpecification;
use App\Models\PurchaseIndent;
use App\Models\PurchaseOrder;
use App\Models\Station;
use App\Models\Vendor;
use App\Models\VendorItem;
use App\Models\Department;
use App\Models\Bom;
use Auth;
use Illuminate\Http\Request;

class AutocompleteController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->input('q');
        $type = $request->input('type');
        $id = $request->input('id');
        $categoryId = $request->input('categoryId');
        $results = [];
        $authUser = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $authUser->organization_id)->first(); 
        $organizationId = $organization ?-> id ?? null;
        $companyId =  $organization?->company_id ?? null;

        try {
            if ($type === 'category') {
                $query = Category::where('organization_id', $authUser->organization_id)
                ->where('status', ConstantHelper::ACTIVE)
                ->whereNull('parent_id');
        
                if ($request->has('category_type')) {
                    $query->where('type', $request->input('category_type'));
                }
            
                $results = $query->when($term, function ($q) use ($term) {
                    return $q->where('name', 'LIKE', "%$term%");
                })->get(['id', 'name', 'cat_initials']);

                if ($results->isEmpty()) {
                    $fallbackQuery = Category::where('status', ConstantHelper::ACTIVE)
                    ->where('organization_id', $authUser->organization_id)
                    ->whereNull('parent_id');
        
                if ($request->has('category_type')) {
                    $fallbackQuery->where('type', $request->input('category_type'));
                }
        
                $results = $fallbackQuery->limit(10)->get(['id', 'name', 'cat_initials']);
              }
            } elseif ($type === 'subcategory') {
                $query = Category::where('organization_id', $authUser->organization_id)
                    ->where('status', ConstantHelper::ACTIVE)
                    ->when($request->has('category_type'), function ($q) use ($request) {
                        return $q->where('type', $request->input('category_type'));
                    })
                    ->when($term, function ($q) use ($term) {
                        return $q->where('name', 'LIKE', "%$term%");
                    });
            
                if ($categoryId) {
                    $query->where('parent_id', $categoryId);
                }
            
                $results = $query->get(['id', 'name', 'sub_cat_initials']);
            
                if ($results->isEmpty()) {
                    $fallbackQuery = Category::where('status', ConstantHelper::ACTIVE)
                        ->when($categoryId, function ($q) use ($categoryId) {
                            return $q->where('parent_id', $categoryId);
                        });
            
                    if ($request->has('category_type')) {
                        $fallbackQuery->where('type', $request->input('category_type'));
                    }
            
                    $results = $fallbackQuery->limit(10)->get(['id', 'name', 'sub_cat_initials']);
                }
            }
    
             elseif ($type === 'hsn') {
                $results = Hsn::where('code', 'LIKE', "%$term%")
                -> withDefaultGroupCompanyOrg()
                    ->where('status', ConstantHelper::ACTIVE)
                    ->get(['id', 'code']);

                if ($results->isEmpty()) {
                    $results = Hsn::where('status', ConstantHelper::ACTIVE)
                    -> withDefaultGroupCompanyOrg()
                        ->limit(10)
                        ->get(['id', 'code']);
                }
            } elseif ($type === 'header_item') {
                $type = ['WIP/Semi Finished', 'Finished Goods'];
                $results = Item::withDefaultGroupCompanyOrg()
                    ->whereHas('subTypes', function ($query) use ($type) {
                        $query->whereIn('name', $type);
                    })
                    ->where(function($query) use ($term) {
                        $query->where('item_name', 'LIKE', "%{$term}%")
                        ->orWhere('item_code', 'LIKE', "%{$term}%");
                    })
                    ->where('status', ConstantHelper::ACTIVE)
                    ->limit(10)
                    ->get(['id', 'item_name', 'item_code']);
            } elseif ($type === 'comp_item') {
                /*This is for Bom*/
                // $selectedAllItemIds = json_decode($request->input('selectedAllItemIds'), true) ?? [];
                // if(count($selectedAllItemIds)) {
                //     array_unique($selectedAllItemIds);
                // }
                $type = ['Raw Material','WIP/Semi Finished','Traded Item', 'Expense'];
                $results = Item::withDefaultGroupCompanyOrg()
                    ->whereHas('subTypes', function ($query) use ($type) {
                        $query->whereIn('name', $type);
                    })
                    ->where(function ($query) use ($term) {
                    $query->where('item_name', 'LIKE', "%{$term}%")
                          ->orWhere('item_code', 'LIKE', "%{$term}%");
                    })
                    -> when($request -> customer_id, function ($custQuery) use($request) {
                        $custQuery-> where(function ($query) use ($request) {
                            $query->whereHas('approvedCustomers', function ($subQuery) use ($request) {
                                $subQuery->where('customer_id', $request->customer_id); // Match the specific customer
                            })
                            ->orWhereDoesntHave('approvedCustomers'); // Include items not linked to any customers
                        });
                    })
                    -> with(['alternateUOMs.uom', 'specifications'])
                    // ->whereNotIn('id', $selectedAllItemIds) // Uncomment if needed
                    ->where('status', ConstantHelper::ACTIVE)
                    ->with(['itemAttributes:id'])
                    ->with(['uom:id,name'])
                    ->withCount('itemAttributes')
                    ->limit(10)
                    ->get(['id', 'item_name', 'item_code', 'uom_id']);
            } elseif ($type === 'sale_module_items') {

                $itemType = ServiceParametersHelper::getBookLevelParameterValue(ServiceParametersHelper::GOODS_SERVICES_PARAM, $request -> header_book_id)['data'];
                $results = Item::withDefaultGroupCompanyOrg()
                    ->where(function ($query) use ($term) {
                    $query->where('item_name', 'LIKE', "%{$term}%")
                          ->orWhere('item_code', 'LIKE', "%{$term}%");
                    })
                    -> when($request -> customer_id, function ($custQuery) use($request) {
                        $custQuery-> where(function ($query) use ($request) {
                            $query->whereHas('approvedCustomers', function ($subQuery) use ($request) {
                                $subQuery->where('customer_id', $request->customer_id); // Match the specific customer
                            })
                            ->orWhereDoesntHave('approvedCustomers'); // Include items not linked to any customers
                        });
                    })
                    -> whereIn('type', $itemType)
                    -> with(['alternateUOMs.uom', 'specifications'])
                    ->where('status', ConstantHelper::ACTIVE)
                    ->with(['itemAttributes'])
                    ->with(['uom:id,name'])
                    ->withCount('itemAttributes')
                    ->limit(10)
                    ->get(['id', 'item_name', 'item_code', 'uom_id']);
            } elseif ($type === 'sales_module_discount') {
                $results = DiscountMaster::withDefaultGroupCompanyOrg()
                    ->where(function ($query) use ($term) {
                    $query->where('name', 'LIKE', "%{$term}%")
                          ->orWhere('alias', 'LIKE', "%{$term}%");
                    })
                    -> when($request -> selected_discount_ids, function ($discountQuery) use($request) {
                        $discountQuery -> whereNotIn('id', $request -> selected_discount_ids);
                    })
                    -> where('is_sale', 1)
                    -> where('status', ConstantHelper::ACTIVE)
                    ->limit(10)
                    ->get(['id', 'name', 'alias', 'percentage']);
            } elseif ($type === 'sales_module_expense') {
                $results = ExpenseMaster::withDefaultGroupCompanyOrg()
                    ->where(function ($query) use ($term) {
                    $query->where('name', 'LIKE', "%{$term}%")
                          ->orWhere('alias', 'LIKE', "%{$term}%");
                    })
                    -> where('is_sale', 1)
                    -> where('status', ConstantHelper::ACTIVE)
                    ->limit(10)
                    ->get(['id', 'name', 'alias', 'percentage']);
            }  elseif ($type === 'po_module_discount') {
                $ids = json_decode($request->ids, TRUE) ?? [];
                $ids = array_map('intval', $ids);
                $results = DiscountMaster::withDefaultGroupCompanyOrg()
                    ->where(function($q) use ($ids) {
                        if(count($ids)) {
                            $q->whereNotIn('id', $ids);
                        }
                    })
                    ->where(function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%{$term}%")
                          ->orWhere('alias', 'LIKE', "%{$term}%");
                    })
                    -> where('is_purchase', 1)
                    -> where('status', ConstantHelper::ACTIVE)
                    ->limit(10)
                    ->get(['id', 'name', 'alias', 'percentage']);
            } elseif ($type === 'po_module_expense') {
                $ids = json_decode($request->ids, TRUE) ?? [];
                $ids = array_map('intval', $ids);
                $results = ExpenseMaster::withDefaultGroupCompanyOrg()
                    ->where(function($q) use ($ids) {
                        if(count($ids)) {
                            $q->whereNotIn('id', $ids);
                        }
                    })
                    ->where(function ($query) use ($term) {
                        $query->where('name', 'LIKE', "%{$term}%")
                          ->orWhere('alias', 'LIKE', "%{$term}%");
                    })
                    -> where('is_purchase', 1)
                    -> where('status', ConstantHelper::ACTIVE)
                    ->limit(10)
                    ->get(['id', 'name', 'alias', 'percentage']);
            } elseif ($type === 'po_item_list') {
                /*This for the PO*/
                // $selectedAllItemIds = json_decode($request->input('selectedAllItemIds'), true) ?? [];
                // // dd($selectedAllItemIds);
                // if(count($selectedAllItemIds)) {
                //     array_unique($selectedAllItemIds);
                // }
                $results = Item::withDefaultGroupCompanyOrg()
                    ->where(function($query) use ($term) {
                            $query->where('item_name', 'LIKE', "%{$term}%")
                            ->orWhere('item_code', 'LIKE', "%{$term}%");
                        })
                    // ->whereNotIn('id', $selectedAllItemIds) // Uncomment if needed
                    ->where('status', ConstantHelper::ACTIVE)
                    ->with(['uom:id,name'])
                    ->with(['hsn:id,code'])
                    ->with(['alternateUOMs.uom'])
                    ->withCount('itemAttributes')
                    ->limit(10)
                    ->get(['id', 'item_name', 'item_code', 'uom_id','hsn_id']);
            } elseif ($type === 'service_item_list') {
                /*This for the Service Based Items*/
                $selectedAllItemIds = json_decode($request->input('selectedAllItemIds'), true) ?? [];
                if(count($selectedAllItemIds)) {
                    array_unique($selectedAllItemIds);
                }
                $results = Item::selectRaw('*, COALESCE(company_id, ?) as company_id, COALESCE(organization_id, ?) as organization_id', [$companyId, $organizationId])
                    ->where('group_id',$organization->group_id)
                    ->where('type', 'Service')
                    ->where(function($query) use ($term) {
                            $query->where('item_name', 'LIKE', "%{$term}%")
                            ->orWhere('item_code', 'LIKE', "%{$term}%");
                        })
                    ->where('status', ConstantHelper::ACTIVE)
                    ->with(['uom:id,name'])
                    ->with(['hsn:id,code'])
                    ->with(['alternateUOMs.uom'])
                    ->withCount('itemAttributes')
                    ->limit(10)
                    ->get(['id', 'item_name', 'item_code', 'uom_id','hsn_id']);
            } elseif ($type === 'goods_item_list') {
                /*This for the Service Based Items*/
                $selectedAllItemIds = json_decode($request->input('selectedAllItemIds'), true) ?? [];
                if(count($selectedAllItemIds)) {
                    array_unique($selectedAllItemIds);
                }
                $results = Item::selectRaw('*, COALESCE(company_id, ?) as company_id, COALESCE(organization_id, ?) as organization_id', [$companyId, $organizationId])
                    ->where('group_id',$organization->group_id)
                    ->where('type', 'Goods')
                    ->where(function($query) use ($term) {
                            $query->where('item_name', 'LIKE', "%{$term}%")
                            ->orWhere('item_code', 'LIKE', "%{$term}%");
                        })
                    ->where('status', ConstantHelper::ACTIVE)
                    ->with(['uom:id,name'])
                    ->with(['hsn:id,code'])
                    ->with(['alternateUOMs.uom'])
                    ->withCount('itemAttributes')
                    ->limit(10)
                    ->get(['id', 'item_name', 'item_code', 'uom_id','hsn_id']);
            } elseif ($type === 'ledger' || $type === 'ladger') {
                $results = Ledger::withDefaultGroupCompanyOrg()
                                 ->where(function($query) use ($term) {
                                     $query->where('code', 'LIKE', "%{$term}%")
                                           ->orWhere('name', 'LIKE', "%{$term}%");
                                 })
                                 ->where('status', 1)
                                 ->get(['id', 'code', 'name']);
        
                if ($results->isEmpty()) {
                    $results = Ledger::where('status', 1)
                                     ->limit(10)
                                     ->get(['id', 'code', 'name']);
                }
            }elseif ($type === 'ledgerGroup') {
                    $results = Group::where('status', 1)
                                    ->limit(10)
                                    ->get(['id', 'name']);
            }elseif ($type === 'book') {
                $serviceAlias = ConstantHelper::BOM_SERVICE_ALIAS;
                $subQuery = Helper::getBookSeries($serviceAlias)->get();
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_sq') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $subQuery = Helper::getBookSeries(ConstantHelper::SQ_SERVICE_ALIAS);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                    ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('id', $applicableBookIds);
                    })
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                        ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                            $applicableQuery -> whereIn('id', $applicableBookIds);
                        })
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_so') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);

                $subQuery = Helper::getBookSeries(ConstantHelper::SO_SERVICE_ALIAS);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                    $applicableQuery -> whereIn('id', $applicableBookIds);
                })
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                    ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('id', $applicableBookIds);
                    })
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_din') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $subQuery = Helper::getBookSeries(ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                    ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('id', $applicableBookIds);
                    })
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                        ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                            $applicableQuery -> whereIn('id', $applicableBookIds);
                        })
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_land_lease') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $subQuery = Helper::getBookSeries(ConstantHelper::LAND_LEASE);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                    ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('id', $applicableBookIds);
                    })
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                        ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                            $applicableQuery -> whereIn('id', $applicableBookIds);
                        })
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_pi') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                if($request->module_type == 'supplier-invoice') {
                    $pi = ConstantHelper::PO_SERVICE_ALIAS;
                } else {
                    $pi = ConstantHelper::PI_SERVICE_ALIAS;
                }
                $subQuery = Helper::getBookSeries($pi);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                    ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('id', $applicableBookIds);
                    })
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_bom') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request->header_book_id);
                $pi = ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS;
                $subQuery = Helper::getBookSeries($pi);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                    // ->when($request->header_book_id, function ($applicableQuery) use($applicableBookIds) {
                    //     $applicableQuery -> whereIn('id', $applicableBookIds);
                    // })
                    ->get(['id', 'book_name', 'book_code']); 
                if ($results->isEmpty()) {
                    $results = $subQuery
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_po') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $subQuery = Helper::getBookSeries(ConstantHelper::PO_SERVICE_ALIAS);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                    $applicableQuery -> whereIn('id', $applicableBookIds);
                })
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                    ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('id', $applicableBookIds);
                    })
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_mrn') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $subQuery = Helper::getBookSeries(ConstantHelper::MRN_SERVICE_ALIAS);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                    $applicableQuery -> whereIn('id', $applicableBookIds);
                })
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                    ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('id', $applicableBookIds);
                    })
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'book_si') {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $subQuery = Helper::getBookSeries(ConstantHelper::SI_SERVICE_ALIAS);
                $results = $subQuery->where('book_name', 'LIKE', "%$term%")
                ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                    $applicableQuery -> whereIn('id', $applicableBookIds);
                })
                    ->get(['id', 'book_name', 'book_code']); 

                if ($results->isEmpty()) {
                    $results = $subQuery
                    ->when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('id', $applicableBookIds);
                    })
                        ->limit(10)
                        ->get(['id', 'book_name', 'book_code']); 
                }
            } elseif ($type === 'vendor_list') {
                $itemId = $request->item_id;
                $vendorIds = VendorItem::where('item_id', $itemId)->pluck('vendor_id')->toArray();
                $subQuery = Vendor::withDefaultGroupCompanyOrg()
                            ->where(function($query) use ($vendorIds) {
                                if(count($vendorIds)) {
                                    $query->whereIn('id',$vendorIds);
                                }
                            })
                            ->where('status', ConstantHelper::ACTIVE)
                            ->with(['currency:id,name'])
                            ->with(['paymentTerms:id,name']);
                $results = $subQuery->where('company_name', 'LIKE', "%$term%")
                    ->get(['id','company_name','vendor_code']); 
                if ($results->isEmpty()) {
                    $results = $subQuery
                        ->limit(10)
                        ->get(['id','company_name','vendor_code']); 
                }
            } elseif ($type === 'product_section') {
                $subQuery = ProductSection::withDefaultGroupCompanyOrg()
                ->where('status', ConstantHelper::ACTIVE);

                $results = $subQuery->where('name', 'LIKE', "%$term%")
                    ->get(['id','name']); 
                if ($results->isEmpty()) {
                    $results = $subQuery->limit(10)
                        ->get(['id','name']); 
                }
            } elseif ($type === 'product_sub_section') {
                $subQuery = ProductSectionDetail::where('section_id', $id)
                            ->with(['station:id,name']);
                $results = $subQuery->where('name', 'LIKE', "%$term%")
                    ->get(['id','name','station_id']);
                if ($results->isEmpty()) {
                    $results = $subQuery->limit(10)
                        ->get(['id','name','station_id']); 
                }
            } elseif ($type === 'station') {
                $subQuery = Station::withDefaultGroupCompanyOrg()
                        ->where('status', ConstantHelper::ACTIVE);

                $results = $subQuery->where('name', 'LIKE', "%$term%")
                    ->get(['id', 'name']); 
                if ($results->isEmpty()) {
                    $results = $subQuery->limit(10)
                        ->get(['id','name','station_id']); 
                }
            } else if ($type === 'customer') {
                $results = ErpCustomer::with(['payment_terms', 'currency'])
                ->where('company_name', 'LIKE', "%$term%")
                ->where('status', ConstantHelper::ACTIVE)
                ->withDefaultGroupCompanyOrg()
                ->get(['id', 'customer_code', 'company_name', 'currency_id', 'payment_terms_id']);

                if ($results->isEmpty()) {
                    $results = ErpCustomer::with(['payment_terms', 'currency'])
                                    ->where('status', ConstantHelper::ACTIVE)
                                    ->withDefaultGroupCompanyOrg()
                                    ->limit(10)
                                    ->get(['id', 'customer_code', 'company_name', 'currency_id', 'payment_terms_id']);
                                }
            } elseif ($type === 'specification') {
                $results = ProductSpecification::withDefaultGroupCompanyOrg()->where('name', 'LIKE', "%$term%")
                    ->where('status', ConstantHelper::ACTIVE)
                    ->get(['id', 'name', 'description']);
                if ($results->isEmpty()) {
                    $results = ProductSpecification::withDefaultGroupCompanyOrg()->where('status', ConstantHelper::ACTIVE)
                        ->limit(10)
                        ->get(['id', 'name', 'description']);
                }
            } else if ($type === "sale_order_document_qt") {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $results = ErpSaleOrder::where('document_number', 'LIKE', "%$term%")
                    -> where('document_type', ConstantHelper::SQ_SERVICE_ALIAS)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                    ->get(['id', 'document_number']);
                if ($results->isEmpty()) {
                    $results = ErpSaleOrder::where('document_type', ConstantHelper::SQ_SERVICE_ALIAS)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                    ->get(['id', 'document_number']);
                }
            } else if ($type === "sale_order_document_qt_pi") {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $results = ErpSaleOrder::where('document_number', 'LIKE', "%$term%")
                    -> where('document_type', ConstantHelper::SO_SERVICE_ALIAS)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                    ->get(['id', 'document_number']);
                if ($results->isEmpty()) {
                    $results = ErpSaleOrder::where('document_type', ConstantHelper::SQ_SERVICE_ALIAS)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                    ->get(['id', 'document_number']);
                }
            } else if ($type === "pi_document_qt") {
                if($request->module_type == 'supplier-invoice') {
                    $results = PurchaseOrder::withDefaultGroupCompanyOrg()
                        ->where('document_number', 'LIKE', "%$term%")
                        ->where('type','po')
                        ->get(['id', 'document_number']);
                    if ($results->isEmpty()) {
                        $results = PurchaseOrder::withDefaultGroupCompanyOrg()
                        ->where('type','po')
                        ->limit(10)
                        ->get(['id', 'document_number']);
                    }
                } else {
                    $results = PurchaseIndent::withDefaultGroupCompanyOrg()
                        ->where('document_number', 'LIKE', "%$term%")
                        ->distinct('document_number')
                        ->get(['id', 'document_number']);
                    if ($results->isEmpty()) {
                        $results = PurchaseIndent::withDefaultGroupCompanyOrg()
                        ->distinct('document_number')
                        ->limit(10)
                        ->get(['id', 'document_number']);
                    }
                }
            } else if ($type === "po_document_qt") {
                $results = PurchaseOrder::withDefaultGroupCompanyOrg()->where('document_number', 'LIKE', "%$term%")
                    ->get(['id', 'document_number']);
                if ($results->isEmpty()) {
                    $results = PurchaseOrder::withDefaultGroupCompanyOrg()->limit(10)
                        ->get(['id', 'document_number']);
                    }
            }  else if ($type === "bom_document_qt") {
                $results = Bom::withDefaultGroupCompanyOrg()
                    ->where('type',ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS)
                    ->where('document_number', 'LIKE', "%$term%")
                    ->get(['id', 'document_number']);
                if ($results->isEmpty()) {
                    $results = Bom::withDefaultGroupCompanyOrg()
                        ->where('type',ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS)
                        ->limit(10)
                        ->get(['id', 'document_number']);
                    }
            } else if ($type === "sale_order_document") {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);

                $results = ErpSaleOrder::where('document_number', 'LIKE', "%$term%")
                    -> where('document_type', ConstantHelper::SO_SERVICE_ALIAS)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                    ->get(['id', 'document_number']);
                if ($results->isEmpty()) {
                    $results = ErpSaleOrder::limit(10)
                    -> where('document_type', ConstantHelper::SO_SERVICE_ALIAS)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                        ->get(['id', 'document_number']);
                    }
            } else if ($type === "land_lease_document") {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $results = LandLease::where('document_no', 'LIKE', "%$term%")
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('approvalStatus', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                    ->get(['id', 'document_no AS document_number']);
                if ($results->isEmpty()) {
                    $results = LandLease::limit(10)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('approvalStatus', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                        ->get(['id', 'document_no AS document_number']);
                    }
            } else if ($type === 'land_lease_parcel') {
                $results = LandParcel::withDefaultGroupCompanyOrg()->where('name', 'LIKE', "%$term%") -> select('id', 'name') -> get() ;
                if ($results->isEmpty()) {
                    $results = LandParcel::limit(10)
                    -> withDefaultGroupCompanyOrg()
                    ->get(['id', 'name']);
                    }
            } else if ($type === 'land_lease_plots') {
                $results = LandPlot::withDefaultGroupCompanyOrg()->where('plot_name', 'LIKE', "%$term%") -> select('id', 'plot_name') -> get() ;
                if ($results->isEmpty()) {
                    $results = LandPlot::limit(10)
                    -> withDefaultGroupCompanyOrg()
                    ->get(['id', 'plot_name']);
                    }
            } else if ($type === "din_document") {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $results = ErpSaleInvoice::where('document_number', 'LIKE', "%$term%")
                    -> where('document_type', ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])                    
                    ->get(['id', 'document_number']);
                if ($results->isEmpty()) {
                    $results = ErpSaleInvoice::limit(10)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                        ->get(['id', 'document_number']);
                    }
            } else if ($type === "si_document") {
                $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
                $results = ErpSaleInvoice::where('document_number', 'LIKE', "%$term%")
                -> withDefaultGroupCompanyOrg()
                -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                    $applicableQuery -> whereIn('book_id', $applicableBookIds);
                })
                -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED])
                    ->get(['id', 'document_number']);
                if ($results->isEmpty()) {
                    $results = ErpSaleInvoice::limit(10)
                    -> withDefaultGroupCompanyOrg()
                    -> when($request -> header_book_id, function ($applicableQuery) use($applicableBookIds) {
                        $applicableQuery -> whereIn('book_id', $applicableBookIds);
                    })
                    -> whereIn('document_status', [ConstantHelper::APPROVAL_NOT_REQUIRED, ConstantHelper::APPROVED]) 
                    ->get(['id', 'document_number']);
                    }
            } else if ($type === "store") {
                $results = ErpStore::where('store_code', 'LIKE', "%$term%")
                    -> where('organization_id', $authUser -> organization_id)
                    ->get(['id', 'store_code']);
                if ($results->isEmpty()) {
                    $results = ErpStore::where('organization_id', $authUser -> organization_id)
                        ->limit(10)
                        ->get(['id', 'store_code']);
                }
            } else if ($type === "store_rack") {
                $results = ErpRack::where('rack_code', 'LIKE', "%$term%")
                    -> where('erp_store_id', $request -> store_id)
                    ->get(['id', 'rack_code']);
                if ($results->isEmpty()) {
                    $results = ErpRack::where('erp_store_id', $request -> store_id)
                        ->limit(10) 
                        ->get(['id', 'rack_code']);
                }
            } else if ($type === "rack_shelf") {
                $results = ErpShelf::where('shelf_code', 'LIKE', "%$term%")
                    -> where('erp_rack_id', $request -> rack_id)
                    ->get(['id', 'shelf_code']);
                if ($results->isEmpty()) {
                    $results = ErpShelf::where('erp_rack_id', $request -> rack_id)
                        ->limit(10)
                        ->get(['id', 'shelf_code']);
                }
            } else if ($type === "shelf_bin") {
                $results = ErpBin::where('bin_code', 'LIKE', "%$term%")
                    -> where('erp_shelf_id', $request -> shelf_id)
                    ->get(['id', 'bin_code']);
                if ($results->isEmpty()) {
                    $results = ErpBin::where('erp_shelf_id', $request -> shelf_id)
                        ->limit(10)
                        ->get(['id', 'bin_code']);
                }
            } elseif ($type === 'salesPerson') {
                $results = Employee::where('name', 'LIKE', "%$term%")
                ->where('status', ConstantHelper::ACTIVE)
                ->where('organization_id', $authUser -> organization_id)
                ->get(['id', 'name']);

                if ($results->isEmpty()) {
                    $results = Employee::where('status', 'active')
                    ->where('organization_id', $authUser -> organization_id)
                        ->limit(10)
                        ->get(['id', 'name']);
                }
            } else if ($type === 'org_services') {
                $results = OrganizationService::withDefaultGroupCompanyOrg() -> where(function ($subQuery) use($term) {
                    $subQuery -> where('name', 'LIKE', '%'.$term.'%') -> orWhere('alias', 'LIKE', '%'.$term.'%');
                })  -> get();
                if ($results->isEmpty()) {
                    $results = OrganizationService::withDefaultGroupCompanyOrg()-> limit(10) ->get();
                }
            } else if ($type === 'vendor_company_list') {
                $vendorId = $authUser?->vendor_portal?->id ?? null;
                $orgIds = PurchaseOrder::where('vendor_id', $vendorId)
                ->distinct()
                ->pluck('organization_id')
                ->toArray();
                $results = Organization::where(function ($subQuery) use($term) {
                    $subQuery -> where('name', 'LIKE', '%'.$term.'%') -> orWhere('alias', 'LIKE', '%'.$term.'%');
                })  -> get();
                if ($results->isEmpty()) {
                    $results = Organization::limit(10) ->get();
                }
            }  else if ($type === 'department') {
                $results = Department::where('organization_id', $organizationId)
                ->where('status', ConstantHelper::ACTIVE)
                ->get();

                if ($results->isEmpty()) {
                    $results = Department::limit(10)
                    ->where('status', ConstantHelper::ACTIVE)
                    ->get();
                }
            } else {
                return response()->json(['error' => 'Invalid type specified'], 400);
            }
            
            return response()->json($results);
        } catch (\Exception $e) {
            \Log::error('Autocomplete search failed: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}