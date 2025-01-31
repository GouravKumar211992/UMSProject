<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Auth;
use App\Models\Category;
use Hash;

class CourseController extends Controller
{

    public function index(Request $request){
		$data['course'] = \App\Models\Course::find($request->id);
		//dd($data['course']);
        return view('frontend.course.course-details',$data);
    }

}
