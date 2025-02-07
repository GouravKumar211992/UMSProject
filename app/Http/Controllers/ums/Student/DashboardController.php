<?php

namespace App\Http\Controllers\ums\Student;

use Illuminate\Support\Facades\View;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\User;
use Illuminate\Http\Request;
use App\Helpers\ConstantHelper;
use App\Http\Controllers\ums\AdminController;
use App\Models\ums\Address;
use App\Models\ums\Icard;
use App\Models\ums\Student;
use App\Models\ums\Semester;
use App\Models\ums\HolidayCalenderModel;
use App\Models\ums\DisabilityCategory;


class DashboardController extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        View::share('menu_id', 'menu_dashboard');
        View::share('submenu_id', 'NA');
    }

    public function index()
    {
        // Auth::guard('student')->logout();
        // return redirect('exam/login?exam_portal=1')->with('error','You are not authorized');
        $data['icard'] = Icard::where('enrolment_number',Auth::guard('student')->user()->enrollment_no)->first();
        return view('ums.student.student_dashboard',$data)->with('success','Login Successfully');
    }
    public function profile()
    {
		$data['student'] = Auth::guard('student')->user();
        // dd($student);
		// $email=Auth::guard('student')->user()->email;

		// // $student=Student::whereEmail($email)
		// // ->first();
        // $data['student'] = Student::select('students.*','icards.*','student_semester_fees.*')
        //                     ->join('icards','icards.roll_no','students.roll_number')
        //                     ->join('student_semester_fees','student_semester_fees.enrollment_no','students.enrollment_no')
        //                     ->where('students.email', $email)
        //                     ->first();
        if(isset($data['student'])){
            $data['semester'] = Semester::where('id', $data['student']->semester_id)->first();
        }
		// dd($data);
        return view('ums.student.profile', $data);
    }

    public function editStudent($roll_number)
    {
        $roll_number = base64_decode($roll_number);
        $student=Student::where('roll_number',$roll_number)->first();
        $disabilitycategories = DisabilityCategory::all();

        return view('student.dashboard.profile-update-data',compact('student','disabilitycategories'));
    }

    public function saveStudent(Request $request, $id)
    {
        $request->validate([
            'first_Name'=>'required',
            'hindi_name'=>'required',
            'father_first_name'=>'required',
            'mother_first_name'=>'required',
            'email'=>'required',
            'date_of_birth'=>'required|date',
            'mobile'=>'required',
        ]);

        $student = Student::find($id);
        if($request->photo){
            $student->addMediaFromRequest('photo')->toMediaCollection('photo');
        }
        if($request->signature){
            $student->addMediaFromRequest('signature')->toMediaCollection('signature');
        }
        $student->fill($request->all());
        $student->date_of_birth = date('Y-m-d',strtotime($request->date_of_birth));
        $student->save();
        return redirect()->route('student-profile')->with('success','Profile Saved Successfully.');
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

    public function holidayCalenderForStudent()
   {
       $holidayCalendor = HolidayCalenderModel::all();
       return view('student.dashboard.calender',['holidayCalendor'=>$holidayCalendor]);
   }
}
