<?php
namespace App\Imports;

use App\Models\Result;
use App\Models\Semester;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class TrBulkUploadImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'roll_no' => ['required'],
            'course_id' => ['required'],
            'semester_id' => ['required'],
            'exam_session' => ['required'],
            'result' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $row = (object)$row;
        $loopLimit = 12;
        $resultArray = [];
        for($loop=1; $loop<=$loopLimit; $loop++){
            $subjectType = 'subject'.$loop;
            $internalType = 'internal'.$loop;
            $externalType = 'external'.$loop;
            if(isset($row->$subjectType)){
                $subject    = $row->$subjectType;
                $internal    = $row->$internalType;
                $external    = $row->$externalType;
                $enrollData = Student::select('enrollment_no')->where('roll_number',$row->roll_no)->first();
                $semesterData = Semester::find($row->semester_id);
                $data = Result::where('roll_no',$row->roll_no)
                ->where('course_id',$row->course_id)
                ->where('semester',$row->semester_id)
                ->where('subject_code',$subject)
                ->where('exam_session',$row->exam_session)
                ->count();
                if($data==0){
                    $dataArray = [
                        'enrollment_no' => $enrollData->enrollment_no,
                        'roll_no' => $row->roll_no,
                        'exam_session' => $row->exam_session,
                        'semester' => $row->semester_id,
                        'semester_number' => $semesterData->semester_number,
                        'semester_final' => (isset($row->semester_final))?$row->semester_final:0,
                        'course_id' => $row->course_id,
                        'subject_code' => $subject,
                        'oral' => (isset($row->oral))?$row->oral:0,
                        'internal_marks' => $internal,
                        'external_marks' => $external,
                        'practical_marks' => (isset($row->practical_marks))?$row->practical_marks:0,
                        'total_marks' => (int)$internal + (int)$external,
                        'max_internal_marks' => 25,
                        'max_external_marks' => 75,
                        'max_total_marks' => 100,
                        'credit' => (isset($row->credit))?$row->credit:0,
                        'grade_letter' => (isset($row->grade_letter))?$row->grade_letter:null,
                        'grade_point' => (isset($row->grade_point))?$row->grade_point:0,
                        'qp' => (isset($row->qp))?$row->qp:0,
                        'sgpa' => (isset($row->sgpa))?$row->sgpa:0,
                        'cgpa' => (isset($row->cgpa))?$row->cgpa:0,
                        'result' => (isset($row->result))?$row->result:null,
                        'year_back' => (isset($row->year_back))?$row->year_back:0,
                        'result_overall' => (isset($row->result_overall))?$row->result_overall:null,
                        'created_at' => date('Y-m-d'),
                        'created_at' => date('Y-m-d')
                    ];
                    $resultArray[] = $dataArray;
                }
            }
        }
        Result::insert($resultArray);
    }
}
