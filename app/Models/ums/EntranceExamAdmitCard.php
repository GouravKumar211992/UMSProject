<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntranceExamAdmitCard extends Model
{
 use SoftDeletes;

    protected $fillable = [
        'campuse_id','program_id','course_id', 'entrance_exam_date', 'reporting_time','examination_time','end_time','center_id','session'
    ];
    public $table="entrance_exam_admit_card";

    public function Category() {
        return $this->belongsTo(Category::class, 'program_id');
    }
    public function Course() {
        return $this->belongsTo(Course::class, 'course_id');
    }
    
    public function campus() {
        return $this->hasOne(Campuse::class, 'id','campuse_id')->withTrashed();
    }
    public function centerName() {
        return $this->hasOne(ExamCenter::class, 'id','center_id')->withTrashed();
    }
    public function total_applications($type=0) {
        $application_query = Application::select('applications.*')
        ->where('payment_status', 1)
        ->where('academic_session', $this->session)
        ->where('course_id', $this->course_id);
        if($type==1){
            $applications = $application_query->get();
        }else{
            $applications = $application_query->count();
        }
        return $applications;
    }
    public function generated_roll_number_applications($type=0) {
        $application_query = Application::select('applications.*')
        ->where('payment_status', 1)
        ->where('academic_session', $this->session)
        ->where('course_id', $this->course_id)
        ->whereNotNull('entrance_roll_number');
        if($type==1){
            $applications = $application_query->get();
        }else{
            $applications = $application_query->count();
        }
        return $applications;
    }

}
