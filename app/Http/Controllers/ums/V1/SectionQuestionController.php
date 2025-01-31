<?php

namespace App\Http\Controllers\V1;

use Auth;
use Models\User;
use Illuminate\Http\Request;
use Models\Question;
use Models\Assessment;
use Illuminate\Routing\Controller;
use Models\AssessmentSection;
use Models\SectionQuestion;
use App\Exceptions\ApiGenericException;
use Illuminate\Validation\ValidationException;
use Lib\Validation\SectionQuestion as Validator;

class SectionQuestionController extends Controller
{

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

        if($validator->fails()) {
            throw new ValidationException($validator);
        }

        $section = AssessmentSection::find($request->training_assessment_section_id);
        $assessment = Assessment::find($section->training_assessment_id);
        $sectionQuestion = SectionQuestion::where('training_assessment_section_id', $request->training_assessment_section_id)
                                ->get()->toArray();

        $totalMappedQuestions = count($sectionQuestion) + count($request->training_question_id);

        if($assessment && $totalMappedQuestions > $assessment->total_questions) {
            throw new ApiGenericException(__('message.question_mapping_limit_reached'));
        }

        foreach ($request->training_question_id as $key => $value) {

            $section->sectionQuestions()->updateOrCreate(
                        ['training_question_id' => $value],
                        [
                            'sequence' => !empty($request->sequence[$key])?$request->sequence[$key]:0,
                            'training_assessment_section_id' => $request->training_assessment_section_id
                        ]
                    );
        }

        return array(
            'message' => __('message.mapped_successfully',['static' => __('static.assessment_section_question')]),
            "data" => $section
        );
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssessmentSection $section, Question $question)
    {

        $sectionQuestion = SectionQuestion::where('training_assessment_section_id',$section->id)
                                ->where('training_question_id',$question->id)->delete();

        return array(
            'message' => __('message.deleted successfully',['static' => __('static.assessment_section_question')]),
            "data" => $sectionQuestion
        );
    }
}
