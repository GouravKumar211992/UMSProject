<?php
namespace App\Models\ums; 
use App\Models\ums\Subject;
use App\Models\ums\Course;
use App\Models\ums\Stream;
use App\Models\ums\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia; 
use Spatie\MediaLibrary\HasMedia; 

class Scrutiny extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia; 

    protected $table = 'scrutinies';


    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'id', 'course_id');
    }

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'id', 'semester_id');
    }

    public function course()
    {
        return $this->hasOne(Course::class, 'id', 'course_id');
    }

    public function semester()
    {
        return $this->hasOne(Semester::class, 'id', 'semester_id');
    }

    public function streams()
    {
        return $this->hasMany(Stream::class, 'id', 'branch_id');
    }

    public function stream()
    {
        return $this->hasOne(Stream::class, 'id', 'branch_id');
    }

    protected $appends = [
        'challan',
        'photo',
        'signature',
    ];

   
    public function getChallanAttribute()
    {
        if ($this->getMedia('challan')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('challan')->last()->getFullUrl();
        }
    }

   
    public function getPhotoAttribute()
    {
        if ($this->getMedia('photo')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('photo')->last()->getFullUrl();
        }
    }

   
    public function getSignatureAttribute()
    {
        if ($this->getMedia('signature')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('signature')->last()->getFullUrl();
        }
    }

   
    public function mbbsformdata()
    {
        $subject_array = explode(' ', $this->sub_code);
        $batch = batchFunctionMbbs($this->roll_no);

        if ($this->course_id == 49) {
            $subjects = Subject::where('batch', $batch)
                ->where('semester_id', $this->semester_id)
                ->whereIn('sub_code', $subject_array)
                ->get();
        } else {
            $subjects = Subject::where('semester_id', $this->semester_id)
                ->whereIn('sub_code', $subject_array)
                ->get();
        }
        
        return $subjects;
    }
}
