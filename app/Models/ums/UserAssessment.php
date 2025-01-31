<?php

namespace App\models\ums;
use Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\ConstantHelper;

class UserAssessment extends Model
{
	use SoftDeletes;

	protected $hidden = [
		'deleted_at',
	];

	protected $appends = [
		'attempted', 'correct', 'pending', 'incorrect',
	];

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function assessment() {
		return $this->belongsTo(Assessment::class, 'assessment_id')->with('course');
	}

	public function getAttemptedAttribute(){

		$attempted = UserAssessmentQuestionAttempt::where('user_assessment_id', $this->id)
													->whereNotnull('answer')
		 											->count();
		return $attempted;

	}

	public function getCorrectAttribute(){

		$correct = UserAssessmentQuestionAttempt::where('user_assessment_id', $this->id)
													->whereNotnull('answer')
													->where('status', ConstantHelper::CORRECT)
		 											->count();
		return $correct;

	}

	public function getPendingAttribute(){

		$pending = UserAssessmentQuestionAttempt::where('user_assessment_id', $this->id)
													->whereNotnull('answer')
													->where('status', ConstantHelper::PENDING)
		 											->count();
		return $pending;

	}

	public function getIncorrectAttribute(){

		$incorrect = UserAssessmentQuestionAttempt::where('user_assessment_id', $this->id)
													->whereNotnull('answer')
													->where('status', ConstantHelper::INCORRECT)
		 											->count();
		return $incorrect;

	}
}
