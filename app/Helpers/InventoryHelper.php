<?php
namespace App\Helpers;

use DB;
use Auth;

use App\Models\ErpSaleInvoice;
use App\Models\ErpInvoiceItem;
use App\Models\ErpInvoiceItemLocation;
use App\Models\ErpInvoiceItemAttribute;

use App\Models\Item;
use App\Models\Unit;
use App\Models\ErpAttribute;
use App\Models\ItemAttribute;

use App\Models\MrnHeader;
use App\Models\MrnDetail;
use App\Models\MrnItemLocation;

use App\Models\StockLedger;
use App\Models\StockLedgerItemAttribute;

use Illuminate\Support\Facades\Log;


class InventoryHelper
{
    
    public static function settlementOfInventoryAndStock($documentHeaderId, $documentDetailId, $bookType, $documentStatus)
    {

        $user = Helper::getAuthenticatedUser();
        $message = '';
        // dd($user->toArray());
        // dd([$documentHeaderId, $documentDetailId, $bookType, $documentStatus]);
        if($bookType == 'mrn'){
            $transactionType = 'receipt';
            $documentItemLocations = MrnItemLocation::where('mrn_header_id',$documentHeaderId)
                ->whereIn('mrn_detail_id',$documentDetailId)
                ->with('mrnHeader',
                    'mrnDetail',
                    'mrnDetail.item',
                    'mrnDetail.attributes',
                    'erpStore',
                    'erpRack',
                    'erpShelf',
                    'erpBin'
                )
                ->get();
            
            $stockLedger = StockLedger::where('organization_id', $user->organization_id)
                ->where('document_header_id',$documentHeaderId)
                ->whereIn('document_detail_id',$documentDetailId)
                ->where('book_type','=',$bookType)
                ->where('transaction_type','=',$transactionType)
                ->where('document_status','draft')
                ->get();
            foreach($stockLedger as $val){
                StockLedgerItemAttribute::where('stock_ledger_id', $val->id)->delete();
                $val->delete();
            }
                            
            foreach ($documentItemLocations as $documentItemLocation) {
                $invoiceLedger = self::insertStockLedger($documentItemLocation, $bookType, $documentStatus, $transactionType);                
            }
            $message = "Success";
        } else if($bookType == 'invoice'){
            $transactionType = 'issue';
            $documentItemLocations = ErpInvoiceItemLocation::where('sale_invoice_id',$documentHeaderId)
                ->where('invoice_item_id',$documentDetailId)
                ->with('header',
                    'detail',
                    'detail.item',
                    'detail.attributes'
                )
                ->get();

            if(isset($documentItemLocations) && $documentItemLocations){
                foreach ($documentItemLocations as $documentItemLocation) {
                    $invoiceLedger = self::insertStockLedger($documentItemLocation, $bookType, $documentStatus, $transactionType);
                    // dd($invoiceLedger);
                    $updatedInvoiceLedger = self::updateStockLedger($invoiceLedger, $documentItemLocation, $bookType, $documentStatus, $transactionType);
                }
            }
        } else {
            $message = "Invalid Book Type";
        }
        return $message;
    }

    // Total Draft And Confirmed Stock
    public static function totalInventoryAndStock($itemId, $selectedAttr, $storeId, $rackId, $shelfId, $binId)
    {
        $user = Helper::getAuthenticatedUser();
        $attributeGroups = ErpAttribute::whereIn('id', $selectedAttr)->pluck('attribute_group_id');

        $stockLedger = StockLedgerItemAttribute::select(
            [
                'stock_ledger.id as stock_id',
                'stock_ledger.organization_id as organization_id',
                'stock_ledger.group_id as group_id',
                'stock_ledger.company_id as company_id',
                'stock_ledger.item_id as item_id',
                'stock_ledger.store as store_code',
                'stock_ledger.store_id as store_id',
                'stock_ledger.bin as bin_code',
                'stock_ledger.bin_id as bin_id',
                'stock_ledger.rack as rack_code',
                'stock_ledger.rack_id as rack_id',
                'stock_ledger.shelf as shelf_code',
                'stock_ledger.shelf_id as shelf_id',
                'stock_ledger.receipt_qty as receipt_qty',
                'stock_ledger.inventory_uom as inventory_uom',
                'stock_ledger.document_number as document_number',
                'stock_ledger.document_date as document_date',
                'stock_ledger.document_status as document_status',
                'stock_ledger_item_attributes.id as attribute_id',
                'stock_ledger_item_attributes.stock_ledger_id as stock_ledger_id',
                'stock_ledger_item_attributes.item_id as attribute_item_id',
                'stock_ledger_item_attributes.attribute_name as attribute_name',
                'stock_ledger_item_attributes.attribute_value as attribute_value'
            ]
        )
        ->leftJoin('stock_ledger', 'stock_ledger.id', '=', 'stock_ledger_item_attributes.stock_ledger_id')
        ->where('stock_ledger.organization_id', $user->organization_id)
        ->where('stock_ledger.item_id', $itemId)
        ->where('stock_ledger_item_attributes.item_id', $itemId)
        ->whereNull('stock_ledger.utilized_id')
        ->whereNotNull('stock_ledger.document_header_id')
        ->whereNotNull('stock_ledger.receipt_qty')
        ->groupBy('stock_ledger.id');

         // // Apply attribute filtering if needed
        if ($selectedAttr) {
            $stockLedger->whereIn('stock_ledger_item_attributes.attribute_name', $attributeGroups)
                ->whereIn('stock_ledger_item_attributes.attribute_value', $selectedAttr)
                ->havingRaw('COUNT(DISTINCT stock_ledger_item_attributes.attribute_value) = ?', [count($selectedAttr)]);
        }

        // Filters for Store, Rack, Shelf, and Bin (if needed)
        if ($storeId) {
            $stockLedger->where('store_id', $storeId);
        }
        if ($rackId) {
            $stockLedger->where('rack_id', $rackId);
        }
        if ($shelfId) {
            $stockLedger->where('shelf_id', $shelfId);
        }
        if ($binId) {
            $stockLedger->where('bin_id', $binId);
        }

        $stockLedger = $stockLedger->get();
        $pendingStocks = $stockLedger->where('document_status', '!=', 'approved')->sum('receipt_qty');
        $confirmedStocks = $stockLedger->where('document_status', '=', 'approved')->sum('receipt_qty');
        
        $data = [
            'pendingStocks' => $pendingStocks,
            'confirmedStocks' => $confirmedStocks,
        ];
        return $data;
    }

