<?php

namespace App\models\ums;
// use Auth;
// use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class Icard extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;
    

	protected $fillable = [
		'enrolment_number',
		'roll_no',
		'semester',
		'student_name',
		'father_name',
		'father_mobile',
		'mother_name',
		'mailing_address',
		'permanent_address',
		'email',
		'dob',
		'gender',
		'blood_group',
		'student_mobile',
		'program',
		'subject',
		'academic_session',
		'disablity',
		'nationality',
		'fee_receipt_number',
		'fee_receipt_date',
		'local_guardian_name',
		'local_guardian_mobile',
		'type',
		'status',
	];

	protected $appends = [
        'profile_photo_url',
        'signature_url',
        'fee_recipt_url'
    ];

	public function getProfilePhotoUrlAttribute()
    {
        if ($this->getMedia('profile_photo')->isEmpty()) {
			$ExamFee = ExamFee::where('roll_no',$this->roll_no)->first();
			if($ExamFee){
				return $ExamFee->photo;
			}else{
				return false;
			}
        } else {
            return $this->getMedia('profile_photo')->first()->getFullUrl();
        }
    }


	public function getSignatureUrlAttribute()
    {

        if ($this->getMedia('signature')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('signature')->first()->getFullUrl();
        }
    }

	public function getFeeReciptUrlAttribute()
    {

        if ($this->getMedia('fee_recipt')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('fee_recipt')->first()->getFullUrl();
        }
    }
<<<<<<< HEAD
	// public function registerMediaCollections()
    // {
    //     $this->addMediaCollection('profile_photo')
    //         ->singleFile();

	// 	$this->addMediaCollection('signature')
    //         ->singleFile();

	// 	$this->addMediaCollection('fee_recipt')
    //         ->singleFile();
    // }
    // public function enrollment(){
    // 	return $this->hasOne(Enrollment::class, 'enrollment_no','enrolment_number');
    // }
    // public function student(){
    // 	return $this->hasOne(Student::class, 'roll_no','roll_number');
    // }
=======
	public function registerMediaCollections()
    {
        $this->addMediaCollection('profile_photo')
            ->singleFile();

		$this->addMediaCollection('signature')
            ->singleFile();

		$this->addMediaCollection('fee_recipt')
            ->singleFile();
    }
    public function enrollment(){
    	return $this->hasOne(Enrollment::class, 'enrollment_no','enrolment_number');
    }
    public function student(){
    	return $this->hasOne(Student::class, 'roll_no','roll_number');
    }
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d

}
