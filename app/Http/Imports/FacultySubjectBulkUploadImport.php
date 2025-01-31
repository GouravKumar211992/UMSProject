<?php
namespace App\Imports;

use App\Models\InternalMarksMapping;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class FacultySubjectBulkUploadImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'faculty_id' => ['required'],
            'campuse_id' => ['required'],
            'course_id' => ['required'],
            'stream_id' => ['required'],
            'program_id' => ['required'],
            'semester_id' => ['required'],
            'sub_code' => ['required'],
            'session' => ['required'],
            'permissions' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $row = (object)$row;
        $data = InternalMarksMapping::where('sub_code',$row->sub_code)
        ->where('course_id',$row->course_id)
        ->where('semester_id',$row->semester_id)
        ->where('stream_id',$row->stream_id)
        ->where('session',$row->session)
        ->where('permissions',$row->permissions)
        ->first();
        if(!$data) {
            $data = new InternalMarksMapping;
            $data->faculty_id = $row->faculty_id;
            $data->campuse_id = $row->campuse_id;
            $data->course_id = $row->course_id;
            $data->stream_id = $row->stream_id;
            $data->program_id = $row->program_id;
            $data->semester_id = $row->semester_id;
            $data->sub_code = $row->sub_code;
            $data->session = $row->session;
            if($row->permissions=='All'){
                $data->permissions = 0;
            }elseif($row->permissions=='Internal'){
                $data->permissions = 1;
            }elseif($row->permissions=='External'){
                $data->permissions = 2;
            }elseif($row->permissions=='Practical'){
                $data->permissions = 3;
            }
            $data->save();
        }

        return $data;
        
    }
}
