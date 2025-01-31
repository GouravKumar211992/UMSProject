<?php

namespace App\Http\Controllers\ums;

use App\Models\ums\User;
use App\Models\ums\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash ;
use Illuminate\Support\Facades\Mail ;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;






use App\Jobs\TrackingEmailFrequency;


//use App\Models\MailBox;
//use App\Services\Mailers\Mailer;

class HomeController extends Controller
{
    /**
     * Display the User Dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$data['campus_courses'] = Course::where('campus_id',1)->limit(4)->get();
		$data['aff_courses'] = Course::where('campus_id',2)->limit(4)->get();
		return view('frontend.landing',$data);
    }

	public function admissionPortal(Request $request){
		$data['campus_courses'] = Course::where('campus_id',1)->limit(4)->get();
		$data['aff_courses'] = Course::where('campus_id',2)->limit(4)->get();
        return view('frontend.index.index',$data);
    }

	public function enquery_login(Request $request){
		$validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => 'required',
		//	'g-recaptcha-response' => 'required|captcha',
		],
		[
			'password.required' => 'Password field is required',
			'g-recaptcha-response.required' => 'Google Captcha field is required.',
		]); 

		if ($validator->fails()) {    
			return response()->json($validator->messages(), 200);
		}

        $credentials = $request->only('email', 'password');
        $credentials['password'] = $request->password;

        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt($credentials,$remember)) {
			if(Auth::user()->status=='pending'){
				$data['status'] = false;
				$data['message'] = 'Your account is not activated. To active your account please click on <a class="btn btn-success" href="activate-your-account/'.$credentials['email'].'">Activate</a> button';
				Auth::logout();
				return response()->json($data);
			}
			$data['status'] = true;
			$data['message'] = 'Logged in Successfully';
			return response()->json($data);
            return redirect()->route('user-dashboard')->with('success','Login Successfully');
        } else {
			$data['status'] = false;
			$data['message'] = 'Email ID or Password is wrong';
			return response()->json($data);
            return back()->with('error','Email ID or Password is wrong');
        }

	}

	public function secretLogin($id,Request $request){
		$user = User::find($id);
		if($user){
			Auth::login($user);
			// Auth::guard('admin')->check() &&  Auth::guard('admin')->user()->role==1
			if(true){
				return redirect()->route('user-dashboard')->with('success','Login Successfully');
			}else{
				return redirect('view-application-form?view=true&application_id='.$request->application_id)->with('success','Login Successfully');
			}
		}else{
			return back()->with('error','Invalid ID');
		}
	}


	public function enquery_register(Request $request){
		$validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha',
            'middle_name' => 'nullable|alpha',
            'last_name' => 'nullable|alpha',
            'mobile' => 'required|numeric|digits:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
			'course_type' => 'required',
		//	'g-recaptcha-response' => 'required|captcha',
		],
		[
            'email.unique' => 'This Email Address is Already Registered',
			'g-recaptcha-response.required' => 'Google Captcha field is required.',
		]); 

		if ($validator->fails()) {    
			return response()->json($validator->messages(), 200);
		}

		$user = New User;
		$user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
		$user->user_name = explode('@',$request->email)[0];
		$user->mobile = $request->mobile;
		$user->email = $request->email;
		$user->password = $request->password;
		// $user->status = 'active';
		if($request->course_type){
		$user->course_type = $request->course_type;
		}
		$user->save();
		if($user) {

	//==========Email Sent=============//
			$sendEmail['link']= url('active-account/'.$user->id);
			$sendEmail['email'] = $request->email;
			$sendEmail['password'] = $request->password;
			$sendEmail['firstname'] = $request->first_name.' '.$request->middle_name.' '.$request->last_name;
			dispatch((new TrackingEmailFrequency($sendEmail,'admission_sell'))->onQueue('high'));

			$data['status'] = true;
			$data['message'] = 'Logged in Successfully';
		}else{
			$data['status'] = false;
			$data['message'] = 'Some Error Occurred!';
		}
		return response()->json($data);

	}
	
	public function active_account(Request $request){
		$user = User::find($request->id);
		$user->status = 'active';
		$user->save();
		echo '<h5 style="text-align:center;">Your Accout is activated successfully.</h5>';
		echo '<h5 style="text-align:center;"><a href="'.url('admission-portal').'">Back to admission portal</h5></h5>';
	}



    /**
     * Display Exam Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('exam-details',['category_id'=>$id]);
    }

    /**
     * Display Exam Test.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function noRecord()
    {
        return view('no-record',['module'=>'dashboard']);
    }

    public function forgotPassword(Request $request){
		$validator = Validator::make($request->all(), [
            'email' => 'required|email',
		]);

		if ($validator->fails()) {    
			return response()->json($validator->messages(), 200);
		}

		$user = User::whereEmail($request->email)->first();
		$token = sha1(time());
		if($user) {
			$user->remember_token = $token;
			$user->save();
			$email = $request->email;
			$name = $user->name;
			$subject = 'Forgot Password';
			$content = url('forgot-password-change').'?token='.$token;

			Mail::raw($content, function ($message) use ($email,$name,$subject){
				// $message->from(env('MAIL_DEFAULT_FROM', ''), 'Your Application');
				$message->to(explode(',', $email));
				$message->subject($subject);
				// $message->to($email, $name)->subject($subject);
			});
			$data['status'] = true;
			$data['message'] = 'Password Change link has been sent on your email ID';
		}else{
			$data['status'] = false;
			$data['message'] = 'Invalid Email ID. Please register first';
		}
		return response()->json($data);
	}

    public function forgotPasswordChange(Request $request)
    {
		if($request->token==null){
			$data['user'] = null;
		}else{
			$data['user'] = User::where('remember_token',$request->token)->first();
		}
        return view('frontend.forgot.index',$data);
    }

    public function forgotPasswordChangeSave(Request $request){
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
            'password' => 'required|confirmed',
		]);

		if ($validator->fails()) {    
			return back()->withInput()->withErrors($validator);
		}
		$user = User::whereEmail($request->email)->first();
		if (Hash::check($request->password, $user->password)) {
			return back()->with('error',"Don't use your current password");
		}
		$user->password = $request->password;
		$user->remember_token = null;
		$user->save();
//		dd($user);
		return back()->with('success','Password Changed Successfully');

    }

    public function categories(Request $request)
    {
		$data['campus_courses'] = Course::where('campus_id',1)->get();
		$data['aff_courses'] = Course::where('campus_id',2)->get();
        return view('frontend.index.all-categories',$data);
    }

    public function activateYourAcc($activeEmail)
    {
		$data = User::where('email', $activeEmail)->first();
		// dd($data->email);
		//==========Email Sent=============//
		$email = $data->email;
		$name = $data->name;
		$subject = 'Regarding account activation';
		Mail::send( ['html' => 'email.user-active'], ['user_id' => $data->id], function ($message) use ($email,$name,$subject){
			// $message->from(env('MAIL_DEFAULT_FROM', ''), 'Registration');
			$message->to($email, $name)->subject($subject);
		});
		return back()->with('success','You got a mail please check your mail');


    }

	public function digilockerupload(Request $request)
    {
        return view('digilockerPage.digilockerupload');
    }
	public function digilockeruploading(Request $request)
    {
        return view('digilockerPage.digilockeruploading');
    }
	public function digilockersuccess(Request $request)
    {
        return view('digilockerPage.digilockersuccess');
    }
	public function digilockeruploadingvedio(Request $request)
    {
        return view('digilockerPage.digilockeruploadingvedio');
    }

}
