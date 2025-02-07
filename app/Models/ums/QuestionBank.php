<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery\Matcher\Subset;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
class QuestionBank extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

     protected $fillable = [
        'campus_id',  
        'program_id',   
        'course_id', 
        'phd_title', 
        'branch_id',  
        'semester_id',      
        'sub_code',      
        'session',      
    ];

    protected $appends = [
        'question_bank_file',
    ];

<<<<<<< HEAD
    public function registerMediaCollections()
    {

        $this->addMediaCollection('question_bank_file')
            ->singleFile();
        $this->addMediaCollection('synopsis_file')
            ->singleFile();
        $this->addMediaCollection('thysis_file')
            ->singleFile();
        $this->addMediaCollection('journal_paper_file')
            ->singleFile();
        $this->addMediaCollection('seminar_file')
            ->singleFile();

        $this->addMediaCollection('domicile_cirtificate')
            ->singleFile();
     }
=======
    // public function registerMediaCollections()
    // {

    //     $this->addMediaCollection('question_bank_file')
    //         ->singleFile();
    //     $this->addMediaCollection('synopsis_file')
    //         ->singleFile();
    //     $this->addMediaCollection('thysis_file')
    //         ->singleFile();
    //     $this->addMediaCollection('journal_paper_file')
    //         ->singleFile();
    //     $this->addMediaCollection('seminar_file')
    //         ->singleFile();

    //     $this->addMediaCollection('domicile_cirtificate')
    //         ->singleFile();
    //  }
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7


    public function Campuse() {
        return $this->belongsTo(Campuse::class, 'campus_id');
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
    public function subject() {
        return $this->belongsTo(Subject::class, 'sub_code','sub_code');
    }

    public function getQuestionBankFileAttribute()
    {
        if ($this->getMedia('question_bank_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('question_bank_file')->first()->getFullUrl();
        }
    }
    public function getSynopsisFileAttribute()
    {
        if ($this->getMedia('synopsis_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('synopsis_file')->first()->getFullUrl();
        }
    }
    public function getThysisFileAttribute()
    {
        if ($this->getMedia('thysis_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('thysis_file')->first()->getFullUrl();
        }
    }
    public function getJournalPaperFileAttribute()
    {
        if ($this->getMedia('journal_paper_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('journal_paper_file')->first()->getFullUrl();
        }
    }
    public function getSeminarFileAttribute()
        {
            if ($this->getMedia('seminar_file')->isEmpty()) {
                return false;
            } else {
                return $this->getMedia('seminar_file')->first()->getFullUrl();
            }
        }

    public function getDomicileCirtificateAttribute()
    {

        if ($this->getMedia('domicile_cirtificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('domicile_cirtificate')->first()->getFullUrl();
        }
    }
}
