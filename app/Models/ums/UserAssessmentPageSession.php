<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserAssessmentPageSession extends Model
{
	public function readTime($testUserId){
		$firstRecord = UserAssessmentPageSession::where('user_assessment_id', $testUserId)
												->first();

		$lastRecord = UserAssessmentPageSession::where('user_assessment_id', $testUserId)
										->orderBy('id', 'DESC')
		 								->first();
		$difference = strtotime($lastRecord->created_at)-strtotime($firstRecord->created_at);
		return $difference;

	}
}
