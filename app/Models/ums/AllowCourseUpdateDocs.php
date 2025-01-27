<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Auth;
use DB;

class AllowCourseUpdateDocs extends Model implements HasMedia
{
	use SoftDeletes,InteractsWithMedia;

	protected $fillable = [
        'course_id',
        'status',
    ];

	protected $appends = [
        'course_allowted_for_update_docs',
    ];
    // public function getCourseAllowtedForUpdateDocsAttribute(){
    //     $course_allowted = AllowCourseUpdateDocs::where('course_id',$this->course_id)->first();
    //     return $course_allowted;
    // }
}
