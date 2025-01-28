<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use Auth;
use App\Helpers\ConstantHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\AssessmentType;
use Illuminate\Validation\ValidationException;
use Lib\Validation\AssessmentType as Validator;
use Models\User;

class AssessmentTypeController extends Controller
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

        $assessment_type = AssessmentType::where('organization_id',$organizationId);

        if(!empty($request->search))
        {
            $assessment_type->where('name','like', '%' .$request->search. '%');
        }
        
        $assessment_type = $assessment_type->paginate($length);

		return [
            'data' => $assessment_type
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
        
        $assessmentType = new AssessmentType; 
        $assessmentType->fill($request->all());
        $assessmentType->organization_id = $request->organization_id;
        $assessmentType->save();

        return array(
            'message' => __('message.created successfully',['static' => __('static.assessment_type')]),
            "data" => $assessmentType
        );
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AssessmentType $assessmentType)
    {
        return array(
            'data' => $assessmentType
        );
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssessmentType $assessmentType)
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
        $request->request->add(['id' => $assessmentType->id]);

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }
        
        $assessmentType->fill($request->all());
        $assessmentType->update();

        return array(
            'message' => __('message.updated successfully',['static' => __('static.assessment_type')]),
            "data" => $assessmentType
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssessmentType $assessment_type)
    { 
        $assessment_type->delete();

        return array(
            'message' => __('message.deleted successfully',['static' => __('static.assessment_type')]),
            "data" => $assessment_type
        );
    }
}
