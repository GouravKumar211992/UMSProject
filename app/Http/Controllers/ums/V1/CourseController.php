<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\ApiGenericException;
use Auth;
use App\Helpers\ConstantHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Models\Category;
use Models\Course;
use Illuminate\Validation\ValidationException;
use Lib\Validation\Course as Validator;

use Models\Role;
use Models\User;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function getRecords()
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();
        $user->load(['courseMappings', 'roles']);
        $mappedAssessmentIds = [];
        $rolesAssessmentIds = [];

        $mappedAssessmentIds = $user->courseMappings->pluck('training_course_id')->toArray();
        

        $roles = Role::whereIn('id', $user->roles->pluck('id'))
                        ->with('courseMappings')
                        ->get()->toArray();

        $rolesAssessmentIds = array_map(function ($rolesArray) {
            return array_column($rolesArray['course_mappings'], 'training_course_id');
        }, $roles);

        if(!empty($rolesAssessmentIds)){
            $mappedAssessmentIds = array_unique(array_merge($mappedAssessmentIds, $rolesAssessmentIds[0]));
        }

        $courses = Course::whereHas('category', function($q) use ($user) {
                $q->where('organization_id', $user->organization_id);
        });

        if(count($mappedAssessmentIds)>0) {
            $courses->whereIn('training_courses.id',$mappedAssessmentIds);
        }

        $courses = $courses->withCount(['assessments'])
            ->paginate(ConstantHelper::PAGE_LIMIT);

		return [
            'data' => $courses
        ];
    }

    public function getCourses()
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();

        $courses = Course::whereHas('category', function($q) use ($user) {
                $q->where('organization_id', $user->organization_id);   
            })->get();

       return [
            'data' => [
                'data' => $courses 
            ]
        ];
    }

    public function categoryCourses(Request $request, Category $category)
    {
       
        $categories = Course::where('training_category_id', $category->id)
            ->get();

		return [
            'data' => [
                'category' => $category,
                'data' => $categories 
            ]
        ];
    }

    public function addMedia(Request $request, Course $course)
	{
		$course->addMediaFromRequest('media')
				->toMediaCollection($request->type);

		return [
			'message' => __('message.added successfully', ['static' => __('field.image')]),
			'data' => $course
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

        $course = Course::whereHas('category', function($q) use($organizationId) {
                                $q->where('organization_id',$organizationId);
                            })->with('category');

        if(!empty($request->search))
        {
            $course->where('name','like', '%' .$request->search. '%');
        }

        $course = $course->paginate($length);

		return [
            'data' => $course
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
        
        $course = new Course; 
        $course->fill($request->all());
        $course->save();

        if($request->hasFile('avatar'))
        {
            $course->addMediaFromRequest('avatar')->toMediaCollection('thumbnail');
        }

        return array(
            'message' => __('message.created successfully',['static' => __('static.course')]),
            "data" => $course
        );
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return array(
            'data' => $course
        );
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $account_user_id = Auth::guard('api')->id();
        $user = User::where('account_user_id','=',$account_user_id)->first();
        
        if($user) {
            $organizationId = $user->organization_id;
        }
        else {
            throw new ApiGenericException('No Organization found');
        }

        $request->request->add(['id' => $course->id]);

        $validator = (new Validator($request))->update();

        if($validator->fails()){
            throw new ValidationException($validator);
        }
        
        $course->fill($request->all());
        $course->update();
        
        if($request->hasFile('avatar'))
        {
            $course->addMediaFromRequest('avatar')->toMediaCollection('thumbnail');
        }

        return array(
            'message' => __('message.updated successfully',['static' => __('static.course')]),
            "data" => $course
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    { 
        $course->delete();

        return array(
            'message' => __('message.deleted successfully',['static' => __('static.course')]),
            "data" => $course
        );
    }
}
