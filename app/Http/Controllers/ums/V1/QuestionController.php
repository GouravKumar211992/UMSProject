<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use Auth;
use DB;
use App\Helpers\ConstantHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\Question;
use Models\QuestionOption;
use Models\QuestionBank;
use Illuminate\Validation\ValidationException;
use Lib\Validation\Question as Validator;
use Models\User;

class QuestionController extends Controller
{
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

        if($user) {
            $organizationId = $user->organization_id;
        }
        else {
            throw new ApiGenericException('No Organization found');
        }

        $question = Question::whereHas('questionBank', function($q) use($organizationId) {
                                $q->where('organization_id',$organizationId);
                            })->with(['questionBank','options']) ->paginate($length);

		return [
            'data' => $question
        ];
    }

    public function store(Request $request)
    {
        
        $user = Auth::guard('api')->user();
        
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
        $requestQuestion = $request->all();

        $requestQuestion['content'] = array_values($requestQuestion['content']);
        
        $question = new Question;
        $question->fill($requestQuestion);
        $question->save(); 
        

        if(!empty($request->options)) {
            foreach ($request->options as $value) {
                $question->options()->create($value);
            }
        }

        return array(
            'message' => __('message.created successfully',['static' => __('static.question')]),
            "data" => $question
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        $question->load(['questionBank', 'options']); 
        return array(
            'data' => $question
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        
        $user = Auth::guard('api')->user();
        
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

        $requestQuestion = $request->all();

        $requestQuestion['content'] = array_values($requestQuestion['content']);

        DB::beginTransaction();

        $question->fill($requestQuestion);
        $questionUpdate = $question->update(); 


        $stored_options = QuestionOption::where('training_question_id',$question->id)->pluck('id')->toArray();
        $request_options = array_column($request->options,'id');
    
        $result=array_diff($stored_options,$request_options);

        if(count($result)>0) {

            QuestionOption::whereIn('id',$result)->delete();
        }
        
        foreach ($request->options as $key => $value) {
            $value['is_correct'] = !empty($value['is_correct'])?$value['is_correct']:0;
            
            if(empty($value['id'])) {
                $optionUpdate = $question->options()->create($value);

            }
            else {
                $optionUpdate = $question->options()->updateOrCreate(['id' => $value['id']] ,$value);
            }
        }   

        if( !$optionUpdate || !$questionUpdate )
        {
            DB::rollBack();
            throw new ApiGenericException('Something went wrong ,please try again!');
        } else {

            DB::commit();
        }

        return array(
            'message' => __('message.updated successfully',['static' => __('static.question')]),
            "data" => $question
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    { 
        $question->delete();

        return array(
            'message' => __('message.deleted successfully',['static' => __('static.question')]),
            "data" => $question
        );
    }

     /**
     * Display a listing of the specified Question Bank.
     * @return Response
     */
    public function questionsByQuestionBank($id)
    {
        $question = Question::where('training_question_bank_id',$id)->get();

        return [
            'data' => $question
        ];
    }
}
