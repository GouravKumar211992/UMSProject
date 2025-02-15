<?php

namespace App\Http\Controllers\ums\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;




use App\Scrutiny;
use App\Challenge;
use Carbon\Carbon;
use App\Models\ums\Fee;
use App\Models\ums\Religion;
use App\Models\ums\Course;
use App\Models\ums\Result;
use App\Models\ums\Stream;
use App\Models\ums\ExamFee;
use App\Models\ums\Student;
use App\Models\ums\Subject;
use App\Models\ums\Campuse;
use App\Models\ums\Semester;
use App\Models\ums\CourseFee;
use App\Models\ums\Enrollment;
use App\Models\ums\Application;
use App\Models\ums\ExamSchedule;
use App\Models\ums\ExamForm;
use App\Models\ums\ExamPayment;
use App\Models\ums\ScribeDetail;
use App\Models\ums\DisabilityCategory;
use App\Models\ums\BackPaper;
use Illuminate\Http\Request;
use App\Models\ums\AcademicSession;
use App\Models\ums\CastCategory;
use App\Http\Controllers\Controller;
use App\Models\StudentAllFromOldAgency;
use App\Models\ums\ChallengeAllowed;
use App\Models\ums\ApprovalSystem;
use Illuminate\Support\Facades\Artisan;
use App\Models\ExamSetting;
use Illuminate\Support\Facades\DB;


use App\Traits\PaymentTraitHDFC;
use App\Traits\PaymentTrait;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use App\Traits\ResultsTrait;

class ExaminationController extends Controller
{
    use PaymentTraitHDFC;
    use PaymentTrait;
    use ResultsTrait;
	// Examination Form //
		public function index(Request $request){
			$student = Auth::guard('student')->user();
			if($request->submit_form){
				if($request->back_papers == 'regular'){
					return redirect('student/show-exam-form/'.$student->roll_number.'?course='.$request->course_id.'&semester='.$request->semester_id.'&back_papers='.$request->back_papers.'&accademic_session='.$request->accademic_session);
				}
				return redirect('student/special-back-form/'.$student->roll_number.'?course='.$request->course_id.'&semester='.$request->semester_id.'&back_papers='.$request->back_papers.'&accademic_session='.$request->accademic_session);
			}
			if($request->back_papers=='regular'){
				$result = Result::where('roll_no',$student->roll_number)->orderBy('semester_number','DESC')->first();
				$next_semester = 1;
				if($result){
					$next_semester = ($result->semester_number+1);
				}
				// $semesters = Semester::where('course_id',$student->enrollments->course_id)
				// ->where('semester_number',$next_semester)
				// ->get();
				// if($semesters->count()==0){
				// 	return back()->with('error','You have already completed your final semester.');
				// }

				$result = Result::where('roll_no',$student->roll_number)->orderBy('semester_number','DESC')->first();
				$semesters = Semester::where('course_id',$student->enrollments->course_id)
				->where('semester_number',$next_semester)
				->get();
				$get_year_back = $this->get_year_back($result,'');
				if($get_year_back!=''){
					if($result->semester_number%2==0){
						$semester_number_yearback = $result->semester_number - 1;
					}else{
						$semester_number_yearback = $result->semester_number;
					}
					$semesters = Semester::where('course_id',$request->course_id)
					->where('semester_number',($semester_number_yearback))
					->get();
				}else{
					$next_semester = 1;
					if($result){
						$next_semester = ($result->semester_number+1);
					}
					if($student->enrollments->is_lateral ==1){
						$get_first_semester = Semester::where('course_id',$student->enrollments->course_id)
							->where('semester_number',$next_semester)
							->first();
								if($get_first_semester->semester_number == 1){
									$semesters = Semester::where('course_id',$student->enrollments->course_id)
										->where('semester_number',$next_semester+2)
										->get();
								}
						
					}else{
					$semesters = Semester::where('course_id',$student->enrollments->course_id)
					->where('semester_number',$next_semester)
					->get();
				}
					if($semesters->count()==0){
						return back()->with('error','You have already completed your final semester.');
					}
				}
				// extra setting for new courses
				if($request->course_id==110 || $request->course_id==147 || $request->course_id==148 || $request->course_id==149 || $request->course_id==151){
					$semesters = Semester::where('course_id',$request->course_id)
					->whereIn('semester_number',[1,2])
					->get();
			}
		}else{
			$semesters = Semester::where('course_id',$student->enrollments->course_id)
			->orderBy('semester_number','ASC')
			->get();
		}
		if($request->back_papers=='final_back_paper'){
			// dd('ds');
			$semesters = Semester::where('course_id',$student->enrollments->course_id)
				->orderBy('semester_number','DESC')
				->limit('1')
				->get();
		}
		$sessions = AcademicSession::orderBy('academic_session','DESC')->get();
		
		return view('ums.student.exam-form',[
      'page_title' => "Exam Form",
      'sub_title' => "Exam Form",
			'student'=>$student,
			'semesters'=>$semesters,
			'sessions'=>$sessions,
		]);

    	
    }
		
		// Fill regular exam form
		public function showRegularExamForm(Request $request){
			// dd($request->all());
			$student = Auth::guard('student')->user();
			$academic_session = AcademicSession::where('academic_session','2023-2024')->first();
			$student_photo = Student::withTrashed()->where('roll_number',$student->roll_number)->first();
			$enrollment = Enrollment::where('roll_number',$student->roll_number)->first();
			if($request->stream_id){
				$enrollment->stream_id = $request->stream_id;
				$enrollment->save();
			}
			$disabilitycategories = DisabilityCategory::all();
			$semester = Semester::where('id',$request->semester)->where('course_id',$request->course)->first();
			$subjectQuery = Subject::where('course_id',$request->course)
			->where('semester_id',$request->semester);
			if($enrollment->course_id == 37 && $enrollment->stream_id > 0){
				$subjectQuery->where('stream_id',$enrollment->stream_id);
			}

			$subjects = $subjectQuery->orderBy('position')->get();
			$streams = Stream::where('course_id',$request->course)->get();
			$examData = ExamFee::where('roll_no',$student->roll_number)
			->where('course_id',$request->course)
			->where('semester',$request->semester)
			->where('form_type',$request->back_papers)
			->where('academic_session',$request->accademic_session)
			->first();
			if($examData){
				return redirect('exam-form-view/'.$examData->id)->with('message','Form successfully submitted. please click on fill fee button for payment');
			}
			if($this->paperIsOpen($request->all(),0)==false){
				return back()->with('error',$this->paperIsOpen($request->all(),1));
			}
			$setting = getExamSettingLatest($request->back_papers,$request->semester);

			$categorys = CastCategory::get();
			$religions = Religion::get();
			return view('student.exam.exam-form-fill',compact('student','semester','streams','subjects','examData','student_photo','categorys','religions','academic_session','disabilitycategories','setting'));
		}

		// Save regular exam form
		public function saveRegularExamForm(Request $request){
			// dd($request->all());
	    $student = Auth::guard('student')->user();
			$validator = Validator::make($request->all(), [
				
				'vaccination' => 'required',
				'writer' => 'required',
				'terms_and_condition' => 'required',
				
                    //'disability_categories' => 'required',
				// 'religion' => 'required',
				// 'category' => 'required',
				// 'stream_id' => 'required',
				// 'ews_status' => 'required_if:category,General,OBC',
				
			]); 
			if ($validator->fails()) {    
				return back()->withErrors($validator);
			}
		$student = Student::where('roll_number',$request->roll_no)->first();
		if($student){
			if($request->religion){
				$student->religion = $request->religion;
			}
			if($request->category){
				$student->category = $request->category;
			}
			if($request->ews_status){
				$student->ews_status = $request->ews_status;
			}
			if($request->disabilty_category){
				$student->disabilty_category = $request->disabilty_category;
			}
			 //dd($student);
			$student->save();
			
			$enrollment = $student->enrollments;
			$enrollment->stream_id = $request->stream_id;
			$enrollment->save();
		}
		$subjects = implode(' ',$request->sub_code);
		$saveData=array(
			'enrollment_no'=>$student->enrollment_no,
			'roll_no'=>$student->roll_number,
			'academic_session'=> $request->accademic_session,
			'course_id'=>$request->course_id,
			'semester'=>$request->semester_id,
			'subject'=>$subjects,
			'form_type'=>$request->back_papers,
			'exam_form'=>1,
			'scribe'=>$request->writer,
			'vaccinated'=>$request->vaccination,
			'disabilty_category'=>$request->disabilty_category,
			'term_condition'=>$request->terms_and_condition,
			'current_exam_session'=>$request->current_exam_session,
			
		);
		$examFee = new ExamFee;
		$examFee->fill($saveData);
		$examFee->save();
		return back()->with('success','Form Successfully Submitted');
    }

    public function studentPhotoSignateure(Request $request){
		// dd($request->all());
		$student_data = Student::where('roll_number',$request->student_roll_no)->first();
		$student = Student::find($student_data->id);
		$student->save();
			if($student_data->id){
				if($request->upload_photo){
				$student->addMediaFromRequest('upload_photo')->toMediaCollection('photo');
			}
			if($request->upload_signature){
				$student->addMediaFromRequest('upload_signature')->toMediaCollection('signature');
			}
			}
			return back()->with('message','Uploaded Successfully.');
		}

