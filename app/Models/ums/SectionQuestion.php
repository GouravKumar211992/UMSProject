<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class SectionQuestion extends Model
{
	protected $hidden = [
		'deleted_at'
	];

	protected $table='section_question';

	protected $fillable = [
		'assessment_section_id','question_id','sequence',
	];

	public function assessmentSection() {
		return $this->belongsTo(AssessmentSection::class, 'assessment_section_id');
	}

	public function questions() {
		return $this->hasMany(Question::class, 'question_id');
	}


}
