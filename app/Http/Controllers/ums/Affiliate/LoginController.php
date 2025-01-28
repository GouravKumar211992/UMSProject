<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Affiliate\LoginController;
use App\Models\Campuse;
use Session;
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
use App\Models\AffiliateAdmin;

use Mail;
use Hash;
class LoginController extends Controller
{
    public function index(Request $request)
    { 
        return view('affiliate.auth.login');
    }

    

    public function login(Request $request){

        $this->validate($request, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required'],
            //'affiliated_collage'=>['required'],
            'g-recaptcha-response' => 'required|captcha',
        ],
        [
            'password.required' => 'Password field is required',
            'g-recaptcha-response.required' => 'Google Captcha field is required.',
        ]); 

        $credentials = $request->only('email', 'password');
        $credentials['password'] = $request->password;

        $remember = $request->has('remember') ? true : false;

        $campuse_id=Campuse::whereEmail($request->email)->first();
       // dd($campuse_id);
       if (\Auth::guard('affiliate')->attempt($credentials,$remember)) {
            $userData = AffiliateAdmin::where('email', '=', $request->email)
                /*->where('affiliated_id', '=', $campuse_id->id)*/->first();
             //dd($userData); 
          $request->session()->put('user',$userData['name']);
        // dd(session('user'));
            if ($userData  && $userData->status != 'active') {
                $errorMsg = "Your account is deactivated.";
                return view('affiliate.auth.login', array('errorMsg' => $errorMsg));
            }
            return redirect()->intended('affiliate/home');
        } else {
            $errorMsg = "Incorrect Username Or Password";
            return view('affiliate.auth.login', array('errorMsg' => $errorMsg));
        }

    }
      
       public function logout(Request $request) {
       
      \Auth::guard('affiliate')->logout();
       return redirect()->route('affiliate-login');
    }
    
 public function forgotPassword(Request $request)
    {  
        if (auth()->user()) 
        {
            return redirect()->intended('/');
        }
        $errorMessage = Session::get('errorMessage');
        $successMessage = Session::get('successMessage');

        return view('affiliate.auth.forgot-password', 
            array(
                "errorMessage" => $errorMessage,
                "successMessage" => $successMessage
            ));
    }

    
    public function forgotPasswordSave(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {    
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
     
        $user = AffiliateAdmin::whereEmail($request->email)->first();
        $token = sha1(time());
        if($user) {
            $user->remember_token = $token;
            $user->save();
            $email = $request->email;
            $name = $user->name;
            $subject = 'Forgot Password Change';
            $content = url('affiliate/forgot-password-change').'?token='.$token;
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
            $data['user'] = AffiliateAdmin::where('remember_token',$request->token)->first();
        }
        return view('affiliate.auth.forgot-password-change',$data);
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
        $user = AffiliateAdmin::whereEmail($request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error',"Don't use your current password");
        }
        $user->password = $request->password;
        $user->remember_token = null;
        $user->save();
        return redirect('/affiliate/login')->with('success','Password Changed Successfully');

    }
    public function passwordChangeSave(Request $request){
        //dd($request->all());
        $validator = $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','string','min:8'],
            'confirm_password' => 'required_with:password|same:password|min:8',
            
        ]);
        $user = AffiliateAdmin::whereEmail($request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            return back()->with('error',"Don't use your current password");
        }
        $user->password = $request->password;
        $user->save();
        //dd($request->all());
        
        return redirect('/affiliate/home
            ')->with('success','Password Changed Successfully');
    }

   
    
}
