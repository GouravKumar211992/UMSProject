<?php
namespace App\Imports;

use App\Models\BulkCouncelling;
use App\Models\Application;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class CouncellingImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'father_name' => ['required','string'],
            'email' => ['required'],
            'dob' => ['required'],
            'gender' => ['required'],
            'mobile' => ['required'],
            'course_name' => ['required'],
            'accademic_session' => ['required'],
            'course_id' => ['required'],
            'subject' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $data = BulkCouncelling::where('email', '=', $row['email'])->first();
        if(!$data) {
            $data = new BulkCouncelling;
            $data->fill($row); 
            $data->save();
            $application = Application::where('email',$row['email'])
            ->where('course_id',$row['course_id'])
            ->where('academic_session',$row['accademic_session'])
            ->where('enrollment_status',0)
            ->first();
            if($application){
                $application->enrollment_status = 1;
                $application->counselled_course_id = $row['counselled_course_id'];
                $application->save();
                BulkCouncelling::where('email',$application->email)
                ->where('course_id',$application->course_id)
                ->where('accademic_session',$application->academic_session)
                ->update(['status'=>1]);
            }
        }

        return $data;
        
    }
}
