<?php

namespace App\Models;

use App\Helpers\Helper;
use App\Traits\DateFormatTrait;
use App\Traits\DefaultGroupCompanyOrg;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpSaleReturnItemLocationHistory extends Model
{
    use HasFactory, SoftDeletes, DefaultGroupCompanyOrg, FileUploadTrait, DateFormatTrait;

    protected $fillable = [
        'sale_return_id',
        'sr_item_id',
        'item_id',
        'item_code',
        'store_id',
        'store_code',
        'rack_id',
        'rack_code',
        'shelf_id',
        'shelf_code',
        'bin_id',
        'bin_code',
        'returned_qty',
        'inventory_uom_qty',
    ];
    public static function boot()
    {
        parent::boot();
            static::creating(function ($model) {
                $user = Helper::getAuthenticatedUser();
                $model->created_by = $user->id;
            });

            static::updating(function ($model) {
                $user = Helper::getAuthenticatedUser();
                $model->updated_by = $user->id;
            });

            static::deleting(function ($model) {
                $user = Helper::getAuthenticatedUser();
                $model->deleted_by = $user->id;
            });
    }

    protected $hidden = ['deleted_at'];

    
    public $referencingRelationships = [
        'erpStore' => 'store_id',
        'erpRack' => 'rack_id',
        'erpShelf' => 'shelf_id',
        'erpBin' => 'bin_id'
    ];

    public function header()
    {
        return $this -> belongsTo(ErpSaleInvoice::class, 'sale_invoice_id');
    }

    public function detail()
    {
        return $this -> belongsTo(ErpInvoiceItem::class, 'invoice_item_id');
    }

    public function erpStore()
    {
        return $this->belongsTo(ErpStore::class, 'store_id');
    }

    public function erpRack()
    {
        return $this->belongsTo(ErpRack::class, 'rack_id');
    }

    public function erpShelf()
    {
        return $this->belongsTo(ErpShelf::class, 'shelf_id');
    }

    public function erpBin()
    {
        return $this->belongsTo(ErpBin::class, 'bin_id');
    }

}
