<?php

namespace App\models\ums;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;

class BulkCouncelling extends Model
{
    public $table="bulk_councelling";
    protected $fillable = [
        'name',
        'father_name',
        'email',
        'dob',
        'gender',
        'mobile',
        'course_name',
        'accademic_session',
        'course_id',
        'counselled_course_id',
        'subject',
    ];
}
