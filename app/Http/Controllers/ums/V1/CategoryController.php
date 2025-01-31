<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use App\Helpers\ConstantHelper;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\Category;
use Illuminate\Validation\ValidationException;
use Lib\Validation\Category as Validator;
use Models\User;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getRecords(Request $request)
    {
  
        $categories = Category::all();

		return [
            'data' => [ 'data' => $categories ]
        ];
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $length = $request->length?:ConstantHelper::PAGE_LIMIT_20;

        $categories = new Category;
        
        if(!empty($request->search))
        {
            $categories->where('name','like', '%' .$request->search. '%');
        }

        $categories = $categories->paginate($length);

		return [
            'data' => $categories
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
        
        if($user) {
            $organizationId = $user->organization_id;
        }
        else {
            throw new ApiGenericException('No Organization found');
        }

        $request->request->add(['organization_id' => $organizationId]);
        
        $validator = (new Validator($request))->store();

        if($validator->fails()){
            throw new ValidationException($validator);
        }
        
        $category = new Category; 
        $category->name = $request->name;
        $category->organization_id = $organizationId;
        $category->save();

        if($request->hasFile('avatar'))
        {
            $category->addMediaFromRequest('avatar')->toMediaCollection('thumbnail');
        }

        return array(
            'message' => __('message.created successfully',['static' => __('static.category')]),
            "data" => $category
        );
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return array(
            'data' => $category
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();
        
        if($user) {
            $organizationId = $user->organization_id;
        }
        else {
            throw new ApiGenericException('No Organization found');
        }

        $request->request->add(['organization_id' => $organizationId]);
        $request->request->add(['id' => $category->id]);

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }
        

        $category->name = $request->name;
        $category->update();
        
        if($request->hasFile('avatar'))
        {
            $category->addMediaFromRequest('avatar')->toMediaCollection('thumbnail');
        }

        return array(
            'message' => __('message.updated successfully',['static' => __('static.category')]),
            "data" => $category
        );
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    { 
        $category->delete();
        
        return array(
            'message' => __('message.deleted successfully',['static' => __('static.category')]),
            "data" => $category
        );
    }
}
