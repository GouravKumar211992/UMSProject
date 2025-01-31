<?php

namespace App\Models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSession extends Model
{
	use SoftDeletes;

	public function lastSession(){
		$lastSession = AcademicSession::where('id', '<', $this->id)->orderBy('id', 'desc')->first();
		if($lastSession){
			return $lastSession->academic_session;
		}else{
			return null;
		}
	}
}
