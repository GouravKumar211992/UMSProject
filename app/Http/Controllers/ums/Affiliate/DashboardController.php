<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Affiliate;
=======
namespace App\Http\Controllers\ums\Affiliate;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
<<<<<<< HEAD
use App\Models\Application;
use App\Models\UploadDocument;
use App\Models\AffiliateCircular;
use App\Models\Address;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Campuse;
use App\Models\AffiliateAdmin;
use App\Models\InternalMarksMapping;
use App\Models\AcademicSession;
use App\Models\InternalMark;
=======
use App\Models\ums\Application;
use App\Models\ums\UploadDocument;
use App\Models\ums\AffiliateCircular;
use App\Models\ums\Address;
use App\Models\ums\Enrollment;
use App\Models\ums\Course;
use App\Models\ums\Campuse;
use App\Models\ums\AffiliateAdmin;
use App\Models\ums\AcademicSession;
use App\Models\ums\InternalMark;
use App\Models\ums\InternalMarksMapping;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

//this is for affiliate tr
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

//use App\Models\Course;
<<<<<<< HEAD
use App\Models\Semester;
use App\Models\Subject;
=======
use App\Models\ums\Semester;
use App\Models\ums\Subject;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use App\Models\Result;
use App\Models\ExamFormAllow;
use App\Models\MbbsExamForm;
use App\Models\ExamFee;
use App\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;


class DashboardController extends Controller
{
    public function index(Request $request)
       { 

         $user=Auth::guard('affiliate')->user();
         //dd($user);  
         $application=Application::where('campuse_id',$user->id)->orderBy('id', 'DESC')->get();
         //dd($application->count());
         $campuse=Campuse::where('id', $user->id)->first();
         //dd($campuse);
        $apl=$application->pluck('id')->toArray();
        $month_pending=$month_approve=$month_enrolled=null;
        
        $enrollment=Enrollment::whereIn('application_id',$apl)->get();
        //dd($enrollment);
        $pending=$application->where('status','pending');
        $monthly_pending=$application->where('status','pending')->groupBy(function($item) {
            return $item->created_at->month;
            });
        $monthly_approved=$application->where('status','Approved')->groupBy(function($item) {
            return $item->created_at->month;
            });
        $monthly_enrolled=$application->groupBy(function($item) {
            return $item->created_at->month;
            });
        //dd($monthly_enrolled);
        foreach($monthly_pending as $key=>$month){
        $month_pending[$key]=$month;
    }
    foreach($monthly_approved as $key=>$month){
        $month_approve[$key]=$month;
    }
    
    foreach($monthly_enrolled as $key=>$month){
        $month_enrolled[$key]=$month;
    }
    //dd($month_approve);
    
         //dd($application[0]->id);
           return view('affiliate.home',['application'=>$application,'user'=>$user,'enrollment'=>$enrollment,'pending'=>$pending,'month_enrolled'=>$month_enrolled,'month_approve'=>$month_approve,'month_pending'=>$month_pending,'campuse'=>$campuse]);
       } 
    public function information()  
       {
         $files=UploadDocument::paginate(10);
<<<<<<< HEAD
           return view('affiliate.information',['files'=>$files]);
=======
           return view('ums.affiliate_information_view.affiliate_informations_view',['files'=>$files]);
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
       }
    

