<?php
namespace App\Models;

use App\Models\User;
use App\Helpers\Helper;
use App\Models\Address;
use App\Models\Customer;
use App\Models\InvoiceBook;
use App\Models\Organization;
use App\Traits\DateFormatTrait;
use App\Traits\FileUploadTrait;
use App\Traits\DefaultGroupCompanyOrg;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PRHeaderHistory extends Model
{
    use HasFactory, SoftDeletes, DateFormatTrait, FileUploadTrait,DefaultGroupCompanyOrg;

    protected $table = 'erp_purchase_return_headers_history';
    protected $fillable = [
        'header_id', 
        'organization_id', 
        'group_id', 
        'company_id', 
        'series_id', 
        'book_id', 
        'book_code', 
        'doc_number_type', 
        'doc_reset_pattern', 
        'doc_prefix', 
        'doc_suffix', 
        'doc_no', 
        'vendor_id', 
        'vendor_code', 
        'customer_id', 
        'customer_code', 
        'document_number', 
        'document_date', 
        'document_status', 
        'revision_number', 
        'revision_date', 
        'approval_level', 
        'reference_number', 
        'supplier_invoice_no', 
        'supplier_invoice_date', 
        'billing_to', 
        'ship_to', 
        'billing_address', 
        'shipping_address', 
        'currency_id', 
        'currency_code', 
        'payment_term_id', 
        'payment_term_code', 
        'transaction_currency', 
        'org_currency_id', 
        'org_currency_code', 
        'org_currency_exg_rate', 
        'comp_currency_id', 
        'comp_currency_code', 
        'comp_currency_exg_rate', 
        'group_currency_id', 
        'group_currency_code', 
        'group_currency_exg_rate', 
        'sub_total', 
        'total_item_amount', 
        'item_discount', 
        'header_discount', 
        'total_discount', 
        'gst', 
        'gst_details', 
        'taxable_amount', 
        'total_taxes', 
        'total_after_tax_amount', 
        'expense_amount', 
        'total_amount', 
        'final_remark', 
        'status', 
        'created_by', 
        'updated_by', 
        'deleted_by'
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'gst_details' => 'array',
    ];

    public static function boot()
    {
        parent::boot();
        $user = Helper::getAuthenticatedUser();
        if($user) {
            static::creating(function ($model) use($user) {
                $model->created_by = $user->id;
            });

            static::updating(function ($model) use($user) {
                $model->updated_by = $user->id;
            });

            static::deleting(function ($model) use($user) {
                $model->deleted_by = $user->id;
            });
        }
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function company()
    {
        return $this->belongsTo(OrganizationCompany::class, 'company_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function header()
    {
        return $this->belongsTo(PRHeader::class, 'header_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'series_id');
    }

    public function paymentTerms()
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->hasMany(PRDetailHistory::class, 'header_history_id');
    }

    public function attributes()
    {
        return $this->hasMany(PRItemAttributeHistory::class, 'header_history_id');
    }

    public function pbTed()
    {
        return $this->hasMany(PRTedHistory::class, 'header_history_id');
    }

    public function ship_address()
    {
        return $this->belongsTo(ErpAddress::class,'shipping_address');
    }

    public function bill_address()
    {
        return $this->belongsTo(ErpAddress::class,'billing_address');
    }

    public function billingAddress()
    {
        return $this->belongsTo(ErpAddress::class, 'billing_to');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ErpAddress::class, 'ship_to')->with(['city', 'state', 'country']);
    }

    public function attachment(): void
    {
        $this->addMediaCollection('attachment');
    }

    public function organizationAddress()
    {
        return $this->morphOne(Address::class, 'addressable')->where('type', 'default');
    }

    public function billingPartyAddress()
    {
        return $this->morphOne(Address::class, 'addressable')->where('type', 'billing');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerm::class,'payment_term_id');
    }

    public function getTotalAmountAttribute()
    {
        return ($this->total_item_amount - $this->total_discount);
    }

    /*Header Level Discount*/
    public function headerDiscount()
    {
        return $this->hasMany(PRTedHistory::class, 'header_history_id')->where('ted_level', 'H')->where('ted_type','Discount');
    }

    /*Total discount header level total_header_disc_amount*/
    public function getTotalHeaderDiscAmountAttribute()
    {
        return $this->headerDiscount()->sum('ted_amount');
    }

    public function expenses()
    {
        return $this->hasMany(PRTedHistory::class,'header_history_id')->where('ted_type', '=', 'PR')
            ->where('ted_level', '=', 'H');
    }

    public function getTotalExpAssessmentAmountAttribute()
    {
        return ($this->total_item_amount + $this->total_taxes - $this->total_discount);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }
    
}
