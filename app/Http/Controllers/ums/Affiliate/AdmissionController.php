<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Affiliate\AdmissionController;
use App\Models\Category;
use App\Models\AcademicSession;
use App\Models\Campuse;
use App\Models\Country;
use App\Models\Course;
use Auth;

class AdmissionController extends Controller
{
  public function application_form(Request $request){
        $data['programm_types'] = Category::get();
        $data['academic_sessions'] = AcademicSession::where('status','active')->get();
        $data['courses'] = [];
        $data['affiliated'] = Campuse::where('is_affiliated',1)->get();
        //dd($data['affiliated']);
        $data['countries'] = Country::get();
        $data['course_id'] = $request->course_id;
        $data['programm_type_id'] = null;
        if($request->course_id!=null){
            $data['courses'] = Course::where('id',$request->course_id)->get();
            $data['programm_type_id'] = Course::join('categories', 'courses.category_id', '=', 'categories.id')->select('categories.id','categories.name')->where('courses.id',$request->course_id)->first()->id;
        //  dd($data['programm_type_id']);
        }
       //dd($program['id']);
       return view('affiliate.affiliate-application-form',$data);
    }
}
