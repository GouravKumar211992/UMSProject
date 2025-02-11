<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\ums\Semester;
use App\Models\ums\Result;
use App\Http\Traits\ResultsTrait;
use Artisan;

class ResultCGPAUpdate extends Command
{
	use ResultsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ResultCGPAUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $roll_no = $request->roll_no;
        $course_id = $request->course_id;
        //  $roll_nos = ['211010348','211010176','211010267','211010187','211010290','211010045','211010231','211010317','211010208','211010308','211010249','211010310','211010037','211010340','211010311','211010211','211010165','211010394','211010140','211010037','211010045','211010092','211010119','211010121','211010294','211010302','211010308','211010319','211010325','211010348','211010185','211010234','211010249','211010397','211010023','211010113','211010135','211010161','211010193','211010212','211010214','211010217','211010280','211010340','211010353','211010379','211010404','211010042','211010232','211010342','211010025','211010061','211010006','211010122','211010074','211010162','211010067','211010052','211010125','211010128','211010211','211010310','211010336','211010377','211010001','211010288','211010154','211010140','211010394','211010130','211010237','211010293','211010399','211010197','211010284','211010254','211010352','211010082','211010280','211010176','211010267','211010248','211010055','211010045','211010075','211010037','211010319','211010325','211010025','211010052','211010208','211010127','211010308','211010128','211010310','211010359','211010177','211010211','211010051','211010337','211010300','211010140','211010011','211010162','211010352','211010353','211010394'];

        $course_id_not_run = [49,64,95,96];
        $students_query = Result::select('roll_no','course_id')
        ->where('result_type','new')
        ->whereNotIn('course_id',$course_id_not_run);
        // $students_query->where('course_id',3);
        // ->where('roll_no','LIKE','22'.'%')
        //  $students_query->whereIn('roll_no',$roll_nos);
        if($roll_no){
            $students_query->where('roll_no',$roll_no);
        }
        if($course_id){
            $students_query->where('course_id',$course_id);
            $students_query->where('exam_session','LIKE','2023-2024%');
        }else{
            $students_query->where('exam_session','LIKE','2023-2024%');
        }

        $students = $students_query->distinct('roll_no')->get();
        // dd($students->count());
        echo $students->count().'-';
        foreach($students as $index=>$student){
            $semesters = Semester::where('course_id',$student->course_id)
            ->orderBy('semester_number')
            ->get();
            $failed_semester_number = null;
            foreach($semesters as $semester){
                $result_details = Result::where('roll_no',$student->roll_no)
                ->where('course_id',$semester->course_id)
                ->where('semester',$semester->id)
                // ->where('result_type','new')
                ->first();
                if($result_details){
                    $all_result = $result_details->get_semester_result_for_cgpa(1);
                    $all_result_single = $all_result->first();
                    if($all_result_single->result!='PASS' && $all_result->first()->result!='P' && $all_result->first()->result!='PASSED'){
                        $failed_semester_number = $all_result_single->semester_number;
                    }

                    $all_result_ids = $all_result->pluck('id')->toArray();
                    $cgpa = $this->get_calculate_cgpa($result_details);
                    Result::whereIn('id',$all_result_ids)
                    ->update(['cgpa' => $cgpa]);
                }
            }
            Result::where('roll_no',$student->roll_no)
            ->where('course_id',$semester->course_id)
            ->where('semester_number',1)
            ->update([
                'result_overall'=>null,
                'failed_semester_number'=>null
            ]);
            if($failed_semester_number){
                Result::where('roll_no',$student->roll_no)
                ->where('course_id',$semester->course_id)
                ->where('semester_number',1)
                ->update([
                    'result_overall'=>'FAILED',
                    'failed_semester_number'=>$failed_semester_number
                ]);
            }
            
            echo $index.' ';
            if(!isset($student) || !$student){
                dd('Only Old Agency record available for Roll.'.$roll_no);
            }
            Artisan::call('command:ResultFinalSemesterDataUpdate', [
                'roll_no' => $student->roll_no,
            ]);
        }
    }

}

