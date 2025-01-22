<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    protected $table = 'erp_cost_centers';

    use HasFactory;

    protected $fillable = [
        'name',
        'cost_group_id',
        'status',
        'group_id',
        'company_id',
        'organization_id'
    ];

    public function group()
    {
        return $this->belongsTo(CostGroup::class, 'cost_group_id');
    }
    public function itemDetail(){
        return $this->hasOne(ItemDetail::class, 'cost_center_id');
    }
}
