<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Models\GradeOldAllowedSemester;
use App\Models\Campuse;
use App\Models\Course;
use App\Models\Semester;
use App\Models\AcademicSession;


class OldGradeController extends AdminController
{
 public function index(Request $request)
 {
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $courses = Course::withoutTrashed()->where('campus_id',$request->campus_id)->orderBy('name')->get();
    $semesters = Semester::withoutTrashed()->where('course_id',$request->course_id)->orderBy('id','asc')->get();
    $sessions = [
        '2022-2023','2021-2022','2020-2021','2019-2020','2018-2019','2017-2018','2016-2017'
    ];
    $grades = GradeOldAllowedSemester::orderBy('id','DESC')->get();

    if($request->campus_id && $request->course_id && $request->semester_id && $request->academic_session && $request->submit_form){
        $grade = GradeOldAllowedSemester::where('semester_id',$request->semester_id)
            ->where('academic_session',$request->academic_session)->first();
        if($grade){
            return back()->with('error','Already Added');
        }{
            $grade = new GradeOldAllowedSemester;
            $grade->semester_id = $request->semester_id;
            $grade->academic_session = $request->academic_session;
            $grade->save();
            return redirect('admin/oldgrade')->with('success','Added Successfully');
        }
    }

  return view('admin.master.old-grade.oldgrade', compact(
      'campuses',
      'courses',
      'semesters',
      'sessions',
      'grades',
    ));
 }

 public function oldgrade_delete(Request $request)
 {
    $grade = GradeOldAllowedSemester::find($request->id);
    if($grade){
        $grade->delete();
        return redirect('admin/oldgrade')->with('success','Deleted Successfully');
    }
 }

}