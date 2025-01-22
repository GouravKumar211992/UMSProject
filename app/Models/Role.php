<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    public function permissions(){
        return $this->belongsToMany(PermissionMaster::class, 'role_permission_master', 'role_id', 'permission_id');
    }
}
