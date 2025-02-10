<?php
namespace App\Imports;

use App\Models\BulkCouncelling;
use App\Models\StudentAllFromOldAgency;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class MBBSBulkUploadImport implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            'roll_no' => ['required'],
            'regular_permission' => ['required'],
            'supplementary_permission' => ['required'],
            'challenge_permission' => ['required'],
            'scrutiny_permission' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $row = (object)$row;
        $data = StudentAllFromOldAgency::where('roll_no',$row->roll_no)->first();
        if($data) {
            $data->regular_permission = $row->regular_permission;
            $data->supplementary_permission = $row->supplementary_permission;
            $data->challenge_permission = $row->challenge_permission;
            $data->scrutiny_permission = $row->scrutiny_permission;
            $data->status_description = null;
            $data->save();
        }

        return $data;
        
    }
}