    // Fetch stock summary 
    public static function fetchStockSummary($itemId, $selectedAttr, $quantity, $storeId, $rackId, $shelfId, $binId)
    {
        $user = Helper::getAuthenticatedUser();

        $attributeGroups = ErpAttribute::whereIn('id', $selectedAttr)->pluck('attribute_group_id');
        $query = StockLedger::query()
            ->where('organization_id', $user->organization_id)
            ->where('document_status', '=', 'approved')
            ->where('transaction_type', '=', 'receipt')
            ->whereNull('utilized_id')
            ->with([
                'attributes' => function ($query) {
                    $query->with(['attributeName', 'attributeValue']);
                }
            ]);

        // Item Filter
        if ($itemId) {
            $query->where('item_id', $itemId);
        }

        if ($selectedAttr) {
            $query->whereHas('attributes', function ($subQuery) use ($selectedAttr, $attributeGroups) {
                $subQuery->whereIn('stock_ledger_item_attributes.attribute_value', $selectedAttr)
                ->whereIn('stock_ledger_item_attributes.attribute_name', $attributeGroups)
                ->havingRaw('COUNT(DISTINCT attribute_value) = ?', [count($selectedAttr)]);
            });
        }

        // Filters for Store, Rack, Shelf, and Bin (if needed)
        if ($storeId) {
            $query->where('store_id', $storeId);
        }
        if ($rackId) {
            $query->where('rack_id', $rackId);
        }
        if ($shelfId) {
            $query->where('shelf_id', $shelfId);
        }
        if ($binId) {
            $query->where('bin_id', $binId);
        }

        // Select Records with Grouping and Summing
        $query->select([
            'stock_ledger.*',
            DB::raw('SUM(receipt_qty) as total_receipt_qty')
        ])
        ->orderBy('id')
        ->groupBy([
            'item_id',
            'store_id',
            'rack_id',
            'shelf_id',
            'bin_id'
        ]);
        $query = $query->get();
        if(count($query) < 1){
            // dd('empty');
            $code = 202;
            $status = 'error';
            $message = 'There is no approved stock, Please approve mrn first.';
            $records = '';
        }
        else{
            // Initialize variables for FIFO breakup
            // $quantity = 400;
            $remainingQuantity = $quantity;
            $fifoBreakup = [];
            $data = array();
            foreach ($query as $stockSummary) {
                $availableQty = $stockSummary->total_receipt_qty;

                if ($availableQty <= 0) {
                    continue; // Skip if no available quantity
                }

                // Allocate quantity
                $allocatedQty = min($availableQty, $remainingQuantity);
                $fifoBreakup[] = [
                    'item_id' => $stockSummary->item_id,
                    'item_name' => $stockSummary->item_name,
                    'item_code' => $stockSummary->item_code,
                    'store_id' => $stockSummary->store_id,
                    'store' => $stockSummary->store,
                    'rack_id' => $stockSummary->rack_id,
                    'rack' => $stockSummary->rack,
                    'shelf_id' => $stockSummary->shelf_id,
                    'shelf' => $stockSummary->shelf,
                    'bin_id' => $stockSummary->bin_id,
                    'bin' => $stockSummary->bin,
                    'allocated_quantity' => $allocatedQty
                ];

                // Decrease remaining quantity
                $remainingQuantity -= $allocatedQty;

                // Stop if we've fulfilled the required quantity
                if ($remainingQuantity <= 0) {
                    break;
                }
            }

            // If remaining quantity is still greater than 0, it means not enough stock was available
            if ($remainingQuantity > 0) {
                $code = 202;
                $status = 'error';
                $message = 'Not enough stock available to fulfill the order quantity';
                $records = '';
            } else{
                $code = 200;
                $status = 'success';
                $message = 'Record fetched successfuly.';
                $records = $fifoBreakup;
            }
        }
        
        $data = [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'records' => $records
        ];
        // Return the FIFO allocation breakup
        return $data;
    }

    // Update document status while update mrn  
    public static function updateInventoryAndStock($documentHeaderId, $documentDetailIds, $bookType, $documentStatus)
    {
        $user = Helper::getAuthenticatedUser();
        
        $stockLedger = StockLedger::where('organization_id', $user->organization_id)
            ->where('document_header_id',$documentHeaderId)
            ->whereIn('document_detail_id', $documentDetailIds)
            ->where('book_type','=',$bookType)
            ->where('document_status', 'submitted')
            ->get();

        if(count($stockLedger) > 0){
            foreach($stockLedger as $val){
                $val->document_status = $documentStatus;
                $val->save();
            }
        }
    }

