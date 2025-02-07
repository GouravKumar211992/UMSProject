<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Models\ums\ExamFee;
use App\Models\ums\StudentSubject;
use Illuminate\Support\Facades\Artisan;

class StudentPromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:StudentPromotion {roll_no}';

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
        $academic_session = '2024-2025';
        if($request->academic_session){
            $academic_session = $request->academic_session;
        }
        $studentSubData_query = ExamFee::has('student')
        ->where('academic_session','LIKE',$academic_session.'%')
        // ->where('course_id','45')
        // ->where('form_type','final_back_paper')
        ->whereNotNull('bank_name');
        if($roll_no!='ALL'){
            $studentSubData_query->where('roll_no',$roll_no);
        }


        $studentSubData = $studentSubData_query->get();
        foreach($studentSubData as $key=> $data){
            $subjects = $data->getAdmitCardSubjects();
            foreach($subjects as $add){
                $StudentSubject_dup_check = StudentSubject::select('*')
                ->where('roll_number',$data->roll_no)
                ->where('course_id',$add->course_id)
                ->where('semester_id',$add->semester_id)
                ->where('sub_code',$add->sub_code)
                ->where('session',$data->academic_session)
                ->where('type',$data->form_type)
                ->first();
                if(!$StudentSubject_dup_check){
                    $Student_save = new StudentSubject;
                    $student_studentSubData_array = [
                        'student_semester_fee_id'=>$data->id,
                        'enrollment_number'=>$data->enrollment_no,
                        'roll_number'=>$data->roll_no,
                        'session'=>$data->academic_session,
                        'program_id'=> $add->program_id,
                        'course_id'=>$add->course_id,
                        'semester_id'=>$add->semester_id,
                        'sub_code'=>$add->sub_code,
                        'sub_name'=>$add->name,
                        'corrupted'=>0,
                        'type'=>$data->form_type,
                        'created_at'=> $data->created_at,
                        'updated_at'=> $data->updated_at,
                    ];
                    $Student_save->fill($student_studentSubData_array);
                    $Student_save->save();
                    echo $Student_save->id.' ';
                }
            }
            Artisan::call('command:FinalBackGroupSettingCommand',['roll_no'=>$data->roll_no]);

        }
    }


}
