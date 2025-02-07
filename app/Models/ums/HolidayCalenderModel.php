<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class HolidayCalenderModel extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;

    protected $table='holidaycalender';
    protected $fillable=['year','file',];

    protected $appends = [
        'HolidayCalenderModel_doc',
    ];

    public function getHolidayCalenderModelDocAttribute()
    {

        if ($this->getMedia('HolidayCalenderModel_doc')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('HolidayCalenderModel_doc')->first()->getFullUrl();
        }
    }

<<<<<<< HEAD
    public function registerMediaCollections():void
    {

        $this->addMediaCollection('HolidayCalenderModel_doc')
            ->singleFile();
    }
=======
    // public function registerMediaCollections()
    // {

    //     $this->addMediaCollection('HolidayCalenderModel_doc')
    //         ->singleFile();
    // }
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

}
