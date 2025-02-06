<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class BackPaper extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    public $table="special_back_table_details";

    protected $fillable = [
 
         'exam_fee_id',
         'roll_number',
         'course_id',
         'semester_id',
         'academic_session',
         'sub_code',
         'mid',
         'ass',
         'external',
         'viva',
         'p_internal',
         'p_external',
         'paper_type',
         'mid_amount',
         'ass_amount',
         'external_amount',
         'viva_amount',
         'p_internal_amount',
         'p_external_amount',
         'term_and_condition',
           
     ];
 
    protected $appends = [
        'payment_status_text',
        'internal_marks_filled',
        'external_marks_filled',
        'practical_marks_filled',
    ];

	public function course() {
		return $this->hasOne(Course::class, 'id','course_id')->withTrashed();
	}
	public function semester() {
		return $this->hasOne(Semester::class, 'id','semester_id')->withTrashed();
	}

    public function subject() {
        return $this->hasOne(Subject::class, 'sub_code','sub_code')
            ->where('semester_id',$this->semester_id)
            ->where('course_id',$this->course_id)
            ->withTrashed();
    }

   public function payment() {
        return $this->hasOne(MigrationPayment::class, 'migration_id');
    }
    public function getPaymentStatusTextAttribute()
    {
        if(!$this->payment){
            return null;
        }
        return $this->payment->txn_status;
    }
	public function student() {
		return $this->hasOne(Student::class, 'roll_number','roll_number');
	}

    public function getNoduesUrlAttribute()
    {
        if ($this->getMedia('nodues_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('nodues_certificate')->first()->getFullUrl();
        }
    }

	public function subjectDetails() {
		return $this->hasOne(Subject::class, 'sub_code','sub_code')
			->where('semester_id',$this->semester_id)
			->where('course_id',$this->course_id)
			->withTrashed();
	}


    public function registerMediaCollections():void
    {
        $this->addMediaCollection('nodues_certificate')
            ->singleFile();
     }


     public function getInternalMarksFilledAttribute()
     {
        $marks = InternalMark::where('roll_number',$this->roll_number)
        ->where('course_id',$this->course_id)
        ->where('semester_id',$this->semester_id)
        ->where('sub_code',$this->sub_code)
        ->where('type',$this->paper_type)
        ->where('session',$this->academic_session)
        ->first();
        return $marks;
     }
     public function getExternalMarksFilledAttribute()
     {
        $marks = ExternalMark::where('roll_number',$this->roll_number)
        ->where('course_id',$this->course_id)
        ->where('semester_id',$this->semester_id)
        ->where('sub_code',$this->sub_code)
        ->where('type',$this->paper_type)
        ->where('session',$this->academic_session)
        ->first();
        return $marks;
     }
     public function getPracticalMarksFilledAttribute()
     {
        $marks = PracticalMark::where('roll_number',$this->roll_number)
        ->where('course_id',$this->course_id)
        ->where('semester_id',$this->semester_id)
        ->where('sub_code',$this->sub_code)
        ->where('type',$this->paper_type)
        ->where('session',$this->academic_session)
        ->first();
        return $marks;
     }
   

}
