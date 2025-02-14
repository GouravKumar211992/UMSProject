<?php

namespace App\Models\ums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmitCard extends Model
{
	use SoftDeletes;

	public function center() {
        return $this->hasOne(ExamCenter::class,'center_code','center_code');
    }

	public function examfee() {
        return $this->hasOne(ExamFee::class,'id','exam_fees_id');
    }
	public function examform() {
        return $this->hasOne(Examform::class,'exam_fee_id','exam_fees_id');
    }
	public function scribeDetails() {
        return $this->hasOne(ScribeDetails::class,'admitcard_id','id');
    }

}
