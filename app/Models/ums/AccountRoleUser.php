<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class AccountRoleUser extends Model
{
    protected $table = 'role_user';
    protected $connection = 'mysql-accounts';

    protected $fillable = [
        'role_id',
        'user_id'
    ];
}
