<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Course;
use App\Models\Campuse;
use App\Models\Semester;
use Auth;

class MdResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    
	
    public function mdMarksheetList(Request $request)
    {
        $results = Result::whereIn('course_id',[131,132])
        ->where('roll_no', $request->search)
        ->groupby('enrollment_no','semester')
        ->orderBy('id', 'DESC')
        ->get();
        return view('admin.result.md-results',compact('results'));
    }

    public function mdMarksheet(Request $request){
		if(Auth::guard('admin')->check()==false){
			return back()->with('error','Not Allowed');
		}
        $campus_ids = [4];
		$course_id = $request->course_id;
		$semester_id = $request->semester_id;
		$batch = $request->batch;
        $roll_no = $request->roll_no;
        if($request->result_query_string){
            $result_query_string = (object)unserialize(base64_decode($request->result_query_string));
            $course_id = $result_query_string->course_id;
            $semester_id = $result_query_string->semester_id;
            $batch = batchFunctionReturn($result_query_string->roll_no);
            $roll_no = $result_query_string->roll_no;
        }
		$courses = Course::whereIn('campus_id',$campus_ids)
        ->whereNotIn('id',[49])
        ->orderBy('name')
        ->get();
		$semesters = Semester::where('course_id',$course_id)
        ->orderBy('semester_number')
        ->get();
        $students = array();
        $batchPrefix = batchPrefixByBatch($batch);
        if($course_id!='' && $semester_id!='' && $batch!=''){
            $students_query = Result::select('roll_no','course_id','semester')
            ->where('roll_no','LIKE',$batchPrefix.'%')
            ->where('course_id',$course_id)
            ->where('semester',$semester_id)
            ->where('status',2);
            if($roll_no!=''){
                $students_query->where('roll_no',$roll_no);
            }
            $students = $students_query->distinct('roll_no','course_id','semester')
            ->orderBy('roll_no')
            ->get();
            foreach($students as $student){
                $exam_year_array = Result::where('roll_no',$student->roll_no)
                ->where('semester',$student->semester)
                ->where('roll_no','LIKE',$batchPrefix.'%')
                ->first();
                $result_single = Result::where('roll_no',$student->roll_no)
                ->where('semester',$student->semester)
                ->where('roll_no','LIKE',$batchPrefix.'%')
                ->orderBy('subject_code','ASC')
                ->orderBy('back_status','DESC')
                ->orderBy('exam_session','DESC')
                ->distinct()
                ->first();
                $results = $result_single->get_semester_result(1);
                $student->exam_year_array = $exam_year_array;
                $student->result_single = $result_single;
                $student->results = $results;
            }   
		}
		return view('admin.result.md-bulk-marksheet',compact('students','courses','semesters','batch','batchPrefix'));
	}
	

}
