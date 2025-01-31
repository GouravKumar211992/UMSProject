<?php

namespace App\models\ums;
// use Auth;
// use DB;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
// use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationPayment extends Model implements HasMedia
{
	use SoftDeletes,InteractsWithMedia;

	protected $fillable = [
		'order_id',
		'transaction_id',
		'paid_amount',
		'bank_name',
		'bank_ifsc_code',
		'challan',
		'payment_mode',
		'txn_date',
		'txn_status',
		'application_id'
	];

	public function application() {
		return $this->belongsTo(Application::class, 'application_id');
	}

	public function getChallanAttribute()
    {

        if ($this->getMedia('challan')->isEmpty()) {
            return false;
        } else {
            return $this->getMedia('challan')->last()->getFullUrl();
        }
    }
}


