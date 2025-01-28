<?php

namespace App\Models\ums;
// use Auth;
// use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnrollmentSubject extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
        'enrollment_id','subject_id', 'created_by','updated_by'
    ];
    protected $hidden = [
        'deleted_at',
    ];
	public function enrollment() {
		return $this->belongsTo(Enrollment::class, 'enrollment_id');
	}
	
	public function subject() {
		return $this->belongsTo(Subject::class, 'subject_id')->withTrashed();;
	}

}
