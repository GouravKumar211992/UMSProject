<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class StudentSubject extends Model
{
     use SoftDeletes;
	
    protected $fillable = [
			'student_semester_fee_id' ,
			'enrollment_number' ,
            'roll_number' ,
            'session' ,
            'program_id',
            'course_id',
            'semester_id',
			'sub_code',
            'sub_name',
            'campus_id',
            'type',
            ];

			protected $appends = [
				'campus_id',
				'internal_marks',
				'external_marks',
				'pratical_marks',
                'subject_sequence',
				'internal_marks_filled',
				'external_marks_filled',
				'practical_marks_filled',
			];

	public function getCampusIdAttribute(){
		return campus_name($this->enrollment_number);
	}
		
	public function student() {
		return $this->hasOne(Student::class, 'roll_number','roll_number')->withTrashed();
	}
	public function students() {
		return $this->hasOne(Student::class, 'roll_number','roll_number');
	}
	public function Subject() {
		return $this->hasOne(Subject::class, 'sub_code','sub_code')
			->where('semester_id',$this->semester_id)
			->where('course_id',$this->course_id)
			->withTrashed();
	}

	public function course() {
		return $this->hasOne(Course::class, 'id','course_id')->withTrashed();
	}
	public function semester() {
		return $this->hasOne(Semester::class, 'id','semester_id')->withTrashed();
	}

	public function subjectDetails() {
		return $this->hasOne(Subject::class, 'sub_code','sub_code')
			->where('semester_id',$this->semester_id)
			->where('course_id',$this->course_id)
			->withTrashed();
	}

	public function getInternalMarksFilledAttribute()
	{
	   $marks = InternalMark::where('roll_number',$this->roll_number)
	   ->where('course_id',$this->course_id)
	   ->where('semester_id',$this->semester_id)
	   ->where('sub_code',$this->sub_code)
	   ->where('type',$this->type)
	   ->where('session',$this->session)
	   ->first();
	   return $marks;
	}
	public function getExternalMarksFilledAttribute()
	{
	   $marks = ExternalMark::where('roll_number',$this->roll_number)
	   ->where('course_id',$this->course_id)
	   ->where('semester_id',$this->semester_id)
	   ->where('sub_code',$this->sub_code)
	   ->where('type',$this->type)
	   ->where('session',$this->session)
	   ->first();
	   return $marks;
	}
	public function getPracticalMarksFilledAttribute()
	{
	   $marks = PracticalMark::where('roll_number',$this->roll_number)
	   ->where('course_id',$this->course_id)
	   ->where('semester_id',$this->semester_id)
	   ->where('sub_code',$this->sub_code)
	   ->where('type',$this->type)
	   ->where('session',$this->session)
	   ->first();
	   return $marks;
	}

	public function assigned_faculty_details(){
		$data = InternalMarksMapping::where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('sub_code',$this->sub_code)
		->get();
		return $data;
	}

	public function result() {
		return $this->hasOne(Result::class, 'roll_no','roll_number')
			->where('subject_code',$this->sub_code)
			->where('semester',$this->semester_id)
			->where('course_id',$this->course_id)
			->withTrashed();
	}

	public function internal_marks_details($session){
		$data = InternalMark::where('course_id',$this->course_id)
			->where('semester_id',$this->semester_id)
			->where('sub_code',$this->sub_code)
			->where('session',$session)
			->where('roll_number',$this->roll_number)
			->first();
			return $data;
	}
	public function external_marks_details($session){
		$data = ExternalMark::where('course_id',$this->course_id)
			->where('semester_id',$this->semester_id)
			->where('sub_code',$this->sub_code)
			->where('session',$session)
			->where('roll_number',$this->roll_number)
			->first();
			return $data;
	}
	public function pratical_marks_details($session){
		$data = PracticalMark::where('course_id',$this->course_id)
			->where('semester_id',$this->semester_id)
			->where('sub_code',$this->sub_code)
			->where('session',$session)
			->where('roll_number',$this->roll_number)
			->first();
		return $data;
	}
	public function getSubjectSequenceAttribute()
    {
        $subjects = Subject::withTrashed()
		->where('course_id',$this->course_id)
        ->where('semester_id',$this->semester_id)
        ->whereIn('sub_code',explode(' ',$this->subject_group))
        ->orderBy('position')
        ->get();
        return $subjects;
    }

	public function studentByGroup() {
		$students = StudentSubject::withTrashed()
		->select('roll_number','course_id','semester_id','session','type')
		->where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		->where('session',$this->session)
		->whereIn('type',['regular','compartment'])
		->where('subject_group',$this->subject_group)
		->distinct('subject_group')
		->orderBy('roll_number','ASC')
		->get();
        return $students;
	}

	public function studentByGroupFinalBack($batch=null,$roll_no=null) {
		$month_year_array = [];
		if($this->month_year_text){
			$month_year_array = explode(',',$this->month_year_text);
		}
		$students_query = StudentSubject::withTrashed()
		->select('roll_number',DB::raw('roll_number as roll_no'),'course_id','semester_id',DB::raw('semester_id as semester'),'session',DB::raw('session as academic_session'),'type',DB::raw('type as form_type'))
		->where('course_id',$this->course_id)
		->where('semester_id',$this->semester_id)
		// ->where('roll_number','LIKE','211010%')
		->where('session',$this->session)
		->where('type',$this->type)
		->where(function($q) use ($month_year_array){
			if(count($month_year_array)>0){
				foreach($month_year_array as $index=>$month_year_row){
					if($index==0){
						$q->where('student_subjects.created_at','LIKE',$month_year_row.'%');
					}else{
						$q->orWhere('student_subjects.created_at','LIKE',$month_year_row.'%');
					}
				}
			}
		});
		if($batch){
			$students_query->where('roll_number','LIKE',$batch.'%');
		}
		if($roll_no){
			$students_query->where('roll_number',$roll_no);
		}
		$students = $students_query->where('subject_group',$this->subject_group)
		->distinct('subject_group')
		->orderBy('roll_number','ASC')
		->get();
        return $students;
	}
	function get_last_semester(){
		$semester = Semester::where('course_id',$this->course_id)
		->orderBy('semester_number','DESC')
		->first();
        return $semester;
	}


}
