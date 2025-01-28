<?php
namespace App\Http\Traits;

use App\Models\ums\Enrollment;
use App\Models\ums\Result;
use App\Models\ums\Semester;
use App\Models\ums\Subject;
use Illuminate\Support\Facades\Artisan;

trait ResultsTrait {

    // public function createResult($sub_code,$course_id,$semester_id,$session,$roll_no) {
    //     $student = Enrollment::where('roll_number',$roll_no)->first();
    //     if(!$student){
    //         return '';
    //     }
    //     $subject = Subject::where('sub_code',$sub_code)
    //         ->where('course_id',$course_id)
    //         ->where('semester_id',$semester_id)
    //         ->first();
    //     $semester = Semester::find($semester_id);
    //     $result = Result::select('results.*')
    //         ->where('roll_no',$roll_no)
    //         ->where('subject_code',$sub_code)
    //         ->where('course_id',$course_id)
    //         ->where('semester',$semester_id)
    //         ->where('exam_session',$session)
    //         ->first();
    //         if(!$result){
    //             $result = new Result;
    //             $result->enrollment_no= $student->enrollment_no;
    //             $result->roll_no = $student->roll_number;
    //             $result->exam_session = $session;
    //             $result->semester = $semester_id;
    //             $result->semester_number = $semester->semester_number;
    //             $result->course_id = $course_id;
    //             $result->subject_code = $sub_code;
    //             $result->subject_name = ($subject)?$subject->name:null;
    //             $result->subject_position = ($subject)?$subject->position:1;
    //             $result->internal_marks = 'ABSENT';
    //             $result->external_marks = 'ABSENT';
    //             $result->practical_marks = 'ABSENT';
    //             $result->total_marks = 'ABSENT';
    //             $result->max_internal_marks = ($subject)?$subject->internal_maximum_mark:25;
    //             $result->max_external_marks = ($subject)?$subject->maximum_mark:75;
    //             $result->max_total_marks = ($subject)?($subject->internal_maximum_mark + $subject->maximum_mark):100;
    //             $result->credit =  ($subject)?$subject->credit:0;
    //             $result->created_at = date('Y-m-d h:i:s');
    //             $result->save();
    //         }
    // }

    public function trGenerateByCommand($result_single){
        $results_query = Result::has('student')
        ->where('roll_no',$result_single->roll_no)
        ->where('back_status_text',$result_single->back_status_text)
        ->where('exam_session',$result_single->exam_session)
        ->where('course_id',$result_single->course_id)
        ->where('semester',$result_single->semester)
        ->where('status',1);
        $results = clone $results_query;
        $results_final = clone $results_query;
        $absents_array_results = clone $results_final;
        $failed_count_results = clone $results_final;
        $results = $results->get();
        if($results->count()>0){
            foreach($results as $result){
                $this->updateGradeLetter($result);
            }

            $this->updateQpSgpa($result);
            $results = $results_final->get();
            $results_single = $results_final->first();
            $absents_array = $absents_array_results->where('external_marks','ABS')->pluck('external_marks')->toArray();
            $failed_count = $failed_count_results->where('grade_letter','F')->count();
            $result_final_text = '';
            $getResult = $results_single->getResult($results_single->sgpa);
            $result_final_text = $results_single->getResultFinal($getResult,$absents_array,$failed_count,$results->count());
            $this->updateTrResult($results_single,$result_final_text);
        }
    }

