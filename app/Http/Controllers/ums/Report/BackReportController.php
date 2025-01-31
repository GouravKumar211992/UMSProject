<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Result;
use App\Models\Campuse;
use App\Models\ExamFee;
use App\Models\Semester;
use App\Models\BackPaperBulk;
use App\Models\AcademicSession;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Imports\BackPaperImport;
use App\Models\ExamType;
use Maatwebsite\Excel\Facades\Excel;

class BackReportController extends Controller {

    public function index(Request $request)
    {
        $course_id_array = [];
        if($request->course){
            $course_id_array = $request->course;
        }
        $campuses = Campuse::all();
        $courses = Course::where('campus_id',$request->campus_id)->orderBy('name','ASC')->get();
        $academic_session = AcademicSession::select('academic_session')
        ->distinct('academic_session')
        ->orderBy('academic_session', 'DESC')
        ->get();
        $examType = ExamType::all();
        $form_form_data = ExamFee::where('form_type',$request->form_type)
        ->where('academic_session',$request->academic_session)
        ->whereNotNull('bank_name')
        ->whereIn('course_id',$course_id_array)
        ->orderBy('course_id','ASC')
        ->orderBy('roll_no','ASC')
        // ->limit(10)
        ->paginate(100);
        $title_course_name = Course::whereIn('id',$course_id_array)->distinct('name')->pluck('name')->toArray();
        $title_course_name = implode(',',$title_course_name);
        return view('report.back-report',compact('academic_session','examType','form_form_data','campuses','courses','title_course_name'));
    }

    public function regularExamFormReport(Request $request)
    {
        $campuses = Campuse::all();
        $courses = Course::where('campus_id',$request->campus_id)->get();
        // $course_id_array = Course::where('campus_id',$request->campus_id)->orderBy('id','ASC')->pluck('id')->toArray();
        // dd($course_id_array);
        $academic_session = AcademicSession::select('academic_session')
        ->distinct('academic_session')
        ->orderBy('academic_session', 'DESC')
        ->get();
        $form_type = ExamFee::select('form_type')
        // ->where('form_type','regular')
        ->distinct('form_type')
        ->orderBy('form_type', 'DESC')
        ->get();
        $form_form_data = array();
        if($request->course){
            $course_id_array = $request->course;
            $form_form_data = ExamFee::where('form_type',$request->form_type)
            ->where('academic_session',$request->academic_session)
            ->whereIn('course_id',$course_id_array)
            ->orderBy('course_id','ASC')
            ->orderBy('roll_no','ASC')
            // ->limit(10)
            ->get();
        }
        // dd($form_form_data);
        return view('report.regular-exam-form-report',compact('academic_session','form_type','form_form_data','campuses','courses'));
    }

    public function checkEligibilityBackStudent(Request $request){
        $resultData = [];
        $campuses = Campuse::all();
        $courses = Course::where('campus_id',$request->campus_id)->get();
        $academic_sessions = AcademicSession::where('academic_session','2021-2022')->first();
        if($request->form_type=='final_back'){
            $results = Result::select('enrollment_no','roll_no','semester','semester_number','semester_final','course_id','subject_code','grade_letter')
            // ->where('course_id',$request->course)
            ->where('grade_letter','F')
            ->orderBy('roll_no')
            ->distinct()
            ->get();
        }else{
            $results = Result::select('enrollment_no','roll_no','semester','semester_number','semester_final','course_id','subject_code','grade_letter')
            // ->where('course_id',$request->course)
            ->where('exam_session',$request->academic_session)
            ->where('result_type','new')
            ->where('grade_letter','F')
            ->orderBy('roll_no')
            ->distinct()
            ->get();
        }
        foreach($results as $result){
            $data = array(
                'exam_session' => $result->exam_session,
                'roll_number' => $result->roll_no,
                'course' => $result->course_id,
                'semester' => $result->semester,
                'back_papers' => 'back_paper',
            );
            if($request->form_type=='final_back'){
                $checkEligibility = $result->checkEligibilityForFinalBack($data);
            }else{
                $checkEligibility = $result->checkEligibilityForBack($data);
            }
            if($checkEligibility==true){
                $resultData[] = $result;
            }
        }
        return view('report.back-paper-eligibility',compact('campuses','courses','academic_sessions','resultData'));
    }


    public function bulkCouncelling(Request $request){
        $applications = BackPaperBulk::get();
        return view('admin.exam-paper-approval.bulk-back-paper',compact('applications'));
    }

    public function bulkCouncellingSave(Request $request){
    	$request->validate([
            'back_paper_file' => 'required',
        ]);
        if($request->hasFile('back_paper_file')){
			Excel::import(new BackPaperImport, $request->file('back_paper_file'));
		}
        return back()->with('success','Records Saved!');
    }

}

