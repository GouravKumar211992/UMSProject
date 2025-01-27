<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Timetable;
use App\Models\StudentSubject;
use Auth;

class ClassScheduleController extends Controller
{
    public function classSchedule()
    {  
        $periods=Period::all();
		$user=Auth::guard('student')->user();
        
		$student_data=StudentSubject::where('roll_number',$user->roll_number)
		->orderBy('id','DESC')->first();
		//dd($student_data->Course->name);
		$weekDays     = Timetable::WEEK_DAYS;
        $timetables=Timetable::with('period')->where(['course_id'=>$student_data->course_id,'semester_id'=>$student_data->semester_id])->get();
		$recordSet = array();
		foreach($timetables as $timeT) {
			if(!isset($recordSet[$timeT->day])) {
				$recordSet[$timeT->day] = array();
			}
			if(!isset($recordSet[$timeT->day][$timeT->period_id])) {
				$recordSet[$timeT->day][$timeT->period_id] = $timeT;
			}
		}
        return view('student.time-table.class-schedule',[
		'periods'=>$periods,
		'weekDays'=>$weekDays,
		'recordSet'=>$recordSet,
		'student_data'=>$student_data
		]);
    }

}
