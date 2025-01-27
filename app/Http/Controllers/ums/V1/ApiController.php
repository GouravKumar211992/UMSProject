<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use App\Helpers\ConstantHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Models\Language;
use Models\Menu;
use Models\User;

class ApiController extends Controller
{
    public function getMenus()
    {
        $account_user_id = \Auth::guard('api')->id();
        $user = User::where('account_user_id', $account_user_id)->first();

        $menu = Menu::whereHas('roles.users',function($q) use($user){
            $q->where('users.id',$user->id);
        })->with([
        'childs' => function($q)  use($user){
            $q->whereHas('roles.users',function($q) use($user){
                $q->where('users.id',$user->id);
            });
        }])
        ->whereParentId(null)->orderBy('sequence','ASC')->get();
        
        return [
            'data' => $menu
        ];
    }

	public function activateAccount(Request $request)
	{
		$user = User::where('remember_token', $request->token)
			->first();
		
		if ($user && $user->email) {
			User::whereId($user->id)
				->update([
					'is_email_verified' => 1,
					'status' => ConstantHelper::ACTIVE,
					'remember_token' => bcrypt(\Str::random(6))
				]);
			return array('message' => __('message.verified',['static' => __('static.account')]));
			
		} else {
			throw new ApiGenericException(__('message.token invalid'));
		}
    }
    
    public function getLanguage(){
        $language = Language::all();
        return [
            'data' => $language
        ];
    }
}
