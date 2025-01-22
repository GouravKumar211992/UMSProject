<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PaymentVoucherDetails extends Model
{
    use HasFactory;
    protected $table = 'erp_payment_voucher_details';
    protected $fillable = ['party_type', 'party_id'];

    public function party()
    {
        return $this->morphTo();
    }
}
