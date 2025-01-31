<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\Student;
use App\Models\ExamFee;
use App\Models\Subject;
use App\Models\Campuse;
use App\Models\ExamSchedule;
use App\Models\Enrollment;
use App\Models\Application;
use App\Models\StudentSemesterFee;
use App\Models\Semester;
use App\Models\Icard;
use App\Models\Stream;
use App\Models\Category;
use App\Models\MbbsExamForm;
use App\Models\ExamPayment;
use App\Models\AdmitCard;
use App\Models\ExamForm;
use App\Models\AcademicSession;
use App\Models\ExamFormAllow;
use App\Scrutiny;
use Validator;  
use App\Exports\ExamFeeExport;
use App\Exports\ChallengeExport;
use App\Models\Audit;
use App\Models\BackPaper;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StudentAllFromOldAgency;
use App\Models\StudentSubject;
use DB;
use Illuminate\Support\Facades\Artisan;
use Mockery\CountValidator\Exact;

class ExamFeeAllController extends AdminController
{
	 public function __construct()
    {
        parent::__construct();
    }
	public function index(Request $request)
    {   
        $examfees = ExamFee::where('roll_no',$request->search)
        ->whereNull('backup_exam_id')
        ->orderBy('id', 'DESC')
        ->withTrashed()
        ->get();
  
       return view('admin.master.examfee.index', [
            'page_title' => "ExamFee",
            'sub_title' => "Records",
            'examfees' => $examfees,
        ]);
    }
	 public function add(Request $request)
    {
        
        return view('admin.master.examfee.addfee', [
            'page_title' => "Add New",
            'sub_title' => "Exam Fee"
        ]);
    }
	
    public function approve(Request $request ,$slug)
    {
        $data=ExamFee::where('id', $slug)->update(['fee_status' => 1]);
		return back()->with('message','Student Exam Fee Approved Successfully');
    }
	public function student_data(Request $request)
	{
		$query=Enrollment::where('enrollment_no',$request->student_id)->first();
		$data=Course::where('id',$query->course_id)->first();
		//dd($data);
		$fee=StudentSemesterFee::where('enrollment_no',$request->student_id);
		return $data->name;
	}
	public function course_data(Request $request)
	{
		//dd($request->all());
		$query=Course::where('name',$request->course_id)->first();
		//dd($query->id);
		$data=Semester::where('course_id',$query->id)->get();
		//dd($data);
		$html='<option value="">--Select Semester--</option>';
		foreach($data as $sc){
			$html.='<option value='.$sc->id.'>'.$sc->name.'</option>';
		}
		return $html;
	
		
	}

    public function view_exam_form(Request $request,$slug)
    { //  dd('');
     
            $exam_fee = ExamFee::where('id',$slug)->first();
            $student = Student::where('roll_number',$exam_fee->roll_no)->first();
            $explode_subject_code = explode(' ',$exam_fee->subject);
            $data['subjects']=Subject::whereIn('sub_code',$explode_subject_code)->where('course_id',$exam_fee->course_id)->get();
		
      
		//dd($data['mbbs']);
        return view('admin.master.examfee.examfee-view',['data'=>$data,'exam_fee' => $exam_fee, 'student' => $student]);
    } 

    public function exam_form_allowed(Request $request,$roll_no)
    {
    	$examFormAllow = ExamFormAllow::where('roll_no',$roll_no)->first();
		if($examFormAllow){
			return back()->with('error','Already Allowed for Re-Exam Form.');
		}
		$examFormAllow = new ExamFormAllow;
		$examFormAllow->roll_no = $roll_no;
		$examFormAllow->save();
		return back()->with('success','Allowed for Re-Exam Form.');
    } 

