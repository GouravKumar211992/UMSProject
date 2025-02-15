<?php

namespace App\Http\Controllers\ums\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Exports\QuestionBanksExport;
use App\models\ums\User;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\ums\Application;
use App\Models\ums\Course;
use App\Models\ums\CourseFee;
use App\Models\ums\Category;
use App\Models\ums\Classe;
use App\Models\ums\AcademicSession;
use App\Models\ums\Campuse;
use App\Models\ums\Subject;
use App\Models\ums\Semester;
use App\Models\ums\Stream;
use App\Models\ums\QuestionBank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;



class QuestionBankController extends Controller
{
    //  public function __construct()
    // {
    //     parent::__construct();
    // }
    
    public function index(Request $request)
    { 
        $internals = QuestionBank::orderBy('id', 'DESC');
        
        if(!empty($request->session)){
            
            $internals->where('session','LIKE', '%'.$request->session.'%');
        }
        if(!empty($request->subject)){
            
            $internals->where('sub_code','LIKE', '%'.$request->subject.'%');
        }
        if (!empty($request->course)) {
            $internals->where('course_id',$request->course);
        }
        if (!empty($request->program)) {
            
            $internals->where('program_id',$request->program);
        }
        if (!empty($request->campus)) {
            $course=Course::where('campus_id',$request->campus)->pluck('id')->toArray();
            //dd($course);
            $internals->whereIn('course_id',$course);
        }
        //dd($internals->get()[1117]->subject->name);
        $quesBankData = $internals->paginate(10);
        //dd($internals[0]);
        $courses=Course::all();
        $programs = Category::all();
        $campuses = Campuse::all();
        $sessions=AcademicSession::all();
        $semester = Semester::select('name')->distinct()->get();
        
        if(Auth::guard('student')->check()){
            $data['quesBankData'] = $quesBankData;
            return view('ums.student.questionbankdownload',$data);
        }

        return view('admin.master.question-bank.index', [
            'page_title' => "Internal Mark Mapping",
            'sub_title' => "records",
            'quesBankData'  => $quesBankData,
            'courses'  => $courses,
            'programs'  => $programs,
            'sessions'  => $sessions,
             'semesters'  => $semester,
             'campuses'  => $campuses
        ]);
    }
    public function add()
    {
        $campuses=Campuse::all();
        $programs = Category::all();
        $sessions=AcademicSession::all();
        
        return view('ums.master.question_bank.add_question_bank',[
            'page_title' => "Internal Mark Mapping",
            'sub_title' => "Add",
            'campuses'  => $campuses,
            'sessions'  => $sessions,
            'programs'  => $programs
           
            
        ]);
    }



    public function addQuestionBank(Request $request)
    {
        // dd($request->all());
         $valid=$request->validate([
            'campus_id' => 'required',
            'program_id' => 'required',
            'course_id' => 'required',
            'branch_id' => 'required',
            'session' => 'required',
            'question_bank_file' => 'nullable|mimes:pdf|max:1024',
            // 'synopsis_file' => 'nullable|mimes:pdf|max:1024',
            // 'thysis_file' => 'nullable|mimes:pdf|max:1024',
            // 'journal_paper_file' => 'nullable|mimes:pdf|max:1024',
            // 'seminar_file' => 'nullable|mimes:pdf|max:1024',
        ]);
        if(!$valid){
            return back()->withInput();
        }

        $dup_check_array = $request->only('campus_id','program_id','course_id','semester_id','sub_code','session');
        $dup_check = QuestionBank::where($dup_check_array)->first();
        if($dup_check){
            return back()->with('error','This Recard is already exist');
        }
        $mapping=new QuestionBank;
        $mapping->fill($request->all());
        if($request->question_bank_file){
            $mapping->addMediaFromRequest('question_bank_file')->toMediaCollection('question_bank_file');
        }
        if($request->synopsis_file){
            $mapping->addMediaFromRequest('synopsis_file')->toMediaCollection('synopsis_file');
        }
        if($request->thysis_file){
            $mapping->addMediaFromRequest('thysis_file')->toMediaCollection('thysis_file');
        }
        if($request->journal_paper_file){
            $mapping->addMediaFromRequest('journal_paper_file')->toMediaCollection('journal_paper_file');
        }
        if($request->seminar_file){
            $mapping->addMediaFromRequest('seminar_file')->toMediaCollection('seminar_file');
        }
        $mapping->save();
        // dd($mapping);
        return redirect()->route('question_bank')->with('message','New Question Bank Added');
    }

