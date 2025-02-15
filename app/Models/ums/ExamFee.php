<?php

namespace App\Models\ums;

use App\Models\ums\Student;

use App\Models\ums\Course;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery\Matcher\Subset;

use Spatie\MediaLibrary\HasMedia;  // Keep this
use Spatie\MediaLibrary\InteractsWithMedia;
use DB;

class ExamFee extends Model implements HasMedia
{
 
 
	use SoftDeletes, InteractsWithMedia;
	 
	 
	 
	 protected $fillable = [
        'enrollment_no' ,
        'roll_no',
        'student_id' ,
        'academic_session' ,
		'semester' ,
		'course_id' ,
		'subject' ,
        'exam_form' ,
        'scribe' ,
		'vaccinated' ,
		'fee_amount' ,
		'fee_status' ,
		'form_type' ,
		'is_agree',
        'current_exam_session',
		'term_condition' 
    ];
            
            protected $appends = [
                'photo',
                'signature',
                'challan',
                'doc',
                'subject_name',
                'subject_sequence'
            ];
         
            
            public function enrollment() {
                return $this->belongsTo(Enrollment::class,'enrollment_no', 'enrollment_no');
            }
			public function semesters()
           {
             return $this->belongsTo(Semester::class, 'semester', 'id');
            }

			public function semester_details() {
                return $this->HasOne(Semester::class,'id','semester');
            }
			public function exam_types_details() {
                return $this->HasOne(ExamType::class,'exam_type','form_type');
            }
			public function academicSession() {
                return $this->HasOne(AcademicSession::class,'academic_session','academic_session');
            }
    public function coursefees() {
		return $this->hasOne(CourseFee::class,'course_id','course_id');
	}
	public function admitcard() {
		return $this->hasOne(AdmitCard::class,'exam_fees_id','id');
	}
	public function icardDetails() {
		return $this->hasOne(Icard::class,'roll_no','roll_no');
	}

    public function course() {
		return $this->hasOne(Course::class,'id','course_id');
	}

    public function formdataone() {
		return $this->hasOne(ExamForm::class,'exam_fee_id','id');
	}
    public function backPaperDetails() {
		return $this->hasMany(BackPaper::class,'exam_fee_id','id');
	}
    public function studentSubjects() {
		return $this->hasMany(StudentSubject::class,'student_semester_fee_id');
	}
    public function regularPaperList() {
		$data = StudentSubject::where('roll_number',$this->roll_no)
        ->where('session',$this->academic_session)
        ->where('course_id',$this->course_id)
        ->where('semester_id',$this->semester)
        ->where('type',$this->form_type)
        ->get();
        // dd($data);
        
        return $data;
	}

    public function formdata() {
		return $this->hasMany(ExamForm::class,'exam_fee_id','id');
	}
	public function examformdata() {
		return $this->hasMany(StudentAllFromOldAgency::class,'roll_no','roll_no');
	}
	public function examformdataone() {
		return $this->hasOne(StudentAllFromOldAgency::class,'roll_no','roll_no');
	}
	public function students() {
		return $this->hasOne(Student::class, 'roll_number','roll_no');
	}
	public function student() {
		return $this->hasOne(Student::class, 'roll_number','roll_no');
	}




	public function mbbsformdata() {
        $subject_array = explode(' ',$this->subject);
        $batch = batchFunctionMbbs($this->roll_no);
        if($this->course_id==49){
            $subjects = Subject::where('batch',$batch)
            ->where('semester_id',$this->semester)
            ->whereIn('sub_code',$subject_array)
            ->get();
        }else{
            $subjects = Subject::where('semester_id',$this->semester)
            ->whereIn('sub_code',$subject_array)
            ->get();
        }
		return $subjects;
	}

	public function mbbsformdataone() {
		return $this->hasOne(Student::class,'roll_number','roll_no');
	}

	public function allow(){
		return $this->hasOne(ExamFormAllow::class,'roll_no','roll_no');
	}

	public function getPhotoAttribute()
    {

        if ($this->getMedia('photo')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('photo')->last()->getFullUrl();
        }
    }
	public function getSignatureAttribute()
    {

        if ($this->getMedia('signature')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('signature')->last()->getFullUrl();
        }
    }
	public function getChallanAttribute()
    {

        if ($this->getMedia('challan')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('challan')->last()->getFullUrl();
        }
    }
	public function getDocAttribute()
    {

        if ($this->getMedia('doc')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('doc')->last()->getFullUrl();
        }
    }