    public function examinationForm(Request $request)
    {
        if(!$request->backlog){
			$validator = Validator::make($request->all(), [
				'alternate_mobile_number' => 'required|numeric|digits:10',
				'vaccinated' => 'required',
				//'disability_certificate' => 'required',
				'terms_and_condition' => 'required',
				'writer'=>'required',
				'scribe_name'=>'required_if:writer,yes',
				'scribe_qualificaiton'=>'required_if:writer,yes',
				'scribe_photo'=>'required_if:writer,yes',
				'scribe_qualificaiton_file'=>'required_if:writer,yes',
			]); 
			if ($validator->fails()) {    
				return back()->withErrors($validator);
			}
		}
		
        if($request->backlog){
			$data=(array)$request;
			$data['subject']= $request->subjects;
			$data['id']= $request->student_id;
		}else{
			$id=Enrollment::where('enrollment_no',Auth::guard('student')->user()->enrollment_no)->first();
			$data=$request->all();
			$data['id']=$id->id;
		}
	   	$exam_form=$this->create($data);
		
	   	if($request->scribe_name!=null){
			$scribe=new ScribeDetail;
			$scribe['name']=$request->scribe_name;
			$scribe['qualification']=$request->scribe_qualificaiton;
			$scribe['enrollment_no']=$request->enrollment_number;
			$scribe->save();
	   	}
		if($request->backlog){
			return true;
		}
	   	$examfee=ExamFee::where('enrollment_no',$id->enrollment_no)->orderBy('id','DESC')->first();
		$subjects = Subject::whereIn('sub_code',explode(' ',$examfee->subject))->get();
		//dd($subjects);
		
		return redirect()->route('student-dashboard',['id'=>$id,'subject'=>$subjects])->with('message','Details Successfully Saved');
    }
	
	
	public function create( array $data){
		if(isset($data['backlog'])){
			$exam_form= ExamFee::create([
				'enrollment_no' => $data['enrollment_number'],
				'roll_no' => $data['roll_number'],
				'student_id' => $data['id'],
				'course_id' => $data['course'],
				'academic_session' => $data['batch'],
				'semester' => $data['semester'],
				'subject' => $data['subject'],
				'form_type' => $data['form_type'],
				'exam_form'=>1,
				'is_agree'=>1,    
				'fee_status'=>1,    
			  ]);
		}else{
			$exam_form= ExamFee::create([
				'enrollment_no' => $data['enrollment_number'],
				'roll_no' => $data['roll_number'],
				'student_id' => $data['id'],
				'course_id' => $data['course'],
				'academic_session' => $data['batch'],
				'semester' => $data['semester'],
				'subject' => $data['subject'],
				'form_type' => $data['form_type'],
				'scribe'=>$data['writer'],
				'vaccinated'=>$data['vaccinated'],
				'exam_form'=>1,
				'is_agree'=>1,    
			  ]);
		}
	  if($exam_form == true){
		  return view('student.exam.exam-fee-details')->with('message','exam form filled successfully please pay exam fee');
	  }
	  else
		  return view('student.exam.exam-fee-details')->with('message','your data already exist please pay exam fee');
		
	}
	
