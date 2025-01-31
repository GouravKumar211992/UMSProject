<?php

namespace App\models\ums;

use App\Models\ums\Semester;
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
