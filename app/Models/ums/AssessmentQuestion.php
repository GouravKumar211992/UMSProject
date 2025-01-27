<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentQuestion extends Model
{
	use SoftDeletes;

	protected $table = "section_question";
	protected $hidden = [
		'deleted_at'
	];

	protected $fillable = [
		'assessment_section_id', 'question_id','sequence',
	];

	public function section() {
		return $this->belongsTo(AssessmentSection::class, 'assessment_section_id');
	}

	public function question() {
		return $this->belongsTo(Question::class, 'question_id')->with('options');
	}

}
