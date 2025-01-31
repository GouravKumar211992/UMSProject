<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

class ScribeDetail extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;

   protected $appends = [
        'scriber_photo',
        'scriber_signature',
        'qualification_certificate',
        'disability_certificate',
    ];

	public function getScriberPhotoAttribute()
    {
        if ($this->getMedia('scriber_photo')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('scriber_photo')->first()->getFullUrl();
        }
    }

	public function getScriberSignatureAttribute()
    {
        if ($this->getMedia('scriber_signature')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('scriber_signature')->first()->getFullUrl();
        }
    }

	public function getQualificationCertificateAttribute()
    {
        if ($this->getMedia('qualification_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('qualification_certificate')->first()->getFullUrl();
        }
    }

	public function getDisabilityCertificateAttribute()
    {
        if ($this->getMedia('disability_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('disability_certificate')->first()->getFullUrl();
        }
    }


    
}
