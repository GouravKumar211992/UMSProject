<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;

class StudentOldAll extends Model
{
    protected $table = 'student_old_all';
    public $timestamps = false;

    public $fillable = [
        'id',
        'roll_no','enrollment_no','application_for',
        'category_id','course_id','coursename',
        'subjects','first_name','mobile','email',
        'date_of_birth','address','father_first_name',
        'mother_first_name','domicile',
        'gender','nationality','blood_group',
        'session','paper_type','campus_code',
        'stream_code','semester','adhar',
        'status',
        'status_description'
    ];
}
