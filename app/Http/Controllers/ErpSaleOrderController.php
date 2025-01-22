<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiGenericException;
use App\Helpers\ConstantHelper;
use App\Helpers\CurrencyHelper;
use App\Helpers\Helper;
use App\Helpers\InventoryHelper;
use App\Helpers\ItemHelper;
use App\Helpers\SaleModuleHelper;
use App\Helpers\ServiceParametersHelper;
use App\Helpers\TaxHelper;
use App\Helpers\NumberHelper;
use App\Http\Requests\ErpSaleOrderRequest;
use App\Models\Book;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Address;
use App\Models\ErpAddress;
use App\Models\ErpAttribute;
use App\Models\ErpSaleOrderHistory;
use App\Models\ErpSoMedia;
use App\Models\Item;
use App\Models\ErpItemAttribute;
use App\Models\ErpSaleOrder;
use App\Models\ErpSaleOrderItem;
use App\Models\ErpSaleOrderItemAttribute;
use App\Models\ErpSaleOrderItemDelivery;
use App\Models\ErpSaleOrderMrnTed;
use App\Models\ErpSaleOrderTed;
use App\Models\ErpSoItem;
use App\Models\ErpSoItemAttribute;
use App\Models\ErpSoItemDelivery;
use App\Models\ErpVendor;
use App\Models\NumberPattern;
use App\Models\Organization;
use App\Models\ServiceParameter;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ErpSaleOrderController extends Controller
{
    public function index(Request $request)
    {
        $pathUrl = request()->segments()[0];
        if ($pathUrl === 'sales-quotation') {
            $orderType = ConstantHelper::SQ_SERVICE_ALIAS;
            $redirectUrl = route('sale.quotation.index');
            $createRoute = route('sale.quotation.create');
        }
        if ($pathUrl === 'sales-order') {
            $orderType = ConstantHelper::SO_SERVICE_ALIAS;
            $redirectUrl = route('sale.order.index');
            $createRoute = route('sale.order.create');

        }
        request() -> merge(['type' => $orderType]);
        
        if ($request -> ajax()) {

            $salesOrder = ErpSaleOrder::where('document_type', $orderType) -> bookViewAccess($pathUrl) -> withDefaultGroupCompanyOrg() -> withDraftListingLogic() -> orderByDesc('id') ->  get();
            return DataTables::of($salesOrder) ->addIndexColumn()
            ->editColumn('document_status', function ($row) use($orderType) {
                $statusClasss = ConstantHelper::DOCUMENT_STATUS_CSS_LIST[$row->document_status];    
                $displayStatus = $row -> display_status;   
                $editRoute = null;
                if ($orderType == ConstantHelper::SO_SERVICE_ALIAS) {
                    $editRoute = route('sale.order.edit', ['id' => $row->id]);
                }
                if ($orderType == ConstantHelper::SQ_SERVICE_ALIAS) {
                    $editRoute = route('sale.quotation.edit', ['id' => $row->id]);
                }     
                return "
                <div style='text-align:right;'>
                    <span class='badge rounded-pill $statusClasss badgeborder-radius'>$displayStatus</span>
                    <div class='dropdown' style='display:inline;'>
                        <button type='button' class='btn btn-sm dropdown-toggle hide-arrow py-0 p-0' data-bs-toggle='dropdown'>
                            <i data-feather='more-vertical'></i>
                        </button>
                        <div class='dropdown-menu dropdown-menu-end'>
                            <a class='dropdown-item' href='" . $editRoute . "'>
                                <i data-feather='edit-3' class='me-50'></i>
                                <span>View/ Edit Detail</span>
                            </a>
                        </div>
                    </div>
                </div>
            ";
            })
            ->addColumn('book_name', function ($row) {
                return $row->book_code ? $row->book_code : 'N/A';
            })
            ->addColumn('curr_name', function ($row) {
                return $row->currency ? ($row->currency?->short_name ?? $row->currency?->name) : 'N/A';
            })
            ->editColumn('document_date', function ($row) {
                return $row->getFormattedDate('document_date') ?? 'N/A';
            })
            ->editColumn('revision_number', function ($row) {
                return strval($row->revision_number);
            })
            ->addColumn('customer_name', function ($row) {
                return $row->customer?->company_name ?? 'NA';
            })
            ->addColumn('items_count', function ($row) {
                return $row->items->count();
            })
            ->editColumn('total_item_value', function ($row) {
                return number_format($row->total_item_value,2);
            })
            ->editColumn('total_discount_value', function ($row) {
                return number_format($row->total_discount_value,2);
            })
            ->editColumn('total_tax_value', function ($row) {
                return number_format($row->total_tax_value,2);
            })
            ->editColumn('total_expense_value', function ($row) {
                return number_format($row->total_expense_value,2);
            })
            ->editColumn('grand_total_amount', function ($row) {
                return number_format($row->total_amount,2);
            })
            ->rawColumns(['document_status'])
            ->make(true);
        }
        $parentURL = request() -> segments()[0];
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        return view('salesOrder.index', [
            'redirect_url' => $redirectUrl,
            'create_route' => $createRoute,
            'create_button' => count($servicesBooks['services'])
        ]);
    }
    public function create(Request $request)
    {
        $pathUrl = request()->segments()[0];
        if ($pathUrl === 'sales-quotation') {
            $orderType = ConstantHelper::SQ_SERVICE_ALIAS;
        }
        if ($pathUrl === 'sales-order') {
            $orderType = ConstantHelper::SO_SERVICE_ALIAS;
        }
        request() -> merge(['type' => $orderType]);
        $orderType = $request -> input('type', ConstantHelper::SO_SERVICE_ALIAS);
        //Get the menu 
        $parentUrl = request() -> segments()[0];
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentUrl);
        if (count($servicesBooks['services']) == 0) {
            return redirect() -> route('/');
        }
        $firstService = $servicesBooks['services'][0];
        $user = Helper::getAuthenticatedUser();
        $bookTypeAlias = ConstantHelper::SO_SERVICE_ALIAS;
        if ($orderType == ConstantHelper::SO_SERVICE_ALIAS) {
            $bookTypeAlias = ConstantHelper::SO_SERVICE_ALIAS;
        } else {
            $bookTypeAlias = ConstantHelper::SQ_SERVICE_ALIAS;
        }
        $books = [];
        $countries = Country::select('id AS value', 'name AS label') -> where('status', ConstantHelper::ACTIVE) -> get();
        $data = [
            'series' => $books,
            'countries' => $countries,
            'type' => $orderType,
            'user' => $user,
            'services' => $servicesBooks['services'],
            'selectedService'  => $firstService ?-> id ?? null,
        ];
        return view('salesOrder.create_edit', $data);
    }
    public function edit(Request $request, string $id)
    {
        try {
            $pathUrl = request()->segments()[0];
            if ($pathUrl === 'sales-quotation') {
                $orderType = ConstantHelper::SQ_SERVICE_ALIAS;
            }
            if ($pathUrl === 'sales-order') {
                $orderType = ConstantHelper::SO_SERVICE_ALIAS;
            }
        request() -> merge(['type' => $orderType]);
        $servicesBooks = [];
            $user = Helper::getAuthenticatedUser();
            $orderType = $request->input('type', ConstantHelper::SO_SERVICE_ALIAS);
            $bookTypeAlias = ConstantHelper::SO_SERVICE_ALIAS;
            if ($orderType === ConstantHelper::SQ_SERVICE_ALIAS) {
                $bookTypeAlias = ConstantHelper::SQ_SERVICE_ALIAS;
            }
            if (isset($request->revisionNumber)) {
                $order = ErpSaleOrderHistory::with(['media_files', 'discount_ted', 'expense_ted', 'billing_address_details', 'shipping_address_details'])->with('items', function ($query) {
                    $query->with('discount_ted', 'tax_ted', 'item_deliveries')->with([
                        'item' => function ($itemQuery) {
                            $itemQuery->with(['specifications', 'alternateUoms.uom', 'uom']);
                        }
                    ]);
                })->where('source_id', $id)->where('revision_number', $request->revisionNumber)->first();
                $ogOrder = ErpSaleOrder::find($id);
            } else {
                $order = ErpSaleOrder::with(['media_files', 'discount_ted', 'expense_ted', 'billing_address_details', 'shipping_address_details'])->with('items', function ($query) {
                    $query->with('discount_ted', 'tax_ted', 'item_deliveries')->with([
                        'item' => function ($itemQuery) {
                            $itemQuery->with(['specifications', 'alternateUoms.uom', 'uom']);
                        }
                    ]);
                })->find($id);
                $ogOrder = $order;
            }
                if (isset($order)) {
                    $parentUrl = request() -> segments()[0];
                    $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentUrl, $order -> book ?-> service ?-> alias);
                    foreach ($order -> items as &$soItem) {
                        $referencedAmount = ErpSoItem::where('sq_item_id', $soItem -> id) -> sum('order_qty');
                        if (isset($referencedAmount) && $referencedAmount > 0) {
                            $soItem -> min_attribute = $referencedAmount;
                            $soItem -> is_editable = false;
                            $soItem -> restrict_delete = true;
                        }
                        else if ($soItem -> sq_item_id !== null) {
                            $pulled = ErpSoItem::find($soItem -> sq_item_id);
                            if (isset($pulled)) {
                                $availableTotalQty = $soItem -> order_qty + $pulled -> balance_qty;
                                $soItem -> max_attribute = $availableTotalQty;
                                $soItem -> is_editable = false;
                            } else {
                                $soItem -> max_attribute = 999999;
                                $soItem -> is_editable = true;
                            }
                        } else {
                            $soItem->max_attribute = 999999;
                            $soItem->is_editable = true;
                        }
                    }
                }
                $revision_number = $order->revision_number;
                $totalValue = ($order -> total_item_value - $order -> total_discount_value) + $order -> total_tax_value + $order -> total_expense_value;
                $userType = Helper::userCheck();
                $buttons = Helper::actionButtonDisplay($order->book_id,$order->document_status , $order->id, $totalValue, $order->approval_level, $order -> created_by ?? 0, $userType['type'], $revision_number);
                $books = Helper::getBookSeriesNew($bookTypeAlias) -> get();
                $countries = Country::select('id AS value', 'name AS label') -> where('status', ConstantHelper::ACTIVE) -> get();
                $revNo = $order->revision_number;
                if($request->has('revisionNumber')) {
                    $revNo = intval($request->revisionNumber);
                } else {
                    $revNo = $order->revision_number;
                }
                $docValue = $order->total_amount ?? 0;
                $approvalHistory = Helper::getApprovalHistory($order->book_id, $ogOrder->id, $revNo, $docValue, $order -> created_by);
                $docStatusClass = ConstantHelper::DOCUMENT_STATUS_CSS[$order->document_status] ?? '';
                $data = [
                    'user' => $user,
                    'series' => $books,
                    'order' => $order,
                    'countries' => $countries,
                    'buttons' => $buttons,
                    'approvalHistory' => $approvalHistory,
                    'type' => $orderType,
                    'revision_number' => $revision_number,
                    'docStatusClass' => $docStatusClass,
                    'maxFileCount' => isset($order -> mediaFiles) ? (10 - count($order -> media_files)) : 10,
                    'services' => $servicesBooks['services']
                ];
                return view('salesOrder.create_edit', $data);
            
        } catch(Exception $ex) {
            dd($ex -> getMessage());
        }
    }

    public function store(ErpSaleOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = Helper::getAuthenticatedUser();
            //Auth credentials
            $organization = Organization::find($user -> organization_id);
            $organizationId = $organization ?-> id ?? null;
            $groupId = $organization ?-> group_id ?? null;
            $companyId = $organization ?-> company_id ?? null;
            //Tax Country and State
            $firstAddress = $organization->addresses->first();
            $companyCountryId = null;
            $companyStateId = null;
            if ($firstAddress) {
                $companyCountryId = $firstAddress->country_id;
                $companyStateId = $firstAddress->state_id;
            } else {
                return response()->json([
                    'message' => 'Please create an organization first'
                ], 422);
            }
            $currencyExchangeData = CurrencyHelper::getCurrencyExchangeRates($request -> currency_id, $request -> document_date);
            if ($currencyExchangeData['status'] == false) {
                return response()->json([
                    'message' => $currencyExchangeData['message']
                ], 422); 
            }

            $itemTaxIds = [];
            $itemAttributeIds = [];
            if (!$request -> sale_order_id)
            {
                $numberPatternData = Helper::generateDocumentNumberNew($request -> book_id, $request -> document_date);
                if (!isset($numberPatternData)) {
                    return response()->json([
                        'message' => "Invalid Book",
                        'error' => "",
                    ], 422);
                }
                $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : $request -> document_no;
                $regeneratedDocExist = ErpSaleOrder::withDefaultGroupCompanyOrg() -> where('book_id',$request->book_id)
                    ->where('document_number',$document_number)->first();
                    //Again check regenerated doc no
                    if (isset($regeneratedDocExist)) {
                        return response()->json([
                            'message' => ConstantHelper::DUPLICATE_DOCUMENT_NUMBER,
                            'error' => "",
                        ], 422);
                    }
            }
            $saleOrder = null;
            if ($request -> sale_order_id) { //Update
                $saleOrder = ErpSaleOrder::find($request -> sale_order_id);
                $saleOrder -> document_date = $request -> document_date;
                $saleOrder -> reference_number = $request -> reference_no;
                $saleOrder -> consignee_name = $request -> consignee_name;
                $saleOrder -> remarks = $request -> final_remarks;
                $actionType = $request -> action_type ?? '';
                //Amend backup
                if($saleOrder -> document_status == ConstantHelper::APPROVED && $actionType == 'amendment')
                {
                    $revisionData = [
                        ['model_type' => 'header', 'model_name' => 'ErpSaleOrder', 'relation_column' => ''],
                        ['model_type' => 'detail', 'model_name' => 'ErpSoItem', 'relation_column' => 'sale_order_id'],
                        ['model_type' => 'sub_detail', 'model_name' => 'ErpSoItemAttribute', 'relation_column' => 'so_item_id'],
                        ['model_type' => 'sub_detail', 'model_name' => 'ErpSoItemDelivery', 'relation_column' => 'so_item_id'],
                        ['model_type' => 'sub_detail', 'model_name' => 'ErpSaleOrderTed', 'relation_column' => 'so_item_id']
                    ];

                    $a = Helper::documentAmendment($revisionData, $saleOrder->id);

                }
                $keys = ['deletedItemDiscTedIds', 'deletedHeaderDiscTedIds', 'deletedHeaderExpTedIds', 'deletedSoItemIds', 'deletedDelivery', 'deletedAttachmentIds'];
                $deletedData = [];

                foreach ($keys as $key) {
                    $deletedData[$key] = json_decode($request->input($key, '[]'), true);
                }

                if (count($deletedData['deletedHeaderExpTedIds'])) {
                    ErpSaleOrderTed::whereIn('id',$deletedData['deletedHeaderExpTedIds'])->delete();
                }

                if (count($deletedData['deletedHeaderDiscTedIds'])) {
                    ErpSaleOrderTed::whereIn('id',$deletedData['deletedHeaderDiscTedIds'])->delete();
                }

                if (count($deletedData['deletedItemDiscTedIds'])) {
                    ErpSaleOrderTed::whereIn('id',$deletedData['deletedItemDiscTedIds'])->delete();
                }

                if (count($deletedData['deletedDelivery'])) {
                    ErpSoItemDelivery::whereIn('id',$deletedData['deletedDelivery'])->delete();
                }

                if (count($deletedData['deletedAttachmentIds'])) {
                    $files = ErpSoMedia::whereIn('id',$deletedData['deletedAttachmentIds'])->get();
                    foreach ($files as $singleMedia) {
                        $filePath = $singleMedia -> file_name;
                        if (Storage::exists($filePath)) {
                            Storage::delete($filePath);
                        }
                        $singleMedia -> delete();
                    }
                }

                if (count($deletedData['deletedSoItemIds'])) {
                    $soItems = ErpSoItem::whereIn('id',$deletedData['deletedSoItemIds'])->get();
                    # all ted remove item level
                    foreach($soItems as $soItem) {
                        //If this item is pulled from another quotation
                        if ($soItem -> sq_item_id) {
                            $qtItem = ErpSoItem::find($soItem -> sq_item_id);
                            if (isset($qtItem)) {
                                //Subtract the value utilized
                                $qtItem -> quotation_order_qty -= $soItem -> order_qty;
                                $qtItem -> save();
                            }
                        }
                        $soItem->teds()->delete();
                        #delivery remove
                        $soItem->item_deliveries()->delete();
                        # all attr remove
                        $soItem->attributes()->delete();
                        $soItem->delete();
                    }
                }

            } else { //Create
                $saleOrder = ErpSaleOrder::create([
                    'organization_id' => $organizationId,
                    'group_id' => $groupId,
                    'company_id' => $companyId,
                    'book_id' => $request -> book_id,
                    'book_code' => $request -> book_code,
                    'document_type' => $request -> type,
                    'document_number' => $document_number,
                    'doc_number_type' => $numberPatternData['type'],
                    'doc_reset_pattern' => $numberPatternData['reset_pattern'],
                    'doc_prefix' => $numberPatternData['prefix'],
                    'doc_suffix' => $numberPatternData['suffix'],
                    'doc_no' => $numberPatternData['doc_no'],
                    'document_date' => $request -> document_date,
                    'revision_number' => 0,
                    'revision_date' => null,
                    'reference_number' => $request -> reference_no,
                    'customer_id' => $request -> customer_id,
                    'customer_code' => $request -> customer_code,
                    'consignee_name' => $request -> consignee_name,
                    'billing_address' => null,
                    'shipping_address' => null,
                    'currency_id' => $request -> currency_id,
                    'currency_code' => $request -> currency_code,
                    'payment_term_id' => $request -> payment_terms_id,
                    'payment_term_code' => $request -> payment_terms_code,
                    'document_status' => ConstantHelper::DRAFT,
                    'approval_level' => 1,
                    'remarks' => $request -> final_remarks,
                    'org_currency_id' => $currencyExchangeData['data']['org_currency_id'],
                    'org_currency_code' => $currencyExchangeData['data']['org_currency_code'],
                    'org_currency_exg_rate' => $currencyExchangeData['data']['org_currency_exg_rate'],
                    'comp_currency_id' => $currencyExchangeData['data']['comp_currency_id'],
                    'comp_currency_code' => $currencyExchangeData['data']['comp_currency_code'],
                    'comp_currency_exg_rate' => $currencyExchangeData['data']['comp_currency_exg_rate'],
                    'group_currency_id' => $currencyExchangeData['data']['group_currency_id'],
                    'group_currency_code' => $currencyExchangeData['data']['group_currency_code'],
                    'group_currency_exg_rate' => $currencyExchangeData['data']['group_currency_exg_rate'],
                    'total_item_value' => 0,
                    'total_discount_value' => 0,
                    'total_tax_value' => 0,
                    'total_expense_value' => 0,
                ]);
                //Billing Address
                $customerBillingAddress = ErpAddress::find($request -> billing_address);
                if (isset($customerBillingAddress)) {
                    $billingAddress = $saleOrder -> billing_address_details() -> create([
                        'address' => $customerBillingAddress -> address,
                        'country_id' => $customerBillingAddress -> country_id,
                        'state_id' => $customerBillingAddress -> state_id,
                        'city_id' => $customerBillingAddress -> city_id,
                        'type' => 'billing',
                        'pincode' => $customerBillingAddress -> pincode,
                        'phone' => $customerBillingAddress -> phone,
                        'fax_number' => $customerBillingAddress -> fax_number
                    ]);
                }
                // Shipping Address
                $customerShippingAddress = ErpAddress::find($request -> shipping_address);
                if (isset($customerShippingAddress)) {
                    $shippingAddress = $saleOrder -> shipping_address_details() -> create([
                        'address' => $customerShippingAddress -> address,
                        'country_id' => $customerShippingAddress -> country_id,
                        'state_id' => $customerShippingAddress -> state_id,
                        'city_id' => $customerShippingAddress -> city_id,
                        'type' => 'shipping',
                        'pincode' => $customerShippingAddress -> pincode,
                        'phone' => $customerShippingAddress -> phone,
                        'fax_number' => $customerShippingAddress -> fax_number
                    ]);
                }
            }
                //Get Header Discount
                $totalHeaderDiscount = 0;
                $totalHeaderDiscountArray = [];
                if (isset($request -> order_discount_value) && count($request -> order_discount_value) > 0)
                foreach ($request -> order_discount_value as $orderHeaderDiscountKey => $orderDiscountValue) {
                    $totalHeaderDiscount += $orderDiscountValue;
                    array_push($totalHeaderDiscountArray, [
                        'id' => isset($request -> order_discount_master_id[$orderHeaderDiscountKey]) ? $request -> order_discount_master_id[$orderHeaderDiscountKey] : null,
                        'amount' => $orderDiscountValue
                    ]);
                }
                //Initialize item discount to 0
                $itemTotalDiscount = 0;
                $itemTotalValue = 0;
                $totalTax = 0;
                $totalItemValueAfterDiscount = 0;

                $saleOrder -> billing_address = isset($billingAddress) ? $billingAddress -> id : null;
                $saleOrder -> shipping_address = isset($shippingAddress) ? $shippingAddress -> id : null;
                $saleOrder -> save();
                //Seperate array to store each item calculation
                $itemsData = array();
                if ($request -> item_id && count($request -> item_id) > 0) {
                    //Items
                    $totalValueAfterDiscount = 0;
                    foreach ($request -> item_id as $itemKey => $itemId) {
                        $item = Item::find($itemId);
                        if (isset($item))
                        {
                            $itemValue = (isset($request -> item_qty[$itemKey]) ? $request -> item_qty[$itemKey] : 0) * (isset($request -> item_rate[$itemKey]) ? $request -> item_rate[$itemKey] : 0);
                            $itemDiscount = 0;
                            //Item Level Discount
                            if (isset($request -> item_discount_value[$itemKey]) && count($request -> item_discount_value[$itemKey]) > 0)
                            {
                                foreach ($request -> item_discount_value[$itemKey] as $itemDiscountValue) {
                                    $itemDiscount += $itemDiscountValue;
                                }
                            }
                            $itemTotalValue += $itemValue;
                            $itemTotalDiscount += $itemDiscount;
                            $itemValueAfterDiscount = $itemValue - $itemDiscount;
                            $totalValueAfterDiscount += $itemValueAfterDiscount;
                            $totalItemValueAfterDiscount += $itemValueAfterDiscount;
                            //Check if discount exceeds item value
                            if ($totalItemValueAfterDiscount < 0) {
                                return response() -> json([
                                    'message' => '',
                                    'errors' => array(
                                        'item_name.' . $itemKey => "Discount more than value"
                                    )
                                ], 422);
                            }
                            $inventoryUomQty = isset($request -> item_qty[$itemKey]) ? $request -> item_qty[$itemKey] : 0;
                            $requestUomId = isset($request -> uom_id[$itemKey]) ? $request -> uom_id[$itemKey] : null;
                            if($requestUomId != $item->uom_id) {
                                $alUom = $item->alternateUOMs()->where('uom_id',$requestUomId)->first();
                                if($alUom) {
                                    $inventoryUomQty= intval(isset($request -> item_qty[$itemKey]) ? $request -> item_qty[$itemKey] : 0) * $alUom->conversion_to_inventory;
                                }
                            }
                            array_push($itemsData, [
                                'sale_order_id' => $saleOrder -> id,
                                'item_id' => $item -> id,
                                'item_code' => $item -> item_code,
                                'item_name' => $item -> item_name,
                                'hsn_id' => $item -> hsn_id,
                                'hsn_code' => $item -> hsn ?-> code,
                                'uom_id' => isset($request -> uom_id[$itemKey]) ? $request -> uom_id[$itemKey] : null, //Need to change
                                'uom_code' => isset($request -> item_uom_code[$itemKey]) ? $request -> item_uom_code[$itemKey] : null,
                                'order_qty' => isset($request -> item_qty[$itemKey]) ? $request -> item_qty[$itemKey] : 0,
                                'invoice_qty' => 0,
                                'inventory_uom_id' => $item -> uom ?-> id,
                                'inventory_uom_code' => $item -> uom ?-> name,
                                'inventory_uom_qty' => $inventoryUomQty,
                                'rate' => isset($request -> item_rate[$itemKey]) ? $request -> item_rate[$itemKey] : 0,
                                'item_discount_amount' => $itemDiscount,
                                'header_discount_amount' => 0,
                                'item_expense_amount' => 0, //Need to change
                                'header_expense_amount' => 0, //Need to change
                                'tax_amount' => 0,
                                'company_currency_id' => null,
                                'company_currency_exchange_rate' => null,
                                'group_currency_id' => null,
                                'group_currency_exchange_rate' => null,
                                'remarks' => isset($request -> item_remarks[$itemKey]) ? $request -> item_remarks[$itemKey] : null,
                                'value_after_discount' => $itemValueAfterDiscount,
                                'item_value' => $itemValue
                            ]);
                        }
                    }
                    foreach ($itemsData as $itemDataKey => $itemDataValue) {
                        //Discount
                        $headerDiscount = 0;
                        $headerDiscount = ($itemDataValue['value_after_discount'] / $totalValueAfterDiscount) * $totalHeaderDiscount;
                        $itemHeaderDiscountArray = $totalHeaderDiscountArray;
                        foreach ($itemHeaderDiscountArray as &$itemDiscountHeader) {
                            $itemDiscountHeader['amount'] = $itemDiscountHeader['amount'] / $totalHeaderDiscount * $headerDiscount;
                        }
                        $valueAfterHeaderDiscount = $itemDataValue['value_after_discount'] - $headerDiscount;
                        //Expense
                        $itemExpenseAmount = 0;
                        $itemHeaderExpenseAmount = 0;
                        //Tax
                        $itemTax = 0;
                        $itemPrice = ($itemDataValue['item_value'] + $headerDiscount + $itemDataValue['item_discount_amount']) / $itemDataValue['order_qty'];
                        $partyCountryId = isset($shippingAddress) ? $shippingAddress -> country_id : null;
                        $partyStateId = isset($shippingAddress) ? $shippingAddress -> state_id : null;
                        $taxDetails = SaleModuleHelper::checkTaxApplicability($request -> customer_id, $request -> book_id) ? TaxHelper::calculateTax($itemDataValue['hsn_id'], $itemPrice, $companyCountryId, $companyStateId, $partyCountryId ?? $request -> shipping_country_id, $partyStateId ?? $request -> shipping_state_id, 'sale') : [];
                        if (isset($taxDetails) && count($taxDetails) > 0) {
                            foreach ($taxDetails as $taxDetail) {
                                $itemTax += ((double)$taxDetail['tax_percentage'] / 100 * $valueAfterHeaderDiscount);
                            }
                        }
                        $totalTax += $itemTax;
                        //Check if update or create
                        $itemRowData = [
                            'sale_order_id' => $saleOrder -> id,
                            'item_id' => $itemDataValue['item_id'],
                            'item_code' => $itemDataValue['item_code'],
                            'item_name' => $itemDataValue['item_name'],
                            'hsn_id' => $itemDataValue['hsn_id'],
                            'hsn_code' => $itemDataValue['hsn_code'],
                            'uom_id' => $itemDataValue['uom_id'], //Need to change
                            'uom_code' => $itemDataValue['uom_code'],
                            'order_qty' => $itemDataValue['order_qty'],
                            'invoice_qty' => $itemDataValue['invoice_qty'],
                            'inventory_uom_id' => $itemDataValue['inventory_uom_id'],
                            'inventory_uom_code' => $itemDataValue['inventory_uom_code'],
                            'inventory_uom_qty' => $itemDataValue['inventory_uom_qty'],
                            'rate' => $itemDataValue['rate'],
                            'item_discount_amount' => $itemDataValue['item_discount_amount'],
                            // 'header_discounts' => $itemHeaderDiscountArray,
                            'header_discount_amount' => $headerDiscount,
                            'item_expense_amount' => $itemExpenseAmount, //Need to change
                            'header_expense_amount' => $itemHeaderExpenseAmount, //Need to change
                            'tax_amount' => $itemTax,
                            'total_item_amount' => ($itemDataValue['order_qty'] * $itemDataValue['rate']) - ($itemDataValue['item_discount_amount'] + $headerDiscount) + ($itemExpenseAmount + $itemHeaderExpenseAmount) + $itemTax,
                            'company_currency_id' => null,
                            'company_currency_exchange_rate' => null,
                            'group_currency_id' => null,
                            'group_currency_exchange_rate' => null,
                            'remarks' => $itemDataValue['remarks'],
                        ];
                        if (isset($request -> so_item_id[$itemDataKey])) {
                            $oldSoItem = ErpSoItem::find($request -> so_item_id[$itemDataKey]);
                            $soItem = ErpSoItem::updateOrCreate(['id' => $request -> so_item_id[$itemDataKey]], $itemRowData);
                        } else {
                            $soItem = ErpSoItem::create($itemRowData);
                        }
                        //Quotation 
                        if ($request -> quotation_item_ids && isset($request -> quotation_item_ids[$itemDataKey])) {
                            $qtItem = ErpSoItem::find($request -> quotation_item_ids[$itemDataKey]);
                            if (isset($qtItem)) {
                                $qtItem -> quotation_order_qty = ($qtItem -> quotation_order_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                $qtItem -> save();
                                $soItem -> order_quotation_id = $qtItem -> header ?-> id;
                                $soItem -> sq_item_id = $qtItem -> id;
                                $soItem -> save();
                            }
                        }
                        //TED Data (DISCOUNT)
                        if (isset($request -> item_discount_value[$itemDataKey]))
                        {
                            foreach ($request -> item_discount_value[$itemDataKey] as $itemDiscountKey => $itemDiscountTed){
                                $itemDiscountRowData = [
                                    'sale_order_id' => $saleOrder -> id,
                                    'so_item_id' => $soItem -> id,
                                    'ted_type' => 'Discount',
                                    'ted_level' => 'D',
                                    'ted_id' => isset($request -> item_discount_master_id[$itemDataKey][$itemDiscountKey]) ? $request -> item_discount_master_id[$itemDataKey][$itemDiscountKey] : null,
                                    'ted_name' => isset($request -> item_discount_name[$itemDataKey][$itemDiscountKey]) ? $request -> item_discount_name[$itemDataKey][$itemDiscountKey] : null,
                                    'assessment_amount' => $itemDataValue['rate'] * $itemDataValue['order_qty'],
                                    'ted_percentage' => isset($request -> item_discount_percentage[$itemDataKey][$itemDiscountKey]) ? $request -> item_discount_percentage[$itemDataKey][$itemDiscountKey] : null,
                                    'ted_amount' => $itemDiscountTed,
                                    'applicable_type' => 'Deduction',
                                ];
                                if (isset($request -> item_discount_id[$itemDataKey][$itemDiscountKey])) {
                                    $soItemTedForDiscount = ErpSaleOrderTed::updateOrCreate(['id' => $request -> item_discount_id[$itemDataKey][$itemDiscountKey]], $itemDiscountRowData);
                                } else {
                                    $soItemTedForDiscount = ErpSaleOrderTed::create($itemDiscountRowData);
                                }
                            }
                        }
                        //TED Data (TAX)
                        if (isset($taxDetails) && count($taxDetails) > 0) {
                            foreach ($taxDetails as $taxDetail) {
                                $soItemTedForDiscount = ErpSaleOrderTed::updateOrCreate(
                                    [
                                        'sale_order_id' => $saleOrder -> id,
                                        'so_item_id' => $soItem -> id,
                                        'ted_type' => 'Tax',
                                        'ted_level' => 'D',
                                        'ted_id' => $taxDetail['tax_id'],
                                    ],
                                    [
                                        'ted_group_code' => $taxDetail['tax_group'],
                                        'ted_name' => $taxDetail['tax_type'],
                                        'assessment_amount' => $valueAfterHeaderDiscount,
                                        'ted_percentage' => (double)$taxDetail['tax_percentage'],
                                        'ted_amount' => ((double)$taxDetail['tax_percentage'] / 100 * $valueAfterHeaderDiscount),
                                        'applicable_type' => 'Collection',
                                    ]
                                );
                                array_push($itemTaxIds,$soItemTedForDiscount -> id);
                            }
                        }
                        //Item Attributes
                        if (isset($request -> item_attributes[$itemDataKey])) {
                            $attributesArray = json_decode($request -> item_attributes[$itemDataKey], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($attributesArray)) {
                                foreach ($attributesArray as $attributeKey => $attribute) {
                                    $attributeVal = "";
                                    $attributeValId = null;
                                    foreach ($attribute['values_data'] as $valData) {
                                        if ($valData['selected']) {
                                            $attributeVal = $valData['value'];
                                            $attributeValId = $valData['id'];
                                            break;
                                        }
                                    }
                                    $itemAttribute = ErpSoItemAttribute::updateOrCreate(
                                        [
                                            'sale_order_id' => $saleOrder -> id,
                                            'so_item_id' => $soItem -> id,
                                            'item_attribute_id' => $attribute['id'],
                                        ],
                                        [
                                            'item_code' => $soItem -> item_code,
                                            'attribute_name' => $attribute['group_name'],
                                            'attr_name' => $attribute['attribute_group_id'],
                                            'attribute_value' => $attributeVal,
                                            'attr_value' => $attributeValId,
                                        ]
                                    );
                                    array_push($itemAttributeIds, $itemAttribute -> id);
                                }
                            } else {
                                return response() -> json([
                                    'message' => 'Item No. ' . ($itemDataKey + 1) . ' has invalid attributes',
                                    'error' => ''
                                ], 422);
                            }
                        }
                        //Item Deliveries
                        if (isset($request -> item_delivery_schedule_qty[$itemDataKey])) {
                            foreach ($request -> item_delivery_schedule_qty[$itemDataKey] as $itemDeliveryKey => $itemDeliveryQty) {
                                $itemDeliveryRowData = [
                                    'sale_order_id' => $saleOrder -> id,
                                    'so_item_id' => $soItem -> id,
                                    'ledger_id' => null,
                                    'qty' => $itemDeliveryQty,
                                    'invoice_qty' => 0,
                                    'delivery_date' => isset($request -> item_delivery_schedule_date[$itemDataKey][$itemDeliveryKey]) ? ($request -> item_delivery_schedule_date[$itemDataKey][$itemDeliveryKey]) : Carbon::now() -> format('Y-m-d'),
                                ];
                                if (isset($request -> item_delivery_schedule_id[$itemDataKey][$itemDeliveryKey])) {
                                    ErpSoItemDelivery::updateOrCreate(['id' => $request -> item_delivery_schedule_id[$itemDataKey][$itemDeliveryKey]], $itemDeliveryRowData);
                                } else {
                                    ErpSoItemDelivery::create($itemDeliveryRowData);
                                }
                            }
                        }
                        ErpSaleOrderTed::where([
                            'sale_order_id' => $saleOrder -> id,
                            'so_item_id' => $soItem -> id,
                            'ted_type' => 'Tax',
                            'ted_level' => 'D',
                        ]) -> whereNotIn('id', $itemTaxIds) -> delete();
                        ErpSoItemAttribute::where([
                            'sale_order_id' => $saleOrder -> id,
                            'so_item_id' => $soItem -> id,
                        ]) -> whereNotIn('id', $itemAttributeIds) -> delete();
                        
                    }
                } else {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Please select Items',
                        'error' => "",
                    ], 422);
                }
                //Header TED (Discount)
                if (isset($request -> order_discount_value) && count($request -> order_discount_value) > 0) {
                    foreach ($request -> order_discount_value as $orderDiscountKey => $orderDiscountVal) {
                        $headerDiscountRowData = [
                            'sale_order_id' => $saleOrder -> id,
                            'so_item_id' => null,
                            'ted_type' => 'Discount',
                            'ted_level' => 'H',
                            'ted_id' => isset($request -> order_discount_master_id[$orderDiscountKey]) ? $request -> order_discount_master_id[$orderDiscountKey] : null,
                            'ted_name' => isset($request -> order_discount_name[$orderDiscountKey]) ? $request -> order_discount_name[$orderDiscountKey] : null,
                            'assessment_amount' => $totalItemValueAfterDiscount,
                            'ted_percentage' => isset($request -> order_discount_percentage[$orderDiscountKey]) ? ($request -> order_discount_percentage[$orderDiscountKey]) : null,
                            'ted_amount' => $orderDiscountVal,
                            'applicable_type' => 'Deduction',
                        ];
                        if (isset($request -> order_discount_id[$orderDiscountKey])) {
                            ErpSaleOrderTed::updateOrCreate(['id' => $request -> order_discount_id[$orderDiscountKey]], $headerDiscountRowData);
                        } else {
                            ErpSaleOrderTed::create($headerDiscountRowData);
                        }
                    }
                }
                //Header TED (Expense)
                $totalValueAfterTax = $totalItemValueAfterDiscount + $totalTax;
                $totalExpenseAmount = 0;
                if (isset($request -> order_expense_value) && count($request -> order_expense_value) > 0) {
                    foreach ($request -> order_expense_value as $orderExpenseKey => $orderExpenseVal) {
                        $headerExpenseRowData = [
                            'sale_order_id' => $saleOrder -> id,
                            'so_item_id' => null,
                            'ted_type' => 'Expense',
                            'ted_level' => 'H',
                            'ted_id' => isset($request -> order_expense_master_id[$orderExpenseKey]) ? $request -> order_expense_master_id[$orderExpenseKey] : null,
                            'ted_name' => isset($request -> order_expense_name[$orderExpenseKey]) ? $request -> order_expense_name[$orderExpenseKey] : null,
                            'assessment_amount' => $totalItemValueAfterDiscount,
                            'ted_percentage' => isset($request -> order_expense_percentage[$orderExpenseKey]) ? $request -> order_expense_percentage[$orderExpenseKey] : null, // Need to change
                            'ted_amount' => $orderExpenseVal,
                            'applicable_type' => 'Collection',
                        ];
                        if (isset($request -> order_expense_id[$orderExpenseKey])) {
                            ErpSaleOrderTed::updateOrCreate(['id' => $request -> order_expense_id[$orderExpenseKey]], $headerExpenseRowData);
                        } else {
                            ErpSaleOrderTed::create($headerExpenseRowData);
                        }
                        $totalExpenseAmount += $orderExpenseVal;
                    }
                }

                //Check all total values
                if ($itemTotalValue - ($totalHeaderDiscount + $itemTotalDiscount) < 0)
                {
                    DB::rollBack();
                    return response() -> json([
                        'status' => 'error',
                        'message' => 'Document Value cannot be less than 0'
                    ], 422);
                }
                
                $saleOrder -> total_discount_value = $totalHeaderDiscount + $itemTotalDiscount;
                $saleOrder -> total_item_value = $itemTotalValue;
                $saleOrder -> total_tax_value = $totalTax;
                $saleOrder -> total_expense_value = $totalExpenseAmount;
                $saleOrder -> total_amount = ($itemTotalValue - ($totalHeaderDiscount + $itemTotalDiscount)) + $totalTax + $totalExpenseAmount;
                //Approval check

                if ($request -> sale_order_id) { //Update condition
                    $bookId = $saleOrder->book_id; 
                    $docId = $saleOrder->id;
                    $amendRemarks = $request->amend_remarks ?? null;
                    $remarks = $saleOrder->remarks;
                    $amendAttachments = $request->file('amend_attachments');
                    $attachments = $request->file('attachment');
                    $currentLevel = $saleOrder->approval_level;
                    $modelName = get_class($saleOrder);
                    $actionType = $request -> action_type ?? "";
                    if($saleOrder -> document_status == ConstantHelper::APPROVED || $saleOrder -> document_status == ConstantHelper::APPROVAL_NOT_REQUIRED && $actionType == 'amendment')
                    {
                        //*amendmemnt document log*/
                        $revisionNumber = $saleOrder->revision_number + 1;
                        $actionType = 'amendment';
                        $totalValue = $saleOrder->grand_total_amount ?? 0;
                        $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $amendRemarks, $amendAttachments, $currentLevel, $actionType, $totalValue, $modelName);
                        $saleOrder->revision_number = $revisionNumber;
                        $saleOrder->approval_level = 1;
                        $saleOrder->revision_date = now();
                        $amendAfterStatus = $approveDocument['approvalStatus'] ?? $saleOrder -> document_status;
                        // $checkAmendment = Helper::checkAfterAmendApprovalRequired($request->book_id);
                        // if(isset($checkAmendment->approval_required) && $checkAmendment->approval_required) {
                        //     $totalValue = $saleOrder->grand_total_amount ?? 0;
                        //     $amendAfterStatus = Helper::checkApprovalRequired($request->book_id,$totalValue);
                        // } else {
                        //     $actionType = 'approve';
                        //     $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
                        // }
                        // if ($amendAfterStatus == ConstantHelper::SUBMITTED) {
                        //     $actionType = 'submit';
                        //     $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
                        // }
                        $saleOrder->document_status = $amendAfterStatus;
                        $saleOrder->save();

                    } else {
                        if ($request->document_status == ConstantHelper::SUBMITTED) {
                            $revisionNumber = $saleOrder->revision_number ?? 0;
                            $actionType = 'submit';
                            $totalValue = $saleOrder->grand_total_amount ?? 0;
                            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $totalValue, $modelName);
                            if ($approveDocument['message']) {
                                DB::rollBack();
                                return response()->json([
                                    'message' => $approveDocument['message'],
                                    'error' => "",
                                ], 422);
                            }

                            $document_status = $approveDocument['approvalStatus'] ?? $saleOrder -> document_status;
                            $saleOrder->document_status = $document_status;
                        } else {
                            $saleOrder->document_status = $request->document_status ?? ConstantHelper::DRAFT;
                        }
                    }
                } else { //Create condition
                    if ($request->document_status == ConstantHelper::SUBMITTED) {
                        $bookId = $saleOrder->book_id;
                        $docId = $saleOrder->id;
                        $remarks = $saleOrder->remarks;
                        $attachments = $request->file('attachment');
                        $currentLevel = $saleOrder->approval_level;
                        $revisionNumber = $saleOrder->revision_number ?? 0;
                        $actionType = 'submit'; // Approve // reject // submit
                        $modelName = get_class($saleOrder);
                        $totalValue = $saleInvoice->total_amount ?? 0;
                        $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $totalValue, $modelName);
                        $saleOrder->document_status = $approveDocument['approvalStatus'] ?? $saleOrder->document_status;

                    }

                    // if ($request->document_status == 'submitted') {
                    //     $totalValue = $saleOrder->total_amount ?? 0;
                    //     $document_status = Helper::checkApprovalRequired($request->book_id,$totalValue);
                    //     $saleOrder->document_status = $document_status;
                    // } else {
                    //     $saleOrder->document_status = $request->document_status ?? ConstantHelper::DRAFT;
                    // }
                    $saleOrder -> save();
                }

                // $bookId = $po->book_id; 
                // $docId = $po->id;
                // $amendRemarks = $request->amend_remarks ?? null;
                // $remarks = $po->remarks;
                // $amendAttachments = $request->file('amend_attachment');
                // $attachments = $request->file('attachment');
                // $currentLevel = $po->approval_level;
                // $modelName = get_class($po);
                // if ($request->document_status == 'submitted') {
                //     $revisionNumber = $saleOrder->revision_number ?? 0;
                //     $actionType = 'submit'; // Approve // reject // submit
                //     $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType);
                //     $totalValue = $saleOrder -> total_amount;

                //     $document_status = Helper::checkApprovalRequired($request->book_id);
                //     $saleOrder->document_status = $document_status;
                // } else {
                //     $saleOrder->document_status = $request->document_status ?? ConstantHelper::DRAFT;
                // }
                $saleOrder -> save();
                //Media
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $singleFile) {
                        $mediaFiles = $saleOrder->uploadDocuments($singleFile, 'sale_order', false);
                    }
                }
                DB::commit();
                return response() -> json([
                    'message' => ($request -> type == "sq" ? "Sale Quotation" : "Sale Order") . " created successfully",
                    'redirect_url' => route('sale.order.index', ['type' => $request -> type])
                ]);

            
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error occurred while creating the record.',
                'error' => 'Server Error',
                'exception' => $ex -> getMessage()
            ], 500); 
        }
    }

    public function amendmentSubmit(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $saleOrder = ErpSaleOrder::where('id', $id)->first();
            if (!$saleOrder) {
                return response()->json(['data' => [], 'message' => "Sale Order not found.", 'status' => 404]);
            }

            $revisionData = [
                ['model_type' => 'header', 'model_name' => 'ErpSaleOrder', 'relation_column' => ''],
                ['model_type' => 'detail', 'model_name' => 'ErpSoItem', 'relation_column' => 'sale_order_id'],
                ['model_type' => 'sub_detail', 'model_name' => 'ErpSoItemAttribute', 'relation_column' => 'so_item_id'],
                ['model_type' => 'sub_detail', 'model_name' => 'ErpSoItemDelivery', 'relation_column' => 'so_item_id'],
                ['model_type' => 'sub_detail', 'model_name' => 'ErpSaleOrderTed', 'relation_column' => 'so_item_id']
            ];

            $a = Helper::documentAmendment($revisionData, $id);
            if ($a) {
                //*amendmemnt document log*/
                $bookId = $saleOrder->book_id;
                $docId = $saleOrder->id;
                $remarks = 'Amendment';
                $attachments = $request->file('attachment');
                $currentLevel = $saleOrder->approval_level;
                $revisionNumber = $saleOrder->revision_number;
                $actionType = 'amendment'; // Approve // reject // submit // amend
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber, $remarks, $attachments, $currentLevel, $actionType);


                $saleOrder->document_status = ConstantHelper::DRAFT;
                $saleOrder->revision_number = $saleOrder->revision_number + 1;
                $saleOrder->approval_level = 1;
                $saleOrder->revision_date = now();
                $saleOrder->save();
            }

            DB::commit();
            return response()->json(['data' => [], 'message' => "Amendment done!", 'status' => 200]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Amendment Submit Error: ' . $e->getMessage());
            return response()->json(['data' => [], 'message' => "An unexpected error occurred. Please try again.", 'error' => $e->getMessage(), 'status' => 500]);
        }
    }

    public function getCustomerAddresses(Request $request, string $customerId)
    {
        try {
            $customer = Customer::find($customerId);
            $billingAddresses = ErpAddress::where('addressable_id', $customerId)->where('addressable_type', Customer::class)->whereIn('type', ['billing', 'both'])->get();
            $shippingAddresses = ErpAddress::where('addressable_id', $customerId)->where('addressable_type', Customer::class)->whereIn('type', ['shipping', 'both'])->get();
            foreach ($billingAddresses as $billingAddress) {
                $billingAddress->value = $billingAddress->id;
                $billingAddress->label = $billingAddress->display_address;
            }
            foreach ($shippingAddresses as $shippingAddress) {
                $shippingAddress->value = $shippingAddress->id;
                $shippingAddress->label = $shippingAddress->display_address;
            }
            if (count($shippingAddresses) == 0) {
                return response()->json([
                    'data' => array(
                        'error_message' => 'Shipping Address not found for ' . $customer?->company_name
                    )
                ]);
            }
            if (count($billingAddresses) == 0) {
                return response()->json([
                    'data' => array(
                        'error_message' => 'Billing Address not found for ' . $customer?->company_name
                    )
                ]);
            }
            if (!isset($customer?->currency_id)) {
                return response()->json([
                    'data' => array(
                        'error_message' => 'Currency not found for ' . $customer?->company_name
                    )
                ]);
            }
            if (!isset($customer?->payment_terms_id)) {
                return response()->json([
                    'data' => array(
                        'error_message' => 'Payment Terms not found for ' . $customer?->company_name
                    )
                ]);
            }
            //Currency Helper
            $currencyData = CurrencyHelper::getCurrencyExchangeRates($customer?->currency_id ?? 0, $request->document_date ?? '');
            return response()->json([
                'data' => array(
                    'billing_addresses' => $billingAddresses,
                    'shipping_addresses' => $shippingAddresses,
                    'currency_exchange' => $currencyData
                )
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function getItemAttributes(string $itemId)
    {
        try {
            $itemAttributes = ErpItemAttribute::where('item_id', $itemId)->get();
            $item = Item::find($itemId);
            foreach ($itemAttributes as $attribute) {
                $attributesArray = array();
                $attribute_ids = [];
                if ($attribute->all_checked) {
                    $attribute_ids = ErpAttribute::where('attribute_group_id', $attribute->attribute_group_id)->get()->pluck('id')->toArray();
                } else {
                    $attribute_ids = json_decode($attribute->attribute_id);
                }
                $attribute->group_name = $attribute->group?->name;
                foreach ($attribute_ids as $attributeValue) {
                    $attributeValueData = ErpAttribute::where('id', $attributeValue)->select('id', 'value')->where('status', 'active')->first();
                    if (isset($attributeValueData)) {
                        $attributeValueData->selected = false;
                        array_push($attributesArray, $attributeValueData);
                    }
                }
                $attribute->values_data = $attributesArray;
                $attribute->only(['id', 'group_name', 'values_data']);
            }
            return response()->json([
                'data' => array(
                    'attributes' => $itemAttributes,
                    'item' => $item,
                    'item_hsn' => $item->hsn?->code
                )
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function getCustomerAddress(Request $request, string $id)
    {
        try {
            $address = ErpAddress::find($id);
            return response()->json([
                'address' => $address
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function getItemDetails(Request $request)
    {
        try {
            $item = Item::with(['alternateUoms.uom', 'category', 'subCategory'])->find($request->item_id);
            if ($request->is_edit && $request->header_id && $request->detail_id) {
                $totalStockData = InventoryHelper::checkIssueStockQuantity($request->header_id, $request->detail_id, $request->item_id, $request->selectedAttr ?? [], $request->quantity ?? 0, $request->store_id ?? null, $request->rack_id ?? null, $request->shelf_id ?? null, $request->bin_id ?? null, 'invoice', 'issue');
            } else {
                $totalStockData = InventoryHelper::totalInventoryAndStock($request->item_id, $request->selectedAttr ?? [], $request->store_id ?? null, $request->rack_id ?? null, $request->shelf_id ?? null, $request->bin_id ?? null);
            }
            // $storeWiseStockData = InventoryHelper::isExistInventoryAndStock($request -> item_id, $request -> selectedAttr ?? [],  []);
            $selectedUom = $request->uom_id ?? null;
            if (isset($item)) {
                $inventoryUomQty = $request->quantity ?? 0;
                $requestUomId = $selectedUom;
                if ($requestUomId != $item->uom_id) {
                    $alUom = $item->alternateUOMs()->where('uom_id', $requestUomId)->first();
                    if ($alUom) {
                        $inventoryUomQty = intval(isset($request->quantity) ? $request->quantity : 0) * $alUom->conversion_to_inventory;
                    }
                }
            }
            return response()->json([
                'message' => 'Item details found',
                'item' => $item,
                'inv_qty' => $item->type === ConstantHelper::SERVICE ? 0 : $inventoryUomQty ?? 0,
                'inv_uom' => $item->type === ConstantHelper::SERVICE ? null : $item->uom?->alias,
                'stocks' => $totalStockData,
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Some internal error occured',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
    public function checkItemBomExists(Request $request)
    {
        $attributes = [];
        $requestAttributes = json_decode($request->item_attributes ?? [], true);
        foreach ($requestAttributes as $attribute) {
            $selectedAttributeValue = null;
            foreach ($attribute['values_data'] as $valData) {
                if ($valData['selected'] == 'true') {
                    $selectedAttributeValue = $valData['id'];
                    break;
                }
            }
            array_push($attributes, [
                'attribute_id' => $attribute['id'],
                'attribute_value' => $selectedAttributeValue
            ]);
        }
        $itemDetails = ItemHelper::checkItemBomExists($request->item_id, $attributes);
        return array(
            'data' => $itemDetails
        );

    }
    public function getItemStoreData(Request $request)
    {
        try {
            $baseUomQty = ItemHelper::convertToBaseUom($request -> item_id, $request -> uom_id, $request -> quantity);
            $storeWiseStockData = InventoryHelper::fetchStockSummary($request -> item_id, $request -> selectedAttr ?? [],$request -> quantity ?? 0, $request -> store_id ?? null, $request -> rack_id ?? null, $request -> shelf_id ?? null, $request -> bin_id ?? null);
            // $fifoBreakup[] = [
            //     'item_id' => $request -> item_id,
            //     'item_name' => null,
            //     'item_code' => null,
            //     'store_id' => 1,
            //     'store' => "STORE-1",
            //     'rack_id' => null,
            //     'rack' => null,
            //     'shelf_id' => null,
            //     'shelf' => null,
            //     'bin_id' => null,
            //     'bin' => null,
            //     'allocated_quantity' => $baseUomQty,
            // ];
            foreach ($storeWiseStockData['records'] as &$breakup) {
                $breakup['alt_uom_qty'] = ItemHelper::convertToAltUom($request -> item_id, $request -> uom_id, $breakup['allocated_quantity']);
            }
            return response() -> json([
                'message' => 'Item details found',
                'stores' => [
                    'code' => 200,
                    'message' => '',
                    'status' => 'success',
                    'records' => $storeWiseStockData['records']
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Some internal error occured',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function processQuotation(Request $request)
    {
        try {
            $quotation = ErpSaleOrder::with(['discount_ted', 'expense_ted', 'billing_address_details', 'shipping_address_details'])->with('customer', function ($sQuery) {
                $sQuery->with(['currency', 'payment_terms']);
            })->whereHas('items', function ($subQuery) use ($request) {
                $subQuery->whereIn('id', $request->items_id);
            })->with('items', function ($itemQuery) use ($request) {
                $itemQuery->whereIn('id', $request->items_id)->with(['discount_ted', 'tax_ted', 'item_deliveries'])->with([
                    'item' => function ($itemQuery) {
                        $itemQuery->with(['specifications', 'alternateUoms.uom', 'uom', 'hsn']);
                    }
                ]);
            })->where('document_type', ConstantHelper::SQ_SERVICE_ALIAS)->whereIn('id', $request->quotation_id)->get();
            foreach ($quotation as $header) {
                foreach ($header->items as &$orderItem) {
                    $orderItem->item_attributes_array = $orderItem->item_attributes_array();
                }
            }
            return response()->json([
                'message' => 'Data found',
                'data' => $quotation
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Some internal error occured',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function getQuotations(Request $request)
    {
        try {
            $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request->header_book_id);
            $quotation = ErpSoItem::whereHas('header', function ($subQuery) use ($request, $applicableBookIds) {
                $subQuery->where('document_type', ConstantHelper::SQ_SERVICE_ALIAS)->whereIn('document_status', [ConstantHelper::APPROVED, ConstantHelper::APPROVAL_NOT_REQUIRED])->whereIn('book_id', $applicableBookIds)->when($request->customer_id, function ($custQuery) use ($request) {
                    $custQuery->where('customer_id', $request->customer_id);
                })->when($request->book_id, function ($bookQuery) use ($request) {
                    $bookQuery->where('book_id', $request->book_id);
                })->when($request->document_id, function ($docQuery) use ($request) {
                    $docQuery->where('id', $request->document_id);
                });
            })-> with('attributes') -> with('uom') -> with(['header.customer']) -> whereColumn('quotation_order_qty', "<", "order_qty");

            if ($request->item_id) {
                $quotation = $quotation->where('item_id', $request->item_id);
            }

            $quotation = $quotation->get();

            return response()->json([
                'data' => $quotation
            ]);

        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Some internal error occurred',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    public function addAddress(Request $request)
    {
        try {
            $address = ErpAddress::where([
                ['addressable_id', $request->customer_id],
                ['addressable_type', Customer::class],
                ['country_id', $request->country_id],
                ['state_id', $request->state_id],
                ['city_id', $request->city_id],
                ['address', $request->address],
                ['type', $request->type],
                ['pincode', $request->pincode],
                ['phone', $request->phone],
            ])->get()->first();
            if (isset($address)) {
                return response()->json([
                    'data' => $address
                ]);
            }
            $address = ErpAddress::create([
                'addressable_id' => $request->customer_id,
                'addressable_type' => Customer::class,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'address' => $request->address,
                'type' => $request->type,
                'pincode' => $request->pincode,
                'phone' => $request->phone,
                'fax_number' => $request->fax_no ?? null,
                'is_billing' => $request->type == 'billing' ? 1 : 0,
                'is_shipping' => $request->type == 'shipping' ? 1 : 0
            ]);
            return response()->json([
                'data' => $address
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    // genrate pdf
    public function generatePdf(Request $request, $id)
    {
        // dd($id,$request);
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first();
        $organizationAddress = Address::with(['city', 'state', 'country'])
            ->where('addressable_id', $user->organization_id)
            ->where('addressable_type', Organization::class)
            ->first();

        $order = ErpSaleOrder::with(
            [
                'customer',
                'currency',
                'discount_ted',
                'expense_ted',
                'billing_address_details',
                'shipping_address_details'
            ]
        )
            ->with('items', function ($query) {
                $query->with('discount_ted', 'tax_ted', 'item_deliveries')->with([
                    'item' => function ($itemQuery) {
                        $itemQuery->with(['specifications', 'alternateUoms.uom', 'uom']);
                    }
                ]);
            })
            ->find($id);
        $type = ConstantHelper::SERVICE_LABEL[$order->document_type];

        $shippingAddress = $order->shipping_address_details;

        $totalItemValue = $order->total_item_value ?? 0.00;
        $totalDiscount = $order->total_discount_value ?? 0.00;
        $totalTaxes = $order->total_tax_value ?? 0.00;
        $totalTaxableValue = ($totalItemValue - $totalDiscount);
        $totalAfterTax = ($totalTaxableValue + $totalTaxes);
        $totalExpense = $order->total_expense_value ?? 0.00;
        $totalAmount = ($totalAfterTax + $totalExpense);
        $amountInWords = NumberHelper::convertAmountToWords($totalAmount);
        // Path to your image (ensure the file exists and is accessible)
        $imagePath = public_path('assets/css/midc-logo.jpg'); // Store the image in the public directory
        // dd($imagePath);
        $dataArray = [
            'type' => $type,
            'user' => $user,
            'order' => $order,
            'shippingAddress' => $shippingAddress,
            'organization' => $organization,
            'amountInWords' => $amountInWords,
            'organizationAddress' => $organizationAddress,
            'totalItemValue' => $totalItemValue,
            'totalDiscount' => $totalDiscount,
            'totalTaxes' => $totalTaxes,
            'totalTaxableValue' => $totalTaxableValue,
            'totalAfterTax' => $totalAfterTax,
            'totalExpense' => $totalExpense,
            'totalAmount' => $totalAmount,
            'imagePath' => $imagePath
        ];
        $pdf = PDF::loadView(
            'pdf.sales-document',
            $dataArray
        );
        if ($order->document_type == 'so') {
            return $pdf->stream('Sales-Order.pdf');
        } else {
            return $pdf->stream('Sales-Quotation.pdf');
            
        }
       
    }

    public function revokeSalesOrderOrQuotation(Request $request)
    {
        DB::beginTransaction();
        try {
            $saleDocument = ErpSaleOrder::find($request -> id);
            if (isset($saleDocument)) {
                $revoke = Helper::approveDocument($saleDocument -> book_id, $saleDocument -> id, $saleDocument -> revision_number, '', [], 0, ConstantHelper::REVOKE, $saleDocument -> total_amount, get_class($saleDocument));
                if ($revoke['message']) {
                    DB::rollBack();
                    return response() -> json([
                        'status' => 'error',
                        'message' => $revoke['message'],
                    ]);
                } else {
                    $saleDocument -> document_status = $revoke['approvalStatus'];
                    $saleDocument -> save();
                    DB::commit();
                    return response() -> json([
                        'status' => 'success',
                        'message' => 'Revoked succesfully',
                    ]);
                }
            } else {
                DB::rollBack();
                throw new ApiGenericException("No Document found");
            }
        } catch(Exception $ex) {
            DB::rollBack();
            throw new ApiGenericException($ex -> getMessage());
        }
    }
}
