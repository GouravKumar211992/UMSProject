<?php
// namespace App\Models\ums;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
// use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\InteractsWithMedia;

// class HolidayCalenderModel extends Model implements HasMedia
// {
//     use SoftDeletes, InteractsWithMedia;

//     protected $table = 'holidaycalender';
//     protected $fillable = ['year', 'file'];

//     protected $appends = [
//         'HolidayCalenderModel_doc',
//     ];

//     public function getHolidayCalenderModelDocAttribute()
//     {
//         if ($this->getMedia('HolidayCalenderModel_doc')->isEmpty()) {
//             return false;
//         } else {
//             return $this->getMedia('HolidayCalenderModel_doc')->first()->getFullUrl();
//         }
//     }

//     public function registerMediaCollections(): void
//     {
//         $this->addMediaCollection('HolidayCalenderModel_doc')->singleFile();
//     }
// }


namespace App\Models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HolidayCalenderModel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $table = 'holidaycalender';
    protected $fillable = ['year','file'];

    protected $appends = [
        'HolidayCalenderModel_doc',
    ];

    public function getHolidayCalenderModelDocAttribute()
    {
        if ($this->getMedia('holiday_calendar')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('holiday_calendar')->first()->getFullUrl();
        }
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('holiday_calendar')->singleFile();
    }
}