    public function informationPost(Request $request)
       {    
                $validator = Validator::make($request->all(),[
                   'upload_document'=> 'required',]);

               if ($validator->fails())
              {    
                return back()->withErrors($validator)->withInput($request->all());
              }
          
          $data= new UploadDocument;
            
            $data->name= $request->document_name;
            if($request->upload_document)
            {
              $data->addMediaFromRequest('upload_document')->toMediaCollection('upload_document');
            }
            $data->save();

           return redirect()->route('affiliate-information')->with('success','Document Uploaded  Successfully.');
       }  
        public function delete($id)
   {
        $data=UploadDocument::find($id);
        $data->delete();
       
     return redirect()->route('affiliate-information')->with('success','Document  Deleted Successfully.');
    
   }
   public function circularview()
   { 
     $file=AffiliateCircular::paginate(10);

      return view('affiliate.circular-view',['file'=>$file]);
   }
   public function profile(Request $request)
   {
       $affiliate_profile=Auth::guard('affiliate')->user()->id;
       //dd($data);
       $user=AffiliateAdmin::whereId($affiliate_profile)->first();
       //dd($user);
       return view('affiliate.profile',['user'=>$user]);

   }
   public function affiliateInternalMarks(Request $request)
   {   
        $user=Auth::guard('affiliate')->user();
        //dd($user);
        $data['mapped_Subjects']=InternalMarksMapping::select('subjects.*')
                ->join('subjects','subjects.sub_code','internal_marks_mappings.sub_code')
                //->where('faculty_id',$user->id)
                ->where('subjects.deleted_at',null)
                ->get();

    

        $data['marks']= InternalMark::where('session',$request->session)->where('sub_code',$request->sub_code)
        ->where('campus_code',$user->affiliated_id)
        ->get();
        //dd($data['marks']);
        $data['details']= InternalMark::where('session',$request->session)->where('sub_code',$request->sub_code)
        ->where('campus_code',$user->affiliated_id)
        ->first();
        //dd($data['details']);
        $data['sessions']=AcademicSession::all();
         //dd($data);
       // $data=$data->paginate(10);
        //return view('faculty.internal.show',$data);
       return view('affiliate.internal-marks',$data);
   }
   public function affiliateTR(Request $request)
   {  

      $data['courses']=Course::all();
        $data['semesters']=Semester::orderBy('id','asc')->get();
        $course_id = $request->course;
        $semester_id = $request->semester;
        
        $data['course_id'] = $course_id;
        $data['semester_id'] = $semester_id;

        $subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
                            ->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
                            ->whereNotNull('combined_subject_name')
                            ->groupBy('combined_subject_name')
                            ->orderBy('sub_code','asc')
                            ->get();

        $subject_total = Subject::where('course_id',$course_id)
                                            ->whereNotNull('combined_subject_name')
                                            ->where('semester_id',$semester_id)
                                            ->get();
            $data['subject_total'] = $subject_total->sum('maximum_mark');

        $subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id){
            $subjects = Subject::where('course_id',$course_id)->where('semester_id',$semester_id)
                            ->whereNotNull('combined_subject_name')
                            ->where('combined_subject_name',$item_group->combined_subject_name)->get();

            $sub_theory_external = Subject::where('course_id',$course_id)
                                                    ->whereNotNull('combined_subject_name')
                                                    ->where('semester_id',$semester_id)
                                                    ->where('combined_subject_name',$item_group->combined_subject_name)
                                                    ->where('subject_type','Theory')
                                                    ->get();
            $item_group['sub_theory_external'] = $sub_theory_external->sum('maximum_mark');

            $sub_theory_internal = Subject::where('course_id',$course_id)
                                                    ->whereNotNull('combined_subject_name')
                                                    ->where('semester_id',$semester_id)
                                                    ->where('combined_subject_name',$item_group->combined_subject_name)
                                                    ->where('subject_type','Theory')
                                                    ->get();
            $item_group['sub_theory_internal'] = $sub_theory_internal->sum('internal_maximum_mark');
                            
            $sub_practical_internal = Subject::where('course_id',$course_id)
                                                    ->whereNotNull('combined_subject_name')
                                                    ->where('semester_id',$semester_id)
                                                    ->where('combined_subject_name',$item_group->combined_subject_name)
                                                    ->where('subject_type','Practical')
                                                    ->get();
            $item_group['sub_practical_internal'] = $sub_practical_internal->sum('internal_maximum_mark');

            $item_group['sub_theory_practical_internal'] = ($item_group['sub_theory_internal'] + $item_group['sub_practical_internal']);
            $item_group['subjects_total'] = ($subjects->sum('maximum_mark'));
            $item_group['subjects'] = $subjects;
        });
            
        $data['subjects_group_all'] = $subjects_group_all;


        if($request->form_type=='compartment'){
            $mbbs_exam_forms = ExamFee::where('course_id',$request->course)
                                    ->where('semester',$request->semester)
                                    ->where('form_type','compartment')
                                    ->distinct()
                                    ->pluck('roll_no')
                                    ->toArray();
            $students = Result::select('roll_no','enrollment_no','course_id','semester')
                                        ->where(['course_id'=>$request->course,'semester'=>$request->semester])
                                        ->whereIn('roll_no',$mbbs_exam_forms)
                                        ->distinct()
                                        //->paginate(5);
                                        ->get();
        }else{
            $mbbs_exam_forms = ExamFee::where('course_id',$request->course)
                                    ->where('semester',$request->semester)
                                    ->where('form_type','regular')
                                    ->distinct()
                                    ->pluck('roll_no')
                                    ->toArray();
            $students = Result::select('roll_no','enrollment_no','course_id','semester')
                                        ->where(['course_id'=>$request->course,'semester'=>$request->semester])
                                        ->whereIn('roll_no',$mbbs_exam_forms)
                                        ->where('roll_no','2001247001')
                                        //->where('roll_no','2001247131')
                                        //->where('roll_no','2001247143')
                                        ->distinct()
                                        //->paginate(5);
                                        ->get();
        }

        $students->each(function ($item_student, $key_student) use ($course_id,$semester_id){
            $grand_total = Result::where('roll_no',$item_student->roll_no)
                                                ->where('course_id',$course_id)
                                                ->where('semester',$semester_id)
                                                ->get();
            $item_student['grand_total'] = $grand_total->sum('external_marks');
            $subjects_group_all = Subject::select('combined_subject_name',DB::raw('count(1) as combined_count'))
                                ->whereNotNull('combined_subject_name')
                                ->where(['course_id'=>$course_id,'semester_id'=>$semester_id])
                                ->groupBy('combined_subject_name')
                                ->orderBy('sub_code','asc')
                                ->get();
            $item_student['subjects_group_all'] = $subjects_group_all->each(function ($item_group, $key_group) use ($course_id,$semester_id,$item_student){
                $subjects = Subject::where('course_id',$course_id)
                            ->whereNotNull('combined_subject_name')
                                ->where('semester_id',$semester_id)
                                ->where('combined_subject_name',$item_group->combined_subject_name)
                                ->distinct()
                                ->orderBy('sub_code','asc')->get();
                $subject_result = Result::join('subjects','subjects.sub_code','results.subject_code')
                                        ->where('combined_subject_name',$item_group->combined_subject_name)
                                        ->where('roll_no',$item_student->roll_no)->where('results.course_id',$course_id)
                                        ->where('semester',$semester_id)
                                        ->get();

                $student_total = ($subject_result->sum('external_marks'));

                $subjects_total = ($subjects->sum('maximum_mark'));

                $student_theory_external = Subject::join('results','results.subject_code','subjects.sub_code')
                                                        ->whereNotNull('combined_subject_name')
                                                        ->where('roll_no',$item_student->roll_no)
                                                        ->where('results.course_id',$course_id)
                                                        ->where('semester_id',$semester_id)
                                                        ->where('combined_subject_name',$item_group->combined_subject_name)
                                                        ->where('subject_type','Theory')
                                                        ->get();
                $item_group['student_theory_external'] = $student_theory_external->sum('external_marks');

                $student_theory_internal = Subject::join('results','results.subject_code','subjects.sub_code')
                                                        ->whereNotNull('combined_subject_name')
                                                        ->where('roll_no',$item_student->roll_no)
                                                        ->where('results.course_id',$course_id)
                                                        ->where('semester_id',$semester_id)
                                                        ->where('combined_subject_name',$item_group->combined_subject_name)
                                                        ->where('subject_type','Theory')
                                                        ->get();
                $item_group['student_theory_internal'] = $student_theory_internal->sum('internal_marks');
                                
                $student_practical_internal = Subject::join('results','results.subject_code','subjects.sub_code')
                                                        ->whereNotNull('combined_subject_name')
                                                        ->where('roll_no',$item_student->roll_no)
                                                        ->where('results.course_id',$course_id)
                                                        ->where('semester_id',$semester_id)
                                                        ->where('combined_subject_name',$item_group->combined_subject_name)
                                                        ->where('subject_type','Practical')
                                                        ->get();
                $item_group['student_practical_internal'] = $student_practical_internal->sum('internal_marks');

                $item_group['student_theory_practical_internal'] = ($item_group['student_theory_internal'] + $item_group['student_practical_internal']);

                $item_group['student_total'] = $student_total;
                $item_group['subjects_total'] = $subjects_total;
                $item_group['subjects'] = $subjects->each(function ($item_sub, $key_sub) use ($item_student){
                    $subject_result = Result::where('roll_no',$item_student->roll_no)
                                            ->where('results.course_id',$item_sub->course_id)
                                            ->where('semester',$item_sub->semester_id)
                                            ->where('subject_code',$item_sub->sub_code)
                                            ->first();
                    if($item_sub->subject_type == 'Theory'){
                        $item_sub['theory_total_all'] = ($subject_result)?((int)$subject_result->external_marks + (int)$subject_result->oral + (int)$subject_result->internal_marks):0;
                    }
                    if($item_sub->subject_type == 'Practical'){
                        $item_sub['practical_total_all'] = ($subject_result)?((int)$subject_result->practical_marks + (int)$subject_result->internal_marks):0;
                    }
                    $item_sub['subject_result'] = $subject_result;
                });


                $grace_mark = $item_student->final_result_grade($item_student->roll_no,$course_id,$semester_id,$item_group->combined_subject_name);
                $item_group['grace_mark'] = $grace_mark;
                //$item_group['result_single_subject'] = $this->result_single_subject($item_student->roll_no,$course_id,$semester_id,$grace_mark,$item_group->combined_subject_name);
            });
            $item_student['final_result'] = $item_student->final_result($item_student);
//          dd($item_student['final_result']);
        });

        $data['students'] = $students;

        //return view('admin.tr.mbbs-tr-first');
     return view('affiliate.tr', $data);
   }
   
}
