<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use App\Helpers\ConstantHelper;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\AssessmentSection;
use Illuminate\Validation\ValidationException;
use Lib\Validation\AssessmentSection as Validator;
use Models\Assessment;
use Models\User;

class AssessmentSectionController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function sectionByAssessment(Request $request, Assessment $assessment)
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

        $assessmentSection = AssessmentSection::with(['language','questions'])->where('training_assessment_id',$assessment->id);

        if(!empty($request->search))
        {
            $assessmentSection->where('name','like', '%' .$request->search. '%');
        }
        
        $assessmentSection = $assessmentSection->paginate($length);

		return [
            'data' => $assessmentSection
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
        $assessmentSection = new AssessmentSection;
        
        $assessmentSection->fill($request->all());
        $assessmentSection->save();        

        return array(
            'message' => __('message.created successfully',['static' => __('static.assessment_section')]),
            "data" => $assessmentSection
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AssessmentSection $assessmentSection)
    {
        return array(
            'data' => $assessmentSection
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssessmentSection $assessmentSection)
    {

        $request->request->add(['id' => $assessmentSection->id]);
        $request->request->add(['training_assessment_id' => $assessmentSection->training_assessment_id]);

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }
        
        $assessmentSection->fill($request->all());
        $assessmentSection->update();

        return array(
            'message' => __('message.updated successfully',['static' => __('static.assessment_section')]),
            "data" => $assessmentSection
        );
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssessmentSection $assessmentSection)
    { 
        $assessmentSection->delete();
        
        return array(
            'message' => __('message.deleted successfully',['static' => __('static.assessment_section')]),
            "data" => $assessmentSection
        );
    }
}
