<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Models\User;
use Models\Role;

class CourseMapping extends Model
{

	protected $hidden = [
		'deleted_at'
	];

	protected $fillable = [
		'morphable_id', 
		'morphable_type', 
		
	];

	public function assessments() {
		return $this->hasMany(Course::class);
	}

	
    public function morphable() {
        return $this->morphTo();
    }
}
