<?php

namespace App\models\ums;
// use Auth;
// use DB;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationAddress extends Model 
{
	use SoftDeletes;
	protected $table = 'application_addresses';
	protected $fillable = [
		'address',
		'address_type',
		'district',
		'police_station',
		'nearest_railway_station',
		'country',
		'state_union_territory',
		'pin_code',
		'mobile_no',
		'landline',
		'application_id'
	];

	public function application() {
		return $this->belongsTo(Application::class, 'application_id');
	}

		public function setAddressAttribute( $value ) {
		  $this->attributes['address'] = strtoupper($value);
		}
		public function setPoliceStationAttribute( $value ) {
		  $this->attributes['police_station'] = strtoupper($value);
		}
		public function setNearestRailwayStationAttribute( $value ) {
		  $this->attributes['nearest_railway_station'] = strtoupper($value);
		}


}