	public function update( array $data){
		return ExamFee::where('enrollment_no',$data['enrollment_number'])->orderBy('id','DESC')->update([
        'bank_name' => $data['bank_name'],
        'bank_IFSC_code' => $data['bank_IFSC_code'],
        'receipt_number' => $data['challan_number'],
        'receipt_date' => $data['challan_reciept_date'],
        'fee_amount'=>$data['fee_amount'],
		
      ]);
	  	
	}
	public function exam_fee(Request $request)
	{
		$slug = $request->exam_fee_id;
		$examfee = ExamFee::find($request->exam_fee_id);
		if(!$examfee){
			return back('Invalie Exam Fees ID');
		}
        $paymentDetails = ExamPayment::where('exam_fee_id',$slug)->first();
		$amount = 0;
		if($paymentDetails){
			$amount = $paymentDetails->paid_amount;
		}
		$student = Student::where('roll_number',$examfee->roll_no)->first();
		$subject = Subject::whereIn('sub_code',explode(' ',$examfee->subject))->get();
		return view('ums.exam.exam-fee-details',compact('student','subject','examfee','slug','amount'));
	}
	public function StudentExamformview( $slug,Request $request)
	{
		// dd($slug);
		$student = Auth::guard('student')->user();
		$enrollment = $student->enrollments;
		$form_data = ExamFee::find($slug);
		$subject= explode(" ",$form_data->subject);
		$subjectQuery = Subject::whereIn('sub_code',$subject)
		->where('course_id',$form_data->course_id)
		->where('semester_id',$form_data->semester);
		if($enrollment->course_id == 37 && $enrollment->stream_id > 0){
			$subjectQuery->where('stream_id',$enrollment->stream_id);
		}
		$subjectList = $subjectQuery->get();
		$exam_fee_amount = 500;
		$payment_details = ExamPayment::where('exam_fee_id',$slug)->first();
		if($payment_details){
			$order_id = $payment_details->order_id;
			// $this->updatePaymentBackPapers($slug,$order_id);
		}else{
			$order_id = 'EXAM'.rand(11111111,999999999);
			// $order_id = $this->createPaymentOrderOnly($slug,$exam_fee_amount);
			$this->insertPayment($slug,$order_id,$exam_fee_amount);
		}
		return view('ums.exam.examination-form-view',
			[
				'student'=>$student,
				
				'form_data'=>$form_data,
				'subjectList'=>$subjectList,
				'enrollment'=>$enrollment,
			]);
	}
	public function StudentExamformviewpost( $slug,Request $request)
	{
		//dd($request->all());
		$validator = Validator::make($request->all(), [
            'agree' => 'required',
			],['agree.required'=>'Please Check Declaration Box']
			);
			if ($validator->fails()) {    
            return back()->withErrors($validator);
        }
		$exam_form = ExamFee::where('roll_no',$request->rollno)->orderBy('id','DESC')->first();
		$exam_form->is_agree = $request->agree;
		$exam_form->save();
		return redirect('exam-form/view/'.$exam_form->roll_no.'?exam_form='.$request->exam_form);
	}
	public function exam_fee_submit(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'bank_name'=>'required',
			'bank_IFSC_code'=>'required',
			'challan_number'=>'required',
			'challan'=>'required',
			'challan_reciept_date'=>'required|date',
			]);
		if ($validator->fails()) {
            return back()->withErrors($validator);
        }
		$examFees = ExamFee::find($request->exam_fee_id);
		if(!$examFees){
			return back()->with('error','Invalid Exam ID');
		}
		$examFees->bank_name = $request->bank_name;
		$examFees->bank_IFSC_code = $request->bank_IFSC_code;
		$examFees->receipt_number = $request->challan_number;
		$examFees->receipt_date = $request->challan_reciept_date;
		$examFees->fee_amount = $request->fee_amount;
		$examFees->is_agree = 1;
		$examFees->save();
		if($request->challan){
			$examFees->addMediaFromRequest('challan')->toMediaCollection('challan');
		}
		Artisan::call('command:StudentPromotion',['roll_no'=>$examFees->roll_no]);

		$payment = ExamPayment::where('exam_fee_id',$request->exam_fee_id)->first();
		if($payment){
			$payment->bank_name = $request->bank_name;
			$payment->bank_ifsc_code = $request->bank_IFSC_code;
			$payment->challan = $request->challan_number;
			$payment->txn_date = $request->challan_reciept_date;
			$payment->paid_amount = $request->fee_amount;
			$payment->payment_mode = 'Offline';
			$payment->txn_status = 'SUCCESS';
			$payment->txn_date = date('Y-m-d');
			$payment->updated_at = date('Y-m-d H:s:i');
			$payment->save();
		}
		return redirect('/exam/pay-success?id='.$request->exam_fee_id)->with('message','Details Successfully Saved');
	}
	
	public function update_student_subjects(Request $request)
	{
		$examFees = ExamFee::find($request->id);
		if(!$examFees){
			return back()->with('error','Invalid Exam ID');
		}
		Artisan::call('command:StudentPromotion',['roll_no'=>$examFees->roll_no]);
		return back()->with('success','Updated successfully.');
	}
	
	
	//--Examination Form --//
	
	
	// Time Table //
	
	 public function timetable(Request $request)
    {
		$id=Enrollment::where('enrollment_no',Auth::guard('student')->user()->enrollment_no)->first();
    	//dd($id->course_id);
		 $data['courses']=Course::all();
    	$data['semesters']=Semester::orderBy('id','asc')->get();
		//$data=ExamSchedule::where('courses_id',$id->course_id)->get();
      $data['course_id']=null;
    	$data['semester_id']=null;
      $data['exams']=null;
      if($request->course!=null)
      {
        $data['course_id']=$request->course;
          $data['semester_id']=$request->semester;
    	$data['exams']=ExamSchedule::where(['courses_id'=>$request->course,'semester_id'=>$request->semester])
      ->get();
      }
     return view('admin.exam.examtime-table',$data);
    }
	//--Time Table --//
	
	
	
	// Mbbs Form Login ///
	public function formlogin(){
		if(Auth::guard('student')->check()){
			return redirect('student/login?exam_portal=1');
		}
		return view('student.exam.result-portal');
	}
	
	
	public function formLogin_post(Request $request)
	{   
		if($request->roll_no){
	        $this->validate($request, [
				'roll_no'=>'required',
				'form_type'=>'required',
				//'g-recaptcha-response' => 'required|captcha',
				'dob' => 'required',
			],
			[
				'g-recaptcha-response.required' => 'Google Captcha field is required.',
				'dob.required' => 'Date Of Birth Field is Required',
			]); 
				
			$check_rollno = StudentAllFromOldAgency::where(['roll_no'=>$request->roll_no])->first();
			
			if($check_rollno){
				
				if(date('d-m-Y',strtotime($check_rollno->date_of_birth))==date('d-m-Y',strtotime($request->dob))){
					//dd($check_rollno);
					if($check_rollno->status==0)
					{
						return back()->with('error',$check_rollno->status_description);
					}
						$rollno=ExamFee::where(['roll_no'=>$request->roll_no])
								->orderBy('id','DESC')
								->first();
								/*if($request->form_type=='compartment')
									{
										if($rollno)
										{
										if($rollno->form_type=='compartment'){
										//dd($request->form_type);	
											if ($rollno->exam_form==1)
											{
												return redirect('exam-form/view/'.$request->roll_no)->with('message','Your Already Filled Application');
											}
										}
										else{
										return redirect('exam-form/'.$request->roll_no.'?form_type='.$request->form_type);
										}
										}
										else{
										return redirect('exam-form/'.$request->roll_no.'?form_type='.$request->form_type);
										}
									}*/
				
			if($rollno){
				if ($rollno->exam_form==1){
					// dd($rollno);
					return redirect('exam-form/view/'.$request->roll_no)->with('message','Your Already Filled Application');
				}
				}else{
					return redirect('exam-form/'.$request->roll_no.'?form_type='.$request->form_type);
				}				
				}
				else{
					return back()->with('error','Incorrect Date Of Birth');
				}
			}
			else{
				return back()->with('error','Roll Number not found');
			}
		}
		
	}
	//--Exam Form LOgin --//
	
	
	
	//Exam Form filling //

	public function ExamForm($slug){
		$roll_no = $slug;
		$old_data = StudentAllFromOldAgency::where(['roll_no'=>$slug])->orderBy('session','DESC')->first();
		$campus_id = campus_name($old_data->enrollment_no);
		if($campus_id==5 || $campus_id==1){
			$examFee = ExamFee::withTrashed()->where(['roll_no'=>$roll_no])->first();
			$examForm = ExamForm::withTrashed()->where(['rollno'=>$roll_no])->first();
		}else{
			$examFee = null;
			$examForm = null;
		}
		$session = AcademicSession::orderBy('id','ASC')->get();
		$course_single = Course::find($old_data->course_id);
		$course = Course::where('campus_id',$campus_id)->whereNotIn('campus_id',[3])->get();
		$campus=Campuse::get();
		return view('student.exam.examination-form',['courses'=>$course,'sessions'=>$session,'roll_no'=>$roll_no,'campus'=>$campus,'old_data'=>$old_data,'examFee'=>$examFee,'examForm'=>$examForm]);
	}
	public function submitExamForm(Request $request, $slug)
	{
		//dd($request->form_type);
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
			'branch'=>'required',
			'batch'=>'required',
			'scribe'=>'required',
			'semester'=>'required',
			'paper'=>'required',
		//	'signature'=>'required',
		//	'photo'=>'required',
			]);
		if ($validator->fails()) {    
            return back()->withErrors($validator)->withInput();
        }
		//dd($request->vaccination);
		foreach($request->paper as $key=>$sub_code)
		{	
			$sub[]=$sub_code;
		}
		$subj=implode(' ',$sub);
		//dd($request->all());
		$data['subject']=implode(' ',$sub);

		$exam_form = ExamFee::where(['roll_no'=>$request->rollNo,'academic_session'=>$request->batch,'form_type'=>$request->form_type])->orderBy('id','DESC')->first();
		$en=Enrollment::where('roll_number',$request->rollNo)->first();
		if($en){
		$application=Application::where('id',$en->application_id)->first();
		if($application){
			$application->date_of_birth=date('Y-m-d',strtotime($request->date_of_birth));
			$application->category=$request->category;
			$application->disability_category=$request->disabilty_category;
			$application->adhar_card_number=$request->aadhar;
			$application->save();
		}
		}
		if($exam_form){
		$exam_form->roll_no=$request->rollNo;
		$exam_form->academic_session=$request->batch;
		$exam_form->semester=$request->semester;
		$exam_form->course_id=$request->course;
		$exam_form->subject=implode(' ',$sub);
		$exam_form->form_type=$request->form_type;
		$exam_form->scribe=$request->scribe;
		$exam_form->vaccinated=$request->vaccination;
		$exam_form->exam_form=1;
		if($request->photo){
		  $exam_form->addMediaFromRequest('photo')->toMediaCollection('photo');
		}
		if($request->signature){
		  $exam_form->addMediaFromRequest('signature')->toMediaCollection('signature');
		}
		$exam_form->save();
		//dd($request->paper);
		foreach($request->paper as $key=>$value){
			$student_data[$key]['exam_fee_id']=$exam_form->id;
			$student_data[$key]['rollno']=$request->rollNo;
			$student_data[$key]['name']=$request->name;
			$student_data[$key]['father_name']=$request->father;
			$student_data[$key]['mother_name']=$request->mother;
			$student_data[$key]['date_of_birth']=$request->date_of_birth;
			$student_data[$key]['mobile']=$request->mobile;
			$student_data[$key]['email']=$request->email;
			$student_data[$key]['alternate_email_id']=$request->alternate_email_id;
			$student_data[$key]['aadhar']=$request->aadhar;
			$student_data[$key]['address']=$request->address;
			$student_data[$key]['pin_code']=$request->pin_code;
			$student_data[$key]['gender']=$request->gender;
			$student_data[$key]['category']=$request->category;
			$student_data[$key]['course_id']=$request->course;
			$student_data[$key]['branch_id']=$request->branch;
			$student_data[$key]['vaccinated']=$request->vaccination;
			$student_data[$key]['batch']=$request->batch;
			$student_data[$key]['scribe']=$request->scribe;
			$student_data[$key]['disabilty_category']=$request->category_of_disability;
			$student_data[$key]['semester']=$request->semester;
			$student_data[$key]['sub_code']=$request->paper[$key];
			$student_data[$key]['form_type']=$request->form_type;
			$student_data[$key]['exam_form']=1;
			$student_data[$key]['created_at']=Carbon::now();
		}
		//dd($student_data);
		$form_data=ExamForm::where(['exam_fee_id'=>$exam_form->id]);
		$form_data->delete();
		ExamForm::insert($student_data);
		}
		else{
		$exam_form=new ExamFee;
		$exam_form->roll_no=$request->rollNo;
		$exam_form->academic_session=$request->batch;
		$exam_form->semester=$request->semester;
		$exam_form->course_id=$request->course;
		$exam_form->subject=implode(' ',$sub);
		$exam_form->form_type=$request->form_type;
		$exam_form->scribe=$request->scribe;
		$exam_form->vaccinated=$request->vaccination;
		$exam_form->exam_form=1;
	//	dd($request->photo);
		if($request->photo){
		  $exam_form->addMediaFromRequest('photo')->toMediaCollection('photo');
		}
		if($request->signature){
		  $exam_form->addMediaFromRequest('signature')->toMediaCollection('signature');
		}
		
		//dd($exam_form->enrollment_no);
		$exam_form->save();
		
		foreach($request->paper as $key=>$value){
			$student_data[$key]['exam_fee_id']=$exam_form->id;
			$student_data[$key]['rollno']=$request->rollNo;
			$student_data[$key]['name']=$request->name;
			$student_data[$key]['father_name']=$request->father;
			$student_data[$key]['mother_name']=$request->mother;
			$student_data[$key]['date_of_birth']=$request->date_of_birth;
			$student_data[$key]['mobile']=$request->mobile;
			$student_data[$key]['email']=$request->email;
			$student_data[$key]['alternate_email_id']=$request->alternate_email_id;
			$student_data[$key]['aadhar']=$request->aadhar;
			$student_data[$key]['address']=$request->address;
			$student_data[$key]['pin_code']=$request->pin_code;
			$student_data[$key]['gender']=$request->gender;
			$student_data[$key]['category']=$request->category;
			$student_data[$key]['course_id']=$request->course;
			$student_data[$key]['branch_id']=$request->branch;
			$student_data[$key]['vaccinated']=$request->vaccination;
			$student_data[$key]['batch']=$request->batch;
			$student_data[$key]['scribe']=$request->scribe;
			$student_data[$key]['disabilty_category']=$request->category_of_disability;
			$student_data[$key]['semester']=$request->semester;
			$student_data[$key]['sub_code']=$request->paper[$key];
			$student_data[$key]['form_type']=$request->form_type;
			$student_data[$key]['exam_form']=1;
			$student_data[$key]['created_at']=Carbon::now();
			
		}
		$check_rollno = StudentAllFromOldAgency::where(['roll_no'=>$request->rollNo])->orderBy('id','DESC')->first();
		if($check_rollno){
		$check_rollno->date_of_birth=$request->date_of_birth;
		//dd($check_rollno->date_of_birth);
		StudentAllFromOldAgency::where(['roll_no'=>$request->rollNo])->update(['date_of_birth'=>$request->date_of_birth]);
		//$check_rollno->save();
		}
		ExamForm::insert($student_data);
		}
		$slug=$request->rollNo;
		if($request->form_type=='compartment'){
		return redirect('exam-form/view/'.$slug.'?course_id='.$request->course.'&exam_form='.$request->form_type.'&rollNo='.$slug.'&subjects='.$subj);
		}
		else{
			return redirect('exam-form/view/'.$slug.'?course_id='.$request->course.'&exam_form='.$request->form_type.'&rollNo='.$slug);
		
		}
		
		
	}


	//-- Exam Form Filling --//

	
	
	
	
	//Exam Form View //
	
	public function Examformview( $slug,Request $request)
	{ 
		//dd($request->all());
		$check_rollno = StudentAllFromOldAgency::where(['roll_no'=>$slug])->orderBy('id','DESC')->first();
		$campus_id = campus_name($check_rollno->enrollment_no);
		if($campus_id==5 || $campus_id==1){
			$examFee_old = ExamFee::withTrashed()->where(['roll_no'=>$slug])->first();
		}else{
			$examFee_old = null;
		}
		$form_data=ExamFee::where('roll_no',$slug)->orderBy('id','DESC')->first();
		if($form_data && $form_data->bank_name==null){
			return redirect('exam-form/payment/'.$form_data->roll_no)->with('error','Please Fill Your Fees Details');
		}
		$form_enrollment=ExamForm::where('rollno',$slug) ->update(['enrollment_number' => $check_rollno->enrollment_no]);
		if($form_data && $form_data->enrollment_no==null){
			if($check_rollno){
				$form_data->enrollment_no = $check_rollno->enrollment_no;
				$form_data->save();
			}
		}
		$subject= ExamForm::where('exam_fee_id',$form_data->id)->pluck('sub_code')->toArray();
		$subjectList=Subject::whereIn('sub_code',$subject)
			->where('course_id',$form_data->course_id)
			->where('semester_id',$form_data->semester)
			->get();
		//	dd($form_data);
		$exam_form=ExamForm::where('rollno',$slug)->first();
		//dd($form_data->formdata[0]->name);
		return view('student.exam.examination-form-view',['form_data'=>$form_data,'exam_form'=>$exam_form,'subjectList'=>$subjectList,'examFee_old'=>$examFee_old]);
	}
	public function Examformviewpost( $slug,Request $request)
	{
		//dd($request->all());
		$validator = Validator::make($request->all(), [
            'agree' => 'required',
			],['agree.required'=>'Please Check Declaration Box']
			);
			if ($validator->fails()) {    
            return back()->withErrors($validator);
        }
		$exam_form = ExamFee::where('roll_no',$request->rollno)->orderBy('id','DESC')->first();
		$exam_form->is_agree = $request->agree;
		$exam_form->save();
		return redirect('exam-form/view/'.$exam_form->roll_no.'?exam_form='.$request->exam_form);
	}
	
	//--Exam Form View --//
	
	//Ajax For Semester and Subjects //
	public function get_Semester(Request $request)
	{
		$html='<option value="">All</option>';
		$query= Semester::where(['course_id' => $request->course_id])->orderBy('semester_number','ASC')->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		return $html;
	}
	public function get_Subject(Request $request)
	{
		$html='';
		$query= Subject::where(['semester_id' => $request->semester])->get();

		foreach($query as $sc){
			$html.='<tr><td><input type="checkbox" value="'.$sc->sub_code.'" name="paper[]"></td><td>'.$sc->sub_code.'</td><td>'.$sc->name.'</td><td>'.$sc->subject_type.'</td><td>'.$sc->type.'</td></tr>';
		}
		
		//dd($html);
		return $html;
	}
	public function get_Subject_mbbs(Request $request)
	{
		$batch = batchFunctionReturn($request->roll_no);
		$enrollment = Enrollment::where('roll_number',$request->roll_no)->first();
		$html='';
		if($enrollment && $enrollment->course_id==49){
			$query= Subject::where(['semester_id' => $request->semester,'batch' => $batch])
			->get();
		}else{
			$query= Subject::where(['semester_id' => $request->semester])
			->get();
		}
		foreach($query as $sc){
			$html.='<tr><td><input type="checkbox" value="'.$sc->sub_code.'" name="paper[]"></td><td>'.$sc->sub_code.'</td><td>'.$sc->name.'</td><td>'.$sc->subject_type.'</td><td>'.$sc->type.'</td></tr>';
		}
		
		return $html;
	}
	public function get_Subject_Practical(Request $request)
	{
		$html='';
		$query= Subject::where(['semester_id' => $request->semester])
		->where('subject_type','!=','Practical')
		->get();

		foreach($query as $sc){
			$html.='<tr><td><input type="checkbox" value="'.$sc->sub_code.'" name="paper[]"></td><td>'.$sc->sub_code.'</td><td>'.$sc->name.'</td><td>'.$sc->subject_type.'</td><td>'.$sc->type.'</td></tr>';
		}
		
		//dd($html);
		return $html;
	}

	public function get_Subjects(Request $request)
	{
		//dd($request->all());
		$html='';
		$query= Subject::where(['semester_id' => $request->semester])->get();

		foreach($query as $sc){
			$html.='<option value='.$sc->sub_code.'>'.$sc->name.'</option>';
			}
		
		//dd($html);
		return $html;
	}

	public function get_Course(Request $request)
	{
		//dd($request->all());
		$html='<option value="">All</option>';
		$query= Course::where(['campus_id' => $request->campus_id])->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		return $html;
	}
	
	
	
	
	//--Ajax For Semester And Subject --//
	
	public function examformfee( Request $request, $slug){
		$exam_fee=0;
		$slug=$slug;
		$exam_form_details = ExamFee::find($slug);
		$student = $exam_form_details->students;
		$fee=CourseFee::where('course_id',$exam_form_details->course_id)->where('fees_details','Exam Fee')->first();
		if($fee){
			$exam_fee = $fee->non_disabled_fees;
		}
		return view('student.exam.exam-fee-details',[
			'slug'=>$slug,
			'exam_fee'=>$exam_fee,
			'exam_form_details'=>$exam_form_details,
			'examfee'=>$exam_form_details,
			'amount'=>$exam_fee,
			'student' =>$student
		]);
	}
	public function submitexamformfee($slug ,Request $request){
		// dd($request->all());
		$student = Auth::guard('student')->user();
		$validator = Validator::make($request->all(), [
      'bank_name' => 'required',
			'bank_IFSC_code'=>'required',
			//'challan_number'=>'required|unique:exam_fees,receipt_number',
			'challan_number'=>'required',
			'fee_amount'=>'required',
			'challan'=>'required',
			'challan_reciept_date'=>'required',
			]);
			if ($validator->fails()) {    
            return back()->withErrors($validator)->withInput();
        }
		
		$exam_fee = ExamFee::where('roll_no',$slug)
		->where('course_id',$request->course)
		->where('semester',$request->semester)
		->where('form_type',$request->back_papers)
		->where('academic_session',$request->accademic_session)
		->orderBy('id','DESC')
		->first();

		if($request->challan){
			$exam_fee->addMediaFromRequest('challan')->toMediaCollection('challan');
		}
		$exam_fee->save();

		ExamFee::where(['roll_no'=>$slug])->orderBy('id','DESC')->update([
           'bank_name' => $request->bank_name,
           'bank_ifsc_code' => $request->bank_IFSC_code,
           'receipt_number' => $request->challan_number,
           'receipt_date' => $request->challan_reciept_date,
           'fee_amount' => $request->fee_amount,
           'is_agree' => 1,
            ]);
		
			return redirect('student/exam-form-view/'.$student->roll_number.'?course='.$request->course.'&semester='.$request->semester.'&back_papers='.$request->back_papers.'&accademic_session='.$request->accademic_session);
		
	}
	//--MBBS Form Fee --//
	
	// Scrutiny And Challenge Form Login //
	
	
	public function form_login(){
		$var='scrutiny/challange';
		return back();
		return redirect('exam-form-login?form='.$var);
	}
	
	
	
	public function Form_Login_Post(Request $request)
	{   
		//dd($request->all());
		if($request->roll_no)
		{
	        $this->validate($request, [
				'roll_no'=>'required',
				'form_type'=>'required',
				//'g-recaptcha-response' => 'required|captcha',
			],
			[
			//	'g-recaptcha-response.required' => 'Google Captcha field is required.',
			]); 
			$check_rollno = StudentAllFromOldAgency::where(['roll_no'=>$request->roll_no])->first();
			if(!$check_rollno){
				return back()->with('error','Roll Number not found');
			}

			if($check_rollno->status==0){
				return back()->with('error',$check_rollno->status_description);
			}
			if($request->form_type=='scrutiny')
			{
				//dd('');
				$rollno=Scrutiny::where(['roll_no'=>$request->roll_no])
				->orderBy('id','DESC')
				->first();
			if($rollno)
				{
					if($rollno->form_type==1)
						{
							if (($rollno->form_status==1)&&($rollno->challan_number!=null) &&($rollno->fee_status==1) ) 
								{
									return redirect('scrutiny-form-view/'.$request->roll_no)->with('message','Your Application is Approved');;
								}
							elseif(($rollno->form_status==1)&&($rollno->challan_number!=null) &&($rollno->fee_status==0))
								{
									return redirect('scrutiny-form-view/'.$request->roll_no)->with('message','Your Data Already Exist Please Wait For Approval');
								}
							elseif(($rollno->form_status==1)&&($rollno->challan_number==null) &&($rollno->fee_status==0))
								{
									//dd($rollno);
									return redirect('scrutiny-form/payment/'.$request->roll_no.'?course_id='.$rollno->course_id.'&exam_form='.$rollno->form_type);
								}
						}
					else
					{
						return redirect('scrutiny-form/'.$request->roll_no.'?exam_form='.$request->form_type);
					}
				}
				else
					{
						return redirect('scrutiny-form/'.$request->roll_no.'?exam_form='.$request->form_type);
					}
			}
			elseif($request->form_type=='challange')
			{
				$rollno=Challenge::where(['roll_no'=>$request->roll_no])
				->orderBy('id','DESC')
				->first();
				if($rollno){
				if (($rollno->challange_form==1)&&($rollno->challan_number!=null) &&($rollno->fees==1) ) {
				
						return redirect('challenge-form-view/'.$request->roll_no)->with('message','Your Application is Approved');;
						}
					elseif(($rollno->challange_form==1)&&($rollno->challan_number!=null) &&($rollno->fees==0))
						{
							return redirect('challenge-form-view/'.$request->roll_no)->with('message','Your Data Already Exist Please Wait For Approval');
							
						}
					elseif(($rollno->challange_form==1)&&($rollno->challan_number==null) &&($rollno->fees==0))
						{

							return redirect('challenge-form/payment/'.$request->roll_no.'?course_id='.$rollno->course_id.'&exam_form='.$rollno->form_type);
						}
			}
			else {
				return redirect('exam-form/'.$request->roll_no.'?exam_form='.$request->form_type);
			}
			}
			
			}
		
		
	}	
	
			
		
			
			
	