	public function edit_exam_form(Request $request, $slug){
        $exam_fee=ExamFee::find($slug);
        $exam_form=ExamForm::where('exam_fee_id',$slug)->get('sub_code');
        //dd($exam_fee);
        $subjects=Subject::where(['course_id'=>$exam_fee->course_id,'semester_id'=>$exam_fee->semester])->whereNull('deleted_at')->get();

        return view('admin.master.examfee.edit-form',['slug'=>$slug,'exam_fee'=>$exam_fee,'subjects'=>$subjects,'$exam_form'=>$exam_form]);
    }
	public function edit_exam_back_form(Request $request, $slug){
        $examFee = ExamFee::find($slug);
        if($examFee->bank_name==null){
            return back()->with('error','Payment Not Paid');
        }
        $papers = BackPaper::where('exam_fee_id',$slug)->get();
        return view('admin.master.examfee.exam-edit-back',compact('papers','examFee'));
    }
    public function edit_exam_back_form_single($id)
    {
        $paper = BackPaper::findOrFail($id);
        $paper->mid = ($paper->mid)?'1':'0';
        $paper->ass = ($paper->ass)?'1':'0';
        $paper->external = ($paper->external)?'1':'0';
        $paper->viva = ($paper->viva)?'1':'0';
        $paper->p_internal = ($paper->p_internal)?'1':'0';
        $paper->p_external = ($paper->p_external)?'1':'0';
        return response()->json($paper);
    }
    public function edit_exam_back_form_update(Request $request, $id)
    {
        $paper = BackPaper::findOrFail($id);

        // Validation
        $request->validate([
            'mid' => 'nullable|numeric',
            'ass' => 'nullable|numeric',
            'external' => 'nullable|numeric',
            'viva' => 'nullable|numeric',
            'p_internal' => 'nullable|numeric',
            'p_external' => 'nullable|numeric'
        ]);

        // Update the record
        $paper->update([
            'mid' => $request->mid,
            'ass' => $request->ass,
            'external' => $request->external,
            'viva' => $request->viva,
            'p_internal' => $request->p_internal,
            'p_external' => $request->p_external
        ]);

        return response()->json(['message' => 'Paper updated successfully.']);
    }
    public function edit_exam_form_post(Request $request, $slug){
        $exam = ExamFee::find($slug);
        if(!$exam){
            return back()->with('error','Invalid Exam ID');
        }
        try {
            // backup of previour records
            $exam_backup = $exam->replicate();
            $exam_backup->deleted_at = date('Y-m-d h:i:s');
            $exam_backup->backup_exam_id = $exam->id;
            $exam_backup->save();

            $subjects_list = implode(' ',$request->paper);
            $exam->subject = $subjects_list;
            $exam->scribe = $request->input('scribe');
            $exam->vaccinated = $request->input('vaccinated');
            $exam->bank_name = $request->input('bank_name');
            $exam->bank_IFSC_code = $request->input('bank_IFSC_code');
            $exam->receipt_number = $request->input('receipt_number');
            $exam->fee_amount = $request->input('fee_amount');
            $exam->receipt_date = $request->input('receipt_date');
            $exam->save();
            $payment = ExamPayment::where('exam_fee_id',$request->exam_fee_id)->first();
            if($payment){
                $payment->bank_name = $request->bank_name;
                $payment->bank_ifsc_code = $request->bank_IFSC_code;
                $payment->challan = $request->receipt_number;
                $payment->txn_date = $request->receipt_date;
                $payment->paid_amount = $request->fee_amount;
                $payment->updated_at = date('Y-m-d H:s:i');
                $payment->save();
            }
            StudentSubject::where('student_semester_fee_id',$slug)->forceDelete();
            Audit::insert([
                'user_type' => $exam->roll_no,
                'user_id' => $exam->id,
                'event' => 'Regular Exam Subject Change by Admin',
                'auditable_type' => 'Regular Exam Subject Change by Admin',
                'auditable_id' => 'Regular Exam Subject Change by Admin',
                'old_values' => serialize($exam),
                'url' => url()->current(),
                'ip_address' => $request->ip(),
            ]);
            Artisan::call('command:StudentPromotion',['roll_no'=>$exam->roll_no]);
            DB::commit();
        } catch(ValidationException $e){
            DB::rollback();
            return back()
                ->withErrors( $e->getErrors() )
                ->withInput();
        } catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
        return redirect('admin/master/examfee?search='.$exam->roll_no)->with('success','Exam Form Updated Successfully.');
    }

