<?php

namespace App\Models\ums;

use App\Services\Mailers\Mailer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;


class Student extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable, SoftDeletes, InteractsWithMedia;

    // protected $guard = 'students';
    protected $table = 'students';

   
    protected $fillable = [

        'enrollment_no' ,
        'roll_number' ,
        'first_Name' ,
        'last_Name' ,
        'middle_Name' ,
        'hindi_name' ,
        'date_of_birth',
        'email' ,
        'mobile' ,
        'father_first_name' ,
        'father_last_name' ,
        'father_middle_Name' ,
        'mother_first_name' ,
        'mother_last_name' ,
        'mother_middle_Name' ,
        'nominee_first_name' ,
        'nominee_last_name' ,
        'nominee_middle_Name' ,
        'domicile', 
        'gender',  
        // 'category', 
        'nationality', 
        'religion', 
        'marital_status', 
        'user_id', 
        'roll_number',		
        'aadhar',
        'category',
        'disabilty_category',
        'address',
        'pin_code',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var arraysequence
     */
    protected $appends = [
        'full_name',
        'father_name',
        'mother_name',
        'nominee_name',
        'photo',
        'signature',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'deleted_at',
    ];

	public function course() {
		return $this->belongsTo(Course::class, 'course_id')->withTrashed();
	}

    public function enrollments() {
        return $this->hasOne(Enrollment::class, 'roll_number','roll_number');
    }
    public function challenge_allowed() {
        return $this->hasOne(ChallengeAllowed::class, 'roll_no','roll_number');
    }

    public function getFullNameAttribute()
    {

        $name = $this->first_Name;

        if($this->middle_Name) $name .= ' '. $this->middle_Name;
        if($this->last_Name) $name .= ' '. $this->last_Name;

        return strtoupper($name);

    }

    public function getFatherFirstNameAttribute($value){
        return strtoupper($value);
    }
    public function getGenderAttribute($value){
        return strtoupper($value);
    }
    public function getMohterFirstNameAttribute($value){
        return strtoupper($value);
    }

    public function getFatherNameAttribute()
    {

        $name = $this->father_first_name;

        if($this->father_middle_Name) $name .= ' '. $this->father_middle_Name;
        if($this->father_last_name) $name .= ' '. $this->father_last_name;

        return strtoupper($name);

    }

    public function getMotherNameAttribute()
    {

        $name = $this->mother_first_name;

        if($this->mother_middle_Name) $name .= ' '. $this->mother_middle_Name;
        if($this->mother_last_name) $name .= ' '. $this->mother_last_name;

        return strtoupper($name);

    }

    public function getNomineeNameAttribute()
    {

        $name = $this->nominee_first_name;

        if($this->nominee_middle_Name) $name .= ' '. $this->nominee_middle_Name;
        if($this->nominee_last_name) $name .= ' '. $this->nominee_last_name;

        return strtoupper($name);

    }

    // public function setDateOfBirthAttribute($value)
    // {
    //     $this->attributes['date_of_birth'] = date('Y-m-d',strtotime($value));
    // }
    // public function getDateOfBirthAttribute($value)
    // {
    //     $this->attributes['date_of_birth'] = date('d-m-Y',strtotime($value));
    // }
    public function setPasswordAttribute($password)
    {
        if (!is_null($password))
            $this->attributes['password'] = bcrypt($password);
    }

    public function getNameAttribute()
    {
        return $this->first_Name . ' ' . $this->last_Name;
    }


    public function generateNewPassowrd() {


        $login_password = 'dsmnru@123';
        //$login_password = Str::random(8);

        $this->password = $login_password;
        $this->save();

        $mailbox = new MailBox;
        $mailbox->mail_cc =  $mailbox->mail_cc;

        $mailArray = array(
            'email' => $this->email,
            'password' => $login_password,
            'name' => $this->full_name
        );

        $mailbox->mail_body = json_encode($mailArray);
        $mailbox->subject = "DSMNRU Student portal login details";
        $mailbox->mail_to = $this->email;

        // $mailbox->mail_cc = trim($mailbox->mail_cc,",");
        
        $mailbox->category = 'student.password';
        $mailbox->layout = "login_details";
        $mailbox->save();

        $mailer = new Mailer;
        $mailer->emailTo($mailbox);
        

    }

    public function getExamFeeDataForFirstSem(){
        $examFormData = ExamForm::select('exam_fee_id','exam_forms.course_id','exam_forms.semester','enrollment_number','rollno','gender','exam_forms.name','father_name','mother_name','date_of_birth','aadhar','mobile','exam_forms.email','exam_forms.address')->join('semesters','semesters.id','exam_forms.semester')
            ->where('semesters.semester_number', 1)
             ->where('form_type', 'regular')
            ->where('rollno', $this->roll_number)
            ->withTrashed()
            ->distinct()
            ->first();
//        dd($examFormData[0]->examfees->subject_name);
            return $examFormData;
    }
    public function getPhotoAttribute()
    {
        if ($this->getMedia('photo')->isEmpty()) {
            $roll_number = $this->roll_number;
            $applicationsData = Enrollment::where('roll_number',$roll_number)->first();
            $applications = null;
            if($applicationsData){
                $applications = $applicationsData->application;
            }
            if($applications && $applications->photo_url){
                return $applications->photo_url;
            }else{
                $examFee = ExamFee::withTrashed()->where(['roll_no'=>$roll_number])->first();
                if($examFee && $examFee->photo){
                    return $examFee->photo;
                }else{
                    return false;
                }
            }
            return false;
        } else {
            return $this->getMedia('photo')->last()->getFullUrl();
        }
    }
    public function getSignatureAttribute()
    {
        if ($this->getMedia('signature')->isEmpty()) {
            $roll_number = $this->roll_number;
            $applications = Enrollment::where('roll_number',$roll_number)->first()->application;
            if($applications && $applications->signature_url){
                return $applications->signature_url;
            }else{
                $examFee = ExamFee::withTrashed()->where(['roll_no'=>$roll_number])->first();
                if($examFee && $examFee->signature){
                    return $examFee->signature;
                }else{
                    return false;
                }
            }
            return false;
        } else {
            return $this->getMedia('signature')->last()->getFullUrl();
        }
    }


}