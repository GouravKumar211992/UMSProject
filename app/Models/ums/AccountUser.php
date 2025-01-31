<?php

namespace App\models\ums;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class AccountUser extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'users';
    protected $fillable = [
        'first_name', 
        'last_name', 
        'email', 
        'user_name', 
        'mobile',
        'organization_id',
        'gender',
        'date_of_birth',
        'marital_status',
        'remember_token'
    ];
}
