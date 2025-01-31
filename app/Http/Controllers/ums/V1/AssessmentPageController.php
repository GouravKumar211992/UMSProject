<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use App\Helpers\ConstantHelper;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\AssessmentPage;
use Illuminate\Validation\ValidationException;
use Lib\Validation\AssessmentPage as Validator;
use Models\Assessment;
use Models\User;

class AssessmentPageController extends Controller
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

        $assessmentPage = AssessmentPage::whereHas('assessment.type', function($q) use($organizationId) {
                                $q->where('organization_id',$organizationId);
                            })->with(['language','assessment']);

        if(!empty($request->search))
        {
            $assessmentPage->where('name','like', '%' .$request->search. '%');
        }
        
        $assessmentPage = $assessmentPage->paginate($length);

		return [
            'data' => $assessmentPage
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
        $assessmentPage = new AssessmentPage;
        
        $assessmentPage->fill($request->all());
        $assessmentPage->save();        

        return array(
            'message' => __('message.created successfully',['static' => __('static.assessment_page')]),
            "data" => $assessmentPage
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AssessmentPage $assessmentPage)
    {
        $assessmentPage = $assessmentPage->load(['assessment']);
        return array(
            'data' => $assessmentPage
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssessmentPage $assessmentPage)
    {

        $request->request->add(['id' => $assessmentPage->id]);

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }
        
        $assessmentPage->fill($request->all());
        $assessmentPage->update();

        return array(
            'message' => __('message.updated successfully',['static' => __('static.assessment_page')]),
            "data" => $assessmentPage
        );
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssessmentPage $assessmentPage)
    { 
        $assessmentPage->delete();
        
        return array(
            'message' => __('message.deleted successfully',['static' => __('static.assessment_page')]),
            "data" => $assessmentPage
        );
    }
}
