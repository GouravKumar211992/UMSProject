<?php


namespace App\Http\Controllers\ums\Student;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campuse;
use App\Models\ums\Course;
use App\Models\ums\ExamFee;
use App\Models\ums\ExamForm;
use App\Models\ums\ExamCenter;
use App\Models\ums\Subject;
use App\Models\ums\AdmitCard;
use App\models\ums\Application as UmsApplication;
use App\Models\ums\Examschedule;
use App\Models\ums\Student;
use App\Models\ums\BacklogAdmitcard;
use App\Models\ums\PhdApplication;
use App\Models\ums\ScribeDetail;
use App\Models\ums\PhdScribeDetail;
use App\Models\ums\MbbsExamForm;
use App\Models\ums\EntranceExam;
use Illuminate\Support\Facades\DB;


use App\Models\ums\EntranceExamResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


use App\Models\Application;
use Illuminate\Support\Facades\Redirect;
use App\Traits\ResultsTrait;

use App\Models\Phd2023Exam;

class AdmitCardController extends Controller
{
	use ResultsTrait;

    public function index(Request $request)
    {
      $examfee = ExamFee::where('id',$request->id)->first();
	  
	  if(!$examfee){
		  return back()->with('error','Invalid Exam ID.');
		}
		if($examfee->bank_name==null){
			return back()->with('error','Payment Not Done.');
		}
		// if($examfee->payment() && $examfee->payment()->txn_status=='SUCCESS'){
			// }else{
				// 	return redirect('exam-form/payment/'.$examfee->id)->with('error','Please Fill Your Fees Details');
				// }
				$student_details = Student::where('roll_number',$examfee->roll_no)->first();
				$examData_photo = $examfee;
				$AdmitCard = AdmitCard::has('center')->where('exam_fees_id',$examfee->id)
				->orderBy('id','desc')
				->first();
				$subjects = [];
				if($AdmitCard){
					$subjects = $examfee->getAdmitCardSubjects();
				}
				$page_title = "Admit Card";
				$sub_title = "Records";
				$sessionName = $this->sessionName($examfee->semester,$examfee->academic_session);
				if($examfee->course_id==49 || $examfee->course_id==64 || $examfee->course_id==95 || $examfee->course_id==96){
					
					// dd($AdmitCard);
					// dd($examfee->id);
			return view('ums.exam.mbbs-admitcard-page', compact('page_title','sub_title','examfee','subjects','AdmitCard','student_details','sessionName'));
		}else{
			return view('ums.exam.admitcardview', compact('page_title','sub_title','examfee','subjects','AdmitCard','student_details','sessionName','examData_photo'));

		}
    }

	public function admitcardDownloadList(Request $request){
		$current_session = accademic_session();
		$previous_session = previous_session($current_session);
		$examData = ExamFee::join('admit_cards','admit_cards.exam_fees_id','exam_fees.id')
		->select('exam_fees.*')
		->where('exam_fees.roll_no',$request->id)
		->where(function($query) use ($current_session,$previous_session){
			$query->where('academic_session','LIKE',$current_session.'%')
			->orWhere('academic_session','LIKE',$previous_session.'%');
		})
		->where(function($query){
			$query->whereNotNull('bank_name')
			->where('bank_name','!=','');
		})
		->orderBy('exam_fees.id','DESC')
		->get();
		return view('ums.student.admitCardList', compact('examData'));
	}

	public function application_data_edit(Request $request){

		return view('student.admitcard.application-data-update');
	}

	public function application_data_update(Request $request){
		$exam = ExamFee::find($request->id);
		$exam->media()->delete();
		if($request->upload_photo){
			$exam->addMediaFromRequest('upload_photo')->toMediaCollection('photo');
		}
		
		if($request->upload_signature){
			$exam->addMediaFromRequest('upload_signature')->toMediaCollection('signature');
		}
		return Redirect::to('admitcard-download?id='.$request->enrollment_no);
//		return redirect('admitcard-download',[$request->enrollment_no]);
	}