	public function getSubjectNameAttribute()
    {
		$subject_name = Subject::whereIn('sub_code',explode(' ', $this->subject))->where('semester_id',$this->semester)->pluck('name')->toArray();
		return implode(', ', $subject_name);
    }
	public function getSubjectSequenceAttribute()
    {
        $subjects = Subject::where('course_id',$this->course_id)
        ->where('semester_id',$this->semester)
        ->whereIn('sub_code',explode(' ',$this->subject))
        ->orderBy('position','ASC')
        ->get();
        return $subjects;
    }
	public function getAdmitCardSubjects()
    {  $batch='';
		$student = Student::where('roll_number',$this->roll_no)->first();
    $match_rollno = substr($student->roll_number,0,2);
    if($match_rollno == '16'){
      $batch= '2016-2017';
    }elseif($match_rollno == 17){
        $batch= '2017-2018';
    }elseif($match_rollno == 18){
        $batch= '2018-2019';
    }elseif($match_rollno == 19){
        $batch= '2019-2020';
    }elseif($match_rollno == 20){
        $batch= '2020-2021';
    }elseif($match_rollno == 21){
      $batch='2021-2022';
    }elseif($match_rollno == 22){
        $batch= '2022-2023';
    }elseif($match_rollno == 23){
        $batch= '2023-2024';
    }elseif($match_rollno == 24){
        $batch= '2024-2025';
    }
        $enrollment = Enrollment::where('roll_number',$this->roll_no)->first();
        if(!$enrollment){
            return false;
        }
        if($this->form_type == 'regular' || $this->form_type == 'compartment'){
            $subjects = explode(' ',$this->subject);
            $course_ids = array($this->course_id);
            $semester_ids = array($this->semester);
        }else{
            $subjects = BackPaper::where('exam_fee_id',$this->id)->pluck('sub_code')->toArray();
            $course_ids = BackPaper::where('exam_fee_id',$this->id)->pluck('course_id')->toArray();
            $semester_ids = BackPaper::where('exam_fee_id',$this->id)->pluck('semester_id')->toArray();
        }
        $subjectQuery = Subject::select('subjects.*',DB::raw('null as date'),DB::raw('null as shift'))
        ->whereIn('subjects.course_id',$course_ids)
        ->whereIn('subjects.semester_id',$semester_ids)
        ->whereIn('subjects.sub_code',$subjects)
        ->leftJoin('exam_schedules',function($join){
            $join->on('exam_schedules.paper_code','subjects.sub_code')
            ->on('exam_schedules.courses_id','subjects.course_id')
            ->on('exam_schedules.semester_id','subjects.semester_id')
            ->where('exam_schedules.year',$this->academic_session)
            ->whereNull('exam_schedules.deleted_at');
        });
		if($enrollment->course_id == 37 && $enrollment->stream_id > 0){
            $subjectQuery->where('stream_id',$enrollment->stream_id);
        }
		if($enrollment->course_id == 49){
            $subjectQuery->where('batch',$batch);
        }
        $subjects = $subjectQuery->orderBy('position','asc')
        ->orderBy('date','asc')
        ->distinct()
        ->get();

        $subjects->each(function ($item, $key) {
            if($item->date==null){
                $item->date_order = 1;
            }else{
                $item->date_order = 2;
            }
        });
        $subjects = $subjects->sortByDesc('date_order');
        return $subjects;
    }

    public function getSingleSubjectNameAttribute()
    {
		$subject_name = Subject::whereIn('sub_code',explode(' ', $this->subject))
        ->where('semester_id',$this->semester)
        ->pluck('name')
        ->toArray();
		return $subject_name;
    }

	public function payment()
    {
        $payment = ExamPayment::where('exam_fee_id',$this->id)
        ->where('txn_status','SUCCESS')
        ->first();
        return $payment;
    }

	public function studentByGroup() {
        // dd($this->course_id,$this->semester,$this->academic_session,$this->subject);
		$students = ExamFee::select('subject','roll_no','course_id','semester','academic_session','form_type')
		->where('course_id',$this->course_id)
		->where('semester',$this->semester)
		->where('academic_session',$this->academic_session)
		->whereIn('form_type',['regular','compartment'])
		->where('subject',$this->subject)
		->distinct('subject')
		->orderBy('roll_no','ASC')
		->get();
        return $students;
	}
	public function studentByGroupBack() {
        // dd($this->course_id,$this->semester,$this->academic_session,$this->subject,$this->form_type);
		$students = ExamFee::select('roll_no','course_id','semester','academic_session','form_type')
		->where('course_id',$this->course_id)
		->where('semester',$this->semester)
		->where('academic_session',$this->academic_session)
		->where('form_type',$this->form_type)
		->where('subject_group',$this->subject)
		->distinct('subject_group')
		->orderBy('roll_no','ASC')
		->get();
        // dd($students);
        return $students;
	}
	function get_last_semester(){
		$semester = Semester::where('course_id',$this->course_id)
		->orderBy('semester_number','DESC')
		->first();
        return $semester;
	}

    public function backup_exams(){
        return ExamFee::where('backup_exam_id',$this->id)
        ->whereNotNull('backup_exam_id')
        ->orderBy('id', 'DESC')
        ->withTrashed()
        ->get();
    }
  
}
