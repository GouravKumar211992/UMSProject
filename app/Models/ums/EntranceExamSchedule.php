<?php
<<<<<<< HEAD

namespace App\models\ums;
=======
namespace App\Models\Ums;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

use Illuminate\Database\Eloquent\Model;

class EntranceExamSchedule extends Model
{
<<<<<<< HEAD
    //
=======
    // Explicitly defining the table name
    protected $table = 'entrance_exam_schedules';

    // If your primary key is not 'id', you can set it here (optional)
    // protected $primaryKey = 'your_primary_key';

    // To enable mass assignment for the fields you want to insert
    protected $fillable = [
        'program_name',
        'program_code',
        'exam_date',
        'exam_time',
        'exam_ending_time',  // Make sure this is listed here
        'created_at',
        'updated_at'
    ];

    // If your table does not have timestamps
    // public $timestamps = false;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
}
