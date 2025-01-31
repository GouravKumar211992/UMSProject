<?php

<<<<<<< HEAD
namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ums\AdminController;

use App\Models\ums\Course;
use App\Models\ums\Campuse;
use App\Models\ums\Semester;
use App\Models\ums\Subject;
use App\Models\ums\Result;
use App\Models\ums\AcademicSession;
use App\Models\ums\ExamFee;
use App\Models\ums\ExamType;
use App\Models\ums\GradeOldAllowedSemester;
use App\Http\Traits\ResultsTrait;
use App\Models\ums\StudentSubject;
=======
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

use App\Models\Course;
use App\Models\Campuse;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Result;
use App\Models\AcademicSession;
use App\Models\ExamFee;
use App\Models\ExamType;
use App\Models\GradeOldAllowedSemester;
use App\Http\Traits\ResultsTrait;
use App\Models\StudentSubject;
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
use DB;

class TrController extends  AdminController
{
<<<<<<< HEAD
	// use ResultsTrait;
=======
	use ResultsTrait;
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
    public function __construct()
    {
        parent::__construct();
    }


    // public function trGenerator(Request $request)
    public function index(Request $request)
    {
		// dd($request);
        $data['sessions'] = AcademicSession::orderBy('id','DESC')->get();
        $data['campuses'] = Campuse::withoutTrashed()->orderBy('name')->get();
        $data['campus_details'] = Campuse::withoutTrashed()->find($request->campus_id);
        $data['courses'] = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
		$data['semesters']=Semester::withoutTrashed()->where('course_id',$request->course)->orderBy('id','asc')->get();
        $course_id = $request->course;
        $session = $request->academic_session;
    	$semester_id = $request->semester;
        $data['course_id'] = $course_id;
    	$data['semester_id'] = $semester_id;
    	$finalize = $request->finalize;

		$group_name_exam_fees = '';
		$group_name_text = '';
		$group_name = '';
		$full_retult = [];

		$data['subjects_header_group'] = ExamFee::select('subject')
			->where('course_id',$course_id)
			->where('semester',$semester_id)
			->where('academic_session',$session)
			->where('form_type','regular')
			->distinct('subject')
			->get();
		$rollnumbers = ExamFee::select('roll_no')
			->where('course_id',$course_id)
			->where('semester',$semester_id)
			->where('academic_session',$session)
			->where('form_type','regular')
			->distinct('roll_no')
			->pluck('roll_no')
			->toArray();
		if($request->group_name && count($request->group_name)>0){
		foreach($request->group_name as $group_index=>$group_name_row){
			$group_name_exam_fees = $group_name_row;
			$group_name = explode(" ",$group_name_row);
			$group_name = Subject::where('semester_id',$semester_id)
				->where('course_id',$course_id)
				->whereIn('sub_code',$group_name)
				->orderBy('position')
				->pluck('sub_code')
				->toArray();
			$group_name_text = implode(" ",$group_name);

			$data_final['group_name_text'] = $group_name_text;
			
			if($session){
				$subjects_query = Subject::select('subjects.name','sub_code','internal_maximum_mark','maximum_mark')
					->where('course_id',$course_id)
					->where('semester_id',$semester_id);
				if($group_name!=''){
					$subjects_query->whereIn('sub_code',$group_name);
				}
			}

			$students = [];
			$studentsQuery = Result::select('roll_no','course_id','semester','exam_session','back_status_text')
				->where('results.course_id',$course_id)
				->where('semester',$semester_id)
				->where('exam_session',$session)
				->whereIn('roll_no',$rollnumbers)
				->where('back_status_text','REGULAR')
				->where('result_type','new')
				->where('status',1);
				if($request->roll_no){
					$studentsQuery->where('roll_no',$request->roll_no);
				}
				$students = $studentsQuery->distinct()
				->orderBy('roll_no')
				->get();
				foreach($students as $students_key_group=>$students_item){
					$check_exam_group = ExamFee::where('subject',$group_name_exam_fees)->where('roll_no',$students_item->roll_no)->first();
					if($check_exam_group){
						$full_retult[] = $students_item->roll_no;
						$this->trGenerateByCommand($students_item);
						if($finalize==true){
							$this->finalizeTr($students_item,1);
						}
						if($finalize=='reset'){
							$this->finalizeTr($students_item,0);
						}

					}else{
						$students->forget($students_key_group);
					}
				};
			}
		}
		$data['full_retult'] = $full_retult;
		$data['download'] = '';

<<<<<<< HEAD
		return view('ums.result.regular_tr_generate', $data);
=======
		return view('admin.tr.university-tr', $data);
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
	}

