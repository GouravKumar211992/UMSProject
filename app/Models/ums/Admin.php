<?php

namespace App\Models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;  // Keep this]
use Spatie\MediaLibrary\InteractsWithMedia;

use Illuminate\Notifications\Notifiable;
<<<<<<< HEAD
use Laravel\Passport\HasApiTokens;
=======
// use Laravel\Passport\HasApiTokens;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements HasMedia
{
<<<<<<< HEAD
    use HasApiTokens, Notifiable, SoftDeletes,InteractsWithMedia;
=======
    use  Notifiable, SoftDeletes,InteractsWithMedia;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

    protected $guard = 'admins';

    protected $fillable = [
        'name',
        'user_name',
        'password',
        'has_password',
        'email',
        'role',
        'is_email_verified',
        'mobile',
        'is_mobile_verified',
        'two_step_verification',
        'remember_token',
        'date_of_birth',
        'gender',
        'marital_status',
        'status',
        'company_name',
        'tax_exempt',
        'currency_id',
        'country_id',
        'social_link_id',
        'reseller_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password', 'remember_token', 'deleted_at',
    ];

    public function setPasswordAttribute($password)
    {
        if (!is_null($password))
            $this->attributes['password'] = bcrypt($password);
    }

    // Implement the required methods from HasMedia
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile')->singleFile(); // Example of a media collection
    }
}
