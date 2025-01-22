<?php

namespace App\Models;

use App\Helpers\ConstantHelper;
use App\Helpers\Helper;
use App\Traits\DateFormatTrait;
use App\Traits\DefaultGroupCompanyOrg;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseIndent extends Model
{
    use HasFactory,DateFormatTrait,DefaultGroupCompanyOrg,FileUploadTrait;

    protected $table = 'erp_purchase_indents';
    
    protected $fillable = [
        'organization_id', 
        'group_id', 
        'company_id',
        'department_id',
        'book_id', 
        'book_code', 
        'document_number',
        'document_date',
        'revision_number',
        'revision_date',
        'reference_number',
        'vendor_id',
        'vendor_code',
        'vendor_name',
        'document_status',
        'approval_level',
        'remarks',
        'org_currency_id',
        'org_currency_code',
        'org_currency_exg_rate',
        'comp_currency_id',
        'comp_currency_code',
        'comp_currency_exg_rate',
        'group_currency_id',
        'group_currency_code',
        'group_currency_exg_rate'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $user = Helper::getAuthenticatedUser();
            if ($user) {
                $model->created_by = $user->id;
            }
        });

        static::updating(function ($model) {
            $user = Helper::getAuthenticatedUser();
            if ($user) {
                $model->updated_by = $user->id;
            }
        });

        static::deleting(function ($model) {
            $user = Helper::getAuthenticatedUser();
            if ($user) {
                $model->deleted_by = $user->id;
            }
        });
    }

    public $referencingRelationships = [
        'book' => 'book_id'
    ];

    public function media()
    {
        return $this->morphMany(PurchaseIndentMedia::class, 'model');
    }

    public function getDisplayStatusAttribute()
    {
        $status = str_replace('_', ' ', $this->document_status);
        return ucwords($status);
    }
    
    public function getDocumentStatusAttribute()
    {
        if ($this->attributes['document_status'] == ConstantHelper::APPROVAL_NOT_REQUIRED) {
            return ConstantHelper::APPROVED;
        }
        return $this->attributes['document_status'];
    }
    
    public function source()
    {
        return $this->hasOne(PurchaseIndentHistory::class, 'source_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function pi_items()
    {
        return $this->hasMany(PiItem::class, 'pi_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(Employee::class,'created_by','id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
