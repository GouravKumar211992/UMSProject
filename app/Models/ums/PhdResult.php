<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;

class PhdResult extends Model {

	protected $table = 'phd_result';
	protected $fillable = [
        'registration_no',
        'gender',
        'category',
        'disability',
        'dob',
        'key',
        'marks',
        'interview_marks',
        'total',
        'overall_rank',
        'status',
    ];
}
