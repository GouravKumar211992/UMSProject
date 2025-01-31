<?php
namespace App\models\ums;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = ['account_organization_id', 'name', 'alias', 'email', 'year_type', 'no_of_employee'];

    public function roles()
	{
		return $this->hasMany(Role::class);
	}

}

