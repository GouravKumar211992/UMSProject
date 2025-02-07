<?php
namespace App\models\ums;

<<<<<<< HEAD
use Laravel\Passport\HasApiTokens;
=======
// use Laravel\Passport\HasApiTokens;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EmailTemplate extends Authenticatable
{

<<<<<<< HEAD
	use HasApiTokens, Notifiable;
=======
	use  Notifiable;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    // use SoftDeletes;

	protected $primaryKey = 'id';
	protected $table = 'email_templates';  
   protected $fillable = ['alias','subject','message','status'];

   //---------------------------------
   	public function getEmailTemplateByAlias($alias)
	{
		$data = $this->where('alias',$alias)->first();
		return $data;
	}

	public function generateTags(): array
    {
        return [
            'Email Template'
        ];
    }
}