public function submit_challenge_form(Request $request){

			$validator= Validator::make($request->all(),[
				//'enrollment_or_roll_no' => 'required',
				'student_name' => 'required',
				'father' => 'required',
				'mother' => 'required',
				'aadhar' => 'required',
				'mobile' => 'required',
				'email' => 'required',
				//'university' => 'required',
				'course_id' => 'required',
				'branch_id' => 'required',
				'semester_id' => 'required',
				'form_count' => 'required',
				'photo' => 'required',
				'signature' => 'required',
				'subject.*' => 'required',
				'paper.*' => 'required',

			],
			[
				//'roll' => 'Roll No',
				'student_name' => 'Student Name',
				'father' => 'Father Name',
				'mother' => 'Mother Name',
				//'university' => 'University Name',
				'aadhar' => 'Aadhar No',
				'mobile' => 'Mobile No',
				'email' => 'Email Id',
				'course_id' => 'Course',
				'branch_id' => 'Branch',
				'photo' => 'Photo',
				'signature' => 'Signature',
				'semester_id' => 'Semester',
			]);
			//dd($request->all());
			
			if($validator->fails()){
				return back()->withErrors($validator)->withInput();
			}
		
			$scrutiny_check = Scrutiny::select('id')->where('roll_no',$request->rollNo)
			->where('course_id',$request->course_id)
			->where('batch',$request->batch)
			->get();
			if(!$request->paper){
				return back()->with('error','Please select subjects');
			}
			$sunbject_l=implode(" ",$request->paper);
			$scrutiny = new Scrutiny;
			$scrutiny->roll_no	 = $request->rollNo;
			$scrutiny->enrollment_no = $request->enrollment;
			$scrutiny->student_name = $request->student_name;
			$scrutiny->father = $request->father;
			$scrutiny->mother = $request->mother;
			$scrutiny->aadhar = $request->aadhar;
			$scrutiny->mobile = $request->mobile;
			$scrutiny->email = $request->email;
			$scrutiny->university = $request->university;
			$scrutiny->course_id = $request->course_id;
			$scrutiny->branch_id = $request->branch_id;
			$scrutiny->semester_id = $request->semester_id;
			$scrutiny->form_type = $request->form_type;
			$scrutiny->academic_session = $request->batch;
			$scrutiny->batch = batchFunctionMbbs($request->rollNo);
			$scrutiny->form_count = $request->form_count;
			// $scrutiny->form_count = ($scrutiny_check->count()+1);
			// $scrutiny->form_status = 1;
			if($request->photo){
				$scrutiny->addMediaFromRequest('photo')->toMediaCollection('photo');
			}
			if($request->signature){
				$scrutiny->addMediaFromRequest('signature')->toMediaCollection('signature');
			}
			$scrutiny->sub_code = $sunbject_l;
			$scrutiny->save();
			return redirect('challenge-form-view/'. $scrutiny->id);
		}
		public function challengeformview($slug,Request $request)
			{
				$scrutiny = Scrutiny::find($slug);
				if(!$scrutiny){
					return back()->with('error','Invalid Challange id found');
				}
				$check_rollno = StudentAllFromOldAgency::where(['roll_no'=>$scrutiny->roll_no])->first();
				
				if($scrutiny && $scrutiny->enrollment_no==null){
					if($check_rollno){
						$scrutiny->enrollment_no = $check_rollno->enrollment_no;
						$scrutiny->save();
					}
				}
				$sublist=Subject::whereIn('sub_code',explode(" ",$scrutiny->sub_code))
					->where('course_id',$scrutiny->course_id)	
					->get();
				return view('student.exam.challenge-form-view',['scrutiny'=>$scrutiny,'sublist'=>$sublist]);
			}
			public function challengeformviewpost( $slug,Request $request)
				{
					//dd($request->all());
					$validator = Validator::make($request->all(), [
						'agree' => 'required',
						],['agree.required'=>'Please Check Declaration Box']
						);
						if ($validator->fails()) {    
						return back()->withErrors($validator);
					}
					$exam_form = Scrutiny::where('roll_no',$request->roll_no)->orderBy('id','DESC')->first();
					$exam_form->agree = $request->agree;
					$exam_form->form_status=1;
					$exam_form->save();
                   
                   return redirect('challenge-form/payment/options/'.$exam_form->roll_no.'?exam_form='.$request->exam_form);
					//return redirect('scrutiny-form/payment/'.$exam_form->roll_no.'?exam_form='.$request->exam_form);
				}
			
			
			
		public function scrutinyfee( Request $request, $slug)
		{
			$exam_fee=null;
			$exam_form_details = Scrutiny::where(['roll_no'=>$slug,'form_type'=>$request->exam_form])->first();
			if(!$exam_form_details){
				return redirect('scrutiny-form/'.$slug)->with('error','Please fill the form first.');
			}
			$subject= explode(' ',$exam_form_details->sub_code);
			$exam_fee = Subject::whereIn('sub_code',$subject)->get('scrutiny_fee')->sum('scrutiny_fee');
			return view('student.exam.scrutiny-fee-details',['slug'=>$slug,'fees'=>$exam_fee,'form_data'=>$exam_form_details]);
		}

		public function submitscrutinyfee(Request $request)
			{
				//dd($request->all());
				$roll_no = $request->roll_no;
				$scrutiny =Scrutiny::where(['roll_no'=>$roll_no])->orderBy('id','DESC')->first();
				$scrutiny->bank_name=$request->bank_name;
				$scrutiny->challan_number=$request->challan_number;
				$scrutiny->challan_reciept_date=$request->challan_reciept_date;
				$scrutiny->amount=$request->amount;
				$scrutiny->bank_ifsc_code=$request->bank_IFSC_code;
				if($request->challan)
				{
				$scrutiny->addMediaFromRequest('challan')->toMediaCollection('challan');
				}
				$scrutiny->save();
				$sublist=Subject::whereIn('sub_code',explode(" ",$scrutiny->sub_code))->get();
				
			return view('student.exam.scrutiny-form-view',['scrutiny'=>$scrutiny,'sublist'=>$sublist])->with('message','Form Submitted Successfully');	
		
	}
	
