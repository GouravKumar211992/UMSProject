<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Auth;
use DB;

class GradeOld extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;

	public $table = 'grades_old';
}
