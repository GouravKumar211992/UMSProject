<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use Auth;
use App\Helpers\ConstantHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\Assessment;
use Models\AssessmentPage;
use Models\AssessmentQuestion;
use Models\AssessmentSection;
use Models\AssessmentType;
use Models\Course;
use Models\Question;
use Illuminate\Validation\ValidationException;
use Lib\Validation\Assessment as Validator;
use Models\User;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getRecords(Assessment $assessment)
    {

        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();
        $assessment->loadCount(['pages', 'sections', 'questions']);
        $assessment->load(['course' => function($q) {
            $q->select(['id','name', 'training_category_id'])
                ->with('category');
            },
            'userLastAssessment' => function($q) use ($user){
                $q->where('user_id', $user->id)
                    ->orderBy('id', 'DESC');
            }]);

        return [
            'data' => $assessment
        ];
    }

    // display all assessment types
    public function getTypes(Request $request) {
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

        $assessmentTypes = AssessmentType::where('organization_id', $organizationId)
            ->get();

        return [
            'data' => [
                'data' => $assessmentTypes
            ]
        ];
    }

    // display all assessment
    public function selectList(Request $request) {
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

        $assessment = Assessment::whereHas('type', function($q) use($organizationId) {
                                $q->where('organization_id',$organizationId);
                            });

        if($request->search) {
            $assessment->where('name','like', '%' .$request->search. '%');
        }

        $assessment = $assessment->select('training_assessments.id','training_assessments.name as text')->paginate();

        return ['data' => $assessment];
    }

    public function courseAssessmentsWithType(Request $request, Course $course) {

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

        $assessments = AssessmentType::whereHas('assessments', function($q) use ($course) {
                $q->where('training_course_id', $course->id);
            })
            ->with(['assessments' => function($q) use($course) {
                $q->withCount(['pages', 'sections', 'questions'])
                    ->where('training_course_id', $course->id);
            }])
            ->where('organization_id', $organizationId)
            ->get();

        $course->load('category');

        return [
            'data' => [
                'course' => $course,
                'data' => $assessments
            ]
        ];
    }

    public function getAssessmentsByType(Request $request, AssessmentType $assessmentType) {

        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        $assessments = Assessment::where('training_assessment_type_id', $assessmentType->id)
            ->withCount(['pages', 'sections', 'questions'])
            ->with(['course' => function($q) {
                $q->select(['id','name', 'training_category_id'])
                    ->with('category');
            }])
            ->with(['userLastAssessment' => function($q) use ($user){
                $q->where('user_id', $user->id)
                    ->orderBy('id', 'DESC');
            }])
            ->paginate($request->limit ?: ConstantHelper::PAGE_LIMIT);

        return [
            'data' => $assessments
        ];
    }

    public function courseAssessments(Request $request, Course $course) {

        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        $assessments = Assessment::where('training_course_id', $course->id)
                                            ->withCount(['pages', 'sections', 'questions'])
                                            ->paginate($request->limit ?: ConstantHelper::PAGE_LIMIT);


        if($user) {

            $assessments->load(['userLastAssessment' => function($q) use ($user){
                $q->where('user_id', $user->id)
                  ->orderBy('id', 'DESC');

            }]);
        }

        return [
            'data' => $assessments
        ];
    }

    public function pages(Assessment $assessment) {

        $pages = AssessmentPage::where("training_assessment_id", $assessment->id)
            ->orderBy('sequence')
            ->get();

        $assessment->load(['course' => function($q) {
            $q->select(['id','name', 'training_category_id'])
                ->with('category');
            }]);

		return [
            'data' => [
                'assessment' => $assessment,
                'data' => $pages
            ]
        ];
    }

    public function sections(Assessment $assessment) {

        $sections = AssessmentSection::where("training_assessment_id", $assessment->id)
            ->orderBy('sequence')
            ->with('questions')
            ->get();

        $assessment->load(['course' => function($q) {
            $q->select(['id','name', 'training_category_id'])
                ->with('category');
            }]);

		return [
            'data' => [
                'assessment' => $assessment,
                'data' => $sections
            ]
        ];
    }

    public function questions(Assessment $assessment) {

        $questions = AssessmentQuestion::whereHas("section", function($q) use($assessment) {
                $q->where('training_assessment_id', $assessment->id);
            })
            ->select('training_assessment_section_id', 'training_question_id', 'sequence')
            ->with('question')
            ->orderBy('sequence')
            ->get();

        $assessment->load(['course' => function($course) {
            $course->select(['id','name', 'training_category_id'])
                ->with('category');
            }]);

		return [
            'data' => [
                'assessment' => $assessment,
                'data' => $questions
            ]
        ];
    }

    public function sectionQuestions(AssessmentSection $section) {

        $sections = Question::whereHas('sections', function($q) use ($section){
                                        $q->where('training_assessment_sections.id', $section->id);
                                    })
                                    ->with('options')
                                    ->get();
        $section->load(['assessment' => function($assessment){
            $assessment->with(['course' => function($course) {
                $course->select(['id','name', 'training_category_id'])
                    ->with('category');
                }]);
        }]);

		return [
            'data' => [
                'section' => $section,
                'data' => $sections
            ]
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

        $assessment = Assessment::whereHas('type', function($q) use($organizationId) {
            $q->where('organization_id',$organizationId);
        })->with(['type','course']);

        if(!empty($request->search))
        {
            $assessment->where('name','like', '%' .$request->search. '%');
        }

        $assessment = $assessment->paginate($length);

		return [
            'data' => $assessment
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
        $assessment = new Assessment;

        $assessment->fill($request->all());
        $assessment->save();

        if($request->hasFile('avatar'))
        {
            $assessment->addMediaFromRequest('avatar')->toMediaCollection('thumbnail');
        }

        return array(
            'message' => __('message.created successfully',['static' => __('static.assessment')]),
            "data" => $assessment
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Assessment $assessment)
    {
        return array(
            'data' => $assessment
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Assessment $assessment)
    {

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        $assessment->fill($request->all());
        $assessment->update();

        if($request->hasFile('avatar'))
        {
            $assessment->addMediaFromRequest('avatar')->toMediaCollection('thumbnail');
        }

        return array(
            'message' => __('message.updated successfully',['static' => __('static.assessment')]),
            "data" => $assessment
        );
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assessment $assessment)
    {
        $assessment->delete();

        return array(
            'message' => __('message.deleted successfully',['static' => __('static.assessment')]),
            "data" => $assessment
        );
    }
}
