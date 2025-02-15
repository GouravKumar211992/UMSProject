<?php

namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;


use App\Models\User;

use App\Helpers\ConstantHelper;
use App\Http\Controllers\ums\AdminController;
use App\Models\Address;
use App\Models\ums\Application;
use App\Models\ums\Enrollment;
use App\Models\ums\Course;
use App\Models\ums\Faculty;
use App\Models\ums\Admin;
use App\Models\InternalMarksMapping;
use Illuminate\Support\Facades\DB;




class DashboardController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        View::share('menu_id', 'menu_dashboard');
        View::share('submenu_id', 'NA');
    }

    public function index(Request $request)
    {
        $session = '2024-2025';
        if($request->session){
            $session = $request->session;
        }
        // academic_session
		$month_pending=$month_approve=$month_enrolled=null;
		$application_count = Application::where('academic_session',$session)->count();
		$enrollment_count = Enrollment::where('academic_session',$session)->count();
		$courses = Course::get();
        $total_exam_count = 0;
        $total_latest_exam_count = 0;
        $total_old_exam_count = 0;
        foreach($courses as $coursesRow){
            $total_exam_count = $total_exam_count + $coursesRow->examFormCount(0,$session);
            $total_latest_exam_count = $total_latest_exam_count + $coursesRow->examFormCount(1,$session);
            $total_old_exam_count = $total_old_exam_count + $coursesRow->examFormCount(2,$session);
        }
		
        $total_application = Application::select('application_for','campuse_id','category_id','course_id','user_id')->where('academic_session',$session)->whereIn('payment_status' , [0,1,2])->where('user_id','!=',0)->distinct('application_for','campuse_id','category_id','course_id','user_id')->count();
        $pending_application = Application::select('application_for','campuse_id','category_id','course_id','user_id')->where('academic_session',$session)->where('payment_status' , 0)->where('user_id','!=',0)->distinct('application_for','campuse_id','category_id','course_id','user_id')->count();
        $paid_application = Application::select('application_for','campuse_id','category_id','course_id','user_id')->where('academic_session',$session)->where('payment_status' , 1)->where('user_id','!=',0)->distinct('application_for','campuse_id','category_id','course_id','user_id')->count();
        $failed_application = Application::select('application_for','campuse_id','category_id','course_id','user_id')->where('academic_session',$session)->where('payment_status' , 2)->where('user_id','!=',0)->distinct('application_for','campuse_id','category_id','course_id','user_id')->count();

        // Application Counts for Non-PhD Course
        $all_application_data = Application::has('campus')->select('campuse_id','course_id',DB::RAW('count(1) as total_applications'))
        ->groupBy('campuse_id','course_id')
        ->where('academic_session',$session)
        ->where('course_id','!=',94)
        ->where('user_id','!=',0)
        ->orderby('campuse_id','ASC')
        ->orderby('course_id','ASC')
        ->get();
        foreach($all_application_data as $application_data){
            $application_data_query = Application::where('user_id','!=',0)
                ->where('academic_session',$session)
                ->where('campuse_id',$application_data->campuse_id)
                ->where('course_id',$application_data->course_id);

            $payment_paid_clone = clone $application_data_query;
            $payment_failed_clone = clone $application_data_query;

            $application_data->payment_pending = $application_data_query->where('payment_status' , 0)->count();
            $application_data->payment_paid = $payment_paid_clone->where('payment_status' , 1)->count();
            $application_data->payment_failed = $payment_failed_clone->where('payment_status' , 2)->count();
        }
        // Application Counts only for PhD Course
        $all_application_data_phd = Application::has('campus')->select('campuse_id','course_id',DB::RAW('count(1) as total_applications'))
        ->groupBy('campuse_id','course_id')
        ->where('academic_session',$session)
        ->where('course_id',94)
        ->where('user_id','!=',0)
        ->orderby('campuse_id','ASC')
        ->orderby('course_id','ASC')
        ->get();
        foreach($all_application_data_phd as $application_data){
            $application_data_query = Application::where('user_id','!=',0)
                ->where('academic_session',$session)
                ->where('campuse_id',$application_data->campuse_id)
                ->where('course_id',$application_data->course_id);

            $payment_paid_clone = clone $application_data_query;
            $payment_failed_clone = clone $application_data_query;

            $application_data->payment_pending = $application_data_query->where('payment_status' , 0)->count();
            $application_data->payment_paid = $payment_paid_clone->where('payment_status' , 1)->count();
            $application_data->payment_failed = $payment_failed_clone->where('payment_status' , 2)->count();
        }

        // $facultyDetails = Faculty::where('email',Auth::guard('admin')->user()->email)->first();
        $hodCounts = Admin::where('role',2)->count();
        $pending = array();
       
        return view('ums.dashboard',compact('application_count','enrollment_count','courses','pending','month_enrolled','month_approve','month_pending','pending_application','paid_application','failed_application','total_application','all_application_data','all_application_data_phd','total_exam_count','total_latest_exam_count','total_old_exam_count','session'));
    }

    public function list()
    {
        return view('admin.dashboard.list');
    }

    public function form1()
    {
        return view('admin.dashboard.form1');
    }

    public function form2()
    {
        return view('admin.dashboard.form2');
    }

    public function form3()
    {
       
        return view('admin.dashboard.form3');
    }

    public function pendingPpprovals()
    {
        $pendingDesigners = Designer::whereStatus(ConstantHelper::PENDING)->get();
        $pendingPortfolios = Portfolio::with('designer')
            ->whereStatus(ConstantHelper::PENDING)->get();
        $pendingProducts = Product::with('subCategories', 'categories')
            ->whereStatus(ConstantHelper::PENDING)->get();

        return view('admin.dashboard.pending-approval', [
            'pendingProducts' => $pendingProducts,
            'pendingDesigners' => $pendingDesigners,
            'pendingPortfolios' => $pendingPortfolios,
        ]);
    }

    public function unauthorized()
    {
        return view('admin.dashboard.unauthorized', array('current_page' => ''));
    }

    public function viewPortfolios(Portfolio $portfolio)
    {
        return view('admin.dashboard.view-portfolio', [
            'portfolio' => $portfolio
        ]);
    }

    
    public function adminProfile()
    {
        $email = Auth::guard('admin')->user()->email;
        
        $admins = Admin::whereEmail($email)
        ->first();        
        return view('admin.dashboard.profile',['adminData' => $admins]);
    }
    
}