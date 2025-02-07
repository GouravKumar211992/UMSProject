<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;  // Keep this
use Spatie\MediaLibrary\InteractsWithMedia;

use Illuminate\Notifications\Notifiable;
<<<<<<< HEAD
use Laravel\Passport\HasApiTokens;
=======
// use Laravel\Passport\HasApiTokens;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Illuminate\Foundation\Auth\User as Authenticatable;

class Faculty extends Authenticatable implements HasMedia
{
<<<<<<< HEAD
    use HasApiTokens, Notifiable, SoftDeletes, InteractsWithMedia;
=======
    use  Notifiable, SoftDeletes, InteractsWithMedia;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

    protected $table = 'facultys';
    protected $guard = 'faculty';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var arraysequence
     */
    // protected $appends = ['profile_image_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at',
    ];

    public function setPasswordAttribute($password)
    {
        if (!is_null($password))
            $this->attributes['password'] = bcrypt($password);
    }
	public function Course() {
		return $this->belongsTo(Course::class, 'course_id');
	}


    protected $appends = [
        'high_school_marksheet_url',
        'inter_marksheet_url',
        'graduation_marksheet_url',
        'post_graduation_marksheet_url',
        'high_school_degree_url',
        'inter_degree_url',
        'graduation_degree_url',
        'post_graduation_degree_url',
        'phd_marksheet_url',
        'mphil_degree_url',
        'mphil_marksheet_url',
    ];

	public function getHighSchoolMarksheetUrlAttribute()
    {
        if ($this->getMedia('high_school_marksheet')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('high_school_marksheet')->first()->getFullUrl();
        }
    }
  
	public function getInterMarksheetUrlAttribute()
    {
        if ($this->getMedia('inter_marksheet')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('inter_marksheet')->first()->getFullUrl();
        }
    }
	public function getGraduationMarksheetUrlAttribute()
    {
        if ($this->getMedia('graduation_marksheet')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('graduation_marksheet')->first()->getFullUrl();
        }
    }
	public function getPostGraduationMarksheetUrlAttribute()
    {
        if ($this->getMedia('post_graduation_marksheet')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('post_graduation_marksheet')->first()->getFullUrl();
        }
    }
	public function getHighSchoolDegreeUrlAttribute()
    {
        if ($this->getMedia('high_school_degree')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('high_school_degree')->first()->getFullUrl();
        }
    }
	public function getInterDegreeUrlAttribute()
    {
        if ($this->getMedia('inter_degree')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('inter_degree')->first()->getFullUrl();
        }
    }
	public function getGraduationDegreeUrlAttribute()
    {
        if ($this->getMedia('graduation_degree')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('graduation_degree')->first()->getFullUrl();
        }
    }
	public function getPostGraduationDegreeUrlAttribute()
    {
        if ($this->getMedia('post_graduation_degree')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('post_graduation_degree')->first()->getFullUrl();
        }
    }
	public function getPhdMarksheetUrlAttribute()
    {
        if ($this->getMedia('phd_marksheet')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('phd_marksheet')->first()->getFullUrl();
        }
    }
    
    public function getMphilMarksheetUrlAttribute()
    {
        if ($this->getMedia('mphil_marksheet')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('mphil_marksheet')->first()->getFullUrl();
        }
    }
    public function getMphilDegreeUrlAttribute()
    {
        if ($this->getMedia('mphil_degree')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('mphil_degree')->first()->getFullUrl();
        }
    }
}