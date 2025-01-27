<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

use App\Imports\CourseSwitchingImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\CourseSwitching;
use App\Models\Student;

class CourseSwitchingController extends AdminController
{

    public function courseSwitching(Request $request){
        $students = CourseSwitching::get();
        return view('admin.admission.course-switching',compact('students'));
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