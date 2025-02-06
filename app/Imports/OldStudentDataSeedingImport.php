<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Illuminate\Http\Request;

use App\Models\StudentOldAll;
use DB;

class OldStudentDataSeedingImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'roll_no' => ['required'],
            'enrollment_no' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $StudentOldAll = StudentOldAll::where('id',$row['id'])->first();
        try {
            if(!$StudentOldAll){
                $subjects_explode = explode(' ',$row['subjects']);
                $row['subjects'] = implode(' ',array_filter($subjects_explode));
                $row['uploaded_status'] = 1;
                $StudentOldAll = new StudentOldAll;
                $StudentOldAll->fill($row);
                echo $row['id'].' ';
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
              return $e->getMessage().' ';
        }
        return $StudentOldAll;
    }
}