    // Update document status while update mrn  
    public static function updateStockBasedOnIssue($documentHeaderId, $documentDetailId, $itemId, $bookType, $documentStatus)
    {
        $user = Helper::getAuthenticatedUser();
        $data = array();

        $stockLedger = StockLedger::where('organization_id', $user->organization_id)
            ->where('document_header_id',$documentHeaderId)
            ->where('item_id',$itemId)
            ->where('document_status', 'approved')
            ->whereNotNull('issue_qty')
            ->whereNotNull('receipt_qty')
            ->first();

        if(!$stockLedger){
            $code = 202;
            $status = 'error';
            $message = 'Stock Ledger not found.';
            $records = '';
        }

        $simillarStockLedger = StockLedger::where('organization_id', $user->organization_id)
            ->where('document_header_id',$stockLedger->document_header_id)
            ->where('document_detail_id',$stockLedger->document_detail_id)
            ->where('item_id',$itemId)
            ->where('document_status', 'approved')
            ->whereNull('issue_qty')
            ->whereNull('utilized_id')
            ->whereNotNull('receipt_qty')
            ->first();

        if(!$simillarStockLedger){
            $stockLedger->receipt_qty += $stockLedger->issue_qty;
            $stockLedger->issue_qty = 0.00;
            $stockLedger->utilized_id = null;
            $stockLedger->utilized_date = null;
            $stockLedger->customer_id = null;
            $stockLedger->customer_code = null;
            $stockLedger->save();

            $code = 200;
            $status = 'success';
            $message = 'Stock Ledger successfuly updated.';
            $records = $stockLedger;
        } else{
            $stockLedger->receipt_qty += $simillarStockLedger->receipt_qty;
            $stockLedger->issue_qty = 0.00;
            $stockLedger->utilized_id = null;
            $stockLedger->utilized_date = null;
            $stockLedger->customer_id = null;
            $stockLedger->customer_code = null;
            $stockLedger->save();

            $simillarStockLedger->delete();

            $code = 200;
            $status = 'success';
            $message = 'Stock Ledger successfuly updated.';
            $records = $stockLedger;
        }

        $data = [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'records' => $records
        ];
        return $data;
    }

    // Update document status while update mrn  
    private static function insertStockLedger($documentItemLocation, $bookType, $documentStatus, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();

        $documentHeader = '';
        $documentDetail = '';
        $stockLedger = new StockLedger;
        $totalItemCost = 0.00;
        if($bookType == 'mrn' ){
            $documentHeader = MrnHeader::find($documentItemLocation->mrn_header_id);
            $documentDetail = MrnDetail::with(['header', 'attributes'])->find($documentItemLocation->mrn_detail_id); 
            $stockLedger->vendor_id = @$documentHeader->vendor_id;
            $stockLedger->vendor_code = @$documentHeader->vendor_code;
            $stockLedger->receipt_qty = $documentItemLocation->inventory_uom_qty ?? $documentItemLocation->quantity;
            $stockLedger->book_id = @$documentHeader->book_id;
            $totalItemCost = $documentDetail->basic_value - ($documentDetail->discount_amount + $documentDetail->header_discount_amount);    
        }
        if($bookType == 'invoice' ){
            $documentHeader = ErpSaleInvoice::find($documentItemLocation->sale_invoice_id);
            $documentDetail = ErpInvoiceItem::with(['header', 'attributes'])->find($documentItemLocation->invoice_item_id); 
            $stockLedger->customer_id = @$documentHeader->customer_id;
            $stockLedger->customer_code = @$documentHeader->customer_code;
            $stockLedger->issue_qty = @$documentItemLocation->inventory_uom_qty;
            $stockLedger->book_id = @$documentHeader->book_id;
            $totalItemCost = ($documentDetail->order_qty*$documentDetail->rate) - ($documentDetail->item_discount_amount + $documentDetail->header_discount_amount); 
        }

        $inventoryUom = Unit::find($documentDetail->item->uom_id);
        //Header Data 
        $stockLedger->group_id = @$documentHeader->group_id;
        $stockLedger->company_id = @$documentHeader->company_id;
        $stockLedger->organization_id = @$documentHeader->organization_id;
        $stockLedger->document_header_id = @$documentHeader->id;
        $stockLedger->document_detail_id = @$documentDetail->id;
        $stockLedger->book_code = @$documentHeader->book_code;
        $stockLedger->document_number = @$documentHeader->document_number;
        $stockLedger->document_date = @$documentHeader->document_date;
        //costing exchange rate currency
        $stockLedger->document_currency_id = @$documentHeader->transaction_currency_id;
        $stockLedger->document_currency = @$documentHeader->transaction_currency;
        $stockLedger->org_currency_id = @$documentHeader->org_currency_id;
        $stockLedger->org_currency_code = @$documentHeader->org_currency_code;
        $stockLedger->org_currency_cost_per_unit = round(($documentDetail->rate * $documentHeader->org_currency_exg_rate),6);
        $stockLedger->org_currency_cost = round((@$totalItemCost * $documentHeader->org_currency_exg_rate),2);
        $stockLedger->comp_currency_id = @$documentHeader->comp_currency_id;
        $stockLedger->comp_currency_code = @$documentHeader->comp_currency_code;
        $stockLedger->comp_currency_cost_per_unit = round(($documentDetail->rate * $documentHeader->comp_currency_exg_rate),6);
        $stockLedger->comp_currency_cost = round((@$totalItemCost * $documentHeader->comp_currency_exg_rate),2);
        $stockLedger->group_currency_id = @$documentHeader->group_currency_id;
        $stockLedger->group_currency_code = @$documentHeader->group_currency_code;
        $stockLedger->group_currency_cost_per_unit = round(($documentDetail->rate * $documentHeader->group_currency_exg_rate),6);
        $stockLedger->group_currency_cost = round((@$totalItemCost * $documentHeader->group_currency_exg_rate),2);
        $stockLedger->original_receipt_date = @$documentHeader->document_date;
        // Detail Data 
        $stockLedger->item_id = @$documentDetail->item_id;
        $stockLedger->item_code = @$documentDetail->item_code;
        $stockLedger->item_name = @$documentDetail->item->item_name;
        $stockLedger->inventory_uom_id = @$inventoryUom->id;
        $stockLedger->inventory_uom = @$inventoryUom->name;
        $stockLedger->cost_per_unit = @$documentDetail->rate;
        $stockLedger->total_cost = round(@$documentItemLocation->quantity * $documentDetail->rate,2);
        // Item Location Data 
        $stockLedger->store_id = @$documentItemLocation->store_id;
        $stockLedger->rack_id = @$documentItemLocation->rack_id;
        $stockLedger->shelf_id = @$documentItemLocation->shelf_id;
        $stockLedger->bin_id = @$documentItemLocation->bin_id;
        $stockLedger->store = @$documentItemLocation->erpStore->store_code;
        $stockLedger->rack = @$documentItemLocation->erpRack->rack_code;
        $stockLedger->shelf = @$documentItemLocation->erpShelf->shelf_code;
        $stockLedger->bin = @$documentItemLocation->erpBin->bin_code;
        $stockLedger->book_type = $bookType;
        $stockLedger->document_status = $documentStatus;
        $stockLedger->transaction_type = $transactionType;
        
        $stockLedger->created_by = @$user->id;
        $stockLedger->updated_by = @$user->id;
        $stockLedger->save();
        
        $attributeArray = array();
        $attributeJsonArray = array();
        if(isset($documentDetail->attributes) && !empty($documentDetail->attributes)){
            foreach($documentDetail->attributes as $key1 => $attribute){
                $ledgerAttribute = new StockLedgerItemAttribute();
                $ledgerAttribute->stock_ledger_id = $stockLedger->id;
                $ledgerAttribute->item_id = @$documentDetail->item_id;
                $ledgerAttribute->item_code = @$documentDetail->item_code;
                $ledgerAttribute->item_attribute_id = @$attribute->item_attribute_id;
                $ledgerAttribute->attribute_name = @$attribute->attr_name;
                $ledgerAttribute->attribute_value = @$attribute->attr_value;
                $ledgerAttribute->status = "active";
                $ledgerAttribute->save();

                $attributeArray[] = [
                    "attr_name" => @$attribute->attr_name,
                    "attribute_name" => @$ledgerAttribute->attributeName->name,
                    "attr_value" => @$attribute->attr_value,
                    "attribute_value" => @$ledgerAttribute->attributeValue->value,
                ];

                $attributeJsonArray[] = [
                    "attr_name" => @$attribute->attr_name,
                    "attribute_name" => @$ledgerAttribute->attributeName->name,
                    "attr_value" => @$attribute->attr_value,
                    "attribute_value" => @$ledgerAttribute->attributeValue->value,
                ];
            }
        }

        $stockLedger->item_attributes = $attributeArray;
        $stockLedger->json_item_attributes = $attributeJsonArray;
        $stockLedger->save();

        return $stockLedger;
    }

