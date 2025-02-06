<?php

namespace App\models\ums;

use Illuminate\Database\Eloquent\Model;

class Provisional extends Model
{
   public $table="provisional_certificates";
   protected $fillable = [
 
      'enrollment_number',
      'roll_number',
      'student_name',
      'father_name',
      'mother_name', 
      'course', 
      'branch', 
      'no_doues', 
      'nodues_certificate', 
      'date_of_migration_certificate', 
        
  ];

   protected $appends = [
      'payment_status_text',
   ];

   public function payment() {
      return $this->hasOne(MigrationPayment::class, 'migration_id');
   }
   public function getPaymentStatusTextAttribute()
   {
      if(!$this->payment){
         return null;
      }
      return $this->payment->txn_status;
   }
   public function student() {
   return $this->hasOne(Student::class, 'roll_number','roll_number');
   }

   public function getNoduesUrlAttribute()
   {
      if ($this->getMedia('nodues_certificate')->isEmpty()) {
         return false;
      } else {
         return $this->getMedia('nodues_certificate')->first()->getFullUrl();
      }
   }

   public function registerMediaCollections()
   {
      $this->addMediaCollection('nodues_certificate')
         ->singleFile();
   }


}
