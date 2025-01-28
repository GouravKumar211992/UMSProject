<?php

namespace App\models\ums;
// use Auth;
// use DB;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

class ApplicationEducation extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;

    protected $table = 'application_educations';

    protected $fillable = [
        "name_of_exam",
        "board",
        "passing_year",
        "passing_status",
        "cgpa_or_marks",
        "total_marks_cgpa",
        "cgpa_optain_marks",
        "equivalent_percentage",
        "subject",
        "certificate_number",
        "application_id",
        "degree_name",
        "user_id"
	];

    protected $appends = [
        'doc_url',
        'cgpa_document',
        'passing_status_text',
    ];

    public function getPassingStatusTextAttribute()
    {
        if($this->passing_status==1){
            return 'Passed';
        }else{
            return 'Appeared';
        }
    }

    public function getDocUrlAttribute()
    {
        if ($this->getMedia('doc')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('doc')->last()->getFullUrl();
        }
    }
    public function getCgpaDocumentAttribute()
    {
        if ($this->getMedia('cgpa_document')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('cgpa_document')->last()->getFullUrl();
        }
    }


	public function application() {
		return $this->belongsTo(Application::class, 'application_id');
	}

  
      public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_image_url')
            ->singleFile();
    }
}


