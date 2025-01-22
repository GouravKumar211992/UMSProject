<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\DefaultGroupCompanyOrg;
use App\Traits\Deletable;


class Voucher extends Model
{
    protected $table = 'erp_vouchers';

    use HasFactory,DefaultGroupCompanyOrg,Deletable;
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->document_status = $model->approvalStatus;
            $model->approval_level = $model->approvalLevel;
        });
    }


    // protected $fillable = [
    //     'voucher_no',
    //     'voucher_name',
    //     'book_type_id',
    //     'date',
    //     'book_id',
    //     'document',
    //     'note',
    //     'remarks',
    //     'group_id',
    //     'company_id',
    //     'organization_id',
    //     'status',
    //     'approvalLevel',
    //     'approvalStatus'
    // ];

    protected $fillable = [
        'voucher_no',
        'document_date',
        'doc_number_type',
        'doc_reset_pattern',
        'doc_prefix',
        'doc_suffix',
        'doc_no',
        'voucher_name',
        'book_type_id',
        'book_id',
        'date',
        'amount',
        'currency_id',
        'currency_code',
        'org_currency_id',
        'org_currency_code',
        'org_currency_exg_rate',
        'comp_currency_id',
        'comp_currency_code',
        'comp_currency_exg_rate',
        'group_currency_id',
        'group_currency_code',
        'group_currency_exg_rate',
        'reference_service',
        'reference_doc_id',
        'document',
        'remarks',
        'group_id',
        'company_id',
        'organization_id',
        'created_at',
        'updated_at',
        'voucherable_type',
        'voucherable_id',
        'approvalLevel',
        'approvalStatus',
        'document_status',
        'revision_number',
        'revision_date',
    ];

    public function documents()
    {
        return $this->belongsTo(OrganizationService::class, 'book_type_id');
    }

    public function series()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function items()
    {
        return $this->hasMany(ItemDetail::class);
    }

    public function approvals()
    {
        return $this->hasMany(ApprovalProcess::class);
    }

    public function voucherable()
    {
        return $this->morphTo();
    }
}
