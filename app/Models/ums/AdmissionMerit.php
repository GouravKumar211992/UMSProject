<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

class AdmissionMerit extends Model implements HasMedia
{
    use SoftDeletes, HasMediaTrait, InteractsWithMedia;

    protected $appends = [
        'merit_file_url',
    ];


    public function campus(){
        return $this->hasOne(Campuse::class, 'id','campus_id');
    }
    public function course(){
        return $this->hasOne(Course::class, 'id','course_id');
    }


    public function getMeritFileUrlAttribute()
    {
        if ($this->getMedia('merit_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('merit_file')->last()->getFullUrl();
        }
    }

}
