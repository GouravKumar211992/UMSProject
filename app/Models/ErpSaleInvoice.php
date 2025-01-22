<?php

namespace App\Models;

use App\Helpers\ConstantHelper;
use App\Helpers\Helper;
use App\Traits\DateFormatTrait;
use App\Traits\DefaultGroupCompanyOrg;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpSaleInvoice extends Model
{
    use HasFactory, SoftDeletes, DefaultGroupCompanyOrg, FileUploadTrait, DateFormatTrait;

    protected $fillable = [
        'organization_id',
        'group_id',
        'company_id',
        'book_id',
        'invoice_required',
        'book_code',
        'document_type',
        'document_number',
        'document_date',
        'revision_number',
        'prefix',
        'suffix',
        'doc_no',
        'revision_date',
        'reference_number',
        'customer_id',
        'customer_code',
        'consignee_name',
        'consignment_no',
        'eway_bill_no',
        'transporter_name',
        'vehicle_no',
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
        'payment_terms' => 'payment_term_id'
    ];

    protected $hidden = ['deleted_at'];

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
        return $this->morphMany(ErpSiMedia::class, 'model');
    }
    public function media_files()
    {
        return $this->morphMany(ErpSiMedia::class, 'model') -> select('id', 'model_type', 'model_id', 'file_name');
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
        return $this -> hasMany(ErpInvoiceItem::class, 'sale_invoice_id');
    }

    public function expense_ted()
    {
        return $this -> hasMany(ErpSaleInvoiceTed::class, 'sale_invoice_id') -> where('ted_level', 'H') -> where('ted_type', 'Expense');
    }
    public function discount_ted()
    {
        return $this -> hasMany(ErpSaleInvoiceTed::class, 'sale_invoice_id') -> where('ted_level', 'H') -> where('ted_type', 'Discount');
    }
    public function billing_address_details()
    {
        return $this->morphOne(ErpAddress::class, 'addressable', 'addressable_type', 'addressable_id') -> where('type', 'billing');
    }
    public function shipping_address_details()
    {
        return $this->morphOne(ErpAddress::class, 'addressable', 'addressable_type', 'addressable_id') -> where('type', 'shipping');
    }
    public function getDocumentStatusAttribute()
    {
        if ($this->attributes['document_status'] == ConstantHelper::APPROVAL_NOT_REQUIRED) {
            return ConstantHelper::APPROVED;
        }
        return $this->attributes['document_status'];
    }
    public function item_locations()
    {
        return $this -> hasMany(ErpInvoiceItemLocation::class, 'sale_invoice_id');
    }

    // public function sale_order_id()
    // {
    //     $item = $this -> items() -> first();
    //     $saleOrderId = $item -> sale_order_id;
    //     return $saleOrderId;
    // }

    public function sale_order_items()
    {
        $item = $this -> items() -> first();
        $saleOrderId = $item -> sale_order_id;
        $saleOrderItems = collect([]);
        if ($saleOrderId) {
            $saleOrderItems = ErpSoItem::where('sale_order_id', $saleOrderId) -> with(['discount_ted', 'tax_ted']) -> with(['item' => function ($itemQuery) {
                $itemQuery -> with(['specifications', 'alternateUoms.uom', 'uom', 'hsn']);
            }]) -> get();
        }
        return $saleOrderItems;
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
    public function book()
    {
        return $this -> belongsTo(Book::class, 'book_id');
    }
}