	public function admitcardportal(Request $request){

		return view('student.admitcard.admit-card-portal');
	}
	public function admitcardportal_login(Request $request){
		//dd($request->all());
		if($request->roll_no){
			$this->validate($request, [
				'roll_no'=>'required',
				'form_type'=>'required',
				'g-recaptcha-response' => 'required|captcha',
			],
			[
				'g-recaptcha-response.required' => 'Google Captcha field is required.',
			]); 
			$check_roll=BacklogAdmitcard::where(['roll_no'=>$request->roll_no])->first();
			if($check_roll){
				//dd($check_roll->dob,$request->dob);
				$check_dob=BacklogAdmitcard::where(['dob'=>$request->dob])->first();
				//dd($check_dob);
				if($check_dob){
					$admitcard=BacklogAdmitcard::where(['roll_no'=>$request->roll_no,'dob'=>$request->dob,'form_type'=>$request->form_type])->orderBy('id','DESC')->first();
					//dd($admitcard);
					$subjects = [];
        if($admitcard){
           $subjects = Subject::whereIn('sub_code',explode('#',$admitcard->sub_code))->get();
        }
					return view('student.admitcard.admitcard-portal-view', [
            'page_title' => "Admit Card",
            'sub_title' => "records",
			'subjects' => $subjects,
			'AdmitCard' => $admitcard,
					]);
					
				}else{
					return back()->with('error','Incorrect Date Of Birth');
				}
			}else{
				return back()->with('error','Your Mobile Number Not Found');
			}
			
		}
		return view('student.admitcard.admit-card-portal');
	}
	public function scribe(Request $request){
		//dd($request->all());
		$subjects=[];
		$examfee = ExamFee::find($request->id);
		$scribedetails = ScribeDetail::where('admitcard_id',$examfee->admitcard->id)->first();
		 if($examfee){
           $subjects = $examfee->getAdmitCardSubjects();
        }
		return view('ums.exam.scribeadmitcard', [
            'page_title' => "Scribe Admit Card",
            'sub_title' => "records",
			'subjects' => $subjects,
			'AdmitCard' => $scribedetails,
			'scribe' => $scribedetails,
			'examfee' => $examfee,
		]);
	}
	public function phdadmitcard_login(Request $request){
		//dd($request->all());
		$this->validate($request, [
			//'mobile_no'=>'required',
			//'dob'=>'required',
			'application_number'=>'required',
			'g-recaptcha-response' => 'required|recaptchav3:admitcard,0.5'
		],
		[
			'g-recaptcha-response.required' => 'Google Captcha field is required.',
		]);
//		$request->application_number = (int)$request->application_number;
		if($request->application_number){
			$result = EntranceExamResult::where(['application_number'=>$request->application_number])->first();
			if($result){
				return redirect('admitcard-view-result/'.$request->application_number)->with('success','Result Find');
			}else{
				return back()->with('error','Invalid Application Number');
			}
		}
			if($request->mobile_no){
			//dd($request->mobile_no);
			//$check_mobile_no=PhdApplication::where(['student_mobile'=>$request->mobile_no])->first();
			$check_mobile_no=EntranceExam::where(['student_mobile'=>$request->mobile_no])->first();
			if($check_mobile_no){//date('Y-m-d', $date);
				//dd($check_mobile_no->dob,$request->dob);
				//$check_dob=PhdApplication::where(['student_mobile'=>$request->mobile_no,'dob'=>date('d-m-Y',strtotime($request->dob))])->first();
				$check_dob=EntranceExam::where(['student_mobile'=>$request->mobile_no,'dob'=>date('d-m-Y',strtotime($request->dob))])->first();
				//dd($check_dob);
				if($check_dob){
				$check_post=EntranceExam::where(['student_mobile'=>$request->mobile_no,'dob'=>date('d-m-Y',strtotime($request->dob)),'post_name'=>$request->post_type])->first();
					//return view('student.admitcard.admitcard-view',['check_dob'=>$check_dob]);
					if($check_post){
					return redirect('entrance-admitcard?id='.$check_post->registration_no);	
					}else{
						return back()->with('error','Incorrect Post');
					}			
					//return redirect('phd-entrance-admitcard?id='.$check_dob->registration_no);				
				}else{
					return back()->with('error','Incorrect Date Of Birth');
				}
			}else{
				return back()->with('error','Your Mobile Number Not Found');
			}
			
		}
		return view('student.admitcard.admit-card-portal');
	}
	public function phd_admitcard(Request $request){
		$admitcard = UmsApplication::join('phd_2023_entrance_test','phd_2023_entrance_test.application_no','applications.application_no')
		->select('applications.*',DB::raw('phd_2023_entrance_test.roll_number as entrance_roll_number'),DB::raw('phd_2023_entrance_test.subject as subject'))
		->where('phd_2023_entrance_test.roll_number',$request->roll_number)
		->first();
		$scribe = null;
		if($admitcard){
			$scribe = PhdScribeDetail::where('phd_applications_id', $admitcard->id )->first();
		}
		// return view('student.admitcard.admitcardview1', [
        //     'page_title' => "Admit Card",
        //     'sub_title' => "records",
		// 	'AdmitCard' => $admitcard,
		// 	'scribe' => $scribe,
		// ]);
		return view('student.admitcard.phdadmitcard2023', [
            'page_title' => "Admit Card",
            'sub_title' => "records",
			'AdmitCard' => $admitcard,
			'scribe' => $scribe,
		]);
	}

	public function phdEntranceAdmitcard2023(Request $request){
		if(!$request->email){
			return back()->with('error','Please provide Application Number');
		}
		$application = UmsApplication::join('phd_2023_entrance_test','phd_2023_entrance_test.application_no','applications.application_no')
		->select('applications.*',DB::raw('phd_2023_entrance_test.roll_number as entrance_roll_number'),DB::raw('phd_2023_entrance_test.subject as subject'))
		->where('applications.email',$request->email)
		->where('applications.course_id','94')
		->first();
		if(!$application){
			return back()->with('error','Please provide Valid Application Number');
		}
		return redirect('phd-entrance-admitcard?roll_number='.$application->entrance_roll_number);
	}

