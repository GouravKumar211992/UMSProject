<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class ExamSetting extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    //
    protected $table='exam_setting';  
    protected $fillable=['form_type','from_date','to_date','message','semester_type'];  
    protected $hidden = [
        'deleted_at',
    ];
    protected $appends = ['paper_doc_url'];
	public function campus() {
		return $this->hasOne(Campuse::class, 'id','campus_id');
	}
	public function course() {
		return $this->hasOne(Course::class, 'id','course_id');
	}
	public function semester() {
		return $this->hasOne(Semester::class, 'id','semester_id');
	}
	function getOddEven(){
		if($this->semester_type == 1){
			return 'ALL';
		}else if($this->semester_type == 2){
			return 'EVEN';
		}else if($this->semester_type == 3){
            return 'ODD';
        }
	}

	public function getPaperDocUrlAttribute()
    {

        if ($this->getMedia('paper_doc_url')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('paper_doc_url')->first()->getFullUrl();
        }
    }

    // public function registerMediaCollections()
    // {

    //     $this->addMediaCollection('paper_doc_url')
    //         ->singleFile();
    //  }


}