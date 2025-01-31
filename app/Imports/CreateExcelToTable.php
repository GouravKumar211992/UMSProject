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

use Illuminate\Support\Facades\Schema;
use DB;

class CreateExcelToTable implements ToModel, WithValidation, WithHeadingRow, WithCalculatedFormulas
{
    public function rules(): array
    {
        return [
            // 'roll_no' => ['required'],
            // 'enrollment_no' => ['required'],
        ];
    }

    public function model(array $row)
    {
        $header = array_keys($row);
        unset($header[0]);
        $table_name = 'users_jrdu';
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function($table) use ($header){
                $table->bigIncrements('id');
                foreach($header as $value){
                    $table->text($value)->nullable();
                }
                $table->integer('status')->default(0)->comment('0=pending, 1=migrated');
            });
        }
        $check_dup = DB::table($table_name)->where('id',$row['regno'])->first();
        if(!$check_dup){
            DB::table($table_name)->insert($row);
            // dd($row);
            echo $row['regno'].' ';
        }


    }
}
