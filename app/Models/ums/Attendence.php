<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    public function course() {
        return $this->belongsTo(Course::class, 'course_id')->withTrashed();
    }
    public function faculty() {
<<<<<<< HEAD
        return $this->belongsTo(Faculty::class, 'faculty_id')->withTrashed();
=======
        return $this->belongsTo(faculty::class, 'faculty_id')->withTrashed();
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class,'semester_id')->withTrashed();
    }
}
