<?php 

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Models\CourseMapping;


class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','alias'];

    /**
     * Timestamps flag (false will disable timestamps)
     *
     * @var boolean
     */
    public $timestamps = true;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function can($permission) {
        $permission = explode('.', $permission, 2);
        return !$this->permissions->filter(function($item) use($permission) {
            if($item->name == $permission[0] && $item->type == 'admin') { return true; }
            if (!isset($permission[1])) {
                return false;
            }
            if($item->name == $permission[0] && $item->type == $permission[1]) { return true; }
            return false;
        })->isEmpty();
    }

    public function courseMappings()
    {
        return $this->morphMany(CourseMapping::class, 'morphable');   
    }
}
