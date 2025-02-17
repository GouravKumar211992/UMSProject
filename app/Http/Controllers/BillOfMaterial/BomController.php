<?php

namespace App\Http\Controllers\BillOfMaterial;
use App\Helpers\ConstantHelper;
use App\Helpers\Helper;
use App\Helpers\ItemHelper;
use App\Helpers\NumberHelper;
use App\Helpers\CurrencyHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\BomRequest;
use App\Models\Address;
use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Bom;
use App\Models\BomAttribute;
use App\Models\BomDetail;
use App\Models\BomMedia;
use App\Models\BomOverhead;
use App\Models\Book;
use App\Models\Item;
use App\Models\NumberPattern;
use App\Models\Organization;
use App\Models\ItemAttribute;
use App\Models\BomNormsCalculation;
use Auth;
use DB;
use Illuminate\Http\Request;
use PDF;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ServiceParametersHelper;

class BomController extends Controller
{
    # Bill of material list
    public function index(Request $request)
    {
        $parentUrl = request()->segments()[0];
        if (request()->ajax()) {
            $user = Helper::getAuthenticatedUser();
            $type = $request->type == 'commercial-bom' ? ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS : ConstantHelper::BOM_SERVICE_ALIAS;
            $boms = Bom::withDefaultGroupCompanyOrg()
                    ->where('type',$type)
                    ->where(function ($query) use ($user) {
                        $query->where('document_status', '!=', ConstantHelper::DRAFT)
                              ->orWhere('created_by', $user->id);
                    })
                    ->latest()
                    ->get();
            return DataTables::of($boms)
                ->addIndexColumn()
                ->editColumn('document_status', function ($row) use ($type) {
                    $statusClasss = ConstantHelper::DOCUMENT_STATUS_CSS_LIST[$row->document_status];
                    $displayStatus = $row->display_status;
                    if($type == 'commercial-bom') {
                        $route = url('commercial-bom').'/edit/'.$row->id;    
                    } else {
                        $route = route('bill.of.material.edit', $row->id);    
                    }
                    return "<div style='text-align:right;'>
                        <span class='badge rounded-pill $statusClasss badgeborder-radius'>$displayStatus</span>
                        <div class='dropdown' style='display:inline;'>
                            <button type='button' class='btn btn-sm dropdown-toggle hide-arrow py-0 p-0' data-bs-toggle='dropdown'>
                                <i data-feather='more-vertical'></i>
                            </button>
                            <div class='dropdown-menu dropdown-menu-end'>
                                <a class='dropdown-item' href='" . $route . "'>
                                    <i data-feather='edit-3' class='me-50'></i>
                                    <span>View/ Edit Detail</span>
                                </a>
                            </div>
                        </div>
                    </div>";
                })
                ->addColumn('book_name', function ($row) {
                    return $row->book ? $row->book?->book_code : 'N/A';
                })
                ->editColumn('document_date', function ($row) {
                    return $row->getFormattedDate('document_date') ?? 'N/A';
                })
                ->editColumn('revision_number', function ($row) {
                    return strval($row->revision_number);
                })
                ->addColumn('item_code', function ($row) {
                    return $row->item ? $row->item?->item_code : 'N/A';
                })
                ->addColumn('item_name', function ($row) {
                    return $row->item ? $row->item?->item_name : 'N/A';
                })
                ->addColumn('attributes', function ($row) {
                   $attributes = $row->bomAttributes;
                   $html = '';
                   foreach($attributes as $attribute) {
                   $attr = AttributeGroup::where('id', intval($attribute->attribute_name))->first();
                   $attrValue = Attribute::where('id', intval($attribute->attribute_value))->first();
                       if ($attr && $attrValue) { 
                            $html .= "<span class='badge rounded-pill badge-light-primary'><strong>{$attr->name}</strong>: {$attrValue->value}</span>";
                       } else {
                            // $html .= "<span class='badge rounded-pill badge-light-secondary'><strong>Attribute not found</strong></span>";
                       }
                   }
                    return $html;
                })
                ->addColumn('uom_name', function ($row) {
                    return $row->uom ? $row->uom?->name : 'N/A';
                })
                ->addColumn('components', function ($row) {
                    return $row->bomItems->count();
                })
                ->editColumn('total_item_value', function ($row) {
                    return number_format($row->total_item_value,2);
                })
                ->addColumn('wastage', function ($row) {
                    return number_format(($row->item_waste_amount + $row->header_waste_amount),2);
                })
                ->addColumn('overhead', function ($row) {
                    return number_format(($row->item_overhead_amount + $row->header_overhead_amount),2);
                })
                ->addColumn('total_cost', function ($row) {
                    return number_format(($row->total_item_value + $row->item_waste_amount + $row->header_waste_amount + $row->item_overhead_amount + $row->header_overhead_amount),2);
                })
                ->rawColumns(['document_status', 'attributes'])
                ->make(true);
        }
        $servicesAliasParam = request()->segments()[0] == 'commercial-bom' ? ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS : ConstantHelper::BOM_SERVICE_ALIAS;
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentUrl, $servicesAliasParam);
        return view('billOfMaterial.index', ['servicesBooks' => $servicesBooks]);
    }

    # Bill of material Create
    public function create(Request $request)
    {
        $parentUrl = request()->segments()[0];
        $servicesAliasParam = request()->segments()[0] == 'commercial-bom' ? ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS : ConstantHelper::BOM_SERVICE_ALIAS;
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentUrl, $servicesAliasParam);

        if (count($servicesBooks['services']) == 0) {
            return redirect()->back();
        }
        $productionTypes = ['In-house','Job Work'];
        $books = Helper::getBookSeriesNew($servicesAliasParam, $parentUrl, true)->get();
        $wasteTypes = ConstantHelper::DISCOUNT_TYPES;
        return view('billOfMaterial.create', [
            'wasteTypes' => $wasteTypes,
            'books' => $books,
            'productionTypes' => $productionTypes,
            'servicesBooks' => $servicesBooks,
            'serviceAlias' => $servicesAliasParam
        ]);
    }

    #Bill of material store
    public function store(BomRequest $request)
    {
        DB::beginTransaction();
        try {
            # Bom Header save
            $user = Helper::getAuthenticatedUser();
            $organization = Organization::where('id', $user->organization_id)->first(); 
            $bom = new Bom;
            $bom->type = $request->type ?? ConstantHelper::BOM_SERVICE_ALIAS; 
            $bom->organization_id = $organization->id;
            $bom->group_id = $organization->group_id;
            $bom->company_id = $organization->company_id;
            $bom->uom_id = $request->uom_id;
            $bom->production_type = $request->production_type;
            $bom->item_id = $request->item_id;
            $bom->item_code = $request->item_code;
            $bom->item_name = $request->item_name;
            $bom->revision_number = $request->revision_number ?? 0;
            // $bom->status = $request->status;
            $bom->remarks = $request->remarks;
            # Extra Column
            $document_number = $request->document_number ?? null;
            
            /**/
            $numberPatternData = Helper::generateDocumentNumberNew($request->book_id, $request->document_date);
            if (!isset($numberPatternData)) {
                return response()->json([
                    'message' => "Invalid Book",
                    'error' => "",
                ], 422);
            }
            $document_number = $numberPatternData['document_number'] ? $numberPatternData['document_number'] : $document_number;
            $regeneratedDocExist = Bom::withDefaultGroupCompanyOrg()->where('book_id',$request->book_id)
                ->where('document_number',$document_number)->first();
                //Again check regenerated doc no
                if (isset($regeneratedDocExist)) {
                    return response()->json([
                        'message' => ConstantHelper::DUPLICATE_DOCUMENT_NUMBER,
                        'error' => "",
                    ], 422);
                }

            $bom->doc_number_type = $numberPatternData['type'];
            $bom->doc_reset_pattern = $numberPatternData['reset_pattern'];
            $bom->doc_prefix = $numberPatternData['prefix'];
            $bom->doc_suffix = $numberPatternData['suffix'];
            $bom->doc_no = $numberPatternData['doc_no'];
            /**/

            $bom->book_id = $request->book_id;
            $bom->book_code = $request->book_code;
            $bom->document_number = $document_number;
            $bom->document_date = $request->document_date ?? now();
            $bom->save();
            
            if($bom->type == ConstantHelper::BOM_SERVICE_ALIAS) {
                $quote_bom_id = $request->quote_bom_id;
                $quoteBom = Bom::find($quote_bom_id);
                if($quoteBom) {
                    $quoteBom->production_bom_id = $bom->id;
                    $quoteBom->save();
                }
            }

            # Save header attribute
            foreach($bom->item->itemAttributes as  $key => $itemAttribute) {
                $key = $key + 1;
                $headerAttr = @$request->all()['attributes'][$key];
                if (isset($headerAttr['attr_group_id'][$itemAttribute->attribute_group_id])) {
                    $bomAttr = new BomAttribute;
                    $bomAttr->bom_id = $bom->id;
                    $bomAttr->item_attribute_id = $itemAttribute->id;
                    $bomAttr->item_id = $bom->item->id;
                    $bomAttr->type = 'H';
                    $bomAttr->item_code = $request->item_code;
                    $bomAttr->attribute_name = $itemAttribute->attribute_group_id;
                    $bomAttr->attribute_value = @$headerAttr['attr_group_id'][$itemAttribute->attribute_group_id]['attr_name'];
                    $bomAttr->save();
                }
            }

            if (isset($request->all()['components'])) {
                $consumptionMethod = $request->consumption_method;
                foreach($request->all()['components'] as $component) {
                    # Bom Detail Save
                    $bomDetail = new BomDetail;
                    $bomDetail->bom_id = $bom->id;
                    $bomDetail->item_id = $component['item_id'];
                    $bomDetail->item_code = $component['item_code'];
                    $bomDetail->qty = $component['qty'] ?? 0.00;
                    $bomDetail->uom_id = $component['uom_id'] ?? null;
                    $bomDetail->waste_perc = $component['waste_perc'] ?? 0.00;
                    $bomDetail->waste_amount = $component['waste_amount'] ?? 0.00;
                    $bomDetail->superceeded_cost = $component['superceeded_cost'] ?? 0.00;
                    $bomDetail->item_cost = $component['item_cost'] ?? 0.00;
                    $bomDetail->item_value = $component['item_value'] ?? 0.00;
                    $bomDetail->overhead_amount = $component['overhead_amount'] ?? 0.00;
                    $bomDetail->total_amount = $component['item_total_cost'] ?? 0.00;
                    $bomDetail->sub_section_id = $component['sub_section_id'] ?? null;
                    $bomDetail->section_name = $component['section_name'] ?? null;
                    $bomDetail->sub_section_name = $component['sub_section_name'] ?? null;
                    $bomDetail->station_name = $component['station_name'] ?? null;
                    $bomDetail->station_id = $component['station_id'] ?? null;
                    $bomDetail->remark = $component['remark'] ?? null;
                    $bomDetail->save();

                    # Store Norms
                    if($consumptionMethod != 'manual') {
                        # Norms
                        $normData = [
                            'bom_id' => $bom->id,
                            'bom_detail_id' => $bomDetail->id,
                        ];
                        $updateData = [
                            'qty_per_unit' => $component['qty_per_unit'] ?? 0.00,
                            'total_qty' => $component['total_qty'] ?? 0.00,
                            'std_qty' => $component['std_qty'] ?? 0.00,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        if($updateData['qty_per_unit'] && $updateData['total_qty'] && $updateData['std_qty']){
                            BomNormsCalculation::updateOrCreate($normData, $updateData);
                        }
                    }
                    // else {
                    //     # Manual
                    // }
                    #Save component Attr
                    foreach($bomDetail->item->itemAttributes as $itemAttribute) {
                        if (isset($component['attr_group_id'][$itemAttribute->attribute_group_id])) {
                            $bomAttr = new BomAttribute;
                            $bomAttr->bom_id = $bom->id;
                            $bomAttr->bom_detail_id = $bomDetail->id;
                            $bomAttr->item_attribute_id = $itemAttribute->id;
                            $bomAttr->type = 'D';
                            $bomAttr->item_code = $component['item_code'];
                            $bomAttr->item_id = $component['item_id'];
                            $bomAttr->attribute_name = $itemAttribute->attribute_group_id;
                            $bomAttr->attribute_value = @$component['attr_group_id'][$itemAttribute->attribute_group_id]['attr_name'];
                            $bomAttr->save();
                        }
                    }

                    #Save item overhead
                    if (isset($component['overhead'])) {
                        foreach($component['overhead'] as $overhead) {
                            if(isset($overhead['amnt']) && $overhead['amnt']) {
                                $bomOverhead = new BomOverhead;
                                $bomOverhead->bom_id = $bom->id;
                                $bomOverhead->bom_detail_id = $bomDetail->id;
                                $bomOverhead->type = 'D';
                                $bomOverhead->overhead_description = $overhead['description'];
                                $bomOverhead->ledger_name = $overhead['leadger'];
                                $bomOverhead->overhead_amount = $overhead['amnt'] ?? 0.00;
                                $bomOverhead->save();
                            }
                        }
                    }
                }
            } else {
                DB::rollBack();
                return response()->json([
                        'message' => 'Please add atleast one row in component table.',
                        'error' => "",
                    ], 422);
            }

            #Save summary overhead
            if (isset($request->overhead) && $bom->id) {
                foreach($request->overhead as $overSummary) {
                    if($overSummary['description'] && $overSummary['amnt']) {
                        $bomOverhead = new BomOverhead;
                        $bomOverhead->bom_id = $bom->id;
                        // $bomOverhead->bom_detail_id = $bomDetail->id;
                        $bomOverhead->type = 'H';
                        $bomOverhead->overhead_description = $overSummary['description'];
                        $bomOverhead->ledger_name = $overSummary['leadger'];
                        $bomOverhead->overhead_amount = $overSummary['amnt'] ?? 0.00;
                        $bomOverhead->save();
                    }
                }
            }
            
            /*Update Bom header*/
            $bom->header_waste_perc = $request->waste_perc ?? 0.00;
            $bom->header_waste_amount = $request->waste_amount ?? 0.00;
            $bom->total_item_value = $bom->bomItems()->sum('item_value') ?? 0.00;
            $bom->item_waste_amount = $bom->bomItems()->sum('waste_amount') ?? 0.00;
            $bom->item_overhead_amount = $bom->bomComponentOverheadItems()->sum('overhead_amount') ?? 0.00;
            $bom->header_overhead_amount = $bom->bomOverheadItems()->where('type','H')->sum('overhead_amount') ?? 0.00;
            $bom->save();

            /*Create document submit log*/
            $modelName = get_class($bom);
            $totalValue = $bom->total_value ?? 0;
            if ($request->document_status == ConstantHelper::SUBMITTED) {
                $bookId = $bom->book_id; 
                $docId = $bom->id;
                $remarks = $bom->remarks;
                $attachments = $request->file('attachment');
                $currentLevel = $bom->approval_level;
                $revisionNumber = $bom->revision_number ?? 0;
                $actionType = 'submit'; // Approve // reject // submit
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $totalValue, $modelName);
            }

            if ($request->document_status == 'submitted') {
                // $totalValue = $bom->total_value ?? 0;
                // $document_status = Helper::checkApprovalRequired($request->book_id,$totalValue);
                $bom->document_status = $approveDocument['approvalStatus'] ?? $request->document_status;
            } else {
                $bom->document_status = $request->document_status ?? ConstantHelper::DRAFT;
            }

            /*Bom Attachment*/
            if ($request->hasFile('attachment')) {
                $mediaFiles = $bom->uploadDocuments($request->file('attachment'), 'bom', false);
            }

            $bom->save();

            DB::commit();

            return response()->json([
                'message' => 'Record created successfully',
                'data' => $bom,
            ]);   
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error occurred while creating the record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    # On change item code
    public function changeItemCode(Request $request)
    {
        $attributeGroups = AttributeGroup::withDefaultGroupCompanyOrg()
                        ->with('attributes')->where('status', ConstantHelper::ACTIVE)->get();
        $item = Item::find($request->item_id);
        $specifications = collect();
        if($item) {
            $item->uom;
            $specifications = $item->specifications()->whereNotNull('value')->get();
        }
        $html = view('billOfMaterial.partials.header-attribute',compact('item','attributeGroups','specifications'))->render();
        return response()->json(['data' => ['html' => $html, 'item' => $item], 'status' => 200, 'message' => 'fetched.']);
    }

    # On change item attribute
    public function getItemAttribute(Request $request)
    {

        $rowCount = intval($request->rowCount) ?? 1;
        $item = Item::find($request->item_id);
        $selectedAttr = $request->selectedAttr ? json_decode($request->selectedAttr,true) : [];

        $detailItemId = $request->bom_detail_id ?? null;
        $itemAttIds = [];
        if($detailItemId) {
            $detail = BomDetail::find($detailItemId);
            if($detail) {
                $itemAttIds = $detail->attributes()->pluck('item_attribute_id')->toArray();
            }
        }
        $itemAttributes = collect();
        if(count($itemAttIds)) {
            $itemAttributes = $item?->itemAttributes()->whereIn('id',$itemAttIds)->get();
        } else {
            $itemAttributes = $item?->itemAttributes;
        }

        $html = view('billOfMaterial.partials.comp-attribute',compact('item','rowCount','selectedAttr','itemAttributes'))->render();
        $hiddenHtml = '';
        foreach ($item->itemAttributes as $attribute) {
                $selected = '';
                foreach ($attribute->attributes() as $value){
                    if (in_array($value->id, $selectedAttr)){
                        $selected = $value->id;
                    }
                }
            $hiddenHtml .= "<input type='hidden' name='components[$rowCount][attr_group_id][$attribute->attribute_group_id][attr_name]' value=$selected>";
        }
        return response()->json(['data' => ['attr' => $item->itemAttributes->count(),'html' => $html, 'hiddenHtml' => $hiddenHtml], 'status' => 200, 'message' => 'fetched.']);
    }

    # Add item row
    public function addItemRow(Request $request)
    {
        $item = json_decode($request->item,true) ?? [];
        $componentItem = json_decode($request->component_item,true) ?? [];
        $moduleType = $request->type ?? null;
        /*Check header mandatory*/
        if ($item['selectedAttrRequired'] && $item['item_code']) {
            if(!$item['item_code'] || $item['selectedAttrRequired']) {
                return response()->json(['data' => ['html' => ''], 'status' => 422, 'message' => 'Please fill all the header details before adding components.']);
            }
        }
        
        if(!$item['item_id']) {
            return response()->json(['data' => ['html' => ''], 'status' => 422, 'message' => 'Please fill all the header details before adding components.']);
        }
        $headerSelectedAttr = json_decode($request->header_attr,true) ?? []; 
        $attributes = [];
        if(count($headerSelectedAttr)) {
               foreach($headerSelectedAttr as $headerAttr) {
                $itemAttr = ItemAttribute::where("item_id",$item['item_id'])
                                ->where("attribute_group_id",$headerAttr['attr_name'])
                                ->first();
                $attributes[] = ['attribute_id' => $itemAttr?->id, 'attribute_value' => $headerAttr['attr_value']];
               }
        }
        $bomExists = ItemHelper::checkItemBomExists($item['item_id'], $attributes, $moduleType);
        if ($bomExists['bom_id']) {
            $bomExists['message'] = "Bom already exists for this item.";
            return response()->json(['data' => ['html' => ''], 'status' => 422, 'message' => $bomExists['message']]);
        }
        /*Check last tr in table mandatory*/
        if(isset($componentItem['attr_require']) && isset($componentItem['item_id']) && $componentItem['row_length']) {
            if (($componentItem['attr_require'] == true || !$componentItem['item_id']) && $componentItem['row_length'] != 0) {
                return response()->json(['data' => ['html' => ''], 'status' => 422, 'message' => 'Please fill all component details before adding new row more!']);
            }
        }

        $compSelectedAttr = json_decode($request->comp_attr,true) ?? []; 
        $attributes = [];
        if(count($compSelectedAttr)) {
               foreach($compSelectedAttr as $compAttr) {
                $itemAttr = ItemAttribute::where("item_id",$componentItem['item_id'])
                                ->where("attribute_group_id",$compAttr['attr_name'])
                                ->first();
                $attributes[] = ['attribute_id' => $itemAttr?->id, 'attribute_value' => $itemAttr?->attribute_group_id];
               }
        }


        $bomExists = ItemHelper::checkItemBomExists($componentItem['item_id'], $attributes, $moduleType);
        $itemType = $bomExists['sub_type'];

        if(in_array($itemType ,['Finished Goods', 'WIP/Semi Finished'])) {
            if (!$bomExists['bom_id']) {
                $compItem = Item::find($componentItem['item_id']);
                $name = $compItem?->item_name;
                $bomExists['message'] = "Bom doesn't exist for $itemType product $name";
                return response()->json(['data' => ['html' => ''], 'status' => 422, 'message' => $bomExists['message']]);
            }
        }
        $rowCount = intval($request->count) == 0 ? 1 : intval($request->count) + 1;
        $wasteTypes = ConstantHelper::DISCOUNT_TYPES;
        $html = view('billOfMaterial.partials.item-row',compact('rowCount','wasteTypes'))->render();
        return response()->json(['data' => ['html' => $html], 'status' => 200, 'message' => 'fetched.']);
    }

    # Get Orver in popup
    public function getOverhead(Request $request)
    {
        $rowCount = intval($request->rowCount) ?? 1;
        $prevSelectedData = $request->prevSelectedData ? json_decode($request->prevSelectedData,true) : [];
        $html = view('billOfMaterial.partials.comp-overhead',compact('rowCount','prevSelectedData'))->render();
        return response()->json(['data' => ['html' => $html], 'status' => 200, 'message' => 'fetched.']);
    }

    # On select row get item detail
    public function getItemDetail(Request $request)
    {
        $selectedAttr = json_decode($request->selectedAttr,200) ?? [];
        $item = Item::find($request->item_id ?? null);
        $specifications = $item->specifications()->whereNotNull('value')->get();
        $sectionName = $request->section_name ?? '';
        $subSectionName = $request->sub_section_name ?? '';
        $stationName = $request->station_name ?? '';
        $remark = $request->remark ?? null;
        $html = view('billOfMaterial.partials.comp-item-detail',compact('item','selectedAttr','specifications','sectionName','subSectionName','stationName','remark'))->render();
        return response()->json(['data' => ['html' => $html], 'status' => 200, 'message' => 'fetched.']);
    }

    # Bom edit
    public function edit(Request $request, $id)
    {
        $parentUrl = request() -> segments()[0];
        $servicesAliasParam = request()->segments()[0] == 'commercial-bom' ? ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS : ConstantHelper::BOM_SERVICE_ALIAS;
        $servicesBooks = Helper::getAccessibleServicesFromMenuAlias($parentUrl, $servicesAliasParam);
        if (count($servicesBooks['services']) == 0) {
            return redirect()->back();
        }
        $bom = Bom::find($id);
        $createdBy = $bom->created_by; 
        $revision_number = $bom->revision_number;
        $books = Helper::getBookSeriesNew($servicesAliasParam,$parentUrl, true)->get();
        $wasteTypes = ConstantHelper::DISCOUNT_TYPES;
        $headerAttributes = $bom->bomAttributes()->where('type','H')->get();
        $selectedAttributes = $headerAttributes->pluck('attribute_value')->all();
        $totalValue = ($bom->total_item_value + $bom->item_waste_amount + $bom->header_waste_amount + $bom->item_overhead_amount + $bom->header_overhead_amount);
        $creatorType = Helper::userCheck()['type'];
        $buttons = Helper::actionButtonDisplay($bom->book_id,$bom->document_status , $bom->id, $totalValue, $bom->approval_level, $bom->created_by ?? 0, $creatorType, $revision_number);
        $revNo = $bom->revision_number;
        if($request->has('revisionNumber')) {
            $revNo = intval($request->revisionNumber);
        } else {
            $revNo = $bom->revision_number;
        }
        $docValue = $bom->total_value ?? 0;
        $approvalHistory = Helper::getApprovalHistory($bom->book_id, $bom->id, $revNo, $docValue, $createdBy);
        $docStatusClass = ConstantHelper::DOCUMENT_STATUS_CSS[$bom->document_status] ?? '';
        $specifications = collect();
        if(isset($bom->item) && $bom->item) {
            $specifications = $bom->item->specifications()->whereNotNull('value')->get();
        }
        $productionTypes = ['In-house','Job Work'];

        $view = 'billOfMaterial.edit';

        if($request->has('revisionNumber') && $request->revisionNumber != $bom->revision_number) {
            $bom = $bom->source()->where('revision_number', $request->revisionNumber)->first();
            $view = 'billOfMaterial.view';
        }
        return view($view, [
            'wasteTypes' => $wasteTypes,
            'books' => $books,
            'bom' => $bom,
            'item' => isset($bom->item) ? $bom->item : null,
            'headerAttributes' => $headerAttributes,
            'selectedAttributes' => $selectedAttributes,
            'buttons' => $buttons,
            'approvalHistory' => $approvalHistory,
            'docStatusClass' => $docStatusClass,
            'specifications' => $specifications,
            'productionTypes' => $productionTypes,
            'revision_number' => $revision_number,
            'servicesBooks' => $servicesBooks,
            'serviceAlias' => $servicesAliasParam
        ]); 
    }

    # Bom Update
    public function update(BomRequest $request, $id)
    {
       DB::beginTransaction();
        try {
            $bom = Bom::find($id);
            $currentStatus = $bom->document_status;
            $actionType = $request->action_type;
            if($currentStatus == ConstantHelper::APPROVED && $actionType == 'amendment')
            {
                $revisionData = [
                    ['model_type' => 'header', 'model_name' => 'Bom', 'relation_column' => ''],
                    ['model_type' => 'detail', 'model_name' => 'BomDetail', 'relation_column' => 'bom_id'],
                    ['model_type' => 'sub_detail', 'model_name' => 'BomAttribute', 'relation_column' => 'bom_detail_id'],
                    ['model_type' => 'sub_detail', 'model_name' => 'BomOverhead', 'relation_column' => 'bom_detail_id'],
                    ['model_type' => 'sub_detail', 'model_name' => 'BomNormsCalculation', 'relation_column' => 'bom_detail_id']
                ];
                $a = Helper::documentAmendment($revisionData, $id);
            }

            $keys = ['deletedHeaderOverheadIds', 'deletedItemOverheadIds', 'deletedBomItemIds', 'deletedAttachmentIds'];
            $deletedData = [];

            foreach ($keys as $key) {
                $deletedData[$key] = json_decode($request->input($key, '[]'), true);
            }
            if (count($deletedData['deletedHeaderOverheadIds'])) {
                BomOverhead::whereIn('id',$deletedData['deletedHeaderOverheadIds'])->delete();
            }

            if (count($deletedData['deletedItemOverheadIds'])) {
                BomOverhead::whereIn('id',$deletedData['deletedItemOverheadIds'])->delete();
            }

            if (count($deletedData['deletedAttachmentIds'])) {
                $medias = BomMedia::whereIn('id',$deletedData['deletedAttachmentIds'])->get();
                foreach ($medias as $media) {
                    if ($request->document_status == ConstantHelper::DRAFT) {
                        Storage::delete($media->file_name);
                    }
                    $media->delete();
                }
            }

            if (count($deletedData['deletedBomItemIds'])) {
                $bomItems = BomDetail::whereIn('id',$deletedData['deletedBomItemIds'])->get();
                foreach($bomItems as $bomItem) {
                    $bomItem->overheads()->delete();
                    $bomItem->delete();
                }
            }

            # Bom Header save
            $bom->uom_id = $request->uom_id;
            $bom->production_type = $request->production_type;
            $bom->item_id = $request->item_id;
            $bom->item_code = $request->item_code;
            $bom->item_name = $request->item_name;
            $bom->document_status = $request->document_status ?? ConstantHelper::DRAFT;
            $bom->remarks = $request->remarks;
            # Extra Column
            $bom->save();

            if($bom->type == ConstantHelper::BOM_SERVICE_ALIAS) {
                $quote_bom_id = $request->quote_bom_id;
                $quoteBom = Bom::find($quote_bom_id);
                if($quoteBom) {
                    Bom::where('production_bom_id', $bom->id)->update(['production_bom_id' => null]);
                    $quoteBom->production_bom_id = $bom->id;
                    $quoteBom->save();
                }
            }

            # Save header attribute
            foreach($bom->item->itemAttributes as  $key => $itemAttribute) {
                $key = $key + 1;
                $headerAttr = @$request->all()['attributes'][$key];
                if (isset($headerAttr['attr_group_id'][$itemAttribute->attribute_group_id])) {

                    $bomAttrId = @$headerAttr['attr_group_id'][$itemAttribute->attribute_group_id]['attr_id'] ?? null;

                    $bomAttr = BomAttribute::find($bomAttrId) ?? new BomAttribute;
                    $bomAttr->bom_id = $bom->id;
                    $bomAttr->item_attribute_id = $itemAttribute->id;
                    $bomAttr->item_id = $bom->item->id;
                    $bomAttr->type = 'H';
                    $bomAttr->item_code = $request->item_code;
                    $bomAttr->attribute_name = $itemAttribute->attribute_group_id;
                    $bomAttr->attribute_value = @$headerAttr['attr_group_id'][$itemAttribute->attribute_group_id]['attr_name'];
                    $bomAttr->save();
                }
            }

            if (isset($request->all()['components'])) {
                $consumptionMethod = $request->consumption_method;
                foreach($request->all()['components'] as $component) {
                    # Bom Detail Save
                    $bomDetail = BomDetail::find(@$component['bom_detail_id']) ?? new BomDetail;
                    $bomDetail->bom_id = $bom->id;
                    $bomDetail->item_id = $component['item_id'];
                    $bomDetail->item_code = $component['item_code'];
                    $bomDetail->qty = $component['qty'] ?? 0.00;
                    $bomDetail->uom_id = $component['uom_id'] ?? null;
                    $bomDetail->waste_perc = $component['waste_perc'] ?? 0.00;
                    $bomDetail->waste_amount = $component['waste_amount'] ?? 0.00;
                    $bomDetail->superceeded_cost = $component['superceeded_cost'] ?? 0.00;
                    $bomDetail->item_cost = $component['item_cost'] ?? 0.00;
                    $bomDetail->item_value = $component['item_value'] ?? 0.00;
                    $bomDetail->overhead_amount = $component['overhead_amount'] ?? 0.00;
                    $bomDetail->total_amount = $component['item_total_cost'] ?? 0.00;
                    $bomDetail->sub_section_id = $component['sub_section_id'] ?? null;
                    $bomDetail->section_name = $component['section_name'] ?? null;
                    $bomDetail->sub_section_name = $component['sub_section_name'] ?? null;
                    $bomDetail->station_name = $component['station_name'] ?? null;
                    $bomDetail->station_id = $component['station_id'] ?? null;
                    $bomDetail->remark = $component['remark'] ?? null;
                    $bomDetail->save();

                    # Norms Save
                    if($consumptionMethod != 'manual') {
                        # Norms
                        $normData = [
                            'bom_id' => $bom->id,
                            'bom_detail_id' => $bomDetail->id,
                        ];
                        $updateData = [
                            'qty_per_unit' => $component['qty_per_unit'] ?? 0.00,
                            'total_qty' => $component['total_qty'] ?? 0.00,
                            'std_qty' => $component['std_qty'] ?? 0.00,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        if($updateData['qty_per_unit'] && $updateData['total_qty'] && $updateData['std_qty']){
                            BomNormsCalculation::updateOrCreate($normData, $updateData);
                        }
                    }
                    // else {
                    //     # Manual
                    // }

                    #Save component Attr
                    foreach($bomDetail->item->itemAttributes as $itemAttribute) {
                        if (isset($component['attr_group_id'][$itemAttribute->attribute_group_id])) {
                            $bomAttrId = @$component['attr_group_id'][$itemAttribute->attribute_group_id]['attr_id'];
                            $bomAttr = BomAttribute::find($bomAttrId) ?? new BomAttribute;
                            $bomAttr->bom_id = $bom->id;
                            $bomAttr->bom_detail_id = $bomDetail->id;
                            $bomAttr->item_id = $component['item_id'];
                            $bomAttr->item_attribute_id = $itemAttribute->id;
                            $bomAttr->type = 'D';
                            $bomAttr->item_code = $component['item_code'];
                            $bomAttr->attribute_name = $itemAttribute->attribute_group_id;
                            $bomAttr->attribute_value = @$component['attr_group_id'][$itemAttribute->attribute_group_id]['attr_name'];
                            $bomAttr->save();
                        }
                    }

                    #Save item overhead
                    if (isset($component['overhead'])) {
                        foreach($component['overhead'] as $overhead) {
                            if (isset($overhead['amnt']) && $overhead['amnt']) {
                                $bomOverheadId = @$overhead['id'];
                                $bomOverhead = BomOverhead::find($bomOverheadId) ?? new BomOverhead;
                                $bomOverhead->bom_id = $bom->id;
                                $bomOverhead->bom_detail_id = $bomDetail->id;
                                $bomOverhead->type = 'D';
                                $bomOverhead->overhead_description = $overhead['description'];
                                $bomOverhead->ledger_name = $overhead['leadger'];
                                $bomOverhead->overhead_amount = $overhead['amnt'] ?? 0.00;
                                $bomOverhead->save();
                            }
                        }
                    }
                }
            } else {
                DB::rollBack();
                return response()->json([
                        'message' => 'Please add atleast one row in component table.',
                        'error' => "",
                    ], 422);
            }

            #Save summary overhead
            if (isset($request->overhead) && $bom->id) {
                foreach($request->overhead as $overSummary) {
                    if($overSummary['description'] && $overSummary['amnt']) {
                        $bomOverhead = BomOverhead::find(@$overSummary['id']) ?? new BomOverhead;
                        $bomOverhead->bom_id = $bom->id;
                        // $bomOverhead->bom_detail_id = $bomDetail->id;
                        $bomOverhead->type = 'H';
                        $bomOverhead->overhead_description = $overSummary['description'];
                        $bomOverhead->ledger_name = $overSummary['leadger'];
                        $bomOverhead->overhead_amount = $overSummary['amnt'] ?? 0.00;
                        $bomOverhead->save();
                    }
                }
            }

            /*Bom Attachment*/
            if ($request->hasFile('attachment')) {
                $mediaFiles = $bom->uploadDocuments($request->file('attachment'), 'bom', false);
            }
            /*Update Bom header*/
            $bom->header_waste_perc = $request->waste_perc ?? 0.00;
            $bom->header_waste_amount = $request->waste_amount ?? 0.00;
            $bom->total_item_value = $bom->bomItems()->sum('item_value') ?? 0.00;
            $bom->item_waste_amount = $bom->bomItems()->sum('waste_amount') ?? 0.00;
            $bom->item_overhead_amount = $bom->bomComponentOverheadItems()->sum('overhead_amount') ?? 0.00;
            $bom->header_overhead_amount = $bom->bomOverheadItems()->sum('overhead_amount') ?? 0.00;
            $bom->save();

            /*Create document submit log*/
            $bookId = $bom->book_id; 
            $docId = $bom->id;
            $amendRemarks = $request->amend_remarks ?? null;
            $remarks = $bom->remarks;
            $amendAttachments = $request->file('amend_attachment');
            $attachments = $request->file('attachment');
            $currentLevel = $bom->approval_level;
            $modelName = get_class($bom);
            $totalValue = $bom->total_value ?? 0;
            if($currentStatus == ConstantHelper::APPROVED && $actionType == 'amendment')
            {
                //*amendmemnt document log*/
                $revisionNumber = $bom->revision_number + 1;
                $actionType = 'amendment';
                $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $amendRemarks, $amendAttachments, $currentLevel, $actionType, $totalValue, $modelName);
                $bom->revision_number = $revisionNumber;
                $bom->approval_level = 1;
                $bom->revision_date = now();
                $amendAfterStatus = $approveDocument['approvalStatus'] ??  $bom->document_status;
                // $checkAmendment = Helper::checkAfterAmendApprovalRequired($request->book_id);
                // if(isset($checkAmendment->approval_required) && $checkAmendment->approval_required) {
                //     $totalValue = $bom->total_value ?? 0;
                //     $amendAfterStatus = Helper::checkApprovalRequired($request->book_id,$totalValue);
                // }
                // if ($amendAfterStatus == ConstantHelper::SUBMITTED) {
                //     $actionType = 'submit';
                //     $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, 0, $modelName);
                // }
                $bom->document_status = $amendAfterStatus;
                $bom->save();
            } else {
                if ($request->document_status == ConstantHelper::SUBMITTED) {
                    $revisionNumber = $bom->revision_number ?? 0;
                    $actionType = 'submit'; // Approve // reject // submit
                    $approveDocument = Helper::approveDocument($bookId, $docId, $revisionNumber , $remarks, $attachments, $currentLevel, $actionType, $totalValue, $modelName);
                }
                if ($request->document_status == 'submitted') {
                    // $totalValue = $bom->total_value ?? 0;
                    // $document_status = Helper::checkApprovalRequired($request->book_id,$totalValue);
                    $bom->document_status = $approveDocument['approvalStatus'] ?? $bom->document_status;
                } else {
                    $bom->document_status = $request->document_status ?? ConstantHelper::DRAFT;
                }
            }
            $bom->save();

            DB::commit();

            return response()->json([
                'message' => 'Record updated successfully',
                'data' => $bom,
            ]);   
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error occurred while creating the record.',
                'error' => $e->getMessage(),
            ], 500);
        } 
    }

    # Get Bom item cost
    public function getItemCost(Request $request)
    {
        $selectedAttributes = json_decode($request->itemAttributes,true);
        $itemId = $request->item_id;
        $result = Helper::getChildBomItemCost($itemId, $selectedAttributes);
        $itemCost = $result['cost'];
        if(!floatval($itemCost)) {
            $uomId = $request->uom_id ?? null;
            $currency =  CurrencyHelper::getOrganizationCurrency();
            $currencyId = $currency->id ?? null; 
            $transactionDate = $request->transaction_date ?? date('Y-m-d');
            if($request->type == ConstantHelper::BOM_SERVICE_ALIAS) {
                $itemCost = ItemHelper::getItemCostPrice($itemId, $selectedAttributes, $uomId, $currencyId, $transactionDate);
            } else {
                $itemCost = ItemHelper::getItemSalePrice($itemId, $selectedAttributes, $uomId, $currencyId, $transactionDate);
            }
        }
        return response()->json(['data' => ['cost' => $itemCost,'route' => $result['route'] ?? null], 'status' => 200, 'message' => 'fetched bom header item cost']);
    }

        // genrate pdf
    public function generatePdf(Request $request, $id)
    {
        $user = Helper::getAuthenticatedUser();
        $organization = Organization::where('id', $user->organization_id)->first();
        $organizationAddress = Address::with(['city', 'state', 'country'])
            ->where('addressable_id', $user->organization_id)
            ->where('addressable_type', Organization::class)
            ->first();
            // dd($organizationAddress);
        $bom = Bom::findOrFail($id);

        $specifications = collect();
        if(isset($bom->item) && $bom->item) {
            $specifications = $bom->item->specifications()->whereNotNull('value')->get();
        }

        $totalAmount = $bom->total_value;
        $amountInWords = NumberHelper::convertAmountToWords($totalAmount);
        // Path to your image (ensure the file exists and is accessible)
        $imagePath = public_path('assets/css/midc-logo.jpg'); // Store the image in the public directory
        $docStatusClass = ConstantHelper::DOCUMENT_STATUS_CSS[$bom->document_status] ?? '';
        $pdf = PDF::loadView(

            // return view(
            'pdf.bom',
            [
                'bom'=> $bom,
                'organization' => $organization,
                'organizationAddress' => $organizationAddress,
                'totalAmount'=>$totalAmount,
                'amountInWords'=>$amountInWords,
                'imagePath' => $imagePath,
                'specifications' => $specifications,
                'docStatusClass' => $docStatusClass
            ]
        );

        $pdf->setOption('isHtml5ParserEnabled', true);
        return $pdf->stream('BillOfMaterial-' . date('Y-m-d') . '.pdf');
    }

    public function revokeDocument(Request $request)
    {
        DB::beginTransaction();
        try {
            $bom = Bom::find($request->id);
            if (isset($bom)) {
                $revoke = Helper::approveDocument($bom->book_id, $bom->id, $bom->revision_number, '', [], 0, ConstantHelper::REVOKE, $bom->total_value, get_class($bom));
                if ($revoke['message']) {
                    DB::rollBack();
                    return response() -> json([
                        'status' => 'error',
                        'message' => $revoke['message'],
                    ]);
                } else {
                    $bom->document_status = $revoke['approvalStatus'];
                    $bom->save();
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

    # Get Quotation Bom Item List
    public function getQuoteBom(Request $request)
    {
        $seriesId = $request->series_id ?? null;
        $docNumber = $request->document_number ?? null;
        $itemId = $request->item_id ?? null;
        $headerBookId = $request->header_book_id ?? null;
        $departmentId = $request->department_id ?? null;
        $applicableBookIds = ServiceParametersHelper::getBookCodesForReferenceFromParam($headerBookId);
        $piItems = Bom::where(function($query) use ($seriesId,$applicableBookIds,$docNumber,$itemId,$departmentId) {
                        $query->whereHas('item');
                        $query->whereNull('production_bom_id');
                        $query->where('type',ConstantHelper::COMMERCIAL_BOM_SERVICE_ALIAS);
                        $query->withDefaultGroupCompanyOrg();
                        $query->whereIn('document_status', [ConstantHelper::APPROVED, ConstantHelper::APPROVAL_NOT_REQUIRED]);
                        if($seriesId) {
                            $query->where('book_id',$seriesId);
                        } else {
                            if(count($applicableBookIds)) {
                                $query->whereIn('book_id',$applicableBookIds);
                            }
                        }
                        if($docNumber) {
                            $query->where('document_number', 'LIKE', "%$docNumber%");
                        }
                        if($departmentId) {
                            $query->where('department_id', $departmentId);
                        }
                        if ($itemId) {
                            $query->where('item_id', $itemId);
                        }
                })
                ->get();
        $html = view('billOfMaterial.partials.q-bom-list', ['piItems' => $piItems])->render();
        return response()->json(['data' => ['pis' => $html], 'status' => 200, 'message' => "fetched!"]);
    }

    # Submit PI Item list
    public function processBomItem(Request $request)
    {
        $ids = json_decode($request->ids,true) ?? [];
        $bom = Bom::with('uom:id,name')
                ->whereIn('id', $ids)
                ->first();
        $html = view('billOfMaterial.partials.item-row-edit', ['bom' => $bom])->render();

        $specifications = collect();
        if(isset($bom->item) && $bom->item) {
            $specifications = $bom->item->specifications()->whereNotNull('value')->get();
        }

        $headerAttributes = $bom->bomAttributes()->where('type','H')->get();
        $selectedAttributes = $headerAttributes->pluck('attribute_value')->all();

        $headerAttrHtml = view('billOfMaterial.partials.header-attribute-edit', [
            'specifications' => $specifications,
            'item' => $bom->item,
            'bom' => $bom,
            'selectedAttributes' => $selectedAttributes
            ])->render();
        
        $headerOverhead = view('billOfMaterial.partials.header-overhead', ['bom' => $bom])->render();
        $headerWaste = view('billOfMaterial.partials.header-waste', ['bom' => $bom])->render();
        return response()->json(['data' => ['bom' => $bom, 'pos' => $html, 'headerAttrHtml' => $headerAttrHtml, 'headerOverhead' => $headerOverhead, 'headerWaste' => $headerWaste], 'status' => 200, 'message' => "fetched!"]);
    }
    
}
