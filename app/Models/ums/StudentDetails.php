<?php

namespace App\models\ums;
// use Auth;
// use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDetails extends Model 
{
    protected $table = 'student_details';
	use SoftDeletes;

}
