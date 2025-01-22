<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpItemAttribute extends Model
{
    use HasFactory;

    public function group()
    {
        return $this -> hasOne(ErpAttributeGroup::class, 'id', 'attribute_group_id');
    }
}
