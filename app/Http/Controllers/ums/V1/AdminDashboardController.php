<?php

namespace App\Http\Controllers\V1;

use Auth;
use Models\User;
use Models\Course;
use Illuminate\Http\Request;
use Models\Assessment;
use Models\UserAssessment;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdminDashboard(Request $request)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::with('roles')->where('account_user_id', $account_user_id)->first();

        $data = array(
            'total_courses' => $this->totalCourses($user),
            'total_assessments' => $this->totalAssessments($user),
            'total_students' => $this->totalStudents($user),
            'top_rankers' => $this->topRankers($user),
            'top_assessments' => $this->topAssessments($user),
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

    public function topAssessments($user)
    {

        $topAssessments = UserAssessment::with('assessment')->whereHas('user', function($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        })->orderBy('marks', 'DESC')->groupBy('training_assessment_id')->take(5)->get();

        return $topAssessments;
    }

    public function totalStudents($user)
    {
        $totalStudents = User::where('organization_id', $user->organization_id)->count();

        $attemptedUsers = UserAssessment::whereHas('user', function($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        })->groupBy('user_id')->get();

        return [
            'attempted' => $attemptedUsers->count(), 'total' => $totalStudents
        ];
    }

    public function totalAssessments($user)
    {
        $totalAssessments = Assessment::whereHas('type', function($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
        })->count();

        $attemptedAssessments = UserAssessment::whereHas('user', function($q) use ($user) {
            $q->where('organization_id', $user->organization_id);
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
            $q->where('organization_id', $user->organization_id);
        })->groupBy('training_assessment_id')->pluck('training_assessment_id');

        $totalAttemptedCourses = Course::whereHas('assessments', function($q) use ($attemptedCourses) {
            $q->whereIn('id', $attemptedCourses);
        })->count();

        return [
            'attempted' => $totalAttemptedCourses, 'total' => $totalCourses
        ];

    }
}
