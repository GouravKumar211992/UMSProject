<?php

namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentType extends Model
{
	use SoftDeletes;

	protected $hidden = [
		'created_by', 'updated_by', 'deleted_at'
	];

	protected $fillable = [
		'name', 'description',
	];

	public function assessments() {
		return $this->hasMany(Assessment::class);
	}
}
