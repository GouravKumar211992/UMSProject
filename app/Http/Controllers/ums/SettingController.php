<?php

namespace App\Http\Controllers\ums;


use Illuminate\Http\Request;
use App\Models\ums\Campuse;
use App\Models\ums\Course;
use App\Models\ums\Semester;
use App\Models\ums\ExamFee;
use App\Models\ums\ExamSetting; 
use App\Models\ums\Category; 
use App\Models\ums\AdmissionSetting; 
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $campuses = Campuse::get();
        $courses = Course::where('campus_id',$request->campus_id)->get();
        $semesters = Semester::where('course_id',$request->courese_id)->get();
        $examfrom= ExamFee::select('form_type')
        ->distinct('form_type')
        ->orderBy('form_type', 'DESC')
        ->get();
        $examsetting = ExamSetting::orderBy('id','DESC')->get();
        return view('ums.setting.open_exam_form',compact('examfrom','examsetting','campuses','courses','semesters'));

        // $allData = ApprovalSystem::orderBy('id','DESC')->get();
        // $backTypes = ExamFee::distinct('form_type')->pluck('form_type')->toArray();
        // // dd($backTypes);
        // return view('admin.exam-paper-approval.show',compact('allData','backTypes'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = new ExamSetting;
        $data->campus_id =  $request->campus_id;
        $data->course_id =  $request->course_id;
        $data->semester_id = $request->semester_id;
        $data->form_type = '2';
        $data->semester_type = $request->semester_type;
        $data->from_date = $request->from_date;
        $data->to_date = $request->to_date;
        $data->session = $request->session;
        $data->message = $request->message;
        if($request->paper_doc_url){
            $data->addMediaFromRequest('paper_doc_url')->toMediaCollection('paper_doc_url');
        }
        $data->save(); 
        return redirect('open_exam_form')->with('success','Data Saved Successfully.');
    }

    public function destroy(Request $request,$id)
    {
        $allowExamDelete = ExamSetting::where('id',$id)->first();
        $allowExamDelete->delete();
        return back()->with('success','Deleted Successfully');
    }


    public function admissionSetting(Request $request)
    {   

        // $admission_open_couse_wise = admission_open_couse_wise(1,$request->type);
    //    dd($admission_open_couse_wise); 
        $campuses = Campuse::get();
        $categorys = Category::all();
        $courses = Course::where('campus_id',$request->campus_id)->get();
        $admissionSetting = AdmissionSetting::where('action_type','1')->orderBy('id','DESC')->get(); //add static value 1//
        return view('ums.setting.open_addmission_form',compact('admissionSetting','campuses','courses','categorys'));
    }

    public function admissionSettingEdit(Request $request) // extra created//
    {   

        // $admission_open_couse_wise = admission_open_couse_wise(1,$request->type);
    //  dd($admission_open_couse_wise);
        $campuses = Campuse::get();
        $categorys = Category::all();
        $courses = Course::where('campus_id',$request->campus_id)->get();
        $admissionSetting = AdmissionSetting::where('action_type','2')->orderBy('id','DESC')->get(); //add static value 2//
        return view('ums.setting.open_admission_edit_form',compact('admissionSetting','campuses','courses','categorys'));
    }

    public function admissionSettingStore(Request $request)
    {
        if(!$request->campus_id){
            return back()->with('success','Campus is required.');
        }
        $data = new AdmissionSetting;
        $request['action_type'] = '1';
        $data->fill($request->all());
        $data->save();
        return back()->with('success','Data Saved Successfully.');
    }

    public function deleteAdmissionSetting(Request $request,$id)
    {
        // dd($id);
        $allowExamDelete = AdmissionSetting::where('id',$id)->first();
        $allowExamDelete->delete();
        return back()->with('success','Deleted Successfully');
    }



}
