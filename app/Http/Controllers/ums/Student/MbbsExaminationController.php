<?php

namespace App\Http\Controllers\Student;

use Auth;
use Validator;
use App\Scrutiny;
use App\Challenge;
use Carbon\Carbon;
use App\Models\Fee;
use App\Models\Icard;
use App\Models\Course;
use App\Models\Result;
use App\Models\Stream;
use App\Models\ExamFee;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Campuse;
use App\Models\Semester;
use App\Models\CourseFee;
use App\Models\Enrollment;
use App\Models\Application;
use App\Models\ExamSchedule;
use App\Models\MbbsExamForm;
use App\Models\ScribeDetail;
use Illuminate\Http\Request;
use App\Models\AcademicSession;
use App\Models\StudentSemesterFee;
use App\Http\Controllers\Controller;
use App\Models\StudentAllFromOldAgency;
use App\Models\AdmitCard;
use Session;
use DB;

class MbbsExaminationController extends Controller
{

	public function mbbsform_login(){
		return view('student.exam.mbbs-exam.form');
	}	
	
	public function formLogin(Request $request){
		if(allowRegularStudentsMbbs($request->roll_no,$request->form_type)==false){
			return back()->with('error','You are not allowed');
		}
		if($request->roll_no){
	        $this->validate($request, [
				'roll_no'=>'required',
				'form_type'=>'required',
				'dob'=>'required',
				// 'g-recaptcha-response' => 'required|captcha',
			],
			[
				// 'g-recaptcha-response.required' => 'Google Captcha field is required.',
				'roll_no.required' => 'Roll Number field is required.',
				'dob.required' => 'DOB field is required.',
			]);
				// dd($request->all());
			if($request->dob== ''){
				return back()->with('error','DOB not found');
			}
			$check_rollno = Student::where('roll_number',$request->roll_no)->first();
			if(!$check_rollno){
				return back()->with('error','Roll Number not found');
			}
			$student_old_data = StudentAllFromOldAgency::where('roll_no',$request->roll_no)->first();
			if($check_rollno && $check_rollno->date_of_birth=='1970-01-01'){
				$check_rollno->date_of_birth = date('Y-m-d',strtotime($check_rollno->date_of_birth));
				$check_rollno->save();
			}
			if(date('Y-m-d',strtotime($check_rollno->date_of_birth)) != date('Y-m-d', strtotime($request->dob))){
				return back()->with('error','Invalid date of birth');
			}
			
			if($request->form_type=='regular'){
				$rollno = ExamFee::where(['roll_no'=>$request->roll_no, 'form_type' => 'regular'])
								->where('academic_session','2022-2023')
								->orderBy('id','DESC')
								->first();
				$allowed_rollnumbers = StudentAllFromOldAgency::where('regular_permission','Allowed')->pluck('roll_no')->toArray();
				if(in_array($request->roll_no,$allowed_rollnumbers)==false){
					return back()->with('error',$student_old_data->status_description);
				}
				if($rollno){
					if($rollno->form_type=='regular'){	
						if (($rollno->exam_form==1)&&($rollno->receipt_number!=null) &&($rollno->fee_status==1)){
							return redirect('mbbs-exam-form/view/'.$rollno->id)->with('message','Your Application is Approved');
						}elseif(($rollno->exam_form==1)&&($rollno->receipt_number!=null) &&($rollno->fee_status==0)){
							return redirect('mbbs-exam-form/view/'.$rollno->id)->with('message','Your Data Already Exist Please Wait For Approval');
						}elseif(($rollno->exam_form==1)&&($rollno->receipt_number==null) &&($rollno->fee_status==0)){
							return redirect('mbbs-exam-form/payment/'.$rollno->id.'?course_id='.$rollno->course_id.'&exam_form='.$rollno->form_type.'&subjects='.$rollno->subject);
						}
					}else{
					return redirect('mbbs-exam-form/'.$request->roll_no.'?exam_form='.$request->form_type);
					}
				}else{
					return redirect('mbbs-exam-form/'.$request->roll_no.'?exam_form='.$request->form_type);
				}
			}elseif($request->form_type=='compartment'){
				$rollno = ExamFee::where(['roll_no'=>$request->roll_no,'form_type' => 'compartment'])
								->orderBy('id','DESC')
								->first();
			$allowed_rollnumbers = StudentAllFromOldAgency::where('supplementary_permission','Allowed')->pluck('roll_no')->toArray();
				if(in_array($request->roll_no,$allowed_rollnumbers)==false){
					return back()->with('error',$check_rollno->status_description);
				}
				if($rollno){
					if($rollno->form_type=='compartment'){	
						if (($rollno->exam_form==1)&&($rollno->receipt_number!=null) &&($rollno->fee_status==1)){
							return redirect('mbbs-exam-form/view/'.$rollno->id)->with('message','Your Application is Approved');
						}elseif(($rollno->exam_form==1)&&($rollno->receipt_number!=null) &&($rollno->fee_status==0)){
							return redirect('mbbs-exam-form/view/'.$rollno->id)->with('message','Your Data Already Exist Please Wait For Approval');
						}elseif(($rollno->exam_form==1)&&($rollno->receipt_number==null) &&($rollno->fee_status==0)){
							return redirect('mbbs-exam-form/payment/'.$rollno->id.'?course_id='.$rollno->course_id.'&exam_form='.$rollno->form_type.'&subjects='.$rollno->subject);
						}
					}else{
					return redirect('mbbs-exam-form/'.$request->roll_no.'?exam_form='.$request->form_type);
					}
				}else{
					return redirect('mbbs-exam-form/'.$request->roll_no.'?exam_form='.$request->form_type);
				}
			}
			elseif($request->form_type=='challenge'){

			$allowed_rollnumbers = StudentAllFromOldAgency::where('challenge_permission','Allowed')->pluck('roll_no')->toArray();
			if(in_array($request->roll_no,$allowed_rollnumbers)==false){
				return back()->with('error',$check_rollno->status_description);
			}
			$scrutiny_details = Scrutiny::where(['roll_no'=>$request->roll_no])
				->where('form_type',1)
				->first();
				if($scrutiny_details){
					return redirect('challenge-form-view/'.$scrutiny_details->id);
					// return redirect('challenge-form/payment/options/'.$request->roll_no.'?course_id='.$rollno->course_id.'&exam_form='.$rollno->form_type);
				}else{
					// return back()->with('error','You are not allowed for challenge form');
					return redirect('challenge-form/'.$request->roll_no.'?exam_form='.$request->form_type);
				}
			}elseif($request->form_type=='scrutiny'){
				return redirect('scrutiny-form/'.$request->roll_no);
			}
				
		// $allowed_rollnumbers = StudentAllFromOldAgency::where('regular_permission','Allowed')->pluck('roll_no')->toArray();
		// if(in_array($request->roll_no,$allowed_rollnumbers)==false){
		// 	return back()->with('error',$check_rollno->status_description);
		// }
		// if($rollno){
		// 	if (($rollno->exam_form==1)&&($rollno->receipt_number!=null) &&($rollno->fee_status==1)){
		// 			return redirect('mbbs-exam-form/view/'.$request->roll_no)->with('message','Your Application is Approved');
		// 	}elseif(($rollno->exam_form==1)&&($rollno->receipt_number!=null) &&($rollno->fee_status==0)){
		// 				return redirect('mbbs-exam-form/view/'.$request->roll_no)->with('message','Your Data Already Exist Please Wait For Approval');			
		// 	}elseif(($rollno->exam_form==1)&&($rollno->receipt_number==null) &&($rollno->fee_status==0)){
		// 				return redirect('mbbs-exam-form/payment/'.$request->roll_no.'?course_id='.$rollno->course_id.'&exam_form='.$request->form_type);
		// 	}
		// }else{
		// 	return redirect('mbbs-exam-form/'.$request->roll_no.'?exam_form='.$request->form_type);
		// }
		}
	}
	//--Mbbs Form LOgin --//
	
	
	
