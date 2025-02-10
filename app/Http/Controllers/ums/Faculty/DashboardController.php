<?php

namespace App\Http\Controllers\ums\Faculty;

use View;
use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Helpers\ConstantHelper;
use App\Http\Controllers\ums\AdminController;
use App\Models\ums\Address;
use App\Models\ums\Faculty;
use App\Models\ums\InternalMarksMapping;
use App\Models\ums\StudentSubject;
use App\Models\ums\HolidayCalenderModel;
use App\Models\ums\AcademicSession;
use App\Models\ums\InternalMark;

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
		$user=Auth::guard('faculty')->user()->id;
		if($request->session){
			//dd($request->session);
		$mapped_papers=InternalMarksMapping::where('faculty_id',$user)
							->orderBy('id', 'DESC')->get();
							
		$student=StudentSubject::join("internal_marks_mappings",function($join){
            $join->on("internal_marks_mappings.sub_code","=","student_subjects.sub_code")
                ->on("internal_marks_mappings.course_id","=","student_subjects.course_id")
                ->on("internal_marks_mappings.semester_id","=","student_subjects.semester_id");
					})
					->select('student_subjects.roll_number')
					->distinct()
					->where('internal_marks_mappings.faculty_id',$user)
					->where('student_subjects.session',$request->session);
					//dd($student->get());
		$internalmark=InternalMark::where('session',$request->session)
					->where('faculty_id',$user)->get();
		$duplicate_roll_no = InternalMark::where(['faculty_id'=>$user,'session'=>$request->session])
				->pluck('roll_number')
				->toArray();
		$session=AcademicSession::get();
		$internal_marks_filled=InternalMark::where(['faculty_id'=>$user,'session'=>$request->session])
			->get();
		$paper_code=$mapped_papers->pluck('sub_code')->toArray();

		$data['mapped_papers']=count($mapped_papers);
		$data['student_count']=count($student->get());
		$data['sessions']=$session;
		$data['internal_marks_filled']=count($internal_marks_filled);
		$pending = $student->whereNotIn('roll_number',$duplicate_roll_no);
		$data['pending']=count($pending->get());
        return view('faculty.dashboard.index',$data);
		}
		$mapped_papers=InternalMarksMapping::where('faculty_id',$user)
							->orderBy('id', 'DESC')->get();
		$student=StudentSubject::join("internal_marks_mappings",function($join){
            $join->on("internal_marks_mappings.sub_code","=","student_subjects.sub_code")
                ->on("internal_marks_mappings.course_id","=","student_subjects.course_id")
				->on("internal_marks_mappings.semester_id","=","student_subjects.semester_id");
				})
			->select('student_subjects.roll_number')
			->distinct()
			->where('internal_marks_mappings.faculty_id',$user);
		//dd($student->get());
		$internal_marks_filled=InternalMark::where('faculty_id',$user)->get();
		$duplicate_roll_no = InternalMark::where(['faculty_id'=>$user])->pluck('roll_number')->toArray();
		//dd($duplicate_roll_no);
		$paper_code=$mapped_papers->pluck('sub_code')->toArray();
		$total_student=$student;
		$session=AcademicSession::get();
		//dd($student[0]);
		$data['mapped_papers']=count($mapped_papers);
		$data['student_count']=count($student->get());
		$data['sessions']=$session;
		$data['internal_marks_filled']=count($internal_marks_filled);
		$pending = $student->whereNotIn('roll_number',$duplicate_roll_no);
		$data['pending']=count($pending->get());
        return view('faculty.dashboard.index',$data);
    }

    public function profile()
    {
		$email=Auth::guard('faculty')->user()->email;
		
		$faculty=Faculty::whereEmail($email)
		->first();
		
        return view('faculty.dashboard.profile',['faculty'=>$faculty]);
    }

   

    public function pendingPpprovals()
    {
        $pendingDesigners = Designer::whereStatus(ConstantHelper::PENDING)->get();
        $pendingPortfolios = Portfolio::with('designer')
            ->whereStatus(ConstantHelper::PENDING)->get();
        $pendingProducts = Product::with('subCategories', 'categories')
            ->whereStatus(ConstantHelper::PENDING)->get();

        return view('faculty.dashboard.pending-approval', [
            'pendingProducts' => $pendingProducts,
            'pendingDesigners' => $pendingDesigners,
            'pendingPortfolios' => $pendingPortfolios,
        ]);
    }

    public function unauthorized()
    {
        return view('faculty.dashboard.unauthorized', array('current_page' => ''));
    }

    public function viewPortfolios(Portfolio $portfolio)
    {
        return view('faculty.dashboard.view-portfolio', [
            'portfolio' => $portfolio
        ]);
    }

	public function holidayCalenderForFaculty()
	{
		$holidayCalendor = HolidayCalenderModel::all();
		return view('ums.master.faculty.holiday_calender',['holidayCalendor'=>$holidayCalendor]);
	}
}