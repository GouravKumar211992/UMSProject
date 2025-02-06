<?php

namespace App\Http\Controllers\V1;

use Auth;
use Models\User;
use Illuminate\Http\Request;
use Models\Assessment;
use App\Helpers\ConstantHelper;
use Models\UserAssessment;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Lib\Validation\UserAssessment as Validator;
use Models\UserAssessmentQuestionAttempt;
use Models\UserAssessmentQuestionSession;
use App\Helpers\ConstantHelper as HelpersConstantHelper;

class UserAssessmentController extends Controller
{

    public function index() {

        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id', $account_user_id)->first();

        $assessments = Assessment::whereHas('userAssessments', function($q) use ($user){
                $q->where('user_id', $user->id);
            })
            ->withCount(['pages', 'sections', 'questions'])
            ->with(['course' => function($q) {
                $q->select(['id','name', 'training_category_id'])
                    ->with('category');
            }])
            ->with(['userLastAssessment' => function($q) use ($user){
                $q->where('user_id', $user->id)
                  ->orderBy('id', 'DESC');

            }])
            ->paginate(HelpersConstantHelper::PAGE_LIMIT);

        return [
            'data' => $assessments
        ];
    }

    public function storeQuestionAttempts(Request $request, Assessment $assessment)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        $validator = (new Validator($request))->store();

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        $userAssessment = new UserAssessment();
        $userAssessment->user_id = $user->id;
        $userAssessment->training_assessment_id = $assessment->id;
        $userAssessment->save();

        foreach($request->questionAttempts as $questionAttempt) {
            $assessmentQuestionAttempt = new UserAssessmentQuestionAttempt();
            $assessmentQuestionAttempt->training_user_assessment_id = $userAssessment->id;
            $assessmentQuestionAttempt->training_assessment_section_id = $questionAttempt['section_id'];
            $assessmentQuestionAttempt->training_question_id = $questionAttempt['question_id'];
            $assessmentQuestionAttempt->answer = $questionAttempt['answer'];
            $assessmentQuestionAttempt->user_id = $user->id;
            $assessmentQuestionAttempt->save();

            foreach($questionAttempt['sessions'] as $session) {

                $assessmentQuestionSession = new UserAssessmentQuestionSession();
                $assessmentQuestionSession->training_assessment_id = $assessment->id;
                $assessmentQuestionSession->training_user_assessment_id = $userAssessment->id;
                $assessmentQuestionSession->training_assessment_section_id = $questionAttempt['section_id'];
                $assessmentQuestionSession->training_question_id = $questionAttempt['question_id'];
                $assessmentQuestionSession->user_id = $user->id;
                $assessmentQuestionSession->created_at = $session['started_at'];
                $assessmentQuestionSession->closed_at = $session['closed_at'];
                $assessmentQuestionSession->save();
            }
        }
        $userAssessment->status = ConstantHelper::PENDING;
        $userAssessment->update();

        $userAssessment = UserAssessment::with('assessment')->find($userAssessment->id);

        return [
            'message' => __('message.user_assessment_submitted'),
            'data' =>  $userAssessment
        ];
    }

    public function assessmentResults(Assessment $assessment) {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id', $account_user_id)->first();

        $assessments = UserAssessment::where('training_assessment_id', $assessment->id)
            ->where('user_id', $user->id)
            ->orderBy('id', 'DESC')
            ->paginate(HelpersConstantHelper::PAGE_LIMIT);

        return [
            'data' => $assessments
        ];
    }

    public function viewUserAssessmentSolutions($userAssessmentId) {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id', $account_user_id)->first();

        $assessments = UserAssessmentQuestionAttempt::with('question')
            ->where('training_user_assessment_id', $userAssessmentId)
            ->get();

        return [
            'data' => $assessments
        ];
    }
}