    public function updateTrResult($result_single,$result_final_text){
        if($result_single->status==1){
            $result_query = Result::has('student')
            ->where('roll_no',$result_single->roll_no)
            ->where('back_status_text',$result_single->back_status_text)
            ->where('exam_session',$result_single->exam_session)
            ->where('course_id',$result_single->course_id)
            ->where('semester',$result_single->semester)
            ->where('status',1);
            $result_update = clone $result_query;
            $result_all = clone $result_query;
            $results = $result_all->get();
            $result_external_cancelled = clone $result_query;
            $result_external_cancelled  = $result_external_cancelled->where('external_marks_cancelled','UFM')->first();
            if($result_external_cancelled){
                $update_array = [
                    'qp' => 0,
                    'sgpa' => '0.00',
                    'cgpa' => '0.00',
                    'result' => 'WH',
                    'required_marks' => $this->get_obtained_marks($results,0),
                    'obtained_marks' => $this->get_obtained_marks($results,1),
                    'total_required_marks' => $this->get_total_obtained_marks($result_single,0),
                    'total_obtained_marks' => $this->get_total_obtained_marks($result_single,1),
                    'total_credit' => $this->get_total_credit($results),
                    'total_semester_credit' => $this->get_total_semester_credit($result_single),
                    'total_qp' => 0,
                    'total_sgpa' => 0,
                ];
                $result_update->update($update_array);
            }else{
                if($result_single){
                    if($result_final_text=='F' || $result_final_text=='PCP'){
                        $get_failed_or_pcp = $this->get_failed_or_pcp($result_single,$result_final_text);
                        $result_final_text = $get_failed_or_pcp;
                    }
                    $get_year_back = $this->get_year_back($result_single,$result_final_text);
                    $update_array = [
                        'result' => ($get_year_back!='')?$get_year_back:$result_final_text,
                        'year_back' => ($get_year_back!='')?1:0,
                        'required_marks' => $this->get_obtained_marks($results,0),
                        'obtained_marks' => $this->get_obtained_marks($results,1),
                        'total_required_marks' => $this->get_total_obtained_marks($result_single,0),
                        'total_obtained_marks' => $this->get_total_obtained_marks($result_single,1),
                        'total_credit' => $this->get_total_credit($results),
                        'total_semester_credit' => $this->get_total_semester_credit($result_single),
                        'total_qp' => 0,
                        'total_sgpa' => 0,
                    ];
                    $result_update->update($update_array);
                    $cgpa_update = [
                        'cgpa' => $this->get_calculate_cgpa($result_single),
                    ];
                    $result_update->update($cgpa_update);
                }
            }
            Artisan::call('command:ResultFinalSemesterDataUpdate', [
                'roll_no' => $result_single->roll_no,
            ]);
        }
    }

    public function get_year_back($result_single,$result_final_text){
        if(!$result_single){
            return '';
        }
        $total_subjects = $result_single->get_semester_result(1)->count();
        $total_failed_count = $result_single->get_semester_result(1)->where('grade_letter','F')->count();
        $total_absent_count = $result_single->get_semester_result(1)->where('external_marks','ABS')->count();
        if($total_subjects == $total_absent_count){
            return "A";
        }
        if($total_failed_count > 4){
            return "F";
        }
        $roll_no = $result_single->roll_no;
        $exam_session = $result_single->exam_session;
        $semester_number = $result_single->semester_number;
        $last_semester_number = ($result_single->semester_number-1);
        if ($semester_number % 2 == 0) {
            $course_id = $result_single->course_id;
            $last_semster_data = Semester::where('course_id',$course_id)
                ->where('semester_number',$last_semester_number)
                ->first();
            if(!$last_semster_data){
                dd('Last Semester Not Found',$result_single);
            }
            $last_semster_id = $last_semster_data->id;
            $last_sem_result = Result::where('roll_no',$roll_no)
            ->where('course_id',$course_id)
            ->where('semester',$last_semster_id)
            ->where('exam_session',$exam_session)
            ->first();
            if(!$last_sem_result){
                return '';
            }
            $last_sem_total_failed_count = $last_sem_result->get_semester_result(1)->where('grade_letter','F')->count();
            $total_failed_count = ($total_failed_count + $last_sem_total_failed_count);
            if($total_failed_count > 4){
                return "F";
            }else{
                return '';
            }
        }else{
            return '';
        }
    }
    public function get_failed_or_pcp($result_single,$result_final_text){
        if($result_final_text=='P' || $result_final_text=='PASS' || $result_final_text=='PASSED'){
            return $result_final_text;
        }
        if($result_single->semester_final=='1'){
            return "F";
        }else{
            return "PCP";
        }
    }

