<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class EntranceExam extends Model
{
    public function result() {
		return $this->hasOne(EntranceExamResult::class,'roll_no', 'roll_no');
	}
}
