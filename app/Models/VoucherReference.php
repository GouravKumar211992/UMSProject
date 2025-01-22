<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherReference extends Model
{
    use HasFactory;
    protected $table = 'erp_voucher_references';

    protected $fillable = [
        'voucher_details_id',
        'party_id',
        'voucher_id',
        'amount'
    ];

    public function voucherDetail()
    {
        return $this->belongsTo(PaymentVoucherDetails::class);
    }
}
