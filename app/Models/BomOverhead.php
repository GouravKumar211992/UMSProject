<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomOverhead extends Model
{
    use HasFactory;

    protected $table = 'erp_bom_overheads';

    protected $fillable = [
        'bom_id',
        'bom_detail_id',
        'type',
        'overhead_description',
        'ledger_name',
        'overhead_amount'
    ];

    public function bom()
    {
        return $this->belongsTo(Bom::class,'bom_id');
    }

    public function bomItem()
    {
        return $this->belongsTo(BomDetail::class, 'bom_detail_id');
    } 
}
