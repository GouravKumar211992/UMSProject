<?php
namespace App\Http\Controllers\Admin;

use Session;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\MailBox;
use App\Models\Address;
use App\Models\Country;
use App\Models\Currency;
use App\Models\SocialMedia;
use App\Models\ServerWelcomeEmail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Validator;
use App\Services\Mailers\Mailer;
use App\Helpers\ConstantHelper;
use App\Helpers\GeneralHelper;
use App\Models\Admin;
use Mail;
use Hash;

class LoginController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index()
    {
        if(Auth::guard('admin')->check()){
            return redirect('admin');
        }
        session(['link' => url()->previous()]);

        if (view()->exists('auth.authenticate')) {
            return view('auth.authenticate');
        }
        return view('admin.auth.login');
    }


<<<<<<< HEAD
	public function login(Request $request){

        $this->validate($request, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
            // 'g-recaptcha-response' => 'required|recaptchav3:adminportal,0.5',

		],
		[
			'password.required' => 'Password field is required',
			'g-recaptcha-response.required' => 'Google Captcha field is required.',
			// 'g-recaptcha-response.validate' => 'Google Captcha field is not validated.',
		]); 

        $credentials = $request->only('email', 'password');
        $credentials['password'] = $request->password;

        $remember = $request->has('remember') ? true : false;

        if (Auth::guard('admin')->attempt($credentials,$remember)) {
            $userData = Admin::where('email', '=', $request->email)
                ->first();

            if ($userData && $userData->status != 'active') {
                $errorMsg = "Your account is deactivated.";
                return view('admin.auth.login', array('errorMsg' => $errorMsg));
            }

            return redirect('admin');
        } else {
            $errorMsg = "Incorrect Username Or Password";
            return view('admin.auth.login', array('errorMsg' => $errorMsg));
        }


	}

=======
	public function login(Request $request)
{
    $this->validate($request, [
        'email' => ['required', 'string', 'email'],
        'password' => ['required'],
    ], [
        'password.required' => 'Password field is required',
        // 'g-recaptcha-response.required' => 'Google Captcha field is required.',
    ]);

    // Fetch all admins with the same email, ordered by created_at in descending order
    $admins = Admin::where('email', $request->email)->orderBy('created_at', 'desc')->get();



    // Loop through each admin record and check the password
    foreach ($admins as $admin) {
        if (\Hash::check($request->password, $admin->password)) {
            // If password matches, log the user in and return to admin dashboard
            \Auth::guard('admin')->login($admin);
            
            // Check the status of the user
            if ($admin->status != 'active') {
                return view('admin.auth.login', ['errorMsg' => "Your account is deactivated."]);
            }

            return redirect('admin');
        }
    }

    return view('admin.auth.login', ['errorMsg' => "User not found or incorrect password."]);
}
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7



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
        if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == '1'){
            Auth::guard('admin')->logout();
            return redirect('admin');
        }else{
            Auth::guard('admin')->logout();
            return redirect('hod/login');
        }
    }
	
    
	 public function forgotPassword(Request $request){
		return view('admin.auth.forgot-password');
	}
	
    public function forgotPasswordSave(Request $request){
		$validator = Validator::make($request->all(), [
            'email' => 'required|email',
		]);

		if ($validator->fails()) {    
			return back()->withErrors($validator->errors())->withInput($request->all());
		}

		$user = Admin::whereEmail($request->email)->first();
		$token = sha1(time());
		if($user) {
			$user->remember_token = $token;
			$user->save();
			$email = $request->email;
			$name = $user->user_name;
			$subject = 'Forgot Password Change';
			$content = url('admin/forgot-password-change').'?token='.$token;
			Mail::raw($content, function ($message) use ($email,$name,$subject){
				$message->from('rahulsharma7860@gmail.com', 'Your Application');
				$message->to($email, $name)->subject($subject);
			});
			return back()->with('success','Password Change link has been sent on your email ID');
		}else{
			return back()->with('error','Invalid Email ID. Please register first');
		}
	}

    public function forgotPasswordChange(Request $request)
    {
		if($request->token==null){
			$data['user'] = null;
		}else{
			$data['user'] = Admin::where('remember_token',$request->token)->first();
		}
		return view('admin.auth.forgot-password-change',$data);
    }

    public function forgotPasswordChangeSave(Request $request){
		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
		]);

		if ($validator->fails()) {    
			return back()->withInput()->withErrors($validator);
		}
		$user = Admin::whereEmail($request->email)->first();
		if (Hash::check($request->password, $user->password)) {
			return back()->with('error',"Don't use your current password");
		}
		$user->password = $request->password;
		$user->remember_token = null;
		$user->save();
		return redirect('/admin/login')->with('success','Password Changed Successfully');

    }
	public function passwordChangeSave(Request $request){
		//dd($request->all());
		$validator = $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','string','min:8'],
            'confirm_password' => 'required_with:password|same:password|min:8',
            
        ]);
		$user = Admin::whereEmail($request->email)->first();
		if (Hash::check($request->password, $user->password)) {
			return back()->with('error',"Don't use your current password");
		}
		$user->password = $request->password;
		$user->save();
		//dd($request->all());
		
		return redirect('/admin')->with('success','Password Changed Successfully');
	}

}
