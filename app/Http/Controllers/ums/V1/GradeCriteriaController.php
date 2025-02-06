<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use App\Helpers\ConstantHelper;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\GradeCriteria;
use Illuminate\Validation\ValidationException;
use Lib\Validation\GradeCriteria as Validator;
use Models\User;

class GradeCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $length = $request->length?:ConstantHelper::PAGE_LIMIT_20;
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        if($user) {
            $organizationId = $user->organization_id;
        }
        else {
            throw new ApiGenericException('No Organization found');
        }

        $gradeCriteria = GradeCriteria::where('organization_id', $organizationId);

        if(!empty($request->search))
        {
            $gradeCriteria->where('name','like', '%' .$request->search. '%');
        }

        $gradeCriteria = $gradeCriteria->paginate($length);

		return [
            'data' => $gradeCriteria
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
        
        $gradeCriteria = new GradeCriteria; 
        $gradeCriteria->fill($request->all());
        $gradeCriteria->save();

        return array(
            'message' => __('message.created successfully',['static' => __('static.grade_criteria')]),
            "data" => $gradeCriteria
        );
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(GradeCriteria $gradeCriteria)
    {
        return array(
            'data' => $gradeCriteria
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GradeCriteria $gradeCriteria)
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
        $request->request->add(['id' => $gradeCriteria->id]);

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }
        

        $gradeCriteria->fill($request->all());
        $gradeCriteria->update();
        
        if($request->hasFile('avatar'))
        {
            $gradeCriteria->addMediaFromRequest('avatar')->toMediaCollection('thumbnail');
        }

        return array(
            'message' => __('message.updated successfully',['static' => __('static.grade_criteria')]),
            "data" => $gradeCriteria
        );
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GradeCriteria $gradeCriteria)
    { 
        $gradeCriteria->delete();
        
        return array(
            'message' => __('message.deleted successfully',['static' => __('static.grade_criteria')]),
            "data" => $gradeCriteria
        );
    }
}
