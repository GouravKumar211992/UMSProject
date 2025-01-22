<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomOverheadHistory extends Model
{
    use HasFactory;

    protected $table = 'erp_bom_overheads_history';

    protected $fillable = [
        'bom_id',
        'source_id',
        'bom_detail_id',
        'type',
        'overhead_description',
        'ledger_name',
        'overhead_amount'
    ];
}