	//mbbs Form filling //
	
	
	public function mbbsForm($slug,Request $request){
		$roll_no=$slug;
		$batch = batchFunctionReturn($roll_no);
		$old_data = Student::where(['roll_number'=>$slug])->first();
		if(!$old_data->enrollments){
			return back()->with('error','Enrollment not found.');
		}
		$mbbs_semesters = [];
		if($old_data->enrollments->course_id == 49){
			$mbbs_semesters = [3,4];
		}
		else if($old_data->enrollments->course_id == 64){
			$mbbs_semesters = [1,2,3,4];
		}
		else if($old_data->enrollments->course_id == 95){
			$mbbs_semesters = [4];
		}
		else if($old_data->enrollments->course_id == 96){
			$mbbs_semesters = [3];
		}
		$sessions = AcademicSession::get();
		$semesters = Semester::where('course_id',$old_data->enrollments->course_id)->whereIn('semester_number',$mbbs_semesters)->orderBy('semester_number','ASC')->get();
		if($old_data->enrollments->course_id == 49){
			$subjects = Subject::where('semester_id',$request->semester_id)
			->where('batch',$batch)
			->get();
		}else{
			$subjects = Subject::where('semester_id',$request->semester_id)->get();
		}
		$exam_fee = ExamFee::where('semester',$request->semester_id)
		->where('roll_no',$roll_no)
		->where('academic_session','2023-2024')
		->where('form_type',$request->exam_form)
		->first();
		if($exam_fee){
			return redirect('mbbs-exam-form/view/'.$exam_fee->id);
		}
		$campus = Campuse::get();
		// dd($old_data->course_id);
		return view('student.exam.mbbs-exam.mbbs-examination-form',compact('sessions','roll_no','campus','old_data','semesters','subjects','exam_fee'));
	}

