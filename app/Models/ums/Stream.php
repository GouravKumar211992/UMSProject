<?php

namespace App\Models\ums;
// use Auth;
// use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stream extends Model 
{
	use SoftDeletes;

   
	protected $fillable = [
        'name', 'category_id','course_id','stream_code', 'created_by','updated_by'
    ];
    protected $hidden = [
        'deleted_at',
    ];

	public function category() {
		return $this->belongsTo(Category::class, 'category_id')->withTrashed();
	}
	public function course() {
		return $this->belongsTo(Course::class, 'course_id')->withTrashed();
	}

	public function assessments() {
		return $this->hasMany(Assessment::class);
	}
	
	public function getThumbnailAttribute(){

		if($this->getMedia('thumbnail')->isEmpty()) {
			return '';
		}
		else {
			return $this->getMedia('thumbnail')->first()->getFullUrl();
		}
	}
	
	public function registerMediaCollections()
	{
		$this
			->addMediaCollection('thumbnail')
			->singleFile();
	}

	public function courseMappings() {
		return $this->hasMany(CourseMapping::class, 'course_id');
	}
}