    private static function updateStockLedger($invoiceLedger, $documentItemLocation, $bookType, $documentStatus, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();

        $itemAttributes = ErpInvoiceItemAttribute::where('invoice_item_id', $documentItemLocation->document_detail_id) -> pluck('attribute_value');
        $selectedAttr = ErpAttribute::whereIn('value', $itemAttributes)->pluck('id');
        $attributeGroups = ErpAttribute::whereIn('value', $itemAttributes)->pluck('attribute_group_id');
        $approvedStockLedger = StockLedgerItemAttribute::select(
            'stock_ledger.*', 
            'stock_ledger_item_attributes.*'
        )
        ->leftJoin('stock_ledger', 'stock_ledger.id', '=', 'stock_ledger_item_attributes.stock_ledger_id')
        ->where('stock_ledger.organization_id', $user->organization_id)
        ->where('stock_ledger.document_status', '=', 'approved')
        ->where('stock_ledger.item_id', $invoiceLedger->item_id)
        ->where('stock_ledger_item_attributes.item_id', $invoiceLedger->item_id)
        ->whereNull('stock_ledger.utilized_id')
        ->whereNotNull('stock_ledger.document_header_id')
        ->whereNotNull('stock_ledger.receipt_qty')
        ->orderBy('stock_ledger.created_at', 'ASC');

        // Apply attribute filtering if attributes are selected
        if ($selectedAttr->isNotEmpty()) {
            $approvedStockLedger->whereIn('stock_ledger_item_attributes.attribute_name', @$attributeGroups)
                ->whereIn('stock_ledger_item_attributes.attribute_value', @$selectedAttr)
                ->groupBy('stock_ledger.id')
                ->havingRaw('COUNT(DISTINCT stock_ledger_item_attributes.attribute_value) = ?', [count(@$selectedAttr)]);
        }

        $approvedStockLedger = $approvedStockLedger->get();
        
        $extraQty = 0;
        $balanceQty = $invoiceLedger->issue_qty;
        $receiptQty = 0;

        if ($approvedStockLedger->isNotEmpty()) {
            foreach ($approvedStockLedger as $val) {
                $stockLedger = StockLedger::find($val -> stock_ledger_id);
                if ($stockLedger->receipt_qty < $balanceQty) {
                    $receiptQty = $stockLedger->receipt_qty;
                    $balanceQty -= $receiptQty;
                } else {
                    $receiptQty = $balanceQty;
                    $extraQty = $stockLedger->receipt_qty - $balanceQty;
                    $balanceQty = 0; // Fully issued
                }

                // Update stock ledger for issued quantity
                $stockLedger->receipt_qty = $receiptQty;
                $stockLedger->utilized_id = $invoiceLedger->id;
                $stockLedger->utilized_date = $invoiceLedger->created_at->format('Y-m-d');
                $stockLedger->save();

                // Handle extra quantity by creating a new stock ledger entry
                if ($extraQty > 0) {
                    $newStockLedger = $stockLedger->replicate();
                    $newStockLedger->receipt_qty = $extraQty;
                    $newStockLedger->issue_qty = 0.00;
                    $newStockLedger->utilized_id = null;
                    $newStockLedger->utilized_date = null;
                    $newStockLedger->save();

                    // Copy stock ledger attributes to the new stock ledger
                    $attributes = StockLedgerItemAttribute::where('stock_ledger_id', $stockLedger->id)->get();
                    foreach ($attributes as $attribute) {
                        $ledgerAttribute = $attribute->replicate();
                        $ledgerAttribute->stock_ledger_id = $newStockLedger->id;
                        $ledgerAttribute->save();
                    }
                }

                // Stop the loop if the balance has been fully issued
                if ($balanceQty <= 0) {
                    break;
                }
            }
            $message = "Success";
        } else{
            $message = "This item does not have approved stocks, Please approve the mrn first.";
        }
        return $message;
    }