	public function submitmbbsform(Request $request, $slug)
	{
		// dd($request->all());
		$validator = Validator::make($request->all(), [
            'rollNo' => 'required',
			'name'=>'required',
			'father'=>'required',
			'mother'=>'required',
			'date_of_birth'=>'required',
			'mobile'=>'required',
			'email'=>'required',
			//'alternate_email_id'=>'required',
			'aadhar'=>'required',
			'pin_code'=>'required',
			'category'=>'required',
			'gender'=>'required',
			'vaccination'=>'required',
			//'disabilty_category'=>'required',
			'address'=>'required',
			'course'=>'required',
			'batch'=>'required',
			'scribe'=>'required',
			'semester'=>'required',
			'paper'=>'required',
			'signature'=>'required',
			'photo'=>'required',
			]);
		if ($validator->fails()) {    
            return back()->withErrors($validator)->withInput();
        }
		//dd($request->all());
		foreach($request->paper as $key=>$sub_code)
		{	
			$sub[]=$sub_code;
		}
		$subj=implode(' ',$sub);
		// dd($request->all());
		$data['subject']=implode(' ',$sub);
		$exam_form = ExamFee::where(['roll_no'=>$request->rollNo,'academic_session'=>$request->batch,'form_type'=>$request->exam_form,'semester'=>$request->semester_id])->first();
		if($exam_form){
			return redirect('mbbs-exam-form/view/'.$exam_form->id.'?course_id='.$request->course.'&exam_form='.$request->exam_form.'&rollNo='.$exam_form->id);
			// return back()->with('error','Your Form is already exists');
		}
		$student = Student::where('roll_number',$slug)->first();

		$exam_form=new ExamFee;
		$exam_form->roll_no = $request->rollNo;
		$exam_form->enrollment_no = $student->enrollment_no;
		$exam_form->academic_session=$request->batch;
		$exam_form->semester=$request->semester;
		$exam_form->course_id=$request->course;
		$exam_form->subject=implode(' ',$sub);
		$exam_form->form_type=$request->exam_form;
		$exam_form->scribe=$request->scribe;
		$exam_form->vaccinated = $request->vaccination;
		$exam_form->exam_form=1;
		$exam_form->save();


		$slug=$request->rollNo;

		$student->mobile = $request->mobile;
		$student->email = $request->email;
		$student->aadhar = $request->aadhar;
		$student->pin_code = $request->pin_code;
		$student->category = $request->category;
		$student->gender= $request->gender;
		$student->address= $request->address;
		$student->save();
		//	dd($request->photo);
		if($request->photo){
			$student->addMediaFromRequest('photo')->toMediaCollection('photo');
		}
		if($request->signature){
			$student->addMediaFromRequest('signature')->toMediaCollection('signature');
		}
	  


	  $exam_fee = ExamFee::where(['roll_no' => $slug, 'form_type' => $request->exam_form])->first();
		if($request->exam_form=='compartment'){
		return redirect('mbbs-exam-form/view/'.$exam_form->id.'?course_id='.$request->course.'&exam_form='.$request->exam_form.'&rollNo='.$exam_form->id.'&subjects='.$subj);
		}
		else{
			return redirect('mbbs-exam-form/view/'.$exam_form->id.'?course_id='.$request->course.'&exam_form='.$request->exam_form.'&rollNo='.$exam_form->id);
		
		}
		
		
	}


	//-- Mbbs Form Filling --//