    public function get_Subject(Request $request)
    {
        // dd($request->all());
        $html='<option value="">--Select Subject--</option>';
        $query= Subject::where(['program_id'=> $request->program_id,'course_id'=>$request->course_id])->get();
        foreach($query as $sc){
            $html.='<option value="'.$sc->sub_code.'">'.'('.$sc->sub_code.') '.$sc->name.'('.$sc->subject_type.')'.'</option>';
        }
        return $html;
    }
    
    
    public function get_Semester(Request $request)
    {
        //dd($request->all());
        $html='<option value="">--Select Semester--</option>';
        $query= Semester::where(['program_id' => $request->program_id,'course_id' => $request->course_id])->get();
        foreach($query as $sc){
            $html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
        }
        $html_branch='<option value="">--Select Stream--</option>';
        $query= Stream::where(['category_id'=> $request->program_id,'course_id'=>$request->course_id])->get();
        foreach($query as $sc){
            $html_branch.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
        }
        $data['semester']= $html;
        $data['branch']= $html_branch;
        return response()->json($data);
    }
    
     public function softDelete(Request $request,$slug) {
        
        QuestionBank::where('id', $slug)->delete();
        return redirect()->route('question-bank-list')->with('message','Question Bank Deleted Succesfully');
        
    }

     public function editShow(Request $request,$slug)
    
    { 
        $selected_internal=QuestionBank::where('id',$slug)->first();
//      dd($selected_internal);
         $category = Category::all();
         $course = Course::all();
        $campuses=Campuse::all();
        $subjects=Subject::where('course_id',$selected_internal->course_id)
                            ->where('semester_id',$selected_internal->semester_id)
                            ->orderBy('name','asc')
                            ->get();
        //dd($subjects);
        $classes=Stream::all();
         $sessions=AcademicSession::all();
         $semester = Semester::get();
       return view('admin.master.question-bank.edit',[
        'selected_internal'=>$selected_internal,
        'categorylist'      => $category,
        'courseList'        => $course,
        'campuses'        => $campuses,
        'sessions'        => $sessions,
        'semesterlist'        => $semester,
        'subjects'        => $subjects,
        'branches'        => $classes,

       ]);  
    }
     public function update(Request $request)
    { 
        // dd($request->all());
        $request->validate([
            'campus_id' => 'required',
            'program_id' => 'required',
            'session' => 'required',
        ]);
        
        $mapping = QuestionBank::where('id',$request->internal_id)->first();
        $mapping->fill($request->all());
        if($request->question_bank_file){
            $mapping->addMediaFromRequest('question_bank_file')->toMediaCollection('question_bank_file');
        }
        $mapping->save();

        return redirect()->route('question-bank-list')->with('success','Updated Successfully');
        
    }
       public function QuestionBankExport(Request $request){
            return Excel::download(new QuestionBanksExport($request), 'QuestionBank.xlsx');
       } 

        // public function questionBankDownload(){
        //     $data['quesBankData'] = QuestionBank::all();
        //     return view('student/questionbank/view',$data);
        // }

        public function pdfview(Request $request)
        {
            $items = QuestionBank::where('id', $request->id)->get();
            // dd($items);
            view()->share('items',$items);


            if($request->has('download')){
                $pdf = PDF::loadView('pdfview');
                return $pdf->download('pdfview.pdf');
            }


            return view('pdfview');
        }

}
