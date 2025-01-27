<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class CourseSwitching extends Model
{
    public $table="course_switching";
    protected $fillable = [
        'roll_no',
        'name',
        'old_course_id',
        'new_course_id',
        'ip_address',
    ];
    public function course_old() {
        return $this->hasOne(Course::class,'id', 'old_course_id')->withTrashed();
    }
    public function course_new() {
        return $this->hasOne(Course::class,'id', 'new_course_id')->withTrashed();
    }

}
