<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

use Illuminate\Database\Eloquent\Model;

class AffiliateCircular extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $appends = [
        'circular_file_url',
        
    ];
    public function getCircularFileUrlAttribute()
    {
        if ($this->getMedia('circular_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('circular_file')->last()->getFullUrl();
        }
    }
}
