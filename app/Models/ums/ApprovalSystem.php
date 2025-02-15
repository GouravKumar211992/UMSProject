<?php

namespace App\Models\ums;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class ApprovalSystem extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;
    public $table="exam_form_approval_system";

    protected $fillable = [
 
         'roll_number',
         'course_id',
         'semester_id',
         'session',
         'exam_form',
         'back_paper',
         'final_back',
         'special_back',
           
     ];

     public function course() {
		return $this->hasOne(Course::class, 'id','course_id')->withTrashed();
	}
	public function semester() {
		return $this->hasOne(Semester::class, 'id','semester_id')->withTrashed();
	}


}
