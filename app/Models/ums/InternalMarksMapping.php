<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalMarksMapping extends Model
{
    use SoftDeletes;

    protected $appends = ['get_permission'];
	
	public function subjects() {
		return $this->hasMany(Subject::class, 'sub_code','sub_code');
	}

	public function subject() {
		return $this->hasOne(Subject::class, 'sub_code','sub_code')->where('course_id',$this->course_id);
	}
	public function faculty() {
		return $this->belongsTo(Faculty::class, 'faculty_id');
	}
	public function Category() {
		return $this->belongsTo(Category::class, 'program_id');
	}
	public function Course() {
		return $this->belongsTo(Course::class, 'course_id');
	}
	public function Semester() {
		return $this->belongsTo(Semester::class, 'semester_id');
	}
	
	public function campus() {
		return $this->hasOne(Campuse::class, 'id','campuse_id')->withTrashed();
	}

	public function getPermissionAttribute()
    {
    	
        if($this->permissions==0){
            return 'All';
        }elseif($this->permissions==1){
            return 'Internal';
        }elseif($this->permissions==2){
            return 'External';
        }elseif($this->permissions==3){
            return 'Practical';
        }
    }
}