    // Delete Receipt Stock 
    public static function deleteReceiptStock($documentHeaderId, $documentDetailId, $itemId, $bookType, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();
        $code = 200;
        $status = 'success';
        $message = 'Stock ledger successfully deleted.';
        
        // Check if the stock is already utilized
        $stockLedger = StockLedger::where('organization_id', $user->organization_id)
            ->where('document_header_id', $documentHeaderId)
            ->where('document_detail_id', $documentDetailId)
            ->where('item_id', $itemId)
            ->where('book_type', $bookType)
            ->where('transaction_type', $transactionType)
            ->where('document_status', 'approved')
            ->whereNotNull('utilized_id')
            ->first();
        if ($stockLedger) {
            // If the stock is utilized, return an error
            return [
                'code' => 200,
                'status' => 'error',
                'message' => "Item ID $itemId cannot be deleted as it has already been utilized."
            ];
        } else {
            // Delete non-utilized stock ledgers
            $stockLedgers = StockLedger::where('organization_id', $user->organization_id)
                ->where('document_header_id', $documentHeaderId)
                ->where('document_detail_id', $documentDetailId)
                ->where('item_id', $itemId)
                ->where('book_type', $bookType)
                ->where('transaction_type', $transactionType)
                ->where('document_status', '!=', 'approved')
                ->whereNull('utilized_id')
                ->get();
            
            // dd($stockLedgers);    
            foreach ($stockLedgers as $ledger) {
                // dd($ledger);
                $ledger->attributes()->delete();
                $ledger->delete();
            }

            // dd('success');
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message
        ];
    }

    // Check Stock Status Item/Attributes/Store/Rack/Bin/Shelf Wise
    public static function checkItemStockStatus($documentHeaderId, $documentDetailId, $itemId, $selectedAttr, $quantity, $storeId, $rackId, $shelfId, $binId, $bookType, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();
        $flag = true; // Flag to track if we can proceed with changes
        $code = 200;
        $status = 'success';
        $message = 'Item is changeable as it is unutilized.';
        $detail = '';
        $attributeGroups = ErpAttribute::whereIn('id', $selectedAttr)->pluck('attribute_group_id');
        
        // Check if the stock is already utilized
        $isExistStockLedger = StockLedger::where('organization_id', $user->organization_id)
            ->where('document_header_id', $documentHeaderId)
            ->where('document_detail_id', $documentDetailId)
            ->where('item_id', $itemId)
            ->where('book_type', $bookType)
            ->where('transaction_type', $transactionType)
            ->where('document_status', 'approved')
            ->whereNotNull('utilized_id')
            ->first();
        if (!$isExistStockLedger) {
            // If the stock is utilized, return an error
            $detail = "Item";
            $flag = false;
        }

        // Check if specific attributes are used
        if ($selectedAttr && $flag) {   
            $stockWithAttributes = StockLedger::where('organization_id', $user->organization_id)
                ->where('document_header_id', $documentHeaderId)
                ->where('document_detail_id', $documentDetailId)
                ->where('item_id', $itemId)
                ->where('book_type', $bookType)
                ->where('transaction_type', $transactionType)
                ->where('document_status', 'approved')
                ->whereHas('attributes', function ($query) use ($selectedAttr, $attributeGroups) {
                    $query->whereIn('stock_ledger_item_attributes.attribute_value', $selectedAttr)
                        ->whereIn('stock_ledger_item_attributes.attribute_name', $attributeGroups)
                        ->groupBy('stock_ledger.id')
                        ->havingRaw('COUNT(DISTINCT stock_ledger_item_attributes.attribute_value) = ?', [count(@$selectedAttr)]);
                })
                ->first();
            
            if (!$stockWithAttributes) {
                $detail = "Attributes";
                $flag = false;
            }  
        }

        // Filters for Store, Rack, Shelf, and Bin
        if ($flag && $storeId) {
            $stockWithStore = StockLedger::where('organization_id', $user->organization_id)
                ->where('document_header_id', $documentHeaderId)
                ->where('document_detail_id', $documentDetailId)
                ->where('item_id', $itemId)
                ->where('store_id', $storeId)
                ->where('document_status', 'approved')
                ->first();

            if (!$stockWithStore) {
                $detail = "Store";
                $flag = false;
            }
        }

        if ($flag && $rackId) {
            $stockWithRack = StockLedger::where('organization_id', $user->organization_id)
                ->where('document_header_id', $documentHeaderId)
                ->where('document_detail_id', $documentDetailId)
                ->where('item_id', $itemId)
                ->where('rack_id', $rackId)
                ->where('document_status', 'approved')
                ->first();

            if (!$stockWithRack) {
                $detail = "Rack";
                $flag = false;
            }
        }

        if ($flag && $shelfId) {
            $stockWithShelf = StockLedger::where('organization_id', $user->organization_id)
                ->where('document_header_id', $documentHeaderId)
                ->where('document_detail_id', $documentDetailId)
                ->where('item_id', $itemId)
                ->where('shelf_id', $shelfId)
                ->where('document_status', 'approved')
                ->first();

            if (!$stockWithShelf) {
                $detail = "Shelf";
                $flag = false;
            }
        }

        if ($flag && $binId) {
            $stockWithBin = StockLedger::where('organization_id', $user->organization_id)
                ->where('document_header_id', $documentHeaderId)
                ->where('document_detail_id', $documentDetailId)
                ->where('item_id', $itemId)
                ->where('bin_id', $binId)
                ->where('document_status', 'approved')
                ->first();

            if (!$stockWithBin) {   
                $detail = "Bin";
                $flag = false;
            }
        }

        // Return message based on flag
        if (!$flag) {
            return [
                'code' => 200,
                'status' => 'error',
                'message' => "$detail cannot be changed as it has already been utilized."
            ];
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message
        ];
    }

