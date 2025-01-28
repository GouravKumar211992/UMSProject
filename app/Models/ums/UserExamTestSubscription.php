<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Models\User;


class UserExamTestSubscription extends Model
{
    use SoftDeletes;

	protected $hidden = [
		'deleted_at'
	];

	protected $fillable = [
		'user_id','exam_test_id',
	];

	public function user() {
		return $this->belongsTo(User::class);
	}

	public function test() {
		return $this->belongsTo(ExamTest::class, 'exam_test_id');
	}
}
