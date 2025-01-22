<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiGenericException;
use App\Helpers\ConstantHelper;
use App\Helpers\CurrencyHelper;
use App\Helpers\FinancialPostingHelper;
use App\Helpers\Helper;
use App\Helpers\InventoryHelper;
use App\Helpers\NumberHelper;
use App\Helpers\SaleModuleHelper;
use App\Helpers\ServiceParametersHelper;
use App\Helpers\TaxHelper;
use App\Http\Requests\ErpSaleInvoiceRequest;
use App\Models\Country;
use App\Models\Address;
use App\Models\DiscountMaster;
use App\Models\EmployeeBookMapping;
use App\Models\ErpAddress;
use App\Models\ErpAttribute;
use App\Models\ErpInvoiceItem;
use App\Models\ErpInvoiceItemAttribute;
use App\Models\ErpInvoiceItemLocation;
use App\Models\ErpItemAttribute;
use App\Models\ErpSaleInvoice;
use App\Models\ErpSaleInvoiceHistory;
use App\Models\ErpSaleInvoiceTed;
use App\Models\ErpSaleOrder;
// use App\Models\ErpSoDnMapping;
use App\Models\ErpSoItem;
use App\Models\Item;
use App\Models\LandLease;
use App\Models\LandLeaseScheduler;
use App\Models\LandParcel;
use App\Models\NumberPattern;
use App\Models\Organization;
use App\Models\OrganizationBookParameter;
use App\Models\OrganizationMenu;
use App\Models\Unit;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;
use PDF;
use Exception;
use Illuminate\Http\Request;
use App\Validations\ErpSaleInvoice as Validator;
use Yajra\DataTables\DataTables;
use stdClass;

class ErpSaleInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $pathUrl = request()->segments()[0];
        if ($pathUrl === 'sale-invoices') {
            $orderType = SaleModuleHelper::SALES_INVOICE_DEFAULT_TYPE;
            $redirectUrl = route('sale.invoice.index');
            $createRoute = route('sale.invoice.create');
        }
        if ($pathUrl === 'lease-invoices') {
            $orderType = SaleModuleHelper::SALES_INVOICE_LEASE_TYPE;
            $redirectUrl = route('sale.leaseInvoice.index');
            $createRoute = route('sale.leaseInvoice.create');
        }
        request() -> merge(['type' => $orderType]);
        // $type = SaleModuleHelper::getAndReturnInvoiceType($request -> type ?? '');
        $typeName = SaleModuleHelper::getAndReturnInvoiceTypeName($orderType);
        if ($request -> ajax()) {
            $invoices = ErpSaleInvoice::withDefaultGroupCompanyOrg() ->  bookViewAccess($pathUrl) ->  withDraftListingLogic() -> whereIn('document_type', SaleModuleHelper::checkInvoiceDocTypesFromUrlType($orderType)) -> orderByDesc('id') -> get();
            return DataTables::of($invoices) ->addIndexColumn()
            ->editColumn('document_status', function ($row) use($orderType) {
                $statusClasss = ConstantHelper::DOCUMENT_STATUS_CSS_LIST[$row->document_status];    
                $displayStatus = $row -> display_status;
                if ($orderType == SaleModuleHelper::SALES_INVOICE_DEFAULT_TYPE) {
                    $editRoute = route('sale.invoice.edit', ['id' => $row->id]);
                }
                if ($orderType == SaleModuleHelper::SALES_INVOICE_LEASE_TYPE) {
                    $editRoute = route('sale.leaseInvoice.edit', ['id' => $row->id]);
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
            ->addColumn('document_type', function ($row) {
                return SaleModuleHelper::getServiceName($row -> book_id);
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
            // ->addColumn('action', function ($row) use($type) {
            //     return '
            //         <div class="dropdown">
            //             <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
            //                 <i data-feather="more-vertical"></i>
            //             </button>
            //             <div class="dropdown-menu dropdown-menu-end">
            //                 <a class="dropdown-item" href="' . route('sale.invoice.edit', ['id' => $row->id, 'type' => $type]) . '">
            //                     <i data-feather="edit-3" class="me-50"></i>
            //                     <span>View/ Edit Detail</span>
            //                 </a>
            //             </div>
            //         </div>';
            // })
            ->rawColumns(['document_status'])
            ->make(true);
        }
        $parentURL = request() -> segments()[0];
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        return view('salesInvoice.index', ['typeName' => $typeName, 'redirect_url' => $redirectUrl, 'create_route' => $createRoute, 'create_button' => count($servicesBooks['services'])]);
    }

    public function create(Request $request)
    {
        //Get the menu 
        $parentURL = request() -> segments()[0];
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentURL);
        if (count($servicesBooks['services']) == 0) {
            return redirect() -> route('/');
        }
        $redirectUrl = route('sale.invoice.index');
        if ($parentURL === 'sale-invoices') {
            $orderType = SaleModuleHelper::SALES_INVOICE_DEFAULT_TYPE;
            $redirectUrl = route('sale.invoice.index');
        }
        if ($parentURL === 'lease-invoices') {
            $orderType = SaleModuleHelper::SALES_INVOICE_LEASE_TYPE;
            $redirectUrl = route('sale.leaseInvoice.index');
        }
        request() -> merge(['type' => $orderType]);
        $firstService = $servicesBooks['services'][0];
        $user = Helper::getAuthenticatedUser();
        $type = SaleModuleHelper::getAndReturnInvoiceType($request -> type ?? '');
        $request -> merge(['type' => $type]);
        $typeName = 'Sales Invoice';
        if ($type == ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
            $typeName = "Delivery Note";
        } else if ($type == ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS) {
            $typeName = "Delivery Note CUM Invoice";
        } else if ($type == ConstantHelper::LEASE_INVOICE_SERVICE_ALIAS) {
            $typeName = "Lease Invoice";
        }
        $books = [];
        $countries = Country::select('id AS value', 'name AS label') -> where('status', ConstantHelper::ACTIVE) -> get();
        $data = [
            'user' => $user,
            'services' => $servicesBooks['services'],
            'selectedService'  => $firstService ?-> id ?? null,
            'series' => $books,
            'countries' => $countries,
            'type' => $type,
            'typeName' => $typeName,
            'redirect_url' => $redirectUrl
        ];
        return view('salesInvoice.create_edit', $data);
    }
    public function edit(Request $request, String $id)
    {
        try {
            $parentUrl = request() -> segments()[0];
            $redirect_url = route('sale.invoice.index');
            if ($parentUrl === 'sale-invoices') {
                $orderType = SaleModuleHelper::SALES_INVOICE_DEFAULT_TYPE;
            }
            if ($parentUrl === 'lease-invoices') {
                $orderType = SaleModuleHelper::SALES_INVOICE_LEASE_TYPE;
            }
            request() -> merge(['type' => $orderType]);
            $user = Helper::getAuthenticatedUser();
            $servicesBooks = [];
            if (isset($request -> revisionNumber))
            {
                $order = ErpSaleInvoiceHistory::with(['media_files','discount_ted', 'expense_ted', 'billing_address_details', 'shipping_address_details']) -> with('items', function ($query) {
                    $query -> with('discount_ted', 'tax_ted', 'item_locations') -> with(['item' => function ($itemQuery) {
                        $itemQuery -> with(['specifications', 'alternateUoms.uom', 'uom']);
                    }]);
                }) -> where('source_id', $id)->first();
            } else {
                $order = ErpSaleInvoice::with(['media_files','discount_ted', 'expense_ted', 'billing_address_details', 'shipping_address_details']) -> with('items', function ($query) {
                    $query -> with('discount_ted', 'tax_ted', 'item_locations') -> with(['item' => function ($itemQuery) {
                        $itemQuery -> with(['specifications', 'alternateUoms.uom', 'uom']);
                    }]);
                }) -> find($id);
            }

            if (isset($order)) {
                $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentUrl,$order -> book ?-> service ?-> alias);
                foreach ($order -> items as &$siItem) {
                    if ($siItem -> so_item_id !== null) {
                        $pulled = ErpSoItem::find($siItem -> so_item_id);
                        if (isset($pulled)) {
                            $siItem -> max_attribute = $pulled -> order_qty;
                            $siItem -> is_editable = false;
                        } else {
                            $siItem -> max_attribute = 999999;
                            $siItem -> is_editable = true;
                        }
                    } else if ($siItem -> land_lease_id || $siItem -> land_schedule_id) {
                        $pulled = LandLease::find($siItem -> land_lease_id);
                        if (isset($pulled)) {
                            $siItem -> max_attribute = 999999;
                            $siItem -> is_editable = false;
                        } else {
                            $siItem -> max_attribute = 999999;
                            $siItem -> is_editable = true;
                        }
                    }
                    else {
                        // if (count($siItem -> mapped_so_item_ids()) > 0) {
                        //     $referenceItems = ErpSoItem::whereIn('id', $siItem -> mapped_so_item_ids()) -> get();
                        //     $maxAttribute = 0;
                        //     foreach ($referenceItems as $refItem) {
                        //         $maxAttribute += $refItem -> balance_qty;
                        //     }
                        //     $maxAttribute += $siItem -> order_qty;
                        //     $siItem -> max_attribute = $maxAttribute;
                        //     $siItem -> is_editable = false;
                        // } else {
                            $siItem -> max_attribute = 999999;
                            $siItem -> is_editable = true;
                        // }
                    }
                }
            }
            
                $revision_number = $order->revision_number;
                $totalValue = ($order -> total_item_value - $order -> total_discount_value) + $order -> total_tax_value + $order -> total_expense_value;
                $userType = Helper::userCheck();
                $buttons = Helper::actionButtonDisplay($order->book_id,$order->document_status , $order->id, $totalValue, $order->approval_level, $order -> created_by ?? 0, $userType['type'], $revision_number);
                $type = SaleModuleHelper::getAndReturnInvoiceType($request -> type);
                $request -> merge(['type' => $type]);
                $books = Helper::getBookSeriesNew($type) -> get();
                $countries = Country::select('id AS value', 'name AS label') -> where('status', ConstantHelper::ACTIVE) -> get();
                $revNo = $order->revision_number;
                if($request->has('revisionNumber')) {
                    $revNo = intval($request->revisionNumber);
                } else {
                    $revNo = $order->revision_number;
                }
                $docValue = $order->total_amount ?? 0;
                $approvalHistory = Helper::getApprovalHistory($order->book_id, $order->id, $revNo, $docValue, $order -> created_by);
                $docStatusClass = ConstantHelper::DOCUMENT_STATUS_CSS[$order->document_status] ?? '';
                $typeName = "Sales Invoice";
                if ($type == ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
                    $typeName = "Delivery Note";
                } else if ($type == ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS) {
                    $typeName = "Delivery Note CUM Invoice";
                } else if ($type == ConstantHelper::LEASE_INVOICE_SERVICE_ALIAS) {
                    $typeName = "Lease Invoice";
                }
                $data = [
                    'user' => $user,
                    'series' => $books,
                    'order' => $order,
                    'countries' => $countries,
                    'buttons' => $buttons,
                    'approvalHistory' => $approvalHistory,
                    'type' => $type,
                    'revision_number' => $revision_number,
                    'docStatusClass' => $docStatusClass,
                    'typeName' => $typeName,
                    'maxFileCount' => isset($order -> mediaFiles) ? (10 - count($order -> media_files)) : 10,
                    'services' => $servicesBooks['services'],
                    'redirect_url' => $redirect_url
                ];
                return view('salesInvoice.create_edit', $data);
            
        } catch(Exception $ex) {
            dd($ex -> getMessage());
        }
    }

    public function store(ErpSaleInvoiceRequest $request)
    {
        try {
            //Reindex
            $request -> item_qty =  array_values($request -> item_qty);
            $request -> item_remarks =  array_values($request -> item_remarks ?? []);
            $request -> uom_id =  array_values($request -> uom_id);
            $request -> item_discount_value =  array_values($request -> item_discount_value ?? []);
            $request -> item_rate =  array_values($request -> item_rate);

            DB::beginTransaction();
            $user = Helper::getAuthenticatedUser();
            $type = SaleModuleHelper::getAndReturnInvoiceType($request -> type ?? '');
            $request -> merge(['type' => $type]);
            $invoiceRequired = false;
            $invoiceRequiredParam = OrganizationBookParameter::where('book_id', $request -> book_id) -> where('parameter_name', ServiceParametersHelper::INVOICE_TO_FOLLOW_PARAM) -> first();
            if (isset($invoiceRequiredParam) && $invoiceRequiredParam -> parameter_value[0] == 'yes') {
                $invoiceRequired = true;
            }
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
            if (!$request -> sale_invoice_id)
            {
                $numberPatternData = Helper::generateDocumentNumberNew($request -> book_id, $request -> document_date);
                if (!isset($numberPatternData)) {
                    return response()->json([
                        'message' => "Invalid Book",
                        'error' => "",
                    ], 422);
                }
                $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : $request -> document_no;
                $regeneratedDocExist = ErpSaleInvoice::withDefaultGroupCompanyOrg() -> where('book_id',$request->book_id)
                    ->where('document_number',$document_number)->first();
                    //Again check regenerated doc no
                    if (isset($regeneratedDocExist)) {
                        return response()->json([
                            'message' => ConstantHelper::DUPLICATE_DOCUMENT_NUMBER,
                            'error' => "",
                        ], 422);
                    }
            }
            $saleInvoice = null;
            if ($request -> sale_invoice_id) { //Update
                $saleInvoice = ErpSaleInvoice::find($request -> sale_invoice_id);
                $saleInvoice -> document_date = $request -> document_date;
                $saleInvoice -> reference_number = $request -> reference_no;
                $saleInvoice -> consignee_name = $request -> consignee_name;
                $saleInvoice -> consignment_no = $request -> consignment_no;
                $saleInvoice -> vehicle_no = $request -> vehicle_no;
                $saleInvoice -> transporter_name = $request -> transporter_name;
                $saleInvoice -> eway_bill_no = $request -> eway_bill_no;
                $saleInvoice -> remarks = $request -> final_remarks;
                $actionType = $request -> action_type ?? '';
                //Amend backup
                if($saleInvoice -> document_status == ConstantHelper::APPROVED && $actionType == 'amendment')
                {
                    $revisionData = [
                        ['model_type' => 'header', 'model_name' => 'ErpSaleInvoice', 'relation_column' => ''],
                        ['model_type' => 'detail', 'model_name' => 'ErpInvoiceItem', 'relation_column' => 'sale_invoice_id'],
                        ['model_type' => 'sub_detail', 'model_name' => 'ErpInvoiceItemAttribute', 'relation_column' => 'invoice_item_id'],
                        ['model_type' => 'sub_detail', 'model_name' => 'ErpInvoiceItemLocation', 'relation_column' => 'invoice_item_id'],
                        ['model_type' => 'sub_detail', 'model_name' => 'ErpSaleInvoiceTed', 'relation_column' => 'invoice_item_id'],
                    ];
                    $a = Helper::documentAmendment($revisionData, $saleInvoice->id);

                }
                $keys = ['deletedItemDiscTedIds', 'deletedHeaderDiscTedIds', 'deletedHeaderExpTedIds', 'deletedSiItemIds', 'deletedDelivery', 'deletedAttachmentIds'];
                $deletedData = [];

                foreach ($keys as $key) {
                    $deletedData[$key] = json_decode($request->input($key, '[]'), true);
                }

                if (count($deletedData['deletedHeaderExpTedIds'])) {
                    ErpSaleInvoiceTed::whereIn('id',$deletedData['deletedHeaderExpTedIds'])->delete();
                }

                if (count($deletedData['deletedHeaderDiscTedIds'])) {
                    ErpSaleInvoiceTed::whereIn('id',$deletedData['deletedHeaderDiscTedIds'])->delete();
                }

                if (count($deletedData['deletedItemDiscTedIds'])) {
                    ErpSaleInvoiceTed::whereIn('id',$deletedData['deletedItemDiscTedIds'])->delete();
                }

                // if (count($deletedData['deletedAttachmentIds'])) {
                //     $files = ErpSoMedia::whereIn('id',$deletedData['deletedAttachmentIds'])->get();
                //     foreach ($files as $singleMedia) {
                //         $filePath = $singleMedia -> file_name;
                //         if (Storage::exists($filePath)) {
                //             Storage::delete($filePath);
                //         }
                //         $singleMedia -> delete();
                //     }
                // }

                if (count($deletedData['deletedSiItemIds'])) {
                    $siItems = ErpInvoiceItem::whereIn('id',$deletedData['deletedSiItemIds'])->get();
                    # all ted remove item level
                    foreach($siItems as $siItem) {
                        if ($siItem -> so_item_id) {
                            $soItem = ErpSoItem::find($siItem -> so_item_id);
                            if (isset($soItem)) {
                                $soItem -> dnote_qty -= $siItem -> order_qty;
                                if ($siItem -> document_type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS) {
                                    $soItem -> invoice_qty -= $siItem -> order_qty;
                                }
                                $soItem -> save(); 
                            }
                        }
                        if ($siItem -> dnote_item_id) {
                            $refSiItem = ErpInvoiceItem::find($siItem -> dnote_item_id);
                            if (isset($refSiItem)) {
                                $refSiItem -> invoice_qty -= $siItem -> order_qty;
                                $refSiItem -> save(); 
                            }
                        }
                        $siItem->teds()->delete();
                        #delivery remove
                        // $siItem->item_deliveries()->delete();
                        # all attr remove
                        $siItem->attributes()->delete();

                        // $refereceItemIds = $siItem -> mapped_so_item_ids();
                        // if (count($refereceItemIds) > 0) {
                        //     foreach ($refereceItemIds as $referenceFromId) {
                        //         $referenceItem = ErpSoItem::where('id', $referenceFromId) -> first();
                        //         $existingMapping = ErpSoDnMapping::where([
                        //             ['sale_order_id', $referenceItem -> sale_order_id],
                        //             ['so_item_id', $referenceItem -> id],
                        //             ['delivery_note_id', $saleInvoice -> id],
                        //             ['dn_item_id', $siItem -> id],
                        //         ]) -> first();
                        //         if (isset($existingMapping)) {
                        //             $referenceItem -> dnote_qty = $referenceItem -> dnote_qty - $siItem -> order_qty;
                        //             if (!$invoiceRequiredParam) {
                        //                 $referenceItem -> invoice_qty = $referenceItem -> invoice_qty - $siItem -> order_qty;
                        //             }
                        //             $referenceItem -> save();
                        //             $existingMapping -> delete();
                        //         }
                        //     }
                        // }
                        
                        $siItem->delete();
                    }
                }

                //Delete all Item references
                // foreach ($saleInvoice -> items as $item) {
                //     InventoryHelper::deleteIssueStock($saleInvoice->id, $item->id, $item->item_id, 'invoice', 'issue');
                //     if (($request -> type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS || $request -> type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS)) {
                //     }
                //     $item -> item_attributes() -> forceDelete();
                //     $item -> discount_ted() -> forceDelete();
                //     $item -> tax_ted() -> forceDelete();
                //     $item -> item_locations() -> forceDelete();
                //     $item -> forceDelete();
                // }
                //Delete all header TEDs
                // foreach ($saleInvoice -> discount_ted as $saleInvoiceDiscount) {
                //     $saleInvoiceDiscount -> forceDelete(); 
                // }
                // foreach ($saleInvoice -> expense_ted as $saleInvoiceExpense) {
                //     $saleInvoiceExpense -> forceDelete(); 
                // }
            } else { //Create
                $saleInvoice = ErpSaleInvoice::create([
                    'organization_id' => $organizationId,
                    'group_id' => $groupId,
                    'company_id' => $companyId,
                    'book_id' => $request -> book_id,
                    'invoice_required' => $invoiceRequired,
                    'book_code' => $request -> book_code,
                    'document_type' => $type,
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
                    'consignment_no' => $request -> consignment_no,
                    'vehicle_no' => $request -> vehicle_no,
                    'transporter_name' => $request -> transporter_name,
                    'eway_bill_no' => $request -> eway_bill_no,
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
                    $billingAddress = $saleInvoice -> billing_address_details() -> create([
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
                    $shippingAddress = $saleInvoice -> shipping_address_details() -> create([
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

                $saleInvoice -> billing_address = isset($billingAddress) ? $billingAddress -> id : null;
                $saleInvoice -> shipping_address = isset($shippingAddress) ? $shippingAddress -> id : null;
                $saleInvoice -> save();
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
                            $uom = Unit::find($request -> uom_id[$itemKey] ?? null);
                            array_push($itemsData, [
                                'sale_invoice_id' => $saleInvoice -> id,
                                'item_id' => $item -> id,
                                'item_code' => $item -> item_code,
                                'item_name' => $item -> item_name,
                                'hsn_id' => $item -> hsn_id,
                                'hsn_code' => $item -> hsn ?-> code,
                                'uom_id' => isset($request -> uom_id[$itemKey]) ? $request -> uom_id[$itemKey] : null, //Need to change
                                'uom_code' => isset($uom) ? $uom -> name : null,
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
                        if ($totalValueAfterDiscount > 0) {
                            $headerDiscount = ($itemDataValue['value_after_discount'] / $totalValueAfterDiscount) * $totalHeaderDiscount;
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
                        $taxDetails = TaxHelper::calculateTax($itemDataValue['hsn_id'], $itemPrice, $companyCountryId, $companyStateId, $partyCountryId ?? $request -> shipping_country_id, $partyStateId ?? $request -> shipping_state_id, 'sale');
                        if (isset($taxDetails) && count($taxDetails) > 0) {
                            foreach ($taxDetails as $taxDetail) {
                                $itemTax += ((double)$taxDetail['tax_percentage'] / 100 * $valueAfterHeaderDiscount);
                            }
                        }
                        $totalTax += $itemTax;
                        //Update or create
                        $itemRowData = [
                            'sale_invoice_id' => $saleInvoice -> id,
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
                            'header_discount_amount' => $headerDiscount,
                            'item_expense_amount' => $itemExpenseAmount, //Need to change
                            'header_expense_amount' => $itemHeaderExpenseAmount, //Need to change
                            'total_item_amount' => ($itemDataValue['order_qty'] * $itemDataValue['rate']) - ($itemDataValue['item_discount_amount'] + $headerDiscount) + ($itemExpenseAmount + $itemHeaderExpenseAmount) + $itemTax,
                            'tax_amount' => $itemTax,
                            'company_currency_id' => null,
                            'company_currency_exchange_rate' => null,
                            'group_currency_id' => null,
                            'group_currency_exchange_rate' => null,
                            'remarks' => $itemDataValue['remarks'],
                        ];
                        if (isset($request -> so_item_id[$itemDataKey])) {
                            $oldSoItem = ErpInvoiceItem::find($request -> so_item_id[$itemDataKey]);
                            $soItem = ErpInvoiceItem::updateOrCreate(['id' => $request -> so_item_id[$itemDataKey]], $itemRowData);
                        } else {
                            $soItem = ErpInvoiceItem::create($itemRowData);
                        }
                        //Order Pulling condition 
                        if (isset($request -> quotation_item_type[$itemDataKey])) {
                            $pullType = $request -> quotation_item_type[$itemDataKey];
                            if ($pullType === ConstantHelper::SO_SERVICE_ALIAS) {
                                $qtItem = ErpSoItem::find($request -> quotation_item_ids[$itemDataKey]);
                                if (isset($qtItem)) {
                                    //If Order is pulled inside DN
                                    if ($invoiceRequired) {
                                        $qtItem -> dnote_qty = ($qtItem -> dnote_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                        $soItem -> dnote_qty = ($soItem -> dnote_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                    } else {
                                        $qtItem -> dnote_qty = ($qtItem -> dnote_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                        $soItem -> dnote_qty = ($soItem -> dnote_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                        $qtItem -> invoice_qty = ($qtItem -> invoice_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                        $soItem -> invoice_qty = ($soItem -> invoice_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                    }
                                    $qtItem -> save();
                                    $soItem -> sale_order_id = $qtItem -> header ?-> id;
                                    $soItem -> so_item_id = $qtItem ?-> id;
                                    $soItem -> save();
                                }
                                // $itemQty = isset($oldSoItem) ? $soItem -> order_qty - $oldSoItem -> order_qty : $soItem -> order_qty;
                                // $referenceFromIds = json_decode($request -> reference_from[$itemDataKey]);
                                // if ($itemQty > 0) {
                                //     sort($referenceFromIds);
                                // } else {
                                //     rsort($referenceFromIds);
                                // }
                                // foreach ($referenceFromIds as $referenceFromId) {
                                //    $referenceItem = ErpSoItem::where('id', $referenceFromId) -> first();
                                //    $existingMapping = ErpSoDnMapping::where([
                                //     ['sale_order_id', $referenceItem -> sale_order_id],
                                //     ['so_item_id', $referenceItem -> id],
                                //     ['delivery_note_id', $saleInvoice -> id],
                                //     ['dn_item_id', $soItem -> id],
                                //    ]) -> first();
                                //    if ($itemQty >= 0) {
                                //     $utilizedQty = min($itemQty, $referenceItem -> balance_qty);
                                //    } else {
                                //     $utilizedQty = min(abs($itemQty), $existingMapping ?-> dn_qty) * -1;
                                //    }
                                //    if (!isset($existingMapping)){
                                //     $dnMapping = ErpSoDnMapping::create([
                                //         'sale_order_id' => $referenceItem -> sale_order_id,
                                //         'so_item_id' => $referenceItem -> id,
                                //         'delivery_note_id' => $saleInvoice -> id,
                                //         'dn_item_id' => $soItem -> id,
                                //         'dn_qty' => $utilizedQty
                                //     ]);
                                //    } else {
                                //     if ($existingMapping -> dn_qty + $utilizedQty <= 0) {
                                //         $existingMapping -> delete();
                                //     } else {
                                //         $existingMapping -> dn_qty = $existingMapping -> dn_qty + $utilizedQty;
                                //         $existingMapping -> save();
                                //     }
                                //    }
                                //    $referenceItem -> dnote_qty = $referenceItem -> dnote_qty + $utilizedQty;
                                //    if (!$invoiceRequiredParam) {
                                //     $referenceItem -> invoice_qty = $referenceItem -> invoice_qty + $utilizedQty;
                                //    }
                                //    if ($referenceItem -> order_qty < $referenceItem -> dnote_qty) {
                                //     DB::rollBack();
                                //     return response() -> json([
                                //         'message' => 'Item No. ' . ($itemDataKey + 1) . ' quantity cannot exceed ' . $referenceItem -> order_qty,
                                //         'error' => ''
                                //     ], 422);
                                //    }
                                //    $referenceItem -> save();
                                //    $itemQty -= $utilizedQty;
                                //    if ($itemQty == 0) {
                                //       break;
                                //    }
                                // }
                                // if (($itemQty) != 0) {
                                //     DB::rollBack();
                                //     return response()->json([
                                //         'message' => '',
                                //         'errors' => array(
                                //             'item_qty.' . $itemDataKey => 'Quantity more than balance'
                                //         )
                                //     ], 422);
                                // }
                            } else if ($pullType === ConstantHelper::SI_SERVICE_ALIAS || $pullType === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
                                $qtItem = ErpInvoiceItem::find($request -> quotation_item_ids[$itemDataKey]);
                                if (isset($qtItem)) {
                                    $qtItem -> invoice_qty = ($qtItem -> invoice_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                    $soItem -> invoice_qty = ($soItem -> invoice_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                    //Check if sales order exists
                                    if ($qtItem -> so_item_id) {
                                        $saleOrderItem = ErpSoItem::find($qtItem -> so_item_id);
                                        if ($saleOrderItem) {
                                            $saleOrderItem -> invoice_qty = ($saleOrderItem -> invoice_qty - (isset($oldSoItem) ? $oldSoItem -> order_qty : 0)) + $itemDataValue['order_qty'];
                                            $saleOrderItem -> save();
                                        }
                                    }
                                    $soItem -> dnote_id = $qtItem -> header ?-> id;
                                    $soItem -> dnote_item_id = $qtItem ?-> id;
                                    $qtItem -> save();
                                    $soItem -> save();
                                }
                            } else if ($pullType === ConstantHelper::LAND_LEASE) {
                                $leaseSchedule = LandLeaseScheduler::find($request -> quotation_item_ids[$itemDataKey]);
                                if (isset($leaseSchedule)) {
                                    $leaseSchedule -> invoice_amount = ($leaseSchedule -> invoice_amount - (isset($oldSoItem) ? $oldSoItem -> rate : 0)) + $itemDataValue['rate'];
                                    $leaseSchedule -> save();
                                    $soItem -> lease_schedule_id = $leaseSchedule -> id;
                                    $soItem -> land_lease_id = $leaseSchedule -> header ?-> id;
                                    $soItem -> lease_item_type = ConstantHelper::LAND_LEASE;
                                    $soItem -> save();
                                }
                                if (!($request -> quotation_item_ids[$itemDataKey]) && isset($request -> quotation_item_ids_header[$itemDataKey] )) {
                                    $landLease = LandLease::find($request -> quotation_item_ids_header[$itemDataKey]);
                                    if (isset($landLease)) {
                                        $landLease -> invoice_security_deposit = ($landLease -> invoice_security_deposit - (isset($oldSoItem) ? $oldSoItem -> rate : 0)) + $itemDataValue['rate'];
                                        $landLease -> save();
                                        $soItem -> land_lease_id = $landLease ?-> id;
                                        $soItem -> lease_item_type = "security";
                                        $soItem -> save();
                                    }
                                }
                            }
                            
                        }
                        //TED Data (DISCOUNT)
                        if (isset($request -> item_discount_value[$itemDataKey]))
                        {
                            foreach ($request -> item_discount_value[$itemDataKey] as $itemDiscountKey => $itemDiscountTed){
                                $itemDiscountRowData = [
                                    'sale_invoice_id' => $saleInvoice -> id,
                                    'invoice_item_id' => $soItem -> id,
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
                                    $soItemTedForDiscount = ErpSaleInvoiceTed::updateOrCreate(['id' => $request -> item_discount_id[$itemDataKey][$itemDiscountKey]], $itemDiscountRowData);
                                } else {
                                    $soItemTedForDiscount = ErpSaleInvoiceTed::create($itemDiscountRowData);
                                }
                                // $soItemTedForDiscount = ErpSaleInvoiceTed::create([
                                //     'sale_invoice_id' => $saleInvoice -> id,
                                //     'invoice_item_id' => $soItem -> id,
                                //     'ted_type' => 'Discount',
                                //     'ted_level' => 'D',
                                //     'ted_id' => null,
                                //     'ted_name' => isset($request -> item_discount_name[$itemDataKey][$itemDiscountKey]) ? $request -> item_discount_name[$itemDataKey][$itemDiscountKey] : null,
                                //     'assessment_amount' => $itemDataValue['rate'] * $itemDataValue['order_qty'],
                                //     'ted_percentage' => $itemDiscountTed / ($itemDataValue['rate'] * $itemDataValue['order_qty']) * 100,
                                //     'ted_amount' => $itemDiscountTed,
                                //     'applicable_type' => 'Deduction',
                                // ]);
                            }
                        }
                        //TED Data (TAX)
                        if (isset($taxDetails) && count($taxDetails) > 0) {
                            foreach ($taxDetails as $taxDetail) {
                                // $soItemTedForDiscount = ErpSaleInvoiceTed::create([
                                //     'sale_invoice_id' => $saleInvoice -> id,
                                //     'invoice_item_id' => $soItem -> id,
                                //     'ted_type' => 'Tax',
                                //     'ted_level' => 'D',
                                //     'ted_id' => $taxDetail['tax_id'],
                                //     'ted_group_code' => $taxDetail['tax_group'],
                                //     'ted_name' => $taxDetail['tax_type'],
                                //     'assessment_amount' => $valueAfterHeaderDiscount,
                                //     'ted_percentage' => (double)$taxDetail['tax_percentage'],
                                //     'ted_amount' => ((double)$taxDetail['tax_percentage'] / 100 * $valueAfterHeaderDiscount),
                                //     'applicable_type' => 'Collection',
                                // ]);
                                $soItemTedForDiscount = ErpSaleInvoiceTed::updateOrCreate(
                                    [
                                        'sale_invoice_id' => $saleInvoice -> id,
                                        'invoice_item_id' => $soItem -> id,
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
                                    $itemAttribute = ErpInvoiceItemAttribute::updateOrCreate(
                                        [
                                            'sale_invoice_id' => $saleInvoice -> id,
                                            'invoice_item_id' => $soItem -> id,
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
                                    // ErpInvoiceItemAttribute::create([
                                    //     'sale_invoice_id' => $saleInvoice -> id,
                                    //     'invoice_item_id' => $soItem -> id,
                                    //     'item_attribute_id' => $attribute['id'],
                                    //     'item_code' => $soItem -> item_code,
                                    //     'attribute_name' => $attribute['group_name'],
                                    //     'attr_name' => $attribute['attribute_group_id'],
                                    //     'attribute_value' => $attributeVal,
                                    //     'attr_value' => $attributeValId,
                                    // ]);
                                }
                            } else {
                                return response() -> json([
                                    'message' => 'Item No. ' . ($itemDataKey + 1) . ' has invalid attributes',
                                    'error' => ''
                                ], 422);
                            }
                        }
                        // Item Locations (only in case of DN and Inv CUM DN)
                        // if (isset($request -> item_locations[$itemDataKey]) && ($request -> type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS || $request -> type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS)) {

                        if (isset($request -> item_locations[$itemDataKey]) ) {
                            $itemLocations = json_decode($request -> item_locations[$itemDataKey], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($itemLocations)) {
                                foreach ($itemLocations as $itemLocationKey => $itemLocationData) {
                                    ErpInvoiceItemLocation::create([
                                        'sale_invoice_id' => $saleInvoice -> id,
                                        'invoice_item_id' => $soItem -> id,
                                        'item_id' => $soItem -> item_id,
                                        'item_code' => $soItem -> item_code,
                                        'store_id' => $itemLocationData['store_id'],
                                        'store_code' => $itemLocationData['store_code'],
                                        'rack_id' => $itemLocationData['rack_id'],
                                        'rack_code' => $itemLocationData['rack_code'],
                                        'shelf_id' => $itemLocationData['shelf_id'],
                                        'shelf_code' => $itemLocationData['shelf_code'],
                                        'bin_id' => $itemLocationData['bin_id'],
                                        'bin_code' => $itemLocationData['bin_code'],
                                        'quantity' => $itemLocationData['qty'],
                                        'inventory_uom_qty' => $itemLocationData['inventory_uom_qty'] ?? 0
                                    ]);
                                }
                            } else {
                                return response() -> json([
                                    'message' => 'Item No. ' . ($itemDataKey + 1) . ' has invalid store locations',
                                    'error' => ''
                                ], 422);
                            }
                        }
                        if (($request -> type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS || $request -> type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS)) {
                            //Update Inventory Stock Settlement
                        }
                        InventoryHelper::settlementOfInventoryAndStock($saleInvoice -> id, $soItem -> id, 'invoice', $request->document_status ?? ConstantHelper::DRAFT);
                    }
                } else {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Please select Items',
                        'error' => "",
                    ], 422);
                }
                ErpSaleInvoiceTed::where([
                    'sale_invoice_id' => $saleInvoice -> id,
                    'invoice_item_id' => $soItem -> id,
                    'ted_type' => 'Tax',
                    'ted_level' => 'D',
                ]) -> whereNotIn('id', $itemTaxIds) -> delete();
                ErpInvoiceItemAttribute::where([
                    'sale_invoice_id' => $saleInvoice -> id,
                    'invoice_item_id' => $soItem -> id,
                ]) -> whereNotIn('id', $itemAttributeIds) -> delete();
                //Header TED (Discount)
                if (isset($request -> order_discount_value) && count($request -> order_discount_value) > 0) {
                    foreach ($request -> order_discount_value as $orderDiscountKey => $orderDiscountVal) {
                        $headerDiscountRowData = [
                            'sale_invoice_id' => $saleInvoice -> id,
                            'invoice_item_id' => null,
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
                            ErpSaleInvoiceTed::updateOrCreate(['id' => $request -> order_discount_id[$orderDiscountKey]], $headerDiscountRowData);
                        } else {
                            ErpSaleInvoiceTed::create($headerDiscountRowData);
                        }
                        // ErpSaleInvoiceTed::create([
                        //     'sale_invoice_id' => $saleInvoice -> id,
                        //     'invoice_item_id' => null,
                        //     'ted_type' => 'Discount',
                        //     'ted_level' => 'H',
                        //     'ted_id' => null,
                        //     'ted_name' => isset($request -> order_discount_name[$orderDiscountKey]) ? $request -> order_discount_name[$orderDiscountKey] : null,
                        //     'assessment_amount' => $totalItemValueAfterDiscount,
                        //     'ted_percentage' => $orderDiscountVal / $totalItemValueAfterDiscount * 100 ,
                        //     'ted_amount' => $orderDiscountVal,
                        //     'applicable_type' => 'Deduction',
                        // ]);
                    }
                }
                //Header TED (Expense)
                $totalValueAfterTax = $totalItemValueAfterDiscount + $totalTax;
                $totalExpenseAmount = 0;
                if (isset($request -> order_expense_value) && count($request -> order_expense_value) > 0) {
                    foreach ($request -> order_expense_value as $orderExpenseKey => $orderExpenseVal) {
                        $headerExpenseRowData = [
                            'sale_invoice_id' => $saleInvoice -> id,
                            'invoice_item_id' => null,
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
                            ErpSaleInvoiceTed::updateOrCreate(['id' => $request -> order_expense_id[$orderExpenseKey]], $headerExpenseRowData);
                        } else {
                            ErpSaleInvoiceTed::create($headerExpenseRowData);
                        }

                        // ErpSaleInvoiceTed::create([
                        //     'sale_invoice_id' => $saleInvoice -> id,
                        //     'invoice_item_id' => null,
                        //     'ted_type' => 'Expense',
                        //     'ted_level' => 'H',
                        //     'ted_id' => null,
                        //     'ted_name' => isset($request -> order_expense_name[$orderExpenseKey]) ? $request -> order_expense_name[$orderExpenseKey] : null,
                        //     'assessment_amount' => $totalItemValueAfterDiscount,
                        //     'ted_percentage' => $orderExpenseVal / $totalValueAfterTax * 100 , // Need to change
                        //     'ted_amount' => $orderExpenseVal,
                        //     'applicable_type' => 'Collection',
                        // ]);
                        $totalExpenseAmount += $orderExpenseVal;
                    }
                }
                //Check all total values
                if ($itemTotalValue - ($totalHeaderDiscount + $itemTotalDiscount) + $totalExpenseAmount < 0)
                {
                    DB::rollBack();
                    return response() -> json([
                        'status' => 'error',
                        'message' => 'Document Value cannot be less than 0'
                    ], 422);
                }
                $saleInvoice -> total_discount_value = $totalHeaderDiscount + $itemTotalDiscount;
                $saleInvoice -> total_item_value = $itemTotalValue;
                $saleInvoice -> total_tax_value = $totalTax;
                $saleInvoice -> total_expense_value = $totalExpenseAmount;
                $saleInvoice -> total_amount = ($itemTotalValue - ($totalHeaderDiscount + $itemTotalDiscount)) + $totalTax + $totalExpenseAmount;
                //Approval check
                //Approval check
                if ($request -> sale_invoice_id) { //Update condition
                    $bookId = $saleInvoice->book_id; 
                    $docId = $saleInvoice->id;
                    $amendRemarks = $request->amend_remarks ?? null;
                    $remarks = $saleInvoice->remarks;
                    $amendAttachments = $request->file('amend_attachments');
                    $attachments = $request->file('attachment');
                    $currentLevel = $saleInvoice->approval_level;
                    $modelName = get_class($saleInvoice);
                    $actionType = $request -> action_type ?? "";
                    if($saleInvoice -> document_status == ConstantHelper::APPROVED && $actionType == 'amendment')
                    {
                        //*amendmemnt document log*/
                        $revisionNumber = $saleInvoice->revision_number + 1;
                        $actionType = 'amendment';
                        $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $amendRemarks, $amendAttachments, $currentLevel, $actionType, 0, $modelName);
                        $saleInvoice->revision_number = $revisionNumber;
                        $saleInvoice->approval_level = 1;
                        $saleInvoice->revision_date = now();
                        $amendAfterStatus = $saleInvoice->document_status;
                        $checkAmendment = Helper::checkAfterAmendApprovalRequired($request->book_id);
                        if(isset($checkAmendment->approval_required) && $checkAmendment->approval_required) {
                            $totalValue = $saleInvoice->grand_total_amount ?? 0;
                            $amendAfterStatus = Helper::checkApprovalRequired($request->book_id,$totalValue);
                        } else {
                            $actionType = 'approve';
                            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
                        }
                        if ($amendAfterStatus == ConstantHelper::SUBMITTED) {
                            $actionType = 'submit';
                            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
                        }
                        $saleInvoice->document_status = $amendAfterStatus;
                        $saleInvoice->save();

                    } else {
                        if ($request->document_status == ConstantHelper::SUBMITTED) {
                            $revisionNumber = $saleInvoice->revision_number ?? 0;
                            $actionType = 'submit';
                            $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);

                            $totalValue = $saleInvoice->grand_total_amount ?? 0;
                            $document_status = Helper::checkApprovalRequired($request->book_id,$totalValue);
                            $saleInvoice->document_status = $document_status;
                        } else {
                            $saleInvoice->document_status = $request->document_status ?? ConstantHelper::DRAFT;
                        }
                    }
                } else { //Create condition
                    if ($request->document_status == ConstantHelper::SUBMITTED) {
                        $bookId = $saleInvoice->book_id;
                        $docId = $saleInvoice->id;
                        $remarks = $saleInvoice->remarks;
                        $attachments = $request->file('attachment');
                        $currentLevel = $saleInvoice->approval_level;
                        $revisionNumber = $saleInvoice->revision_number ?? 0;
                        $actionType = 'submit'; // Approve // reject // submit
                        $modelName = get_class($saleInvoice);
                        $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
                    }

                    if ($request->document_status == 'submitted') {
                        $totalValue = $saleInvoice->total_amount ?? 0;
                        $document_status = Helper::checkApprovalRequired($request->book_id,$totalValue);
                        $saleInvoice->document_status = $document_status;
                    } else {
                        $saleInvoice->document_status = $request->document_status ?? ConstantHelper::DRAFT;
                    }
                    $saleInvoice -> save();
                }
                $saleInvoice -> document_type = isset($request -> type) && in_array($request -> type, ConstantHelper::SALE_INVOICE_DOC_TYPES) ? $request -> type : ConstantHelper::SI_SERVICE_ALIAS;
                $saleInvoice -> save();
                //Media
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $singleFile) {
                        $mediaFiles = $saleInvoice->uploadDocuments($singleFile, 'sale_order', false);
                    }
                }
                //Logs
                // if ($request->document_status == ConstantHelper::SUBMITTED) {
                //     $bookId = $saleInvoice->book_id; 
                //     $docId = $saleInvoice->id;
                //     $remarks = $saleInvoice->remarks;
                //     $attachments = null;
                //     $currentLevel = $saleInvoice->approval_level;
                //     $revisionNumber = $saleInvoice->revision_number ?? 0;
                //     $actionType = 'submit'; // Approve // reject // submit
                //     $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType);
                // }
                DB::commit();
                $module = "Sales Invoice";
                if ($request -> type == ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
                    $module = "Delivery Note";
                } else if ($request -> type == ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS) {
                    $module = "DN Cum Invoice";
                }
                return response() -> json([
                    'message' => $module .  " created successfully",
                    'redirect_url' => route('sale.invoice.index', ['type' => $request -> type ?? ConstantHelper::SI_SERVICE_ALIAS])
                ]);

            
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error occurred while creating the record.',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }
    // public function storeBackup(ErpSaleInvoiceRequest $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $user = Helper::getAuthenticatedUser();
    //         //Auth credentials
    //         $organization = Organization::find($user -> organization_id);
    //         $organizationId = $organization ?-> id ?? null;
    //         $groupId = $organization ?-> group_id ?? null;
    //         $companyId = $organization ?-> company_id ?? null;
    //         //Tax Country and State
    //         $firstAddress = $organization->addresses->first();
    //         $companyCountryId = null;
    //         $companyStateId = null;
    //         if ($firstAddress) {
    //             $companyCountryId = $firstAddress->country_id;
    //             $companyStateId = $firstAddress->state_id;
    //         } else {
    //             return response()->json([
    //                 'message' => 'Please create an organization first'
    //             ], 422);
    //         }
    //         $currencyExchangeData = CurrencyHelper::getCurrencyExchangeRates($request -> currency_id, $request -> document_date);
    //         if ($currencyExchangeData['status'] == false) {
    //             return response()->json([
    //                 'message' => $currencyExchangeData['message']
    //             ], 422); 
    //         }
    //         $documentNo = $request -> document_number ?? null;
    //         if (!$request -> sale_invoice_id)
    //         {
    //             $numberPatternData = Helper::generateDocumentNumberNew($request -> book_id, $request -> document_date);
    //             if (!isset($numberPatternData)) {
    //                 return response()->json([
    //                     'message' => "Invalid Book",
    //                     'error' => "",
    //                 ], 422);
    //             }
    //             $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : $request -> document_no;
    //             $regeneratedDocExist = ErpSaleInvoice::withDefaultGroupCompanyOrg() -> where('book_id',$request->book_id)
    //                 ->where('document_number',$document_number)->first();
    //                 //Again check regenerated doc no
    //                 if (isset($regeneratedDocExist)) {
    //                     return response()->json([
    //                         'message' => ConstantHelper::DUPLICATE_DOCUMENT_NUMBER,
    //                         'error' => "",
    //                     ], 422);
    //                 }
    //         }
    //         $saleInvoice = null;
    //         if ($request -> sale_invoice_id) { //Update
    //             $saleInvoice = ErpSaleInvoice::find($request -> sale_invoice_id);
    //             $saleInvoice -> document_date = $request -> document_date;
    //             $saleInvoice -> reference_number = $request -> reference_no;
    //             $saleInvoice -> consignee_name = $request -> consignee_name;
    //             $saleInvoice -> consignment_no = $request -> consignment_no;
    //             $saleInvoice -> vehicle_no = $request -> vehicle_no;
    //             $saleInvoice -> transporter_name = $request -> transporter_name;
    //             $saleInvoice -> eway_bill_no = $request -> eway_bill_no;
    //             $saleInvoice -> remarks = $request -> final_remarks;
    //             $actionType = $request -> action_type ?? '';
    //             //Amend backup
    //             if($saleInvoice -> document_status == ConstantHelper::APPROVED && $actionType == 'amendment')
    //             {
    //                 $revisionData = [
    //                     ['model_type' => 'header', 'model_name' => 'ErpSaleInvoice', 'relation_column' => ''],
    //                     ['model_type' => 'detail', 'model_name' => 'ErpInvoiceItem', 'relation_column' => 'sale_invoice_id'],
    //                     ['model_type' => 'sub_detail', 'model_name' => 'ErpInvoiceItemAttribute', 'relation_column' => 'invoice_item_id'],
    //                     ['model_type' => 'sub_detail', 'model_name' => 'ErpInvoiceItemLocation', 'relation_column' => 'invoice_item_id'],
    //                     ['model_type' => 'sub_detail', 'model_name' => 'ErpSaleInvoiceTed', 'relation_column' => 'invoice_item_id'],
    //                 ];
    //                 $a = Helper::documentAmendment($revisionData, $saleInvoice->id);

    //             }
    //             //Delete all Item references
    //             foreach ($saleInvoice -> items as $item) {
    //                 InventoryHelper::deleteIssueStock($saleInvoice->id, $item->id, $item->item_id, 'invoice', 'issue');
    //                 if (($request -> type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS || $request -> type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS)) {
    //                 }
    //                 $item -> item_attributes() -> forceDelete();
    //                 $item -> discount_ted() -> forceDelete();
    //                 $item -> tax_ted() -> forceDelete();
    //                 $item -> item_locations() -> forceDelete();
    //                 $item -> forceDelete();
    //             }
    //             //Delete all header TEDs
    //             foreach ($saleInvoice -> discount_ted as $saleInvoiceDiscount) {
    //                 $saleInvoiceDiscount -> forceDelete(); 
    //             }
    //             foreach ($saleInvoice -> expense_ted as $saleInvoiceExpense) {
    //                 $saleInvoiceExpense -> forceDelete(); 
    //             }
    //         } else { //Create
    //             $saleInvoice = ErpSaleInvoice::create([
    //                 'organization_id' => $organizationId,
    //                 'group_id' => $groupId,
    //                 'company_id' => $companyId,
    //                 'book_id' => $request -> book_id,
    //                 'book_code' => $request -> book_code,
    //                 'document_type' => $request -> type,
    //                 'document_number' => $request -> document_no,
    //                 'document_date' => $request -> document_date,
    //                 'revision_number' => 0,
    //                 'revision_date' => null,
    //                 'reference_number' => $request -> reference_no,
    //                 'customer_id' => $request -> customer_id,
    //                 'customer_code' => $request -> customer_code,
    //                 'consignee_name' => $request -> consignee_name,
    //                 'consignment_no' => $request -> consignment_no,
    //                 'vehicle_no' => $request -> vehicle_no,
    //                 'transporter_name' => $request -> transporter_name,
    //                 'eway_bill_no' => $request -> eway_bill_no,
    //                 'billing_address' => null,
    //                 'shipping_address' => null,
    //                 'currency_id' => $request -> currency_id,
    //                 'currency_code' => $request -> currency_code,
    //                 'payment_term_id' => $request -> payment_terms_id,
    //                 'payment_term_code' => $request -> payment_terms_code,
    //                 'document_status' => ConstantHelper::DRAFT,
    //                 'approval_level' => 1,
    //                 'remarks' => $request -> final_remarks,
    //                 'org_currency_id' => $currencyExchangeData['data']['org_currency_id'],
    //                 'org_currency_code' => $currencyExchangeData['data']['org_currency_code'],
    //                 'org_currency_exg_rate' => $currencyExchangeData['data']['org_currency_exg_rate'],
    //                 'comp_currency_id' => $currencyExchangeData['data']['comp_currency_id'],
    //                 'comp_currency_code' => $currencyExchangeData['data']['comp_currency_code'],
    //                 'comp_currency_exg_rate' => $currencyExchangeData['data']['comp_currency_exg_rate'],
    //                 'group_currency_id' => $currencyExchangeData['data']['group_currency_id'],
    //                 'group_currency_code' => $currencyExchangeData['data']['group_currency_code'],
    //                 'group_currency_exg_rate' => $currencyExchangeData['data']['group_currency_exg_rate'],
    //                 'total_item_value' => 0,
    //                 'total_discount_value' => 0,
    //                 'total_tax_value' => 0,
    //                 'total_expense_value' => 0,
    //             ]);
    //             //Billing Address
    //             $customerBillingAddress = ErpAddress::find($request -> billing_address);
    //             if (isset($customerBillingAddress)) {
    //                 $billingAddress = $saleInvoice -> billing_address_details() -> create([
    //                     'address' => $customerBillingAddress -> address,
    //                     'country_id' => $customerBillingAddress -> country_id,
    //                     'state_id' => $customerBillingAddress -> state_id,
    //                     'city_id' => $customerBillingAddress -> city_id,
    //                     'type' => 'billing',
    //                     'pincode' => $customerBillingAddress -> pincode,
    //                     'phone' => $customerBillingAddress -> phone,
    //                     'fax_number' => $customerBillingAddress -> fax_number
    //                 ]);
    //             }
    //             // Shipping Address
    //             $customerShippingAddress = ErpAddress::find($request -> shipping_address);
    //             if (isset($customerShippingAddress)) {
    //                 $shippingAddress = $saleInvoice -> shipping_address_details() -> create([
    //                     'address' => $customerShippingAddress -> address,
    //                     'country_id' => $customerShippingAddress -> country_id,
    //                     'state_id' => $customerShippingAddress -> state_id,
    //                     'city_id' => $customerShippingAddress -> city_id,
    //                     'type' => 'shipping',
    //                     'pincode' => $customerShippingAddress -> pincode,
    //                     'phone' => $customerShippingAddress -> phone,
    //                     'fax_number' => $customerShippingAddress -> fax_number
    //                 ]);
    //             }
    //         }
    //             //Get Header Discount
    //             $totalHeaderDiscount = 0;
    //             if (isset($request -> order_discount_value) && count($request -> order_discount_value) > 0)
    //             foreach ($request -> order_discount_value as $orderDiscountValue) {
    //                 $totalHeaderDiscount += $orderDiscountValue;
    //             }
    //             //Initialize item discount to 0
    //             $itemTotalDiscount = 0;
    //             $itemTotalValue = 0;
    //             $totalTax = 0;
    //             $totalItemValueAfterDiscount = 0;

    //             $saleInvoice -> billing_address = isset($billingAddress) ? $billingAddress -> id : null;
    //             $saleInvoice -> shipping_address = isset($shippingAddress) ? $shippingAddress -> id : null;
    //             $saleInvoice -> save();
    //             //Seperate array to store each item calculation
    //             $itemsData = array();
    //             if ($request -> item_id && count($request -> item_id) > 0) {
    //                 //Items
    //                 $totalValueAfterDiscount = 0;
    //                 foreach ($request -> item_id as $itemKey => $itemId) {
    //                     $item = Item::find($itemId);
    //                     if (isset($item))
    //                     {
    //                         $itemValue = (isset($request -> item_qty[$itemKey]) ? $request -> item_qty[$itemKey] : 0) * (isset($request -> item_rate[$itemKey]) ? $request -> item_rate[$itemKey] : 0);
    //                         $itemDiscount = 0;
    //                         //Item Level Discount
    //                         if (isset($request -> item_discount_value[$itemKey]) && count($request -> item_discount_value[$itemKey]) > 0)
    //                         {
    //                             foreach ($request -> item_discount_value[$itemKey] as $itemDiscountValue) {
    //                                 $itemDiscount += $itemDiscountValue;
    //                             }
    //                         }
    //                         $itemTotalValue += $itemValue;
    //                         $itemTotalDiscount += $itemDiscount;
    //                         $itemValueAfterDiscount = $itemValue - $itemDiscount;
    //                         $totalValueAfterDiscount += $itemValueAfterDiscount;
    //                         $totalItemValueAfterDiscount += $itemValueAfterDiscount;
    //                         $inventoryUomQty = isset($request -> item_qty[$itemKey]) ? $request -> item_qty[$itemKey] : 0;
    //                         $requestUomId = isset($request -> uom_id[$itemKey]) ? $request -> uom_id[$itemKey] : null;
    //                         if($requestUomId != $item->uom_id) {
    //                             $alUom = $item->alternateUOMs()->where('uom_id',$requestUomId)->first();
    //                             if($alUom) {
    //                                 $inventoryUomQty= intval(isset($request -> item_qty[$itemKey]) ? $request -> item_qty[$itemKey] : 0) * $alUom->conversion_to_inventory;
    //                             }
    //                         }
    //                         array_push($itemsData, [
    //                             'sale_invoice_id' => $saleInvoice -> id,
    //                             'item_id' => $item -> id,
    //                             'item_code' => $item -> item_code,
    //                             'item_name' => $item -> item_name,
    //                             'hsn_id' => $item -> hsn_id,
    //                             'hsn_code' => $item -> hsn ?-> code,
    //                             'uom_id' => isset($request -> uom_id[$itemKey]) ? $request -> uom_id[$itemKey] : null, //Need to change
    //                             'uom_code' => isset($request -> item_uom_code[$itemKey]) ? $request -> item_uom_code[$itemKey] : null,
    //                             'order_qty' => isset($request -> item_qty[$itemKey]) ? $request -> item_qty[$itemKey] : 0,
    //                             'invoice_qty' => 0,
    //                             'inventory_uom_id' => $item -> uom ?-> id,
    //                             'inventory_uom_code' => $item -> uom ?-> name,
    //                             'inventory_uom_qty' => $inventoryUomQty,
    //                             'rate' => isset($request -> item_rate[$itemKey]) ? $request -> item_rate[$itemKey] : 0,
    //                             'item_discount_amount' => $itemDiscount,
    //                             'header_discount_amount' => 0,
    //                             'item_expense_amount' => 0, //Need to change
    //                             'header_expense_amount' => 0, //Need to change
    //                             'tax_amount' => 0,
    //                             'company_currency_id' => null,
    //                             'company_currency_exchange_rate' => null,
    //                             'group_currency_id' => null,
    //                             'group_currency_exchange_rate' => null,
    //                             'remarks' => isset($request -> item_remarks[$itemKey]) ? $request -> item_remarks[$itemKey] : null,
    //                             'value_after_discount' => $itemValueAfterDiscount,
    //                             'item_value' => $itemValue
    //                         ]);
    //                     }
    //                 }
    //                 foreach ($itemsData as $itemDataKey => $itemDataValue) {
    //                     //Discount
    //                     $headerDiscount = 0;
    //                     if ($totalValueAfterDiscount > 0) {
    //                         $headerDiscount = ($itemDataValue['value_after_discount'] / $totalValueAfterDiscount) * $totalHeaderDiscount;
    //                     }
    //                     $valueAfterHeaderDiscount = $itemDataValue['value_after_discount'] - $headerDiscount;
    //                     //Expense
    //                     $itemExpenseAmount = 0;
    //                     $itemHeaderExpenseAmount = 0;
    //                     //Tax
    //                     $itemTax = 0;
    //                     $itemPrice = ($itemDataValue['item_value'] + $headerDiscount + $itemDataValue['item_discount_amount']) / $itemDataValue['order_qty'];
    //                     $partyCountryId = isset($shippingAddress) ? $shippingAddress -> country_id : null;
    //                     $partyStateId = isset($shippingAddress) ? $shippingAddress -> state_id : null;
    //                     $taxDetails = TaxHelper::calculateTax($itemDataValue['hsn_id'], $itemPrice, $companyCountryId, $companyStateId, $partyCountryId ?? $request -> shipping_country_id, $partyStateId ?? $request -> shipping_state_id, 'sale');
    //                     if (isset($taxDetails) && count($taxDetails) > 0) {
    //                         foreach ($taxDetails as $taxDetail) {
    //                             $itemTax += ((double)$taxDetail['tax_percentage'] / 100 * $valueAfterHeaderDiscount);
    //                         }
    //                     }
    //                     $totalTax += $itemTax;
    //                     $soItem = ErpInvoiceItem::create([
    //                         'sale_invoice_id' => $saleInvoice -> id,
    //                         'item_id' => $itemDataValue['item_id'],
    //                         'item_code' => $itemDataValue['item_code'],
    //                         'item_name' => $itemDataValue['item_name'],
    //                         'hsn_id' => $itemDataValue['hsn_id'],
    //                         'hsn_code' => $itemDataValue['hsn_code'],
    //                         'uom_id' => $itemDataValue['uom_id'], //Need to change
    //                         'uom_code' => $itemDataValue['uom_code'],
    //                         'order_qty' => $itemDataValue['order_qty'],
    //                         'invoice_qty' => $itemDataValue['invoice_qty'],
    //                         'inventory_uom_id' => $itemDataValue['inventory_uom_id'],
    //                         'inventory_uom_code' => $itemDataValue['inventory_uom_code'],
    //                         'inventory_uom_qty' => $itemDataValue['inventory_uom_qty'],
    //                         'rate' => $itemDataValue['rate'],
    //                         'item_discount_amount' => $itemDataValue['item_discount_amount'],
    //                         'header_discount_amount' => $headerDiscount,
    //                         'item_expense_amount' => $itemExpenseAmount, //Need to change
    //                         'header_expense_amount' => $itemHeaderExpenseAmount, //Need to change
    //                         'total_item_amount' => ($itemDataValue['order_qty'] * $itemDataValue['rate']) - ($itemDataValue['item_discount_amount'] + $headerDiscount) + ($itemExpenseAmount + $itemHeaderExpenseAmount) + $itemTax,
    //                         'tax_amount' => $itemTax,
    //                         'company_currency_id' => null,
    //                         'company_currency_exchange_rate' => null,
    //                         'group_currency_id' => null,
    //                         'group_currency_exchange_rate' => null,
    //                         'remarks' => $itemDataValue['remarks'],
    //                     ]);
    //                     //Order Pulling condition 
    //                     if ($request -> quotation_item_ids && isset($request -> quotation_item_ids[$itemDataKey]) && isset($request -> quotation_item_type[$itemDataKey])) {
    //                         $pullType = $request -> quotation_item_type[$itemDataKey];
    //                         if ($pullType === ConstantHelper::SO_SERVICE_ALIAS) {
    //                             $qtItem = ErpSoItem::find($request -> quotation_item_ids[$itemDataKey]);
    //                             if (isset($qtItem)) {
    //                                 $qtItem -> invoice_qty = $qtItem -> invoice_qty + $itemDataValue['order_qty'];
    //                                 $qtItem -> save();
    //                                 $soItem -> sale_order_id = $qtItem -> header ?-> id;
    //                                 $soItem -> save();
    //                             }
    //                         } else if ($pullType === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
    //                             $qtItem = ErpInvoiceItem::find($request -> quotation_item_ids[$itemDataKey]);
    //                             if (isset($qtItem)) {
    //                                 $qtItem -> invoice_qty = $qtItem -> invoice_qty + $itemDataValue['order_qty'];
    //                                 $qtItem -> save();
    //                                 $soItem -> dn_id = $qtItem -> header ?-> id;
    //                                 $soItem -> save();
    //                             }
    //                         } else if ($pullType === ConstantHelper::SI_SERVICE_ALIAS) {
    //                             $qtItem = ErpInvoiceItem::find($request -> quotation_item_ids[$itemDataKey]);
    //                             if (isset($qtItem)) {
    //                                 $qtItem -> invoice_qty = $qtItem -> invoice_qty + $itemDataValue['order_qty'];
    //                                 $qtItem -> save();
    //                                 $soItem -> invoice_id = $qtItem -> header ?-> id;
    //                                 $soItem -> save();
    //                             }
    //                         } else if ($pullType === ConstantHelper::LAND_LEASE) {
    //                             $leaseSchedule = LandLeaseScheduler::find($request -> quotation_item_ids[$itemDataKey]);
    //                             if (isset($leaseSchedule)) {
    //                                 // $leaseSchedule -> status = 'paid';
    //                                 $leaseSchedule -> invoice_amount = $leaseSchedule -> invoice_amount + $itemDataValue['rate'];
    //                                 $leaseSchedule -> save();
    //                                 $soItem -> lease_schedule_id = $leaseSchedule -> id;
    //                                 $soItem -> land_lease_id = $leaseSchedule -> header ?-> id;
    //                                 $soItem -> save();
    //                             }
    //                             if (isset($request -> quotation_item_ids[$itemDataKey]) && ($request -> quotation_item_ids[$itemDataKey] == 0) && isset($request -> quotation_item_ids_header[$itemDataKey] )) {
    //                                 $landLease = LandLease::find($request -> quotation_item_ids_header[$itemDataKey]);
    //                                 if (isset($landLease)) {
    //                                     $landLease -> invoice_security_deposit = $landLease -> invoice_security_deposit + $itemDataValue['rate'];
    //                                     $landLease -> save();
    //                                 }
    //                             }
    //                         }
                            
    //                     }
    //                     //TED Data (DISCOUNT)
    //                     if (isset($request -> item_discount_value[$itemDataKey]))
    //                     {
    //                         foreach ($request -> item_discount_value[$itemDataKey] as $itemDiscountKey => $itemDiscountTed){
    //                             $soItemTedForDiscount = ErpSaleInvoiceTed::create([
    //                                 'sale_invoice_id' => $saleInvoice -> id,
    //                                 'invoice_item_id' => $soItem -> id,
    //                                 'ted_type' => 'Discount',
    //                                 'ted_level' => 'D',
    //                                 'ted_id' => null,
    //                                 'ted_name' => isset($request -> item_discount_name[$itemDataKey][$itemDiscountKey]) ? $request -> item_discount_name[$itemDataKey][$itemDiscountKey] : null,
    //                                 'assessment_amount' => $itemDataValue['rate'] * $itemDataValue['order_qty'],
    //                                 'ted_percentage' => $itemDiscountTed / ($itemDataValue['rate'] * $itemDataValue['order_qty']) * 100,
    //                                 'ted_amount' => $itemDiscountTed,
    //                                 'applicable_type' => 'Deduction',
    //                             ]);
    //                         }
    //                     }
    //                     //TED Data (TAX)
    //                     if (isset($taxDetails) && count($taxDetails) > 0) {
    //                         foreach ($taxDetails as $taxDetail) {
    //                             $soItemTedForDiscount = ErpSaleInvoiceTed::create([
    //                                 'sale_invoice_id' => $saleInvoice -> id,
    //                                 'invoice_item_id' => $soItem -> id,
    //                                 'ted_type' => 'Tax',
    //                                 'ted_level' => 'D',
    //                                 'ted_id' => $taxDetail['tax_id'],
    //                                 'ted_group_code' => $taxDetail['tax_group'],
    //                                 'ted_name' => $taxDetail['tax_type'],
    //                                 'assessment_amount' => $valueAfterHeaderDiscount,
    //                                 'ted_percentage' => (double)$taxDetail['tax_percentage'],
    //                                 'ted_amount' => ((double)$taxDetail['tax_percentage'] / 100 * $valueAfterHeaderDiscount),
    //                                 'applicable_type' => 'Collection',
    //                             ]);
    //                         }
    //                     }
    //                     //Item Attributes
    //                     if (isset($request -> item_attributes[$itemDataKey])) {
    //                         $attributesArray = json_decode($request -> item_attributes[$itemDataKey], true);
    //                         if (json_last_error() === JSON_ERROR_NONE && is_array($attributesArray)) {
    //                             foreach ($attributesArray as $attributeKey => $attribute) {
    //                                 $attributeVal = "";
    //                                 $attributeValId = null;
    //                                 foreach ($attribute['values_data'] as $valData) {
    //                                     if ($valData['selected']) {
    //                                         $attributeVal = $valData['value'];
    //                                         $attributeValId = $valData['id'];
    //                                         break;
    //                                     }
    //                                 }
    //                                 ErpInvoiceItemAttribute::create([
    //                                     'sale_invoice_id' => $saleInvoice -> id,
    //                                     'invoice_item_id' => $soItem -> id,
    //                                     'item_attribute_id' => $attribute['id'],
    //                                     'item_code' => $soItem -> item_code,
    //                                     'attribute_name' => $attribute['group_name'],
    //                                     'attr_name' => $attribute['attribute_group_id'],
    //                                     'attribute_value' => $attributeVal,
    //                                     'attr_value' => $attributeValId,
    //                                 ]);
    //                             }
    //                         } else {
    //                             return response() -> json([
    //                                 'message' => 'Item No. ' . ($itemDataKey + 1) . ' has invalid attributes',
    //                                 'error' => ''
    //                             ], 422);
    //                         }
    //                     }
    //                     // Item Locations (only in case of DN and Inv CUM DN)
    //                     // if (isset($request -> item_locations[$itemDataKey]) && ($request -> type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS || $request -> type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS)) {
    //                     if (isset($request -> item_locations[$itemDataKey]) ) {
    //                         $itemLocations = json_decode($request -> item_locations[$itemDataKey], true);
    //                         if (json_last_error() === JSON_ERROR_NONE && is_array($itemLocations)) {
    //                             foreach ($itemLocations as $itemLocationKey => $itemLocationData) {
    //                                 ErpInvoiceItemLocation::create([
    //                                     'sale_invoice_id' => $saleInvoice -> id,
    //                                     'invoice_item_id' => $soItem -> id,
    //                                     'item_id' => $soItem -> item_id,
    //                                     'item_code' => $soItem -> item_code,
    //                                     'store_id' => $itemLocationData['store_id'],
    //                                     'store_code' => $itemLocationData['store_code'],
    //                                     'rack_id' => $itemLocationData['rack_id'],
    //                                     'rack_code' => $itemLocationData['rack_code'],
    //                                     'shelf_id' => $itemLocationData['shelf_id'],
    //                                     'shelf_code' => $itemLocationData['shelf_code'],
    //                                     'bin_id' => $itemLocationData['bin_id'],
    //                                     'bin_code' => $itemLocationData['bin_code'],
    //                                     'quantity' => $itemLocationData['qty'],
    //                                     'inventory_uom_qty' => $itemDataValue['inventory_uom_qty'] ?? 0
    //                                 ]);
    //                             }
    //                         } else {
    //                             return response() -> json([
    //                                 'message' => 'Item No. ' . ($itemDataKey + 1) . ' has invalid store locations',
    //                                 'error' => ''
    //                             ], 422);
    //                         }
    //                     }
    //                     if (($request -> type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS || $request -> type === ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS)) {
    //                         //Update Inventory Stock Settlement
    //                     }
    //                     InventoryHelper::settlementOfInventoryAndStock($saleInvoice -> id, $soItem -> id, 'invoice', $request->document_status ?? ConstantHelper::DRAFT);
    //                 }
    //             } else {
    //                 DB::rollBack();
    //                 return response()->json([
    //                     'message' => 'Please select Items',
    //                     'error' => "",
    //                 ], 422);
    //             }
    //             //Header TED (Discount)
    //             if (isset($request -> order_discount_value) && count($request -> order_discount_value) > 0) {
    //                 foreach ($request -> order_discount_value as $orderDiscountKey => $orderDiscountVal) {
    //                     ErpSaleInvoiceTed::create([
    //                         'sale_invoice_id' => $saleInvoice -> id,
    //                         'invoice_item_id' => null,
    //                         'ted_type' => 'Discount',
    //                         'ted_level' => 'H',
    //                         'ted_id' => null,
    //                         'ted_name' => isset($request -> order_discount_name[$orderDiscountKey]) ? $request -> order_discount_name[$orderDiscountKey] : null,
    //                         'assessment_amount' => $totalItemValueAfterDiscount,
    //                         'ted_percentage' => $orderDiscountVal / $totalItemValueAfterDiscount * 100 ,
    //                         'ted_amount' => $orderDiscountVal,
    //                         'applicable_type' => 'Deduction',
    //                     ]);
    //                 }
    //             }
    //             //Header TED (Expense)
    //             $totalValueAfterTax = $totalItemValueAfterDiscount + $totalTax;
    //             $totalExpenseAmount = 0;
    //             if (isset($request -> order_expense_value) && count($request -> order_expense_value) > 0) {
    //                 foreach ($request -> order_expense_value as $orderExpenseKey => $orderExpenseVal) {
    //                     ErpSaleInvoiceTed::create([
    //                         'sale_invoice_id' => $saleInvoice -> id,
    //                         'invoice_item_id' => null,
    //                         'ted_type' => 'Expense',
    //                         'ted_level' => 'H',
    //                         'ted_id' => null,
    //                         'ted_name' => isset($request -> order_expense_name[$orderExpenseKey]) ? $request -> order_expense_name[$orderExpenseKey] : null,
    //                         'assessment_amount' => $totalItemValueAfterDiscount,
    //                         'ted_percentage' => $orderExpenseVal / $totalValueAfterTax * 100 , // Need to change
    //                         'ted_amount' => $orderExpenseVal,
    //                         'applicable_type' => 'Collection',
    //                     ]);
    //                     $totalExpenseAmount += $orderExpenseVal;
    //                 }
    //             }
    //             $saleInvoice -> total_discount_value = $totalHeaderDiscount + $itemTotalDiscount;
    //             $saleInvoice -> total_item_value = $itemTotalValue;
    //             $saleInvoice -> total_tax_value = $totalTax;
    //             $saleInvoice -> total_expense_value = $totalExpenseAmount;
    //             $saleInvoice -> total_amount = ($itemTotalValue - ($totalHeaderDiscount + $itemTotalDiscount)) + $totalTax + $totalExpenseAmount;
    //             //Approval check
    //             //Approval check
    //             if ($request -> sale_invoice_id) { //Update condition
    //                 $bookId = $saleInvoice->book_id; 
    //                 $docId = $saleInvoice->id;
    //                 $amendRemarks = $request->amend_remarks ?? null;
    //                 $remarks = $saleInvoice->remarks;
    //                 $amendAttachments = $request->file('amend_attachments');
    //                 $attachments = $request->file('attachment');
    //                 $currentLevel = $saleInvoice->approval_level;
    //                 $modelName = get_class($saleInvoice);
    //                 $actionType = $request -> action_type ?? "";
    //                 if($saleInvoice -> document_status == ConstantHelper::APPROVED && $actionType == 'amendment')
    //                 {
    //                     //*amendmemnt document log*/
    //                     $revisionNumber = $saleInvoice->revision_number + 1;
    //                     $actionType = 'amendment';
    //                     $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $amendRemarks, $amendAttachments, $currentLevel, $actionType, 0, $modelName);
    //                     $saleInvoice->revision_number = $revisionNumber;
    //                     $saleInvoice->approval_level = 1;
    //                     $saleInvoice->revision_date = now();
    //                     $amendAfterStatus = $saleInvoice->document_status;
    //                     $checkAmendment = Helper::checkAfterAmendApprovalRequired($request->book_id);
    //                     if(isset($checkAmendment->approval_required) && $checkAmendment->approval_required) {
    //                         $totalValue = $saleInvoice->grand_total_amount ?? 0;
    //                         $amendAfterStatus = Helper::checkApprovalRequired($request->book_id,$totalValue);
    //                     } else {
    //                         $actionType = 'approve';
    //                         $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
    //                     }
    //                     if ($amendAfterStatus == ConstantHelper::SUBMITTED) {
    //                         $actionType = 'submit';
    //                         $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
    //                     }
    //                     $saleInvoice->document_status = $amendAfterStatus;
    //                     $saleInvoice->save();

    //                 } else {
    //                     if ($request->document_status == ConstantHelper::SUBMITTED) {
    //                         $revisionNumber = $saleInvoice->revision_number ?? 0;
    //                         $actionType = 'submit';
    //                         $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);

    //                         $totalValue = $saleInvoice->grand_total_amount ?? 0;
    //                         $document_status = Helper::checkApprovalRequired($request->book_id,$totalValue);
    //                         $saleInvoice->document_status = $document_status;
    //                     } else {
    //                         $saleInvoice->document_status = $request->document_status ?? ConstantHelper::DRAFT;
    //                     }
    //                 }
    //             } else { //Create condition
    //                 if ($request->document_status == ConstantHelper::SUBMITTED) {
    //                     $bookId = $saleInvoice->book_id;
    //                     $docId = $saleInvoice->id;
    //                     $remarks = $saleInvoice->remarks;
    //                     $attachments = $request->file('attachment');
    //                     $currentLevel = $saleInvoice->approval_level;
    //                     $revisionNumber = $saleInvoice->revision_number ?? 0;
    //                     $actionType = 'submit'; // Approve // reject // submit
    //                     $modelName = get_class($saleInvoice);
    //                     $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
    //                 }

    //                 if ($request->document_status == 'submitted') {
    //                     $totalValue = $saleInvoice->total_amount ?? 0;
    //                     $document_status = Helper::checkApprovalRequired($request->book_id,$totalValue);
    //                     $saleInvoice->document_status = $document_status;
    //                 } else {
    //                     $saleInvoice->document_status = $request->document_status ?? ConstantHelper::DRAFT;
    //                 }
    //                 $saleInvoice -> save();
    //             }
    //             $saleInvoice -> document_type = isset($request -> type) && in_array($request -> type, ConstantHelper::SALE_INVOICE_DOC_TYPES) ? $request -> type : ConstantHelper::SI_SERVICE_ALIAS;
    //             $saleInvoice -> save();
    //             //Images
    //             if ($request->hasFile('attachments')) {
    //                 if ($saleInvoice->hasMedia('attachments')) {
    //                     $saleInvoice->clearMediaCollection('attachments');
    //                 }
    //                 foreach ($request->file('attachments') as $file) {
    //                     $saleInvoice->addMedia($file)->toMediaCollection('attachments'); // 'attachments' is the media collection name
    //                 }
    //             }
    //             //Logs
    //             // if ($request->document_status == ConstantHelper::SUBMITTED) {
    //             //     $bookId = $saleInvoice->book_id; 
    //             //     $docId = $saleInvoice->id;
    //             //     $remarks = $saleInvoice->remarks;
    //             //     $attachments = null;
    //             //     $currentLevel = $saleInvoice->approval_level;
    //             //     $revisionNumber = $saleInvoice->revision_number ?? 0;
    //             //     $actionType = 'submit'; // Approve // reject // submit
    //             //     $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType);
    //             // }
    //             DB::commit();
    //             $module = "Sales Invoice";
    //             if ($request -> type == ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
    //                 $module = "Delivery Note";
    //             } else if ($request -> type == ConstantHelper::DELIVERY_CHALLAN_CUM_SI_SERVICE_ALIAS) {
    //                 $module = "DN Cum Invoice";
    //             }
    //             return response() -> json([
    //                 'message' => $module .  " created successfully",
    //                 'redirect_url' => route('sale.invoice.index', ['type' => $request -> type ?? ConstantHelper::SI_SERVICE_ALIAS])
    //             ]);

            
    //     } catch(Exception $ex) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Error occurred while creating the record.',
    //             'error' => $ex->getMessage(),
    //         ], 500);
    //     }
    // }

    public function amendmentSubmit(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $saleInvoice = ErpSaleInvoice::where('id',$id)->first();
            if (!$saleInvoice) {
                return response()->json(['data' => [], 'message' => "Sale Invoice not found.", 'status' => 404]);
            }

            $revisionData = [
                ['model_type' => 'header', 'model_name' => 'ErpSaleInvoice', 'relation_column' => ''],
                ['model_type' => 'detail', 'model_name' => 'ErpInvoiceItem', 'relation_column' => 'sale_invoice_id'],
                ['model_type' => 'sub_detail', 'model_name' => 'ErpInvoiceItemAttribute', 'relation_column' => 'invoice_item_id'],
                ['model_type' => 'sub_detail', 'model_name' => 'ErpInvoiceItemLocation', 'relation_column' => 'invoice_item_id'],
                ['model_type' => 'sub_detail', 'model_name' => 'ErpSaleInvoiceTed', 'relation_column' => 'invoice_item_id'],
            ];

            $a = Helper::documentAmendment($revisionData, $id);
            if ($a) {
                //*amendmemnt document log*/
                $bookId = $saleInvoice->book_id; 
                $docId = $saleInvoice->id;
                $remarks = 'Amendment';
                $attachments = $request->file('attachment');
                $currentLevel = $saleInvoice->approval_level;
                $revisionNumber = $saleInvoice->revision_number;
                $actionType = 'amendment'; // Approve // reject // submit // amend
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType);

                
                $saleInvoice->document_status = ConstantHelper::DRAFT;
                $saleInvoice->revision_number = $saleInvoice->revision_number + 1;
                $saleInvoice->approval_level = 1;
                $saleInvoice->revision_date = now();
                $saleInvoice->save();
            }

            DB::commit();
            return response()->json(['data' => [], 'message' => "Amendment done!", 'status' => 200]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Amendment Submit Error: ' . $e->getMessage());
            return response()->json(['data' => [], 'message' => "An unexpected error occurred. Please try again.", 'error' => $e -> getMessage(), 'status' => 500]);
        }
    }

    //Function to get all items of sales module depending upon the doc type - order , invoice, delivery note
    public function getSalesItemsForPulling(Request $request)
    {
        try {
            $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($request -> header_book_id);
            if ($request -> doc_type === ConstantHelper::SO_SERVICE_ALIAS) {
                $order = ErpSoItem::whereHas('header', function ($subQuery) use($request, $applicableBookIds) {
                    $subQuery -> where('document_type', ConstantHelper::SO_SERVICE_ALIAS) -> whereIn('document_status', [ConstantHelper::APPROVED, ConstantHelper::APPROVAL_NOT_REQUIRED]) -> whereIn('book_id', $applicableBookIds) -> when($request -> customer_id, function ($custQuery) use($request) {
                        $custQuery -> where('customer_id', $request -> customer_id);
                    }) -> when($request -> book_id, function ($bookQuery) use($request) {
                        $bookQuery -> where('book_id', $request -> book_id);
                    }) -> when($request -> document_id, function ($docQuery) use($request) {
                        $docQuery -> where('id', $request -> document_id);
                    });
                })-> with('attributes') -> with('uom') -> with('header', function ($headerQuery) {
                    $headerQuery -> with(['customer', 'shipping_address_details']);
                }) -> whereColumn('dnote_qty', "<", "order_qty");
            } else if ($request -> doc_type === ConstantHelper::SI_SERVICE_ALIAS || $request -> doc_type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
                $order = ErpInvoiceItem::whereHas('header', function ($subQuery) use($request, $applicableBookIds) {
                    $subQuery -> whereIn('document_type', [ConstantHelper::SI_SERVICE_ALIAS, ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS]) -> whereIn('document_status', [ConstantHelper::APPROVED, ConstantHelper::APPROVAL_NOT_REQUIRED]) -> whereIn('book_id', $applicableBookIds) -> when($request -> customer_id, function ($custQuery) use($request) {
                        $custQuery -> where('customer_id', $request -> customer_id);
                    }) -> when($request -> book_id, function ($bookQuery) use($request) {
                        $bookQuery -> where('book_id', $request -> book_id);
                    }) -> when($request -> document_id, function ($docQuery) use($request) {
                        $docQuery -> where('id', $request -> document_id);
                    });
                })-> with('attributes') -> with('uom') -> with('header', function ($headerQuery) use($request) {
                    $headerQuery -> with(['customer', 'shipping_address_details']) -> when($request -> doc_type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS, function ($dnQuery) {
                        $dnQuery -> where('invoice_required', 1);
                    });
                })-> whereColumn('invoice_qty', "<", "order_qty");    
            } 
            else if ($request -> doc_type === ConstantHelper::LAND_LEASE) {
                $order = LandLeaseScheduler::whereHas('header', function ($subQuery) use($applicableBookIds, $request) {
                    $subQuery -> whereIn('book_id', $applicableBookIds) -> whereIn('approvalStatus', [ConstantHelper::APPROVED, ConstantHelper::APPROVAL_NOT_REQUIRED])
                    -> when($request -> book_id, function ($bookQuery) use($request) {
                        $bookQuery -> where('book_id', $request -> book_id);
                    }) -> when($request -> document_id, function ($docQuery) use($request) {
                        $docQuery -> where('id', $request -> document_id);
                    })-> when($request -> book_id, function ($bookQuery) use($request) {
                        $bookQuery -> where('book_id', $request -> book_id);
                    }) -> when($request -> document_id, function ($docQuery) use($request) {
                        $docQuery -> where('id', $request -> document_id);
                    }) -> when($request -> customer_id, function ($docQuery) use($request) {
                        $docQuery -> where('customer_id', $request -> customer_id);
                    }) -> when($request -> land_parcel_id, function ($landPlotQuery) use($request) {
                        $landPlotQuery -> whereHas('plots', function ($plotQuery) use($request) {
                            $plotQuery -> where('land_parcel_id', $request -> land_parcel_id);
                        });
                    }) -> when($request -> land_plot_id, function ($landPlotQuery) use($request) {
                        $landPlotQuery -> whereHas('plots', function ($plotQuery) use($request) {
                            $plotQuery -> where('land_plot_id', $request -> land_plot_id);
                        });
                    });
                }) -> with('header', function ($headerQuery) use($request) {
                    $headerQuery -> with(['address', 'series', 'customer', 'plots.land', 'plots.plot']);
                }) -> where('due_date', '<=', Carbon::now()) -> whereColumn('installment_cost', '>', 'invoice_amount');
            } 
            else {
                $order = null;
            }
            if ($request -> item_id && isset($order) && $request -> doc_type !== ConstantHelper::LAND_LEASE) {
                $order = $order -> where('item_id', $request -> item_id);
            }
            $order = isset($order) ? $order -> get() : new Collection();
            if ($request -> doc_type !== ConstantHelper::LAND_LEASE) {
                $order = $order -> filter(function ($singleOrder) {
                    return $singleOrder -> balance_qty > 0;
                });
                foreach ($order as &$itemVal) {
                    $itemVal -> stock_qty = $itemVal -> getStockBalanceQty(); 
                }                
            } else {
                $headerId = null;
                $headerIds = [];
                foreach ($order as &$itemVal) {
                    if ($headerId !== $itemVal -> header -> id) {
                        $securityItem = $itemVal ?-> header ?-> securityItem();
                        if (isset($securityItem)) {
                            $order -> push($securityItem);
                        }
                    }
                    $itemVal -> type = ConstantHelper::LEASE;
                    $itemDetails = ($itemVal -> header ?-> plots() ?-> first() ?-> land);
                    if (($itemDetails)) {
                        $serviceItems = (json_decode($itemDetails -> service_item, true));
                        if (isset($serviceItems) && count($serviceItems) > 0) {
                            $serviceItem = array_filter($serviceItems, function ($servItem) {
                                return $servItem["'servicetype'"] == 'land-lease';
                            });
                            if (count($serviceItem) > 0) {
                                $serviceItem = array_values($serviceItem);
                                $item = Item::where('item_code', $serviceItem[0]["'servicecode'"])-> where('type', ConstantHelper::SERVICE) -> first();
                                if (isset($item)) {
                                    $itemVal -> can_check = true; 
                                    $itemVal -> can_check_message = '';
                                } else {
                                    $itemVal -> can_check = false; 
                                    $itemVal -> can_check_message = 'Service Item not found, Please update before selecting';
                                }
                            } else {
                                $itemVal -> can_check = false; 
                                $itemVal -> can_check_message = 'Service Item not found, Please update before selecting'; 
                            }
                        }
                    } else {
                        $itemVal -> can_check = false; 
                        $itemVal -> can_check_message = 'Service Item not found, Please update before selecting'; 
                    }
                    $itemVal -> installment_cost = round($itemVal -> installment_cost - $itemVal -> invoice_amount, 2);
                    $headerId = $itemVal -> header -> id;
                    array_push($headerIds, $itemVal -> header -> id);
                }
                $headers = LandLease:: whereIn('book_id', $applicableBookIds) -> whereNotIn('id', $headerIds) 
                -> whereIn('approvalStatus', [ConstantHelper::APPROVED, ConstantHelper::APPROVAL_NOT_REQUIRED]) 
                -> whereColumn('security_deposit', '>', 'invoice_security_deposit') 
                -> with(['address', 'series', 'customer', 'plots.land', 'plots.plot']) 
                -> when($request -> book_id, function ($bookQuery) use($request) {
                    $bookQuery -> where('book_id', $request -> book_id);
                }) -> when($request -> document_id, function ($docQuery) use($request) {
                    $docQuery -> where('id', $request -> document_id);
                }) -> when($request -> customer_id, function ($docQuery) use($request) {
                    $docQuery -> where('customer_id', $request -> customer_id);
                }) -> when($request -> land_parcel_id, function ($landPlotQuery) use($request) {
                    $landPlotQuery -> whereHas('plots', function ($plotQuery) use($request) {
                        $plotQuery -> where('land_parcel_id', $request -> land_parcel_id);
                    });
                }) -> when($request -> land_plot_id, function ($landPlotQuery) use($request) {
                    $landPlotQuery -> whereHas('plots', function ($plotQuery) use($request) {
                        $plotQuery -> where('land_plot_id', $request -> land_plot_id);
                    });
                }) -> get();
                    if (count($headers) > 0) {
                        foreach($headers as &$header) {
                            $securityItem = $header ?-> securityItem();
                            if (isset($securityItem)) {
                                $order -> push($securityItem);
                            }
                        }
                    }
                
            }
            $order = $order -> values();
            if ($request -> doc_type == ConstantHelper::LAND_LEASE) {
                $order = SaleModuleHelper::sortByDueDateLogic($order);
                $order = $order->groupBy('lease_id')
                    ->flatMap(function ($group) {
                        // Optionally, sort each group further if needed
                        return $group;
                    });
            }
            return response() -> json([
                'data' => $order
            ]);
        } catch(Exception $ex) {
            return response() -> json([
                'message' => 'Some internal error occurred',
                'error' => $ex -> getMessage() . $ex -> getFile() . $ex -> getLine()
            ]);
        }
    }

    //Function to get all items of sales module depending upon the doc type - order , invoice, delivery note
    public function processPulledItems(Request $request)
    {
        try {
            // $processedCollection = new Collection();
            $itemIds = $request -> items_id;
            $modelName = null;
            $headers = [];
            if ($request -> doc_type === ConstantHelper::SO_SERVICE_ALIAS) {
                $modelName = resolve("App\\Models\\ErpSaleOrder");
            } else if ($request -> doc_type === ConstantHelper::SI_SERVICE_ALIAS || $request -> doc_type === ConstantHelper::DELIVERY_CHALLAN_SERVICE_ALIAS) {
                $modelName = resolve("App\\Models\\ErpSaleInvoice");
            } else {
                $modelName = null;
            }
            if (isset($modelName)) {

                // if ($request -> doc_type === ConstantHelper::SO_SERVICE_ALIAS) {
                //     $soItemGrouped = DB::table(function ($query) use ($itemIds) {
                //         $query->from('erp_so_items')
                //             ->join('erp_so_item_attributes', 'erp_so_items.id', '=', 'erp_so_item_attributes.so_item_id')
                //             ->select(
                //                 'erp_so_items.id as so_item_id',
                //                 'erp_so_items.item_id',
                //                 'erp_so_items.rate',
                //                 'erp_so_items.uom_id',
                //                 DB::raw("GROUP_CONCAT(CONCAT(erp_so_item_attributes.item_attribute_id, ':', erp_so_item_attributes.attribute_value) ORDER BY erp_so_item_attributes.item_attribute_id SEPARATOR ', ') as attributes"),
                //                 'erp_so_items.dnote_qty',
                //                 'erp_so_items.order_qty'
                //             )
                //             ->whereIn('erp_so_items.id',$itemIds)
                //             ->groupBy('erp_so_items.id', 'erp_so_items.item_id','erp_so_items.uom_id', 'erp_so_items.rate');
                //     })
                //     ->select(
                //         'item_id',
                //         'uom_id',
                //         'attributes',
                //         'rate',
                //         DB::raw("SUM(order_qty-dnote_qty) as total_qty")
                //     )
                //     ->groupBy('item_id', 'uom_id', 'attributes', 'rate')
                //     ->get();

                //     foreach ($soItemGrouped as $itemKey => &$item) {
                //         $totalDiscountArray = [];
                //         $totalDiscountAmount = 0;
                //         $soItems = ErpSoItem::whereIn('id', $itemIds) -> with('header') -> where([
                //             ['item_id', $item -> item_id],
                //             ['uom_id', $item -> uom_id],
                //             ['rate', $item -> rate]
                //         ]) -> get();
                //         $soItems = $soItems -> filter(function ($soItem) use($item) {
                //             return $soItem -> attributes_array() ?-> first() ?-> attributes == $item -> attributes;
                //         });
                //         $firstSoItem = $soItems ?-> first();
                //         $soDetails = [];
                //         foreach ($soItems as $soItem) {
                //             array_push($soDetails, [
                //                 'id' => $soItem -> id,
                //                 'balance_qty' => $soItem -> balance_qty,
                //                 'sale_order_id' => $soItem -> sale_order_id,
                //                 'book_code' => $soItem ?-> header ?-> book_code,
                //                 'document_number' => $soItem ?-> header ?-> document_number,
                //                 'document_date' => $soItem ?-> header ?-> document_date,
                //             ]);
                //             //Add a header row
                //             $headerDiscountArray = $soItem -> header_discounts ?? [];
                //             //header discount
                //             foreach ($headerDiscountArray as $headerDiscount) {
                //                 $existingIndex = null;
                //                 foreach ($totalDiscountArray as $existingDiscountIndex => $existingDiscount) {
                //                     if ($existingDiscount['ted_id'] == $headerDiscount['id']) {
                //                         $existingIndex = $existingDiscountIndex;
                //                         break;
                //                     }
                //                 }
                //                 if (isset($existingIndex)) {
                //                     $totalDiscountArray[$existingDiscountIndex]['ted_amountamount'] += $headerDiscount['amount'];
                //                     $totalDiscountAmount += $headerDiscount['amount'];
                //                 } else {
                //                     $discount = DiscountMaster::find($headerDiscount['id']);
                //                     array_push($totalDiscountArray, [
                //                         'ted_id' => $headerDiscount['id'],
                //                         'ted_amount' => $headerDiscount['amount'],
                //                         'ted_name' => $discount ?-> name
                //                     ]);
                //                     $totalDiscountAmount += $headerDiscount['amount'];
                //                 }
                //             }
                //             //item discount -> ted
                //             foreach ($soItem -> discount_ted as $itemDiscount) {
                //                 $existingDiscountIndex = null;
                //                 foreach ($totalDiscountArray as $headerDiscountIndex => $headerDiscount) {
                //                     if ($headerDiscount['ted_id'] == $itemDiscount['ted_id']) {
                //                         $existingDiscountIndex = $headerDiscountIndex;
                //                         break;
                //                     }
                //                 }
                //                 if (isset($existingDiscountIndex)) {
                //                     $totalDiscountArray[$existingDiscountIndex]['ted_amount'] += $itemDiscount -> ted_amount;
                //                     $totalDiscountAmount += $itemDiscount -> ted_amount;

                //                 } else {
                //                     $discount = DiscountMaster::find($itemDiscount -> ted_id);
                //                     array_push($totalDiscountArray, [
                //                         'ted_id' => $itemDiscount -> ted_id,
                //                         'ted_amount' => $itemDiscount -> ted_amount,
                //                         'ted_name' => $discount ?-> name
                //                     ]);
                //                     $totalDiscountAmount += $itemDiscount -> ted_amount;
                //                 }
                //             }
                //         }
                //         if (isset($firstSoItem)) {
                //             $firstSoItem->load([
                //                 'header.customer.currency',
                //                 'header.customer.payment_terms',
                //                 'item.specifications',
                //                 'item.alternateUoms.uom',
                //                 'item.uom', 
                //                 'item.hsn'
                //             ]);
                //             $item -> discount_ted = $totalDiscountArray;
                //             $item -> item = $firstSoItem -> item;
                //             $item -> item_attributes_array = $firstSoItem -> item_attributes_array();
                //             $item -> balance_qty = $item -> total_qty;
                //             $item -> stock_qty = $item -> total_qty;
                //             $item -> actual_qty = $item -> total_qty;
                //             $item -> item_discount_amount = $totalDiscountAmount;
                //             $item -> header_discount_amount = 0;
                //             $item -> tax_amount = 0;
                //             $item -> so_details = $soDetails;
                //             if ($itemKey == 0) {
                                
                //                 $processedCollection -> push([
                //                     'id' => 1,
                //                     'customer_code' => $firstSoItem ?-> header ?-> customer_code,
                //                     'customer_id' => $firstSoItem ?-> header ?-> customer_id,
                //                     'consignee_name' => $firstSoItem ?-> header ?-> consignee_name,
                //                     'customer' => $firstSoItem ?-> header ?-> customer,
                //                     'billing_address_details' => $firstSoItem ?-> header ?-> billing_address_details,
                //                     'shipping_address_details' => $firstSoItem ?-> header ?-> shipping_address_details,
                //                     'payment_term_id' => $firstSoItem ?-> header ?-> payment_term_id,
                //                     'currency_id' => $firstSoItem ?-> header ?-> currency_id,
                //                     'document_type' => $firstSoItem ?-> header ?-> document_type,
                //                     'items' => collect([]),
                //                     'discount_ted' => collect([]),
                //                     'expense_ted' => collect([])
                //                 ]);
                //             }
                //             $processedCollection -> first()['items'] -> push($item);
                //         }
                        
                //     }
                // }

                $headers = $modelName::with(['discount_ted', 'expense_ted', 'billing_address_details', 'shipping_address_details']) -> with('customer', function ($sQuery) {
                    $sQuery -> with(['currency', 'payment_terms']);
                }) -> with('items', function ($itemQuery) use($request) {
                    $itemQuery -> whereIn('id', $request -> items_id) -> with(['discount_ted', 'tax_ted']) -> with(['item' => function ($itemQuery) {
                        $itemQuery -> with(['specifications', 'alternateUoms.uom', 'uom', 'hsn']);
                    }]);
                }) -> whereIn('id', $request -> order_id) -> get();
                foreach ($headers as $header) {
                    if ($modelName::class == "App\\Models\\ErpSaleInvoice") {
                        $saleOrderItems = $header -> sale_order_items();
                        foreach ($saleOrderItems as &$saleOrderItem) {
                            $saleOrderItem -> actual_qty = $saleOrderItem -> order_qty;
                        }
                    }
                    foreach ($header -> items as $orderItemKey => &$orderItem) {
                        $orderItem -> stock_qty = $orderItem -> getStockBalanceQty();
                        $orderItem -> item_attributes_array = $orderItem -> item_attributes_array();
                        if (isset($saleOrderItems[$orderItemKey])) {
                            $header -> items[$orderItemKey] = $saleOrderItems[$orderItemKey];
                            $header -> items[$orderItemKey] -> id = $orderItem -> id;
                            $header -> items[$orderItemKey] -> item_attributes_array = $orderItem -> item_attributes_array();
                        }
                    }
                }
            } else {
                if ($request -> doc_type === ConstantHelper::LAND_LEASE) {
                    $headers = LandLease::with(['customer.currency', 'customer.payment_terms'])
                    // ->whereHas('schedule', function ($subQuery) use ($request) {
                    //     $subQuery->whereIn('id', $request->items_id);
                    // })
                    ->with(['schedule' => function ($itemQuery) use ($request) {
                        $itemQuery->whereIn('id', $request->items_id);
                        // Uncomment below if needed, ensure relationships are correctly defined
                        // ->with(['discount_ted', 'tax_ted'])
                        // ->with(['item' => function ($itemQuery) {
                        //     $itemQuery->with(['specifications', 'alternateUoms.uom', 'uom', 'hsn']);
                        // }]);
                    }]) -> with('plots')
                    ->whereIn('id', $request->order_id)
                    ->get();

                    if ($headers && count($headers) > 0) {
                        foreach ($headers as &$header) {
                            //Customer Details
                            $header -> customer_code = $header -> customer ?-> customer_code;
                            $header -> consignee_name = '';
                            $header -> currency_code = $header -> currency ?-> short_name;
                            $header -> payment_term_code = $header -> payment_terms ?-> name;
                            //Address details
                            $header -> shipping_address_details = $header -> address;
                            $header -> billing_address_details = $header -> address;
                            //Other
                            $header -> document_type = '';
                            $header -> book_code = $header -> series ?-> book_code;
                            $header -> document_number = $header -> document_no;
                            $header -> discount_ted = [];
                            $header -> expense_ted = [];
                            $header -> document_type = ConstantHelper::LAND_LEASE;
                            //Item or Detail details
                            $items = [];
                            $landParcelId = $header -> plots ?-> first() -> land_parcel_id;
                            $landParcel = LandParcel::find($landParcelId);
                            $itemDetails = json_decode($landParcel -> service_item, true);
                            foreach ($header -> schedule as $headerItem) {
                                $itemDetail = null;
                                if (isset($landParcel)) {
                                    $itemDetail = new stdClass();
                                    $itemDetail -> id = $headerItem -> id;
                                    $itemDetail -> balance_qty = 1;
                                    $itemDetail -> actual_qty = 1;
                                    $itemDetail -> stock_qty = 1;
                                    $itemDetail -> remarks = null;
                                    $itemDetail -> discount_ted = [];
                                    $itemDetail -> tax_ted = [];
                                    $itemDetail -> header_discount_amount = 0;
                                    $itemDetail -> item_discount_amount = 0;
                                    $itemDetail -> item_expense_amount = 0;
                                    $itemDetail -> tax_amount = 0;
                                    $itemDetail -> header_expense_amount = 0;
                                    $itemDetail -> rate = round($headerItem -> installment_cost - $headerItem -> invoice_amount, 2);
                                    
                                    if (isset($itemDetails) && count($itemDetails) > 0) {
                                        $serviceItem = array_filter($itemDetails, function ($leaseItem) {
                                            return $leaseItem["'servicetype'"] == ConstantHelper::LAND_LEASE;
                                        });
                                        if ($serviceItem && count($serviceItem) > 0) {
                                            $serviceItem = array_values($serviceItem);
                                            $item = Item::where('item_code', $serviceItem[0]["'servicecode'"]) -> where('type', ConstantHelper::SERVICE) -> with(['specifications', 'alternateUoms.uom', 'uom', 'hsn']) -> first();
                                            if (isset($item)) {
                                                $itemDetail -> item = $item;
                                                $itemDetail -> due_date = $headerItem -> due_date;
                                                $itemDetail -> item_id = $item -> id;
                                                $itemDetail -> item_lease_type = ConstantHelper::LAND_LEASE;
                                                $itemDetail -> item_attributes_array = SaleModuleHelper::item_attributes_array($item -> id, $serviceItem[0]["'attributes'"] ?? []);
                                                $itemDetail -> land_parcel_display = $landParcel -> name;
                                                $plots = '';
                                                foreach ($header -> plots as $headerPlotIndex => $headerPlot) {
                                                    $plots .= (($headerPlotIndex !== 0 ? ',' : '') . ($headerPlot ?-> plot ?-> plot_name));
                                                }
                                                $itemDetail -> land_plots_display = $plots;
                                                //Attributes
                                                // $itemAttributes = ErpItemAttribute::where('item_id', $item -> id) -> get();
                                                // foreach ($itemAttributes as &$attribute) {
                                                //     $attributesArray = array();
                                                //     $attribute_ids = json_decode($attribute -> attribute_id);
                                                //     $attribute -> group_name = $attribute -> group ?-> name;
                                                //     foreach ($attribute_ids as $attributeValue) {
                                                //         $attributeValueData = ErpAttribute::where('id', $attributeValue) -> select('id', 'value') -> where('status', 'active') -> first();
                                                //         if (isset($attributeValueData))
                                                //         {
                                                //             $attributeValueData -> selected = false;
                                                //             array_push($attributesArray, $attributeValueData);
                                                //         }
                                                //     }
                                                //    $attribute -> values_data = $attributesArray;
                                                //    $attribute -> only(['id','group_name', 'values_data']);
                                                // }
                                                // $itemDetail -> item_attributes_array = $itemAttributes;
                                            }
                                        }
                                    }
                                }
                                array_push($items, $itemDetail);
                            }
                            $itemIds = isset($request -> items_id) ? $request -> items_id : [];
                            if (isset($landParcel) && in_array(0, $itemIds)) {
                                $securityItem = array_filter($itemDetails, function ($leaseItem) {
                                    return $leaseItem["'servicetype'"] === 'security';
                                });
                                if ($securityItem && count($securityItem) > 0) {
                                    $securityItem = array_values($securityItem);
                                    $item = Item::where('item_code', $securityItem[0]["'servicecode'"]) -> where('type', ConstantHelper::SERVICE) -> with(['specifications', 'alternateUoms.uom', 'uom', 'hsn']) -> first();
                                    if (isset($item)) {
                                        $itemDetail = new stdClass();
                                        $itemDetail -> id = 0;
                                        $itemDetail -> balance_qty = 1;
                                        $itemDetail -> actual_qty = 1;
                                        $itemDetail -> stock_qty = 1;
                                        $itemDetail -> remarks = null;
                                        $itemDetail -> discount_ted = [];
                                        $itemDetail -> tax_ted = [];
                                        $itemDetail -> item_lease_type = "security";
                                        $itemDetail -> header_discount_amount = 0;
                                        $itemDetail -> item_discount_amount = 0;
                                        $itemDetail -> item_expense_amount = 0;
                                        $itemDetail -> tax_amount = 0;
                                        $itemDetail -> header_expense_amount = 0;
                                        $itemDetail -> rate = $header -> security_deposit - $header -> invoice_security_deposit;
                                        $itemDetail -> item = $item;
                                        $itemDetail -> due_date = $header -> document_date;
                                        $itemDetail -> item_id = $item -> id;
                                        $itemDetail -> item_attributes_array = SaleModuleHelper::item_attributes_array($item -> id, $securityItem[0]["'attributes'"] ?? []);;
                                        $itemDetail -> land_parcel_display = $landParcel -> name;
                                        $plots = '';
                                        foreach ($header -> plots as $headerPlotIndex => $headerPlot) {
                                            $plots .= (($headerPlotIndex !== 0 ? ',' : '') . ($headerPlot ?-> plot ?-> plot_name));
                                        }
                                        $itemDetail -> land_plots_display = $plots;
                                        //Attributes
                                        // $itemAttributes = ErpItemAttribute::where('item_id', $item -> id) -> get();
                                        // foreach ($itemAttributes as &$attribute) {
                                        //     $attributesArray = array();
                                        //     $attribute_ids = json_decode($attribute -> attribute_id);
                                        //     $attribute -> group_name = $attribute -> group ?-> name;
                                        //     foreach ($attribute_ids as $attributeValue) {
                                        //         $attributeValueData = ErpAttribute::where('id', $attributeValue) -> select('id', 'value') -> where('status', 'active') -> first();
                                        //         if (isset($attributeValueData))
                                        //         {
                                        //             $attributeValueData -> selected = false;
                                        //             array_push($attributesArray, $attributeValueData);
                                        //         }
                                        //     }
                                        //    $attribute -> values_data = $attributesArray;
                                        //    $attribute -> only(['id','group_name', 'values_data']);
                                        // }
                                        // $itemDetail -> item_attributes_array = $itemAttributes;
                                        array_push($items, $itemDetail);
                                    }
                                }
                            }
                            $header -> items = $items;
                            
                        }
                    }
                }
            }
            // dd($headers);
            return response() -> json([
                'message' => 'Data found',
                'data' => $headers
            ]);
        } catch(Exception $ex) {
            return response() -> json([
                'message' => 'Some internal error occurred',
                'error' => $ex -> getMessage()
            ]);
        }
    }

    // genrate pdf
    public function generatePdf(Request $request, $id,$pattern)
    {
        $user = Helper::getAuthenticatedUser();

        $organization = Organization::where('id', $user->organization_id)->first();
        $organizationAddress = Address::with(['city', 'state', 'country'])
            ->where('addressable_id', $user->organization_id)
            ->where('addressable_type', Organization::class)
            ->first();
        
        $order = ErpSaleInvoice::with(
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
            $query -> with('discount_ted', 'tax_ted', 'item_locations') -> with(['item' => function ($itemQuery) {
                $itemQuery -> with(['specifications', 'alternateUoms.uom', 'uom']);
            }]);
        })
        -> find($id);

        $shippingAddress = $order->shipping_address_details;
        $type = ConstantHelper::SERVICE_LABEL[$order->document_type];
        
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

        $pdf = PDF::loadView(

            // return view(
            'pdf.sales-document',
            [
                'pattern' => $pattern,
                'type' => $pattern,
                'order' => $order,
                'user' => $user,
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
            ]
        );

        return $pdf->stream('Sales-Invoice.pdf');
    }

    public function getPostingDetails(Request $request)
    {
        try {
            $data = FinancialPostingHelper::financeVoucherPosting($request -> book_id ?? 0, $request -> document_id ?? 0, "get");
            return response() -> json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch(Exception $ex) {
            return response() -> json([
                'status' => 'exception',
                'message' => 'Some internal error occured',
                'error' => $ex -> getMessage() . $ex -> getFile() . $ex -> getLine()
            ]);
        }
    }

    public function postInvoice(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = FinancialPostingHelper::financeVoucherPosting($request -> book_id ?? 0, $request -> document_id ?? 0, "post");
            if ($data['status']) {
                DB::commit();
            } else {
                DB::rollBack();
            }
            return response() -> json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch(Exception $ex) {
            DB::rollBack();
            return response() -> json([
                'status' => 'exception',
                'message' => 'Some internal error occured',
                'error' => $ex -> getMessage()
            ]);
        }
    }

    public function revokeSalesInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $saleDocument = ErpSaleInvoice::find($request -> id);
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
