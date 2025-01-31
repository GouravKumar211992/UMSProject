<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;
    
    protected $appends = ['icon'];

    protected $fillable = [
        'name', 'alias', 'parent_id','sequence'
    ];

    protected $hidden = [
        'media','deleted_at'
    ];

    public function getIconAttribute()
    {
        return ($this->getMedia('icon')->first() == NULL) ? asset('images/dashboard.png') : $this->getMedia('icon')->first()->getFullUrl();
    }
    
    public function childs(){
        return $this->hasMany( Menu::class, 'parent_id', 'id' )->orderBy('sequence','ASC');
    }

    public function parent(){
        return $this->hasOne( Menu::class, 'id', 'parent_id' );
    }

    public function children()
    {
        return $this->childs()->with('children');
    }

    public function iconUrl()
    {
        return ($this->getMedia('icon')->first() == NULL) ? asset('images/dashboard.png') : $this->getMedia('icon')->first()->getUrl();
    }

    public function registerMediaCollections()
	{
		$this
			->addMediaCollection('icon')
			->singleFile();
    }

    public function roles()
    {
    	return $this->belongsToMany(Role::class);
    }

    
}
