<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\User;
use App\Models\Application;
use App\Models\CourseType;
use App\Models\EducationDetails;
use App\Models\StudentDetails;
use App\Models\PermanentAddress;
use App\Models\UploadDocuments;
use App\Models\PersonalInformations;
use App\Models\PaymentDetails;
use App\Models\Course;
use App\Models\CourseFee;
use App\Models\Category;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Models\EnrollmentSubject;
use App\Exports\AdmissionExport;
use App\Models\Campuse;
use App\Models\Icard;
use App\Models\Classe;
use App\Models\StudentSemesterFee;
use App\Models\StudentSubject;
use App\Models\CourseSubject;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Auth;

class ClassController extends AdminController
{
     public function __construct()
    {
        parent::__construct();
    }
	
	public function index(Request $request)
    {

        $classes = Classe::orderBy('id', 'DESC');

        if ($request->search) {
            $keyword = $request->search;
            $applications->where(function ($q) use ($keyword) {

                $q->where('application_no', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('first_Name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $keyword . '%');
            });
        }

        $classes = $classes->paginate(10);

        $categories = Category::all();
        $courses = Course::all();

        return view('admin.internal.index', [
            'page_title' => "Internal Mark Mapping",
            'sub_title' => "records",
            
            'classes'  => $classes
        ]);
    }
	public function addclass()
	{
		return view('admin.internal.add');
	}

}
