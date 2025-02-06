<?php

namespace App\Http\Controllers\ums\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ums\AdminController;

use App\Imports\CourseSwitchingImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\ums\CourseSwitching;
use App\Models\ums\Student;

class CourseSwitchingController extends AdminController
{

    public function courseSwitching(Request $request){
        $students = CourseSwitching::get();
        return view('ums.admissions.course_transfer',compact('students'));
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