    public function get_total_credit($results){
        // need to check this function for optimization
        $total = 0;
        $total = ($total + $results->sum('credit'));
        return $total;
    }
    public function get_total_semester_credit($result_single){
        if($result_single->semester_final == 0){
            return 0;
        }
        // need to check this function for optimization
        $total = 0;
        $results = Result::select('enrollment_no','roll_no','semester','course_id')
            ->where('roll_no',$result_single->roll_no)
            ->where('semester_number', '<=' ,$result_single->semester_number)
            ->distinct()
            ->get();
        foreach($results as $result){
            $get_semester_result = $result->get_semester_result(1);
            $total = ($total + $get_semester_result->sum('credit'));
        }
        return $total;
    }
    public function get_total_qp($result_single){
        if($result_single->semester_final == 0){
            return 0;
        }
        $total = 0;
        $results = Result::select('enrollment_no','roll_no','semester','course_id')
            ->where('roll_no',$result_single->roll_no)
            ->where('semester_number', '<=' ,$result_single->semester_number)
            ->distinct()
            ->get();
        foreach($results as $result){
            $get_semester_result = $result->get_semester_result(1);
            if($get_semester_result->count()>0){
                $total = ($total + $get_semester_result[0]->qp);
            }
        }
        return $total;
    }
    public function get_total_sgpa($result_single){
        if($result_single->semester_final == 0){
            return 0;
        }
        $total = 0;
        $results = Result::select('enrollment_no','roll_no','semester','course_id')
            ->where('roll_no',$result_single->roll_no)
            ->where('semester_number', '<=' ,$result_single->semester_number)
            ->distinct()
            ->get();
        foreach($results as $result){
            $get_semester_result = $result->get_semester_result(1);
            if($get_semester_result->count()>0){
                $total = ($total + $get_semester_result[0]->sgpa);
            }
        }
        return number_format((float)$total, 2, '.', '');
    }
    public function get_obtained_marks($results,$type){
        $total = 0;
        foreach($results as $get_semester_result_single){
            if($type==1){
                $total = ($total + $get_semester_result_single->total_marks);
            }else{
                $total = ($total + $get_semester_result_single->max_total_marks);
            }
        }
        return $total;
    }
    public function get_obtained_marks_mbbs($result_single,$type){
        // need to check this function for optimization
        $total = 0;
        $results = Result::select('enrollment_no','roll_no','semester','course_id')
            ->where('roll_no',$result_single->roll_no)
            ->where('semester', $result_single->semester)
            ->distinct()
            ->get();
        foreach($results as $result){
            $get_semester_result = $result->get_semester_result(1);
            foreach($get_semester_result as $get_semester_result_single){
                if($type==1){
                    $total = ($total + (int)$get_semester_result_single->oral + (int)$get_semester_result_single->internal_marks + (int)$get_semester_result_single->external_marks);
                }else{
                    $total = ($total + $get_semester_result_single->max_total_marks);
                }
            }
        }
        return $total;
    }
    public function get_total_obtained_marks($result_single,$type){
        if($result_single->semester_final==0){
            return 0;
        }
        $total = 0;
        $results = Result::select('enrollment_no','roll_no','semester','course_id')
            ->where('roll_no',$result_single->roll_no)
            ->where('semester_number', '<=' ,$result_single->semester_number)
            ->distinct()
            ->get();
        foreach($results as $result){
            $get_semester_result = $result->get_semester_result(1);
            foreach($get_semester_result as $get_semester_result_single){
                // if($get_semester_result_single->credit > 0){
                // }
                if($type==1){
                    $total = ($total + $get_semester_result_single->total_marks);
                }else{
                    $total = ($total + $get_semester_result_single->max_total_marks);
                }
            }
        }
        return $total;
    }


    public function get_calculate_cgpa($result_single){
        // need to check this function for optimization
        $results = Result::select('enrollment_no','roll_no','semester','course_id')
        ->where('roll_no',$result_single->roll_no)
        ->where('semester_number', '<=' ,$result_single->semester_number)
        ->distinct()
        ->get();
        $semeter_qp = 0;
        $selecter_total_credit = 0;
        foreach($results as $result){
            $get_semester_result = $result->get_semester_result_for_cgpa(1);
            if($get_semester_result->count()>0){
                foreach($get_semester_result as $row){
                    $qp = $row->qp;
                }
                $selecter_credit = $get_semester_result->sum('credit');
                $selecter_total_credit = ($selecter_total_credit + $selecter_credit);
                $semeter_qp = ($semeter_qp + $qp);
            }
        }
        if($selecter_total_credit>0){
            $cgpa = ($semeter_qp / $selecter_total_credit);
        }else{
            $cgpa = 0;
        }
        return number_format((float)$cgpa, 2, '.', '');
    }

    // public function updateTrResultSingle($roll_no,$course_id,$semester_id,$session,$sub_code,$grade_letter,$grade_point){
    //     // need to check this function for optimization
    //     $result_update = Result::where('roll_no',$roll_no)
    //         ->where('exam_session',$session)
    //         ->where('course_id',$course_id)
    //         ->where('semester',$semester_id)
    //         ->where('subject_code',$sub_code)
    //         ->first();
    //         if(!$result_update){
    //             echo 'updateTrResultSingle function';
    //             dd($roll_no,$course_id,$semester_id,$session,$sub_code,$grade_letter);
    //         }
    //         $result_update->grade_letter = $grade_letter;
    //         $result_update->grade_point = $grade_point;
    //         $result_update->save();
    // }

