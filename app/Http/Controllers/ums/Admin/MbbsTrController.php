<?php

namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;
use App\Helpers\CustomHelper;
require_once app_path('Helpers/CustomHelper.php');
use App\Models\ums\Course;
use App\Models\ums\Semester;
use App\Models\ums\Subject;
use App\Models\ums\Result;
use App\Models\ums\StudentSubject;
use App\Models\ums\Category;
use App\Models\ums\AcademicSession;
use App\Models\ums\ExamFee;
use App\Models\ums\Campuse;
use App\Models\ums\StudentAllFromOldAgency;
use App\Models\ums\ExamType;
use App\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use DB;
use App\Http\Traits\ResultsTrait;

class MbbsTrController extends  AdminController
{
	use ResultsTrait;
    public function __construct()
    {
        parent::__construct();
    }
	
	function batchPrefixByBatch($batch) {
		return substr($batch,2,2);
	}
	
	// This function returns an array of batch values.
public function batchArray()
{
    // Example static array, replace this with dynamic data if necessary
    return ['2019-2020', '2020-2021', '2021-2022', '2021-2022']; 

    // Alternatively, if batches are fetched from a database:
    // return Batch::pluck('name')->toArray();
}

	// s
	
	//  public function index(Request $request)
	//  {
	// 	 $data['courses'] = Course::where('id',49)->get();
	// 	 $data['semesters'] = Semester::where('course_id',49)->orderBy('id','asc')->get();
	// 	 $data['sessions'] = AcademicSession::get();
	// 	 $data['batchArray'] = $this->batchArray();
	// 	 $session = $request->session;
	// 	 $course_id = $request->course;
	// 	 $semester_id = $request->semester;
	// 	 $batch = $request->batch;
	// 	 $batchPrefix = $this->batchPrefixByBatch($request->batch); // This is an array
	// 	 $data['course_id'] = $course_id;
	// 	 $data['semester_id'] = $semester_id;
	 
	// 	 $subjects_group_all = Subject::select('combined_subject_name', DB::raw('count(1) as combined_count'))
	// 		 ->where(['course_id' => $course_id, 'semester_id' => $semester_id])
	// 		 ->whereNotNull('combined_subject_name')
	// 		 ->where('batch', $batch)
	// 		 ->groupBy('combined_subject_name')
	// 		 ->orderBy('sub_code', 'asc')
	// 		 ->get();
	 
	// 	 $subject_total = Subject::where('course_id', $course_id)
	// 		 ->whereNotNull('combined_subject_name')
	// 		 ->where('semester_id', $semester_id)
	// 		 ->where('batch', $batch)
	// 		 ->get();
	// 	 $data['subject_total'] = $subject_total->sum('maximum_mark');
	 
	// 	 $subjects_group_all->each(function ($item_group, $key_group) use ($course_id, $semester_id, $batch, $batchPrefix) {
	// 		 $subjects = Subject::where('course_id', $course_id)
	// 			 ->where('semester_id', $semester_id)
	// 			 ->whereNotNull('combined_subject_name')
	// 			 ->where('batch', $batch)
	// 			 ->where('combined_subject_name', $item_group->combined_subject_name)
	// 			 ->get();
	 
	// 		 $sub_theory_external = Subject::where('course_id', $course_id)
	// 			 ->where('semester_id', $semester_id)
	// 			 ->where('batch', $batch)
	// 			 ->where('combined_subject_name', $item_group->combined_subject_name)
	// 			 ->where('subject_type', 'Theory')
	// 			 ->get();
	// 		 $item_group['sub_theory_external'] = $sub_theory_external->sum('maximum_mark');
	 
	// 		 $sub_theory_internal = Subject::where('course_id', $course_id)
	// 			 ->where('semester_id', $semester_id)
	// 			 ->where('batch', $batch)
	// 			 ->where('combined_subject_name', $item_group->combined_subject_name)
	// 			 ->where('subject_type', 'Theory')
	// 			 ->get();
	// 		 $item_group['sub_theory_internal'] = $sub_theory_internal->sum('internal_maximum_mark');
	 
	// 		 $sub_practical_internal = Subject::where('course_id', $course_id)
	// 			 ->where('semester_id', $semester_id)
	// 			 ->where('batch', $batch)
	// 			 ->where('combined_subject_name', $item_group->combined_subject_name)
	// 			 ->where('subject_type', 'Practical')
	// 			 ->get();
	// 		 $item_group['sub_practical_internal'] = $sub_practical_internal->sum('internal_maximum_mark');
	 
	// 		 $item_group['sub_theory_practical_internal'] = ($item_group['sub_theory_internal'] + $item_group['sub_practical_internal']);
	// 		 $item_group['subjects_total'] = ($subjects->sum('maximum_mark'));
	// 		 $item_group['subjects'] = $subjects;
	// 	 });
	 
	// 	 $data['subjects_group_all'] = $subjects_group_all;
	 
	// 	 $scrutiny_data = Result::where('scrutiny', 1)->pluck('roll_no')->toArray();
	 
	// 	 if ($request->form_type == 'compartment') {
	// 		 $mbbs_exam_forms = ExamFee::withTrashed()
	// 			 ->where('course_id', $request->course)
	// 			 ->where('semester', $request->semester)
	// 			 ->where('form_type', 'compartment')
	// 			 ->where('academic_session', $session)
	// 			 ->where(function ($query) use ($batchPrefix) {
	// 				 foreach ($batchPrefix as $batch) {
	// 					 $query->orWhere('roll_no', 'LIKE', $batch . '%');
	// 				 }
	// 			 })
	// 			 ->distinct()
	// 			 ->pluck('roll_no')
	// 			 ->toArray();
	// 		 $students_query = Result::select('roll_no', 'enrollment_no', 'course_id', 'semester')
	// 			 ->where(['course_id' => $request->course, 'semester' => $request->semester])
	// 			 ->where('exam_session', $session)
	// 			 ->where(function ($query) use ($batchPrefix) {
	// 				 foreach ($batchPrefix as $batch) {
	// 					 $query->orWhere('roll_no', 'LIKE', $batch . '%');
	// 				 }
	// 			 })
	// 			 ->whereIn('roll_no', $mbbs_exam_forms);
	// 	 } else {
	// 		 $mbbs_exam_forms = ExamFee::withTrashed()
	// 			 ->where('course_id', $request->course)
	// 			 ->where('academic_session', $session)
	// 			 ->where('semester', $request->semester)
	// 			 ->where('form_type', 'regular')
	// 			 ->where(function ($query) use ($batchPrefix) {
	// 				 foreach ($batchPrefix as $batch) {
	// 					 $query->orWhere('roll_no', 'LIKE', $batch . '%');
	// 				 }
	// 			 })
	// 			 ->distinct()
	// 			 ->pluck('roll_no')
	// 			 ->toArray();
	// 		 $students_query = Result::select('roll_no', 'course_id', 'semester')
	// 			 ->where(['course_id' => $request->course, 'semester' => $request->semester])
	// 			 ->where('exam_session', $session)
	// 			 ->where(function ($query) use ($batchPrefix) {
	// 				 foreach ($batchPrefix as $batch) {
	// 					 $query->orWhere('roll_no', 'LIKE', $batch . '%');
	// 				 }
	// 			 })
	// 			 ->whereIn('roll_no', $mbbs_exam_forms);
	// 	 }
	 
	// 	 if ($request->form_type == 'scrutiny') {
	// 		 $students = $students_query->whereIn('roll_no', $scrutiny_data)->distinct()->get();
	// 	 } else {
	// 		 $students = $students_query->distinct()->get();
	// 	 }
	 
	// 	 $students->each(function ($item_student, $key_student) use ($course_id, $semester_id, $session, $request, $batch, $batchPrefix) {
	// 		 $grand_total = Result::where('roll_no', $item_student->roll_no)
	// 			 ->where('course_id', $course_id)
	// 			 ->where('semester', $semester_id)
	// 			 ->where('exam_session', $session)
	// 			 ->where(function ($query) use ($batchPrefix) {
	// 				 foreach ($batchPrefix as $batch) {
	// 					 $query->orWhere('roll_no', 'LIKE', $batch . '%');
	// 				 }
	// 			 })
	// 			 ->get();
	// 		 $item_student['grand_total'] = $grand_total->sum('external_marks');
	 
	// 		 $subjects_group_all = Subject::select('combined_subject_name', DB::raw('count(1) as combined_count'))
	// 			 ->whereNotNull('combined_subject_name')
	// 			 ->where('batch', $batch)
	// 			 ->where(['course_id' => $course_id, 'semester_id' => $semester_id])
	// 			 ->groupBy('combined_subject_name')
	// 			 ->orderBy('sub_code', 'asc')
	// 			 ->get();
	 
