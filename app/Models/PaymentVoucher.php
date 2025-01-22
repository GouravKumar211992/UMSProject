<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Deletable;
use App\Traits\DefaultGroupCompanyOrg;


class PaymentVoucher extends Model
{
    use HasFactory,Deletable,DefaultGroupCompanyOrg,Deletable;
    public $referencingRelationships = [
        'currency' => 'currency_id',
        'ledger' => 'ledger_id',
        'bank' => 'bank_id'
    ];

    protected $guarded = ['id'];


    protected $table = 'erp_payment_vouchers';

    public function series()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'ledger_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function details()
    {
        return $this->hasMany(PaymentVoucherDetails::class,'payment_voucher_id');
    }

    public function approvals()
    {
        return $this->hasMany(ApprovalProcess::class);
    }
}