	public function freezRegularTr(Request $request){
		$approval_date = date('Y-m-d',strtotime($request->approval_date));
		$examType = ExamType::where('exam_type',$request->form_type)->first();
		if($examType){
		  $back_status_text = $examType->result_exam_type;
		}else{
		  $back_status_text = null;
		}
		$results_query = Result::where('back_status_text',$back_status_text)
		->join('student_subjects',function($query)use ($request){
			$query->on('student_subjects.roll_number','results.roll_no')
			->on('student_subjects.course_id','results.course_id')
			->on('student_subjects.semester_id','results.semester')
			->on('student_subjects.session','results.exam_session')
			->where('student_subjects.type',$request->form_type);
		  })
		->where('exam_session',$request->academic_session)
		->where('semester',$request->semester)
		->where('results.course_id',$request->course_id)
		->where('result_type','new');
		$results = clone $results_query;
		$results_query->select('results.*')->where('status',1)->update(['status'=>2,'approval_date'=>$approval_date]);
		$result_count = $results->select('results.roll_no')
		->where('status',2)
		->distinct('results.roll_no')
		->count();
		return back()->with('success',$result_count.' Results are Freezed.');
	}
	public function unFreezRegularTr(Request $request){
		$approval_date = date('Y-m-d',strtotime($request->approval_date));
		$examType = ExamType::where('exam_type',$request->form_type)->first();
		if($examType){
		  $back_status_text = $examType->result_exam_type;
		}else{
		  $back_status_text = null;
		}
		$results_query = Result::where('back_status_text',$back_status_text)
		->join('student_subjects',function($query)use ($request){
			$query->on('student_subjects.roll_number','results.roll_no')
			->on('student_subjects.course_id','results.course_id')
			->on('student_subjects.semester_id','results.semester')
			->on('student_subjects.session','results.exam_session')
			->where('student_subjects.type',$request->form_type);
		  })
		->where('exam_session',$request->academic_session)
		->where('semester',$request->semester)
		->where('results.course_id',$request->course_id)
		->where('result_type','new');
		$results = clone $results_query;
		$results_query->select('results.*')->where('status',2)->update(['status'=>1,'approval_date'=>null]);
		$result_count = $results->select('results.roll_no')
		->where('status',1)
		->distinct('results.roll_no')
		->count();
		return back()->with('success',$result_count.' Results are Unfreezed.');
	}
	public function trSummaryShowStudents(Request $request){
		$examType = ExamType::where('exam_type',$request->form_type)->first();
		$results = array();
		$result_single = Result::where('exam_session',$request->academic_session)
		->where('semester',$request->semester)
		->where('course_id',$request->course_id)
		->where('status',$request->status)
		->where('result_type','new')
		->distinct()
		->first();
		if($result_single){
			$results = $result_single->getTrAllStudent($request->form_type,2);
		}
		// dd($results->first()->semester);
		return view('admin.tr.tr-summary-show-students',compact('results','examType'));
	}

