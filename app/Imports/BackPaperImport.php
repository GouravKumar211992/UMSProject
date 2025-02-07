<?php
namespace App\Imports;

use App\Models\ums\BackPaperBulk;
use App\Models\ums\ApprovalSystem;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class BackPaperImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'campus_name' => ['required'],
            'campus_id' => ['required'],
            'enrollment_no' => ['required'],
            'roll_no' => ['required'],
            // 'course_name' => ['required'],
            // 'course_id' => ['required'],
            // 'semester_name' => ['required'],
            // 'semester_id' => ['required'],
            // 'sub_code' => ['required'],
            'accademic_session' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $data = BackPaperBulk::where('roll_no', $row['roll_no'])
        ->where('course_id', $row['course_id'])
        ->where('semester_id', $row['semester_id'])
        ->where('accademic_session', $row['accademic_session'])
        ->where('back_paper_type', $row['back_paper_type'])
        ->where('sub_code', $row['sub_code'])
        ->first();
        
        if(!$data) {
            $data = new BackPaperBulk;
            $data->fill($row);
            $data->save();
        }
        $application = ApprovalSystem::where('roll_number', $row['roll_no'])
        ->where('course_id', $row['course_id'])
        ->where('semester_id', $row['semester_id'])
        ->where('session', $row['accademic_session'])
        ->where('special_back', $row['back_paper_type'])
        ->first();
        if(!$application){
            if($row['roll_no']!='' && $row['course_id']!='' && $row['semester_id']!='' && $row['accademic_session']!='' && $row['back_paper_type']!=''){
                $BackPaperBulk = BackPaperBulk::where('roll_no', $row['roll_no'])
                ->where('course_id', $row['course_id'])
                ->where('semester_id', $row['semester_id'])
                ->where('accademic_session', $row['accademic_session'])
                ->where('back_paper_type', $row['back_paper_type'])
                ->where('sub_code', $row['sub_code'])
                ->first();
                if($BackPaperBulk){
                    $BackPaperBulk->status = 1;
                    $BackPaperBulk->save();
                }
                $application = new ApprovalSystem;
                $application->roll_number = $row['roll_no'];
                $application->course_id = $row['course_id'];
                $application->semester_id = $row['semester_id'];
                $application->session = $row['accademic_session'];
                $application->special_back = $row['back_paper_type'];
                $application->sub_code = $row['sub_code'];
                $application->save();
            }
        }

        return $data;
        
    }
}