    // Check Stock Status Item/Attributes/Store/Rack/Bin/Shelf Wise
    public static function checkItemStockQuantity($documentHeaderId, $documentDetailId, $itemId, $selectedAttr, $quantity, $storeId, $rackId, $shelfId, $binId, $bookType, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();
        $flag = true; // Flag to track if we can proceed with changes
        $code = 200;
        $status = 'success';
        $message = '';
        $approvedStock = 0.00;
        $attributeGroups = ErpAttribute::whereIn('id', $selectedAttr)->pluck('attribute_group_id');
        if($quantity){
            // dd($quantity);
            $stockWithQuantity = StockLedger::query()
                ->where('organization_id', $user->organization_id)
                ->where('document_header_id', $documentHeaderId)
                ->where('document_detail_id', $documentDetailId)
                ->where('book_type', $bookType)
                ->where('transaction_type', $transactionType)
                ->where('document_status', 'approved')
                ->whereNotNull('utilized_id')
                ->select([
                    'stock_ledger.*',
                    DB::raw('SUM(receipt_qty) as total_receipt_qty')
                ])
                ->groupBy([
                    'item_id',
                    'document_header_id',
                    'document_detail_id',
                ])
                ->first();
            // dd($stockWithQuantity->toArray());        
            
            if($stockWithQuantity){
                $approvedStock = $stockWithQuantity->total_receipt_qty;
                $bal = (int)$quantity - (int)$approvedStock;
                // dd([$quantity, $approvedStock, $bal]);
                if($bal < 0){
                    $status = 'error';
                    $message = 'Quantity can not be less than approved stock qty. which is '.$approvedStock;
                }
            }
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'approvedStock' => $approvedStock,
        ];
    }

    // Delete Issue Stock 
    public static function deleteIssueStock($documentHeaderId, $documentDetailId, $itemId, $bookType, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();
        $code = 200;
        $status = 'success';
        $message = 'Stock ledger successfully deleted.';

        // Get stock ledgers for the specified filters
        $stockLedgers = StockLedger::where('organization_id', $user->organization_id)
            ->where('document_header_id', $documentHeaderId)
            ->where('document_detail_id', $documentDetailId)
            ->where('item_id', $itemId)
            ->where('book_type', $bookType)
            ->where('transaction_type', $transactionType)
            ->whereNull('utilized_id')
            ->orderBy('id')
            ->get();

        if ($stockLedgers->isEmpty()) {
            return [
                'code' => 404,
                'status' => 'error',
                'message' => 'Stock ledger not found.'
            ];
        }

        // Store IDs of ledgers and attributes to delete
        $ledgerIdsToDelete = [];
        $attributeIdsToDelete = [];

        foreach ($stockLedgers as $ledger) {
            $utilizedRecord = StockLedger::where('organization_id', $user->organization_id)
                ->where('utilized_id', $ledger->id)
                ->first();

            if ($utilizedRecord) {
                $similarUtilizedRecord = StockLedger::where('organization_id', $user->organization_id)
                    ->where('document_header_id', $utilizedRecord->document_header_id)
                    ->where('document_detail_id', $utilizedRecord->document_detail_id)
                    ->where('item_id', $utilizedRecord->item_id)
                    ->where('book_type', $utilizedRecord->book_type)
                    ->where('transaction_type', $utilizedRecord->transaction_type)
                    ->whereNull('utilized_id')
                    ->first();

                if ($similarUtilizedRecord) {
                    // Merge quantities and reset utilization
                    $utilizedRecord->receipt_qty += $similarUtilizedRecord->receipt_qty;
                    $utilizedRecord->utilized_id = null;
                    $utilizedRecord->utilized_date = null;
                    $utilizedRecord->save();

                    // Add to batch delete
                    $ledgerIdsToDelete[] = $similarUtilizedRecord->id;
                    $attributeIdsToDelete = array_merge($attributeIdsToDelete, $similarUtilizedRecord->attributes()->pluck('id')->toArray());
                } else {
                    // Reset utilization for the utilized record
                    $utilizedRecord->utilized_id = null;
                    $utilizedRecord->utilized_date = null;
                    $utilizedRecord->save();
                }

                // Add current ledger and attributes to batch delete
                $ledgerIdsToDelete[] = $ledger->id;
                $attributeIdsToDelete = array_merge($attributeIdsToDelete, $ledger->attributes()->pluck('id')->toArray());
            } else {
                $status = 'error';
                $message = 'Utilized record not found.';
                break;
            }
        }

        // Delete all gathered attributes and ledgers in batch
        if (!empty($attributeIdsToDelete)) {
            StockLedgerItemAttribute::whereIn('id', $attributeIdsToDelete)->delete();
        }
        if (!empty($ledgerIdsToDelete)) {
            StockLedger::whereIn('id', $ledgerIdsToDelete)->delete();
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message
        ];

    }

    // Check Stock Status Item/Attributes/Store/Rack/Bin/Shelf Wise
    public static function checkIssueStockQuantity($documentHeaderId, $documentDetailId, $itemId, $selectedAttr, $quantity, $storeId, $rackId, $shelfId, $binId, $bookType, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();
        $flag = true; // Flag to track if we can proceed with changes
        $code = 200;
        $status = 'success';
        $message = '';
        $approvedStock = 0.00;
        $attributeGroups = ErpAttribute::whereIn('id', $selectedAttr)->pluck('attribute_group_id');
        if($quantity){
            // dd($quantity);
            $stockWithQuantity = StockLedger::query()
                ->where('organization_id', $user->organization_id)
                ->where('document_header_id', $documentHeaderId)
                ->where('document_detail_id', $documentDetailId)
                ->where('book_type', $bookType)
                ->where('transaction_type', $transactionType)
                ->where('document_status', 'approved')
                ->whereNotNull('utilized_id')
                ->select([
                    'stock_ledger.*',
                    DB::raw('SUM(receipt_qty) as total_receipt_qty'),
                    DB::raw('SUM(total_cost) as total_item_cost')
                ])
                ->groupBy([
                    'item_id',
                    'document_header_id',
                    'document_detail_id',
                ])
                ->first();
            // dd($stockWithQuantity->toArray());        
            
            if($stockWithQuantity){
                $approvedStock = $stockWithQuantity->total_receipt_qty;
                $bal = (int)$quantity - (int)$approvedStock;
                // dd([$quantity, $approvedStock, $bal]);
                if($bal < 0){
                    $status = 'error';
                    $message = 'Quantity can not be less than approved stock qty. which is '.$approvedStock;
                }
            }
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'approvedStock' => $approvedStock,
        ];
    }

