<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Models\ums\Result;

class ResultSerialNoUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ResultSerialNoUpdate';

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
        $students_query = Result::select('roll_no','course_id','semester','exam_session');
            if($request->roll_no){
                $students_query->where('roll_no',$request->roll_no);
            }
            if($request->semester_id){
                $students_query->where('semester',$request->semester_id);
            }
            $students = $students_query->where('status',2)
            ->where(function($query){
                $query->whereNull('serial_no')
                ->orWhere('serial_no',0);
            })
            ->orderBy('roll_no')
            ->orderBy('course_id')
            ->orderBy('semester')
            ->orderBy('exam_session')
            ->distinct()
            ->get();

        foreach($students as $key=> $student_row){
            $serial_no_query = Result::select('serial_no')->orderBy('serial_no','DESC')->first();
            $serial_no = 1;
            if($serial_no_query){
                $serial_no = ($serial_no_query->serial_no + 1);
            }

            $where_clause = [
				'roll_no' => $student_row->roll_no,
				'course_id' => $student_row->course_id,
				'semester' => $student_row->semester,
				'exam_session' => $student_row->exam_session,
				// 'back_status_text' => 'REGULAR',
			];
            $update_array = [
                'serial_no'=>$serial_no,
                // 'approval_date'=> '2023-04-14',
            ];
			Result::where($where_clause)->update($update_array);
			echo $key.' ';
		}
    }
}