	// 		 $item_student['subjects_group_all'] = $subjects_group_all->each(function ($item_group, $key_group) use ($course_id, $semester_id, $item_student, $session, $request, $batch, $batchPrefix) {
	// 			 $subjects = Subject::where('course_id', $course_id)
	// 				 ->whereNotNull('combined_subject_name')
	// 				 ->where('semester_id', $semester_id)
	// 				 ->where('batch', $batch)
	// 				 ->where('combined_subject_name', $item_group->combined_subject_name)
	// 				 ->distinct()
	// 				 ->orderBy('sub_code', 'asc')
	// 				 ->get();
	 
	// 			 $subject_result = Result::join('subjects', 'subjects.sub_code', 'results.subject_code')
	// 				 ->where('combined_subject_name', $item_group->combined_subject_name)
	// 				 ->where('roll_no', $item_student->roll_no)
	// 				 ->where('results.course_id', $course_id)
	// 				 ->where('semester', $semester_id)
	// 				 ->where('exam_session', $session)
	// 				 ->where('batch', $batch)
	// 				 ->get();
	 
	// 			 $student_total = ($subject_result->sum('external_marks'));
	// 			 $subjects_total = ($subjects->sum('maximum_mark'));
	// 			 $item_group['student_total'] = $student_total;
	// 			 $item_group['subjects_total'] = $subjects_total;
	// 			 $item_group['subjects'] = $subjects;
	// 		 });
	 
	// 		 $result_final_text = $item_student->final_result($item_student);
	// 		 $item_student['final_result'] = $result_final_text;
	// 		 $this->updateTrResult($item_student, $result_final_text);
	// 	 });
	 
		
	// 	 $data['students'] = $students;
	 
	// 	 return view('ums.mbbsparanursing.mbbs_tr', $data);
	//  }
	 


	public function index(Request $request)
	{
		$data['courses'] = Course::where('id',49)->get();
		$data['semesters'] = Semester::where('course_id',49)->orderBy('id','asc')->get();
		$data['sessions'] = AcademicSession::get();
		$data['batchArray'] = $this->batchArray();
		$session = $request->session;
		$course_id = $request->course;
		$semester_id = $request->semester;
		$batch = $request->batch;
		$batchPrefix = $this->batchPrefixByBatch($request->batch); // This is an array
		$data['course_id'] = $course_id;
		$data['semester_id'] = $semester_id;
	
		$subjects_group_all = Subject::select('combined_subject_name', DB::raw('count(1) as combined_count'))
			->where(['course_id' => $course_id, 'semester_id' => $semester_id])
			->whereNotNull('combined_subject_name')
			->where('batch', $batch)
			->groupBy('combined_subject_name',  'sub_code')
			->orderBy('sub_code', 'asc')
			->get();
	
		$subject_total = Subject::where('course_id', $course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id', $semester_id)
			->where('batch', $batch)
			->get();
		$data['subject_total'] = $subject_total->sum('maximum_mark');
	
		$subjects_group_all->each(function ($item_group, $key_group) use ($course_id, $semester_id, $batch, $batchPrefix) {
			$subjects = Subject::where('course_id', $course_id)
				->where('semester_id', $semester_id)
				->whereNotNull('combined_subject_name')
				->where('batch', $batch)
				->where('combined_subject_name', $item_group->combined_subject_name)
				->get();
	
			$sub_theory_external = Subject::where('course_id', $course_id)
				->where('semester_id', $semester_id)
				->where('batch', $batch)
				->where('combined_subject_name', $item_group->combined_subject_name)
				->where('subject_type', 'Theory')
				->get();
			$item_group['sub_theory_external'] = $sub_theory_external->sum('maximum_mark');
	
			$sub_theory_internal = Subject::where('course_id', $course_id)
				->where('semester_id', $semester_id)
				->where('batch', $batch)
				->where('combined_subject_name', $item_group->combined_subject_name)
				->where('subject_type', 'Theory')
				->get();
			$item_group['sub_theory_internal'] = $sub_theory_internal->sum('internal_maximum_mark');
	
			$sub_practical_internal = Subject::where('course_id', $course_id)
				->where('semester_id', $semester_id)
				->where('batch', $batch)
				->where('combined_subject_name', $item_group->combined_subject_name)
				->where('subject_type', 'Practical')
				->get();
			$item_group['sub_practical_internal'] = $sub_practical_internal->sum('internal_maximum_mark');
	
			$item_group['sub_theory_practical_internal'] = ($item_group['sub_theory_internal'] + $item_group['sub_practical_internal']);
			$item_group['subjects_total'] = ($subjects->sum('maximum_mark'));
			$item_group['subjects'] = $subjects;
		});
	
		$data['subjects_group_all'] = $subjects_group_all;
	
		$scrutiny_data = Result::where('scrutiny', 1)->pluck('roll_no')->toArray();
	
		if ($request->form_type == 'compartment') {
			$mbbs_exam_forms = ExamFee::withTrashed()
				->where('course_id', $request->course)
				->where('semester', $request->semester)
				->where('form_type', 'compartment')
				->where('academic_session', $session)
				->where(function ($query) use ($batchPrefix) {
					foreach ($batchPrefix as $batch) {
						$query->orWhere('roll_no', 'LIKE', $batch . '%');
					}
				})
				->distinct()
				->pluck('roll_no')
				->toArray();
			$students_query = Result::select('roll_no', 'enrollment_no', 'course_id', 'semester')
				->where(['course_id' => $request->course, 'semester' => $request->semester])
				->where('exam_session', $session)
				->where(function ($query) use ($batchPrefix) {
					foreach ($batchPrefix as $batch) {
						$query->orWhere('roll_no', 'LIKE', $batch . '%');
					}
				})
				->whereIn('roll_no', $mbbs_exam_forms);
		} else {
			$mbbs_exam_forms = ExamFee::withTrashed()
				->where('course_id', $request->course)
				->where('academic_session', $session)
				->where('semester', $request->semester)
				->where('form_type', 'regular')
				->where(function ($query) use ($batchPrefix) {
				   if (!is_array($batchPrefix)) {
					   $batchPrefix = [$batchPrefix]; // Ensure it's an array
				   }
				   foreach ($batchPrefix as $batch) {
					   $query->orWhere('roll_no', 'LIKE', $batch . '%');
				   }
			   })
				//  ->where(function ($query) use ($batchPrefix) {
			   // 	 foreach ($batchPrefix as $batch) {
			   // 		 $query->orWhere('roll_no', 'LIKE', $batch . '%');
			   // 	 }
			   //  })
				->distinct()
				->pluck('roll_no')
				->toArray();
			$students_query = Result::select('roll_no', 'course_id', 'semester')
				->where(['course_id' => $request->course, 'semester' => $request->semester])
				->where('exam_session', $session)
			   //  ->where(function ($query) use ($batchPrefix) {
			   // 	 foreach ($batchPrefix as $batch) {
			   // 		 $query->orWhere('roll_no', 'LIKE', $batch . '%');
			   // 	 }
			   //  })

			   ->where(function ($query) use ($batchPrefix) {
				   if (!is_array($batchPrefix)) {
					   $batchPrefix = [$batchPrefix]; // Ensure it's an array
				   }
				   foreach ($batchPrefix as $batch) {
					   $query->orWhere('roll_no', 'LIKE', $batch . '%');
				   }
			   })

				->whereIn('roll_no', $mbbs_exam_forms);
		}
	
		if ($request->form_type == 'scrutiny') {
			$students = $students_query->whereIn('roll_no', $scrutiny_data)->distinct()->get();
		} else {
			$students = $students_query->distinct()->get();
		}
	
		$students->each(function ($item_student, $key_student) use ($course_id, $semester_id, $session, $request, $batch, $batchPrefix) {
			$grand_total = Result::where('roll_no', $item_student->roll_no)
				->where('course_id', $course_id)
				->where('semester', $semester_id)
				->where('exam_session', $session)
			   //  ->where(function ($query) use ($batchPrefix) {
			   // 	 foreach ($batchPrefix as $batch) {
			   // 		 $query->orWhere('roll_no', 'LIKE', $batch . '%');
			   // 	 }
			   //  })


			   ->where(function ($query) use ($batchPrefix) {
				   if (!is_array($batchPrefix)) {
					   $batchPrefix = [$batchPrefix]; // Ensure it's an array
				   }
				   foreach ($batchPrefix as $batch) {
					   $query->orWhere('roll_no', 'LIKE', $batch . '%');
				   }
			   })


				->get();
			$item_student['grand_total'] = $grand_total->sum('external_marks');
	
			$subjects_group_all = Subject::select('combined_subject_name', DB::raw('count(1) as combined_count'))
				->whereNotNull('combined_subject_name')
				->where('batch', $batch)
				->where(['course_id' => $course_id, 'semester_id' => $semester_id])
				->groupBy('combined_subject_name' , 'sub_code')
				->orderBy('sub_code', 'asc')
				->get();
	
			$item_student['subjects_group_all'] = $subjects_group_all->each(function ($item_group, $key_group) use ($course_id, $semester_id, $item_student, $session, $request, $batch, $batchPrefix) {
				$subjects = Subject::where('course_id', $course_id)
					->whereNotNull('combined_subject_name')
					->where('semester_id', $semester_id)
					->where('batch', $batch)
					->where('combined_subject_name', $item_group->combined_subject_name)
					->distinct()
					->orderBy('sub_code', 'asc')
					->get();
	
				$subject_result = Result::join('subjects', 'subjects.sub_code', 'results.subject_code')
					->where('combined_subject_name', $item_group->combined_subject_name)
					->where('roll_no', $item_student->roll_no)
					->where('results.course_id', $course_id)
					->where('semester', $semester_id)
					->where('exam_session', $session)
					->where('batch', $batch)
					->get();
	
				$student_total = ($subject_result->sum('external_marks'));
				$subjects_total = ($subjects->sum('maximum_mark'));
				$item_group['student_total'] = $student_total;
				$item_group['subjects_total'] = $subjects_total;
				$item_group['subjects'] = $subjects;
			});
	
			$result_final_text = $item_student->final_result($item_student);
			$item_student['final_result'] = $result_final_text;
			$this->updateTrResult($item_student, $result_final_text);
		});
	
	   
		$data['students'] = $students;
	
		return view('ums.mbbsparanursing.mbbs_tr', $data);
	}
	
	

	 public function index_2019_2020And2020_2021(Request $request)
	 {
		 $data['courses'] = Course::where('id',49)->get();
		 $data['semesters'] = Semester::where('course_id',49)->orderBy('id','asc')->get();
		 $data['selectet_semester_data'] = Semester::find($request->semester);
		 $data['sessions'] = AcademicSession::get();
		 $session = $request->session;
		 $course_id = $request->course;
		 $semester_id = $request->semester;
		 $batch = $request->batch;
		 $batchPrefix = $this->batchPrefixByBatch($request->batch);
		 $data['course_id'] = $course_id;
		 $data['semester_id'] = $semester_id;
 
		 $subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
		 ->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
		 ->whereNotNull('combined_subject_name')
		 ->where('batch',$batch)
		 ->groupBy('combined_subject_name', 'sub_code')
		 ->orderBy('sub_code','asc')
		 ->get();
 
		 $subject_total = Subject::where('course_id',$course_id)
		 ->whereNotNull('combined_subject_name')
		 ->where('semester_id',$semester_id)
		 ->where('batch',$batch)
		 ->get();
		 $data['subject_total'] = $subject_total->sum('maximum_mark');
 
		 $subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id,$batch,$batchPrefix){
			 $subjects = Subject::where('course_id',$course_id)->where('semester_id',$semester_id)
			 ->whereNotNull('combined_subject_name')
			 ->where('batch',$batch)
			 ->where('combined_subject_name',$item_group->combined_subject_name)
			 ->get();
 
			 $sub_theory_external = Subject::where('course_id',$course_id)
			 ->whereNotNull('combined_subject_name')
			 ->where('semester_id',$semester_id)
			 ->where('batch',$batch)
			 ->where('combined_subject_name',$item_group->combined_subject_name)
			 ->where('subject_type','Theory')
			 ->get();
			 $item_group['sub_theory_external'] = $sub_theory_external->sum('maximum_mark');
 
			 $sub_theory_internal = Subject::where('course_id',$course_id)
			 ->whereNotNull('combined_subject_name')
			 ->where('semester_id',$semester_id)
			 ->where('batch',$batch)
			 ->where('combined_subject_name',$item_group->combined_subject_name)
			 ->where('subject_type','Theory')
			 ->get();
			 $item_group['sub_theory_internal'] = $sub_theory_internal->sum('internal_maximum_mark');
 
			 $sub_practical_internal = Subject::where('course_id',$course_id)
			 ->whereNotNull('combined_subject_name')
			 ->where('semester_id',$semester_id)
			 ->where('batch',$batch)
			 ->where('combined_subject_name',$item_group->combined_subject_name)
			 ->where('subject_type','Practical')
			 ->get();
			 $item_group['sub_practical_internal'] = $sub_practical_internal->sum('internal_maximum_mark');
 
			 $item_group['sub_theory_practical_internal'] = ($item_group['sub_theory_internal'] + $item_group['sub_practical_internal']);
			 $item_group['subjects_total'] = ($subjects->sum('maximum_mark'));
			 $item_group['subjects'] = $subjects;
		 });
 
