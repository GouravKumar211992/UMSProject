<?php

namespace App\Models\ums;
// use Auth;
// use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ApplicationAddress;

class Enrollment extends Model
{
	use SoftDeletes;

	protected $table = 'enrollments';

    protected $fillable = [
		'student_id',
		'enrollment_no',
		'roll_number',
		'application_id',
		'user_id',
		'course_id',
		'stream_id',
		'category_id',
		'academic_session',
		'is_lateral',
		'is_student_studing',
		'current_semester',
    ];
	public function student() {
		return $this->belongsTo(Student::class, 'student_id');
	}
	public function studentData() {
		return $this->hasOne(Student::class, 'roll_number', 'roll_number');
	}
	public function icard() {
		return $this->hasOne(Icard::class,'enrolment_number', 'enrollment_no');
	}
	public function course() {
		return $this->belongsTo(Course::class, 'course_id')->withTrashed();
	}
	public function campus() {
		return $this->belongsTo(Campuse::class, 'campuse_id')->withTrashed();
	}
	public function stream() {
		return $this->hasOne(Stream::class, 'id','stream_id')->withTrashed();
	}
	public function categories() {
		return $this->belongsTo(Course::class, 'category_id')->withTrashed();
	}

	public function generateICard() {

		$iCard = new Icard();

		$application = Application::with(['addresses', 'course', 'payment'])->find($this->application_id);
		$student = Student::find($this->student_id);

		$mailingAddress = $permanentAddress = "";
		foreach($application->addresses as $address) {

			$combineAddress = $address->address .' PS: '.$address->police_station;

			$combineAddress .= " Distt: ".$address->district;
			$combineAddress .= " Pincode: ".$address->pin_code;
			$combineAddress .= " State: ".$address->state_union_territory;

			if($address->address_type == 'permanent') {
				$permanentAddress = $combineAddress;
			}
			else {
				$mailingAddress = $combineAddress;
			}
		}

		$enSubjectIds = EnrollmentSubject::where('enrollment_id', $this->id)->get()->pluck('subject_id');
		$subjects = Subject::whereIn('id', $enSubjectIds)->get()->pluck('name')->toArray();
		$guardian_name=$application->guardian_first_name .' '.$application->guardian_middle_Name.' '.$application->guardian_last_name;
		$iCard->fill([
			'enrolment_number' => $this->enrollment_no,
			'roll_no' => $student->roll_number,
			'student_name' => $student->full_name,
			'father_name' => $student->father_name,
			'father_mobile',
			'mother_name' => $student->mother_name,
			'mailing_address' => $mailingAddress,
			'permanent_address' => $permanentAddress,
			'email' => $student->email,
			'dob' => $student->date_of_birth,
			'gender' => $student->gender,
			'blood_group' => $application->blood_group,
			'student_mobile' => $student->mobile,
			'program' => $application->course? $application->course->name : "",
			'subject' => implode(',', $subjects),
			'academic_session' => $application->academic_session,
			'disablity' => $application->disability_category,
			'nationality' => $student->nationality,
			'fee_receipt_number' =>  $application->payment? $application->payment->transaction_id : "",
			'fee_receipt_date' => $application->payment? $application->payment->txn_date : "",
			 'local_guardian_name'=>$guardian_name,
			'local_guardian_mobile'=>$application->guardian_mobile
		]);

		$iCard->save();
		
		if (!$application->getMedia('photo')->isEmpty()) {
            $profileMedia = $application->getMedia('photo')->first();
			$profileMedia->copy($iCard, 'profile_photo');

        }

		if (!$application->getMedia('signature')->isEmpty()) {
			$signMedia = $application->getMedia('signature')->first();
			$signMedia->copy($iCard, 'signature');
		}


	}
	
	public function application() {
		return $this->belongsTo(Application::class, 'application_id');
	}

	public function first_paper_name(){
		$paper_name = '';
		$exam = ExamFee::where('course_id',$this->course_id)
		->orderBy('created_at')
		->first();
		if($exam){
			$subject = $exam->subject;
			$subject_single = explode(' ',$exam->subject)[0];
			$subject_name = Subject::withTrashed()->where('semester_id',$exam->semester)->first();
			if($subject_name){
				$paper_name = $subject_name->name;
			}
		}
		return $paper_name;
	}

    // public function applicationAddress() {
    //     return $this->hasOne(ApplicationAddress::class, 'application_id','application_id')->where('address_type','permanent');
    // }

	public function courseMaxDuration(){
		$course = Course::find($this->course_id);
		if($course){
			$max_course_duration = $course->max_course_duration;
			$student_batch = batchFunctionReturn($this->roll_number);
			$accademic_session = accademic_session();
			$student_batch_prefix = batchPrefixByBatch($student_batch);
			$accademic_session_prefixt = batchPrefixByBatch($accademic_session);
			if( ($student_batch_prefix+$max_course_duration) > $accademic_session_prefixt ){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

}
