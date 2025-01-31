<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class StudentSemesterFee extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;
	
    protected $fillable = [
			'program_id' ,
			'course_id' ,
            'enrollment_no' ,
            'semester_id' ,
            'semester_fee',
            'subjects',
            'payment_mode',
            'bank_name',
            'bank_ifsc',
            'receipt_number',
            'receipt_date',
            'status',
			];
			
			
			

			protected $appends = [
        'payment_file',
        
				];
	
	
	public function getPaymentFileAttribute()
    {

        if ($this->getMedia('payment_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('payment_file')->first()->getFullUrl();
        }
    }
			public function category() {
		return $this->belongsTo(Category::class, 'program_id')->withTrashed();
	}
	public function course() {
		return $this->belongsTo(Course::class, 'course_id')->withTrashed();
	}
	public function semester() {
		return $this->belongsTo(Semester::class, 'semester_id')->withTrashed();
	}
	public function enrollment() {
		return $this->belongsTo(Enrollment::class, 'enrollment_no')->withTrashed();
	}
}
