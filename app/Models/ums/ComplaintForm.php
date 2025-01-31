<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class ComplaintForm extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    //
    protected $table='complaint_forms';  
    protected $fillable=['complaint_no','enrollment_no','roll_number','responder_id','responder_type','course_id','first_Name','complaint'];  
    protected $hidden = [
        'deleted_at',
    ];
    protected $appends = [
        'status_text',
        'responder_type_text',
    ];

    public function getFileAttribute()
    {
        if ($this->getMedia('file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('file')->first()->getFullUrl();
        }
    }
    public function getAttachedAttribute()
    {
        if ($this->getMedia('attached')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('attached')->first()->getFullUrl();
        }
    }
	public function getStatusTextAttribute()
    {
        if($this->status==0){
			return 'Pending';
		}else if($this->status==1){
			return 'Under Process';
		}else if($this->status==2){
			return 'Closed';
		}else{
			return 'Pending';
		}
    }
	public function getResponderTypeTextAttribute()
    {
        if($this->responder_type==0){
			return 'Student';
		}else if($this->responder_type==1){
			return 'Admin';
		}
    }

    function complaintCount(){
        return ComplaintForm::where('roll_number',$this->roll_number)->distinct('complaint_no')->count();
    }
    function latestComplaint(){
        return ComplaintForm::where('complaint_no',$this->complaint_no)
        ->orderBy('id','DESC')
        ->first();
    }

    public function student() {
		return $this->hasOne(Student::class, 'roll_number','roll_number')->withTrashed();
	}
    public function enrollment() {
		return $this->hasOne(Enrollment::class, 'roll_number','roll_number');
	}

}
