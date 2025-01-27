<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use Spatie\MediaLibrary\InteractsWithMedia;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class AffiliateAdmin  extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable, SoftDeletes, InteractsWithMedia;

     protected $guard = 'affiliate';
   protected $fillable = [
        'name',
        'user_name',
        'password',
        'has_password',
        'email',
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
        'affiliated_id',
        'social_link_id',
        'reseller_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var arraysequence
     */
    // protected $appends = ['profile_image_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at',
    ];

    public function setPasswordAttribute($password)
    {
        if (!is_null($password))
            $this->attributes['password'] = bcrypt($password);
    }
  public function affiliateCollege()
    {
        return $this->hasOne(Campuse::class,'affiliated_id','id');
    }
}
