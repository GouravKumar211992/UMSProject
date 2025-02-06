<?php
namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class SubjectBulkUploadImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'program_id' => ['required'],
            'course_id' => ['required'],
            'semester_id' => ['required'],
            'stream_id' => ['required'],
            'name' => ['required'],
            'sub_code' => ['required'],
            'subject_type' => ['required'],
            'type' => ['required'],
            'internal_maximum_mark' => ['required'],
            'maximum_mark' => ['required'],
            'credit' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $row = (object)$row;
        $data = Subject::where('sub_code',$row->sub_code)
        ->where('course_id',$row->course_id)
        ->where('semester_id',$row->semester_id)
        ->where('stream_id',$row->stream_id)
        ->first();
        if(!$data) {
            $data = new Subject;
            $data->program_id = $row->program_id;
            $data->course_id = $row->course_id;
            $data->semester_id = $row->semester_id;
            $data->stream_id = $row->stream_id;
            $data->name = $row->name;
            $data->sub_code = $row->sub_code;
            $data->subject_type = $row->subject_type;
            $data->type = $row->type;
            $data->internal_maximum_mark = $row->internal_maximum_mark;
            $data->maximum_mark = $row->maximum_mark;
            $data->credit = $row->credit;
            $data->save();
        }

        return $data;
        
    }
}
