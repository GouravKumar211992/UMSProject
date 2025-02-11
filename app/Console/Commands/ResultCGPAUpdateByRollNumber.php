<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Result;
use App\Http\Traits\ResultsTrait;

class ResultCGPAUpdateByRollNumber extends Command
{
	use ResultsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ResultCGPAUpdateByRollNumber {roll_no}';

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
        $roll_no = $this->argument('roll_no');
        $students = Result::select('roll_no','course_id')
        ->where('roll_no',$roll_no)
        ->where('result_type','new')
        ->distinct('roll_no')
        ->get();

        foreach($students as $index=>$student){
            $semesters = Semester::where('course_id',$student->course_id)
            ->orderBy('semester_number')
            ->get();
            $failed_semester_number = null;
            foreach($semesters as $semester){
                $result_details = Result::where('roll_no',$student->roll_no)
                ->where('course_id',$semester->course_id)
                ->where('semester',$semester->id)
                ->where('result_type','new')
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
        }
    }

}