<?php

namespace App\Http\Controllers\Grievance;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComplaintForm;
use App\Models\Student;
use App\Models\Campuse;
use Validator;
use Hash;
use Auth;

class ComplaintFormController extends Controller
{
    public function index()
    {
        return view('grievance.grievancelogin');
    }

    public function login(Request $request){
        $this->validate($request, [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        //  'g-recaptcha-response' => 'required|captcha',
        ],
        [
            'g-recaptcha-response.required' => 'Google Captcha field is required.',
        ]);
        $credentials['password'] = $request->password;

        $userData = Student::where('email', $request->email)->first();
        if($userData){
            if(Hash::check($request->password, $userData->password)){
                Auth::guard('grievance')->login($userData);
                return redirect('complaint-form')->with('success','Login Successful');
            }else{
                return back()->with('error','Credentials are wrong.');
            }
        }else{
            return back()->with('error','Credentials are wrong.');
        }
    }

    
    public function form(Request $request)
    {
        $student = Auth::guard('grievance')->user();
        $campus = Campuse::all();
        $data = ComplaintForm::select('complaint_no')
        ->where('roll_number',$student->roll_number)
        ->distinct('complaint_no')
        ->orderBy('complaint_no','ASC')
        ->orderBy('id','DESC')
        ->get();
        return view('grievance.complaint-form',compact('student','campus','data'));
    }

    public function saveQuery(Request $request)
    {
        
        $complaint_no = strtoupper(substr(md5(microtime()), 0, 6));
        $this->validate($request, [
            'file' => ['required','max:10000'],
        ]);
        $data = new ComplaintForm;
        $data->enrollment_no =  $request->enrollment_no;
        $data->roll_number = $request->roll_number;
        $data->first_Name = $request->first_Name;
        $data->course_id = $request->course_id;
        $data->complaint = $request->complaint;
        $data->complaint_no = $complaint_no;
        if($request->file){
            $data->addMediaFromRequest('file')->toMediaCollection('file');
          }
        $data->save(); 
        return back()->with('success','Your Query is submitted.And your complain number is '.$complaint_no);
    }

    public function complaintDetails(Request $request){
        $complaint_no = $request->complaint_no;
        $data = ComplaintForm::where('complaint_no',$complaint_no)->orderBy('id','ASC')->get();

        if($request->ajax()){
            $returnHTML = view('grievance.chatting-messages')->with('data', $data)->render();
            return response()->json(array('success' => true, 'html'=>$returnHTML));
        }

        return view('grievance.complaint-details',compact('data'));
    }
    public function complaintDetailsSave(Request $request){
       
        $validator = Validator::make($request->all(), [
            'complaint_no' => 'required',
            'attached' => 'nullable|mimes:jpeg,png,jpg,pdf|max:500',
        ]);
        if ($validator->fails()) {
            return response()->json(['success'=>false,'html'=>$validator->messages()]);
			// return response()->json($validator->messages(), 200);
		}

        if($request->attached==null && $request->complaint==null){
            return response()->json(['success'=>false,'html'=>'']);
        }
        $complaint_no = $request->complaint_no;
        $data = ComplaintForm::where('complaint_no',$complaint_no)->orderBy('id','DESC')->first();
        if(!$data){
            return response()->json(['success'=>false,'html'=>'']);
        }
        $complaint = new ComplaintForm;
        $complaint->complaint_no = $data->complaint_no;
        $complaint->enrollment_no = $data->enrollment_no;
        $complaint->roll_number = $data->roll_number;
        $complaint->responder_id = $data->responder_id;
        $complaint->responder_type = 0;
        $complaint->course_id = $data->course_id;
        $complaint->first_Name = $data->first_Name;
        $complaint->complaint = $request->complaint;
        $complaint->status = $data->status;
        // $complaint->created_at = date('Y-m-d H:i:s');
        if($request->attached){
            $complaint->addMediaFromRequest('attached')->toMediaCollection('attached');
        }
        $complaint->save();

        return response()->json(['success'=>true,'html'=>'']);
    }

}
