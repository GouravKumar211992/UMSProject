<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;
use Auth;
use DB;

class Assessment extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;

	protected $hidden = [
		'created_by', 'updated_by', 'deleted_at', 'media'
	];

	protected $fillable = [
		'name', 'assessment_type_id','course_id','description','price', 'discount','parent_id',
		'total_questions', 'total_marks','color_code','negative_marking','negative_rate', 'valid_to','valid_from',
		'is_conditional_locked', 'max_attempts', 'attempt_interval'
	];

	protected $appends = ['thumbnail'];

	public function pages() {
		return $this->hasMany(AssessmentPage::class);
	}

	public function course() {
		return $this->belongsTo(Course::class, 'course_id');
	}

	public function type() {
		return $this->belongsTo(AssessmentType::class,'assessment_type_id');
	}
	
	public function sections() {
		return $this->hasMany(AssessmentSection::class);
	}

	public function questions() {
		return $this->hasManyThrough(AssessmentQuestion::class, AssessmentSection::class);
	}
	
	public function userAssessments() {
		return $this->hasMany(UserAssessment::class);
	}
	
	public function userLastAssessment() {
		return $this->hasOne(UserAssessment::class);
	}

	public function getThumbnailAttribute(){
	
		if($this->getMedia('thumbnail')->isEmpty()) {
			return '';
		}
		else {
			return $this->getMedia('thumbnail')->first()->getFullUrl();
		}
	}
	
	public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
            ->singleFile();
    }
}
