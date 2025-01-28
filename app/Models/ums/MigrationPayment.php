<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class MigrationPayment extends Model
{
	public function student() {
		return $this->hasOne(Student::class, 'roll_number','roll_no');
	}
}