public function fetchSubject(Request $request)
    {
      //  dd($request->semester_id);
        $data['subject'] = Subject::where("semester_id",$request->semester_id)->get(["name", "id"]);
        return response()->json($data);
    }
	public function challenge_form( $slug,Request $request)
   	 {

    	//dd($request->all(),$slug);
    	$roll_no=$slug;
		$old_data = StudentAllFromOldAgency::where(['roll_no'=>$slug])->orderBy('session','DESC')->first();
		$scrutinies_check = Scrutiny::where('roll_no',$slug)->orderBy('id','DESC')->first();
		//dd($old_data);
		
		$session = AcademicSession::get();
		$course = Course::where('id',$old_data->course_id)->get();
		$stream = Stream::where('course_id',$old_data->course_id)->first();
		$semesters_query = Semester::where('course_id',$old_data->course_id);
		if($old_data->course_id==49){
			$semesters_query->whereIn('semester_number',[3,4]);
		}
		if($old_data->course_id==95){
			$semesters_query->whereIn('semester_number',[1,3,4]);
		}
		if($old_data->course_id==96){
			$semesters_query->whereIn('semester_number',[1,3,4]);
		}
		$semesters = $semesters_query->get();
		$campus=Campuse::get();
		if($scrutinies_check){
			if($scrutinies_check->form_type==1){
				if (($scrutinies_check->form_status==1)&&($scrutinies_check->challan_number!=null) &&($scrutinies_check->fee_status==1) ){
					return redirect('challenge-form-view/'.$roll_no)->with('message','Your Application is Approved');;
				}elseif(($scrutinies_check->form_status==1)&&($scrutinies_check->challan_number!=null) &&($scrutinies_check->fee_status==0)){
					return redirect('challenge-form-view/'.$roll_no)->with('message','Your Data Already Exist Please Wait For Approval');
				}elseif(($scrutinies_check->form_status==1)&&($scrutinies_check->challan_number==null) &&($scrutinies_check->fee_status==0)){
					return redirect('challenge-form/payment/options/'.$roll_no.'?course_id='.$scrutinies_check->course_id.'&exam_form='.$scrutinies_check->form_type);
				}
			}
		}
    	return view('student.exam.challenge-form',
    		['old_data'=>$old_data,
    		'roll_no'=>$roll_no,
    		'campus'=>$campus,
    		'course'=>$course,
    		'semesters'=>$semesters,
    		'stream'=>$stream,
    		'sessions'=>$session,
    		'scrutinies_check'=>$scrutinies_check,
    		]);
    }

	public function scrutiny_form( $slug,Request $request)
   	 {

    	if(allowRegularStudentsMbbs($slug, 'scrutiny')==false){
			return redirect('mbbs-exam-form-login')->with('error','You are not allowed');
		}
    	$roll_no=$slug;
		$old_data = StudentAllFromOldAgency::where(['roll_no'=>$slug])->orderBy('session','DESC')->first();
		$scrutinies_check = Scrutiny::where('roll_no',$slug)
		->where('form_type',2)
		->orderBy('id','DESC')
		->first();
		// dd($old_data);
		
		$session=AcademicSession::limit(1)
		->orderBy('id','DESC')
		->get();
		$course=Course::where('id',49)->get();
		$semesters=Semester::where('course_id',49)
		->whereIn('semester_number',[3,4])
		->orderBy('semester_number','ASC')
		->get();
		$campus=Campuse::get();
		if($scrutinies_check && $scrutinies_check->form_count!=1){
			if (($scrutinies_check->form_status==1)&&($scrutinies_check->challan_number!=null) &&($scrutinies_check->fee_status==1) ){
				return redirect('scrutiny-form-view/'.$roll_no)->with('message','Your Application is Approved');;
			}elseif(($scrutinies_check->form_status==1)&&($scrutinies_check->challan_number!=null) &&($scrutinies_check->fee_status==0)){
				return redirect('scrutiny-form-view/'.$roll_no)->with('message','Your Data Already Exist Please Wait For Approval');
			}elseif(($scrutinies_check->form_status==1)&&($scrutinies_check->challan_number==null) &&($scrutinies_check->fee_status==0)){
				//dd($rollno);
				return redirect('scrutiny-form/payment/options/'.$roll_no.'?course_id='.$scrutinies_check->course_id.'&exam_form='.$scrutinies_check->form_type);
			}
			
		}
    	return view('student.exam.scrutiny-form',
    		['old_data'=>$old_data,
    		'roll_no'=>$roll_no,
    		'campus'=>$campus,
    		'course'=>$course,
    		'semesters'=>$semesters,
    		'sessions'=>$session,
    		'scrutinies_check'=>$scrutinies_check,
    		]);
    }
	public function submit_scrutiny_form(Request $request){
		// dd($request->all());
		$validator= Validator::make($request->all(),[
			'exam_month' => 'required',
			'student_name' => 'required',
			'father' => 'required',
			'mother' => 'required',
			'aadhar' => 'required',
			'mobile' => 'required',
			'email' => 'required',
			'course_id' => 'required',
			'semester_id' => 'required',
			'academic_session' => 'required',
			'photo' => 'required',
			'signature' => 'required',
			'paper.*' => 'required',

		],
		[
			//'roll' => 'Roll No',
			'student_name' => 'Student Name',
			'father' => 'Father Name',
			'mother' => 'Mother Name',
			//'university' => 'University Name',
			'aadhar' => 'Aadhar No',
			'mobile' => 'Mobile No',
			'email' => 'Email Id',
			'course_id' => 'Course',
			// 'branch_id' => 'Branch',
			'photo' => 'Photo',
			'signature' => 'Signature',
			'semester_id' => 'Semester',
		]);
		
		if($validator->fails()){
			return back()->withErrors($validator)->withInput();
		}
		if(!isset($request->paper) || count($request->paper)==0){
			return back()->with('error','Please select your papers.');
		}
	
		$scrutiny_check = Scrutiny::select('id')->where('roll_no',$request->rollNo)
		->where('course_id',$request->course_id)
		->where('semester_id',$request->semester_id)
		->where('academic_session',$request->academic_session)
		->where('form_type',2)
		->get();
		if($scrutiny_check->first()){
			return back()->with('error','Already Form Submitted.');
		}
		$sunbject_l=implode(" ",$request->paper);
		$scrutiny = new Scrutiny;
		$scrutiny->exam_month	 = $request->exam_month;
		$scrutiny->roll_no	 = $request->rollNo;
		$scrutiny->enrollment_no = $request->enrollment;
		$scrutiny->student_name = $request->student_name;
		$scrutiny->father = $request->father;
		$scrutiny->mother = $request->mother;
		$scrutiny->aadhar = $request->aadhar;
		$scrutiny->mobile = $request->mobile;
		$scrutiny->email = $request->email;
		$scrutiny->university = $request->university;
		$scrutiny->course_id = $request->course_id;
		// $scrutiny->branch_id = $request->branch_id;
		$scrutiny->semester_id = $request->semester_id;
		$scrutiny->form_type = $request->form_type;
		$scrutiny->academic_session = $request->academic_session;
		$scrutiny->batch = batchFunctionMbbs($request->rollNo);
		$scrutiny->form_count = ($scrutiny_check->count()+1);
		if($request->photo){
			$scrutiny->addMediaFromRequest('photo')->toMediaCollection('photo');
		}
		if($request->signature){
			$scrutiny->addMediaFromRequest('signature')->toMediaCollection('signature');
		}
		$scrutiny->sub_code = $sunbject_l;
		$scrutiny->save();
		return redirect('scrutiny-form-view/'. $request->rollNo);
	}
