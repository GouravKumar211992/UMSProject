<?php

namespace App\Http\Controllers\ums\Student;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;
 
use App\Admin;
use App\Imports\ICardsImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\ums\Student;
use App\Models\ums\Application;
use App\Models\ums\Course;
use App\Models\ums\Category;
use App\Models\ums\PermanentAddress;
use App\Models\ums\UploadDocuments;
use App\Models\ums\Icard;
use App\Models\ums\Enrollment;
use App\Models\ums\StudentSemesterFee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class IcardsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }
	

    public function singleIcard(Request $request){
        $data['icards'] = Icard::whereId($request->id)->paginate(100);
		$icard= Icard::whereId($request->id)->first();
		 $en=Enrollment::where('enrollment_no',$icard->enrolment_number)->orderBy('id','desc')->first();
        $data['application']=Application::where('id',$en->application_id)->first();
		//dd($en->application_id,$data['application']);
        return view('admin.cards.bulk-icard',$data);
    }
	
    public function icardForm(Request $request){
        $student = Auth::guard('student')->user();
        $icard = Icard::where('roll_no',$student->roll_number)->first();
        $subject = StudentSemesterFee::where('enrollment_no',$student->enrollment_no)->first();
        return view('student.cards.icard-form',compact('student','icard','subject'));
    }
	public function icardForm_Submit(Request $request)
	{
		$validator = Validator::make($request->all(), [
			
            //'academic_session' => 'required',
            //'program' => 'required',
            //'subject' => 'required',
            //'student_name' => 'required',
            //'dob' => 'required|date',
            //'email' => 'required|email',
            //'student_mobile' => 'required|numeric|digits:10',
            //'father_name' => 'required',
            'father_mobile' => 'required|numeric|digits:10',
            //'mother_name' => 'required',
			//'local_guardian_name' => 'required',
            //'local_guardian_mobile' => 'required|numeric|digits:10',
            //'gender' => 'required',
            //'nationality' => 'required',
            //'disability_category' => 'required',
            //'blood_group' => 'required',
            //'permanent_address' => 'required',
            //'mailing_address' => 'required',
           // 'fee_receipt_date' => 'required',
            //'fee_receipt_number' => 'required',
            //'signature' => 'required',
           // 'photo' => 'required',
            	
		]); 

		if ($validator->fails()) {    
			return back()->withErrors($validator)->withInput($request->all());
		}
		$icarddata=array(//'academic_session'=>$request->academic_session,
			//'program'=>$request->program,
			//'subject'=>$request->subject,
			//'student_mobile' => $request->student_mobile,
			'father_mobile' => $request->father_mobile,
            //'mother_name' => $request->mother_name,
            'local_guardian_name' => $request->local_guardian_name,
			'local_guardian_mobile' => $request->local_guardian_mobile,
            'mailing_address'=>$request->mailing_address,
            'permanent_address'=>$request->permanent_address,
            'disablity'=>$request->disability_category,
            'nationality'=>$request->nationality,
            'blood_group'=>$request->blood_group,
			//'signature'=>$request->signature,
			//'fee_receipt_date'=>$request->fee_receipt_date,
		//	'fee_receipt_number'=>$request->fee_receipt_number
			
			);
			//dd($icarddata);
			$enrollment_no=Auth::guard('student')->user()->enrollment_no;
			$en=Enrollment::where('enrollment_no',$enrollment_no)->orderBy('id','desc')->first();
			$application=Application::where('id',$en->application_id)->first();
			//dd($application);
			if($request->photo){
		  $application->addMediaFromRequest('photo')->toMediaCollection('photo');
		}
		if($request->signature){
		  $application->addMediaFromRequest('signature')->toMediaCollection('signature');
		}
			$application->save();
			$icard=Icard::where('enrolment_number',$enrollment_no);
			$icard->update($icarddata);
			
			$data['status'] = true;
		$data['message'] = 'Icard Data Saved Please Download Your Icard';
		return redirect()->route('student-dashboard')->with('message','Icard Data Saved Please Download Your Icard');
		
			
		
	}

}