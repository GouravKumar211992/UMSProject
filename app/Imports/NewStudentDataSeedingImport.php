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

use App\Models\StudentNew;
use DB;

class NewStudentDataSeedingImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
        //    'roll_no' => ['required'],
        //    'enrollment_no' => ['required'],
        ];
    }

    public function model(array $row)
    {
		$row = array_filter($row);
		if($row['application_for']==null){
			return $row;
		}
        $StudentNew = StudentNew::where('id',$row['id'])->first();
        try {
            if(!$StudentNew){
                if($row['email'] =='' || $row['email']==null){
                   // $row['email'] = $row['first_name'].'@gmail.com';
                }
				if(!isset($row['campuse_id'])){
					$row['campuse_id'] = 0;
				}
                $row['date_of_birth'] = date('Y-m-d',strtotime($row['date_of_birth']));
				if(isset($row['status'])){
					$row['status'] = $row['status'];
				}else{
					$row['status'] = 0;
				}
                if($row['status']==0){
                    $StudentNew = new StudentNew;
                    $StudentNew->id = $row['id'];
                    $StudentNew->application_for = $row['application_for'];
                    $StudentNew->campuse_id = $row['campuse_id'];
                    $StudentNew->category_id = $row['category_id'];
                    $StudentNew->course_id = $row['course_id'];
                    $StudentNew->subjects = $row['subjects'];
                    $StudentNew->first_name = $row['first_name'];
                    $StudentNew->mobile = $row['mobile'];
                    $StudentNew->email = $row['email'];
                    $StudentNew->date_of_birth = $row['date_of_birth'];
                    $StudentNew->address = $row['address'];
                    $StudentNew->father_first_name = $row['father_first_name'];
                    $StudentNew->mother_first_name = $row['mother_first_name'];
                    $StudentNew->domicile = $row['domicile'];
                    $StudentNew->gender = $row['gender'];
                    $StudentNew->nationality = $row['nationality'];
                   // $StudentNew->blood_group = $row['blood_group'];
                    $StudentNew->session = $row['session'];
                    $StudentNew->status = $row['status'];
					if(isset($row['roll_no'])){
						$StudentNew->roll_no = $row['roll_no'];
					}else{
						$StudentNew->roll_no = null;
					}
					if(isset($row['enrollment_no'])){
						$StudentNew->enrollment_no = $row['enrollment_no'];
					}else{
						$StudentNew->enrollment_no = null;
					}
                    $StudentNew->save();

                    echo $row['id'].' ';
                }
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
              return $e->getMessage().' ';
        }
        return $StudentNew;
    }
}
