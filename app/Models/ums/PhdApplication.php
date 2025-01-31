<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

class PhdApplication extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;

	public function Exam_center() {
		return $this->hasOne(ExamCenter::class, 'id','exam_center_id');
	} 
	public function Entrance_exam_schedule() {
		return $this->hasOne(EntranceExamSchedule::class, 'program_code','program_code');
	}
}
