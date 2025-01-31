<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    public function course() {
        return $this->belongsTo(Course::class, 'course_id')->withTrashed();
    }
    public function faculty() {
        return $this->belongsTo(Faculty::class, 'faculty_id')->withTrashed();
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class,'semester_id')->withTrashed();
    }
}