    // Check Stock Status Item/Attributes/Store/Rack/Bin/Shelf Wise
    public static function changeIssueQuantity($documentHeaderId, $documentDetailId, $itemId, $quantity, $bookType, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();
        $flag = true; // Flag to track if we can proceed with changes
        $code = 200;
        $status = 'success';
        $message = 'Quantity Updated Successfuly.';
        $stockQty = 0;
        $records = '';
        
        // Check if the stock is already utilized
        $stockLedgers = StockLedger::where('organization_id', $user->organization_id)
            ->where('document_header_id', $documentHeaderId)
            ->where('document_detail_id', $documentDetailId)
            ->where('book_type', $bookType)
            ->where('transaction_type', $transactionType)
            ->whereNull('utilized_id')
            ->get();

        if ($stockLedgers->isEmpty()) {
            return [
                'code' => 404,
                'status' => 'error',
                'message' => 'Stock ledger not found.',
                'records' => ''
            ];
        }

        foreach ($stockLedgers as $ledger) {
            $utilizedRecord = StockLedger::where('organization_id', $user->organization_id)
                ->where('utilized_id', $ledger->id)
                ->first();

            if ($utilizedRecord) {
                $stockQty += $utilizedRecord->receipt_qty;
                $similarUtilizedRecord = StockLedger::where('organization_id', $user->organization_id)
                    ->where('document_header_id', $utilizedRecord->document_header_id)
                    ->where('document_detail_id', $utilizedRecord->document_detail_id)
                    ->where('book_type', $utilizedRecord->book_type)
                    ->where('transaction_type', $utilizedRecord->transaction_type)
                    ->whereNull('utilized_id')
                    ->first();

                if ($similarUtilizedRecord) {
                    // Merge quantities and reset utilization
                    $stockQty += $similarUtilizedRecord->receipt_qty;
                }
            }
        }

        if($quantity > $stockQty){
            return [
                'code' => 404,
                'status' => 'error',
                'message' => 'Quantity not available in stock ledger.',
                'records' => ''
            ];
        } else{
            $documentHeaderIds = $stockLedgers->pluck('document_header_id');
            $documentDetailIds = $stockLedgers->pluck('document_detail_id');
            $utilizedStockLedgers = StockLedger::select(
                [
                    'stock_ledger.*',
                    DB::raw('SUM(receipt_qty) as total_receipt_qty'),
                ]
            )
            ->where('organization_id', $user->organization_id)
            ->whereIn('document_header_id', $documentHeaderIds)
            ->whereIn('document_detail_id', $documentDetailIds)
            // ->orderBy('id', 'DESC')
            ->groupBy([
                'stock_ledger.document_header_id',
                'stock_ledger.document_detail_id',
                'stock_ledger.item_id',
                'stock_ledger.store_id',
                'stock_ledger.rack_id',
                'stock_ledger.shelf_id',
                'stock_ledger.bin_id'
            ])
            ->get();

            $remainingQuantity = $quantity;
            $lifoBreakup = [];
            $data = array();
            foreach ($utilizedStockLedgers as $stockSummary) {
                $availableQty = $stockSummary->total_receipt_qty;

                if ($availableQty <= 0) {
                    continue; // Skip if no available quantity
                }

                // Allocate quantity
                $allocatedQty = min($availableQty, $remainingQuantity);
                $lifoBreakup[] = [
                    'item_id' => $stockSummary->item_id,
                    'item_name' => $stockSummary->item_name,
                    'item_code' => $stockSummary->item_code,
                    'store_id' => $stockSummary->store_id,
                    'store' => $stockSummary->store,
                    'rack_id' => $stockSummary->rack_id,
                    'rack' => $stockSummary->rack,
                    'shelf_id' => $stockSummary->shelf_id,
                    'shelf' => $stockSummary->shelf,
                    'bin_id' => $stockSummary->bin_id,
                    'bin' => $stockSummary->bin,
                    'allocated_quantity' => $allocatedQty
                ];

                // Decrease remaining quantity
                $remainingQuantity -= $allocatedQty;

                // Stop if we've fulfilled the required quantity
                if ($remainingQuantity <= 0) {
                    break;
                }
            }

            // If remaining quantity is still greater than 0, it means not enough stock was available
            if ($remainingQuantity > 0) {
                $code = 202;
                $status = 'error';
                $message = 'Not enough stock available to fulfill the order quantity';
                $records = '';
            } else{
                $code = 200;
                $status = 'success';
                $message = 'Record fetched successfuly.';
                $records = $lifoBreakup;
            }
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'records' => $records
        ];
    }

