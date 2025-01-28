<?php

namespace App\Http\Controllers\ums\User;

use App\Http\Controllers\Controller;
use App\Models\ums\Application;
use App\Models\ums\ApplicationAddress;
use App\Models\ums\ApplicationEducation;
use App\Models\ums\ApplicationPayment;
use App\Models\ums\Notification;
use App\models\ums\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\ums\Category;
use App\Models\ums\AcademicSession;
use App\Models\ums\Country;
use App\Models\ums\City;
use App\Models\ums\Course;
use App\Models\ums\CourseFee;
use App\Models\ums\State;
use Hash;
use App\Models\ums\Icard;
use App\Models\ums\Result;

use App\Models\ums\Student;
use App\Models\ums\Enrollment;
use App\Models\ums\Stream;
use App\Models\ums\Campuse;
use App\Models\ums\Subject;
use App\Models\ums\Semester;
use App\Models\ums\CastCategory;
use App\Models\ums\StudentSubject;
use App\Models\ums\StudentSemesterFee;
use App\Models\ums\DisabilityCategory;
use App\Models\ums\EntranceExamAdmitCard;
use App\Models\ums\ExamFee;
use Spatie\MediaLibrary\Models\Media;

use Illuminate\Support\Facades\Auth as FacadesAuth;

class HomeController extends Controller
{

	// ye maine banaya hai 
	public function Userdashboard(Request $request)
{
    
    $dashboardData = $this->dashboard($request);

   
    $profileData = $this->profile($request);

	$notice =$this->showNotification();

  
    return view('ums.usermanagement.users.user_dashboard', [
        'lastApplication' => $dashboardData['lastApplication'],
        'applications' => $dashboardData['applications'],
        'subjectList' => $dashboardData['subjectList'],
        'total_non_disabled_fees' => $dashboardData['total_non_disabled_fees'],
        'total_disabled_fees' => $dashboardData['total_disabled_fees'],
        'student_semster' => $dashboardData['student_semster'],
        'course_type' => $dashboardData['course_type'],
        'section' => 'user-dashboard',
        'data' => $profileData['data'],
        'address' => $profileData['address'],
		'noticedata'=>$notice['data'],
		'notifications' => $notice['notifications'],
    ]);
}





    public function dashboard(Request $request){
		$this->profile($request);
			$this->showNotification();
		// Auth::guard('admin')->check() && Auth::guard('admin')->user()->role!=1
		if(true){
			Auth::logout();
			return redirect('admission-portal')->with('error','Please login first');
		}

		$subjectList = $total_non_disabled_fees = $total_disabled_fees = $student_semster = '';
		$user = Auth::user();
		$lastApplication = Application::where('user_id', $user->id)
			->orderBy('counselling_date', 'DESC')
			->orderBy('id', 'DESC')
			->with(['course', 'categories'])
			->first();
		$applications = Application::where('user_id', $user->id)
			->orderBy('id', 'DESC')
			->with(['course', 'categories'])
			->get();

			//dd($lastApplication);
			if($lastApplication){
			$student_semster=StudentSemesterFee::where('enrollment_no',$lastApplication->application_no)->first();
			//dd($lastApplication->course_id );
			$subjectList=Subject::select('subjects.*')->join('semesters','semesters.id','subjects.semester_id')
							->where(['subjects.program_id'=>$lastApplication->category_id,'subjects.course_id'=>$lastApplication->course_id,'semesters.semester_number'=>1])->get();
							//dd($subjectList,$lastApplication->category_id,$lastApplication->course_id,$lastApplication);
			$coursefee=CourseFee::where('course_id',$lastApplication->course_id)->get();
			$total_non_disabled_fees = $coursefee->sum('non_disabled_fees');
			$total_disabled_fees = $coursefee->sum('disabled_fees');
			}
			
			//dd($student_semster);
        return[
			'lastApplication' => $lastApplication,
			'applications' => $applications,
			'subjectList' => $subjectList,
			'total_non_disabled_fees'=>$total_non_disabled_fees,
			'total_disabled_fees'=>$total_disabled_fees,
			'student_semster'=>$student_semster,
			'course_type'=>$user->course_type,
			'section' => 'user-dashboard'
		];
		
		
    }

    public function profile(Request $request){
		$user_data = Auth::user();
		$data = ['user_data' => $user_data,'application' => array()];
		$application = Application::where('user_id',$user_data->id)->orderBy('id', 'DESC')->first();
		if($application != null){
		$address = ApplicationAddress::where('application_id',$application->id)->first();

		$data['user_data'] = $user_data;
		$data['application'] = $application;

		$combineAddress = '';
		if($address){
			$combineAddress .= $address->address .' PS: '.$address->police_station;
			$combineAddress .= " Distt. ".$address->district;
			$combineAddress .= " Pincode: ".$address->pin_code;
			$combineAddress .= " State: ".$address->state_union_territory;
		}
dd($data);
        return  [
			'section' => 'user-profile',
			'data'=>$data,
			'address'=>$combineAddress
		];
		}
		
    }
	public function showNotification()
    {
		$data['user_data']=Auth::user();
		$data['application']=Application::where('email',$data['user_data']->email)->orderBy('id', 'DESC')->first();
		$notification= Notification::all();
    	//$data['notification']=$notification;
		return ['data'=>$data,'notifications'=>$notification, ];
    }

    public function applications(Request $request){
		$user = Auth::user();
		$applications = Application::where('user_id', $user->id)
			->orderBy('id', 'DESC')
			->with(['course', 'categories'])
			->get();

        return view('frontend.user.applications',[
			'applications' => $applications,
			'section' => 'user-applications'
		]);
    }

    public function application(Request $request){
        return view('frontend.index.application');
    }

    public function application_form(Request $request){
		if(Auth::user()->course_type==1){
			return redirect('application-form-phd');
		}
		if($request->course_id && admission_open_couse_wise($request->course_id,1)==false){
			return back()->with('error','You are not allowed to apply for the course');
		}
		$user=Auth::user()->id;
		$application=Application::where('user_id',$user)->first();
		if($application){
			// return redirect('dashboard')->with('error','Click Add More Course To Add New Application');
		}
		$data['programm_types'] = Category::get();
		$data['disabilities'] = DisabilityCategory::get();
		$data['academic_sessions'] = AcademicSession::where('status','active')->get();
		$data['courses'] = Course::where('id',$request->course_id)->orderBy('id')->get();
		$data['course_single'] = Course::where('id',$request->course_id)->first();
		$data['affiliated'] = Campuse::where('is_affiliated',1)->get();
		$data['colleges'] = Campuse::where('visible_in_admission',0)->get();
		$data['countries'] = Country::get();
		$data['course_id'] = $request->course_id;
		$data['programm_type_id'] = null;
		if($request->course_id!=null){
			$data['programm_type_id'] = Course::join('categories', 'courses.category_id', '=', 'categories.id')->select('categories.id','categories.name')->where('courses.id',$request->course_id)->first()->id;
		}
	   return view('frontend.index.application-form',$data);
    }

