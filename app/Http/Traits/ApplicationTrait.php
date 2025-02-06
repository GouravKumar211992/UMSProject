<?php
namespace App\Http\Traits;

use App\Models\ApplicationPayment;
use App\Models\Application;
use App\Models\ExamFee;
use App\Models\ExamPayment;
use App\Models\Student;
use App\Models\Enrollment;
use Auth;
use Razorpay\Api\Api;
use Carbon\Carbon;

trait ApplicationTrait {

    public function insertEnrollmentTable($application,$enrollment_no,$roll_no){
        $enrollment = Enrollment::where('application_id',$application->id)->first();
        if(!$enrollment){
            $enrollment = new Enrollment;
            $enrollment->enrollment_no = $enrollment_no;
            $enrollment->roll_number = $roll_no;
            $enrollment->student_id = $application->id;
            $enrollment->application_id  = $application->id;
            $enrollment->user_id = $application->user_id;
            $enrollment->course_id = $application->counselled_course_id;
            $enrollment->category_id = $application->category_id;
            $enrollment->academic_session = $application->academic_session;
            $enrollment->is_lateral = ($application->lateral_entry=='yes')?1:0;
            $enrollment->save();
        }
    }
    public function insertStudentTable($application,$enrollment_no,$roll_no){
        $student = Student::where('roll_number',$roll_no)->first();
        if(!$student){
            $student = new Student;
            $student->enrollment_no = $enrollment_no;
            $student->roll_number = $roll_no;
            $student->password = $roll_no;
            $student->first_Name = $application->full_name;
            $student->date_of_birth = $application->date_of_birth;
            $student->email = $application->email;
            $student->mobile = $application->mobile;
            $student->father_first_name = $application->father_first_name;
            $student->mother_first_name = $application->mother_first_name;
            $student->nominee_first_name = $application->nominee_first_name;
            $student->domicile = $application->domicile;
            $student->gender = $application->gender;
            $student->nationality = $application->nationality;
            $student->religion = $application->religion;
            $student->marital_status = $application->marital_status;
            $student->user_id = $application->user_id;
            $student->course_type = '';
            $student->mode_of_admission = 'Online';
            $student->aadhar = $application->adhar_card_number;
            $student->category = $application->category;
            $student->disabilty_category = $application->disability_category;
            if($application->addressByApplicationId){
                $student->address = $application->addressByApplicationId->address;
                $student->pin_code = $application->addressByApplicationId->pin_code;
            }
            $student->save();
        }
    }


}

