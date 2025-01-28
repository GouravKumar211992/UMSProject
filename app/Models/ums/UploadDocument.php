<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
class UploadDocument extends Model implements HasMedia
{    
  use SoftDeletes, InteractsWithMedia;
    
  protected $table='university_documents';


    protected $appends = [
      
        'upload_document_url',
    ];

public function getUploadDocumentUrlAttribute()
    {

        if ($this->getMedia('upload_document')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('upload_document')->first()->getFullUrl();
        }

    }

}