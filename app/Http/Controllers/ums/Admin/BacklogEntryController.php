<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Admin;
use App\Imports\EnrollmentsImport;
use App\Imports\OldStudentEnrollmentsImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Student;
use App\Models\Application;
use App\Models\Course;
use App\Models\Category;
use App\Models\PermanentAddress;
use App\Models\UploadDocuments;
use App\Models\Icard;
use Illuminate\Support\Facades\Storage;
use Validator;
class BacklogEntryController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function bulkEnrollment(Request $request)
    {

        return view('admin.backlog.index');
    }

    public function bulkEnrollmentSave(Request $request)
    {   
	//dd($request->all());
				$request->validate([
					'enrollment_file' => 'required|mimetypes:application/vnd.ms-excel',
           		]);
        if($request->hasFile('enrollment_file')){
			$ddd = Excel::import(new EnrollmentsImport, $request->file('enrollment_file'));
		}

        return back()->with('success','Records Saved!');
    }

     public function bulkEnrollmentOldStudent(Request $request)
    {

        return view('admin.backlog.old-student');
    }

     public function bulkEnrollmentOldStudentSave(Request $request)
    {   
        if($request->hasFile('enrollment_file')){
            $ddd = Excel::import(new OldStudentEnrollmentsImport, $request->file('enrollment_file'));
        }

        return back()->with('success','Records Saved!');
    }




}