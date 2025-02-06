<?php

namespace App\models\ums;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

class Phd2023Exam extends Model implements HasMedia
{
 
 
	use SoftDeletes, InteractsWithMedia;

	protected $table = 'phd_2023_exam';
    protected $appends = [
        'photo',
        'signature',
        'challan',
        'doc',
        'subject_name',
        'subject_sequence'
    ];

	public function getPhotoAttribute()
    {

        if ($this->getMedia('photo')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('photo')->last()->getFullUrl();
        }
    }
	public function getSignatureAttribute()
    {

        if ($this->getMedia('signature')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('signature')->last()->getFullUrl();
        }
    }

}


