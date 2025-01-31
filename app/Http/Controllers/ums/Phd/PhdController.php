<?php

namespace App\Http\Controllers\Phd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Country;
use App\Models\Campuse;
use App\Models\Category;
use App\Models\Subject;
use App\Models\PhdResult;
use App\Models\Application;
use App\Models\AcademicSession;
use App\Models\EntranceExam;
use App\Models\ApplicationPayment;
use App\Models\DisabilityCategory;
use App\Models\ApplicationAddress;
use App\Models\ApplicationEducation;
use App\Models\Phd2023Exam;
use Auth;
use DB;
use Validator;
use Mail;

use Illuminate\Support\Facades\Redirect;


class PhdController extends Controller
{

	public function admitcardportal(Request $request){
		return view('student.phdadmitcard.phd_login');
	}

	public function phdadmitcard_login(Request $request){
		// dd($request->all());
		$this->validate($request, [
			'registration_no'=>'required',
			'dob'=>'required',
			// 'application_number'=>'required',
			// 'g-recaptcha-response' => 'required|recaptchav3:admitcard,0.5'
		],
		[
			'g-recaptcha-response.required' => 'Google Captcha field is required.',
		]);
//		$request->application_number = (int)$request->application_number;
		if($request->registration_no){
			$result = PhdResult::where(['registration_no'=>$request->registration_no])->first();
			if($result){
				return redirect('phd-admitcard-view-result/'.$request->registration_no)->with('success','Result Find');
			}else{
				return back()->with('error','Invalid Registration Number');
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

	public function phdViewResult(Request $request){
		// dd($request->all());
		$result = PhdResult::where('registration_no',$request->id)->first()->toArray();
		if(!$result){
			return back()->with('error','Result not generated');
		}
		unset($result['id']);
		unset($result['correct_marks']);
		unset($result['wrong_marks']);
//		dd($result);
		return view('student.phdadmitcard.phd_admitcard_view_result', [
            'page_title' => "Admit Card",
            'sub_title' => "records",
			'results' => $result,
		]);
	}

	//Ph.D Admission form
	
	public function application_form_phd(Request $request){
		// $user=Auth::user()->id;
		// $application=Application::where('user_id',$user)->first();
		// if($application){
		// 	return redirect('dashboard')->with('error','Click Add More Course To Add New Application');
		// }
		$data['subjects'] = Subject::where('course_id',94)->get();
		$data['programm_types'] = Category::get();
		$data['disabilities'] = DisabilityCategory::get();
		$data['academic_sessions'] = AcademicSession::where('status','active')->get();
		$data['courses'] = [];
		$data['affiliated'] = Campuse::where('is_affiliated',1)->get();
		$data['colleges'] = Campuse::where('visible_in_admission',0)->get();
		//$data['subjects'] = Subject::all();
		//dd($data['affiliated']);
		$data['countries'] = Country::get();
		$data['course_id'] = $request->course_id;
		$data['programm_type_id'] = null;
		$data['courses'] = Course::where('id',94)->orderBy('id')->get();
		$data['course_single'] = Course::where('id',94)->first();
		if($request->course_id!=null){
			$data['programm_type_id'] = Course::join('categories', 'courses.category_id', '=', 'categories.id')->select('categories.id','categories.name')->where('courses.id',$request->course_id)->first()->id;
		//	dd($data['programm_type_id']);
		}
       	//dd($program['id']);
	   	$is_form_open = admission_open_couse_wise(94,1);
	   	if($is_form_open){
			return view('student.phdadmitcard.application-form-phd_start_code',$data);
	   	}else{
			return view('student.phdadmitcard.application-form-phd',$data);
		}
    }

    public function add_application_form_save(Request $request)
	{
		 // dd($request->all());
		$validator = Validator::make($request->all(), [
            'subject_id' => 'required',
            'academic_session' => 'required',
            'application_for' => 'required',
            //'affiliated_collage' => 'required_if:application_for,2',
            'course_type' => 'required',
            'course_id' => 'required',
            'student_first_Name' => 'required|alpha',
            'student_middle_Name' => 'nullable|alpha',
            'student_last_Name' => 'nullable|alpha',
            'date_of_birth' => 'required|date',
            'student_email' => 'required|email',
            'student_mobile' => 'required|numeric|digits:10',
            'father_name' => 'required',
            'mother_name' => 'required',
			// 'nominee_name' => 'required',
            'guardian_name' => 'required',
            'guardian_mobile' => 'required|numeric|digits:10',
            'domicile' => 'required',
            'domicile_cirtificate' => 'required_if:domicile,Uttar Pradesh|mimes:jpeg,png,jpg|max:512',
            'gender' => 'required',
            'category' => 'required',
            'caste_certificate_number' => 'required_if:category,SC,OBC,ST,EWS',
            'upload_caste_certificate' => 'required_if:category,SC,OBC,ST,EWS|mimes:jpeg,png,jpg|max:512',
			'nationality' => 'required',
            'religion' => 'required',
            'marital_status' => 'required',
            'dsmnru_employee' => 'required',
            'dsmnru_relation' => 'required_if:dsmnru_employee,yes',
            'dsmnru_employee_ward' => 'required',
            'ward_emp_name' => 'required_if:dsmnru_employee_ward,yes',
            'ward_emp_relation' => 'required_if:dsmnru_employee_ward,yes',
            'disability' => 'required',
            'disability_category' => 'required_if:disability,yes',
			'percentage_of_disability'=> 'required_with:disability_category',
			'upload_disability_certificate'=> 'required_with:disability_category|mimes:jpeg,png,jpg|max:512',
			//'udid_number'=> 'required_with:disability_category|nullable|regex:/^([a-zA-Z]){2}([0-9]){16}?$/',
			'udid_number'=> 'nullable|regex:/^([a-zA-Z]){2}([0-9]){16}?$/',
            // 'freedom_fighter_dependent' => 'required',
            // 'freedom_fighter_dependent_file' => 'required_if:freedom_fighter_dependent,yes|mimes:jpeg,png,jpg|max:512',
            // 'ncc' => 'required',
            // 'ncc_cirtificate' => 'required_if:ncc,yes|mimes:jpeg,png,jpg|max:512',
            // 'nss' => 'required',
            // 'nss_cirtificate' => 'required_if:nss,yes|mimes:jpeg,png,jpg|max:512',
            // 'sports' => 'required',
            // 'sport_level' => 'required_if:sports,yes',
            // 'sportt_cirtificate' => 'required_if:sports,yes|mimes:jpeg,png,jpg|max:512',

            // 'hostel_facility_required' => 'required',
            // 'hostel_for_years' => 'required_if:hostel_facility_required,yes',
            // 'distance_from_university' => 'required_if:hostel_facility_required,yes',

            'blood_group' => 'required',
            'address' => 'required',
            'district' => 'required',
            'police_station' => 'required',
            'nearest_railway_station' => 'required',
            'country' => 'required',
            'state_union_territory' => 'required',
            'pin_code' => 'required|numeric|digits:6',
            'correspondence_address' => 'required',
            'correspondence_district' => 'required',
            'correspondence_police_station' => 'required',
            'correspondence_nearest_railway_station' => 'required',
            'correspondence_country' => 'required',
            'correspondence_state_union_territory' => 'required',
            'correspondence_pin_code' => 'required|numeric|digits:6',
            'upload_photo' => 'required|image|max:100',
            'upload_signature' => 'required|image|max:100',
			'dsmnru_student'=> 'required',
			'enrollment_number'=> 'old_student|required_if:dsmnru_student,Yes',
            'is_agree' => 'required',
			'aiot_score'=>'required_if:course_id,11,26,27',
			//'aiot_rank'=>'required_if:course_id,11,26,27',
            // 'admission_through' => 'required_if:course_id,41,42,43,44,45',
            // 'jeee_date_of_examination' => 'required_if:admission_through,JEEE(MAIN)',
            // 'jeee_roll_number' => 'required_if:admission_through,JEEE(MAIN)',
            // 'jeee_score' => 'required_if:admission_through,JEEE(MAIN)',
            // 'jeee_merit' => 'required_if:admission_through,JEEE(MAIN)',
            // 'jeee_rank' => 'required_if:admission_through,JEEE(MAIN)',
            // 'upsee_date_of_examination' => 'required_if:admission_through,UPSEE',
            // 'upsee_roll_number' => 'required_if:admission_through,UPSEE',
            // 'upsee_score' => 'required_if:admission_through,UPSEE',
            // 'upsee_merit' => 'required_if:admission_through,UPSEE',
            // 'upsee_rank' => 'required_if:admission_through,UPSEE',
            /*'order_id' => 'required',
            'transaction_id' => 'required',
            'paid_amount' => 'required|numeric',
            'txn_date' => 'required|date',
            'txn_status' => 'required',*/

            'course_id' => 'required',
            'adhar_card_number' => 'required|numeric|digits:12',

			/*=====education code=====*/
            'name_of_exam.*' => 'required',
            'board.*' => 'required',
            'passing_status.*' => 'required',
            'passing_year.*' => 'nullable|numeric|digits:4',
            'total_marks_cgpa.*' => 'nullable',
            'cgpa_optain_marks.*' => 'nullable|numeric',
            'equivalent_percentage.*' => 'nullable',
            'subject.*' => 'nullable',
            'certificate_number.*' => 'nullable',
            'education_document.*' => 'nullable|mimes:jpeg,png,jpg|max:512',
            'cgpa_document.*' => 'nullable|mimes:jpeg,png,jpg|max:512',
//			'g-recaptcha-response' => 'required|recaptchav3:admitcard,0.5'
		],
		[
			'old_student' => 'Invalid Enrollment',
			// 'affiliated_collage.required_if' => 'Affiliated Collage Field is required.',
			// 'state_union_territory.required' => 'The state/union territory field is required.',
			// 'correspondence_state_union_territory.required' => 'The correspondence state/union territory field is required.',
			// 'g-recaptcha-response.required' => 'Google Captcha field is required.',
			// 'g-recaptcha-response.captcha' => 'Google Captcha field is required.',
			// 'aiot_score.required_if' => 'AIOT Score Required When Course is D.ed',
			// 'admission_through.required_if'=>'Addmission Through Required If Course is B.Tech',
			// 'jeee_date_of_examination.required_if'=>'Date Of Examination Required If JEE(Main) Selected',
   //          'jeee_roll_number.required_if'=>'Roll Number Required If JEE(Main) Selected',
   //          'jeee_score.required_if'=>'Score Required If JEE(Main) Selected',
   //          'jeee_merit.required_if'=>'Merit Required If JEE(Main) Selected',
   //          'jeee_rank.required_if'=>'Rank Required If JEE(Main) Selected',
   //          'upsee_date_of_examination.required_if'=>'Date Of Examination Required If UPSEE Selected',
   //          'upsee_roll_number.required_if'=>'Roll Number Required If UPSEE Selected',
   //          'upsee_score.required_if'=>'Score Required If UPSEE Selected',
   //          'upsee_merit.required_if'=>'Merit Required If UPSEE Selected',
   //          'upsee_rank.required_if'=>'Rank Required If UPSEE Selected',
		]); 

		if ($validator->fails()) {    
			return response()->json($validator->messages(), 200);
		}
		
		DB::beginTransaction();
		try {
				
				$old_application=Application::where(['user_id'=>Auth::user()->id,
													'course_id'=>$request->course_id,
													'application_for'=>$request->application_for])->first();
				if($old_application){
					$data['status'] = false;
					$data['message'] = 'You Already Applied For This Course!!!';
					return response()->json($data);
				}

				$applicationData=array(

					'academic_session'=>$request->academic_session,
					'application_for'=>$request->application_for,
					'campuse_id'=>$request->campus_id,
					'category_id'=>$request->course_type,
					'course_id'=>$request->course_id,
					'is_agree'=>$request->is_agree,
					'user_id'=> Auth::user()->id,
					'first_Name' => $request->student_first_Name,
					'last_Name' => $request->student_last_Name,
					'middle_Name' => $request->student_middle_Name,
					'date_of_birth' => $request->date_of_birth,
					'email' => $request->student_email,
					'mobile' => $request->student_mobile,
					'father_first_name' => $request->father_name,
					'mother_first_name' => $request->mother_name,
					'nominee_first_name' => $request->nominee_name,
					'guardian_first_name' => $request->guardian_name,
					'guardian_mobile' => $request->guardian_mobile,
					'domicile'=>$request->domicile,
					'dsmnru_student'=>$request->dsmnru_student,
					'enrollment_number'=>$request->enrollment_number,
					// 'sport_level'=>$request->sport_level,
					// 'hostel_facility_required'=>$request->hostel_facility_required,
					// 'hostel_for_years'=>$request->hostel_for_years,
					// 'distance_from_university'=>$request->distance_from_university,

					// 'aiot_rank'=>$request->aiot_rank,
					// 'aiot_score'=>$request->aiot_score,
					//'jee_score'=>$request->jee_score,
					// 'udid_number'=>$request->udid_number,
					'disability'=>$request->disability,
					'percentage_of_disability'=>$request->percentage_of_disability,
					// 'admission_through'=>$request->admission_through,
					// 'date_of_examination'=>($request->jeee_date_of_examination)?$request->jeee_date_of_examination:$request->upsee_date_of_examination,
					// 'roll_number'=>($request->jeee_roll_number)?$request->jeee_roll_number:$request->upsee_roll_number,
					// 'score'=>($request->jeee_score)?$request->jeee_score:$request->upsee_score,
					// 'merit'=>($request->jeee_merit)?$request->jeee_merit:$request->upsee_merit,
					// 'rank'=>($request->jeee_rank)?$request->jeee_rank:$request->upsee_rank,
					'gender'=>$request->gender, 
					'dsmnru_relation'=>$request->dsmnru_relation, 
					'category'=>$request->category,
					'certificate_number' => $request->caste_certificate_number,
					'nationality'=>$request->nationality,
					'religion'=>$request->religion,
					'marital_status'=>$request->marital_status,
					// 'sub_category'=>$request->sub_category,
					'dsmnru_employee' =>$request->dsmnru_employee ,
					'dsmnru_employee_ward' => $request->dsmnru_employee_ward,
					'ward_emp_name' => $request->ward_emp_name,
					'ward_emp_relation' => $request->ward_emp_relation,

					'disability_category' => $request->disability_category,
					// 'freedom_fighter_dependent' => $request->freedom_fighter_dependent,
					// 'ncc'=>$request->ncc ,
					// 'nss' =>$request->nss,
					// 'sports'=>$request->sports ,
					//'weightage'=>$weightage,
					'blood_group'=>$request->blood_group,
					'adhar_card_number'=>$request->adhar_card_number,
					'subject_id'=>$request->subject_id,
				);

				$application = new Application;
				$application->fill($applicationData);
				$application->save();
				$application->application_no = "DSMNRU/REQ/".$application->id;
				$application->status = "pending";
				if($application->application_for=='1'){
					$application->campuse_id=1;
				}
				$application->save();

				if($request->upload_photo){
					$application->addMediaFromRequest('upload_photo')->toMediaCollection('photo');
				}
				
				if($request->upload_signature){
					$application->addMediaFromRequest('upload_signature')->toMediaCollection('signature');
				}
				
				if($request->upload_adhar){
					$application->addMediaFromRequest('upload_adhar')->toMediaCollection('aadharcards');
				}
				
				if($request->caste_certificate){
					$application->addMediaFromRequest('caste_certificate')->toMediaCollection('caste_certificate');
				}
				
				if($request->disability_certificate){
					$application->addMediaFromRequest('disability_certificate')->toMediaCollection('disability_certificate');
				}
				
				if($request->income_certificate){
					$application->addMediaFromRequest('income_certificate')->toMediaCollection('income_certificate');
				}
				
				if($request->any_other){
					$application->addMediaFromRequest('any_other')->toMediaCollection('any_other');
				}

				if($request->freedom_fighter_dependent_file){
					$application->addMediaFromRequest('freedom_fighter_dependent_file')->toMediaCollection('freedom_fighter_dependent_file');
				}

				if($request->ncc_cirtificate){
					$application->addMediaFromRequest('ncc_cirtificate')->toMediaCollection('ncc_cirtificate');
				}

				if($request->nss_cirtificate){
					$application->addMediaFromRequest('nss_cirtificate')->toMediaCollection('nss_cirtificate');
				}

				if($request->sportt_cirtificate){
					$application->addMediaFromRequest('sportt_cirtificate')->toMediaCollection('sportt_cirtificate');
				}

				if($request->domicile_cirtificate){
					$application->addMediaFromRequest('domicile_cirtificate')->toMediaCollection('domicile_cirtificate');
				}

				if($request->upload_caste_certificate){
					$application->addMediaFromRequest('upload_caste_certificate')->toMediaCollection('upload_caste_certificate');
				}

				if($request->upload_disability_certificate){
					$application->addMediaFromRequest('upload_disability_certificate')->toMediaCollection('upload_disability_certificate');
				}
				

				
				/*$paymentData=array(
					'order_id'=>$request->order_id,
					'transaction_id'=>$request->transaction_id,
					'paid_amount'=>$request->paid_amount,
					'txn_date'=>$request->txn_date,
					'txn_status'=>$request->txn_status,
					'application_id'=> $application->id
				);
				
				$applicationPayment = new ApplicationPayment();
				$applicationPayment->fill($paymentData);
				$applicationPayment->save();*/
				foreach($request->name_of_exam as $key=>$val){
					$passing_status_key = ($key+1);
					$applicationEducation = new ApplicationEducation();
					$applicationEducation->name_of_exam = $request->name_of_exam[$key];
					$applicationEducation->board = $request->board[$key];
					$passing_status = 'passing_status'.$passing_status_key;
					$applicationEducation->passing_status = $request->$passing_status;
					if(!$applicationEducation->passing_status){
						$applicationEducation->passing_status = 1;
					}
					if($request->$passing_status==1 || $request->board[$key]=='PHD'){
						$applicationEducation->passing_year = $request->passing_year[$key];
						$cgpa_or_marks = 'cgpa_or_marks'.$passing_status_key;
						$applicationEducation->cgpa_or_marks = $request->$cgpa_or_marks;
						if(!$applicationEducation->cgpa_or_marks){
							$applicationEducation->cgpa_or_marks = 1;
						}
						$applicationEducation->total_marks_cgpa = $request->total_marks_cgpa[$key];
						$applicationEducation->cgpa_optain_marks = $request->cgpa_optain_marks[$key];
						$applicationEducation->equivalent_percentage = round($request->equivalent_percentage[$key],2);
						$applicationEducation->subject = $request->subject[$key];
						$applicationEducation->degree_name = $request->degree_name[$key];
						$applicationEducation->certificate_number = $request->certificate_number[$key];
						if(isset($request->education_document[$key]) && $request->education_document[$key]) {
							$applicationEducation->addMediaFromRequest("education_document[$key]")->toMediaCollection('doc');
						}
						if(isset($request->cgpa_document[$key]) && $request->cgpa_document[$key]) {
							$applicationEducation->addMediaFromRequest("cgpa_document[$key]")->toMediaCollection('cgpa_document');
						}
					}
					$applicationEducation->application_id = $application->id;
					$applicationEducation->save();
				}

				$permanentAddressData=array(
					'address'=>$request->address,
					'address_type'=>'permanent',
					'district'=>$request->district,
					'police_station'=>$request->police_station,
					'nearest_railway_station'=>$request->nearest_railway_station,
					'country'=>$request->country,
					'state_union_territory'=>$request->state_union_territory,
					'pin_code'=>$request->pin_code,
					'application_id'=> $application->id
				);

				$address = new ApplicationAddress();
				$address->fill($permanentAddressData);
				$address->save();

				$correspondenceAddressData=array(
					'address'=>$request->correspondence_address,
					'address_type'=>'correspondence',
					'district'=>$request->correspondence_district,
					'police_station'=>$request->correspondence_police_station,
					'nearest_railway_station'=>$request->correspondence_nearest_railway_station,
					'country'=>$request->correspondence_country,
					'state_union_territory'=>$request->correspondence_state_union_territory,
					'pin_code'=>$request->correspondence_pin_code,
					'application_id'=> $application->id
				);

				$address = new ApplicationAddress();
				$address->fill($correspondenceAddressData);
				$address->save();

				$data['status'] = true;
				$data['message'] = 'Data Saved Succesfully';
				$data['application_id'] = $application->id;

				DB::commit();
				return response()->json($data);
		} catch (\Exception $e) {
			DB::rollback();
			$data['status'] = false;
			$data['message'] = $e->getmessage();
			return response()->json($data);
		}
		}


	public function phdApplicationFormView(Request $request){
		$user=Auth::user();
		$application = Application::where(['user_id'=>$user->id,'id'=>$request->application_id])->first();
		$data['program'] = Category::find($application->category_id);
		$data['course'] = Course::find($application->course_id);
		$data['subject'] = Subject::find($application->subject_id);
		$data['application']=$application;
		$data['applicationPayment']=ApplicationPayment::where('application_id',$request->application_id)->first();
		$data['applicationEducation'] = ApplicationEducation::where('application_id',$request->application_id)->get();
		$data['permanent_address']=ApplicationAddress::where(['application_id'=>$request->application_id,'address_type'=>'permanent'])->first();
		$data['correspondence_address']=ApplicationAddress::where(['application_id'=>$request->application_id,'address_type'=>'correspondence'])->first();
		return view('student.phdadmitcard.phd_application_form_view',$data);
	}

	public function payNow(Request $request){
		if($request->application_id){
			$data['applicationId'] = $request->application_id;
		}
		if($request->id){
			$data['applicationId'] = $request->id;
		}
	
        return view('student.phdadmitcard.phd_offline_pay',$data);
    }

    public function phdPaymentSave(Request $request){
		// dd($request->all());
		if($request->payment_mode=='offline'){
				
		$validator = Validator::make($request->all(), [
            'bank_name' => 'required',
			'bank_ifcs'=>'required',
//			'transaction_id'=>'required|unique:scrutinies,challan_number',
			'transaction_id'=>'required',
			'amount'=>'required',
			'challan'=>'required',
			'payment_datetime'=>'required',
			// 'order_id'=>'required|unique:scrutinies,order_id',
			]);
			if ($validator->fails()) {    
            return back()->withErrors($validator)->withInput();
        }
	}

		$application = Application::find($request->application_id);
		$application->payment_status = 1;
		$application->save();

		$phdPayment = new ApplicationPayment();
			if($request->payment_mode=='offline'){
			$phdPayment->application_id = $request->application_id;
			$phdPayment->order_id = 10101010;
			$phdPayment->transaction_id = $request->transaction_id;
			$phdPayment->paid_amount = $request->amount;
			$phdPayment->bank_name = $request->bank_name;
			$phdPayment->payment_mode = $request->payment_mode;
			$phdPayment->bank_ifsc_code = $request->bank_ifcs;
			$phdPayment->txn_date = $request->payment_datetime;
			$phdPayment->txn_status = $request->response_message;
			if($request->challan)
				{
				$phdPayment->addMediaFromRequest('challan')->toMediaCollection('challan');
				}
			$phdPayment->save();
		}
				return redirect('dashboard')->with('success','Payment Done');

		// if($request->payment_mode=='offline'){
		// 	if($scrutiny_update->form_type==1){
		// 	}else
		// 	return redirect('scrutiny-form-view/'. $scrutiny_update->roll_no);
		// }
  //       return redirect('challenge-pay-success?id='.$scrutiny_update->id)->with('success','Payment Done');
    }


	public function phd_2023_exam(Request $request){
		if($request->id && $request->final_submit=='true'){
			$student = Phd2023Exam::find($request->id);
			if($student){
				$student->final_submit=1;
				$student->save();
				return back()->with('success','Exam form is finally submitted.');
			}
		}
		$facultys = Phd2023Exam::distinct('faculty')->pluck('faculty')->toArray();
		$departments = Phd2023Exam::where('faculty',$request->faculty)
		->distinct('department_name')
		->pluck('department_name')
		->toArray();
		$enrollments = Phd2023Exam::where('faculty',$request->faculty)
		->where('department_name',$request->department)
		->distinct('enrollment_number')
		->pluck('enrollment_number')
		->toArray();
		$student = Phd2023Exam::find(base64_decode($request->id));
		return view('student.phdadmitcard.phd_2023_exam',compact('facultys','departments','enrollments','student'));
	}

	public function phd_2023_exam_admitcard(Request $request){
		$AdmitCard = Phd2023Exam::where('email',$request->email)->first();
		if(!$AdmitCard && $request->email){
			return back()->with('error','Invalid Email ID.');
		}
		return view('student.phdadmitcard.phd_2023_exam_admitcard',compact('AdmitCard'));
	}

	public function phd_2023_exam_send_email(Request $request){
		if(!$request->email){
			return back()->with('error','Please provide email ID.');
		}
		$student = Phd2023Exam::where('email',$request->email)->first();
		if(!$student){
			return back()->with('error','Invalid Email ID.');
		}
		$email = $student->email;
		$name = $student->student_name;
		$subject = 'Ph.D. Course Work Exam';
		Mail::send( ['html' => 'email.phd-exam-2023'], ['id' => $student->id], function ($message) use ($email,$name,$subject){
			// $message->from(env('MAIL_DEFAULT_FROM', ''), 'Registration');
			$message->to($email, $name)->subject($subject);
		});
		return back()->with('success','Email Send Successfully.');
	}

	public function phdStudentPhotoSignateure(Request $request){
		// dd($request->all());
		$student = Phd2023Exam::find($request->student_id);
		if($request->upload_photo) {
			$student->addMediaFromRequest('upload_photo')->toMediaCollection('photo');
		}
		if($request->upload_signature){
			$student->addMediaFromRequest('upload_signature')->toMediaCollection('signature');
		}
		return back()->with('message','Uploaded Successfully.');
	}
    
	
}

