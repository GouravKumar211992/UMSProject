<?php
namespace App\Imports;

use App\Models\Enrollment;
use App\Models\Application;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Http\Request;

class EnrollmentsImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{

    public function rules(): array
    {
        return [
            'application_for' => ['required','numeric'],
            'category_id' => ['required','numeric'],
            'course_id' => ['required','numeric'],
            'subjects' => ['required','string'],
            'first_name' => ['required','string'],
            'mobile' => ['required','numeric'],
            'email' => ['required','email'],
            'date_of_birth' => ['required', 'date'],
            'address' => ['required','string'],
            'father_first_name' => ['nullable', 'string'],
            'mother_first_name' => ['required', 'string'],
            'domicile' => ['nullable','string'],
            'gender' => ['nullable','string'],
            'nationality' => ['nullable','string'],
            'blood_group' => ['nullable','string'],
            'session' => ['nullable'],
        ];
    }

    public function model(array $row)
    {
        $sub_code = explode(" ",$row['subjects'])[0];
        $subject = Subject::where('sub_code',$sub_code)->first();
//        dd($subject);
        if(!$subject){
            dd('This Subject Code is not found ('.$sub_code.')');
        }
        $enrollment_no = createEnrollment($row['application_for'],$row['course_id']);
        $roll_no = createRollNo($row['application_for'],$row['course_id']);

        $application = new Application();
        $application->fill($row);
        if($application->save()){
            $application_id = $application->id;
            $application = Application::find($application->id);
            $application->application_no = 'DSMNRU/REQ/'.$application_id;
            $application->academic_session = '2021-2022';
            $application->is_agree = 1;
            $application->status = 'Enrolled';
            $application->first_Name = $row['first_name'];
            $application->date_of_birth = date('Y-m-d',strtotime($row['date_of_birth']));
            $application->save();
            
            $request = new Request;
            $request->enrollment_no = $enrollment_no;
            $request->roll_number = $roll_no;
            $request->backlog = 'backlog';
//            dd($request);
            $AdmissionController = new \App\Http\Controllers\Admin\AdmissionController;
            $student_id = $AdmissionController->generateEnrollments($request, $application_id);
            if($student_id){
                $request->student_id = $enrollment_no;
                $request->roll_no = $roll_no;
                $request->course_code = $subject->course_id;
                $request->program = $subject->program_id;
                $request->semester = $subject->semester_id;
                $request->academic_session = $row['session'];
                $request->subject = $row['subjects'];
                $generateSemester = $AdmissionController->addsemesterFee($request);
                $ExaminationController = new \App\Http\Controllers\Student\ExaminationController;

                $request->student_id = $student_id;
                $request->enrollment_number = $enrollment_no;
                $request->batch = $row['session'];
                $request->course = $subject->course_id;
                $ExaminationController->examinationForm($request);
            }
        }

        return $application;
        
    }
}