	public function mbbsfee( Request $request, $slug){
		$exam_fee=null;
		$exam_form_details = ExamFee::where(['id'=>$slug])->first();
		if($exam_form_details->form_type=='compartment'){
			$batch = batchFunctionReturn($exam_form_details->roll_no);
			$subject= explode(' ',$exam_form_details->subject);
			$exam_fee = Subject::whereIn('sub_code',$subject)
			->where('batch',$batch)
			->get('back_fees')
			->sum('back_fees');
			// fees for bsc mursing only
			if($exam_form_details->course_id==64 || $exam_form_details->course_id==95 || $exam_form_details->course_id==96){
				$exam_fee = 6000;
			}
		}else{
			$fee=CourseFee::where('course_id',$exam_form_details->course_id)->where('fees_details','Exam Fee')->first();
			if($fee){
				$exam_fee=$fee->non_disabled_fees;
			}else{
				$exam_fee = '';
			}
		}

		return view('student.exam.mbbs-exam.mbbs-exam-fee-details',['slug'=>$exam_form_details->id,'exam_fee'=>$exam_fee,'exam_form_details'=>$exam_form_details]);
	}
	public function submitmbbsfee($slug ,Request $request){
		$validator = Validator::make($request->all(), [
            'bank_name' => 'required',
			'bank_IFSC_code'=>'required',
			'challan_number'=>'required',
			'fee_amount'=>'required',
			'challan'=>'required',
			'challan_reciept_date'=>'required',
			]);
			if ($validator->fails()) {    
            return back()->withErrors($validator)->withInput();
        }
		
		$exam_form = ExamFee::where(['id'=>$slug])->orderBy('id','DESC')->first();
		// dd($exam_form);
		$exam_form->bank_name = $request->bank_name;
		$exam_form->bank_IFSC_code = $request->bank_IFSC_code;
		$exam_form->receipt_number = $request->challan_number;
		$exam_form->receipt_date = $request->challan_reciept_date;
		$exam_form->fee_amount = $request->fee_amount;

		if($request->challan){
			$exam_form->addMediaFromRequest('challan')->toMediaCollection('challan');
		}
		$exam_form->save();
		
		return redirect('mbbs-exam-form/view/'.$slug);
		
	}
	//--MBBS Form Fee --//
	
	
	//MBBS Form View //
	
	public function mbbsformview($slug,Request $request)
	{
		$form_data = ExamFee::where('id',$slug)->orderBy('id','DESC')->first();
		// $subjects = Subject::where('semester_id',$request->semester_id)->get();
		$student = Student::where(['roll_number'=>$form_data->roll_no])->first();
		return view('student.exam.mbbs-exam.mbbs-examination-form-view',compact('form_data','student'));
	}
	public function mbbsformviewpost( $slug,Request $request)
	{
		//dd($request->all(),$slug);
		if($request->is_agree){
		$validator = Validator::make($request->all(), [
            'agree' => 'required',
			],['agree.required'=>'Please Check Declaration Box']
			);
			if ($validator->fails()) {    
            return back()->withErrors($validator);
        }
		}
		$exam_form = ExamFee::where('id',$slug)->orderBy('id','DESC')->first();
		$student = Student::where('roll_number',$exam_form->roll_no)->orderBy('id','DESC')->first();
		//dd($exam_form);
		$exam_form->is_agree = $request->agree;
		if($request->photo){
			$student->addMediaFromRequest('photo')->toMediaCollection('photo');
		}
		if($request->signature){
			$student->addMediaFromRequest('signature')->toMediaCollection('signature');
		}
		$student->save();
		
		$exam_form->save();
		//dd($exam_form,$request->agree);
		if($exam_form->bank_name==null){
		return redirect('mbbs-exam-form/payment/'.$exam_form->id.'?exam_form='.$request->exam_form);}
		return redirect('mbbs-exam-form/view/'.$exam_form->id);
	}
	
	//--Mbbs Form View --//
	//--Delete Exam Form--//
	public function delete_mbbs_exam_form_data($slug,Request $request){
		$exam_fee = ExamFee::where('id',$slug)->first();
		if($exam_fee){
			$MbbsExamForm = MbbsExamForm::where('exam_fee_id',$slug)->get();
			if($MbbsExamForm->count() > 0){
				MbbsExamForm::where('exam_fee_id',$slug)->forceDelete();
				$admitcard=AdmitCard::where('exam_fees_id',$slug)->first();
				if($admitcard){
				//AdmitCard::where('exam_fees_id',$slug)->forceDelete();
					$exam_fee->admitcard->forceDelete();
					//$exam_fee->forceDelete();
				}
			}
			$exam_fee->forceDelete();
		}
		
		return redirect('mbbs-exam-form-login')->with('message','Your Form Reset Please login again and fill form');
	}
	
	
	
}