    public function resetPayment(Request $request,$slug)
    {
        $exam = ExamFee::where('id',$slug)->first();
        if(!$exam){
            return back()->with('error','Invalid Exam ID');
        }
        ExamFee::where('id',$slug)->update(['fee_amount' => '', 'fee_status' => '', 'is_agree' =>'', 'bank_name' =>'', 'order_id' =>'', 'bank_IFSC_code' =>'', 'receipt_number' =>'', 'receipt_date' =>'']);
        ExamPayment::where('exam_fee_id',$slug)->delete();
        Audit::insert([
            'user_type' => $exam->roll_no,
            'user_id' => $exam->id,
            'event' => 'Regular Exam Payment Deleted by Admin',
            'auditable_type' => 'Regular Exam Payment Deleted by Admin',
            'auditable_id' => 'Regular Exam Payment Deleted by Admin',
            'old_values' => serialize($exam),
            'url' => url()->current(),
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success','Payment Reset Successfully');
    } 
    public function deleteRegularExamForm(Request $request,$slug)
    {   
        $exam = ExamFee::where('id',$slug)->first();
        if(!$exam){
            return back()->with('error','Invalid Exam ID');
        }
        ExamFee::where('id',$slug)->delete();
        ExamPayment::where('exam_fee_id',$slug)->delete();
        StudentSubject::where('student_semester_fee_id',$slug)->delete();
        BackPaper::where('exam_fee_id',$slug)->delete();
        Audit::insert([
            'user_type' => $exam->roll_no,
            'user_id' => $exam->id,
            'event' => 'Regular Exam Form Deleted by Admin',
            'auditable_type' => 'Regular Exam Form Deleted by Admin',
            'auditable_id' => 'Regular Exam Form Deleted by Admin',
            'old_values' => serialize($exam),
            'url' => url()->current(),
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success','Deleted Successfully');
    } 
	public function examfeeExport(Request $request)
    {
        return Excel::download(new ExamFeeExport($request), 'ExamForm.xlsx');
    }
    public function challengeindex(Request $request){
        $scrutiny=Scrutiny::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $scrutiny->where(function($q) use ($keyword){

                $q->where('roll_no', 'LIKE', '%'.$keyword.'%')
				->orWhere('challan_number','LIKE', '%'.$keyword.'%');
            });
        }
		
        if(!empty($request->roll_no)){
           $scrutiny->where('roll_no','LIKE', '%'.$request->roll_no.'%');
			
        }
        if (!empty($request->campus)) {
            $course=Course::where('campus_id',$request->campus)->pluck('id')->toArray();
            $scrutiny->whereIn('course_id',$course);
        }
        if(!empty($request->semester)) {
            $semester_ids = Semester::where('name',$request->semester)->pluck('id')->toArray();
            $scrutiny->whereIn('semester_id',$semester_ids);
        }
		if(!empty($request->session)) {
			$scrutiny->where('batch',$request->session);
        }
		if(!empty($request->course)) {
			$scrutiny->where('course_id',$request->course);
        }
        
		$scrutiny = $scrutiny->paginate(10);
		$session=AcademicSession::all();
		$category = Category::all();
        $campuse = Campuse::all();
        $course = Course::all();
		$semester = Semester::select('name')->distinct()->get();
      
       return view('admin.Student-form.index', [
            'page_title' => "Challenge Form",
            'sub_title' => "records",
            'scrutinies' => $scrutiny,
			'semesterlist' => $semester,
			'courses' => $course,
			'sessions' => $session,
            'categories' => $category,
            'campuselist' => $campuse,
                 ]);

    }
    public function view_challenge_form(Request $request,$slug)
    { 	//dd('');
        $scrutiny=Scrutiny::where('id',$slug)->orderBy('id','DESC')->first();
        //dd($scrutiny);
        $sublist=Subject::whereIn('sub_code',explode(" ",$scrutiny->sub_code))
            ->where('course_id',$scrutiny->course_id)
            ->get();
                
        return view('admin.Student-form.view',['scrutiny'=>$scrutiny,'sublist'=>$sublist,'id'=>$slug]);
    }
    public function approve_challenge(Request $request ,$slug)
    {
        $data=Scrutiny::where('id', $slug)->update(['fee_status' => 1,'form_count' => 1]);
		return back()->with('message','Student Challenge Fee Approved Successfully');
    } 
    public function delete_challenge_form(Request $request,$slug)
    {	
    	$exam_fee = Scrutiny::where('id',$slug)->first();
		$exam_fee->Delete();
        return back()->with('success','Deleted Successfully');
    } 
    public function challengeExport(Request $request)
    {//dd($request->all());
        return Excel::download(new ChallengeExport($request), 'ChallengeForm.xlsx');
    }

}
