<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ConstantHelper;

class UserAssessmentQuestionAttempt extends Model
{
	// exam test user question attempt

	protected $casts = [
    	"answer" => 'array'
	];

	// public function question() {
	// 	return $this->belongsTo(Question::class);
    // }

    public function question() {
		return $this->belongsTo(Question::class, 'question_id')->with('options');
	}
}
