<?php
namespace App\models\ums;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class ServiceUser extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $fillable = [
        'organization_id', 
        'user_id', 
        'service_id',
    ];
}
