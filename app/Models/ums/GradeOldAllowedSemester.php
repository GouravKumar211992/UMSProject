<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeOldAllowedSemester extends Model
{
    use SoftDeletes;
    protected $table = 'grade_old_allowed_semesters';

    public function semester(){
        return $this->hasOne(Semester::class,'id','semester_id')->withTrashed();
    }

}