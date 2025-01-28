<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamFormAllow extends Model
{
	use SoftDeletes;
    protected $table = 'exam_form_allow';
}