	public function universityTrView(Request $request)
    {
		$sessions = AcademicSession::orderBy('id','DESC')->get();
        $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
<<<<<<< HEAD
		// dd($campuses->where('id' , $request->id));
=======
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
        $courses = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
		$course_single = Course::find($request->course);
		$semesters = Semester::withoutTrashed()->where('course_id',$request->course)->orderBy('id','asc')->get();
		$semester_details = Semester::find($request->semester);
		$group_name_array = ($request->group_name)?array_filter($request->group_name):array();

		$data['group_list'] = ExamFee::has('student')
		->where('course_id',$request->course)
		->where('semester',$request->semester)
		->where('academic_session',$request->academic_session)
		->where('form_type','regular')
		->distinct('subject')
		->pluck('subject')
		->toArray();

		$campus_details = null;
		$full_retult = [];
		if($request->showdata && $semester_details){
			$campus_details = $semester_details->course->campuse;
			$full_retult_query = ExamFee::has('student')
			->select('subject','course_id','semester','academic_session','form_type')
			->where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('academic_session',$request->academic_session)
			->distinct('exam_fees.subject')
			->where('form_type','regular');
			if($request->roll_no){
				$full_retult_query->where('roll_no',$request->roll_no);
			}
			if(count($group_name_array)>0){
				$full_retult_query->whereIn('subject',$request->group_name);
			}
			$full_retult = $full_retult_query->paginate(20);
		}
		$data['grade_type'] = GradeOldAllowedSemester::where('semester_id',$request->semester)
		->where('academic_session',$request->academic_session)
		->first();
		$data['result_details'] = Result::where('semester',$request->semester)
		->where('exam_session',$request->academic_session)
		->first();
		$data['sessions'] = $sessions;
		$data['campuses'] = $campuses;
		$data['courses'] = $courses;
		$data['course_single'] = $course_single;
		$data['semesters'] = $semesters;
		$data['semester_details'] = $semester_details;
		$data['campus_details'] = $campus_details;
		$data['full_retult'] = $full_retult;
		$data['download'] = '';

		if($request->paper_size!=null && count($full_retult) > 0){
			$data['download'] = 'pdf';
<<<<<<< HEAD
            $htmlfile = view('ums.result.Regular_TR_View', $data)->render();
=======
            $htmlfile = view('admin.tr.university-tr-view', $data)->render();
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
			$pdf = app()->make('dompdf.wrapper');
			$pdf->loadHTML($htmlfile,'UTF-8')
			->setWarnings(false)
			->setPaper($request->paper_size, $request->paper_type);
            return $pdf->download('Regular TR Report For Course '.$semester_details->course->name.' ( '.$semester_details->name.' ) and Academic Session '.$request->academic_session.'.pdf');
        }
<<<<<<< HEAD
		return view('ums.result.Regular_TR_View', $data);
	}
	public function batchPrefixByBatch($batch)
	{
		$batchPrefix = [];
		
		switch ($batch) {
			case '2020-2021':
				$batchPrefix = ['2020-2021', '2021-2022'];
				break;
			case '2021-2022':
				$batchPrefix = ['2021-2022', '2022-2023'];
				break;
			case '2022-2023':
				$batchPrefix = ['2022-2023', '2023-2024'];
				break;
			default:
				$batchPrefix = ['2013-2014', '2014-2015', '2015-2016'];
				break;
		}
		
		return $batchPrefix;
	}

