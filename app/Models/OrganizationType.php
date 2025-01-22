<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationType extends Model
{
    use HasFactory;
    
    protected $table = 'erp_organization_types';

    protected $fillable = [
        'name',
        'status', 
    ];
}
