<?php
namespace App\Imports;

use App\Models\Icard;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ICardsImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{

    public function rules(): array
    {
        return [
            'enrolment_number' => ['required', 'string'],
            'student_name' => ['required','string','max:299'],
            'roll_no' => ['required'],
            'semester' => ['required'],
            'student_mobile' => ['required'],
            'father_name' => ['required','max:99'],
            //'father_mobile' => ['required'],
            'mother_name' => ['required','string'],
            'mailing_address' => ['nullable','string'],
            'permanent_address' => ['nullable','string'],
            'email' => ['nullable', 'string'],
            'dob' => ['required', 'date'],
            'gender' => ['nullable','string'],
            //'blood_group' => ['nullable','string'],
            'program' => ['nullable','string'],
            //'subject' => ['nullable','string'],
            'academic_session' => ['nullable'],
            //'disablity' => ['nullable','string'],
            //'nationality' => ['nullable','string'],
            //'fee_receipt_number' => ['nullable'],
            //'fee_receipt_date' => ['nullable','string'],
            //'local_guardian_name' => ['nullable','string'],
            //'local_guardian_mobile' => ['nullable'],

        ];
    }

    public function model(array $row)
    {
        
        $idCard = Icard::where('enrolment_number', '=', $row['enrolment_number'])
            ->where('roll_no', $row['roll_no'])
            ->withTrashed()
            ->first();
        
        if(!$idCard) {

			$row['type'] = 'offline';
			$row['status'] = 'approved';
            $idCard = new Icard();
        }
        $idCard->fill($row);
        $idCard->save();

        return $idCard;
        
    }
}