    public function education_single_row(Request $request){
    	$user=Auth::user();
		$data['next_rows'] = ($request->rows+1);
		$returnHTML = view('frontend.index.application-form.education-single-row')->with($data)->render();
		return response()->json(array('success' => true, 'html'=>$returnHTML));
}

    public function applicationSave(Request $request)
	{
		$btech_course = array(114,133,134);
		$validator = Validator::make($request->all(), [
            'academic_session' => 'required',
            'application_for' => 'required',
            //'affiliated_collage' => 'required_if:application_for,2',
            'course_type' => 'required',
            'course_id' => 'required',
            'lateral_entry' => 'required',
            'course_preferences' => 'required',
            'student_first_Name' => 'required|alpha',
            'student_middle_Name' => 'nullable|alpha',
            'student_last_Name' => 'nullable|alpha',
            'date_of_birth' => 'required|date',
            'student_email' => 'required|email',
            'student_mobile' => 'required|numeric|digits:10',
            'father_mobile' => 'required|numeric|digits:10',
            'mother_mobile' => 'nullable|numeric|digits:10',
            'father_name' => 'required',
            'mother_name' => 'required',
			'nominee_name' => 'required',
            'guardian_name' => 'required',
            'guardian_mobile' => 'required|numeric|digits:10',
            'domicile' => 'required',
            'domicile_cirtificate' => 'required_if:domicile,Uttar Pradesh|mimes:jpeg,png,jpg,pdf|min:200|max:500',
            'gender' => 'required',
            'category' => 'required',
            'caste_certificate_number' => 'required_if:category,SC,OBC,ST,EWS',
            'upload_caste_certificate' => 'required_if:category,SC,OBC,ST,EWS|mimes:jpeg,png,jpg,pdf|min:200|max:500',
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
			'percentage_of_disability'=> 'required_if:disability,yes|nullable|numeric|min:40|max:100',
			'upload_disability_certificate'=> 'required_with:disability_category|mimes:jpeg,png,jpg,pdf|min:200|max:500',
			//'udid_number'=> 'required_with:disability_category|nullable|regex:/^([a-zA-Z]){2}([0-9]){16}?$/',
			'udid_number'=> 'nullable|regex:/^([a-zA-Z]){2}([0-9]){16}?$/',
            'freedom_fighter_dependent' => 'required',
            'freedom_fighter_dependent_file' => 'required_if:freedom_fighter_dependent,yes|mimes:jpeg,png,jpg,pdf|min:200|max:500',
            'ncc' => 'required',
            'ncc_cirtificate' => 'required_if:ncc,yes|mimes:jpeg,png,jpg,pdf|min:200|max:500',
            'nss' => 'required',
            'nss_cirtificate' => 'required_if:nss,yes|mimes:jpeg,png,jpg,pdf|min:200|max:500',
            'sports' => 'required',
            'sport_level' => 'required_if:sports,yes',
            'sportt_cirtificate' => 'required_if:sports,yes|mimes:jpeg,png,jpg,pdf|min:200|max:500',

            'hostel_facility_required' => 'required',
            'hostel_for_years' => 'required_if:hostel_facility_required,yes',
            'distance_from_university' => 'required_if:hostel_facility_required,yes',

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
            'upload_photo' => 'required|mimes:jpeg,png,jpg|max:50',
            'upload_signature' => 'required|mimes:jpeg,png,jpg|max:50',
			'dsmnru_student'=> 'required',
			'enrollment_number'=> 'old_student|required_if:dsmnru_student,Yes',
            'is_agree' => 'required',
			// 'aiot_score'=>'required_if:course_id,11,26,27',
			// 'aiot_score_card'=>'required_if:course_id,11,26,27',
			// 'aiot_rank'=>'required_if:course_id,11,26,27',
            'admission_through' => Rule::requiredIf(function () use ($request,$btech_course) {
				$return_val = false;
				if((in_array($request->course_id, $btech_course)) && $request->lateral_entry=='no'){
					$return_val = true;
				}
				return $return_val;
			}),
            'appeared_or_passed' => Rule::requiredIf(function () use ($request,$btech_course) {
				$return_val = false;
				if((in_array($request->course_id, $btech_course)) && $request->lateral_entry=='no'){
					$return_val = true;
				}
				return $return_val;
			}),
            'jeee_roll_number' => Rule::requiredIf(function () use ($request,$btech_course) {
				$return_val = false;
				if((in_array($request->course_id, $btech_course)) && $request->lateral_entry=='no'){
					$return_val = true;
				}
				return $return_val;
			}),
            'admission_through_exam_name' => 'required_if:admission_through,OTHER STATE LEVEL EXAM',
            'jeee_date_of_examination' => 'required_if:appeared_or_passed,Passed',
            // 'jeee_roll_number' => 'required_if:appeared_or_passed,Passed',
            'jeee_score' => 'required_if:appeared_or_passed,Passed',
            'jeee_rank' => 'required_if:appeared_or_passed,Passed',
            // 'jeee_merit' => 'required_if:appeared_or_passed,Passed',
            // 'upsee_date_of_examination' => 'required_if:course_id,126',
            // 'upsee_roll_number' => 'required_if:course_id,126',
            // 'upsee_score' => 'required_if:course_id,126',
            // 'upsee_merit' => 'required_if:course_id,126',
            // 'upsee_rank' => 'required_if:course_id,126',
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
            'total_marks_cgpa.*' => 'nullable|numeric',
            'cgpa_optain_marks.*' => 'nullable|numeric',
            'equivalent_percentage.*' => 'nullable',
            'subject.*' => 'nullable',
            'certificate_number.*' => 'nullable',
            'education_document.*' => 'nullable|mimes:jpeg,png,jpg,pdf|min:200|max:500',
            'cgpa_document.*' => 'nullable|mimes:jpeg,png,jpg,pdf|min:200|max:500',
//			'g-recaptcha-response' => 'required|recaptchav3:admitcard,0.5'
			'cuet_application_number' => 'required_if:cuet_required,Yes',
			'cuet_score_card' => 'required_if:cuet_required,Yes|mimes:jpeg,png,jpg,pdf|min:200|max:500',
		],
		[
			'old_student' => 'Invalid Enrollment',
			'affiliated_collage.required_if' => 'Affiliated Collage Field is required.',
			'state_union_territory.required' => 'The state/union territory field is required.',
			'correspondence_state_union_territory.required' => 'The correspondence state/union territory field is required.',
			'g-recaptcha-response.required' => 'Google Captcha field is required.',
			'g-recaptcha-response.captcha' => 'Google Captcha field is required.',
			// 'aiot_score.required_if' => 'AIOT Score Required When Course is D.ed',
			'aiot_score_card.required_if' => 'AIOT Score Card Required When Course is D.ed',
			'admission_through.required_if'=>'Addmission Through Required If Course is B.Tech',
			'jeee_date_of_examination.required_if'=>'Date Of Examination Required',
            'jeee_roll_number.required_if'=>'Roll Number Required',
            'jeee_score.required_if'=>'Score Required',
            'jeee_merit.required_if'=>'Merit Required',
            'jeee_rank.required_if'=>'Rank Required',
            'upsee_date_of_examination.required_if'=>'Date Of Examination Required',
            'upsee_roll_number.required_if'=>'Roll Number Required',
            'upsee_score.required_if'=>'Score Required',
            'upsee_merit.required_if'=>'Merit Required',
            'upsee_rank.required_if'=>'Rank Required',
		]);

		if ($validator->fails()) {
			return response()->json($validator->messages(), 200);
		}

		DB::beginTransaction();
		try {

				$old_application=Application::where(['user_id'=>Auth::user()->id,
													'course_id'=>$request->course_id,
													'academic_session'=>$request->academic_session,
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
					'course_preferences'=> implode(',',array_filter($request->course_preferences)),
					'lateral_entry'=>$request->lateral_entry,
					'is_agree'=>$request->is_agree,
					'user_id'=> Auth::user()->id,
					'first_Name' => $request->student_first_Name,
					'last_Name' => $request->student_last_Name,
					'middle_Name' => $request->student_middle_Name,
					'date_of_birth' => $request->date_of_birth,
					'email' => $request->student_email,
					'mobile' => $request->student_mobile,
					'father_mobile' => $request->father_mobile,
					'mother_mobile' => $request->mother_mobile,
					'father_first_name' => $request->father_name,
					'mother_first_name' => $request->mother_name,
					'nominee_first_name' => $request->nominee_name,
					'guardian_first_name' => $request->guardian_name,
					'guardian_mobile' => $request->guardian_mobile,
					'domicile'=>$request->domicile,
					'dsmnru_student'=>$request->dsmnru_student,
					'enrollment_number'=>$request->enrollment_number,
					'sport_level'=>$request->sport_level,
					'hostel_facility_required'=>$request->hostel_facility_required,
					'hostel_for_years'=>$request->hostel_for_years,
					'distance_from_university'=>$request->distance_from_university,

					'aiot_rank'=>$request->aiot_rank,
					'aiot_score'=>$request->aiot_score,
					//'jee_score'=>$request->jee_score,
					'udid_number'=>$request->udid_number,
					'disability'=>$request->disability,
					'percentage_of_disability'=>$request->percentage_of_disability,
					'admission_through'=>$request->admission_through,
					'appeared_or_passed'=>$request->appeared_or_passed,
					'admission_through_exam_name'=>$request->admission_through_exam_name,
					'date_of_examination'=>($request->jeee_date_of_examination)?$request->jeee_date_of_examination:$request->upsee_date_of_examination,
					'roll_number'=>($request->jeee_roll_number)?$request->jeee_roll_number:$request->upsee_roll_number,
					'score'=>($request->jeee_score)?$request->jeee_score:$request->upsee_score,
					'merit'=>($request->jeee_merit)?$request->jeee_merit:$request->upsee_merit,
					'rank'=>($request->jeee_rank)?$request->jeee_rank:$request->upsee_rank,
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
					'freedom_fighter_dependent' => $request->freedom_fighter_dependent,
					'ncc'=>$request->ncc ,
					'nss' =>$request->nss,
					'sports'=>$request->sports ,
					//'weightage'=>$weightage,
					'blood_group'=>$request->blood_group,
					'adhar_card_number'=>$request->adhar_card_number,
				);
				$application = new Application;
				$application->fill($applicationData);
				$application->save();
				$application->application_no = "DSMNRU/REQ/".$application->id;
				$application->status = "pending";
				if($application->application_for=='1'){
					$application->campuse_id=1;
				}
				$application->cuet_details = $this->cuet_details_save($request->course_id,$request->all());
				$application->save();

				if($request->cuet_score_card){
					$application->addMediaFromRequest('cuet_score_card')->toMediaCollection('cuet_score_card');
				}
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
				if($request->aiot_score_card){
					$application->addMediaFromRequest('aiot_score_card')->toMediaCollection('aiot_score_card');
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
				$this->saveEducationAllFiles($request,$application->id);

				$permanentAddressData=array(
					'address'=>$request->address,
					'address_type'=>'permanent',
					'district'=>$request->district,
					'police_station'=>$request->police_station,
					'nearest_railway_station'=>$request->nearest_railway_station,
					'country'=>$request->country,
					'state_union_territory'=>$request->state_union_territory,
					'pin_code'=>$request->pin_code,
					'application_id'=> $application->id,
					'user_id'=> Auth::user()->id
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
					'application_id'=> $application->id,
					'user_id'=> Auth::user()->id
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

	public function cuet_details_save($course_id,$request_data){
		$course_details = Course::find($course_id);
		if($course_details && $course_details->cuet_status=='Yes'){
			for($cuet_loop=1;$cuet_loop<=6;$cuet_loop++){
				$cuet_subject_code = 'cuet_subject_code'.$cuet_loop;
				$cuet_subject_name = 'cuet_subject_name'.$cuet_loop;
				$cuet_maximum_marks = 'cuet_maximum_marks'.$cuet_loop;
				$cuet_obtained_marks = 'cuet_obtained_marks'.$cuet_loop;
				$cuet_array[] = [
					'cuet_subject_code' =>  $request_data[$cuet_subject_code],
					'cuet_subject_name' =>  $request_data[$cuet_subject_name],
					'cuet_maximum_marks' =>  $request_data[$cuet_maximum_marks],
					'cuet_obtained_marks' =>  $request_data[$cuet_obtained_marks],
					'total_cuet_maximum_marks' =>  $request_data['total_cuet_maximum_marks'],
					'total_cuet_obtained_marks' =>  $request_data['total_cuet_obtained_marks'],
					'cuet_application_number' =>  $request_data['cuet_application_number']
				];
			}
			return serialize($cuet_array);
		}else{
			return null;
		}
	}

	public function add_application_form_save(Request $request)
	{
		if(Auth::user()->course_type==1){
			return redirect('application-form-phd');
		}

		$validator = Validator::make($request->all(), [
            'course_type' => 'required',
            'course_id' => 'required',
		]);

		if ($validator->fails()) {
			return response()->json($validator->messages(), 200);
		}

		$id=$request->application_id;
		$application_data=Application::find($id);
		$applicationeducation_data=ApplicationEducation::where('application_id',$id)->get();
		$applicationaddress_data_permanent=ApplicationAddress::where(['application_id'=>$id,'address_type'=>'permanent'])->first();
		$applicationaddress_data_correspondence=ApplicationAddress::where(['application_id'=>$id,'address_type'=>'correspondence'])->first();
		//dd($application_data);
		$old_application=Application::where(['user_id'=>Auth::user()->id,
											'course_id'=>$request->course_id])->first();
		if($old_application){
			$data['status'] = false;
			$data['message'] = 'You Already Applied For This Course!!!';
			return response()->json($data);
		}

		$applicationData=array(

			'academic_session'=>$application_data->academic_session,
			'application_for'=>$application_data->application_for,
			'campuse_id'=>$application_data->campuse_id,
			'category_id'=>$request->course_type,
			'course_id'=>$request->course_id,
			'lateral_entry'=>$request->lateral_entry,
			'is_agree'=>$application_data->is_agree,
			'user_id'=> Auth::user()->id,
			'first_Name' => $application_data->first_Name,
            'last_Name' => $application_data->last_Name,
            'middle_Name' => $application_data->middle_Name,
			'date_of_birth' => $application_data->date_of_birth,
			'email' => $application_data->email,
			'mobile' => $application_data->mobile,
			'father_first_name' => $application_data->father_first_name,
            'mother_first_name' => $application_data->mother_first_name,
            'nominee_first_name' => $application_data->nominee_first_name,
            'guardian_first_name' => $application_data->guardian_first_name,
            'guardian_mobile' => $application_data->guardian_mobile,
			'domicile'=>$application_data->domicile,
			'enrollment_number'=>$application_data->enrollment_number,
			'sport_level'=>$application_data->sport_level,
			'gender'=>$application_data->gender,
			'category'=>$application_data->category,
			'certificate_number' => $application_data->certificate_number,
			'nationality'=>$application_data->nationality,
			'religion'=>$application_data->religion,
			'marital_status'=>$application_data->marital_status,
			// 'sub_category'=>$application_data->sub_category,
			'dsmnru_employee' =>$application_data->dsmnru_employee ,
            'dsmnru_employee_ward' => $application_data->dsmnru_employee_ward,
            'disability_category' => $application_data->disability_category,
            'freedom_fighter_dependent' => $application_data->freedom_fighter_dependent,
			'ncc'=>$application_data->ncc ,
            'nss' =>$application_data->nss,
            'sports'=>$application_data->sports ,
			//'weightage'=>$weightage,
			'blood_group'=>$application_data->blood_group,
			'adhar_card_number'=>$application_data->adhar_card_number,
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
		if($application_data->photourl){
			$mediaItems = $application_data->getMedia('photo');
			//dd($mediaItems[0]->getPath());
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('photo');
		}

		if($application_data->signatureurl){
			$mediaItems = $application_data->getMedia('signature');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('signature');
		}

		if($application_data->aadharcardsurl){
			$mediaItems = $application_data->getMedia('signature');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('aadharcards');
		}

		if($application_data->castecertificateurl){
			$mediaItems = $application_data->getMedia('caste_certificate');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('caste_certificate');
		}

		if($application_data->disabilitycertificateurl){
			$mediaItems = $application_data->getMedia('upload_disability_certificate');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('upload_disability_certificate');
		}

		if($application_data->incomecertificateurl){
			$mediaItems = $application_data->getMedia('income_certificate');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('income_certificate');
		}

		if($application_data->anyotherurl){
			$mediaItems = $application_data->getMedia('any_other');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('any_other');
		}

		if($application_data->freedomfighterdependent){
			$mediaItems = $application_data->getMedia('freedom_fighter_dependent');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('freedom_fighter_dependent');
		}

		if($application_data->ncccirtificate){
			$mediaItems = $application_data->getMedia('ncc_cirtificate');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('ncc_cirtificate');
		}

		if($application_data->nsscirtificate){
			$mediaItems = $application_data->getMedia('nss_cirtificate');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('nss_cirtificate');
		}

		if($application_data->sporttcirtificate){
			$mediaItems = $application_data->getMedia('sportt_cirtificate');
			$application->addMedia($mediaItems[0]->getPath())->toMediaCollection('sportt_cirtificate');
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

		// foreach($applicationeducation_data as $key=>$val)
		// {
		// 	//dd($val);
		// 	$educationData = array(
		// 		'name_of_exam'=>$val->name_of_exam,
		// 		'board'=>$val->board,
		// 		'passing_year'=>$val->passing_year,
		// 		'total_marks_cgpa'=>$val->total_marks_cgpa,
		// 		'cgpa_optain_marks'=>$val->cgpa_optain_marks,
		// 		'subject'=>$val->subject,
		// 		'certificate_number'=>$val->certificate_number,
		// 		'application_id'=> $application->id
		// 	);
		// 	// dd($educationData);

		// 	$applicationEducation = new ApplicationEducation();
		// 	$applicationEducation->fill($educationData);
		// 	$applicationEducation->save();

		// 	if(isset($applicationeducation_data->doc) && $applicationeducation_data->doc) {
		// 		$mediaItems = $application_data->getMedia('doc');
		// 		$applicationEducation->addMedia($mediaItems[0]->getPath())->toMediaCollection('doc');
		// 	}

		// }


		// $permanentAddressData=array(
		// 	'address'=>$applicationaddress_data_permanent->address,
		// 	'address_type'=>$applicationaddress_data_permanent->address_type,
		// 	'district'=>$applicationaddress_data_permanent->district,
		// 	'police_station'=>$applicationaddress_data_permanent->police_station,
		// 	'nearest_railway_station'=>$applicationaddress_data_permanent->nearest_railway_station,
		// 	'country'=>$applicationaddress_data_permanent->country,
		// 	'state_union_territory'=>$applicationaddress_data_permanent->state_union_territory,
		// 	'pin_code'=>$applicationaddress_data_permanent->pin_code,
		// 	'application_id'=> $application->id,
		// 	'user_id'=> Auth::user()->id
		// );

		// $address = new ApplicationAddress();
		// $address->fill($permanentAddressData);
		// $address->save();

		// $correspondenceAddressData=array(
		// 	'address'=>$applicationaddress_data_correspondence->address,
		// 	'address_type'=>$applicationaddress_data_correspondence->address_type,
		// 	'district'=>$applicationaddress_data_correspondence->district,
		// 	'police_station'=>$applicationaddress_data_correspondence->police_station,
		// 	'nearest_railway_station'=>$applicationaddress_data_correspondence->nearest_railway_station,
		// 	'country'=>$applicationaddress_data_correspondence->country,
		// 	'state_union_territory'=>$applicationaddress_data_correspondence->state_union_territory,
		// 	'pin_code'=>$applicationaddress_data_correspondence->pin_code,
		// 	'application_id'=> $application->id,
		// 	'user_id'=> Auth::user()->id
		// );

		// $address = new ApplicationAddress();
		// $address->fill($correspondenceAddressData);
		// $address->save();

		$data['status'] = true;
		$data['message'] = 'Data Saved Succesfully';
		$data['application_id'] = $application->id;

		return response()->json($data);
	}

	public function edit_application_form_save(Request $request){
		if($request->table=='applications'){
			if($request->category){
				$validator = Validator::make($request->all(),[
					'category' => 'required',
					'certificate_number' => 'required_if:category,SC,OBC,ST,EWS',
					'upload_caste_certificate' => 'required_if:category,SC,OBC,ST,EWS|mimes:jpeg,png,jpg,pdf|min:200|max:500',
				]);
			}
			if($request->domicile){
				$validator = Validator::make($request->all(),[
					'domicile' => 'required',
					'domicile_cirtificate' => 'required_if:domicile,Uttar Pradesh|mimes:jpeg,png,jpg,pdf|min:200|max:500',
				]);
			}
			if($request->admission_through){
				$validator = Validator::make($request->all(),[
					'admission_through' => 'required',
					'appeared_or_passed' => 'required',
					'admission_through_exam_name' => 'required_if:admission_through,OTHER STATE LEVEL EXAM',
					'date_of_examination' => 'required_if:appeared_or_passed,Passed',
					'roll_number' => 'required_if:appeared_or_passed,Passed',
					'score' => 'required_if:appeared_or_passed,Passed',
					'rank' => 'required_if:appeared_or_passed,Passed',
				]);
			}
			if ($validator->fails()) {
				return response()->json(['status'=>false,'message'=>$validator->errors()->first()], 201);
			}
			$application = Application::find($request->id);
			if($application==null || admission_open_couse_wise($application->course_id,2,$application->academic_session)==false){
				return response()->json(['status'=>false,'message'=>'Admission Edit is not allowed. Please contact admin..'], 201);
			}
			$application->fill($request->all());
			if($request->upload_caste_certificate){
				$application->addMediaFromRequest('upload_caste_certificate')->toMediaCollection('upload_caste_certificate');
			}
			if($request->domicile_cirtificate){
				$application->addMediaFromRequest('domicile_cirtificate')->toMediaCollection('domicile_cirtificate');
			}
			$application->save();
			return response()->json(['status'=>true,'message'=>'Record is saved successfully'], 200);
		}else{
			$validator = Validator::make($request->all(),[
				'passing_year' => 'required|numeric|digits:4',
				'total_marks_cgpa' => 'required|numeric',
				'cgpa_optain_marks' => 'required|numeric',
				'equivalent_percentage' => 'required',
				'cgpa_or_marks' => 'required',
				// 'subject' => 'required',
				// 'certificate_number' => 'required',
				'education_document' => 'required|mimes:jpeg,png,jpg,pdf|min:200|max:500',
				'cgpa_document' => 'nullable|mimes:jpeg,png,jpg,pdf|min:200|max:500',
			]);
			if ($validator->fails()) {
				return response()->json(['status'=>false,'message'=>$validator->errors()->first()], 201);
			}
			$applicationEducation = ApplicationEducation::find($request->degree_id);
			if($applicationEducation->application==null || admission_open_couse_wise($applicationEducation->application->course_id,2,$applicationEducation->application->academic_session)==false){
				return response()->json(['status'=>false,'message'=>'Admission Edit is not allowed. Please contact admin.'], 201);
			}
			$applicationEducation->fill($request->all());
			if($request->education_document) {
				$applicationEducation->addMediaFromRequest("education_document")->toMediaCollection('doc');
			}
			if($request->cgpa_document) {
				$applicationEducation->addMediaFromRequest("cgpa_document")->toMediaCollection('cgpa_document');
			}
			$applicationEducation->passing_status=1;
			$applicationEducation->save();
			return response()->json(['status'=>true,'message'=>'Record is saved successfully'], 200);
		}
	}

	public function get_programm(Request $request)
	{
		$html='<option value="">--Select Course--</option>';
		$query = Course::where(['category_id' => $request->course_type, 'status' => 1]);
		if($request->campus_id){
			$query->where('campus_id', $request->campus_id);
		}
		$course = $query->orderBy('name')->get();
		foreach($course as $sc){
			if(Auth::user()->course_type==1){
				$html.='<option value="'.$sc->id.'" >'.$sc->name.'</option>';
			}else{
				if($sc->id!=94){
					if($sc->visible_in_application==0){
						$html.='<option value="'.$sc->id.'" disabled >'.$sc->name.'</option>';
					}else{
						$html.='<option value="'.$sc->id.'" >'.$sc->name.'</option>';
					}
				}
			}
		}
		return $html;
	}
	public function applicationCourseList(Request $request)
	{
		$course_groups = Course::whereNotNull('course_group')->get();
		$course_groups_array = [];
		foreach($course_groups as $course_group_id){
			$course_group_id = explode(',',$course_group_id->course_group);
			$course_groups_array = array_merge($course_group_id,$course_groups_array);
		}
		$html='<option value="">--Select Course--</option>';
		$query = Course::where(['category_id' => $request->course_type, 'status' => 1]);
		if($request->campus_id){
			$query->where('campus_id', $request->campus_id);
		}
		$course_groups_array[] = "94";
		$course = $query->whereNotIn('id',$course_groups_array)->orderBy('name')->get();
		foreach($course as $sc){
			if(admission_open_couse_wise($sc->id,1)==false){
				$html.='<option value="'.$sc->id.'" disabled >'.$sc->name.'</option>';
			}else{
				$html.='<option value="'.$sc->id.'" >'.$sc->name.'</option>';
			}
		}
		return $html;
	}
	public function get_affiliate(Request $request)
	{
		$html='<option value="">--Select Program--</option>';
		$query= Course::where('campus_id', $request->affiliated)->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		return $html;
	}
	public function get_state(Request $request)
	{
		$country= Country::where('name',$request->country_id)->first();
		$html='<option value="">--Select State--</option>';
		$query= State::where('country_id', $country->id)->get();
		foreach($query as $sc){
			$html.='<option value="'.$sc->name.'">'.$sc->name.'</option>';
		}
		return $html;
	}
	public function get_district(Request $request)
		{
			$state= State::where('name',$request->state_id)->first();

			$html='<option value="">--Select District--</option>';
			$query= City::where('state_id', $state->id)->get();
			foreach($query as $sc){
				$html.='<option>'.$sc->name.'</option>';
			}
			return $html;
		}

	public function view_application_form(Request $request){
		if(Auth::user()->course_type==1){
			if($request->view=='true'){
				return redirect('phd-application-form-view?view=true&application_id='.$request->application_id);
			}else{
				return redirect('phd-application-form-view?application_id='.$request->application_id);
			}
		}
		$user = Auth::user();
		$application = Application::where(['user_id'=>$user->id,'id'=>$request->application_id])->first();
		$data['program'] = Category::find($application->category_id);
		$data['course'] = Course::find($application->course_id);
		$data['castCategorys'] = CastCategory::all();
		$data['application'] = $application;
		$data['applicationPayment'] = ApplicationPayment::where('application_id',$request->application_id)->first();
		//dd($data['applicationPayment']);
		// $data['applicationEducation'] = ApplicationEducation::where('application_id',$request->application_id)->get();
		$data['applicationEducation'] = ApplicationEducation::where('application_id',$request->application_id)->get();
		$data['permanent_address'] = ApplicationAddress::where(['application_id'=>$request->application_id,'address_type'=>'permanent'])->first();
		//dd($data['permanent_address']);
		$data['correspondence_address'] = ApplicationAddress::where(['application_id'=>$request->application_id,'address_type'=>'correspondence'])->first();
		// dd($data['applicationEducation']);
		if($user->course_type == 0){
		 return view('frontend.index.view-application-form',$data);
		}else{
		 return view('student.phdadmitcard.phd_application_form_view',$data);
		}
	}
	public function add_application_form(Request $request){
		$user=Auth::user();

		if(Auth::user()->course_type==1){
			return redirect('application-form-phd');
		}

		$application = Application::where(['user_id'=>$user->id,'id'=>$request->application_id])->first();
		if(!$application){
			return redirect('dashboard')->with('error','Application Not Found');
		}
		$data['programm_types'] = Category::all();
		$data['courses'] = array();
		$data['application']=$application;
		$data['applicationPayment']=ApplicationPayment::where('application_id',$request->application_id)->first();
		//dd($data['applicationPayment']);
		$data['applicationEducation']=ApplicationEducation::where('application_id',$request->application_id)->get();
		$data['permanent_address']=ApplicationAddress::where(['application_id'=>$request->application_id,'address_type'=>'permanent'])->first();
		$data['correspondence_address']=ApplicationAddress::where(['application_id'=>$request->application_id,'address_type'=>'correspondence'])->first();
		//dd($data['applicationEducation']);
		return view('frontend.index.add-application-form',$data);
	}

	public function certificateLogin(Request $request){
		
		return view('frontend.certificate-portal');
	}
	public function certificatePortal(Request $request){
		$this->validate($request, [
			'roll_number' => ['required', 'string'],
            'password' => ['required', 'string'],
			// 'g-recaptcha-response' => 'required|captcha',
		],
		[
			'g-recaptcha-response.required' => 'Google Captcha field is required.',
		]);
		$userData = Student::where('roll_number', $request->roll_number)->first();
		if($userData){
			if(Hash::check($request->password, $userData->password)){
				Auth::guard('student')->login($userData);
				return redirect('certificate-page/'.base64_encode($userData->roll_number));
			}

		}
		else{
				return back()->with('error','Invalid Data Found');
		}
	}

	public function certificatesPage(Request $request){
		$roll_no = base64_decode($request->roll_no);

		$result=Result::where('roll_no',$roll_no)->first();
	    //dd($result);
		return view('frontend.certificate-page',['result'=>$result]);
	}

	public function resultLogin(Request $request)
	{
		return view('frontend.result-portal');
	}
	public function resultLoginForm(Request $request)
	{
		$this->validate($request, [
			'roll_no'=>'required',
			'dob'=>'required',
			// 'g-recaptcha-response' => 'required|captcha',
		],
		[
			'roll_no.required' => 'Roll Number Required',
			'dob.required' => 'Date Of Birth Required',
			'g-recaptcha-response.required' => 'Google Captcha field is required.',
		]);
		$roll_no = $request->roll_no;
		$student = Student::where('roll_number',$roll_no)
			->where('date_of_birth',$request->dob)
			->first();
		if(!$student){
			return back()->with('error','Invalid Roll Number or DOB You Have Provided.');
		}
		return redirect('student-results/'.base64_encode($roll_no));
	}

	public function studentResultData(Request $request,$roll_no){
		$results = Result::select('enrollment_no','roll_no','course_id','semester','result_type','status','semester_final','back_status_text')
		->whereNotIn('course_id', [49,64,95,96])
		->where('roll_no',base64_decode($roll_no))
		->distinct()
		->orderBy('semester_number','ASC')
		->orderBy('id','DESC')
		->get();
		return view('frontend.student_result_page',compact('results'));
	}

	public function oneViewResult(Request $request){
		$roll_no = base64_decode($request->roll_no);
		$student = Student::where('roll_number',$roll_no)->first();
		if(!$student){
			return back()->with('error','Invalid Roll Number');
		}

		$result_single = Result::where('roll_no',$roll_no)
		->where('status',2)
		->orderBy('semester_number','DESC')
		->first();
		if(!$result_single){
			return back()->with('error','Semester result is not generated. Please contact to admin');
		}
		if($result_single->course_id==49){
			return back()->with('error','Please contact to admin to view this result.');
		}

		$semesters = Semester::where('course_id',$result_single->course_id)->get();
		$results = Result::select('roll_no','semester','course_id')
			->where('roll_no',$roll_no)
			->where('status',2)
			->distinct('roll_no','semester','course_id')
			->get();
		$results_session_wise = Result::select('roll_no','semester','exam_session','back_status_text','result','cgpa','sgpa','approval_date','result_overall')
			->where('roll_no',$roll_no)
			->where('status',2)
			->distinct()
			->orderBy('exam_session')
			->get();
		$examData = ExamFee::withTrashed()->where('roll_no',$roll_no)->first();
		$student_details = Student::withTrashed()->where('roll_number',$roll_no)->first();
		return view('frontend.one-view-result',compact('student','result_single','results','examData','student_details','results_session_wise','semesters'));
	}

	public function resultPage(Request $request)
	{
		$roll_no = base64_decode($request->roll_no);
		$roll_number=Result::where('roll_no',$roll_no)->first();
		//dd($roll_number);

		//$roll_number = Auth::guard('student')->user()->roll_number;
		$data=Student::where('roll_number',$roll_no)->first();
		//dd($data);

		$en=Enrollment::where('roll_number',$roll_no)->first();
		//dd($en);
		$application=Icard::where('roll_no',$roll_no)->first();
		$course=Course::where('id',$en->course_id)->first();
		$stream=Stream::where('course_id',$course->id)->first();
		//dd($course);
		$campuse=Campuse::where('id',$course->campus_id)->first();
		//dd($campuse);

        $semesters = Semester::select('semesters.*')->join('results','results.semester','semesters.id')
						->where('roll_no',$roll_no)
						->groupBy('semester')
						->get();
//		dd($semesters);
		$all_data = [];
		foreach($semesters as $index=>$semester){
			$results=Result::where('roll_no',$roll_no)
							->where('semester',$semester->id)
							->where('status',2)
							->orderBy('subject_code','asc')
							->get();
			$all_data[$index]['results'] = $results;
			$all_data[$index]['p_count'] = Result::where('roll_no',$roll_no)->where('semester',$semester->id)
							->join('subjects','subject_code','sub_code')
							->where('subject_type','Practical')
							->count();
			$all_data[$index]['t_count'] = Result::where('roll_no',$roll_no)->where('semester',$semester->id)
							->join('subjects','subject_code','sub_code')
							->where('subject_type','Theory')
							->count();
			$all_data[$index]['year'] = null;
			$max_marks=0;
			$obtain=0;
			$sum=0;
			$back=null;
			$pass=null;
			$fail=null;
			$counter=0;
			$cgpa=0;
			$carryover=null;
			//dd($results);
			foreach($results as $key=>$result){
				if(is_numeric($result->external_marks))
				{
					$external=$result->external_marks;
				}
				else{
					$external=0;
				}
				if(is_numeric($result->internal_marks))
				{
					$internal=$result->internal_marks;
				}
				else{
					$internal=0;
				}
				$sum=$internal+$external;
				if($sum<$result->subject->minimum_mark)
				{
					$back[$key]['subject_code']=$result->subject_code;
					$counter++;
				}
				$max_marks=$max_marks+$result->subject->maximum_mark;
				$obtain=$obtain+$sum;

			}

			$all_data[$index]['obtain']=$obtain;
			$all_data[$index]['sum']=$sum;
			$all_data[$index]['back']=$back;
			$all_data[$index]['fail']=$fail;
			$all_data[$index]['counter']=$counter;

			if($max_marks==0){
				$percentage = 0;
			}else{
				$percentage=($obtain/$max_marks)*100;
			}
			$all_data[$index]['max_marks']=$max_marks;
			//dd($percentage);
			if($percentage>35){
				$pass='Pass';
				//dd($pass);
				//return $pass;
			}elseif($percentage<35){
				$pass='Fail';
				//dd($fail);
				//return $fail;
			}
			$cgpa=($percentage+4.5)/10;
			$all_data[$index]['pass']=$pass;
			if($counter>0){
				$carryover='CP';
			}
			$all_data[$index]['carryover']=$carryover;

			$all_data[$index]['cgpa']=number_format((float)$cgpa, 2, '.', '');
		}

	   	return view('frontend.result-page',['all_data'=>$all_data,'campuse'=>$campuse,'stream'=>$stream,'course'=>$course,'data'=>$data,'application'=>$en->application]);
	}
	public function addsemesterFee(Request $request)
    {
		//dd($request->all());
		$validator = Validator::make($request->all(),[

            'subject_id'=>'required|array',
			'subject_id.*' => 'required',
        ]

		);
		//dd($validator->fails());
		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput();
		}
		$application=Application::where('application_no',$request->application_id)->first();
		$id=$application->id;
		//dd($request->all());
		//dd('Work Under Process');
		$student_subjects = [];
		//dd($request->subjectname);

		//dd($student_subjects);

        $data = $request->all();

		foreach($data['subject_id'] as $key => $value){
			$enrollmentSubject[]=$value;
		}

		//dd(implode(" ",$enrollmentSubject));
		$data['enrollmentSubject']=implode(" ",$enrollmentSubject);
		//dd($data);
		$studentsemester=new StudentSemesterFee;
		$studentsemester->enrollment_no=$data['application_id'];
		$studentsemester->course_id=$data['course_id'];
		$studentsemester->program_id=$data['program_id'];
		$studentsemester->semester_id=$data['semester_id'];
		$studentsemester->subjects=$data['enrollmentSubject'];
		$studentsemester->status=0;
		//dd($studentsemester);
		$studentsemester->save();
		$studentsemester->id;

        $studentsemester_id = $studentsemester->id;

		foreach($request->subject_id as $index=>$subject_row){
			$student_subjects[$index]['student_semester_fee_id'] = $studentsemester_id;
			$student_subjects[$index]['sub_code'] = $subject_row;
			$student_subjects[$index]['enrollment_number'] = $request->application_id;
			//$student_subjects[$index]['roll_number'] = $request->roll_no;
			//$student_subjects[$index]['session'] = $request->academic_session;
			$student_subjects[$index]['program_id'] = $request->program_id;
			$student_subjects[$index]['course_id'] = $request->course_id;
			$student_subjects[$index]['semester_id'] = $request->semester_id;
			$student_subjects[$index]['sub_name'] = Subject::where('sub_code',$subject_row)->first()->name;

		}

		StudentSubject::insert($student_subjects);
		//dd($student_subjects);
		return redirect('semester-payment?id='.$id);
       // return redirect('dashboard')->with('semester','First Semester Details Saved Successfully');
    }

	

    public function entranceAdmitCard($id)
    {
    	$admitcard = Application::where('id',$id)->first();
		if(!$admitcard){
			return back()->with('error','Invalid Application ID');
		}
    	$entranceExamData = EntranceExamAdmitCard::where('course_id',$admitcard->course_id)->first();
		if(!$entranceExamData){
			return back()->with('error','Admit card is not generated');
		}
		return view('frontend.index.entrance_admit_card',compact('admitcard','entranceExamData'));
    }

    public function delApplication($id,Request $request){

		$application_delete = Application::find($id);
			if($application_delete){
					ApplicationAddress::where('application_id', $application_delete->id)->delete();
					ApplicationEducation::where('application_id', $application_delete->id)->delete();
					ApplicationPayment::where('application_id', $application_delete->id)->delete();
				$application_delete->delete();
				return redirect('dashboard')->with('message','Course deleted successfully.');
			}else{
				return redirect('dashboard')->with('error','You can not delete because you paid successfully.');

			}
			}


	public function additionalQualification($applicationId)
	{
		$data['applicationId'] = $applicationId;
		$data['applicationEducation'] = ApplicationEducation::where('user_id',Auth::user()->id)->get();
		return view('frontend.index.application-form.additional-education-qualification',$data);
	}

	public function aiotUpdateFoarm($applicationId)
	{
		 $data['applicationId'] = $applicationId;
		$data['applicationEducation'] = ApplicationEducation::where('user_id',Auth::user()->id)->get();
		return view('frontend.index.application-form.aiot_upload',$data);
	}

	public function additionalQualificationSave(Request $request)
	{
		$validator = Validator::make($request->all(), [
			// /=====education code=====/
            'name_of_exam.*' => 'required',
            'board.*' => 'required',
            'passing_status.*' => 'required',
            'passing_year.*' => 'nullable|numeric|digits:4',
            'total_marks_cgpa.*' => 'nullable|numeric',
            'cgpa_optain_marks.*' => 'nullable|numeric',
            'equivalent_percentage.*' => 'nullable',
            'subject.*' => 'nullable',
            'certificate_number.*' => 'nullable',
            'education_document.*' => 'nullable|mimes:jpeg,png,jpg|max:512',
            'cgpa_document.*' => 'nullable|mimes:jpeg,png,jpg|max:512',
			'g-recaptcha-response' => 'required|recaptchav3:admitcard,0.5'
 		]);


				 $application = Application::find($request->applicationId);

				$this->saveEducationAllFiles($request,$request->applicationId);

				return redirect('dashboard')->with('success','Document Uploaded Successfully. Now You can Print Application Form');

	}

	public function aiotFormSave(Request $request)
	{
		$validator = Validator::make($request->all(), [
			// /=====education code=====/
            // 'aiot_score.*' => 'required',
            'aiot_rank' => 'required',
            'aiot_score_card' => 'required',
 		]);
			// dd($request->all());
			$application = Application::find($request->applicationId);
			$application->aiot_score = $request->aiot_score;
			$application->aiot_rank = $request->aiot_rank;
			if($request->aiot_score_card){
				$application->addMediaFromRequest('aiot_score_card')->toMediaCollection('aiot_score_card');
			}
			$application->save();

			return redirect('dashboard')->with('success','AIOT Uploaded Successfully. Now You Can Print Application Form');

	}

	public function saveEducationAllFiles(Request $request,$application_id){

		foreach($request->name_of_exam as $key=>$val){
			$passing_status_key = ($key+1);
			$passing_status = 'passing_status'.$passing_status_key;
			$cgpa_or_marks = 'cgpa_or_marks'.$passing_status_key;
			$applicationEducation = new ApplicationEducation();
			$applicationEducation->name_of_exam = $request->name_of_exam[$key];
			$applicationEducation->degree_name = $request->degree_name[$key];
			$applicationEducation->board = $request->board[$key];
			$applicationEducation->passing_status = $request->$passing_status;
			$applicationEducation->passing_year = $request->passing_year[$key];
			$applicationEducation->cgpa_or_marks = $request->$cgpa_or_marks;
			$applicationEducation->total_marks_cgpa = $request->total_marks_cgpa[$key];
			$applicationEducation->cgpa_optain_marks = $request->cgpa_optain_marks[$key];
			$applicationEducation->equivalent_percentage = round($request->equivalent_percentage[$key],2);
			$applicationEducation->subject = $request->subject[$key];
			$applicationEducation->certificate_number = $request->certificate_number[$key];
			if(isset($request->education_document[$key]) && $request->education_document[$key]) {
				$applicationEducation->addMediaFromRequest("education_document[$key]")->toMediaCollection('doc');
			}
			if(isset($request->cgpa_document[$key]) && $request->cgpa_document[$key]) {
				$applicationEducation->addMediaFromRequest("cgpa_document[$key]")->toMediaCollection('cgpa_document');
			}
			$applicationEducation->application_id = $application_id;
			$applicationEducation->user_id = Auth::user()->id;
			$response = $applicationEducation->save();
		}
	}

	public function deleteDocument(Request $request,$id){
		$educaton = ApplicationEducation::find($id);
		$media = $educaton->getMedia('doc')->first();
		if($media){
			$media->delete();
		}
		$educaton->delete();
		return back()->with('message','Document Deleted Successfully.');
	}

	public function savePhotoSignateure(Request $request){
		// dd($request->all());
		$applicationData = Application::where('user_id',$request->user_id)->first();
		$application = Application::find($applicationData->id);
		$application->save();
		if($applicationData->id){
			if($request->upload_photo){
			$application->addMediaFromRequest('upload_photo')->toMediaCollection('photo');
		}

		if($request->upload_signature){
			$application->addMediaFromRequest('upload_signature')->toMediaCollection('signature');
		}
		}
		return back()->with('message','Uploaded Successfully.');
	}

}

