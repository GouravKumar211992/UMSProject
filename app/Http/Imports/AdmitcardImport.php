<?php

namespace App\Imports;

use App\Models\BacklogAdmitcard;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class AdmitcardImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
	 public function rules(): array
    {
        return [
            'roll_no' => ['required', 'string'],
            'enrollment_no' => ['required', 'string'],
            'student_name' => ['required','string','max:299'],
            'father_name' => ['required'],
            'mother_name' => ['required'],
            'course_id' => ['required'],
            'branch_id' => ['required','max:99'],
            'semester_number' => ['required','string'],
            'sub_code' => ['nullable','string'],
            'gender' => ['nullable','string'],
            'adhar_card_number' => ['nullable', 'string'],
            'photo' => ['required', 'string'],
			'dob' => ['nullable','string'],
            'sign' => ['nullable','string'],
			'scribe' => ['nullable','string'],
            'category' => ['nullable'],
            'batch' => ['nullable','string'],
            'form_type' => ['nullable','string'],
            
        ];
    }

    public function model(array $row)
    {
        
        $admitcardCard = BacklogAdmitcard::where('enrollment_no', '=', $row['enrollment_no'])
            ->where('roll_no', $row['roll_no'])
			->where('form_type', $row['form_type'])
            ->first();
        
        if(!$admitcardCard) {

			$admitcardCard = new BacklogAdmitcard();
        }
		if($row['dob']){
			$row['dob'] = date('Y-m-d',strtotime($row['dob']));
		}
        $admitcardCard->fill($row);
        $admitcardCard->save();

        return $admitcardCard;
        
    }
    
}
