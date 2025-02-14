<?php

namespace App\Http\Controllers\ums\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\ApprovalSystem;
use App\Models\ums\Enrollment;
use App\Models\ums\Subject;
use App\Models\ums\Student;
use App\Models\ums\ExamFee;
use App\Models\ums\Semester;
use App\Models\ums\AcademicSession;

class ApprovalSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sessions = AcademicSession::orderBy('id','DESC')->get();
        $allData = ApprovalSystem::orderBy('id','DESC')->get();
        $backTypes = ExamFee::distinct('form_type')
        // ->where('form_type','special_back')
        ->pluck('form_type')->toArray();
        $enrollment = Enrollment::where('roll_number',$request->roll_no)->first();
        $couse_id = null;
        if($enrollment){
            $couse_id = $enrollment->course_id;
        }
        $semesters = Semester::where('course_id',$couse_id)->get();
        $subjects = Subject::withTrashed()->where('course_id',$couse_id)
        ->where('semester_id',$request->semester_id)
        ->orderBy('position','ASC')
        ->get();
        return view('ums.exam.Exam_paper_Approvel_system',compact('sessions','allData','backTypes','couse_id','semesters','subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $student = Student::where('roll_number',$request->roll_no)->first();
        if(!$student){
            return back()->with('error','Invalid Roll Number');
        }
        $enrollment = Enrollment::where('roll_number',$request->roll_no)->first();
        if(!$enrollment){
            return back()->with('error','Invalid Roll Number');
        }
        $add_for_special_back = ApprovalSystem::where('roll_number',$request->roll_no)
        ->where('course_id',$enrollment->course_id)
        ->where('semester_id',$request->semester_id)
        ->where('special_back',$request->special_back)
        ->where('session',$request->session)
        ->first();
        if($add_for_special_back){
            return back()->with('error','Already Added');
        }
        $add_for_special_back = new ApprovalSystem;
        $add_for_special_back->roll_number = $request->roll_no;
        $add_for_special_back->special_back = $request->special_back;
        $add_for_special_back->course_id = $enrollment->course_id;
        $add_for_special_back->semester_id = $request->semester_id;
        $add_for_special_back->sub_code = implode(',',$request->sub_code);
        $add_for_special_back->session = $request->session;
        $add_for_special_back->save();
        return back()->with('success','Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // dd($request->all());   
        $allowspecialBack = ApprovalSystem::where('roll_number',$request->roll_number)->first();
        $allowspecialBack->special_back = $request->special_back;
        $allowspecialBack->save();
        return back()->with('success','Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($roll_number)
    {
        $allowExamDelete = ApprovalSystem::where('roll_number',$roll_number)->first();
        $allowExamDelete->delete();
        return back()->with('success','Deleted Successfully');
    }
}
