<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class Message extends Model implements HasMedia
{
	use InteractsWithMedia;

    protected $fillable = [
        'messagable_type',
        'messagable_id',
        'conversation',
        'sender_type',
        'sender_id',
        'content',
    ];

    protected $appends = ['attachment'];

    public function messageable()
    {
        return $this->morphTo();
    }

    public function getAttachmentAttribute(){

		if($this->getMedia('attachment')->isEmpty()) {
			return false;
		}
		else {
			return $this->getMedia('attachment')->first()->getFullUrl();
		}
    }

    public function registerMediaCollections()
	{
		$this->addMediaCollection('attachment')
			->singleFile();
	}

    public function scopeDaa($query)
    {
        $query->where('conversation', '=', 'DAA');
    }

    public function scopeUad($query)
    {
        $query->where('conversation', '=', 'UAD');
    }
}