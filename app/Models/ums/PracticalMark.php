<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PracticalMark extends Model
{
  use SoftDeletes;
  protected $table = 'practical_mraks';
    
    public function subjects() {
      return $this->hasMany(Subject::class, 'sub_code','sub_code');
    }
    public function faculty() {
      return $this->belongsTo(Faculty::class, 'faculty_id');
    }
    public function Category() {
      return $this->belongsTo(Category::class, 'program_id');
    }
    public function Course() {
      return $this->belongsTo(Course::class, 'course_id');
    }
    public function Semester() {
      return $this->belongsTo(Semester::class, 'semester_id');
    }
    public function file() {
		return $this->hasOne(AwardSheetFile::class, 'id','award_sheet_file_id');
	}



}
