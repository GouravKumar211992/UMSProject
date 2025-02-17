<?php

namespace App\Models;

use App\Helpers\ConstantHelper;
use App\Helpers\Helper;
use App\Traits\DateFormatTrait;
use App\Traits\DefaultGroupCompanyOrg;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpSaleOrder extends Model
{
    use HasFactory, DefaultGroupCompanyOrg, FileUploadTrait, DateFormatTrait;

    protected $fillable = [
        'organization_id',
        'group_id',
        'company_id',
        'book_id',
        'book_code',
        'document_type',
        'document_number',
        'doc_number_type',
        'doc_reset_pattern',
        'doc_prefix',
        'doc_suffix',
        'doc_no',
        'document_date',
        'revision_number',
        'revision_date',
        'reference_number',
        'customer_id',
        'customer_code',
        'consignee_name',
        'billing_address',
        'shipping_address',
        'currency_id',
        'currency_code',
        'payment_term_id',
        'payment_term_code',
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
        'group_currency_exg_rate',
        'total_item_value',
        'total_discount_value',
        'total_tax_value',
        'total_expense_value',
        'total_amount'
    ];

    
    public $referencingRelationships = [
        'customer' => 'customer_id',
        'currency' => 'currency_id',
        'paymentTerms' => 'payment_term_id'
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

    public function media()
    {
        return $this->morphMany(ErpSoMedia::class, 'model');
    }
    public function media_files()
    {
        return $this->morphMany(ErpSoMedia::class, 'model') -> select('id', 'model_type', 'model_id', 'file_name');
    }

    public function cust()
    {
        return $this -> hasOne(Customer::class, 'id', 'customer_id');
    }
    public function customer()
    {
        return $this -> hasOne(ErpCustomer::class, 'id', 'customer_id');
    }

    public function currency()
    {
        return $this -> hasOne(ErpCurrency::class, 'id', 'currency_id');
    }
    
    public function payment_terms()
    {
        return $this -> hasOne(ErpPaymentTerm::class, 'id', 'payment_term_id');
    }
    public function items()
    {
        return $this -> hasMany(ErpSoItem::class, 'sale_order_id');
    }

    public function expense_ted()
    {
        return $this -> hasMany(ErpSaleOrderTed::class, 'sale_order_id') -> where('ted_level', 'H') -> where('ted_type', 'Expense');
    }
    public function tax_ted()
    {
        return $this->hasMany(ErpSaleOrderTed::class,'sale_order_id')->where('ted_type','Tax');
    }
    public function discount_ted()
    {
        return $this -> hasMany(ErpSaleOrderTed::class, 'sale_order_id') -> where('ted_level', 'H') -> where('ted_type', 'Discount');
    }
    public function billing_address_details()
    {
        return $this->morphOne(ErpAddress::class, 'addressable', 'addressable_type', 'addressable_id') -> where('type', 'billing');
    }
    public function shipping_address_details()
    {
        return $this->morphOne(ErpAddress::class, 'addressable', 'addressable_type', 'addressable_id') -> where('type', 'shipping')->with(['city', 'state', 'country']);
    }
    public function getDocumentStatusAttribute()
    {
        if ($this->attributes['document_status'] == ConstantHelper::APPROVAL_NOT_REQUIRED) {
            return ConstantHelper::APPROVED;
        }
        return $this->attributes['document_status'];
    }
    public function getDisplayStatusAttribute()
    {
        $status = str_replace('_', ' ', $this->document_status);
        return ucwords($status);
    }
    public function addresses()
    {
        return $this->morphMany(ErpAddress::class, 'addressable', 'addressable_type', 'addressable_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(Employee::class,'created_by','id');
    }
}
