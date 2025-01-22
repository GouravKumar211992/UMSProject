<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiItem extends Model
{
    use HasFactory;

    protected $table = 'erp_pi_items';

    protected $fillable = [
        'pi_id',
        'item_id',
        'item_code',
        'hsn_id',
        'hsn_code',
        'uom_id',
        'uom_code',
        'order_qty',
        'indent_qty',
        // 'grn_qty',
        'inventory_uom_id',
        'inventory_uom_code',
        'inventory_uom_qty',
        'vendor_id',
        'vendor_code',
        'vendor_name',
        'remarks'
    ];

    public $referencingRelationships = [
        'item' => 'item_id',
        'uom' => 'uom_id',
        'hsn' => 'hsn_id',
        'inventoryUom' => 'inventory_uom_id',
        'vendor' => 'vendor_id',
    ];
    
    public function pi()
    {
        return $this->belongsTo(PurchaseIndent::class, 'pi_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
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

    public function hsn()
    {
        return $this->belongsTo(Hsn::class, 'hsn_id');
    }

    public function attributes()
    {
        return $this->hasMany(PiItemAttribute::class,'pi_item_id');
    }

    public function po_item()
    {
        return $this->hasOne(PoItem::class,'pi_item_id','id');
    }

    public function po_items()
    {
        return $this->hasMany(PoItem::class,'pi_item_id');
    }

    public function itemDelivery()
    {
        return $this->hasMany(PiItemDelivery::class,'pi_item_id');
    }

    public function getBalenceQtyAttribute()
    {
        return $this->indent_qty - ($this->order_qty ?? 0);
    }

    public function so_pi_mapping_item()
    {
        return $this->hasMany(PiSoMappingItem::class,'pi_item_id');
    }
}
