<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Semester;
use App\Models\ExamType;
use App\Models\AcademicSession;
use App\Models\Subject;
use App\Models\Result;
use App\Models\ExamFee;
use Illuminate\Support\Facades\DB;

class DPharmController extends Controller
{

    public function DpharmTr(Request $request)
    {
        $examTypeData = ExamType::where('exam_type', $request->form_type)->first();
        $examType = $examTypeData ? $examTypeData->result_exam_type : 'REGULAR';
    
        $course_id = $request->course;
        $semester_id = $request->semester;
        $session = $request->session;
    
        // Fetch course, semester, and session data
        $data['courses'] = Course::whereIn('id', [124])->get();
        $data['semesters'] = Semester::where('course_id', $course_id)->orderBy('semester_number', 'asc')->get();
        $data['sessions'] = AcademicSession::all();
        $data['semester_details'] = Semester::find($semester_id);
        $data['course_id'] = $course_id;
        $data['semester_id'] = $semester_id;
    
        // Subject totals
        $subjects = Subject::where('course_id', $course_id)->where('semester_id', $semester_id)->get();
        $data['subject_total'] = $subjects->sum('maximum_mark');
    
        // Theory and Practical groups
        $theorySubjects = $subjects->where('subject_type', 'Theory');
        $practicalSubjects = $subjects->where('subject_type', 'Practical');
    
        $item_group['theory_oral'] = $theorySubjects->sum('oral');
        $item_group['theory_ia'] = $theorySubjects->sum('internal_maximum_mark');
        $item_group['sub_theory_external'] = $theorySubjects->sum('internal_maximum_mark') + $theorySubjects->sum('maximum_mark') + $theorySubjects->sum('oral');
        $item_group['sub_theory_internal'] = $theorySubjects->sum('internal_maximum_mark');
        $item_group['sub_practical_internal'] = $practicalSubjects->sum('internal_maximum_mark') + $practicalSubjects->sum('maximum_mark');
        $item_group['sub_theory_practical_internal'] = $item_group['sub_theory_internal'] + $item_group['sub_practical_internal'];
        $item_group['subjects_total'] = $item_group['sub_theory_external'] + $item_group['sub_practical_internal'];
    
        // Fetch scrutiny data and exam forms based on form type
        $scrutiny_data = Result::where('scrutiny', 1)->pluck('roll_no')->toArray();
        $formType = $request->form_type == 'compartment' ? 'compartment' : 'regular';
        $examForms = ExamFee::where('course_id', $course_id)
            ->where('semester', $semester_id)
            ->where('form_type', $formType)
            ->distinct()
            ->pluck('roll_no')
            ->toArray();
    
        // Prepare student query based on form type
        $students_query = Result::select('roll_no', 'enrollment_no', 'course_id', 'semester')
            ->where(['course_id' => $course_id, 'semester' => $semester_id, 'exam_session' => $session, 'back_status_text' => $examType])
            ->whereIn('roll_no', $examForms);
    
        if ($request->form_type == 'scrutiny') {
            $students = $students_query->whereIn('roll_no', $scrutiny_data)->distinct()->orderBy('roll_no', 'asc')->get();
        } else {
            $students = $students_query->distinct()->orderBy('roll_no', 'asc')->get();
        }
    
        // Process each student's data
        $students->each(function ($item_student) use ($course_id, $semester_id, $session, $examType, &$item_group, $subjects) {
            $grand_total = Result::where('roll_no', $item_student->roll_no)
                ->where('course_id', $course_id)
                ->where('semester', $semester_id)
                ->where('exam_session', $session)
                ->where('back_status_text', $examType)
                ->where('external_marks', '!=', 'ABSENT')
                ->sum('external_marks');
            $item_student['grand_total'] = $grand_total;
    
            $subject_result = Result::join('subjects', 'subjects.sub_code', '=', 'results.subject_code')
                ->where('roll_no', $item_student->roll_no)
                ->where('results.course_id', $course_id)
                ->where('semester', $semester_id)
                ->where('exam_session', $session)
                ->where('back_status_text', $examType)
                ->where('external_marks', '!=', 'ABSENT')
                ->get();
    
            $student_total = $subject_result->sum('external_marks');
            $subjects_total = $subjects->sum('maximum_mark');
    
            // Theory calculations
            $theoryResults = Subject::join('results', 'results.subject_code', '=', 'subjects.sub_code')
                ->where('roll_no', $item_student->roll_no)
                ->where('results.course_id', $course_id)
                ->where('semester_id', $semester_id)
                ->where('back_status_text', $examType)
                ->where('subject_type', 'Theory')
                ->get();
    
            $student_theory_external_sum = $theoryResults->sum('external_marks');
            $student_theory_internal_sum = $theoryResults->sum('internal_marks');
            $student_theory_oral_sum = $theoryResults->sum('oral');
            $item_group['student_theory_oral'] = $student_theory_internal_sum;
            $item_group['student_theory_ia'] = $student_theory_oral_sum;
            $item_group['student_theory_external'] = $student_theory_internal_sum + $student_theory_external_sum + $student_theory_oral_sum;
    
            // Practical calculations
            $practicalResults = Subject::join('results', 'results.subject_code', '=', 'subjects.sub_code')
                ->where('roll_no', $item_student->roll_no)
                ->where('results.course_id', $course_id)
                ->where('semester_id', $semester_id)
                ->where('back_status_text', $examType)
                ->where('subject_type', 'Practical')
                ->get();
    
            $student_practical_internal_sum = $practicalResults->sum('internal_marks') + $practicalResults->sum('oral');
            $student_practical_external_sum = $practicalResults->sum('practical_marks');
            $item_group['student_practical_internal'] = $student_practical_internal_sum;
            $item_group['student_practical_external'] = $student_practical_external_sum;
            $item_group['student_practical_total'] = $student_practical_internal_sum + $student_practical_external_sum;
            $item_group['theory_practical_total'] = $item_group['student_theory_external'] + $item_group['student_practical_total'];
    
            $final_result = $item_student->final_result_for_third($item_student);
            Result::where([
                'roll_no' => $item_student->roll_no,
                'course_id' => $item_student->course_id,
                'semester' => $item_student->semester,
                'exam_session' => $session
            ])->update(['result' => $final_result]);
            $item_student['final_result'] = $final_result;
        });
    
        $data['students'] = $students;
        $data['subject_total'] = $subjects->sum('maximum_mark');
    
        if ($request->pdf_download) {
            $data['download'] = 'pdf';
            $htmlfile = view('admin.tr.bpt-bmlt-tr1', $data)->render();
            $pdf = app()->make('dompdf.wrapper');
            $pdf->loadHTML($htmlfile, 'UTF-8')->setWarnings(false)->setPaper('a1', 'landscape');
            return $pdf->download("Regular TR Report For Course {$data['semester_details']->course->name} ({$data['semester_details']->name}) and Academic Session {$session}.pdf");
        }
    
        return view('admin.tr.dpharm-tr', [
            'courses' => $data['courses'],
            'semesters' => $data['semesters'],
            'sessions' => $data['sessions'],
            'semester_details' => $data['semester_details'],
            'course_id' => $data['course_id'],
            'semester_id' => $data['semester_id'],
            'students' => $data['students'],
            'subject_total' => $data['subject_total'],
            'item_group' => $item_group, // Ensure this variable is available in the view
        ]);
    }
    
    
   
    public function saveTheoryValue(Request $request){
		$columnName = $request->columnName;
		$result = Result::where('roll_no',$request->roll_no)
		->where('subject_code',$request->subject_code)
		->first();
		if($result){
			if($columnName =='external_marks'){
				$result->$columnName = $request->markValue;
				$result->save();
			}elseif($columnName =='oral'){
				$result->$columnName = $request->markValue;
				$result->save();
			}
			return response()->json(['message'=>true]);
		}
		return response()->json(['message'=>false]);
	}
}
