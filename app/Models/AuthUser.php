<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthUser extends Model
{
    protected $connection = 'mysql_master';

    protected $fillable = [
        'organization_id', 
        'organization_name', 
        'organization_alias', 
        'email', 
        'mobile', 
        'db_name',
        'status',
    ];

    // public function organization() {
    //     return $this->belongsTo(Organization::class);
    // }
}
