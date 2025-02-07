<?php

namespace App\models\ums;

<<<<<<< HEAD
use App\Models\ums\Semester;
=======
use App\Models\Semester;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Illuminate\Database\Eloquent\Model;

class BackPaperBulk extends Model
{
    public $table="back_paper_bulk";
    protected $fillable = [
        'campus_name',
        'campus_id',
        'enrollment_no',
        'roll_no',
        'course_name',
        'course_id',
        'semester_name',
        'semester_id',
        'sub_code',
        'accademic_session',
        'back_paper_type',
        'status'
    ];
}
