<?php

<<<<<<< HEAD
namespace App\Models\Ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
=======
namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Spatie\MediaLibrary\InteractsWithMedia;

class AdmissionMerit extends Model implements HasMedia
{
<<<<<<< HEAD
    use SoftDeletes, InteractsWithMedia; // Removed HasMediaTrait
=======
    use SoftDeletes, HasMediaTrait, InteractsWithMedia;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

    protected $appends = [
        'merit_file_url',
    ];

<<<<<<< HEAD
    public function campus()
    {
        return $this->hasOne(Campus::class, 'id', 'campus_id');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }
=======

    public function campus(){
        return $this->hasOne(Campuse::class, 'id','campus_id');
    }
    public function course(){
        return $this->hasOne(Course::class, 'id','course_id');
    }

>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

    public function getMeritFileUrlAttribute()
    {
        if ($this->getMedia('merit_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('merit_file')->last()->getFullUrl();
        }
    }
<<<<<<< HEAD
=======

>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
}
