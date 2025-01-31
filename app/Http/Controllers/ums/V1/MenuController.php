<?php

namespace App\Http\Controllers\V1;

use Auth;
use Models\User;
use Models\Menu;
use Illuminate\Http\Request;
use App\Helpers\ConstantHelper;
use App\Http\Controllers\Controller;
use Lib\Validation\Menu as Validator;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $length = $request->length?:ConstantHelper::PAGE_LIMIT_20;

        $menus = Menu::with('children')->whereParentId(null);

        if(!empty($request->search))
        {
            $menus->where('name','like', '%' .$request->search. '%');
        }

        $menus = $menus->paginate($length);

		return [
            'data' => $menus
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
        $user = User::where('account_user_id', $account_user_id)->first();

        $request->request->add(['created_by' => $user->id]);

        $validator = (new Validator($request))->store();

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        $menus = new Menu;
        $menus->fill($request->all());
        $menus->save();

        if($request->hasFile('avatar'))
        {
            $menus->addMediaFromRequest('avatar')->toMediaCollection('icon');
        }

        return array(
            'message' => __('message.created successfully',['static' => __('static.menu')]),
            "data" => $menus
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu = Menu::find($id);

        return array(
            'data' => $menu
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $menuId)
    {
        $menu = Menu::find($menuId);

        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id', $account_user_id)->first();

        $request->request->add(['updated_by' => $user->id]);
        $request->request->add(['id' => $menu->id]);

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        $menu->fill($request->all());
        $menu->update();

        if($request->hasFile('avatar'))
        {
            $menu->addMediaFromRequest('avatar')->toMediaCollection('icon');
        }

        return array(
            'message' => __('message.updated successfully',['static' => __('static.menu')]),
            "data" => $menu
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::find($id);

        $menu->delete();

        return array(
            'message' => __('message.deleted successfully',['static' => __('static.menu')]),
        );
    }
}