public function scrutinyformview( $slug,Request $request)
{
	$check_rollno = StudentAllFromOldAgency::where(['roll_no'=>$slug])->first();
	$scrutiny=Scrutiny::where('roll_no',$slug)->orderBy('id','DESC')->first();
	if(!$scrutiny){
		return redirect('scrutiny-form/'.$slug)->with('error','Please fill the form first.');
	}

	if($scrutiny && $scrutiny->enrollment_no==null){
		if($check_rollno){
			$scrutiny->enrollment_no = $check_rollno->enrollment_no;
			$scrutiny->save();
		}
	}
	$sublist=Subject::whereIn('sub_code',explode(" ",$scrutiny->sub_code))
	->where('course_id',$scrutiny->course_id)	
	->get();
	return view('student.exam.scrutiny-form-view',['scrutiny'=>$scrutiny,'sublist'=>$sublist]);
}
public function scrutinyformviewpost( $slug,Request $request)
{
	//dd($request->all());
	$validator = Validator::make($request->all(), [
		'agree' => 'required',
		],['agree.required'=>'Please Check Declaration Box']
		);
		if ($validator->fails()) {    
		return back()->withErrors($validator);
	}
	$exam_form = Scrutiny::where('roll_no',$request->roll_no)->orderBy('id','DESC')->first();
	$exam_form->agree = $request->agree;
	$exam_form->form_status=1;
	$exam_form->save();
   
   return redirect('scrutiny-form/payment/options/'.$exam_form->roll_no.'?exam_form='.$request->exam_form);
	//return redirect('scrutiny-form/payment/'.$exam_form->roll_no.'?exam_form='.$request->exam_form);
}

     public function payNow(Request $request ,$slug){
        $scrutiny = Scrutiny::find($request->pid);
		if(!$scrutiny){
			return back()->with('error','Invalid ID');
		}

		$serial = 1;
	if($request->exam_form==1){
		$exam_form_details = $scrutiny;
		$subject= explode(' ',$exam_form_details->sub_code);
		if($exam_form_details->form_count>1){
			$exam_fee = (count($subject) * 2500);
		}else{
			$exam_fee = (count($subject) * 300);
		}
	}
	if($request->exam_form==2){
        $exam_fee=null;
		$slug=$slug;
		$exam_form_details_check = Scrutiny::where(['roll_no'=>$slug,'form_type'=>$request->exam_form])->orderBy('id','desc')->get();
		$exam_form_details = Scrutiny::where(['roll_no'=>$slug,'form_type'=>$request->exam_form])->orderBy('id','desc')->first();
		$subject= explode(' ',$exam_form_details->sub_code);
		if($exam_form_details_check->count()>1){
			$sd = Subject::select(DB::raw('2500 as scrutiny_fee'))->whereIn('sub_code',$subject)->get('scrutiny_fee')->sum('scrutiny_fee');
		}else{
			$sd = Subject::whereIn('sub_code',$subject)->get('scrutiny_fee')->sum('scrutiny_fee');
		}
		$exam_fee=$sd;
		if($scrutiny){
			$serial = $scrutiny->id + 1;
		}
	}
		$data['order_id'] = uniqid().sprintf('%05d', $serial);
        return view('payments.scrutiny-challenge-payments.payment-options',$data,['slug'=>$slug,'fees'=>$exam_fee,'form_data'=>$exam_form_details]);
    }

    public function redirectPaymentGetway(Request $request){
		$data['amount'] = $request->amount;
		$data['order_id'] = $request->order_id;
		$data['return_url'] = $request->return_url;
		//dd($data);
		return view('payments.scrutiny-challenge-payments.scrutiny-challenge-payment-getway',$data);
    }


	public function paymentSuccess(Request $request){
			// dd($request->all());
		$scrutiny = Scrutiny::find($request->id);
		$data['scrutiny'] = $scrutiny;
        return view('payments.scrutiny-challenge-payments.scrutiny-challenge-success-payment',$data);
    }

	public function paymentSave(Request $request){
		//dd($request->all());
		if($request->payment_mode=='offline'){
				
		$validator = Validator::make($request->all(), [
            'bank_name' => 'required',
			'bank_ifcs'=>'required',
//			'transaction_id'=>'required|unique:scrutinies,challan_number',
			'transaction_id'=>'required',
			'amount'=>'required',
			'challan'=>'required',
			'payment_datetime'=>'required',
			'order_id'=>'required|unique:scrutinies,order_id',
			]);
			if ($validator->fails()) {    
            return back()->withErrors($validator)->withInput();
        }
	}
		$scrutiny_update = Scrutiny::find($request->scrutiny_id);
		if($scrutiny_update){
			if($request->payment_mode=='offline'){
			$scrutiny_update->amount = $request->amount;
			$scrutiny_update->bank_name = $request->bank_name;
			$scrutiny_update->bank_ifsc_code = $request->bank_ifcs;
			$scrutiny_update->order_id = $request->order_id;
			$scrutiny_update->challan_number = $request->transaction_id;
			$scrutiny_update->txn_status = $request->response_message;
			$scrutiny_update->payment_mode = $request->payment_mode;
			$scrutiny_update->challan_reciept_date = $request->payment_datetime;
			if($request->challan)
				{
				$scrutiny_update->addMediaFromRequest('challan')->toMediaCollection('challan');
				}
			$scrutiny_update->save();
		}
		else{
			$scrutiny_update->order_id = $request->order_id;
			$scrutiny_update->challan_number = $request->transaction_id;
			$scrutiny_update->amount = $request->amount;
			$scrutiny_update->challan_reciept_date = $request->payment_datetime;
			$scrutiny_update->txn_status = $request->response_message;
			$scrutiny_update->created_at = $request->payment_datetime;
			$scrutiny_update->payment_mode = $request->payment_mode;
			$scrutiny_update->save();
		}
		}
		if($request->payment_mode=='offline'){
			if($scrutiny_update->form_type==1){
				return redirect('challenge-form-view/'.$scrutiny_update->id);
			}else
			return redirect('scrutiny-form-view/'. $scrutiny_update->roll_no);
		}
        return redirect('challenge-pay-success?id='.$scrutiny_update->id)->with('success','Payment Done');
    }


	public function successPaymentViewPage(){
        $data=Payment::first();
        //dd($data);
        return view('payments.success-payment-page',['data'=>$data]);
    }

	public function deletechallenge($id,Request $request){
		$scrutiny_delete = Scrutiny::find($id);
		$scrutiny_delete->delete();
		return back()->with('message','Form reset successfully and again fill the form');
	}


	public function checkEligibilityForBack($data){

			$data = (object)$data;
			$exam_session = '2023-2024';
			$student = Auth::guard('student')->user();
			$allowspecialBack = ApprovalSystem::where('roll_number',$student->roll_number)
      	->where('special_back',$data->back_papers)->first();
			if($allowspecialBack){
				return true;
			}
			$result = Result::where('roll_no',$student->roll_number)
			->where('semester',$data->semester)
			->where('exam_session',$exam_session)
			->distinct('semester')
			->first();
			// dd($result);
			if($result){
			$get_semester = Semester::where('course_id',$result->course_id)
					->where('id',$result->semester)
					->first();
      	if($get_semester->semester_number %2 == 0){
      		$check_back = Result::where('course_id',$result->course_id)
						->where('semester',$result->semester)
      			->where('roll_no',$student->roll_number)
						->where('exam_session',$exam_session)
						->where('grade_letter','F')
						->count();
						// dd($check_back);
						if($check_back >4){
							return false;
						}else{
							$next_sem_id = $get_semester->semester_number -1;
							$privious_semester = Semester::where('course_id',$result->course_id)
								->where('semester_number',$next_sem_id)
								->first();
							$privious_sem_back = Result::where('course_id',$privious_semester->course_id)
								->where('semester',$privious_semester->id)
		      			->where('roll_no',$student->roll_number)
								->where('exam_session',$exam_session)
								->where('grade_letter','F')
								->count();
								if($privious_sem_back >4){
									return false;
								}else{
									return true;
								}
						}
				}else{
					$check_back = Result::where('course_id',$result->course_id)
						->where('semester',$result->semester)
      			->where('roll_no',$student->roll_number)
						->where('exam_session',$exam_session)
						->where('grade_letter','F')
						->count();
						// dd($check_back);
						if($check_back >4){
							return false;
						}else{
							$next_sem_id = $get_semester->semester_number +1;
							$privious_semester = Semester::where('course_id',$result->course_id)
								->where('semester_number',$next_sem_id)
								->first();
							$privious_sem_back = Result::where('course_id',$privious_semester->course_id)
								->where('semester',$privious_semester->id)
		      			->where('roll_no',$student->roll_number)
								->where('exam_session',$exam_session)
								->where('grade_letter','F')
								->count();
								if($privious_sem_back >4){
									return false;
								}else{
									return true;
								}
						}
				}
			}else{
				return false;
			}
      

			$semester_last = Semester::where('course_id',$data->course)->orderBy('semester_number','DESC')->first();
			$checkResult = Result::where('course_id',$data->course)
				->where('semester',$semester_last->id)
				->where('roll_no',Auth::guard('student')->user()->roll_number)
				->first();
			if($checkResult){
				return false;
			}


			$semester = Semester::find($data->semester);
			if($semester->semester_number %2 == 0 ){
				$check_semester_id_for_year_back = $semester->id;
			}else{
				$next_sem_id = ($semester->semester_number+1);
				$semester = Semester::where('course_id',$data->course)->where('semester_number',$next_sem_id)->first();
				$check_semester_id_for_year_back = $semester->id;
			}

			$resultData_check = Result::where('course_id',$data->course)
				->where('semester',$check_semester_id_for_year_back)
				->where('exam_session',$exam_session)
				->where('result_type','new')
				->where('year_back',1)
				->where('roll_no',Auth::guard('student')->user()->roll_number)
				->first();
				dd($resultData_check);
			if($resultData_check){
				return false;
			}
			$resultData = Result::where('course_id',$data->course)
				->where('semester',$data->semester)
				->where('exam_session',$exam_session)
				->where('result_type','new')
				->where('result','PCP')
				->where('roll_no',Auth::guard('student')->user()->roll_number)
				->first();
				// dd($resultData);
				if($resultData){
				return true;
			}else{
				return false;
			}
	}
	public function checkEligibilityForFinalBack($data){
		$data = (object)$data;
		$roll_no = Auth::guard('student')->user()->roll_number;
		$semesters = Semester::where('course_id',$data->course)->get();
		$isEligibilityForFinalBack = false;
		foreach($semesters as $semester){
			$resultData = Result::where('course_id',$data->course)
			->where('semester',$semester->id)
			->where('roll_no',$roll_no)
			->first();
			if($resultData){
				$getSemData = $resultData->get_semester_result_single();
				$getSemData_all = $resultData->get_semester_result(1);
				$getSemData_check_absent = false;
				foreach($getSemData_all as $getSemData_all_row){
					if($getSemData_all_row->internal_marks=='ABS' || $getSemData_all_row->internal_marks=='ABSENT'){
						$getSemData_check_absent = true;
					}
				}
				if($getSemData->result=='F' || $getSemData->result=='PCP' || $getSemData->result=='A' || $getSemData->result=='WH' || $getSemData_check_absent==true){
					$isEligibilityForFinalBack = true;
				}
			}
		}
		return $isEligibilityForFinalBack;
	}
	public function checkEligibilityForSpecialBack($data){
			$data = (object)$data;
			$resultData = ApprovalSystem::where('roll_number',Auth::guard('student')->user()->roll_number)
			->where('course_id',$data->course)
			->where('semester_id',$data->semester)
			->where('special_back',$data->back_papers)
			->where('session',$data->accademic_session)
			->first();
			if($resultData){
				return true;
			}else{
				return false;
			}
	}
	public function checkEligibilityForRagularExam($data){
			$data = (object)$data;
			$exam_session = '2023-2024';
			$resultData = Result::where('course_id',$data->course)
				->where('semester',$data->semester)
				// ->where('exam_session',$exam_session)
				->where('roll_no',Auth::guard('student')->user()->roll_number)
				->first();
				// dd($resultData);
				if($resultData){
				return true;
			}else{
				return false;
			}
	}

	public function finalBackForm($id,Request $request){
		// dd($request->all());
		$student = Auth::guard('student')->user();
		$examData_photo = ExamFee::withTrashed()->where('roll_no',$student->roll_number)->first();
		$student_photo = Student::withTrashed()->where('roll_number',$student->roll_number)->first();
		
		$examData = ExamFee::where('roll_no',$student->roll_number)
		->where('course_id',$request->course)
		->where('semester',$request->semester)
		->where('form_type',$request->back_papers)
		->where('academic_session',$request->accademic_session)
		->first();
		if($examData){
			return redirect('student/view-back-paper/'.$student->roll_number.'?course='.$request->course.'&semester='.$request->semester.'&back_papers='.$request->back_papers.'&accademic_session='.$request->accademic_session);
		}
		if($this->paperIsOpen($request->all(),0)==false){
			return back()->with('error',$this->paperIsOpen($request->all(),1));
		}

		$checkEligibility = false;
		if($request->back_papers=='back_paper'){
			$checkEligibility = $this->checkEligibilityForBack($request->all());
			if($checkEligibility==false){
				return back()->with('error','You are not eligible for back paper');
			}
		}
		if($request->back_papers=='final_back_paper'){
			if(getStudentCourseDurationEligibility($student->roll_number,$request->course)==false){
				return back()->with('error','Your course maximum duration is over. You can not apply for back / final back paper.');
			}
			$checkEligibility = $this->checkEligibilityForFinalBack($request->all());
			if($checkEligibility==false){
					return back()->with('error','You are not eligible for final back paper');
			}
		}
		if($request->back_papers=='special_back'){
			$checkEligibility = $this->checkEligibilityForSpecialBack($request->all());
			if($checkEligibility==false){
				return back()->with('error','You are not eligible for special back paper');
			}
		}

		if($request->back_papers=='ragular_exam'){
			return back()->with('error','Spacial back form is not open. Please contact to COE office.');
			$checkEligibility = $this->checkEligibilityForRagularExam($request->all());
			if($checkEligibility==false){
				return back()->with('error','You are not eligible for special back paper');
			}
		}


		$semester = Semester::where('id',$request->semester)->where('course_id',$request->course)->first();
		$course = Course::where('id',$request->course)->first();
		$stream = Stream::where('course_id',$course->id)->first();
		$sessions = AcademicSession::get();

		$subjectList = $this->allowedSujects($request);
		return view('student.exam.final-back-paper',compact('student','course','sessions','semester','stream','examData','subjectList','examData_photo','student_photo'));
	}

	function paperIsOpen($requestData,$status){
		$response = false;
		$message = 'Exam date is over';
		if($status==1){
			return $message;
		}

		$course = Course::find($requestData['course']);
		$semester = Semester::find($requestData['semester']);
		$setting = getExamSettingLatest($requestData['back_papers'],$requestData['semester']);
		if($setting){
			$message = $setting->message;
			$currentDate = date('Y-m-d');
			$startData = $setting->from_date;
			$endData = $setting->to_date;
			if($setting->campus_id == null && $setting->course_id == null && $setting->semester_id == null){
				if ($currentDate >= $startData && $currentDate <= $endData){
					$response = true;
				}
			}else if($setting->campus_id != null && $setting->course_id != null && $setting->semester_id != null){
				if ($setting->course_id == $requestData['course'] && $setting->semester_id == $requestData['semester']){
					if ($currentDate >= $startData && $currentDate <= $endData){
						$response = true;
					}
				}
			}else if($setting->campus_id != null && $setting->course_id != null && $setting->semester_id == null){
				if ($setting->course_id == $requestData['course']){
					if ($currentDate >= $startData && $currentDate <= $endData){
						$response = true;
					}
				}
			}else if($setting->campus_id != null && $setting->course_id == null && $setting->semester_id == null){
				if ($setting->campus_id == $course->campus_id){
					if ($currentDate >= $startData && $currentDate <= $endData){
						$response = true;
					}
				}
			}
			if($requestData['back_papers']=='regular'){
				if($semester->getOddEven() != $setting->getOddEven() && $setting->getOddEven()!='ALL'){
					$response = false;
					$message = 'Exam form is open only for '.$setting->getOddEven().' semesters';
				}
			}
		}
		$student = Auth::guard('student')->user();
		if($student && allowRegularStudents($student->roll_number)){
			return $message;
		}else{
			return $response;
		}
	}

	public function allowedSujects($data){
		$data = (object)$data->all();
		$student = Auth::guard('student')->user();
		// $session = ['2020-2021','2021-2022','2022-2023','2023-2024'];
		// $allowspecialBack = ApprovalSystem::where('roll_number',$student->roll_number)
		// ->where('special_back',$data->back_papers)->first();
		// if(!$allowspecialBack){
		// 		// $session = ['2022-2023','2023-2024'];
		// }
		// getting setting details and odd/even semester ids
		$getEvenSemestersIds = Semester::where('course_id',$data->course)->pluck('id')->toArray();
		$getExamSettingLatest = getExamSettingLatest($data->back_papers,$data->semester);
		if($getExamSettingLatest){
			if($getExamSettingLatest->semester_type==2){
				$getEvenSemestersIds = getEvenSemesters($data->course,1);
			}
			if($getExamSettingLatest->semester_type==3){
				$getEvenSemestersIds = getOddSemesters($data->course,1);
			}
		}
		if($data->back_papers=='final_back_paper'){
			$semester_id_year_back_array = [];
			$failed_subject_codes = [];
			$failed_semester_id = [];
			$results = Result::select('enrollment_no','roll_no','exam_session','semester','semester_number','subject_code','grade_letter','result')
			->where('course_id',$data->course)
			// ->where('grade_letter','F')
			// ->orWhere('internal_marks','ABS')
			// ->orWhere('internal_marks','ABSENT')
			->where('roll_no',$student->roll_number)
			->distinct();
			$results_clone = clone $results;
			$resultData = $results_clone->get();
			$result_failed_ids = [];
			foreach($resultData as $resultRow){
				$single_result = $resultRow->get_semester_result(1);
				$semester_id_year_back = $this->get_year_back_semesters($single_result);
				if(count($semester_id_year_back)>0){
					$semester_id_year_back_array = $semester_id_year_back;
				}
				foreach($single_result as $single_result_row){
					if($single_result_row->grade_letter=='F' || $single_result_row->internal_marks=='ABS' || $single_result_row->internal_marks=='ABSENT'){
						$result_failed_ids[] = $single_result_row->id;
						$failed_subject_codes[] = $single_result_row->subject_code;
						$failed_semester_id[] = $single_result_row->semester;
					}
				}
			}
			$result_failed_ids = array_unique($result_failed_ids);
			// dd($semester_id_year_back_array);
			$subjectList = Subject::withTrashed()
			->select('subjects.*')
			->join('results',function($q){
				$q->on('results.course_id','subjects.course_id')
				->on('results.semester','subjects.semester_id')
				->on('results.subject_code','subjects.sub_code');
			})
			->where('results.status','2')
			->whereIn('results.id',$result_failed_ids)
			->whereNotIn('results.semester',$semester_id_year_back_array)
			->whereIn('semester_id',$getEvenSemestersIds)
			->orderBy('subjects.position','ASC')
			->get();
			return $subjectList;
		}
		// elseif($data->back_papers=='special_back'){
		// 	$resultData = ApprovalSystem::where('roll_number',$student->roll_number)
		// 	->where('course_id',$data->course)
		// 	->where('semester_id',$data->semester)
		// 	->where('special_back',$data->back_papers)
		// 	->where('session',$data->accademic_session)
		// 	->first();
		// 	$failed_subject_codes = explode(',',$resultData->sub_code);
		// 	$subjectList = Subject::withTrashed()
		// 	->where('course_id',$data->course)
		// 	->where('semester_id',$data->semester)
		// 	->whereIn('sub_code',$failed_subject_codes)
		// 	->orderBy('position','ASC')
		// 	->get();
		// 	return $subjectList;
		// }
		// else{
		// 	$failed_subject_codes = Result::where('course_id',$data->course)
		// 		->where('semester',$data->semester)
		// 		->where('grade_letter','F')
		// 		->whereIn('exam_session',$session)
		// 		->where('roll_no',$student->roll_number)
		// 		->pluck('subject_code')
		// 		->toArray();
		// 	return $subjectList = Subject::withTrashed()
		// 		->where('course_id',$data->course)
		// 		->where('semester_id',$data->semester)
		// 		->whereIn('sub_code',$failed_subject_codes)
		// 		->orderBy('position','ASC')
		// 		->get();
		// }
	}

	public function get_year_back_semesters($single_result){
		$year_back_semester = [];
		foreach($single_result as $row){
			if($row->year_back==1){
				$course_id = $row->course_id;
				$current_semester = $row->semester;
				$last_semester = ($row->semester_number-1);
				$semester = Semester::where('course_id',$course_id)
				->where('semester_number',$last_semester)
				->first();
				if($semester){
					$year_back_semester = [$semester->id,$current_semester];
				}else{
					$year_back_semester = [$current_semester];
				}
			}
		}
		return $year_back_semester;
	}

	public function finalBackSave(Request $request){
			$request->validate([
				'sub_code' => 'required',
				'term_and_condition' => 'required',
      		]);
			DB::beginTransaction();
			try {

				$subjectsArray = [];
				foreach($request->sub_code as $index=>$subCode){
						$subjectsArray[$index] = $subCode;
				}
				$subjects = implode(' ',$subjectsArray);
				$student = Auth::guard('student')->user();
				$saveData=array(
					'enrollment_no'=>$student->enrollment_no,
					'roll_no'=>$student->roll_number,
					'academic_session'=> $request->accademic_session,
					// 'batch'=>'2021-2022',
					'course_id'=>$request->course_id,
					'semester'=>$request->final_semester_id,
					'subject'=>$subjects,
					'form_type'=>$request->back_papers,
					'exam_form'=>1,
					'scribe'=>$request->writer_scribe,
					'current_exam_session'=>$request->current_exam_session,
				);

        		$examFee = new ExamFee;
				$examFee->fill($saveData);
				$examFee->save();
				$getExamFeeId = $examFee->id;
				$rowArray = [];
				foreach($request->sub_code as $index=>$subCode){
						$rowArray[$index] = array(
							'sub_code' => $subCode,
							'mid' => (isset($request->mid[$index]))?$request->mid[$index]:null,
							'external' => (isset($request->external[$index]))?$request->external[$index]:null,
							'viva' => (isset($request->viva[$index]))?$request->viva[$index]:null,
							'p_internal' => (isset($request->p_internal[$index]))?$request->p_internal[$index]:null,
							'paper_type' => $request->back_papers,
							'term_and_condition' => $request->term_and_condition,
							'roll_number' => $request->roll_no,
							'academic_session' => $request->accademic_session,
							'course_id' => $request->course_id,
							'semester_id' => $request->semester_id[$index],
							'exam_fee_id' => $getExamFeeId,
						);
				}
				BackPaper::insert($rowArray);
		    	DB::commit();
				return redirect('student/view-back-paper/'.$student->roll_number.'?course='.$request->course_id.'&semester='.$request->semester_id.'&back_papers='.$request->back_papers.'&accademic_session='.$request->accademic_session)->with('message', 'Form Submitted Successfully. Now you pay after that you can take print');
			} catch (\Exception $e) {
			    DB::rollback();
					return back()->with('error',$e->getMessage());
			}
	}



	public function checkBackResult(){
		$getPcpData = Result::first();
	}

	public function viewfinalBackForm(Request $request){
		// dd($request->all());
		  $student = Auth::guard('student')->user();
		  $examData_photo = ExamFee::withTrashed()->where('roll_no',$student->roll_number)->first();
		  $student_photo = Student::withTrashed()->where('roll_number',$student->roll_number)->first();
		  $examData = ExamFee::where('roll_no',$student->roll_number)
				->where('course_id',$request->course)
				->where('semester',$request->semester)
				->where('form_type',$request->back_papers)
				->where('academic_session',$request->accademic_session)
				->first();
			if(!$examData){
				return redirect('student/exam-form')->with('error','Invalid Exam Form ID');
			}
			$semester = Semester::where('id',$request->semester)->where('course_id',$request->course)->first();
			$course = Course::where('id',$request->course)->first();
			$stream = Stream::where('course_id',$course->id)->first();
			$sessions = AcademicSession::get();
			$payment_details = ExamPayment::where('exam_fee_id',$examData->id)->first();
			$viewBackData = BackPaper::where('course_id',$request->course)->where('semester_id',$request->semester)->get();
			if($request->back_papers=='final_back_paper'){
				$viewBackData = BackPaper::where('course_id',$request->course)->get();
			}
			$subjectList=Subject::where('course_id',$semester->course_id)->where('semester_id',$semester->id)->orderBy('position','ASC')->get();
			// dd($subjectList);
			$exam_fee_amount = $this->getBackFeesAmount($examData->id);
			// $exam_fee_amount = 1;
			if($payment_details){
				$order_id = $payment_details->order_id;
				// $this->updatePaymentBackPapers($examData->id,$order_id);
			}else{
				// $order_id = $this->createPaymentOrderOnly($examData->id,$exam_fee_amount);
				$order_id = 'BACKEXAM'.rand(11111111,999999999);
				$this->insertPayment($examData->id,$order_id,$exam_fee_amount);
			}
			$payment = ExamPayment::where('exam_fee_id',$examData->id)
				->where('txn_status','SUCCESS')
				->first();
			$subjectList = $this->allowedSujects($request);
			$allowedForOfflinePayment = $this->allowedForOfflinePayment($semester);
			$allowedForPayment = $this->paperIsOpen($request->all(),0);
		return view('student.exam.final-back-paper-view',compact('student','course','sessions','semester','stream','examData','subjectList','viewBackData','payment','exam_fee_amount','order_id','allowedForOfflinePayment','allowedForPayment','examData_photo','student_photo'));
	}

	public function allowedForOfflinePayment($semester){
		$allowedCourses = Course::where('campus_id',1)
			->where('id',$semester->course_id)
			// B.Tech Courses Not Allowed
			->whereNotIn('id',[41,42,43,44,45])
			->first();
		return 'Payment service is not available. Please contact to COE office.';
		if($allowedCourses){
			return '';
		}else{
			return 'Payment service is not available. Please contact to COE office.';
		}
	}

	public function getBackFeesAmount($exam_fee_id){
			$examSubjects = BackPaper::where('exam_fee_id',$exam_fee_id)->get();
			$exam_fee_amount = 0;
			foreach($examSubjects as $examSubjectsRow){
				if($examSubjectsRow->mid=='1'){
					$exam_fee_amount = $exam_fee_amount + 500;
				}
				if($examSubjectsRow->external=='1'){
					$exam_fee_amount = $exam_fee_amount + 500;
				}
				if($examSubjectsRow->viva=='1'){
					$exam_fee_amount = $exam_fee_amount + 1000;
				}
				if($examSubjectsRow->p_internal=='1'){
					$exam_fee_amount = $exam_fee_amount + 1000;
				}
			}
			return $exam_fee_amount;
	}


    // public function paymentSuccessSave(Request $request){
    // 		$payment_success = $this->updatePayment($request->id);
    //     if($payment_success==true){
    //         return back();
    //     }
    // 		$this->insertPayment($request->id,$request->txnid);

    //     $key = $this->hdfcKey();
    //     $salt = $this->hdfcSalt();
    //     $action = $this->hdfcUrl();
    //     return view('student.certificate.razorpayView',compact('key','salt','action'));
    // }

    // public function updatePayment($exam_fee_id){
    //     $payment_success = false;
	// 			$paymentstatus = ExamPayment::where('exam_fee_id',$exam_fee_id)->where('transaction_id','SUCCESS')->first();
	// 			if($paymentstatus){
	// 				return false;
	// 			}
	// 			$paymentDetails_array = ExamPayment::where('exam_fee_id',$exam_fee_id)->orderBy('txn_date','DESC')->get();
    //     foreach($paymentDetails_array as $paymentDetails){
    //     	$verifyPayment = (object)$this->verifyPayment($paymentDetails->transaction_id,'all');
    //     	if(isset($verifyPayment->amt)){
    //         if($verifyPayment->status=='success'){
    //             $paymentDetails->txn_status = 'SUCCESS';
    //         		$payment_success = true;
    //         }else{
    //             $paymentDetails->txn_status = $verifyPayment->status;
    //         }
    //         $paymentDetails->order_id = $verifyPayment->mihpayid;
    //         $paymentDetails->paid_amount = $verifyPayment->amt;
    //         $paymentDetails->txn_date = $verifyPayment->addedon;
    //         $paymentDetails->save();
    //     	}

    //     }
    //     return $payment_success;
    // }

    public function insertPayment($exam_fee_id,$order_id,$exam_fee_amount){
				$data = ExamFee::find($exam_fee_id);
        if(!$data){
            return false;
        }
        $payment = ExamPayment::where('transaction_id',$order_id)
        	->where('roll_no',$data->roll_no)
        	->first();
        if($payment){
        	return false;
        }
        $payment = new ExamPayment;
        $payment->exam_fee_id = $exam_fee_id;
        $payment->roll_no = $data->roll_no;
        $payment->order_id = $order_id;
        $payment->transaction_id = $order_id;
        $payment->paid_amount = $exam_fee_amount;
        $payment->payment_mode = 'Online';
        $payment->txn_status = 'Initiated';
        $payment->txn_date = null;
        $payment->created_at = date('Y-m-d H:s:i');
        $payment->updated_at = date('Y-m-d H:s:i');
        $payment->save();
    }

    public function examFeesSave(Request $request){
		$data = ExamFee::find($request->id);
		$ApplicationPayment = ExamPayment::where('exam_fee_id',$request->id)->first();
		if(!$data){
            dd('Some Error Occurred!');
        }
        $this->updatePaymentBackPapers($data->id,$ApplicationPayment->order_id);
        if($request->status=='success'){
            return redirect('student/exam/pay-success?id='.$request->id)->with('success','Payment Done');
        }else{
            return redirect('student/exam/pay-success?id='.$request->id)->with('error','Payment Failed');
        }
    }

    public function paymentSlip(Request $request){
        $exam_fee_id = $request->id;
		$examFees = ExamFee::find($exam_fee_id);
		$paymentDetails_array = ExamPayment::where('exam_fee_id',$exam_fee_id)->orderBy('txn_date','DESC')->get();
        if($paymentDetails_array->count()==0){
            return back()->with('error','Some Error Occurred');
        }
        $download = $request->download;
        view()->share(compact('paymentDetails_array','download','examFees'));
        if($request->has('download')){
            $htmlfile = view('ums.exam.success_payment')->render();
            $pdf = app()->make('dompdf.wrapper');
            $pdf->loadHTML($htmlfile);
            return $pdf->download('Payment-Slip.pdf');
        }

        return view('ums.exam.success_payment');
    }


}

