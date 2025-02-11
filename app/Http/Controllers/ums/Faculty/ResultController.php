<?php

namespace App\Http\Controllers\ums\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\ExamType;
use App\Models\ExamFee;
use App\Models\ums\AdmitCard;
use App\Models\ums\Subject;
use App\Models\Student;
use App\Models\Icard;
use App\Models\ums\Campuse;
use App\Models\ums\Semester;
use App\Models\Stream;
use App\Models\ums\Course;
use App\Models\ums\Result;
use App\Models\ums\Category;
use App\Models\ums\ExternalMark;
use App\Models\ums\InternalMark;
use App\Models\ums\AcademicSession;
use App\Models\ums\ResultBackupScrutiny;
use App\Models\ums\PracticalMark;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ResultsTrait;
use App\Models\ums\Grade;
class ResultController extends Controller
{
    public function index()
    {
		return view('admin/exam/result-entry');
    	
    }
    public function entry(Request $request){
      $admit_card = AdmitCard::whereId($request->id)->first();
      $data['subjects'] = Subject::whereIn('sub_code',explode(' ',$admit_card->examfee->subject))->get();
      $data['exam_data'] = $admit_card->examfee;
      $data['enrollment'] = $admit_card->examfee->enrollment;
      $data['application'] = $admit_card->examfee->enrollment->application;
      $data['admit_card'] = $admit_card;
    	return view('admin/result/result-form',$data);
    }

    public function entrySave(Request $request){
      $this->validate($request, [
       // 'enrollment_no' => 'required',
       // 'roll_no' => 'required',
       // 'admit_card_id' => 'required',
       // 'semester' => 'required',
        'subject_code.*' => 'required',
        'internal_marks.*' => 'required',
        'external_marks.*' => 'required',
        //'practical_marks.*' => 'required',
      ]); 

      $admit_card = AdmitCard::whereId($request->id)->first();
      //dd($admit_card->examfee->semester);
      $result_array = [];
      foreach($request->subject_code as $index=>$subject_code){
        $result_array[$index]['enrollment_no'] = $admit_card->enrollment_no;
        $result_array[$index]['roll_no'] = $admit_card->roll_no;
//        $result_array[$index]['exam_session'] = '2021-2022';
        $result_array[$index]['admit_card_id'] = $request->id;
        $result_array[$index]['semester'] = $admit_card->examfee->semester;
        $result_array[$index]['subject_code'] = $subject_code;
        $result_array[$index]['internal_marks'] = $request->internal_marks[$index];
        $result_array[$index]['external_marks'] = $request->external_marks[$index];
        $result_array[$index]['created_at'] = date('Y-m-d H:i:s');
      }

      Result::insert($result_array);
      return redirect('admin/master/admit-card-list')->with('success','Marks Saved Successfully');
    }

    public function schedule()
    {
    	return view('admin/exam/exam-schedule');
    }
    public function timetable()
    {
     return view('admin/exam/examtime-table');
    }
    public function allResults(Request $request)
{
    $results = Result::query();  // Start building the query

    if(count($request->all()) > 0){
        
        // Apply filters based on request
        if($request->search) {
            $keyword = $request->search;
            $results->where(function($q) use ($keyword){
                $q->where('roll_no', 'LIKE', '%'.$keyword.'%');
            });
        }

        if(!empty($request->name)){
            $results->where('roll_no', 'LIKE', '%'.$request->name.'%');
        }

        if(!empty($request->course_id)){
            $results->where('course_id', $request->course_id);
        }

        if(!empty($request->campus)) {
            $enrollment = [];
            $campus = Campuse::find($request->campus);
            if ($campus) {
                foreach ($results->get() as $result) {
                    if ($campus->campus_code == campus_name($result->enrollment_no)) {
                        $enrollment[] = $result->enrollment_no;
                    }
                }
                $results->whereIn('enrollment_no', $enrollment);
            }
        }

        if(!empty($request->semester)) {
            $semester_ids = Semester::where('name', $request->semester)->pluck('id')->toArray();
            $results->whereIn('semester', $semester_ids);
        }
    } else {
        // If no filters are applied, set a default filter for null course_id
        $results->where('course_id', null);
    }

    // Apply aggregation and grouping using selectRaw
    $results = $results
        ->selectRaw('MAX(results.id) as id, results.roll_no, results.exam_session, results.back_status_text, results.enrollment_no, results.semester, results.semester_number')
        ->groupBy('results.roll_no', 'results.exam_session', 'results.back_status_text', 'results.enrollment_no', 'results.semester', 'results.semester_number')
        ->orderBy('results.semester_number', 'ASC')
        ->orderBy('results.back_status_text', 'ASC')
        ->orderBy('results.exam_session', 'DESC')
        ->paginate(10);

    // Get additional data for the view
    $category = Category::all();
    $course = Course::all();
    $campus = Campuse::all();
    $semester = Semester::select('name')->distinct()->get();

    // Prepare the data array for the view
    $data['results'] = $results;
    $data['categories'] = $category;
    $data['courses'] = $course;
    $data['campuselist'] = $campus;
    $data['semesterlist'] = $semester;

    // Return the view with the data
    return view('ums.result.Result_list', $data);
}
}
