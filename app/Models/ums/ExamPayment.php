<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamPayment extends Model
{
    use SoftDeletes;
	public function student() {
		return $this->hasOne(Student::class, 'roll_number','roll_no');
	}
}
