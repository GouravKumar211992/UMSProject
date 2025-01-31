<?php

namespace App\models\ums;

use App\Models\Course;
use App\Models\Period;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = [
        'timetable_status',
        'period_id',
        'day',
        'course_id',
        'semester_id',
        'subject_id',
        'faculty_id',
        'room_no',
    ];
    protected $hidden = [
        'deleted_at',
    ];

    public function period()
    {
       return $this->belongsTo(Period::class,'period_id','id');
    }
    public function course()
    {
       return $this->belongsTo(Course::class,'course_id','id');
      
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class,'semester_id','id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id','id');
    }
    public function faculty()
    {
        return $this->belongsTo(Faculty::class,'faculty_id','id');
    }
	
	const WEEK_DAYS = [
        '1' => 'Monday',
        '2' => 'Tuesday',
        '3' => 'Wednesday',
        '4' => 'Thursday',
        '5' => 'Friday',
        '6' => 'Saturday',
        '7' => 'Sunday',
    ];
}
