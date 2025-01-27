<?php

namespace App\models\ums;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'is_email_verified',
        'mobile',
        'is_mobile_verified',
        'is_primary_user',
        'date_of_birth',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['profile_image_url'];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getProfileImageUrlAttribute() {
        return  asset('images/default-user-icon.jpg');
    }

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function courseMappings()
    {
        return $this->morphMany(CourseMapping::class, 'morphable');
    }

	public function PaymentDetails(){
        return $this->hasOne(PaymentDetails::class,'user_id');
    }

	public function PersonalInformations(){
        return $this->hasOne(PersonalInformations::class,'user_id');
    }

	public function ApplicationDetails(){
        return $this->hasOne(ApplicationDetails::class,'user_id');
    }

	public function EducationDetails(){
        return $this->hasOne(EducationDetails::class,'user_id');
    }

	public function StudentDetails(){
        return $this->hasOne(StudentDetails::class,'user_id');
    }

	public function PermanentAddress(){
        return $this->hasOne(PermanentAddress::class,'user_id');
    }

	public function UploadDocuments(){
        return $this->hasOne(UploadDocuments::class,'user_id');
    }



}
