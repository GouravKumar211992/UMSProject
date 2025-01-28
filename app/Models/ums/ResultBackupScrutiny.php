<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultBackupScrutiny extends Model
{
	use SoftDeletes;
	protected $table = 'results_backup_scrutiny';

	protected $fillable = [
		'result_id', 
		'enrollment_no', 
		'roll_no', 
		'exam_session', 
		'admit_card_id', 
		'semester', 
		'course_id', 
		'subject_code', 
		'internal_marks', 
		'previous_external', 
		'external_marks', 
		'practical_marks', 
		'oral', 
		'total_marks', 
		'scrutiny', 
		'created_at', 
		'updated_at', 
		'deleted_at', 
		'status', 
		'approval_date', 
		'serial_no',
		'obtained_marks',
		'total_obtained_marks',
		'required_marks',
		'total_required_marks',
		'total_credit',
		'total_semester_credit',
		'total_qp',
		'total_sgpa',
    ];

}
