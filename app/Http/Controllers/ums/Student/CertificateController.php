<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Migration;
use App\Models\MigrationPayment;
use App\Models\Provisional;
use App\Models\Result;
use App\Models\Enrollment;

use App\Models\Student;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Semester;
use App\Http\Traits\PaymentTraitHDFC;
use App\Models\Icard;
use Validator;
use Auth;
use DB;

use App\Models\StudentSubject;
use App\Models\ExamFee;
use App\Models\Grade;


use App\Models\ExamSchedule;

class CertificateController extends Controller
{
	use PaymentTraitHDFC;
   public function migrationShow(Request $request)
   {
		if(!$request->roll_no){
			return back()->with('error','Please Provide Roll Number');
		}
		$roll_number = base64_decode($request->roll_no);
		$data = Migration::where('roll_number',$roll_number)->first();
		if(!$data){
			return redirect('/cirtificate-form?roll_number='.base64_encode($roll_number));
		}
		$detail = Result::where('roll_no',$roll_number)
		->where('semester_number',1)
		->first();
		$payment = MigrationPayment::where('migration_id',$data->id)
			->where('txn_status','SUCCESS')
			->first();
		return view('student.certificate.migration-certificate',[
			'item'=>$data,
			'delta'=>$detail,
			'payment'=>$payment,
		]);
   }
    public function provisionalShow(Request $request)
   	{
      if($request->roll_no){
         $roll_number = base64_decode($request->roll_no);
         $data = Provisional::where('roll_number',$roll_number)->first();
         if(!$data){
         	return redirect('/certificate-provisonal-form/'.base64_encode($roll_number));
         }
       }else{
         $roll_number = Auth::guard('student')->user()->roll_number;
       }
   	  $detail=Provisional::where('roll_number', $roll_number)->first();
   	  $enrollment=Enrollment::where('roll_number',$roll_number)->first();
   	  if(!$detail || !$enrollment){
	   	  $result=null;
	   	  $ExamSchedule=null;
   	  }else{
	   	  $result=Result::where('roll_no',$detail->roll_number)->first();
	   	  $ExamSchedule=ExamSchedule::where('courses_id',$enrollment->course_id)->latest()->first();
   	  }
//   	  dd($ExamSchedule);

   	  return view('student.certificate.provisional-certificate',['viu'=>$detail,'result'=>$result,'ExamSchedule'=>$ExamSchedule]);
   	  
   }

  public function marksheetShow(Request $request)
  {
		if(Auth::guard('student')->check()){
			$roll_number = Auth::guard('student')->user()->roll_number;
		}else{
			$roll_number = base64_decode($request->roll_number);
		}
	//	dd($roll_number);
		$course_id = '';
		$semester_id = $request->id;
		$semester_details = Semester::find($semester_id);
		$query = StudentSubject::select('enrollment_number', 'roll_number', 'session', 'program_id', 'course_id', 'semester_id', 'sub_code', 'sub_name')
								->where('roll_number',$roll_number)
								->where('semester_id',$semester_id)
								->distinct();
	//	dd($query->get());

		
		if($semester_details && $semester_details->id==49){
			return redirect('/admin/mbbs-result?semester_id='.$semester_id.'&roll_number='.base64_encode($roll_number));
		} 

		$studentSubjects = $query->get();
		$studentSubjects->each(function ($item, $key) use ($course_id) {
			$result = Result::select('results.*',DB::raw('sum(internal_marks + external_marks) as total_mark'))
								->where('roll_no',$item->roll_number)
								->where('semester',$item->semester_id)
								->where('subject_code',$item->sub_code)
								->first();
//dd($item->sub_code);
			$item['approval_status'] = $result->status;
			$course_id = $item->course_id;
			$subject = Subject::select('subjects.*',DB::raw('sum(internal_maximum_mark + maximum_mark) as total_mark'))
								->where('course_id',$item->course_id)
								->where('semester_id',$item->semester_id)
								->where('sub_code',$item->sub_code)
								->first();



			if($result->total_mark){
				$student_total = $result->total_mark;
				$subject_total = ((int)$subject->internal_maximum_mark + (int)$subject->maximum_mark);
				$student_total_percent = ($student_total*100)/$subject_total;
			}else{
				$student_total = 0;
				$student_total_percent = 0;
			}
			
			$grade = Grade::where('min','<=',$student_total_percent)->where('max','>',$student_total_percent)->first();

			$grade_value = 0;
			$grade_point = 0;
			$grade_letter = 'F';
			$grade_level = 'F';
			if($grade){
				$grade_point = $grade->grade_point;
				$grade_letter = $grade->grade_letter;
				$grade_level = $grade->grade_level;
				$grade_value = ($grade->grade_point*$subject->credit);
			}
			
			$item['subject_total_mark'] = $subject->total_mark;
			$item['result_total_mark'] = $result->total_mark;
			$item['result'] = $result;
			$item['subject'] = $subject;
			$item['grade_value'] = $grade_value;
			$item['grade_letter'] = $grade_letter;
			$item['grade_level'] = $grade_level;
			$item['grade_point'] = $grade_point;
			$item['credit'] = $subject->credit;
			$item['subject_type'] = $subject->subject_type;
			$item['type'] = $subject->type;
			$item['internal_maximum_mark'] = $subject->internal_maximum_mark;
			$item['maximum_mark'] = $subject->maximum_mark;
			$item['minimum_mark'] = $subject->minimum_mark;
		});
		
		$data['student'] = Student::where('roll_number',$roll_number)->first();
		$data['enrollment'] = Enrollment::where('roll_number',$roll_number)->first();
		$data['studentSubjects'] = $studentSubjects;
		$data['semester_id'] = $semester_id;
		$data['roll_number'] = $roll_number;
		$data['session'] = ($query->first())?$query->first()->session:'2021-2022';
		$grade_value = $studentSubjects->sum('grade_value');
		if($grade_value==0){
			$SGPA = 0;
			$CGPA = 0;
		}else{
			$SGPA = ( $grade_value / $studentSubjects->sum('credit') );
			$CGPA = ( $SGPA * $studentSubjects->sum('credit') /$studentSubjects->sum('credit'));
		}

		$getResultDetails = Result::where('roll_no',$roll_number)->where('semester',$semester_id);
		$getResultDetails_single = Result::where('roll_no',$roll_number)->where('semester',$semester_id)->first();
		$data['getResultDetails'] = $getResultDetails_single;
		$failed = $studentSubjects->where('grade_letter','F')->pluck('grade_letter')->toArray();

		$data['SGPA'] = $SGPA;
		$data['CGPA'] = $CGPA;
		$data['qp'] = $studentSubjects->sum('grade_value');
		$getResult = $getResultDetails_single->getResult($CGPA);
		$data['result'] = $getResult;
		$data['result_final_text'] = $getResultDetails_single->getResultFinal($getResult,$getResultDetails->pluck('external_marks')->toArray(),count($failed),$getResultDetails->count());
		$data['equivalent_percentage'] = ($SGPA*10);

		$result_approval_status = $studentSubjects->where('approval_status', 1)->count();
		$result_approvaed_status = $studentSubjects->where('approval_status', 2)->count();
		$subject_approval_status = $studentSubjects->count();
		$data['allow_for_approval'] = false;
		$data['approved'] = false;
		if($result_approval_status == $subject_approval_status){
			$data['allow_for_approval'] = true;
		}
		if($result_approvaed_status == $subject_approval_status){
//		dd($result_approval_status,$result_approvaed_status,$subject_approval_status);
			$data['approved'] = true;
		}
		$data['photo_data'] = ExamFee::where('roll_no',$roll_number)->first();
		return view('student.result.semester-result',$data);

	}

