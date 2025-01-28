<?php

namespace App\models\ums;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;  // Keep this
use Spatie\MediaLibrary\InteractsWithMedia;
use Auth;
use DB;
use Storage;

class Application extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;


    protected $fillable = [

        'application_no',
        'academic_session',
        'application_for',
        'category_id',
        'campuse_id', 
        'course_id',
        'course_preferences', 
        'is_agree' ,
        'user_id' ,
        'status' ,
        'remarks' ,
        'counselling_date' ,
        'first_Name' ,
        'last_Name' ,
        'middle_Name' ,
        'date_of_birth' ,
        'email' ,
        'mobile' ,
        'father_first_name' ,
        'father_last_name' ,
        'father_middle_Name' ,
        'father_mobile' ,
        'mother_first_name' ,
        'mother_last_name' ,
        'mother_middle_Name' ,
        'mother_mobile' ,
        'nominee_first_name' ,
        'nominee_last_name' ,
        'nominee_middle_Name' ,
        'guardian_first_name', 
        'guardian_last_name',  
        'guardian_middle_Name', 
        'guardian_mobile', 
        'domicile', 
        'gender', 
        'category', 
        'certificate_number', 
        'dsmnru_employee', 
        'dsmnru_employee_ward', 
        'disability_category', 
        'freedom_fighter_dependent', 
        'ncc', 
        'nss', 
        'sports', 
        'nationality', 
        'religion', 
        'marital_status', 
        'zero_fee', 
        'annual_income', 
        'is_father_income',         
        'income_certificate_number',        
        'blood_group',      
        'id_proof',         
        'id_number',        
        'adhar_card_number',
        'enrollment_number',
        'sport_level',
        'hostel_facility_required',
        'hostel_for_years',
        'distance_from_university',
        'aiot_rank',
        'aiot_score',
        'udid_number',
        'disability',
        'percentage_of_disability',
        'admission_through',
        'appeared_or_passed',
        'date_of_examination',
        'roll_number',
        'score',
        'merit',
        'rank',
        'dsmnru_relation',
        'counselling_vanue',
        'counselling_time', 
        'subject_id',    
        'lateral_entry',
        'enrollment_status',
        'admission_through_exam_name',
        'dsmnru_student',
        'ward_emp_relation',
        'ward_emp_name',
        'cuet_details',
    ];

    protected $hidden = ['deleted_at'];

    protected $appends = [
        'full_name',
        'photo_url',
        'signature_url',
        'aadharcards_url',
        'caste_certificate_url',
        'disability_certificate_url',
        'upload_disability_certificate',
        'cuet_score_card',
        'income_certificate_url',
        'any_other_url',
        'doc_url',
        'payment_status_text',
        'application_fees',
        'photo_url_user',
        'domicile_cirtificate_user',
        'signature_url_user',
        'course_allowted_for_update_docs',
    ];

    public function course_preference_array($course_preference_id){
		$course = Course::find($course_preference_id);
        return $course;
    }
    public function ifCoursePreferenceRequired(){
		$course = Course::find($this->course_id);
        if($course && $course->course_group){
            return true;
        }else{
            return false;
        }
    }
    public function course_preference_list(){
		$course_ids = explode(',',$this->course_preferences);
        $courses = [];
        foreach($course_ids as $course_id){
            $courses[] = Course::where('id',$course_id)->first();
        }
        return $courses;
    }

  

    // For course counselled id 

    public function counselled_course() {
        return $this->hasOne(Course::class, 'id', 'counselled_course_id');
    }

    public function getCourseAllowtedForUpdateDocsAttribute(){
        $course_allowted = AllowCourseUpdateDocs::where('course_id',$this->course_id)->first();
        return $course_allowted;
    }

    public function getApplicationFeesAttribute(){
        if($this->disability_category==null){
            $disability_type = 2;
        }else{
            $disability_type = 1;
        }
        $fees = \App\Models\ApplicationFees::where('category_id',$this->category_id)->where('type',$disability_type)->first()->fees;
		if($this->course_id==94){
            $late_fees = 500;
			if($this->category=='SC' || $this->category=='ST' || $this->disability=='yes'){
				$fees = (750 + $late_fees);
			}else{
				$fees = (1500 + $late_fees);
			}
		}
        return $fees;
    }


    public function getPaymentStatusTextAttribute()
    {

        if($this->payment_status==0){
            return 'Pending';
        }elseif($this->payment_status==1){
            return 'Paid';
        }elseif($this->payment_status==2){
            return 'Failed';
        }
    }

    public function getFullAddress($type)
    {
        if($type==1){
            $address = ApplicationAddress::where('application_id',$this->id)
            ->where('address_type','permanent')
            ->first();
        }else{
            $address = ApplicationAddress::where('application_id',$this->id)
            ->where('address_type','correspondence')
            ->first();
        }
        if($address){
            return "Address : ".$address->address.", Police Station : ".$address->police_station.", District: ".$address->district.", State: ".$address->state_union_territory.", Country: ".$address->country.", PIN Code : ".$address->pin_code.", Nearest Railway Station : ".$address->police_station." ";
        }else{
            return "";
        }
    }


    public function getFullNameAttribute()
    {

        $name = $this->first_Name;

        if($this->middle_Name) $name .= ' '. $this->middle_Name;
        if($this->last_Name) $name .= ' '. $this->last_Name;

        return $name;

    }


    public function getPhotoUrlUserAttribute()
    {
        $applications = Application::withTrashed()->select('id')->where('user_id',$this->user_id)->orderBy('id','ASC')->distinct('id')->get();
        foreach($applications as $application){
            $media = $application->getMedia('photo')->last();
            if($media){
                $file_name = $media->file_name;
                if(Storage::disk('public')->has($media->id.'/'.$file_name)){
                    return $application->photo_url;
                }
            }
        }
        if($applications){
            $application_id = $applications[0]->id;
            $applicatoin = Application::find($application_id);
            if($applicatoin){
                return Application::find($application_id)->photo_url;
            }else{
                return null;
            }
        }
    }
    public function getDomicileCirtificateUserAttribute()
    {
        $user = Auth::user();
        $applications = Application::withTrashed()->select('id')->where('user_id',$user->id)->orderBy('id','ASC')->distinct('id')->get();
        foreach($applications as $application){
            $media = $application->getMedia('domicile_cirtificate')->first();
            if($media){
                $file_name = $media->file_name;
                if(Storage::disk('public')->has($media->id.'/'.$file_name)){
                    return $application->domicile_cirtificate;
                }
            }
        }
        if($applications){
            $application_id = $applications[0]->id;
            return Application::find($application_id)->domicile_cirtificate;
        }
    }
    public function getSignatureUrlUserAttribute()
    {
        $user = Auth::user();
        $applications = Application::withTrashed()->select('id')->where('user_id',$user->id)->orderBy('id','ASC')->distinct('id')->get();
        foreach($applications as $application){
            $media = $application->getMedia('signature')->last();
            if($media){
                $file_name = $media->file_name;
                if(Storage::disk('public')->has($media->id.'/'.$file_name)){
                    return $application->signature_url;
                }
            }
        }
        if($applications){
            $application_id = $applications[0]->id;
            return Application::find($application_id)->signature_url;
        }
    }

    public function getPhotoUrlAttribute()
    {
        if ($this->getMedia('photo')->isEmpty()) {
            return false;
        } else {
            // $file_name = $this->getMedia('photo')->first()->file_name;
            // dd(Storage::disk('public')->has($file_name));
            // dd($this->getMedia('photo')->first());
            return $this->getMedia('photo')->first()->getFullUrl();
        }
    }


    public function getSignatureUrlAttribute()
    {

        if ($this->getMedia('signature')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('signature')->first()->getFullUrl();
        }
    }

    public function getAadharcardsUrlAttribute()
    {
        if ($this->getMedia('aadharcards')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('aadharcards')->first()->getFullUrl();
        }
    }

    public function getCasteCertificateUrlAttribute()
    {
        if ($this->getMedia('caste_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('caste_certificate')->first()->getFullUrl();
        }
    }

    public function getDisabilityCertificateUrlAttribute()
    {
        if ($this->getMedia('upload_disability_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('upload_disability_certificate')->first()->getFullUrl();
        }
    }

    public function getIncomeCertificateUrlAttribute()
    {
        if ($this->getMedia('income_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('income_certificate')->first()->getFullUrl();
        }
    }

    public function getAnyOtherUrlAttribute()
    {
        if ($this->getMedia('any_other')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('any_other')->first()->getFullUrl();
        }
    }
       public function getDocUrlAttribute()
    {
        if ($this->getMedia('doc')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('doc')->first()->getFullUrl();
        }
    }


    public function entranceExamAdmitCard() {
        return $this->hasOne(EntranceExamAdmitCard::class, 'course_id', 'course_id')->where('session',$this->academic_session);
    }
    public function phdSubject() {
        return $this->hasOne(Subject::class, 'id', 'subject_id')->withTrashed();
    }
    public function categories() {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }
    public function campus() {
        return $this->hasOne(Campuse::class, 'id','campuse_id')->withTrashed();
    }
    public function course() {
        return $this->belongsTo(Course::class, 'course_id')->withTrashed();
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function addresses() {
        return $this->hasMany(ApplicationAddress::class);
    }

    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
    public function phd_2023_entrance_details() {
        return $this->hasOne(Phd2023EntranceTest::class,'application_no','application_no');
    }

    public function enrollment() {
        return $this->hasOne(Enrollment::class);
    }

    public function educations() {
        return $this->hasMany(ApplicationEducation::class);
    }
    public function getAllEducations() {
        return $this->hasMany(ApplicationEducation::class, 'application_id')->orderBy('id','ASC');
    }

    public function payments() {
        return $this->hasMany(ApplicationPayment::class);
    }

    public function payment() {
        return $this->hasOne(ApplicationPayment::class)->orderBy('id', 'desc');
    }
    public function payment_details() {
        return ApplicationPayment::where('application_id',$this->id)
        ->where('txn_status','SUCCESS')
        ->first();
    }

    public function fees() {
        return $this->hasOne(CourseSession::class,'course_id','course_id');
    }

    // public function latestEducationDetails() {
    //     return $this->hasOne(ApplicationEducation::class, 'user_id','user_id')->orderBy('id','DESC');
    // }
    public function applicationAddress() {
        return $this->hasOne(ApplicationAddress::class, 'user_id','user_id');
    }
    public function addressByApplicationId() {
        return $this->hasOne(ApplicationAddress::class, 'application_id','id');
    }

   public function registerMediaCollections(): void
{
    // Personal Information
    $this->addMediaCollection('photo')->singleFile();
    $this->addMediaCollection('signature')->singleFile();

    // Certificates
    $this->addMediaCollection('caste_certificate')->singleFile();
    $this->addMediaCollection('income_certificate')->singleFile();
    $this->addMediaCollection('disability_certificate')->singleFile();
    $this->addMediaCollection('freedom_fighter_dependent_file')->singleFile();
    $this->addMediaCollection('domicile_certificate')->singleFile();

    // Additional Documents
    $this->addMediaCollection('ncc_certificate')->singleFile();
    $this->addMediaCollection('nss_certificate')->singleFile();
    $this->addMediaCollection('sports_certificate')->singleFile();
    $this->addMediaCollection('cuet_score_card')->singleFile();

    // Miscellaneous
    $this->addMediaCollection('any_other')->singleFile();
    $this->addMediaCollection('doc')->singleFile();
}

     public function getUploadDisabilityCertificateAttribute(){
        if ($this->getMedia('upload_disability_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('upload_disability_certificate')->first()->getFullUrl();
        }
     }
     public function getCuetScoreCardAttribute(){
        if ($this->getMedia('cuet_score_card')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('cuet_score_card')->first()->getFullUrl();
        }
     }

    public function getNssCirtificateAttribute()
    {
        if ($this->getMedia('nss_cirtificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('nss_cirtificate')->first()->getFullUrl();
        }
    }
    public function getNccCirtificateAttribute()
    {
        if ($this->getMedia('ncc_cirtificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('ncc_cirtificate')->first()->getFullUrl();
        }
    }
    public function getFreedomFighterDependentFileAttribute()
    {
        if ($this->getMedia('freedom_fighter_dependent_file')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('freedom_fighter_dependent_file')->first()->getFullUrl();
        }
    }
    public function getSporttCirtificateAttribute()
    {
        if ($this->getMedia('sportt_cirtificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('sportt_cirtificate')->first()->getFullUrl();
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

    public function getCasteCirtificateAttribute()
    {
        if ($this->getMedia('upload_caste_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('upload_caste_certificate')->first()->getFullUrl();
        }
    }

    public function getDisabilityCirtificateAttribute()
    {
        if ($this->getMedia('upload_disability_certificate')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('upload_disability_certificate')->first()->getFullUrl();
        }
    }
    public function getAiotScoreCardAttribute()
    {
        if ($this->getMedia('aiot_score_card')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('aiot_score_card')->last()->getFullUrl();
        }
    }
    
    
    /*====================Save all data in Capital letter===============*/
        public function setFirstNameAttribute( $value ) {
          $this->attributes['first_Name'] = strtoupper($value);
        }
        public function setLastNameAttribute( $value ) {
          $this->attributes['last_Name'] = strtoupper($value);
        }
        public function setMiddleNameAttribute( $value ) {
          $this->attributes['middle_Name'] = strtoupper($value);
        }
        public function setFatherFirstNameAttribute( $value ) {
          $this->attributes['father_first_name'] = strtoupper($value);
        }
        public function setFatherLastNameAttribute( $value ) {
          $this->attributes['father_last_name'] = strtoupper($value);
        }
        public function setFatherMiddleNameAttribute( $value ) {
          $this->attributes['father_middle_Name'] = strtoupper($value);
        }
        public function setMotherFirstNameAttribute( $value ) {
          $this->attributes['mother_first_name'] = strtoupper($value);
        }
        public function setMotherLastNameAttribute( $value ) {
          $this->attributes['mother_last_name'] = strtoupper($value);
        }
        public function setMotherMiddleNameAttribute( $value ) {
          $this->attributes['mother_middle_Name'] = strtoupper($value);
        }
        public function setNomineeFirstNameAttribute( $value ) {
          $this->attributes['nominee_first_name'] = strtoupper($value);
        }
        public function setNomineeLastNameAttribute( $value ) {
          $this->attributes['nominee_last_name'] = strtoupper($value);
        }
        public function setNomineeMiddleNameAttribute( $value ) {
          $this->attributes['nominee_middle_Name'] = strtoupper($value);
        }
        public function setGuardianFirstNameAttribute( $value ) {
          $this->attributes['guardian_first_name'] = strtoupper($value);
        }
        public function setGuardianLastNameAttribute( $value ) {
          $this->attributes['guardian_last_name'] = strtoupper($value);
        }
        public function setGuardianMiddleNameAttribute( $value ) {
          $this->attributes['guardian_middle_Name'] = strtoupper($value);
        }
        public function setWardEmpNameAttribute( $value ) {
          $this->attributes['ward_emp_name'] = strtoupper($value);
        }
        public function setWardEmpRelationAttribute( $value ) {
          $this->attributes['ward_emp_relation'] = strtoupper($value);
        }
    
        public function setDomicileAttribute( $value ) {
          $this->attributes['domicile'] = strtoupper($value);
        }

        public function setUpceeScoreAttribute( $value ) {
          $this->attributes['upcee_score'] = strtoupper($value);
        }
        public function setAiotScoreAttribute( $value ) {
          $this->attributes['aiot_score'] = strtoupper($value);
        }
        public function setJeeScoreAttribute( $value ) {
          $this->attributes['jee_score'] = strtoupper($value);
        }



}