		 $data['subjects_group_all'] = $subjects_group_all;
 
		 $scrutiny_data = Result::where('scrutiny',1)->pluck('roll_no')->toArray();
		 if($request->form_type=='compartment'){
			 $mbbs_exam_forms = ExamFee::where('course_id',$request->course)
			 ->where('semester',$request->semester)
			 ->where('form_type','compartment')
			 ->where('academic_session', $session)
			 ->where('roll_no','LIKE',$batchPrefix.'%')
			 ->distinct()
			 ->pluck('roll_no')
			 ->toArray();
			 $students_query = Result::select('roll_no','enrollment_no','course_id','semester')
			 ->where(['course_id'=>$request->course,'semester'=>$request->semester])
			 ->where('exam_session', $session)
			 ->where('roll_no','LIKE',$batchPrefix.'%')
			 ->whereIn('roll_no',$mbbs_exam_forms);
		 }else{
			 $mbbs_exam_forms = ExamFee::where('course_id',$request->course)
			 ->where('academic_session', $session)
			 ->where('semester',$request->semester)
			 ->where('form_type','regular')
			 ->where('roll_no','LIKE',$batchPrefix.'%')
			 ->distinct()
			 ->pluck('roll_no')
			 ->toArray();
			 // dd($request->semester);
			 $students_query = Result::select('roll_no','course_id','semester')
			 ->where(['course_id'=>$request->course,'semester'=>$request->semester])
			 ->where('exam_session', $session)
			 ->where('roll_no','LIKE',$batchPrefix.'%')
			 // ->where('roll_no','1901247058')
			 ->whereIn('roll_no',$mbbs_exam_forms);
		 }
		 // $students = $students_query->where('roll_no','1901247054');
		 if($request->form_type=='scrutiny'){
			 $students = $students_query->whereIn('roll_no',$scrutiny_data)->distinct()->get();
		 }else{
			 $students = $students_query->distinct()->get();
		 }
		 // dd($students);
		 $students->each(function ($item_student, $key_student) use ($course_id,$semester_id,$session,$request,$batch,$batchPrefix){
			 $grand_total = Result::where('roll_no',$item_student->roll_no)
			 ->where('course_id',$course_id)
			 ->where('semester',$semester_id)
			 ->where('exam_session', $session)
			 ->where('roll_no','LIKE',$batchPrefix.'%')
			 ->get();
			 $item_student['grand_total'] = $grand_total->sum('external_marks');
			 $subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
			 ->whereNotNull('combined_subject_name')
			 ->where('batch',$batch)
			 ->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
			 ->groupBy('combined_subject_name','sub_code')
			 ->orderBy('sub_code','asc')
			 ->get();
			 $item_student['subjects_group_all'] = $subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id,$item_student,$session,$request,$batch,$batchPrefix){
				 $subjects = Subject::where('course_id',$course_id)
				 ->whereNotNull('combined_subject_name')
				 ->where('semester_id',$semester_id)
				 ->where('batch',$batch)
				 ->where('combined_subject_name',$item_group->combined_subject_name)
				 ->distinct()
				 ->orderBy('sub_code','asc')
				 ->get();
				 $subject_result = Result::join('subjects','subjects.sub_code','results.subject_code')
				 ->where('combined_subject_name',$item_group->combined_subject_name)
				 ->where('roll_no',$item_student->roll_no)->where('results.course_id',$course_id)
				 ->where('semester',$semester_id)
				 ->where('exam_session', $session)
				 ->where('batch',$batch)
				 ->whereNull('results.deleted_at')
				 ->get();
 
				 $student_total = ($subject_result->sum('external_marks'));
 
				 $subjects_total = ($subjects->sum('maximum_mark'));
				 $student_theory_external = Subject::join('results','results.subject_code','subjects.sub_code')
					 ->whereNotNull('combined_subject_name')
					 ->where('roll_no',$item_student->roll_no)
					 ->where('roll_no','LIKE',$batchPrefix.'%')
					 ->where('results.course_id',$course_id)
					 ->where('semester_id',$semester_id)
					 ->where('batch',$batch)
					 ->whereNull('results.deleted_at')
					 ->where('combined_subject_name',$item_group->combined_subject_name)
					 ->where('subject_type','Theory')
					 ->get();
 
				 $student_theory_external_amount = 0;
				 // dd($student_theory_external);
				 foreach($student_theory_external as $student_theory_external_row){
					 $student_theory_external_amount = $student_theory_external_amount + (int)$student_theory_external_row->external_marks;
				 }
				 $item_group['student_theory_external'] = $student_theory_external_amount;
 
				 $student_theory_internal = Subject::join('results','results.subject_code','subjects.sub_code')
				 ->whereNotNull('combined_subject_name')
				 ->where('roll_no',$item_student->roll_no)
				 ->where('roll_no','LIKE',$batchPrefix.'%')
				 ->where('results.course_id',$course_id)
				 ->where('semester_id',$semester_id)
				 ->where('batch',$batch)
				 ->whereNull('results.deleted_at')
				 ->where('combined_subject_name',$item_group->combined_subject_name)
				 ->where('subject_type','Theory')
				 ->get();
				 $student_theory_internal_sum = 0;
				 foreach($student_theory_internal as $student_theory_internalRow){
					 $student_theory_internal_sum = $student_theory_internal_sum + (int)$student_theory_internalRow->internal_marks;
				 }
				 $item_group['student_theory_internal'] = $student_theory_internal_sum;
								 
				 $student_practical_internal = Subject::join('results','results.subject_code','subjects.sub_code')
				 ->whereNotNull('combined_subject_name')
				 ->where('roll_no',$item_student->roll_no)
				 ->where('roll_no','LIKE',$batchPrefix.'%')
				 ->where('results.course_id',$course_id)
				 ->where('semester_id',$semester_id)
				 ->where('batch',$batch)
				 ->whereNull('results.deleted_at')
				 ->where('combined_subject_name',$item_group->combined_subject_name)
				 ->where('subject_type','Practical')
				 ->get();
				 $student_practical_internal_sum = 0;
				 foreach($student_practical_internal as $student_practical_internalRow){
					 $student_practical_internal_sum = $student_practical_internal_sum + (int)$student_practical_internalRow->internal_marks;
				 }
				 $item_group['student_practical_internal'] = $student_practical_internal_sum;
 
				 $item_group['student_theory_practical_internal'] = ($item_group['student_theory_internal'] + $item_group['student_practical_internal']);
 
				 $item_group['student_total'] = $student_total;
				 $item_group['subjects_total'] = $subjects_total;
				 $item_group['subjects'] = $subjects->each(function ($item_sub, $key_sub) use ($item_student,$session,$request,$batch,$batchPrefix){
					 $subject_result = Result::where('roll_no',$item_student->roll_no)
					 ->where('results.course_id',$item_sub->course_id)
					 ->where('semester',$item_sub->semester_id)
					 ->where('subject_code',$item_sub->sub_code)
					 ->where('exam_session', $session)
					 ->where('roll_no','LIKE',$batchPrefix.'%')
					 ->whereNull('results.deleted_at')
					 ->first();
					 $item_sub['subject_result'] = $subject_result;
				 });
 
			 });
		 });
		 $data['students'] = $students;
 
		 return view('ums.mbbsparanursing.MBBS_RT_2019_2020_2020_2021', $data);
	 }

    public function mbbs_tr_third(Request $request)
    {
        $course_id = $request->course;
        $data['courses'] = Course::where('id',49)->get();
    	$data['semesters']=Semester::where('course_id',$course_id)->whereIn('semester_number',[3,4])->orderBy('id','asc')->get();
    	$data['sessions'] = AcademicSession::get();
    	$session = $request->session;
    	$batch = $request->batch;
		$batchPrefix = batchPrefixByBatch($batch);
    	$semester_id = $request->semester;
        $data['course_id'] = $course_id;
    	$data['semester_id'] = $semester_id;

		$subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
		->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
		->whereNotNull('combined_subject_name')
		->where('batch',$batch)
		->groupBy('combined_subject_name' )
		->orderBy('sub_code','asc')
		->get();

		$subject_total = Subject::where('course_id',$course_id)
		->whereNotNull('combined_subject_name')
		->where('semester_id',$semester_id)
		->where('batch',$batch)
		->get();
		$data['subject_total'] = $subject_total->sum('maximum_mark');

		$subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id,$batch,$batchPrefix){
			$subjects = Subject::where('course_id',$course_id)->where('semester_id',$semester_id)
			->whereNotNull('combined_subject_name')
			->where('batch',$batch)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->get();

			$sub_theory_external = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('batch',$batch)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Theory')
			->get();
			$item_group['theory_oral'] = $sub_theory_external->sum('oral');
			$item_group['theory_ia'] = $sub_theory_external->sum('internal_maximum_mark');

			$item_group['sub_theory_external'] = ($sub_theory_external->sum('internal_maximum_mark')+$sub_theory_external->sum('maximum_mark')+$sub_theory_external->sum('oral'));

			$sub_theory_internal = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('batch',$batch)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Theory')
			->get();
			$item_group['sub_theory_internal'] = $sub_theory_internal->sum('internal_maximum_mark');
							
			$sub_practical_internal = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('batch',$batch)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Practical')
			->get();
			$item_group['sub_practical_internal'] = ($sub_practical_internal->sum('internal_maximum_mark') + $sub_practical_internal->sum('maximum_mark'));

			$item_group['sub_theory_practical_internal'] = ($item_group['sub_theory_internal'] + $item_group['sub_practical_internal']);
			$item_group['subjects_total'] = ($item_group['sub_theory_external'] + $item_group['sub_practical_internal']);
			$item_group['subjects'] = $subjects;
		});
			
		$data['subjects_group_all'] = $subjects_group_all;

		$scrutiny_data = Result::where('scrutiny',1)
		->where('roll_no','LIKE',$batchPrefix.'%')
		->pluck('roll_no')->toArray();
		if($request->form_type=='compartment'){
			$mbbs_exam_forms = ExamFee::where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('academic_session', $session)
			->where('roll_no','LIKE',$batchPrefix.'%')
			->where('form_type','compartment')
			->distinct()
			->pluck('roll_no')
			->toArray();
			$students_query = Result::select('roll_no','enrollment_no','course_id','semester')
			->where('semester',$request->semester)
			// ->where('exam_session', $session)
			->where('roll_no','LIKE',$batchPrefix.'%')
			->whereIn('roll_no',$mbbs_exam_forms);
			// dd($mbbs_exam_forms,$batchPrefix,$request->semester,$students_query->get());
		}else{
			$mbbs_exam_forms = ExamFee::where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('academic_session', $session)
			->where('roll_no','LIKE',$batchPrefix.'%')
			->where('form_type','regular')
			->distinct()
			->pluck('roll_no')
			->toArray();
			$students_query = Result::select('roll_no','course_id','semester')
			->where(['course_id'=>$request->course,'semester'=>$request->semester])
			// ->where('exam_session', $session)
			->where('roll_no','LIKE',$batchPrefix.'%')
			->whereIn('roll_no',$mbbs_exam_forms);
		}
		if($request->form_type=='scrutiny'){
			$students = $students_query->whereIn('roll_no',$scrutiny_data)->distinct()->get();
		}else{
			$students = $students_query->distinct()->get();
		}
		// dd($students);
		$students->each(function ($item_student, $key_student) use ($course_id,$semester_id,$session,$batch,$batchPrefix){
			
			$grand_total = Result::where('roll_no',$item_student->roll_no)
			->where('course_id',$course_id)
			->where('semester',$semester_id)
			->where('roll_no','LIKE',$batchPrefix.'%')
			->get();
			$item_student['grand_total'] = $grand_total->sum('external_marks');
			$subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
			->whereNotNull('combined_subject_name')
			->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
			->where('batch',$batch)
			->groupBy('combined_subject_name' )
			->orderBy('sub_code','asc')
			->get();
			$item_student['subjects_group_all'] = $subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id,$item_student,$session,$batch,$batchPrefix){
				$subjects = Subject::where('course_id',$course_id)
				->whereNotNull('combined_subject_name')
				->where('semester_id',$semester_id)
				->where('batch',$batch)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->distinct()
				->orderBy('sub_code','asc')
				->get();
				$subject_result = Result::join('subjects','subjects.sub_code','results.subject_code')
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('roll_no',$item_student->roll_no)->where('results.course_id',$course_id)
				->where('semester',$semester_id)
				// ->where('exam_session', $session)
				->whereNull('results.deleted_at')
				->where('roll_no','LIKE',$batchPrefix.'%')
				->get();

				$student_total = ($subject_result->sum('external_marks'));

				$subjects_total = ($subjects->sum('maximum_mark'));

				$student_theory_external = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('batch',$batch)
				->whereNull('results.deleted_at')
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Theory')
				->get();
				$student_theory_external_sum = 0;
				$student_theory_internal_sum = 0;
				$student_theory_oral_sum = 0;
				foreach($student_theory_external as $student_theory_externalRow){
					$student_theory_external_sum = $student_theory_external_sum + (int)$student_theory_externalRow->external_marks;
					$student_theory_internal_sum = $student_theory_internal_sum + (int)$student_theory_externalRow->internal_marks;
					$student_theory_oral_sum = $student_theory_oral_sum + (int)$student_theory_externalRow->oral;
				}

				$item_group['student_theory_external'] = ($student_theory_internal_sum + $student_theory_external_sum + $student_theory_oral_sum);


				$student_theory_internal = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('batch',$batch)
				->whereNull('results.deleted_at')
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Theory')
				->select('subjects.*','subjects.oral as subject_oral','results.*','results.oral as oral')
				->get();

				$student_theory_internal_sum = 0;
				$student_theory_oral_sum = 0;
				foreach($student_theory_internal as $student_theory_internalRow){
					$student_theory_internal_sum = $student_theory_internal_sum + (int)$student_theory_internalRow->internal_marks;
					$student_theory_oral_sum = $student_theory_oral_sum + (int)$student_theory_internalRow->oral;
				}
				$item_group['student_theory_oral'] = $student_theory_oral_sum;
				$item_group['student_theory_ia'] = $student_theory_internal_sum;
				$item_group['student_theory_internal'] = $student_theory_internal_sum;
								
				$student_practical_internal = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('batch',$batch)
				->whereNull('results.deleted_at')
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Practical')
				->get();
				$student_practical_internal_sum = 0;
				foreach($student_practical_internal as $student_practical_internalRow){
					$student_practical_internal_sum = $student_practical_internal_sum + (int)$student_practical_internalRow->internal_marks;
				}
				$item_group['student_practical_internal'] = $student_practical_internal_sum;
				$item_group['student_theory_practical_internal'] = ($item_group['student_theory_internal'] + $item_group['student_practical_internal']);

				$item_group['student_total'] = $student_total;
				$item_group['subjects_total'] = $subjects_total;
				$item_group['subjects'] = $subjects->each(function ($item_sub, $key_sub) use ($item_student,$session,$batch,$batchPrefix){
					$subject_result = Result::where('roll_no',$item_student->roll_no)
					->where('results.course_id',$item_sub->course_id)
					->where('semester',$item_sub->semester_id)
					->where('subject_code',$item_sub->sub_code)
					// ->where('exam_session', $session)
					->whereNull('results.deleted_at')
					->where('roll_no','LIKE',$batchPrefix.'%')
					->first();
					$item_sub['subject_result'] = $subject_result;
				});
			});
		});
		$data['students'] = $students;
		$data['subject_total'] = $subjects_group_all->sum('subjects_total');

		return view('admin.tr.mbbs-tr-third', $data);
    }
    public function bptBmltTr1(Request $request)
    {
        $examTypeData = ExamType::where('exam_type',$request->form_type)->first();
		$examType = 'REGULAR';
		if($examTypeData){
			$examType = $examTypeData->result_exam_type;
		}
        $course_id = $request->course;
        $data['courses'] = Course::whereIn('id',[64,96,95,124])->get();
    	$data['semesters'] = Semester::where('course_id',$course_id)->orderBy('semester_number','asc')->get();
    	$data['sessions'] = AcademicSession::get();
    	$session = $request->session;
    	$semester_id = $request->semester;
    	$semester_details = Semester::where('id',$semester_id)->first();
    	$data['semester_details'] = $semester_details;
        $data['course_id'] = $course_id;
    	$data['semester_id'] = $semester_id;

		$subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
		->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
		->whereNotNull('combined_subject_name')
		->groupBy('combined_subject_name' )
		->orderBy('sub_code','asc')
		->get();
		$subject_total = Subject::where('course_id',$course_id)
		->whereNotNull('combined_subject_name')
		->where('semester_id',$semester_id)
		->get();
		$data['subject_total'] = $subject_total->sum('maximum_mark');

		$subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id){
			$subjects = Subject::where('course_id',$course_id)->where('semester_id',$semester_id)
			->whereNotNull('combined_subject_name')
			->where('combined_subject_name',$item_group->combined_subject_name)
			->get();

			$sub_theory_external = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Theory')
			->get();
			$item_group['theory_oral'] = $sub_theory_external->sum('oral');
			$item_group['theory_ia'] = $sub_theory_external->sum('internal_maximum_mark');

			$item_group['sub_theory_external'] = ($sub_theory_external->sum('internal_maximum_mark')+$sub_theory_external->sum('maximum_mark')+$sub_theory_external->sum('oral'));

			$sub_theory_internal = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Theory')
			->get();
			$item_group['sub_theory_internal'] = $sub_theory_internal->sum('internal_maximum_mark');
							
			$sub_practical_internal = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Practical')
			->get();
			$item_group['sub_practical_internal'] = ($sub_practical_internal->sum('internal_maximum_mark') + $sub_practical_internal->sum('maximum_mark'));

			$item_group['sub_theory_practical_internal'] = ($item_group['sub_theory_internal'] + $item_group['sub_practical_internal']);
			$item_group['subjects_total'] = ($item_group['sub_theory_external'] + $item_group['sub_practical_internal']);
			$item_group['subjects'] = $subjects;
		});
			
		$data['subjects_group_all'] = $subjects_group_all;

		$scrutiny_data = Result::where('scrutiny',1)->pluck('roll_no')->toArray();
		if($request->form_type=='compartment'){
			$mbbs_exam_forms = ExamFee::where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('form_type','compartment')
			->distinct()
			->pluck('roll_no')
			->toArray();
			$students_query = Result::select('roll_no','enrollment_no','course_id','semester')
			->where(['course_id'=>$request->course,'semester'=>$request->semester])
			->where('exam_session', $session)
			->where('back_status_text', $examType)
			->whereIn('roll_no',$mbbs_exam_forms);
		}else{
			$mbbs_exam_forms = ExamFee::where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('form_type','regular')
			->distinct()
			->pluck('roll_no')
			->toArray();
			$students_query = Result::select('roll_no','course_id','semester')
			->where('course_id', $request->course)
			->where('semester', $request->semester)
			->where('exam_session', $session)
			->where('back_status_text', $examType)
			->whereIn('roll_no',$mbbs_exam_forms);
		}
		if($request->form_type=='scrutiny'){
			$students = $students_query->whereIn('roll_no',$scrutiny_data)
			->distinct()
			->orderBy('roll_no','asc')
			->get();
		}else{
			$students = $students_query->distinct()
			->orderBy('roll_no','asc')
			->get();
		}
		$students->each(function ($item_student, $key_student) use ($course_id,$semester_id,$session,$examType){

			$grand_total = Result::where('roll_no',$item_student->roll_no)
			->where('course_id',$course_id)
			->where('semester',$semester_id)
			->where('exam_session', $session)
			->where('back_status_text', $examType)
			->where('external_marks','!=', 'ABSENT')
			->get();
			$item_student['grand_total'] = $grand_total->sum('external_marks');
			$subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
			->whereNotNull('combined_subject_name')
			->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
			->groupBy('combined_subject_name' )
			->orderBy('sub_code','asc')
			->get();
			$item_student['subjects_group_all'] = $subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id,$item_student,$session,$examType){
				$subjects = Subject::where('course_id',$course_id)
				->whereNotNull('combined_subject_name')
				->where('semester_id',$semester_id)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->distinct()
				->orderBy('sub_code','asc')
				->get();
				$subject_result = Result::join('subjects','subjects.sub_code','results.subject_code')
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('roll_no',$item_student->roll_no)->where('results.course_id',$course_id)
				->where('semester',$semester_id)
				->where('exam_session', $session)
				->where('back_status_text', $examType)
				->where('external_marks','!=', 'ABSENT')
				->get();

				$student_total = ($subject_result->sum('external_marks'));

				$subjects_total = ($subjects->sum('maximum_mark'));

				$student_theory_external = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('back_status_text', $examType)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Theory')
				->get();
				$student_theory_external_sum = 0;
				$student_theory_internal_sum = 0;
				$student_theory_oral_sum = 0;
				foreach($student_theory_external as $student_theory_externalRow){
					$student_theory_external_sum = $student_theory_external_sum + (int)$student_theory_externalRow->external_marks;
					$student_theory_internal_sum = $student_theory_internal_sum + (int)$student_theory_externalRow->internal_marks;
					$student_theory_oral_sum = $student_theory_oral_sum + (int)$student_theory_externalRow->oral;
				}
				$item_group['student_theory_oral'] = $student_theory_internal_sum;
				$item_group['student_theory_ia'] = $student_theory_oral_sum;

				$item_group['student_theory_external'] = ($student_theory_internal_sum + $student_theory_external_sum + $student_theory_oral_sum);


				$student_theory_internal = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('back_status_text', $examType)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Theory')
				->select('subjects.*','subjects.oral as subject_oral','results.*','results.oral as oral')
				->get();
				$theory_subject_total_required = ($student_theory_internal->sum('internal_maximum_mark') + $student_theory_internal->sum('maximum_mark') + $student_theory_internal->sum('subject_oral'));
				$item_group['theory_grace_mark'] = $item_student->mbbs_grade_for_third($theory_subject_total_required,$item_group['student_theory_external']);
				$item_group['grace_mark'] = $item_group['theory_grace_mark'];

				$student_theory_internal_sum = 0;
				foreach($student_theory_internal as $student_theory_internalRow){
					$student_theory_internal_sum = $student_theory_internal_sum + (int)$student_theory_internalRow->internal_marks;
				}
				$item_group['student_theory_internal'] = $student_theory_internal_sum;

				$student_practical_internal = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('back_status_text', $examType)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Practical')
				->get();
				$student_practical_internal_sum = 0;
				$student_practical_external_sum = 0;
				$student_practical_practical_sum = 0;
				foreach($student_practical_internal as $student_practical_internalRow){
					$student_practical_internal_sum = $student_practical_internal_sum + (int)$student_practical_internalRow->internal_marks + (int)$student_practical_internalRow->oral;
					$student_practical_external_sum = $student_practical_external_sum + (int)$student_practical_internalRow->external_marks;
					$student_practical_practical_sum = $student_practical_practical_sum + (int)$student_practical_internalRow->practical_marks;
				}
				$item_group['student_practical_internal'] = $student_practical_internal_sum;
				$item_group['student_practical_external'] = ($student_practical_practical_sum);

				$item_group['student_practical_total'] = (int)($item_group['student_practical_internal'] + (int)$item_group['student_practical_external']);
				
				$item_group['theory_practical_total'] = ($item_group['student_theory_external'] + $item_group['student_practical_total']);
				$practical_subject_total_required = ($student_practical_internal->sum('internal_maximum_mark') + $student_practical_internal->sum('maximum_mark'));
				$item_group['practical_grace_mark'] = $item_student->mbbs_grade_for_third($practical_subject_total_required,$item_group['student_practical_total']);

				$item_group['grace_mark'] = [$item_group['theory_grace_mark'],$item_group['practical_grace_mark']];
				$item_group['student_theory_practical_internal'] = (int)($item_group['student_theory_internal'] + (int)$item_group['student_practical_internal']);

				$item_group['student_total'] = $student_total;
				$item_group['subjects_total'] = $subjects_total;
				$item_group['subjects'] = $subjects->each(function ($item_sub, $key_sub) use ($item_student,$session,$examType){
					$subject_result = Result::where('roll_no',$item_student->roll_no)
					->where('results.course_id',$item_sub->course_id)
					->where('semester',$item_sub->semester_id)
					->where('subject_code',$item_sub->sub_code)
					->where('exam_session', $session)
					->where('back_status_text', $examType)
					->first();
					if($item_sub->subject_type == 'Theory'){
						$item_sub['theory_total_all'] = ($subject_result)?((int)$subject_result->external_marks + (int)$subject_result->oral + (int)$subject_result->internal_marks):0;
					}
					if($item_sub->subject_type == 'Practical'){
						$item_sub['practical_total_all'] = ($subject_result)?((int)$subject_result->practical_marks + (int)$subject_result->internal_marks):0;
					}
					$item_sub['subject_result'] = $subject_result;
				});

			});
			$final_result = $item_student->final_result_for_third($item_student);
			Result::where(['roll_no'=>$item_student->roll_no,'course_id'=>$item_student->course_id,'semester'=>$item_student->semester,'exam_session'=>$session])->update(['result'=>$final_result]);
			$item_student['final_result'] = $final_result;
		});
		$data['students'] = $students;
		$data['subject_total'] = $subjects_group_all->sum('subjects_total');

        if($request->pdf_download!=null){
			$data['download'] = 'pdf';
            $htmlfile = view('admin.tr.bpt-bmlt-tr1', $data)->render();
			$pdf = app()->make('dompdf.wrapper');
			$pdf->loadHTML($htmlfile,'UTF-8')
			->setWarnings(false)
			->setPaper('a1', 'landscape');
            return $pdf->download('Regular TR Report For Course '.$semester_details->course->name.' ( '.$semester_details->name.' ) and Academic Session '.$request->session.'.pdf');
        }

		return view('admin.tr.bpt-bmlt-tr1', $data);
    }
    public function dpharma_tr(Request $request)
    {
		$examtype = $request->form_type;
        $course_id = 124;
        $data['courses'] = Course::whereIn('id',[124])->get();
    	$data['semesters'] = Semester::where('course_id',$course_id)->orderBy('semester_number','asc')->get();
    	$data['sessions'] = AcademicSession::get();
    	$session = $request->session;
    	$semester_id = $request->semester;
    	$semester_details = Semester::where('id',$semester_id)->first();
    	$data['semester_details'] = $semester_details;
        $data['course_id'] = $course_id;
    	$data['semester_id'] = $semester_id;
		if($request->generate=='true'){
			$this->generate_dpharma_tr($course_id,$semester_id,$session,$examtype);
		}

		$data['results'] = Result::select('roll_no','course_id','semester','exam_session','back_status_text')
		->where('course_id',$course_id)
		->where('semester',$semester_id)
		->where('exam_session',$session)
		->where('back_status_text',$examtype)
		->distinct('roll_no','course_id','semester','exam_session','back_status_text')
		->orderBy('roll_no','asc')
		->get();
		$data['subjects'] = Subject::where('course_id',$course_id)
		->where('semester_id',$semester_id)
		->orderBy('position','asc')
		->get();

        if($request->pdf_download!=null){
			$data['download'] = 'pdf';
            $htmlfile = view('ums.mbbsparanursing.dpharma_tr', $data)->render();
			$pdf = app()->make('dompdf.wrapper');
			$pdf->loadHTML($htmlfile,'UTF-8')
			->setWarnings(false)
			->setPaper('a1', 'landscape');
            return $pdf->download('Regular TR Report For Course '.$semester_details->course->name.' ( '.$semester_details->name.' ) and Academic Session '.$request->session.'.pdf');
        }

		return view('ums.mbbsparanursing.dpharma_tr', $data);
    }

	function generate_dpharma_tr($course_id,$semester_id,$session,$examtype){
		$results = \App\Models\Result::select('*')
		->where('course_id',$course_id)
		->where('semester',$semester_id)
		->where('exam_session',$session)
		->where('back_status_text',$examtype)
		->where('status',1)
		->orderBy('subject_position','asc')
		->get();
		foreach($results as $result_single){
            $result_array = \App\Models\Result::select('*')
            ->where('roll_no',$result_single->roll_no)
            ->where('course_id',$result_single->course_id)
            ->where('semester',$result_single->semester)
            ->where('exam_session',$result_single->exam_session)
            ->where('back_status_text',$result_single->back_status_text)
			->where('status',1)
            ->orderBy('subject_position','asc');
			$result_array = clone $result_array;
			$result_array_update = clone $result_array;
			$failed_count = 0;
			$result_status = 'P';
			foreach($result_array->get() as $result){
				$total_mark = (int)$result->internal_marks + (int)$result->external_marks;
				$required_mark = (int)$result->max_internal_marks + (int)$result->max_external_marks;
				$grade_percent = ($total_mark/$required_mark)*100;
				if($grade_percent>=40){
					$result->grade_letter = 'P';
				}else{
					$result->grade_letter = 'F';
					$failed_count = $failed_count + 1;
					if($failed_count>4){
						$result_status = 'F';
					}else{
						$result_status = 'PCP';
					}
				}
				$result->save();
				$result_array_update->update(['result'=>$result_status]);
			}
		}
	}
	
    public function add(Request $request)
    {
        
        return view('admin.master.category.addcategory', [
            'page_title' => "Add New",
            'sub_title' => "Category"
        ]);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required'
        ]);
        $data = $request->all();
        $category = $this->create($data);
        return redirect()->route('get-categories')->with('success','Added Successfully.');
    }

    public function create(array $data)
    {
      return Category::create([
        'name' => $data['category_name'],
        'created_by' => 1,
        //'created_by' => Auth::guard('admin')->user()->id,
        'status' => $data['category_status'] == 'active'?1:0
      ]);
    }

    public function editCategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required'
        ]);
        $status = $request['category_status'] == 'active'?1:0;
        $update_category = Category::where('id', $request->category_id)->update(['name' => $request->category_name, 'status' => $status, 'updated_by' => 1]);
        return redirect()->route('get-categories')->with('success','Update Successfully.');
        
    }


    public function editcategories(Request $request, $slug)
    {
        $selectedCategory = Category::Where('id', $slug)->first();

        return view('admin.master.category.editcategory', [
            'page_title' => $selectedCategory->name,
            'sub_title' => "Edit Information",
            'selected_category' => $selectedCategory
        ]);
    }


    public function show()
    {
        return view('admin.ProductCategory.view');
    }

    public function edit($id)
    {
        $productCategory = ProductCategory::find($id);
        $parents = ProductCategory::whereNull('parent_id')->get();


        return view(
            'admin.master.category.edit',
            array(
                'parents' => $parents,
                'productCategory' => $productCategory
            )
        );
    }

    public function softDelete(Request $request,$slug) {
        
        Category::where('id', $slug)->delete();
        return redirect()->route('get-categories')->with('success','Deleted Successfully.');
        
    }
    public function categoryExport(Request $request)
    {
        return Excel::download(new CategoryExport($request), 'Category.xlsx');
    } 


	public function mbbs_allowed_students(Request $request)
    {
        $data['courses']=Course::whereIn('campus_id',[4,6,23])->get();
		$data['mbbs_allowed_students'] = StudentAllFromOldAgency::select('course_id','roll_no','enrollment_no','first_name','date_of_birth','email','regular_permission','supplementary_permission','challenge_permission','scrutiny_permission','status_description','mobile')
		->where('course_id',$request->course)
		->distinct()
		->get();
		return view('ums.mbbsparanursing.mbbs_allowed_students', $data);
    }

	public function mbbs_allowed_students_edit(Request $request)
    {
		$mbbs_allowed_students_list = StudentAllFromOldAgency::where('roll_no',$request->roll_no)->get();
		if($mbbs_allowed_students_list->count()>0){
			foreach($mbbs_allowed_students_list as $mbbs_allowed_students){
				$mbbs_allowed_students->regular_permission = $request->regular_permission; 
				$mbbs_allowed_students->supplementary_permission = $request->supplementary_permission; 
				$mbbs_allowed_students->challenge_permission = $request->challenge_permission; 
				$mbbs_allowed_students->scrutiny_permission = $request->scrutiny_permission; 
				$mbbs_allowed_students->date_of_birth = date('Y-m-d',strtotime($request->date_of_birth)); 
				$mbbs_allowed_students->save(); 
			}
			return response()->json(['status'=>1,'message'=>'Data Saved Successfully']);
		}else{
			return response()->json(['status'=>0,'message'=>'Roll Number not found']);
		}
    }


    public function nursingTr(Request $request)
	{
        $course_id = $request->course;
        $data['courses'] = Course::whereIn('id',[64])->get();
    	$data['semesters'] = Semester::where('course_id',$course_id)->orderBy('semester_number','asc')->get();
    	$data['sessions'] = AcademicSession::get();
    	$session = $request->session;
    	$semester_id = $request->semester;
    	$semester_details = Semester::where('id',$semester_id)->first();
    	$data['semester_details'] = $semester_details;
        $data['course_id'] = $course_id;
    	$data['semester_id'] = $semester_id;

		$subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
		->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
		->whereNotNull('combined_subject_name')
		->groupBy('combined_subject_name' )
		->orderBy('sub_code','asc')
		->get();
		$subject_total = Subject::where('course_id',$course_id)
		->whereNotNull('combined_subject_name')
		->where('semester_id',$semester_id)
		->get();
		$data['subject_total'] = $subject_total->sum('maximum_mark');

		$subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id){
			$subjects = Subject::where('course_id',$course_id)->where('semester_id',$semester_id)
			->whereNotNull('combined_subject_name')
			->where('combined_subject_name',$item_group->combined_subject_name)
			->get();

			$sub_theory_external = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Theory')
			->get();
			$item_group['theory_oral'] = $sub_theory_external->sum('oral');
			$item_group['theory_ia'] = $sub_theory_external->sum('internal_maximum_mark');

			$item_group['sub_theory_external'] = ($sub_theory_external->sum('internal_maximum_mark')+$sub_theory_external->sum('maximum_mark')+$sub_theory_external->sum('oral'));

			$sub_theory_internal = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Theory')
			->get();
			$item_group['sub_theory_internal'] = $sub_theory_internal->sum('internal_maximum_mark');
							
			$sub_practical_internal = Subject::where('course_id',$course_id)
			->whereNotNull('combined_subject_name')
			->where('semester_id',$semester_id)
			->where('combined_subject_name',$item_group->combined_subject_name)
			->where('subject_type','Practical')
			->get();
			$item_group['sub_practical_internal'] = ($sub_practical_internal->sum('internal_maximum_mark') + $sub_practical_internal->sum('maximum_mark'));

			$item_group['sub_theory_practical_internal'] = ($item_group['sub_theory_internal'] + $item_group['sub_practical_internal']);
			$item_group['subjects_total'] = ($item_group['sub_theory_external'] + $item_group['sub_practical_internal']);
			$item_group['subjects'] = $subjects;
		});
			
		$data['subjects_group_all'] = $subjects_group_all;

		$scrutiny_data = Result::where('scrutiny',1)->pluck('roll_no')->toArray();
		if($request->form_type=='compartment'){
			$mbbs_exam_forms = ExamFee::where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('form_type','compartment')
			->distinct()
			->pluck('roll_no')
			->toArray();
			$students_query = Result::select('roll_no','enrollment_no','course_id','semester')
			->where(['course_id'=>$request->course,'semester'=>$request->semester])
			->where('exam_session', $session)
			->whereIn('roll_no',$mbbs_exam_forms);
		}else{
			$mbbs_exam_forms = ExamFee::where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('form_type','regular')
			->distinct()
			->pluck('roll_no')
			->toArray();
			$students_query = Result::select('roll_no','course_id','semester')
			->where('course_id', $request->course)
			->where('semester', $request->semester)
			->where('exam_session', $session)
			->whereIn('roll_no',$mbbs_exam_forms);
		}
		if($request->form_type=='scrutiny'){
			$students = $students_query->whereIn('roll_no',$scrutiny_data)->distinct()->get();
		}else{
			$students = $students_query->distinct()->get();
		}
		$students->each(function ($item_student, $key_student) use ($course_id,$semester_id,$session){

			$grand_total = Result::where('roll_no',$item_student->roll_no)
			->where('course_id',$course_id)
			->where('semester',$semester_id)
			->where('exam_session', $session)
			->where('external_marks','!=', 'ABSENT')
			->get();
			$item_student['grand_total'] = $grand_total->sum('external_marks');
			$subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
			->whereNotNull('combined_subject_name')
			->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
			->groupBy('combined_subject_name' )
			->orderBy('sub_code','asc')
			->get();
			$item_student['subjects_group_all'] = $subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id,$item_student,$session){
				$subjects = Subject::where('course_id',$course_id)
				->whereNotNull('combined_subject_name')
				->where('semester_id',$semester_id)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->distinct()
				->orderBy('sub_code','asc')
				->get();
				$subject_result = Result::join('subjects','subjects.sub_code','results.subject_code')
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('roll_no',$item_student->roll_no)->where('results.course_id',$course_id)
				->where('semester',$semester_id)
				->where('exam_session', $session)
				->where('external_marks','!=', 'ABSENT')
				->get();

				$student_total = ($subject_result->sum('external_marks'));

				$subjects_total = ($subjects->sum('maximum_mark'));

				$student_theory_external = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Theory')
				->get();
				$student_theory_external_sum = 0;
				$student_theory_internal_sum = 0;
				$student_theory_oral_sum = 0;
				foreach($student_theory_external as $student_theory_externalRow){
					$student_theory_external_sum = $student_theory_external_sum + (int)$student_theory_externalRow->external_marks;
					$student_theory_internal_sum = $student_theory_internal_sum + (int)$student_theory_externalRow->internal_marks;
					$student_theory_oral_sum = $student_theory_oral_sum + (int)$student_theory_externalRow->oral;
				}
				$item_group['student_theory_oral'] = $student_theory_internal_sum;
				$item_group['student_theory_ia'] = $student_theory_oral_sum;

				$item_group['student_theory_external'] = ($student_theory_internal_sum + $student_theory_external_sum + $student_theory_oral_sum);


				$student_theory_internal = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Theory')
				->select('subjects.*','subjects.oral as subject_oral','results.*','results.oral as oral')
				->get();
				$theory_subject_total_required = ($student_theory_internal->sum('internal_maximum_mark') + $student_theory_internal->sum('maximum_mark') + $student_theory_internal->sum('subject_oral'));
				$item_group['theory_grace_mark'] = $item_student->mbbs_grade_for_third($theory_subject_total_required,$item_group['student_theory_external']);
				$item_group['grace_mark'] = $item_group['theory_grace_mark'];

				$student_theory_internal_sum = 0;
				foreach($student_theory_internal as $student_theory_internalRow){
					$student_theory_internal_sum = $student_theory_internal_sum + (int)$student_theory_internalRow->internal_marks;
				}
				$item_group['student_theory_internal'] = $student_theory_internal_sum;
								
				$student_practical_internal = Subject::join('results','results.subject_code','subjects.sub_code')
				->whereNotNull('combined_subject_name')
				->where('roll_no',$item_student->roll_no)
				->where('results.course_id',$course_id)
				->where('semester_id',$semester_id)
				->where('combined_subject_name',$item_group->combined_subject_name)
				->where('subject_type','Practical')
				->get();
				$student_practical_internal_sum = 0;
				$student_practical_external_sum = 0;
				$student_practical_practical_sum = 0;
				foreach($student_practical_internal as $student_practical_internalRow){
					$student_practical_internal_sum = $student_practical_internal_sum + (int)$student_practical_internalRow->internal_marks;
					$student_practical_external_sum = $student_practical_external_sum + (int)$student_practical_internalRow->external_marks;
					$student_practical_practical_sum = $student_practical_practical_sum + (int)$student_practical_internalRow->practical_marks;
				}
				$item_group['student_practical_internal'] = $student_practical_internal_sum;
				$item_group['student_practical_external'] = ($student_practical_practical_sum);

				$item_group['student_practical_total'] = (int)($item_group['student_practical_internal'] + (int)$item_group['student_practical_external']);
				
				$item_group['theory_practical_total'] = ($item_group['student_theory_external'] + $item_group['student_practical_total']);
				$practical_subject_total_required = ($student_practical_internal->sum('internal_maximum_mark') + $student_practical_internal->sum('maximum_mark'));
				$item_group['practical_grace_mark'] = $item_student->mbbs_grade_for_third($practical_subject_total_required,$item_group['student_practical_total']);

				$item_group['grace_mark'] = [$item_group['theory_grace_mark'],$item_group['practical_grace_mark']];
				$item_group['student_theory_practical_internal'] = (int)($item_group['student_theory_internal'] + (int)$item_group['student_practical_internal']);

				$item_group['student_total'] = $student_total;
				$item_group['subjects_total'] = $subjects_total;
				$item_group['subjects'] = $subjects->each(function ($item_sub, $key_sub) use ($item_student,$session){
					$subject_result = Result::where('roll_no',$item_student->roll_no)
					->where('results.course_id',$item_sub->course_id)
					->where('semester',$item_sub->semester_id)
					->where('subject_code',$item_sub->sub_code)
					->where('exam_session', $session)
					->first();
					if($item_sub->subject_type == 'Theory'){
						$item_sub['theory_total_all'] = ($subject_result)?((int)$subject_result->external_marks + (int)$subject_result->oral + (int)$subject_result->internal_marks):0;
					}
					if($item_sub->subject_type == 'Practical'){
						$item_sub['practical_total_all'] = ($subject_result)?((int)$subject_result->practical_marks + (int)$subject_result->internal_marks):0;
					}
					$item_sub['subject_result'] = $subject_result;
				});

			});
			$final_result = $item_student->final_result_for_third($item_student);
			Result::where(['roll_no'=>$item_student->roll_no,'course_id'=>$item_student->course_id,'semester'=>$item_student->semester,'exam_session'=>$session])->update(['result'=>$final_result]);
			$item_student['final_result'] = $final_result;
		});
		$data['students'] = $students;
		$data['subject_total'] = $subjects_group_all->sum('subjects_total');

        if($request->pdf_download!=null){
			$data['download'] = 'pdf';
            $htmlfile = view('admin.tr.nursing-tr', $data)->render();
			$pdf = app()->make('dompdf.wrapper');
			$pdf->loadHTML($htmlfile,'UTF-8')
			->setWarnings(false)
			->setPaper('a1', 'landscape');
            return $pdf->download('Regular TR Report For Course '.$semester_details->course->name.' ( '.$semester_details->name.' ) and Academic Session '.$request->session.'.pdf');
        }

		return view('admin.tr.nursing-tr', $data);
    }


  //   public function nursingTr(Request $request)
  //   {
  //   	// dd($request->all());
  //   	$data['campuses'] = Campuse::get();
  //   	$data['courses'] = Course::all();
  //   	$data['semesters'] = Semester::orderBy('id','asc')->get();
  //   	$data['sessions'] = AcademicSession::all();
  //   	$course_id = $request->course;
  //   	$semester_id = $request->semester;
  //   	$data['course_id'] = $request->course;
  //   	$data['semester_id'] = $request->semester;
  //   	$data['subjects_group_all'] = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
		// 					->where(['course_id'=>$request->course,'semester_id'=>$request->semester])
		// 					->whereNotNull('combined_subject_name')
		// 					->groupBy('combined_subject_name')
		// 					->orderBy('sub_code','asc')
		// 					->get();
  //   	$data['result'] = Result::where(['course_id' => $request->course, 'semester' =>$request->semester])->first();
  //   	$data['subject_total'] = Subject::where('course_id',$request->course)
		// 	->whereNotNull('combined_subject_name')
		// 	->where('semester_id',$request->semester)
		// 	->get();
		// 	$data['subject_total'] = $data['subject_total']->sum('maximum_mark');

		// $data['subjects_group_all']->each(function ($item_group, $key_group) use ($course_id,$semester_id){
		// 	$subjects = Subject::where('course_id',$course_id)->where('semester_id',$semester_id)
		// 					->whereNotNull('combined_subject_name')
		// 					->where('combined_subject_name',$item_group->combined_subject_name)->get();

		// 	$sub_theory_external = Subject::where('course_id',$course_id)
		// 											->whereNotNull('combined_subject_name')
		// 											->where('semester_id',$semester_id)
		// 											->where('combined_subject_name',$item_group->combined_subject_name)
		// 											->where('subject_type','Theory')
		// 											->get();
		// 	$item_group['sub_theory_external'] = $sub_theory_external->sum('maximum_mark');

		// 	$sub_theory_internal = Subject::where('course_id',$course_id)
		// 											->whereNotNull('combined_subject_name')
		// 											->where('semester_id',$semester_id)
		// 											->where('combined_subject_name',$item_group->combined_subject_name)
		// 											->where('subject_type','Theory')
		// 											->get();
		// 	$item_group['sub_theory_internal'] = $sub_theory_internal->sum('internal_maximum_mark');
							
		// 	$sub_practical_internal = Subject::where('course_id',$course_id)
		// 											->whereNotNull('combined_subject_name')
		// 											->where('semester_id',$semester_id)
		// 											->where('combined_subject_name',$item_group->combined_subject_name)
		// 											->where('subject_type','Practical')
		// 											->get();
		// 	$item_group['sub_practical_internal'] = $sub_practical_internal->sum('internal_maximum_mark');

		// 	$item_group['sub_theory_practical_internal'] = ($item_group['sub_theory_internal'] + $item_group['sub_practical_internal']);
		// 	$item_group['subjects_total'] = ($subjects->sum('maximum_mark'));
		// 	$item_group['subjects'] = $subjects;
		// });

  //   	$data['results'] = Result::where(['course_id' => $request->course, 'semester' =>$request->semester])->get();
			
		// $data['subjects_group_all'] = $data['subjects_group_all'];

  //   	return view('admin.tr.nursing-tr',$data);


  //   }	


	public function saveExternalValue(Request $request){
		$columnName = $request->columnName;
		$result = Result::where('roll_no',$request->roll_no)
		->where('subject_code',$request->subject_code)
		->first();
		if($result){
			$result->$columnName = $request->markValue;
			$result->save();
			return response()->json(['message'=>true]);
		}
		return response()->json(['message'=>false]);
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