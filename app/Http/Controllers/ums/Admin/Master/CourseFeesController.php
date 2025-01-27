<?php

namespace App\Http\Controllers\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Models\Course;
use App\Models\CourseFee;
use App\Models\Category;
use App\Models\Campuse;
use App\Models\Subject;
use App\Exports\CourseExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class CourseFeesController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $courses = CourseFee::orderBy('id', 'asc')->where('course_id',$request->course_id)->get();
        $course_details = Course::find($request->course_id);

        return view('admin.master.course-fee.index', [
            'page_title' => "Course Fees",
            'sub_title' => "records",
            'courses' => $courses,
            'course_details' => $course_details,
        ]);
    }

    public function addCourse(Request $request)
    {
        $request->validate([
            'fees_details' => 'required',
            'non_disabled_fees' => 'required',
            'disabled_fees' => 'required',
            'fees_type' => 'required',
        ]);

        $data = $request->except('_token');
        $data['course_id'] = $request->course_id;
        $data['academic_session'] = date('Y');
        CourseFee::insert($data);
        return back()->with('success','Saved');
    }

    public function editCourse(Request $request)
    {

        $request->validate([
            'course_name' => 'required',
            'category_id' => 'required',
            'course_description' => 'required',
            'campus_id' => 'required',
        ]);
        $update_category = Course::where('id', $request->course_id)->update(['name' => $request->course_name,'category_id' => $request->category_id, 'updated_by' => 1,'course_description' => $request->course_description, 'campus_id' => $request->campus_id,'color_code' => $request->color_code]);
        return redirect()->route('get-course');
    }


    public function editcourses(Request $request, $slug)
    {
        $selectedCourse = Course::where('id', $slug)->first();
        $category = Category::all();
        $campus = Campuse::all();
        $subjects = Subject::all();
        return view('admin.master.course-fee.editcourse', [
            'page_title' => $selectedCourse->name,
            'sub_title' => "Edit Information",
            'categorylist' => $category,
            'selected_course' => $selectedCourse,
            'campuslist' => $campus,
            'subjects' => $subjects,
        ]);
    }

    public function addSubjects(Request $request, $id) {
        $course = Course::find($id);

        $course->subjects()->sync($request->subject_id);

        return back()->with('success','Added Successfully');
    }


    public function show()
    {
        return view('admin.ProductCategory.view');
    }

    public function edit($id)
    {
        $productCategory = ProductCategory::find($id);
        $parents = ProductCategory::whereNull('parent_id')->get();


        return view(
            'admin.master.course-fee.edit',
            array(
                'parents' => $parents,
                'productCategory' => $productCategory
            )
        );
    }

    public function softDelete(Request $request,$slug) {
        
        Course::where('id', $slug)->delete();
        return redirect()->route('get-course');
        
    }
    public function courseExport(Request $request)
    {
        return Excel::download(new CourseExport($request), 'Course.xlsx');
    } 
}