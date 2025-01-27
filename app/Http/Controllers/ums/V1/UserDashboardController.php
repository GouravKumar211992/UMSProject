<?php

namespace App\Http\Controllers\V1;

use Auth;
use Models\User;
use Models\Course;
use Illuminate\Http\Request;
use Models\Assessment;
use Models\UserAssessment;
use App\Http\Controllers\Controller;

class UserDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */

    public function getUserDashboard(Request $request)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::with('roles')->where('account_user_id', $account_user_id)->first();

        $data = array(
            'total_courses' => $this->totalCourses($user),
            'total_assessments' => $this->totalAssessments($user),
            'best_performance_result' => $this->userBestPerformance($user),
            'top_rankers' => $this->topRankers($user),
            'top_assessment_results' => $this->assessmentResults($user),
        );

		return [
            'data' => $data
        ];
    }

    public function topRankers($user)
    {
        $topRankers = UserAssessment::with(['user', 'assessment'])->whereHas('user', function($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        })->orderBy('marks', 'DESC')->take(5)->get();

        return $topRankers;
    }

    public function assessmentResults($user)
    {

        $topAssessments = UserAssessment::with('assessment')->whereHas('user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orderBy('marks', 'DESC')->take(5)->get();

        return $topAssessments;
    }

    public function userBestPerformance($user)
    {
        $topAssessments = UserAssessment::with('assessment')->whereHas('user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->orderBy('marks', 'DESC')->first();

        return $topAssessments;
    }

    public function totalAssessments($user)
    {
        $totalAssessments = Assessment::whereHas('type', function($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        })->count();

        $attemptedAssessments = UserAssessment::whereHas('user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->groupBy('training_assessment_id')->get();


        return [
            'attempted' => $attemptedAssessments->count(), 'total' => $totalAssessments
        ];
    }


    public function totalCourses($user)
    {
        $totalCourses = Course::whereHas('category', function($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        })->count();

        $attemptedCourses = UserAssessment::whereHas('user', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->groupBy('training_assessment_id')->pluck('training_assessment_id');

        $totalAttemptedCourses = Course::whereHas('assessments', function($q) use ($attemptedCourses) {
            $q->whereIn('id', $attemptedCourses);
        })->count();

        return [
            'attempted' => $totalAttemptedCourses, 'total' => $totalCourses
        ];

    }

    public function assessmentAnalysis($id)
    {
        $trainingUserAssessment = UserAssessment::find($id);
        $trainingUserAssessment->load('assessment');


        $data = array(
            'assessment_analysis' => $trainingUserAssessment,
            'top_rankers' => $this->AssessmentTopRankers($trainingUserAssessment->training_assessment_id),
        );

        return [
            'data' => $data
        ];
    }

    public function AssessmentTopRankers($training_assessment_id)
    {
        $topRankers = UserAssessment::with('user')->where('training_assessment_id', $training_assessment_id)
                                            ->orderBy('marks', 'DESC')->take(5)->get();

        return $topRankers;
    }
}
