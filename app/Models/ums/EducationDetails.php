<?php

namespace App\models\ums;
// use Auth;
// use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationDetails extends Model 
{
    protected $table = 'education_details';
	use SoftDeletes;

}
