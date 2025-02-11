<?php
namespace App\models\ums;

// use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class EmailTemplate extends Authenticatable
{

	use  Notifiable;
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