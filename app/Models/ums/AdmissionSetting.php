<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionSetting extends Model
{
    use SoftDeletes;
    //
    protected $table='admission_settings';  
    protected $fillable=['campus_id','course_id','category_id','from_date','to_date','message','session','action_type'];  
    protected $hidden = [
        'deleted_at',
    ];
	public function campus() {
		return $this->hasOne(Campuse::class, 'id','campus_id');
	}
	public function course() {
		return $this->hasOne(Course::class, 'id','course_id');
	}
	public function category() {
		return $this->hasOne(Category::class, 'id','category_id');
	}



}