<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    protected $table = 'erp_item_details';

    use HasFactory;
    protected $guarded = [];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class,'voucher_id','id');
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }

    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }
}