    // Get Quantity In Issue Stock 
    private static function quantityBasedIssueStock($StockLedger, $quantity)
    {
        $user = Helper::getAuthenticatedUser();

        $availableStockData = StockLedger::where('organization_id', $user->organization_id)
            ->whereIn('document_header_id', $StockLedger['documentHeaderIds'])
            ->where('document_detail_id', $StockLedger['documentDetailIds'])
            ->where('transaction_type', 'receipt')
            ->whereNull('utilized_id')
            ->orderBy(['id', 'document_date'], 'DESC')
            ->get();


        $remainingQuantity = $quantity;
        $lifoBreakup = [];
        $data = array();
        foreach ($availableStockData as $stockSummary) {
            $availableQty = $stockSummary->receipt_qty;

            if ($availableQty <= 0) {
                continue; // Skip if no available quantity
            }

            // Allocate quantity
            $allocatedQty = min($availableQty, $remainingQuantity);
            $lifoBreakup[] = [
                'item_id' => $stockSummary->item_id,
                'item_name' => $stockSummary->item_name,
                'item_code' => $stockSummary->item_code,
                'store_id' => $stockSummary->store_id,
                'store' => $stockSummary->store,
                'rack_id' => $stockSummary->rack_id,
                'rack' => $stockSummary->rack,
                'shelf_id' => $stockSummary->shelf_id,
                'shelf' => $stockSummary->shelf,
                'bin_id' => $stockSummary->bin_id,
                'bin' => $stockSummary->bin,
                'allocated_quantity' => $allocatedQty
            ];

            // Decrease remaining quantity
            $remainingQuantity -= $allocatedQty;

            // Stop if we've fulfilled the required quantity
            if ($remainingQuantity <= 0) {
                break;
            }
        }

        // If remaining quantity is still greater than 0, it means not enough stock was available
        if ($remainingQuantity > 0) {
            $code = 202;
            $status = 'error';
            $message = 'Not enough stock available to fulfill the order quantity';
            $records = '';
        } else{
            $code = 200;
            $status = 'success';
            $message = 'Record fetched successfuly.';
            $records = $lifoBreakup;
        }        
    }

    public static function updateIssueStock($documentHeaderId, $documentDetailId, $bookType, $documentStatus, $transactionType)
    {

        $user = Helper::getAuthenticatedUser();
        $message = '';
        $transactionType = 'issue';
        $checkInvoiceLedger = self::updateIssueStockLedger($documentHeaderId, $documentDetailId, $bookType, $documentStatus, $transactionType);

    }

    // Get Quantity In Issue Stock 
    private static function updateIssueStockLedger($documentHeaderId, $documentDetailId, $bookType, $documentStatus, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();

        $documentItemLocations = ErpInvoiceItemLocation::where('sale_invoice_id',$documentHeaderId)
            ->where('invoice_item_id',$documentDetailId)
            ->with(
                'header',
                'detail',
                'detail.item',
                'detail.attributes'
            )
            ->get();

        if(isset($documentItemLocations) && $documentItemLocations){
            foreach ($documentItemLocations as $documentItemLocation) {
                $oldStockLedger = StockLedger::where('organization_id', $user->organization_id)
                    ->where('document_header_id', $documentItemLocation->document_header_id)
                    ->where('document_detail_id', $documentItemLocation->document_detail_id)
                    ->where('item_id', $documentItemLocation->item_id)
                    ->where('book_type', $documentItemLocation->book_type)
                    ->where('transaction_type', $documentItemLocation->transaction_type)
                    ->whereNull('utilized_id')
                    ->first();

                if ($oldStockLedger) {
                    $oldStockLedger->issue_qty = $documentItemLocation->quantity;
                    $oldStockLedger->save();
                } else {
                    // Reset utilization for the utilized record
                }
            }
        }
    }
    public static function addReturnedStock($documentHeaderId, $documentDetailId, $itemId, $bookType, $transactionType)
    {
        $user = Helper::getAuthenticatedUser();
        $code = 200;
        $status = 'success';
        $message = 'Stock ledger successfully updated.';

        // Get stock ledgers for the specified filters
        $stockLedgers = StockLedger::where('organization_id', $user->organization_id)
            ->where('document_header_id', $documentHeaderId)
            ->where('document_detail_id', $documentDetailId)
            ->where('item_id', $itemId)
            ->where('book_type', $bookType)
            ->where('transaction_type', $transactionType)
            ->whereNull('utilized_id')
            ->orderBy('id')
            ->get();

        if ($stockLedgers->isEmpty()) {
            return [
                'code' => 404,
                'status' => 'error',
                'message' => 'Stock ledger not found.'
            ];
        }

        // Store IDs of ledgers and attributes to delete
        $ledgerIdsToRestore = [];
        $attributeIdsToRestore = [];

        foreach ($stockLedgers as $ledger) {
            $utilizedRecord = StockLedger::where('organization_id', $user->organization_id)
                ->where('utilized_id', $ledger->id)
                ->first();

            if ($utilizedRecord) {
                $similarUtilizedRecord = StockLedger::where('organization_id', $user->organization_id)
                    ->where('document_header_id', $utilizedRecord->document_header_id)
                    ->where('document_detail_id', $utilizedRecord->document_detail_id)
                    ->where('item_id', $utilizedRecord->item_id)
                    ->where('book_type', $utilizedRecord->book_type)
                    ->where('transaction_type', $utilizedRecord->transaction_type)
                    ->whereNull('utilized_id')
                    ->first();

                if ($similarUtilizedRecord) {
                    // Merge quantities and reset utilization
                    $utilizedRecord->receipt_qty += $similarUtilizedRecord->receipt_qty;
                    $utilizedRecord->utilized_id = null;
                    $utilizedRecord->utilized_date = null;
                    $utilizedRecord->save();

                    // Add to batch delete
                    $ledgerIdsToRestore[] = $similarUtilizedRecord->id;
                    $attributeIdsToRestore = array_merge($attributeIdsToRestore, $similarUtilizedRecord->attributes()->pluck('id')->toArray());
                } else {
                    // Reset utilization for the utilized record
                    $utilizedRecord->utilized_id = null;
                    $utilizedRecord->utilized_date = null;
                    $utilizedRecord->save();
                }

                // Add current ledger and attributes to batch delete
                $ledgerIdsToRestore[] = $ledger->id;
                $attributeIdsToRestore = array_merge($attributeIdsToRestore, $ledger->attributes()->pluck('id')->toArray());
            } else {
                $status = 'error';
                $message = 'Utilized record not found.';
                break;
            }
        }

        // Delete all gathered attributes and ledgers in batch
        if (!empty($attributeIdsToRestore)) {
            StockLedgerItemAttribute::whereIn('id', $attributeIdsToRestore)->restore();
        }
        if (!empty($ledgerIdsToRestore)) {
            StockLedger::whereIn('id', $ledgerIdsToRestore)->restore();
        }

        return [
            'code' => $code,
            'status' => $status,
            'message' => $message
        ];

    }

}


