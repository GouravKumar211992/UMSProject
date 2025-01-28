<?php
namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;

class ChallengeAllowed extends Model
{
    protected $table = 'challange_allowed';
    protected $fillable = ['roll_no','step'];
	public function student() {
		return $this->hasOne(Student::class, 'roll_number','roll_no');
	}
}
