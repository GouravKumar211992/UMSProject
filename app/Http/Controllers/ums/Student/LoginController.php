<?php
namespace App\Http\Controllers\ums\Student;

use Session;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\ums\MailBox;
use App\Models\ums\Address;
use App\Models\ums\Country;
use App\Models\ums\Currency;
use App\Models\ums\SocialMedia;
use App\Models\ums\ServerWelcomeEmail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Validator;
use App\Services\Mailers\Mailer;
use App\Helpers\ConstantHelper;
use App\Helpers\GeneralHelper;
use App\Models\ums\Student;
use Mail;
use Hash;
use App\Jobs\TrackingEmailFrequency;


class LoginController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index()
    {
        if(Auth::guard('student')->check()){
            $dashboard_url = '/student/dashboard';
            // if(Auth::guard('student')->user()->exam_portal==0){
            //     $dashboard_url = '/student/exam-form';
            // }
            return redirect($dashboard_url);
        }
        // session(['link' => url()->previous()]);
        return view('student.auth.login');
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
        if(!$userData){
            $userData = Student::where('roll_number', $request->email)->orderBy('id','DESC')->first();
            if($userData && (campus_name($userData->enrollment_no)==5 || campus_name($userData->enrollment_no)==1)){
                if(Hash::check($request->password, $userData->password)){
                    Auth::guard('student')->login($userData);
                    if($request->exam_portal==1){
                        // $userData->exam_portal = 1;
                        // $userData->save();
                        return redirect('student/dashboard');
                    }else{
                        // $userData->exam_portal = 0;
                        // $userData->save();
                        return redirect('student/dashboard');
                    }
                }else{
                    $errorMsg = "Incorrect Username Or Password";
                    return view('student.auth.login', array('errorMsg' => $errorMsg));
                }
            }
        }

        $credentials = ['email'=>$request->email, 'password'=>$request->password];

        $remember = $request->has('remember') ? true : false;

        if (\Auth::guard('student')->attempt($credentials,$remember)) {
            $userData = Student::where('email', '=', $request->email)
                ->first();
            if ($userData && $userData->status != 'active') {
                $errorMsg = "Your account is deactivated.";
                return view('student.auth.login', array('errorMsg' => $errorMsg));
            }
            if($request->exam_portal==1){
                // $userData->exam_portal = 1;
                // $userData->save();
//                dd('');
                // return redirect('student/exam-form?exam_portal=1');
                return redirect('student/dashboard');
            }else{
                $userData->exam_portal = 0;
                $userData->save();
            }
            // return redirect()->intended('/student/exam-form?exam_portal=1');
            return redirect('student/dashboard');

            // if(in_array($userData->roll_number, challenge_allowed()) ){
            //     return redirect()->intended('/student/exam-form?exam_portal=1');
            // }else{
            //     $errorMsg = "Your are not allowed to fill challenge evaluation form.";
            //         return view('student.auth.login', array('errorMsg' => $errorMsg));
            // }
//            return redirect()->intended('/student/dashboard');
        } else {
            $errorMsg = "Incorrect Username Or Password";
            return view('student.auth.login', array('errorMsg' => $errorMsg));
        }


    }

	public function secretLogin($id,Request $request){
		$user = Student::find($id);
		if($user){
            $user->exam_portal = 0;
            $user->save();
			Auth::guard('student')->login($user);
			return redirect('stu-dashboard');
		}else{
			return back()->with('error','Invalid ID');
		}
	}



    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => ['required','string','email'],
            'password' => ['required','string'],
        ]);

        $credentials = $request->only('email', 'password');

        $userData = User::where('email', '=', $request->email)->first();

        if(!$userData) {
            $errorMsg = "User does not exist";
            return array(
                "status" => 'error',
                "code" => 204,
                "message" => $errorMsg
            );
            
        }

        if($userData && (!$userData->password )) {
            $errorMsg = "Your password has been expired please reset your password";
            return array(
                "status" => 'error',
                "code" => 204,
                "message" => $errorMsg
            );
            
        }
        if (Auth::attempt($credentials)) {
            $userData = User::where('email', '=', $request->email)->first();
            // if($userData->status == 'pending'){
            //     $errorMsg = "Your account is not activated yet.";
            //     return view('frontend.login.index',array('errorMsg' => $errorMsg));
            // }
            // if($userData->status == 'inactive'){
            //     $errorMsg = "Your account is deactivated.";
            //     // return view('frontend.login.index',array('errorMsg' => $errorMsg));
            //     return $errorMsg;
            // }

            if($request->session()->get('cart')) {
                GeneralHelper::addUserCartDataFromSession($userData, $request->session()->get('cart'));
                $request->session()->forget('cart');
                $request->session()->save();
            }
            // Authentication passed...

            // if ($request->route) {
            //     return redirect(route($request->route));
            // }
            // if($request->is_url == 'checkout'){
            //     return redirect()->intended('/checkout');
            // }
            // else{
            //     if($request->route) {
            //         return redirect(route($request->route));
            //     }
            //     return redirect()->intended('/');
            // }
            $successMsg = "";
            return array(
                "status" => 'success',
                "code" => 200,
                "message" => $successMsg
            );
        }
        else{
            $errorMsg = "Incorrect  Password";
            return array(
                "status" => 'error',
                "code" => 204,
                "message" => $errorMsg
            );
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required','string','max:99'],
            'email' => ['required','string','email','max:99','unique:users,email'],
        ]);
    }

    public function signup()
    {
        return view('frontend.login.register');
    }

    public function register()
    { 
        if (auth()->user()) 
        {
            return redirect()->intended('/');
        }
        $successMessage = Session::get('successMessage');
        $errorMessage = Session::get('errorMessage');

        $currencies = Currency::all(); 
        $countries = Country::all();
        $socialLinks = SocialMedia::all();

        return view('frontend.login.register',
            array(
                'countries' => $countries,
                'currencies' => $currencies,
                'socialLinks' => $socialLinks,
                'errorMessage' => $errorMessage,
                'successMessage' => $successMessage,
            )
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function validateEmail(Request $request) {

        $mailbox = new MailBox;
        $userData = User::where('email','=',$request->email)->first();

        if($userData){
            $errorMsg = "Email Already Exist";
            return view('frontend.login.signup',array('errorMsg' => $errorMsg));
        }
        else{
            $email = $request->email;
            $name = $request->name;
            $token = base64_encode($email.time().$name);
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->token = $token;
            $user->save();

            $link = env('APP_URL', '');
            $description = 'Dear '.$user->name.'!<br/><br/>Verify your email address by clicking on the below link <br/><br/><a href="'.$link.'/login/'.$user->email.'/register/'.$token.'" target="new"><b>Confirm Email</b> </a>! <br/><br/><br/>Thanks,<br/>RedSky Team';

            $mailArray = array(
                "header" => "Email Confirmation",
                "content" => $description,
                "footer" => "System Generated Email"
            );

            $mailbox->mail_body = json_encode($mailArray);
            $mailbox->mail_to = $request->email;
            $mailbox->subject = "account activated successfully";
            $mailbox->save();

            $mailer = new Mailer;
            $mailer->emailTo($mailbox);

            if($mailer){
                $mailmsg = "mail sent successfully";
                return view('frontend.login.signup',array('mailmsg' => $mailmsg));

            }

            else{
                $mailmsg = "something went wrong.";
                return view('frontend.login.signup',array('mailmsg' => $mailmsg));
            }
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $request)
    {
        if($request->is_url == 'checkout'){
            $this->validate($request, [
                'name' => ['required','string','max:99'],
                'email' => ['required','string','email','max:99','unique:users,email'],
                'mobile' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/','unique:users', 'between:'.ConstantHelper::MOBILE_MIN_LENGTH.','.ConstantHelper::MOBILE_MAX_LENGTH],
                'password' => ['required','string','min:6'],
                'password_confirmation' => 'required_with:password|same:password|min:6',
               // 'g-recaptcha-response' => ['required'],
            ]);
        }
        else{
            $this->validate($request, [
                'name' => ['required','string','max:99'],
                'company_name' => ['required','string','max:199'],
                'email' => ['required','string','email','max:99','unique:users,email'],
                'mobile' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/','unique:users', 'between:'.ConstantHelper::MOBILE_MIN_LENGTH.','.ConstantHelper::MOBILE_MAX_LENGTH],
                'password' => ['required','string','min:6'],
                'password_confirmation' => 'required_with:password|same:password|min:6',
                'country_id' => ['required', 'integer', Rule::exists('countries', 'id')],
               // 'g-recaptcha-response' => ['required'],
            ]);
        }


        $userData = User::where('email','=',$request->email)->first();
        if($userData){
            $errorMessage = "Email Already Exist !!";
            return redirect('/login/register')->with('errorMessage', $errorMessage);
        }
        $currencyId = null;
        $country = Country::find($request->country_id);
        if($country){
            if($country->name == 'India'){
                session()->put('currency', 'INR');
                $currencyId = 1;
            }
            else{
                session()->put('currency', 'AUD');
                $currencyId = 2;
            }
        }
        $user = new User();
        $user->fill($request->all());
        $user->user_name = $request->email;
        $user->currency_id = $currencyId;
        $user->user_type = 'user';
      //  dd($user);
        $user->save();

        if($request->line_1){
            $user->address()->create($request->all());
        }

        $welcomeEmail = ServerWelcomeEmail::where('name', '=', 'Client Signup Email')
            ->first();

        $siteLink = env('APP_URL', '');
       // $verificationLink = env('APP_URL', '').'/login/verify-email?user_id='.$user->id;
        $verificationLink = 'uat.miditech.co.in/login/verify-email?user_id='.$user->id;

        if($welcomeEmail){
            $description = $welcomeEmail->description;
            $emailKeywords = array(
                "{CLIENT_NAME}" => $user->name,
                "{COMPANY_NAME}" => $user->company_name,
                "{WHMCS_LINK}" => $siteLink,
                "{VERIFICATION_LINK}" => $verificationLink,
                "{SIGNATURE}" => "",
            );
            foreach($emailKeywords as $key => $val){
                $description = str_replace("$key",$val, $description);
            }

            $mailbox = new MailBox;

            $mailArray = array(
                "name" => $user->name,
                "mobile" => $user->mobile,
                "email" => $user->email,
                "email_template_name" => $welcomeEmail->name,
                "from_name" => $welcomeEmail->fromname,
                "from_email" => $welcomeEmail->fromemail,
                "subject" => $welcomeEmail->subject,
                "copy_to" => $welcomeEmail->copyto,
                "description" => $description
            );

            $mailbox->mail_body = json_encode($mailArray);
            $mailbox->subject = $welcomeEmail->subject;
            $mailbox->mail_to = $user->email;
            if($welcomeEmail->copyto){
                $mailbox->mail_cc = $welcomeEmail->copyto;
            }
            $mailbox->category = $welcomeEmail->name;
            $mailbox->layout = "email.email-template";
            $mailbox->save();

            $mailer = new Mailer;
            $mailer->emailTo($mailbox);
        }

        if($request->session()->get('cart')) {
            GeneralHelper::addUserCartDataFromSession($user, $request->session()->get('cart'));
            $request->session()->forget('cart');
            $request->session()->save();
        }

        if($request->is_url == 'checkout'){
            \Auth::login($user);
            return redirect('/checkout');
        }
        $successMessage = "We just send you a request to confirm your registration. If you did not receive it please check your spam folder. Do contact us when you can’t find the confirmation mail.";
        return redirect('/login/register')->with('successMessage', $successMessage);
    }





    public function emailVerify(Request $request)
    {
        $user = User::find($request->user_id);
        if($user){
            $user->status = 'active';
            $user->is_email_verified = 1;
            $user->save();
        }

        $message = "Email verified successfully !!";

        return view('frontend.login.email-verification',
            array(
                "user" => $user,
                "message" => $message
            )
        );
    }


    protected function createUser(Request $request)
    {
        
        $validator = $this->validate($request, [
            'name' => ['required','string','max:99'],
            'email' => ['required','string','email','max:99','unique:users,email'],
            'password' => ['required','string','min:8'],
            'confirm_password' => 'required_with:password|same:password|min:8',
            
        ]);
        
        
        $user = new User();
        $user->fill($request->all());
        $user->user_name = $request->email;
        $user->status = "active";
        $user->save();

        return \Response::json(array("status"=>"1","message" => "Thanks your account has been created successfully. Please login to your account."), 200);
        
        
    }

    public function forgetPassword()
    {
        if (auth()->user()) 
        {
            return redirect()->intended('/');
        }
        $errorMessage = Session::get('errorMessage');
        $successMessage = Session::get('successMessage');

        return view('frontend.login.forget-password',
            array(
                "errorMessage" => $errorMessage,
                "successMessage" => $successMessage
            )
        );
    }

    public function submitPassword(Request $request)
    {
        $mailbox = new MailBox;
        $userData = User::where('email','=',$request->email)->first();

        if(!$userData){
            $errorMessage = "No client account was found with the email address you entered.";
            return redirect('/login/forget-password')->with('errorMessage', $errorMessage);
        }
        else{
            $email = $request->email;
            $name = $request->name;
            $token = base64_encode($email.time().$name);
            $userData->remember_token = $token;
            $userData->save();
            $link = env('APP_URL', '');
            $description = 'Dear '.$userData->name.' ('.$userData->company_name.')!<br/><br/>Recently a request was submitted to reset your password for our client area. If you did not request this, please ignore this email. It will expire and become useless in 2 hours time.
 <br/><br/>To reset your password, please <a href="'.$link.'/reset-password?token='.$token.'" target="new"><b>Click Here</b> </a>! <br/><br/>
 When you visit the link above, you will have the opportunity to choose a new password.';

            $mailArray = array(
                "header" => "Reset Password",
                "description" => $description,
                "footer" => "System Generated Email"
            );

            $mailbox->mail_body = json_encode($mailArray);
            $mailbox->category = "Reset password";
            $mailbox->mail_to = $userData->email;
            $mailbox->subject = "Your login details for Miditech Technical";
            $mailbox->layout = "email.email-template";
            $mailbox->save();

            $mailer = new Mailer;
            $mailer->emailTo($mailbox);

            if($mailer){
                $successMessage = "The password reset process has now been started. Please check your email for instructions on what to do next.";
                return redirect('/login/forget-password')->with('successMessage', $successMessage);

            }

            else{
                $errorMessage = "something went wrong.";
                return redirect('/login/forget-password')->with('errorMessage', $errorMessage);
            }
        }
    }

    public function reset(Request $request)
    {
        $errorMessage = Session::get('errorMessage');
        $successMessage = Session::get('successMessage');
        $user = User::where('remember_token','=',$request->token)->first();
        if($user){
            return view('frontend.login.reset',
                array(
                    'email' => $user->email,
                    'name' => $user->name,
                    'token' => $request->token,
                    "errorMessage" => $errorMessage,
                    "successMessage" => $successMessage
                )
            );
        }
        else{
            return view('frontend.login.index');
        }
    }

    public function resetPassword(Request $request)
    {
        $user = User::where('remember_token','=',$request->token)
                    ->first();
        if($user){
            $password = $request->password;
            $passwordConfirmation = $request->password_confirmation;

            if (!empty($password)) {
                if ($password === $passwordConfirmation) {
                    $user->password = $password;
                }
                else {
                    $errorMessage = "Confirmation password not matched !!";
                    return redirect()->back()->with('errorMessage', $errorMessage);
                }
                $user->save();

                $welcomeEmail = ServerWelcomeEmail::where('name', '=', 'Automated Password Reset')
                    ->first();

                $siteLink = env('APP_URL', '');

                if($welcomeEmail){
                    $description = $welcomeEmail->description;
                    $emailKeywords = array(
                        "{CLIENT_NAME}" => $user->name,
                        "{CLIENT_EMAIL}" => $user->email,
                        "{CLIENT_PASSWORD}" => $request->password,
                        "{COMPANY_NAME}" => $user->company_name,
                        "{WHMCS_LINK}" => $siteLink,
                        "{SIGNATURE}" => "",
                    );
                    foreach($emailKeywords as $key => $val){
                        $description = str_replace("$key",$val, $description);
                    }

                    $mailbox = new MailBox;

                    $mailArray = array(
                        "name" => $user->name,
                        "mobile" => $user->mobile,
                        "email" => $user->email,
                        "email_template_name" => $welcomeEmail->name,
                        "from_name" => $welcomeEmail->fromname,
                        "from_email" => $welcomeEmail->fromemail,
                        "subject" => $welcomeEmail->subject,
                        "copy_to" => $welcomeEmail->copyto,
                        "description" => $description
                    );

                    $mailbox->mail_body = json_encode($mailArray);
                    $mailbox->subject = $welcomeEmail->subject;
                    $mailbox->mail_to = $user->email;
                    if($welcomeEmail->copyto){
                        $mailbox->mail_cc = $welcomeEmail->copyto;
                    }
                    $mailbox->category = $welcomeEmail->name;
                    $mailbox->layout = "email.email-template";
                    $mailbox->save();

                    $mailer = new Mailer;
                    $mailer->emailTo($mailbox);
                }
            }

            $successMessage = "Password changed successfully !!";
            return redirect()->back()->with('successMessage', $successMessage);

        }
        else{
            return view('frontend.login.index');
        }
    }

    public function logout(Request $request) {
        $logout_url = '/student/login';
        if(Auth::guard('student')->user()->exam_portal==1){
            $logout_url = '/exam/login?exam_portal=1';
        }
        \Auth::guard('student')->logout();
        return redirect($logout_url);
    }
    
    
    
    public function forgotPassword(Request $request){
        return view('student.auth.forgot-password');
    }
    
    public function forgotPasswordSave(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {    
            return back()->withErrors($validator->errors())->withInput($request->all());
        }

        $user = Student::whereEmail($request->email)->first();
        $token = sha1(time());
        if($user) {
            $user->remember_token = $token;
            $user->save();
            $sendEmail['email'] = $request->email;
            $sendEmail['firstname'] = $user->first_Name.' '.$user->middle_Name.' '.$user->last_Name;
            $sendEmail['content'] = url('student/forgot-password-student-login').'?token='.$token;
            dispatch((new TrackingEmailFrequency($sendEmail,'student_forgot_pass'))->onQueue('high'));
            // Mail::raw($content, function ($message) use ($email,$name,$subject){
            //  $message->from('rahulsharma7860@gmail.com', 'Your Application');
            //  $message->to($email, $name)->subject($subject);
            // });
            return back()->with('success','Password Change link has been sent on your email ID');
        }else{
            return back()->with('error','Invalid Email ID. Please register first');
        }
    }


    public function forgotPasswordStudent(Request $request)
    {
        if($request->token==null){
            $data['user'] = null;
        }else{
            $data['user'] = Student::where('remember_token',$request->token)->first();
        }
        return view('frontend.forgot.forgot_password_exam_login',$data);
    }

    public function forgotPasswordStudentSave(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {    
            return back()->withInput()->withErrors($validator);
        }
        $user = Student::whereEmail($request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error',"Don't use your current password");
        }
        $user->password = $request->password;
        $user->remember_token = null;
        $user->save();
//      dd($user);
        return back()->with('success','Password Changed Successfully');

    }

    // Change password function
    public function changeStudentPass(){
        return view('student.change-password.change_password');
    }

    // Save password
    public function studentPassSave(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => ['required','string','min:8'],
            'confirm_password' => 'required_with:new_password|same:new_password|min:8',
            
        ]);

        if ($validator->fails()) {    
            return back()->withInput()->withErrors($validator);
        }
        $student = Student::where(['email' => Auth::guard('student')->user()->email])->first();
        // dd($student);
        $data = array(
        'password'=> \Hash::make($request->new_password),
         );
        
        if($student){
            Student::where('email', $student->email)->update($data);
           return redirect()->back()->with('success', 'Password changed successfully.');
        }
    }


}
