<?php

namespace App\Http\Controllers\ums\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\Result;
use App\Models\ums\Course;
use App\Models\ums\Campuse;
use App\Models\ums\Semester;
use Auth;

class MdResultController extends Controller
{

    public function mdMarksheetList(Request $request)
    {
        $results = Result::whereIn('course_id', [131, 132])
            ->where('roll_no', $request->search)
            ->orderBy('id', 'DESC') // Just ordering by id to get the latest
            ->get();
        
        return view('ums.result.md_marksheet_list', compact('results'));
    }
    

    
    public function batchPrefixByBatch($batch)
    {
        return substr($batch, 2, 2); 
    }

    
    public function mdMarksheet(Request $request){
        $campus_ids = [4];
        $course_id = $request->course_id;
        $semester_id = $request->semester_id;
        $batch = $request->batch;
        $roll_no = $request->roll_no;
        
        // Handle query string if present
        if($request->result_query_string){
            $result_query_string = (object)unserialize(base64_decode($request->result_query_string));
            $course_id = $result_query_string->course_id;
            $semester_id = $result_query_string->semester_id;
            $batch = batchFunctionReturn($result_query_string->roll_no);
            $roll_no = $result_query_string->roll_no;
        }
        
        // Fetch courses and semesters
        $courses = Course::whereIn('campus_id', $campus_ids)
            ->whereNotIn('id', [49])  // Excluding certain course IDs
            ->orderBy('name')
            ->get();
        
        $semesters = Semester::where('course_id', $course_id)
            ->orderBy('semester_number')
            ->get();
        
        $students = [];
        $batchPrefix = $this->batchPrefixByBatch($batch); // Use the newly defined function here
        
        // Ensure course, semester, and batch are provided
        if($course_id && $semester_id && $batch) {
            $students_query = Result::select('roll_no', 'course_id', 'semester')
                ->where('roll_no', 'LIKE', $batchPrefix.'%')
                ->where('course_id', $course_id)
                ->where('semester', $semester_id)
                ->where('status', 2);
            
            if($roll_no) {
                $students_query->where('roll_no', $roll_no);
            }
            
            // Get distinct students based on the query
            $students = $students_query->distinct('roll_no', 'course_id', 'semester')
                ->orderBy('roll_no')
                ->get();
            
            // Process the students if any are found
            foreach($students as $student) {
                // Fetch associated exam year data
                $exam_year_array = Result::where('roll_no', $student->roll_no)
                    ->where('semester', $student->semester)
                    ->where('roll_no', 'LIKE', $batchPrefix.'%')
                    ->first();
                
                // Get single result for the student
                $result_single = Result::where('roll_no', $student->roll_no)
                    ->where('semester', $student->semester)
                    ->where('roll_no', 'LIKE', $batchPrefix.'%')
                    ->orderBy('subject_code', 'ASC')
                    ->orderBy('back_status', 'DESC')
                    ->orderBy('exam_session', 'DESC')
                    ->distinct()
                    ->first();
                
                // Fetch semester results for the student
                $results = $result_single->get_semester_result(1);
                
                // Attach the data to the student object
                $student->exam_year_array = $exam_year_array;
                $student->result_single = $result_single;
                $student->results = $results;
            }
        }
        
        // Return the view with the results
        return view('ums.result.md_marksheet', compact('students', 'courses', 'semesters', 'batch', 'batchPrefix'));
    }

    
}
