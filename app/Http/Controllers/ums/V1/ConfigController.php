<?php

namespace App\Http\Controllers\V1;

use App\Helpers\ConstantHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Models\AccountOrganization;
use Models\AccountUser;
use Models\Menu;
use Models\Organization;
use Models\Role;
use Models\User;

class ConfigController extends Controller
{
    public function init()
    {
        $account_user_id = \Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();
        if(!$user){
            $organization = $this->initOrganization();
            $user = $this->initUser($organization);
            $this->attachRole($user);
        }else{
            $user->update(['status' => 'active']);
        }

        return [
            'data' => $user
        ];
    }


    private function initOrganization(){
        $organization_id = \Auth::user()->organization_id;
        $accountOrganization = AccountOrganization::find($organization_id)->toArray();
        $organization = new Organization();
        $organization->account_organization_id = $accountOrganization['id'];
        $organization->fill($accountOrganization);
        $organization->save();

        $this->initRole($organization);
        return $organization;
    }

    private function initRole($organization)
    {
        $roles = ConstantHelper::ORGANIZATION_DEFAULT_ROLES;

        foreach($roles as $roleKey => $roleValue){
            $role = $organization->roles()->create([
                'name' => $roleValue,
                'alias' => $roleKey
            ]);

            $this->attachMenus($role);
        }

        return true;
    }

    private function initUser($organization)
    {
        $accountUser = AccountUser::find(\Auth::id())->toArray();
        $user = new User();
        $user->account_user_id = $accountUser['id'];
        $user->organization_id = $organization->id;
        $user->is_primary_user = true;
        $user->remember_token = bcrypt(\Str::random(6));
        $user->fill($accountUser);
        $user->status = ConstantHelper::ACTIVE;
        $user->save();

        return $user;
    }

    private function attachRole($user)
    {
        $role = Role::where([
            'alias' => ConstantHelper::ADMIN_ROLE_ALIAS,
            'organization_id' => $user->organization_id
        ])->first();



        $user->roles()->attach($role);
        return true;
    }

    public function attachMenus($role)
    {
        if($role->alias == ConstantHelper::ADMIN_ROLE_ALIAS){
            $menus = Menu::where('for_frontend', true)->get();
        }else{
            $menus = Menu::whereIn('alias', ConstantHelper::USER_DEFAULT_MENUS)->get();
        }

        $role->menus()->attach($menus);
    }
}
