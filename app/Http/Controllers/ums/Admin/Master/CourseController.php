<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Models\Course;
use App\Models\RequiredQualification;
use App\Models\Category;
use App\Models\Campuse;
use App\Models\Subject;
use App\Models\Semester;
=======
namespace App\Http\Controllers\ums\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;

use App\Models\ums\Course;
use App\Models\ums\RequiredQualification;
use App\Models\ums\Category;
use App\Models\ums\Campuse;
use App\Models\ums\Subject;
use App\Models\ums\Semester;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use App\Exports\CourseExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class CourseController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $courses = Course::with(['category','campuse'])->orderBy('id', 'DESC');
        // dd($courses);
        if($request->search) {
            $keyword = $request->search;
            $courses->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            $courses->where('name','LIKE', '%'.$request->name.'%');
        }
        if (!empty($request->category_id)) {
            $courses->where('category_id',$request->category_id);
        }
        if (!empty($request->campuse_id)) {
            $courses->where('campus_id',$request->campuse_id);
        }
        if ($request->visibility != null) {
            $courses->where('visible_in_application',$request->visibility);
        }
        $courses = $courses->paginate(10);
        
        $categories = Category::all();
        $campuses = Campuse::all();        
<<<<<<< HEAD
        return view('admin.master.course.index', [
=======
        return view('ums.master.course.course_list', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Courses",
            'sub_title' => "records",
            'all_courses' => $courses,
            'categories' => $categories,
            'campuses' => $campuses

        ]);
    }

    public function add(Request $request)
    {
        $category = Category::all();
        $campus = Campuse::all();
        $required_qualification = RequiredQualification::all();
<<<<<<< HEAD
        return view('admin.master.course.addcourse', [
=======
        return view('ums.Master.course.add_course', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Add New",
            'sub_title' => "Course",
            'categorylist' => $category,
            'campuslist' => $campus,
            'required_qualification' => $required_qualification,
        ]);
    }

    public function addCourse(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'course_description' => 'required',
            'campus_id' => 'required',
            'color_code' => 'required',
            'total_semester_number' => 'required',
            'semester_type' => 'required',
            'required_qualification_data' => 'required',
            'cuet_status' => 'required'
        ]);

        $data = $request->all();
        $data['required_qualification'] = implode(',', $request->required_qualification_data);
        $course = new Course();
        $course->fill($data);
        $course->save();
<<<<<<< HEAD
        return redirect('admin/master/course/')->with('success','Saved Successfully');
=======
        return redirect()->route('course_list')->with('success','Saved Successfully');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }

    public function editCourse(Request $request)
    {
        
        $request->validate([
            'course_name' => 'required',
            'category_id' => 'required',
            'course_description' => 'required',
            'campus_id' => 'required',
			'total_semester_number' => 'required',
            'required_qualification_data' => 'required',
            'roll_number' => 'required',
            'cuet_status' => 'required',
        ]);
        $cate_array = [
            'name' => $request->course_name,
            'category_id' => $request->category_id,
            'updated_by' => 1,
            'course_description' => $request->course_description,
            'campus_id' => $request->campus_id,
            'color_code' => $request->color_code,
            'total_semester_number' => $request->total_semester_number,
            'visible_in_application' => $request->visible_in_application,
            'required_qualification' => implode(',',$request->required_qualification_data),
            'course_group' => ($request->course_group)?implode(',',$request->course_group):null,
            'roll_number' => $request->roll_number,
            'cuet_status' => $request->cuet_status,
        ];
                
        Course::where('id', $request->course_id)->update($cate_array);
<<<<<<< HEAD
        return back()->with('success','Done');
=======
        return redirect()->route('course_list')->with('success','Saved Successfully');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }


    public function editcourses(Request $request, $slug)
    {
        $selectedCourse = Course::where('id', $slug)->first();
        $category = Category::all();
        $campus = Campuse::all();
        $subjects = Subject::all();
        $required_qualification = RequiredQualification::all();
        $courses = Course::where('campus_id', $selectedCourse->campus_id)
        ->orderBy('name','ASC')
        ->get();
<<<<<<< HEAD
        return view('admin.master.course.editcourse', [
=======
        return view('ums.master.course.course_edit', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => $selectedCourse->name,
            'sub_title' => "Edit Information",
            'categorylist' => $category,
            'selected_course' => $selectedCourse,
            'campuslist' => $campus,
            'subjects' => $subjects,
            'required_qualification' => $required_qualification,
            'courses' => $courses,
        ]);
    }
    // public function semesterOrdering(Request $request, $slug)
    // {
    //     $semOrdering = Semester::where('course_id', $slug)->get();
    //     return view('admin.master.course.semester-ordering', [
    //         'semester_ordering' => $semOrdering,
    //     ]);
    // }

    // public function saveOrdering(Request $request)
    // {
    //     dd($request->all());
    //     $semOrdering = Semester::where('course_id', $slug)->get();
    //     return view('admin.master.course.semester-ordering', [
    //         'semester_ordering' => $semOrdering,
    //     ]);
    // }

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
            'admin.master.category.edit',
            array(
                'parents' => $parents,
                'productCategory' => $productCategory
            )
        );
    }

    public function softDelete(Request $request,$slug) {
        
        Course::where('id', $slug)->delete();
<<<<<<< HEAD
        return redirect('admin/master/course/')->with('success','Deleted Successfully');
=======
        return redirect('course_list')->with('success','Deleted Successfully');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
        
    }
    public function courseExport(Request $request)
    {
        return Excel::download(new CourseExport($request), 'Course.xlsx');
    } 
}