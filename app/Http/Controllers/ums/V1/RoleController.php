<?php

namespace App\Http\Controllers\V1;

use Models\Role;
use Models\User;
use Models\Category;
use App\Exceptions\ApiGenericException;
use App\Helpers\ConstantHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Lib\Validation\Role as Validator;

class RoleController extends Controller
{

    public function __construct(Request $request)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = $request->length?:ConstantHelper::PAGE_LIMIT_20;

        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        $roles = Role::with('menus')->where('organization_id','=',$user->organization_id);

        if($request->search) {
            $roles->where('name','like', '%' .$request->search. '%');
        }

        $roles = $roles->paginate($length);
        return [
            'data' => $roles,
        ];
    }

    public function selectList(Request $request)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

		$roles = Role::select('name as text', 'id')->where('organization_id','=',$user->organization_id);
        if($request->search){
            $roles->where('name','like', '%' .$request->search. '%');
        }

        $roles = $roles->paginate();
        
        return [
            'data' => $roles
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        $validator = (new Validator($request))->store($user);
        
        if($validator->fails()){
            throw new ValidationException($validator);
        }
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();
        $role = new Role();
        $role->name = $request->name;
        $role->alias = str_replace(" ","_",strtolower($request->name));
        $role->organization_id = $user->organization_id;
        $role->save();

        $role->menus()->attach($request->menus);
        
        return [
            'message' => __('message.created successfully',['static' => __('static.role')]),
            'data' => $role,
        ];
    }

    public function addMenus(Request $request,$id)
    {
        $this->validate($request,[
            'menus' => ['required','array'],
        ]);
        $role = Role::find($id);
        $role->menus()->detach();
        $role->menus()->attach($request->menus);
        return [
            'message' => __('message.added successfully',['static' => __('static.menus')]),
            'data' => $role
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        $data = Role::with('menus')->where('organization_id','=',$user->organization_id)->find($id);

        return [
            'data' => $data
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();        
        $request->request->add(['id' => $id]);
        $validator = (new Validator($request))->update($user);

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        $role = Role::find($id);
        $role->update([
            'name' => $request->name,
            'alias' => str_replace(" ","_",strtolower($request->name)),
        ]);

        $role->menus()->sync($request->menus);

        return [
            'message' => __('message.updated successfully',['static' => __('static.role')]),
            'data' => $role,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