	public function mdTrView(Request $request)
{
    // Array of batches
    $batchs = ['2020-2021', '2021-2022', '2023-2024'];

    // Get the university and campus details
    $unuversity_name = Campuse::find(1);
    $campus_details = Campuse::find(4);

    // Fetch courses based on the specific IDs
    $courses = Course::whereIn('id', [131, 132])->get();

    // Fetch semesters based on the selected course
    $semesters = Semester::where('course_id', $request->course)->orderBy('id', 'asc')->get();
    $semester_details = Semester::find($request->semester);

    // Fetch subjects for the selected semester
    $subjects = Subject::where('semester_id', $request->semester)->orderBy('position', 'asc')->get();

    // Get the batch prefix using the method defined in the controller
    $batchPrefixByBatch = $this->batchPrefixByBatch($request->batch);

    // Convert the array to a string (join array elements with a comma or any other separator)
    $batchPrefixString = implode(',', $batchPrefixByBatch); // Join array elements into a single string

    // Query to fetch results based on the batch prefix and other criteria
    $results = Result::where('roll_no', 'LIKE', $batchPrefixString . '%')
        ->select('back_status_text', 'exam_session', 'semester', 'course_id', 'roll_no')
        ->where('semester', $request->semester)
        ->distinct()
        ->get();

    // Pass all the required data to the view
    $data['batchs'] = $batchs;
    $data['campus_details'] = $campus_details;
    $data['courses'] = $courses;
    $data['semesters'] = $semesters;
    $data['semester_details'] = $semester_details;
    $data['subjects'] = $subjects;
    $data['subject_theory_count'] = $subjects->where('subject_type', 'Theory')->count();
    $data['results'] = $results;
    $data['unuversity_name'] = $unuversity_name;
    $data['download'] = '';

    // Check if the user wants to download the PDF
    if ($request->download_pdf == 'true') {
        $data['download'] = 'pdf';
        $htmlfile = view('ums.result.MD_TR_Generate', $data)->render();
        $pdf = app()->make('dompdf.wrapper');

        $pdf->loadHTML($htmlfile, 'UTF-8')
            ->setWarnings(false)
            ->setPaper('a3', 'landscape');
        return $pdf->download("MD_TR_" . $semester_details->course->name . "_batch_" . $request->batch . ".pdf");
    }

    // Return the view with the data
    return view('ums.result.MD_TR_Generate', $data);
}

=======
		return view('admin.tr.university-tr-view', $data);
	}
	public function mdTrView(Request $request)
    {
		$batchs = batchArray();
        $unuversity_name = Campuse::find(1);
        $campus_details = Campuse::find(4);
        $courses = Course::whereIn('id',[131,132])->get();
		$semesters = Semester::where('course_id',$request->course)->orderBy('id','asc')->get();
		$semester_details = Semester::find($request->semester);
		$subjects = Subject::where('semester_id',$request->semester)->orderBy('position','asc')->get();

		$batchPrefixByBatch = batchPrefixByBatch($request->batch);
		$results = Result::where('roll_no','LIKE',$batchPrefixByBatch.'%')
		->select('back_status_text','exam_session','semester','course_id','roll_no')
		->where('semester',$request->semester)
		->distinct()
		->get();
		
		$data['batchs'] = $batchs;
		$data['campus_details'] = $campus_details;
		$data['courses'] = $courses;
		$data['semesters'] = $semesters;
		$data['semester_details'] = $semester_details;
		$data['subjects'] = $subjects;
		$data['subject_theory_count'] = $subjects->where('subject_type','Theory')->count();
		$data['results'] = $results;
		$data['unuversity_name'] = $unuversity_name;
		$data['download'] = '';

		if($request->download_pdf=='true'){
			$data['download'] = 'pdf';
            $htmlfile = view('admin.tr.md-tr-view', $data)->render();
			$pdf = app()->make('dompdf.wrapper');
			$pdf->loadHTML($htmlfile,'UTF-8')
			->setWarnings(false)
			->setPaper('a3', 'landscape');
            return $pdf->download("MD_TR_".$semester_details->course->name."_batch_".$request->batch.".pdf");
        }
		return view('admin.tr.md-tr-view', $data);
	}
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d


    // public function backTr(Request $request)
    // {
    //     $data['sessions'] = AcademicSession::orderBy('id','DESC')->get();
    //     $data['campuses'] = Campuse::withoutTrashed()->orderBy('name')->get();
    //     $data['campus_details'] = Campuse::withoutTrashed()->find($request->campus_id);
    //     $data['courses'] = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
	// 	$data['semesters'] = Semester::withoutTrashed()->where('course_id',$request->course)->orderBy('id','asc')->get();
    //     $course_id = $request->course;
    //     $session = $request->academic_session;
    // 	$semester_id = $request->semester;
    //     $data['course_id'] = $course_id;
    // 	$data['semester_id'] = $semester_id;
    // 	$finalize = $request->finalize;

	// 	$group_name_exam_fees = '';
	// 	$group_name_text = '';
	// 	$group_name = '';
	// 	$full_retult = [];

	// 	$data['subjects_header_group'] = ExamFee::withTrashed()
	// 		->select(DB::raw('subject_group as subject'))
	// 		->where('course_id',$course_id)
	// 		->where('semester',$semester_id)
	// 		->where('academic_session',$session)
	// 		->where('form_type',$request->form_type)
	// 		->distinct('subject')
	// 		->get();
	// 	$rollnumbers = ExamFee::select('roll_no')
	// 		->where('course_id',$course_id)
	// 		->where('semester',$semester_id)
	// 		->where('academic_session',$session)
	// 		->where('form_type',$request->form_type)
	// 		->distinct('subject')
	// 		->pluck('roll_no')
	// 		->toArray();
	// 	if($request->group_name && count($request->group_name)>0){
	// 	foreach($request->group_name as $group_index=>$group_name_row){
	// 		$group_name_exam_fees = $group_name_row;
	// 		$group_name = explode(" ",$group_name_row);
	// 		$group_name = Subject::withTrashed()->where('semester_id',$semester_id)
	// 			->where('course_id',$course_id)
	// 			->whereIn('sub_code',$group_name)
	// 			->orderBy('position')
	// 			->pluck('sub_code')
	// 			->toArray();
	// 		$group_name_text = implode(" ",$group_name);

	// 		$data_final['group_name_text'] = $group_name_text;

				
	// 		$subjects = Subject::withTrashed()->where('course_id',0)->get();
	// 		if($session){
	// 			$subjects_query = Subject::withTrashed()->select('subjects.name','sub_code','internal_maximum_mark','maximum_mark')
	// 				->where('course_id',$course_id)
	// 				->where('semester_id',$semester_id);
	// 			if($group_name!=''){
	// 				$subjects_query->whereIn('sub_code',$group_name);
	// 			}
	// 			$subjects = $subjects_query->orderBy('position')->get();
	// 		}

	// 		$students = [];
	// 		$examType = ExamType::where('exam_type',$request->form_type)->first()->result_exam_type;
	// 		$studentsQuery = Result::select('roll_no','course_id','semester')
	// 			->where('results.course_id',$course_id)
	// 			->where('semester',$semester_id)
	// 			->where('exam_session',$session)
	// 			->where('result_type','new')
	// 			->where('back_status_text',$examType)
	// 			->whereIn('roll_no',$rollnumbers);
	// 			if($request->roll_no){
	// 				$studentsQuery->where('roll_no',$request->roll_no);
	// 			}
	// 			$students = $studentsQuery->distinct()
	// 			->orderBy('roll_no')
	// 			->get();
	// 			// dd($students);
	// 			$students->each(function ($students_item, $students_key_group) use ($course_id,$semester_id,$group_name,$students,$session,$finalize,$group_name_exam_fees){
	// 			$check_exam_group = ExamFee::withTrashed()->where('subject_group',$group_name_exam_fees)->where('roll_no',$students_item->roll_no)->first();
	// 			// dd($group_name_exam_fees);
	// 			if($check_exam_group){

	// 				$students_item_array = [];
	// 				$grade_value = 0;
	// 				$subject_credit = 0;
	// 				$failed_count = 0;
	// 				$absents_array = [];
	// 				$total_subjects = count($group_name);
	// 				foreach($group_name as $key_group=>$item_row){
	// 					// dd($item_row);
	// 					$item_group = [];
	// 					// $this->createResult($item_row,$course_id,$semester_id,$session,$students_item->roll_no);
	// 					$result_query = Result::select('results.*')
	// 						->where('roll_no',$students_item->roll_no)
	// 						->where('subject_code',$item_row)
	// 						->where('course_id',$course_id)
	// 						->where('semester',$semester_id)
	// 						->where('exam_session',$session)
	// 						->where('back_status_text','BACK');
	// 					$result_clone = clone $result_query;
	// 					$result_saved_clone = clone $result_query;
	// 					$result = $result_clone->first();

	// 					$external_marks_value = ($result->external_marks)?$result->external_marks:'-';
	// 					if($external_marks_value == 'ABS'){
	// 						$absents_array[] = $external_marks_value;
	// 					}
	// 					$item_group['total_marks_value'] = ( (int)$result->internal_marks + (int)$result->external_marks );
	// 					$item_group['external_marks_cancelled'] = $result->external_marks_cancelled;

	// 					$grade = $result->grade();
	// 					if(!$grade){
	// 						dd($result);
	// 					}
	// 					$grade_letter = $grade->grade_letter;
	// 					if($grade_letter=='F'){
	// 						$failed_count = ($failed_count + 1);
	// 					}
	// 					$grade_value = (($grade->grade_point * $result->credit) + $grade_value);
	// 					$subject_credit = ( $result->credit + $subject_credit );
	// 					$this->updateGradeLetter($result);
	// 					$result_saved = $result_saved_clone->first();
	// 					$item_group['result'] = $result_saved;
	// 					$students_item_array[] = $item_group;
	// 				}
	// 				$students_item->subjects = $students_item_array;

	// 				$cgpa = 0; $qp = 0; $sgpa = 0;
	// 				if($grade_value > 0){
	// 					$sgpa = ( $grade_value / $subject_credit );
	// 					$cgpa = ( $sgpa * $subject_credit / $subject_credit);
	// 					$qp = $grade_value;
	// 				}
	// 				$students_item['qp'] = $qp;
	// 				$sgpa = sprintf("%0.2f",$sgpa);
	// 				$students_item['sgpa'] = $sgpa;


	// 				$getResult = $students_item->getResult($cgpa);
	// 				$students_item['result'] = $getResult;

	// 				$result_final_text = $students_item->getResultFinal($getResult,$absents_array,$failed_count,$total_subjects);
	// 				$students_item['result_final_text'] = $result_final_text;
	// 				$this->updateTrResult($students_item,$result_final_text);
	// 				if($finalize==true){
	// 					$this->finalizeTr($students_item->roll_no,$course_id,$semester_id,$session,1);
	// 				}
	// 				if($finalize=='reset'){
	// 					$this->finalizeTr($students_item->roll_no,$course_id,$semester_id,$session,0);
	// 				}

	// 			}else{
	// 				$students->forget($students_key_group);
	// 			}
	// 		});

	// 		$data_final['students'] = $students;
	// 		$data_final['subjects'] = $subjects;
	// 		$data_final['result_details'] = Result::where('semester',$semester_id)
	// 			->where('exam_session',$session)
	// 			->first();
	// 		$data_final['grade_type'] = GradeOldAllowedSemester::where('semester_id',$semester_id)
	// 			->where('academic_session',$session)
	// 			->first();
	// 		$full_retult[$group_index] = $data_final;
	// 		echo $group_index.', ';
	// 		// dd($group_index);
	// 	}
	// 	}
	// 	$data['full_retult'] = $full_retult;
	// 	$data['download'] = '';

    //     // view()->share(compact('paymentDetails','download','total_fees','order_id','applications_single','payment_id'));
    //     if($request->paper_size!=null && count($full_retult) > 0){
	// 		$data['download'] = 'pdf';
    //         $htmlfile = view('admin.tr.back-university-tr', $data)->render();
    //         $pdf = app()->make('dompdf.wrapper');
    //         $pdf->loadHTML($htmlfile,'UTF-8')
	// 		->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif'])
	// 		->setWarnings(false)
	// 		->setPaper($request->paper_size, 'landscape');
    //         return $pdf->download('TR Report.pdf');
    //     }

	// 	return view('admin.tr.back-university-tr', $data);
	// }

	// public function backUniversityTrView(Request $request)
    // {
    //     $sessions = AcademicSession::orderBy('id','DESC')->get();
    //     $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    //     $courses = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
	// 	$semesters = Semester::withoutTrashed()->where('course_id',$request->course)->orderBy('id','asc')->get();
	// 	$semester_details = Semester::find($request->semester);

	// 	$campus_details = null;
	// 	$full_retult = [];
	// 	if($request->showdata && $semester_details){
	// 		$campus_details = $semester_details->course->campuse;
	// 		$full_retult = ExamFee::withTrashed()
	// 		->select(DB::raw('subject_group as subject'),'course_id','semester','academic_session','form_type')
	// 		->where('course_id',$request->course)
	// 		->where('semester',$request->semester)
	// 		->where('academic_session',$request->academic_session)
	// 		->where('form_type',$request->form_type)
	// 		->distinct('subject')
	// 		// ->limit(25)
	// 		->paginate(100);
	// 	}

	// 	$data['grade_type'] = GradeOldAllowedSemester::where('semester_id',$request->semester)
	// 	->where('academic_session',$request->academic_session)
	// 	->first();
	// 	$data['result_details'] = Result::where('semester',$request->semester)
	// 	->where('exam_session',$request->academic_session)
	// 	->first();
	// 	$data['sessions'] = $sessions;
	// 	$data['campuses'] = $campuses;
	// 	$data['courses'] = $courses;
	// 	$data['semesters'] = $semesters;
	// 	$data['semester_details'] = $semester_details;
	// 	$data['campus_details'] = $campus_details;
	// 	$data['full_retult'] = $full_retult;
	// 	$data['download'] = '';

	// 	// view()->share(compact('paymentDetails','download','total_fees','order_id','applications_single','payment_id'));
	// 	// dd($request->all());
    //     if($request->paper_size!=null && count($full_retult) > 0){
	// 		$data['download'] = 'pdf';
    //         $htmlfile = view('admin.tr.back-university-tr-view', $data)->render();
	// 		$pdf = app()->make('dompdf.wrapper');
	// 		$pdf->loadHTML($htmlfile,'UTF-8')
	// 		->setWarnings(false)
	// 		->setPaper($request->paper_size, 'landscape');
    //         return $pdf->download('TR Report.pdf');
    //     }

	// 	return view('admin.tr.back-university-tr-view', $data);
	// }

    public function finalBackTr(Request $request)
    {
        $data['sessions'] = AcademicSession::orderBy('id','DESC')->get();
        $data['campuses'] = Campuse::withoutTrashed()->orderBy('name')->get();
        $data['campus_details'] = Campuse::withoutTrashed()->find($request->campus_id);
        $data['courses'] = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
		$data['semesters'] = Semester::withoutTrashed()->where('course_id',$request->course)->orderBy('id','asc')->get();
        $course_id = $request->course;
        $session = $request->academic_session;
    	$semester_id = $request->semester;
        $data['course_id'] = $course_id;
    	$data['semester_id'] = $semester_id;
    	$finalize = $request->finalize;
		if($request->month_year){
			$month_year_array = $request->month_year;
		}else{
			$month_year_array = [];
		}

		$group_name_exam_fees = '';
		$group_name_text = '';
		$group_name = '';
		$full_retult = [];

		$month_years = StudentSubject::withTrashed()
		->select(DB::raw('MONTH(created_at) month'),DB::raw('YEAR(created_at) year'))
		->where('course_id',$course_id)
		->where('semester_id',$semester_id)
		->where('session',$session)
		->where('type',$request->form_type)
		->distinct()
		->get();
		$data['month_years'] = $month_years;
		$data['subjects_header_group'] = StudentSubject::withTrashed()
		->select(DB::raw('subject_group as subject'))
		->where('course_id',$course_id)
		->where('semester_id',$semester_id)
		->where('session',$session)
		->where('type',$request->form_type)
		->where(function($q) use ($month_year_array){
			foreach($month_year_array as $index=>$month_year_row){
				if($index==0){
					$q->where('student_subjects.created_at','LIKE',$month_year_row.'%');
				}else{
					$q->orWhere('student_subjects.created_at','LIKE',$month_year_row.'%');
				}
			}
		})
		->distinct('subject')
		->get();
		$rollnumbers = StudentSubject::select('roll_number')
		->where('course_id',$course_id)
		->where('semester_id',$semester_id)
		->where('session',$session)
		->where('type',$request->form_type)
		->where(function($q) use ($month_year_array){
			foreach($month_year_array as $index=>$month_year_row){
				if($index==0){
					$q->where('student_subjects.created_at','LIKE',$month_year_row.'%');
				}else{
					$q->orWhere('student_subjects.created_at','LIKE',$month_year_row.'%');
				}
			}
		})
		->distinct('subject')
		->pluck('roll_number')
		->toArray();
		if($request->group_name && count($request->group_name)>0){
			foreach($request->group_name as $group_index=>$group_name_row){
				$group_name_exam_fees = $group_name_row;
				$group_name = explode(" ",$group_name_row);
				$group_name = Subject::withTrashed()->where('semester_id',$semester_id)
					->where('course_id',$course_id)
					->whereIn('sub_code',$group_name)
					->orderBy('position')
					->distinct('sub_code')
					->pluck('sub_code')
					->toArray();
				$group_name_text = implode(" ",$group_name);

				$data['group_name_text'] = $group_name_text;
					
				if($session){
					$subjects_query = Subject::withTrashed()->select('subjects.name','sub_code','internal_maximum_mark','maximum_mark')
						->where('course_id',$course_id)
						->where('semester_id',$semester_id);
						if($group_name!=''){
						$subjects_query->whereIn('sub_code',$group_name);
					}
				}

				$students = [];
				$examType = ExamType::where('exam_type',$request->form_type)->first()->result_exam_type;
				$studentsQuery = Result::select('roll_no','course_id','semester','exam_session','back_status_text')
				->where('results.course_id',$course_id)
				->where('semester',$semester_id)
				->where('exam_session',$session)
				->whereIn('roll_no',$rollnumbers)
				->where('back_status_text','BACK')
				->where('result_type','new')
				->where('status',1);
				if($request->roll_no){
					$studentsQuery->where('roll_no',$request->roll_no);
				}
				$students = $studentsQuery->distinct()
				->orderBy('roll_no')
				->get();
				foreach($students as $students_key_group=>$students_item){
					$check_exam_group = StudentSubject::withTrashed()->where('subject_group',$group_name_exam_fees)->where('roll_number',$students_item->roll_no)->first();
					if($check_exam_group){
						$full_retult[] = $students_item->roll_no;
						$this->trGenerateByCommand($students_item);
						if($finalize==true){
							$this->finalizeTr($students_item,1);
						}
						if($finalize=='reset'){
							$this->finalizeTr($students_item,0);
						}
					}else{
						$students->forget($students_key_group);
					}
				};

			}
		}
		$data['full_retult'] = $full_retult;
		$data['download'] = '';
		return view('admin.tr.final-back-tr-generate', $data);
	}

	public function finalBackUniversityTrView(Request $request)
    {
        $sessions = AcademicSession::orderBy('id','DESC')->get();
        $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
        $courses = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
		$semesters = Semester::withoutTrashed()->where('course_id',$request->course)->orderBy('id','asc')->get();
		$semester_details = Semester::find($request->semester);

		if($request->month_year){
			$month_year_array = $request->month_year;
		}else{
			$month_year_array = [];
		}
		$month_year_text = implode(',',$month_year_array);

		$month_years = StudentSubject::withTrashed()
		->select(DB::raw('MONTH(created_at) month'),DB::raw('YEAR(created_at) year'))
		->where('course_id',$request->course)
		->where('semester_id',$request->semester)
        // ->where('roll_number','LIKE','211010%')
		->where('session',$request->academic_session)
		->where('type',$request->form_type)
		->distinct()
		->get();
		$data['month_years'] = $month_years;


		$campus_details = null;
		$full_retult = [];
		if($request->showdata && $semester_details){
			$campus_details = $semester_details->course->campuse;
			$full_retult_query = StudentSubject::withTrashed()
			->select('subject_group','course_id','semester_id','session','type',DB::raw("'".$month_year_text."'"." as month_year_text"))
			->where('course_id',$request->course)
			->where('semester_id',$request->semester)
			// ->where('roll_number','LIKE','211010%')
			->where('session',$request->academic_session)
			->where('type',$request->form_type)
			->where(function($q) use ($month_year_array){
				if(count($month_year_array)>0){
					foreach($month_year_array as $index=>$month_year_row){
						if($index==0){
							$q->where('student_subjects.created_at','LIKE',$month_year_row.'%');
						}else{
							$q->orWhere('student_subjects.created_at','LIKE',$month_year_row.'%');
						}
					}
				}
			});
			if($request->batch){
				$full_retult_query->where('roll_number','LIKE',$request->batch.'%');
			}
			if($request->roll_no){
				$full_retult_query->where('roll_number',$request->roll_no);
			}
			// ->where('roll_number','211010231')
			$full_retult = $full_retult_query->whereNotNull('subject_group')
			->distinct('subject_group')
			->paginate(50);
			// ->get();
			// ->first();
			// dd($full_retult);
		}

		$data['grade_type'] = GradeOldAllowedSemester::where('semester_id',$request->semester)
		->where('academic_session',$request->academic_session)
		->first();
		$data['result_details'] = Result::where('semester',$request->semester)
		->where('exam_session',$request->academic_session)
		// ->where('roll_no','LIKE','211010%')
		->first();
		$data['sessions'] = $sessions;
		$data['campuses'] = $campuses;
		$data['courses'] = $courses;
		$data['semesters'] = $semesters;
		$data['semester_details'] = $semester_details;
		$data['campus_details'] = $campus_details;
		$data['full_retult'] = $full_retult;

		$data['download'] = '';
        if($request->paper_size!=null && count($full_retult) > 0){
			$data['download'] = 'pdf';
            $htmlfile = view('admin.tr.final-back-tr-view', $data)->render();
			$pdf = app()->make('dompdf.wrapper');
			$pdf->loadHTML($htmlfile,'UTF-8')
			->setWarnings(false)
			->setPaper($request->paper_size, 'landscape');
            return $pdf->download($request->form_type.'_Report.pdf');
        }

		return view('admin.tr.final-back-tr-view', $data);
	}



}