	public function entrance_admitcard(Request $request){
		//dd($request->all());
		$AdmitCards=null;
		$admitcard = EntranceExam::where('registration_no',$request->id)->first();
		if(!$admitcard){
			return back()->with('error','Invalid Registration No.');
		}
		return view('student.admitcard.admitcard-view', [
            'page_title' => "Admit Card",
            'sub_title' => "records",
			'AdmitCard' => $admitcard,
			'AdmitCards' => $AdmitCards,
		]);
	}
	public function admitcard_view_result(Request $request){
		$result = EntranceExamResult::where('application_number',$request->id)->first()->toArray();
		if(!$result){
			return back()->with('error','Result not generated');
		}
		unset($result['id']);
		unset($result['correct_marks']);
		unset($result['wrong_marks']);
//		dd($result);
		return view('student.admitcard.admitcard-view-result', [
            'page_title' => "Admit Card",
            'sub_title' => "records",
			'results' => $result,
		]);
	}


	public function phdScribeForm(Request $request){
		$admitcard = PhdApplication::where('id',$request->id)->first();
		$scribe = null;
		if($admitcard){
			$scribe = PhdScribeDetail::where('phd_applications_id', $request->id )->first();
		}else{
			return back()->with('error','Some Error Occurred.');
		}
		$data['admitcard'] = $admitcard;
		$data['scribe'] = $scribe;
		 if($scribe){
			$data['AdmitCard'] = $admitcard;
           return view('student.admitcard.phd-scribe-admitcard',$data);
        }else{
           return view('student.admitcard.phd-scribe-form',$data);
		}
	}

	public function phdScribeFormSave(Request $request){
		$this->validate($request, [
			'name'=>'required',
			'qualification'=>'required',
		]); 

		$scribe = PhdScribeDetail::where('phd_applications_id', $request->id )->first();
		if($scribe){
			return back()->with('error','Form fill already');
		}
		$scribe = new PhdScribeDetail;
		$scribe->phd_applications_id = $request->id;
		$scribe->name = $request->name;
		$scribe->qualification = $request->qualification;
		if($request->scriber_photo){
		  $scribe->addMediaFromRequest('scriber_photo')->toMediaCollection('scriber_photo');
		}

		if($request->scriber_signature){
		  $scribe->addMediaFromRequest('scriber_signature')->toMediaCollection('scriber_signature');
		}
		if($request->qualification_certificate){
		  $scribe->addMediaFromRequest('qualification_certificate')->toMediaCollection('qualification_certificate');
		}
		if($request->disability_certificate){
		  $scribe->addMediaFromRequest('disability_certificate')->toMediaCollection('disability_certificate');
		}

		$scribe->save();
		return back()->with('success','Form filled successfully');
	}
	
	
	//Scribe Form//
	
	public function ScribeForm(Request $request, $slug_id){
		$sign = ExamFee::find($slug_id);
		$admitcard = AdmitCard::where('exam_fees_id',$slug_id)->orderBy('id','desc')->first();
		$scribe = null;
		$subjects=null;
		if($admitcard){
			$scribe = ScribeDetail::where('admitcard_id', $admitcard->id )->first();
		}
		$data['admitcard'] = $admitcard;
		$data['scribe'] = $scribe;
		$data['sign']=$sign;
		$data['examfee']=$sign;
		$data['sessionName'] = $this->sessionName($sign->semester,$sign->academic_session);
		if($scribe){
			$data['AdmitCard'] = $admitcard;
			$subjects = $sign->getAdmitCardSubjects();
			$data['subjects'] = $subjects;
           return view('student.admitcard.scribeadmitcard',$data);
        }else{
           return view('student.admitcard.scribe-form',$data);
		}
	}

	public function ScribeFormSave(Request $request){
		// dd($request->all(),$request->id);
		$this->validate($request, [
			'name'=>'required',
			'qualification'=>'required',
		]); 

		$scribe = ScribeDetail::where('admitcard_id', $request->id )->first();
		if($scribe){
			return back()->with('error','Form fill already');
		}
		$scribe = new ScribeDetail;
		$scribe->admitcard_id = $request->id;
		$scribe->enrollment_no = $request->enrollment_no;
		
		$scribe->name = $request->name;
		$scribe->qualification = $request->qualification;
		if($request->scriber_photo){
		  $scribe->addMediaFromRequest('scriber_photo')->toMediaCollection('scriber_photo');
		}

		if($request->scriber_signature){
		  $scribe->addMediaFromRequest('scriber_signature')->toMediaCollection('scriber_signature');
		}
		if($request->qualification_certificate){
		  $scribe->addMediaFromRequest('qualification_certificate')->toMediaCollection('qualification_certificate');
		}
		if($request->disability_certificate){
		  $scribe->addMediaFromRequest('disability_certificate')->toMediaCollection('disability_certificate');
		}

		$scribe->save();
		return back()->with('success','Form filled successfully');
	}
	public function answer_key($slug){
		$admitcard = EntranceExam::where('registration_no',$slug)->first();
		return view('student.answer-key.answer-key', [
            'page_title' => "Answer Key",
            'sub_title' => "records",
			'AdmitCard' => $admitcard,
		]);
	}

}

