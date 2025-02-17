<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DefaultGroupCompanyOrg;
use App\Traits\Deletable;

class Item extends Model
{
    use HasFactory, Deletable,DefaultGroupCompanyOrg;

    protected $table = 'erp_items';

    protected $fillable = [
        'type',
        'unit_id',
        'hsn_id',
        'category_id',
        'subcategory_id',
        'item_code',
        'item_name',
        'item_initial',
        'item_remark',
        'uom_id',
        'cost_price',
        'sell_price',
        'min_stocking_level',
        'max_stocking_level',
        'reorder_level',
        'minimum_order_qty',
        'lead_days',
        'safety_days',
        'shelf_life_days',
        'group_id',     
        'company_id',       
        'organization_id',
        'service_type',
        'status',
    ];

    public function uom()
    {
        return $this->belongsTo(Unit::class, 'uom_id');
    }
    
    public function alternateUOMs()
    {
        return $this->hasMany(AlternateUOM::class);
    }


    public function hsn()
    {
        return $this->belongsTo(Hsn::class, 'hsn_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function subTypes()
    {
        return $this->belongsToMany(SubType::class, 'erp_item_subtypes');
    }
    public function inventoryDetails()
    {
        return $this->hasOne(InventoryDetail::class);
    }

    public function approvedCustomers()
    {
        return $this->hasMany(CustomerItem::class);
    }

    public function approvedVendors()
    {
        return $this->hasMany(VendorItem::class);
    }

    public function attributes()
    {
        return $this->hasMany(ErpAttribute::class);
    }

    public function itemAttributes()
    {
        return $this->hasMany(ItemAttribute::class);
    }
    public function alternateItems()
    {
        return $this->hasMany(AlternateItem::class);
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function specifications()
    {
        return $this->hasMany(ItemSpecification::class);
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }
}
