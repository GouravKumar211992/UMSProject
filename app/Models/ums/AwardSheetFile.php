<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class AwardSheetFile extends Model implements HasMedia
{
	use SoftDeletes, InteractsWithMedia;
	
    protected $appends = [
        'award_sheet',
    ];


    public function getAwardSheetAttribute(){
        if ($this->getMedia('award_sheet')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('award_sheet')->first()->getFullUrl();
        }
    }

	
}
