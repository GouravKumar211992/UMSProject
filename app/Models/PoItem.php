<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoItem extends Model
{
    use HasFactory;

    protected $table = 'erp_po_items';

    protected $fillable = [
        'purchase_order_id',
        'pi_item_id',
        'po_item_id',
        'item_id',
        'item_code',
        'hsn_id',
        'hsn_code',
        'uom_id',
        'uom_code',
        'order_qty',
        'grn_qty',
        'inventory_uom_id',
        'inventory_uom_code',
        'inventory_uom_qty',
        'rate',
        'item_discount_amount',
        'header_discount_amount',
        'tax_amount',
        'expense_amount',
        'company_currency_id',
        'company_currency_exchange_rate',
        'group_currency_id',
        'group_currency_exchange_rate',
        'remarks'
    ];

    public $referencingRelationships = [
        'item' => 'item_id',
        'uom' => 'uom_id',
        'hsn' => 'hsn_id',
        'inventoryUom' => 'inventory_uom_id'
    ];

    protected $appends = [
        'cgst_value',
        'sgst_value',
        'igst_value'
    ];

    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function pi_item()
    {
        return $this->belongsTo(PiItem::class, 'pi_item_id');
    }

    public function po_item()
    {
        return $this->belongsTo(PoItem::class, 'po_item_id');
    }

    public function si_item()
    {
        return $this->hasOne(PoItem::class, 'po_item_id');
    }

    public function mrn_details()
    {
        return $this->hasMany(PoItem::class,'purchase_order_item_id');
    }

    public function uom()
    {
        return $this->belongsTo(Unit::class, 'uom_id');
    }

    public function inventoryUom()
    {
        return $this->belongsTo(Unit::class, 'inventory_uom_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    
    public function items()
    {
        return $this->hasOne(Item::class, 'item_id');
    }
    
    public function hsn()
    {
        return $this->belongsTo(Hsn::class, 'hsn_id');
    }

    // After item discount and header discount
    public function getAssessmentAmountTotalAttribute()
    {
        return ($this->order_qty * $this->rate) - ($this->item_discount_amount - $this->header_discount_amount);
    }

    public function getAssessmentAmountItemAttribute()
    {
        return ($this->order_qty * $this->rate) - ($this->item_discount_amount);
    }

    // After item discount
    public function getAssessmentAmountHeaderAttribute()
    {
        return ($this->order_qty * $this->rate) - ($this->item_discount_amount);
    }

    public function getTotalItemValueAttribute()
    {
        return ($this->order_qty * $this->rate);
    }

    public function getTotalDiscValueAttribute()
    {
        return ($this->item_discount_amount + $this->header_discount_amount);
    }

    public function attributes()
    {
        return $this->hasMany(PoItemAttribute::class,'po_item_id')->with(['headerAttribute', 'headerAttributeValue']);
    }

    public function teds()
    {
        return $this->hasMany(PurchaseOrderTed::class,'po_item_id');
    }

    public function ted_tax()
    {
        return $this->hasOne(PurchaseOrderTed::class,'po_item_id')->where('ted_type','Tax')->latest();
    }

    public function itemDelivery()
    {
        return $this->hasMany(PoItemDelivery::class,'po_item_id');
    }

    /*Detail level*/
    public function itemDiscount()
    {
        return $this->hasMany(PurchaseOrderTed::class,'po_item_id')->where('ted_level', 'D')->where('ted_type','Discount');
    }

    /*Header Level Discount*/
    public function headerDiscount()
    {
        return $this->hasMany(PurchaseOrderTed::class)->where('ted_level', 'H')->where('ted_type','Discount');
    }

    public function taxes()
    {
        return $this->hasMany(PurchaseOrderTed::class,'po_item_id')->where('ted_type','Tax');
    }

    public function getCgstValueAttribute()
    {
        $tedRecords = PurchaseOrderTed::where('po_item_id', $this->id)
            ->where('purchase_order_id', $this->purchase_order_id)
            ->where('ted_type', '=', 'Tax')
            ->where('ted_level', '=', 'D')
            ->where('ted_name', '=', 'CGST')
            ->sum('ted_amount');

        $tedRecord = PurchaseOrderTed::with(['taxDetail'])
            ->where('po_item_id', $this->id)
            ->where('purchase_order_id', $this->purchase_order_id)
            ->where('ted_type', '=', 'Tax')
            ->where('ted_level', '=', 'D')
            ->where('ted_name', '=', 'CGST')
            ->first();


        return [
            'rate' => @$tedRecord->taxDetail->tax_percentage,
            'value' => $tedRecords ?? 0.00
        ];
    }

    public function getSgstValueAttribute()
    {
        $tedRecords = PurchaseOrderTed::where('po_item_id', $this->id)
            ->where('ted_type', '=', 'Tax')
            ->where('ted_level', '=', 'D')
            ->where('ted_name', '=', 'SGST')
            ->sum('ted_amount');

            $tedRecord = PurchaseOrderTed::with(['taxDetail'])
            ->where('po_item_id', $this->id)
            ->where('purchase_order_id', $this->purchase_order_id)
            ->where('ted_type', '=', 'Tax')
            ->where('ted_level', '=', 'D')
            ->where('ted_name', '=', 'SGST')
            ->first();


        return [
            'rate' => @$tedRecord->taxDetail->tax_percentage,
            'value' => $tedRecords ?? 0.00
        ];
    }

    public function getIgstValueAttribute()
    {
        $tedRecords = PurchaseOrderTed::where('po_item_id', $this->id)
            ->where('ted_type', '=', 'Tax')
            ->where('ted_level', '=', 'D')
            ->where('ted_name', '=', 'IGST')
            ->sum('ted_amount');

            $tedRecord = PurchaseOrderTed::with(['taxDetail'])
            ->where('po_item_id', $this->id)
            ->where('purchase_order_id', $this->purchase_order_id)
            ->where('ted_type', '=', 'Tax')
            ->where('ted_level', '=', 'D')
            ->where('ted_name', '=', 'IGST')
            ->first();


        return [
            'rate' => @$tedRecord->taxDetail->tax_percentage,
            'value' => $tedRecords ?? 0.00
        ];
    }

    public function supplierPoItems()
    {
        return $this->hasMany(PoItem::class, 'po_item_id');
    }

    public function pi_item_mappings()
    {
        return $this->hasMany(PiPoMapping::class,'po_item_id');
    }
}
