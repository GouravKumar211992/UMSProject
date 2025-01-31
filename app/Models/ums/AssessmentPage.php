<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Models\Language;

class AssessmentPage extends Model
{
	use SoftDeletes;

	protected $hidden = [
		'created_by', 'updated_by', 'deleted_at'
	];

	protected $fillable = [
		'name', 'assessment_id', 'content','language_id','sequence','created_by', 'updated_by'
	];

	protected $casts = [
    	"content" => 'array'
    ];

	public function assessment() {
		return $this->belongsTo(Assessment::class,'assessment_id');
	}

	protected function setSequenceAttribute($value = 0)
	{
		if(!$value) {
			$lastSequence = AssessmentPage::where([
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
