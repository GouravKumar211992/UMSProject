<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
=======
namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ums\AdminController;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

use App\Imports\CourseSwitchingImport;
use Maatwebsite\Excel\Facades\Excel;

<<<<<<< HEAD
use App\Models\CourseSwitching;
use App\Models\Student;
=======
use App\Models\ums\CourseSwitching;
use App\Models\ums\Student;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

class CourseSwitchingController extends AdminController
{

    public function courseSwitching(Request $request){
        $students = CourseSwitching::get();
<<<<<<< HEAD
        return view('admin.admission.course-switching',compact('students'));
=======
        return view('ums.admissions.course_transfer',compact('students'));
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }

    public function courseSwitchingSave(Request $request)
    {
    	$request->validate([
            'course_switching_file' => 'required',
        ]);
        if($request->hasFile('course_switching_file')){
			Excel::import(new CourseSwitchingImport, $request->file('course_switching_file'));
		}
        return back()->with('success','Records Saved!');
    }

    
}