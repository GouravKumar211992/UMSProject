<?php
namespace App\models\ums;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Phd2023EntranceTest extends Model
{
    use SoftDeletes;

	protected $table = 'phd_2023_entrance_test';

    public function getApplication() {
        return $this->hasOne(Application::class,'application_no','application_no');
    }

    public function getApplicationSubjectWise(){
        $app = Phd2023EntranceTest::where('subject',$this->subject)
        ->orderBy('roll_number')
        ->get();
        return $app;
    }

}


