<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use Auth;
use App\Helpers\ConstantHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\QuestionBank;
use Illuminate\Validation\ValidationException;
use Lib\Validation\QuestionBank as Validator;
use Models\User;

class QuestionBankController extends Controller
{


    /**
     * Display a listing of the resource.
     * @return Response
     */
     public function getRecords(Request $request)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        if($user) {
            $organizationId = $user->organization_id;
        }
        else if($request->organization_id){
            $organizationId = $request->organization_id;
        }
        else {
            throw new ApiGenericException('No Organization found');
        }

        $questionBank = QuestionBank::where('organization_id', $organizationId)->get();

        return [
            'data' => [ 'data' => $questionBank ]
        ];
    }

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

        $questionBank = QuestionBank::with('language')->where('organization_id',$organizationId)
                                        ->paginate($length);

		return [
            'data' => $questionBank
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

        $validator = (new Validator($request))->store();
        
        if($validator->fails()){
            throw new ValidationException($validator);
        }

        $questionBank = new QuestionBank;
        $questionBank->fill($request->all());
        $questionBank->organization_id = $organizationId;  
        
        $questionBank->save();

        return array(
            'message' => __('message.created successfully',['static' => __('static.question_bank')]),
            "data" => $questionBank
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionBank $questionBank)
    {
        return array(
            'data' => $questionBank
        );
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionBank $questionBank)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();
        
        if($user) {
            $organizationId = $user->organization_id;
        }
        else {
            throw new ApiGenericException('No Organization found');
        }

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }
        
        $questionBank->fill($request->all());
        $questionBank->update();

        return array(
            'message' => __('message.updated successfully',['static' => __('static.question_bank')]),
            "data" => $questionBank
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionBank $questionBank)
    { 
        $questionBank->delete();

        return array(
            'message' => __('message.deleted successfully',['static' => __('static.question_bank')]),
            "data" => $questionBank
        );
    }
}
