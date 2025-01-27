<?php

namespace App\models\ums;

use Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AssessmentSection extends Model
{
	use SoftDeletes;

	protected $hidden = [
		'deleted_at'
	];

	protected $fillable = [
		'name','sequence', 'assessment_id', 'language_id', 'marks_per_question'
	];

	public function assessment() {
		return $this->belongsTo(Assessment::class, 'assessment_id');
	}

	public function questions()
	{
		return $this->belongsToMany(Question::class,
				'section_question',
				'assessment_section_id',
				'question_id'
			)->with(['options','questionBank']);
	}

	public function sectionQuestions()
	{
		return $this->hasMany(SectionQuestion::class,'assessment_section_id');
	}

	protected function setSequenceAttribute($value = 0)
	{
		if(!$value) {
			$lastSequence = AssessmentSection::where([
				'assessment_id' => $this->attributes['assessment_id']
			])
			->orderBy('sequence', 'DESC')
			->first();
			$value = $lastSequence ? ($lastSequence->sequence + 1) : 1;
		}
		$this->attributes['sequence'] = $value;
	}

	public function language() {
		return $this->belongsTo(Language::class, 'language_id');
	}
}