	public function cirtificateForm(Request $request){
		if($request->roll_number){
         $roll_number = base64_decode($request->roll_number);
       }
      $data['courses'] = Course::all();
		$data['student'] = Student::withTrashed()->where('roll_number',$roll_number)->first(); 
		$data['examData'] = ExamFee::withTrashed()->where('roll_no',$roll_number)->first(); 
       return view ('student.certificate.cirtificate-form',$data);

     }
	public function provisionalForm(Request $request){
		if($request->roll_no){
         $roll_number = base64_decode($request->roll_no);
       }
      $data['courses'] = Course::all();
		$data['student'] = Student::withTrashed()->where('roll_number',$roll_number)->first(); 
		$data['examData'] = ExamFee::withTrashed()->where('roll_no',$roll_number)->first(); 
       return view ('student.certificate.certificate-provisonal-form',$data);

     }

     public function cirtificateFormSave(Request $request){
     	$request->validate([
            // 'nodues_certificate' => 'required',
            'no_doues' => 'required',
            'course' => 'required',
            'terms_and_condition' => 'required',
        ]);
		if($request->roll_number){
         $studentData = Student::withTrashed()->where('roll_number',$request->roll_number)->first();
         // dd($studentData);
       }
       $migrationData=array(

					'enrollment_number'=>$studentData->enrollment_no,
					'roll_number'=>$studentData->roll_number,
					'student_name'=>$studentData->first_Name,
					'father_name'=>$studentData->father_first_name,
					'mother_name'=>$studentData->mother_first_name,
					'course'=>$request->course,
					'no_doues'=>$request->no_doues,
				);
       $migration = new Migration;
				$migration->fill($migrationData);
				$migration->save();
       if($request->nodues_certificate){
					$migration->addMediaFromRequest('nodues_certificate')->toMediaCollection('nodues_certificate');
				}
      return redirect('/migration-certificate/'.base64_encode($studentData->roll_number));

     }

	 public function cirtificateProvisinolFormSave(Request $request){
		$request->validate([
		   // 'nodues_certificate' => 'required',
		   'no_doues' => 'required',
		   'course' => 'required',
		   'terms_and_condition' => 'required',
	   ]);
	   if($request->roll_number){
		$studentData = Student::withTrashed()->where('roll_number',$request->roll_number)->first();
		// dd($studentData);
	  }
	  $provisionalData=array(

				   'enrollment_number'=>$studentData->enrollment_no,
				   'roll_number'=>$studentData->roll_number,
				   'student_name'=>$studentData->first_Name,
				   'father_name'=>$studentData->father_first_name,
				   'mother_name'=>$studentData->mother_first_name,
				   'course'=>$request->course,
				   'no_doues'=>$request->no_doues,
			   );
	  $provisional = new Provisional;
			   $provisional->fill($provisionalData);
			   $provisional->save();
	  if($request->nodues_certificate){
				   $migration->addMediaFromRequest('nodues_certificate')->toMediaCollection('nodues_certificate');
			   }
	 return redirect('/provisional-certificate/'.base64_encode($studentData->roll_number));

	}
}
