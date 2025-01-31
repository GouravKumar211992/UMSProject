<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;
    protected $table = 'blogs';
    protected $hidden = [
        'deleted_at',
    ];
    protected $appends = [
        'photo',
        'signature',
    ];

  public function getPhotoAttribute()
    {

        if ($this->getMedia('photo')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('photo')->first()->getFullUrl();
        }
    }
    public function getSignatureAttribute()
    {

        if ($this->getMedia('signature')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('signature')->first()->getFullUrl();
        }
    }
}
