<?php

namespace App\Http\Controllers\V1;

use Auth;
use Models\User;
use Models\Role;
use Models\Course;
use Illuminate\Http\Request;
use Models\CourseMapping;
use Illuminate\Routing\Controller;
use App\Exceptions\ApiGenericException;
use Illuminate\Validation\ValidationException;
use Lib\Validation\CourseMapping as Validator;

class CourseMappingController extends Controller
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
        $user = User::where('account_user_id', $account_user_id)->first();

        if($user) {
            $organizationId = $user->organization_id;
        }
        else {
            throw new ApiGenericException(__('message.doesnt_exist', ['static' => __('field.organization')]));
        }

        $validator = (new Validator($request))->store();

        if($validator->fails()){
            throw new ValidationException($validator);
        }

        if($request->morphable_type == 'User') {

            $morphable = User::find($request->morphable_id);

        }else if($request->morphable_type == 'Role') {

            $morphable = Role::find($request->morphable_id);
        }
        else {

            throw new ApiGenericException(__('message.invalid request'));
        }

        $morphable->courseMappings()->updateOrCreate([
            'training_course_id' => $request->training_course_id
        ]);

        return array(
            'message' => __('message.created successfully',['static' => __('field.course_mapping')])
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function courseMappingRecords($courseId)
    {

        $courseMapping = CourseMapping::where('training_course_id', $courseId)
                                ->with('morphable')
                                ->get();

        return array(
            'data' => $courseMapping
        );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseMapping $courseMapping)
    {
        $courseMapping->delete();

        return array(
            'message' => __('message.deleted successfully',['static' => __('field.course_mapping')]),
        );
    }
}