    public function finalizeTr($result,$type){
        if($type==1){
            $result_update = Result::where('course_id',$result->course_id)
            ->where('semester',$result->semester)
            ->where('exam_session',$result->exam_session)
            ->where('roll_no',$result->roll_no)
            ->where('status',1)
            ->where('result_type','new');
            $update_array = [
                'status' => 2
            ];
        }else{
            $result_update = Result::where('course_id',$result->course_id)
            ->where('semester',$result->semester)
            ->where('exam_session',$result->exam_session)
            ->where('roll_no',$result->roll_no)
            ->where('status',2)
            ->where('result_type','new');
            $update_array = [
                'status' => 1
            ];
        }
        $result_update->update($update_array);
    }

    public function sessionName($semester_id,$session)
    {
		$semester_details = Semester::find($semester_id);
        $for_odd_sem = explode('-',$session)[0];
        $for_even_sem = explode('-',$session)[1];
        if($semester_details->semester_number % 2 == 0){
            $year = $for_even_sem;
        }else{
            $year = $for_odd_sem;
        }
        return $this->sessionNameMonth($semester_id).'-'.$year;
	}
    public function sessionNameMonth($semester_id)
    {
		$semester_details = Semester::find($semester_id);
		if($semester_details->semester_number % 2 == 0){
		  $session_name = 'MAY';
		}else{
		  $session_name = 'DECEMBER';
		}
		return $session_name;
	}

    public function insertBackPapers($roll_number,$semester_id,$session,$examType){
        // if($roll_number='221010266'){
            // dd($roll_number,$semester_id,$session,$examType);
        // }
		$result_single = Result::where('roll_no',$roll_number)
        ->where('semester',$semester_id)
        ->distinct()
        ->orderBy('id','DESC')
        ->first();
        if($result_single){
            $results = $result_single->get_semester_result_back(1);
            // if($roll_number=='221010266'){
            //     dd($results);
            // }
        foreach($results as $row){
                $check_result = Result::where('roll_no',$row->roll_no)
                ->where('course_id',$row->course_id)
                ->where('semester',$row->semester)
                ->where('subject_code',$row->subject_code)
                ->where('exam_session',$session)
                ->where('back_status_text',$examType)
                ->first();
                if(!$check_result){
                    $newTask = $row->replicate();
                    $newTask->back_id = $row->id;
                    $newTask->exam_session = $session;
                    $newTask->back_status_text = $examType;
                    $newTask->status = 1;
                    $newTask->result_type = 'new';
                    $newTask->year_back = 0;
                    $newTask->result_overall = null;
                    $newTask->failed_semester_number = null;
                    $newTask->serial_no = null;
                    $newTask->approval_date = null;
                    $newTask->external_marks_cancelled = null;
                    $newTask->current_internal_marks = null;
                    $newTask->current_external_marks = null;
                    $newTask->save();
                }
            }
        }
    }

    public function updateGradeLetter($result){
        $grade = $result->grade();
        if(!$grade){
            dd('Grade not found',$result);
        }
        $grade_letter = $grade->grade_letter;
        if($result->max_internal_marks==0 && $result->max_external_marks==0){
            $grade_letter='P';
        }
        $result->grade_letter = $grade_letter;
        $result->grade_point = $grade->grade_point;
        $result->save();
    }
    public function updateQpSgpa($result_single){
        $results = Result::select('*')
        ->where('roll_no',$result_single->roll_no)
        ->where('course_id',$result_single->course_id)
        ->where('semester',$result_single->semester)
        ->where('exam_session',$result_single->exam_session)
        ->where('back_status_text',$result_single->back_status_text)
        ->get();
        $total_credit = 0;
        $qp = 0;
        foreach($results as $result){
            $total_credit = $total_credit +  (int)$result->credit;
            $qp = $qp + ((int)$result->credit * (int)$result->grade_point);
        }
        if($total_credit==0){
            echo 'total credit updateQpSgpa function';
            dd($results);
        }
        $sgpa = $qp/$total_credit;
        $sgpa = sprintf("%0.2f",$sgpa);
        foreach($results as $result){
            $result->qp = $qp;
            $result->sgpa = $sgpa;
            $result->save();
        }
    }


}

