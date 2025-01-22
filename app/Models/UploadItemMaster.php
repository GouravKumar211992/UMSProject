<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadItemMaster extends Model
{
    use HasFactory;

    protected $table = 'upload_item_masters';


    protected $fillable = [
        'item_name',
        'item_code',
        'category_id',
        'subcategory_id',
        'hsn_id',
        'uom_id',
        'type',
        'min_stocking_level',
        'max_stocking_level',
        'reorder_level',
        'min_order_qty',
        'lead_days',
        'safety_days',
        'shelf_life_days',
        'status',
        'group_id',
        'company_id',
        'organization_id',
        'attributes',
        'specifications',
        'alternate_uoms',
        'sub_type',
        'remarks',
        'batch_no',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function hsn()
    {
        return $this->belongsTo(Hsn::class, 'hsn_id');
    }

    public function uom()
    {
        return $this->belongsTo(Unit::class, 'uom_id');
    }


}
