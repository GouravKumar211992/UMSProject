<?php

<<<<<<< HEAD
namespace App\Http\Controllers\ums\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\AcademicSession;
use App\Models\ums\Result;
use App\Models\ums\Course;
use App\Models\ums\Campuse;
use App\Models\ums\Category;
use App\Models\ums\Semester;
use Illuminate\Support\Facades\Auth;
=======
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\Course;
use App\Models\Campuse;
use App\Models\Category;
use App\Models\Semester;
use Auth;

>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
class MbbsResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function mbbsResult(Request $request){
        $campus_ids = [4,6];
		$course_id = $request->course_id;
		$semester_id = $request->semester_id;
		$batch = $request->batch;
        $roll_no = $request->roll_no;
        if($request->result_query_string){
            $result_query_string = (object)unserialize(base64_decode($request->result_query_string));
            $course_id = $result_query_string->course_id;
            $semester_id = $result_query_string->semester_id;
            $batch = batchFunctionReturn($result_query_string->roll_no);
            $roll_no = $result_query_string->roll_no;
        }
        $campuses = Campuse::whereIn('id',$campus_ids)
        ->get();
		$courses = Course::withoutTrashed()
        ->whereIn('campus_id',$campus_ids)
        ->orderBy('name')
        ->get();
		$semesters = Semester::withoutTrashed()
        ->where('course_id',$course_id)
        ->orderBy('semester_number')
        ->get();
<<<<<<< HEAD
		// if(Auth::guard('admin')->check()==false){
		// 	return back()->with('error','Not Allowed');
		// }
        $students = array();
        $batchPrefix = ['2020-2021','2021-2022','2022-2023','2023-2024']; //updated code//
        // $batchPrefix = batchPrefixByBatch($batch);
=======
		if(Auth::guard('admin')->check()==false){
			return back()->with('error','Not Allowed');
		}
        $students = array();
        $batchPrefix = batchPrefixByBatch($batch);
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
        if($course_id!='' && $semester_id!='' && $batch!=''){
            
        // $array_roll_no = [
        //     '1901247001',
        //     '1901247010',
        //     '1901247018',
        //     '1901247044',
        // ];

            $students_query = Result::select('roll_no','course_id','semester')
//            ->where('exam_session',$batch)
<<<<<<< HEAD
            // ->where('roll_no','LIKE',$batchPrefix.'%')  //
            // ->whereIn('roll_no',$array_roll_no)
            ->where(function($query) use ($batchPrefix) {
                foreach ($batchPrefix as $prefix) {
                    $query->orWhere('roll_no', 'LIKE', $prefix.'%');
                } 
            })    //updated//
            ->where('course_id',$course_id)
            ->where('semester',$semester_id)
            ->where('status',2);
            // if(Auth::guard('admin')->check()==true){
            //     $students_query->whereIn('result_type',['new','old']);
            // }
            // else{
            //     $students_query->where('result_type','new');
            // }
=======
            ->where('roll_no','LIKE',$batchPrefix.'%')
            // ->whereIn('roll_no',$array_roll_no)
            ->where('course_id',$course_id)
            ->where('semester',$semester_id)
            ->where('status',2);
            if(Auth::guard('admin')->check()==true){
                $students_query->whereIn('result_type',['new','old']);
            }else{
                $students_query->where('result_type','new');
            }
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
            if($roll_no!=''){
                $students_query->where('roll_no',$roll_no);
            }
            // if($request->exam_type==3){
            //     $students_query->where('scrutiny',$request->exam_type);
            // }
            $students = $students_query->distinct('roll_no','course_id','semester')
            ->orderBy('roll_no')
            // ->limit(5)
            ->get();
            foreach($students as $student){
                $exam_year_array = Result::where('roll_no',$student->roll_no)
                ->where('semester',$student->semester)
                ->where('roll_no','LIKE',$batchPrefix.'%')
                ->first();
                $result_single = Result::where('roll_no',$student->roll_no)
                ->where('semester',$student->semester)
                ->where('roll_no','LIKE',$batchPrefix.'%')
                ->orderBy('subject_code','ASC')
                ->orderBy('back_status','DESC')
                ->orderBy('exam_session','DESC')
                ->distinct()
                ->first();
                $results = $result_single->get_semester_result(1);
                $student->exam_year_array = $exam_year_array;
                $student->result_single = $result_single;
                $student->results = $results;
            }   
		}
<<<<<<< HEAD
		return view('ums.mbbsparanursing.bulk_result',compact('students','campuses','courses','semesters','batch','batchPrefix'));
=======
		return view('admin.result.mbbs-result-view',compact('students','campuses','courses','semesters','batch','batchPrefix'));
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
	}
	
	




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function mbbs_all_result(Request $request)
    {
<<<<<<< HEAD
        // dd($request->all());
=======
>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
      $results = Result::where('course_id','49')
      ->groupby('enrollment_no','semester')
      ->orderBy('id', 'DESC');
      if($request->search) {
        $keyword = $request->search;
        $results->where(function($q) use ($keyword){
            $q->where('roll_no', 'LIKE', '%'.$keyword.'%');
        });
    }
    if(!empty($request->name)){
            
      $results->where('roll_no','LIKE', '%'.$request->name.'%');
  }
  if (!empty($request->course_id)) {
      $results->where('course_id',$request->course_id);
  }
    if (!empty($request->campus)) {
      $enrollment[]=null;
      $campus=Campuse::find($request->campus);
     //dd($campus);
      foreach($results->get() as $key=> $result){
        //dd(campus_name($result->enrollment_no));
        if($campus->campus_code==campus_name($result->enrollment_no)){
       $enrollment[]=$result->enrollment_no;}
      
       //dd($enrollment);
          }
    //dd($results->first()->campus_code);
      $results->whereIn('enrollment_no',$enrollment);
  }
      if(!empty($request->semester)) {
      $semester_ids = Semester::where('name',$request->semester)->pluck('id')->toArray();
            $results->whereIn('semester',$semester_ids);
        }
        $category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();
        $semester = Semester::select('name')->distinct()->get();
        $data['results'] = $results->paginate(100);
        $data['categories']=$category;
        $data['courses']=$course;
        $data['campuselist']=$campuse;
        $data['semesterlist']=$semester;

<<<<<<< HEAD
      return view('ums.reports.mbbs_result',$data);
    }

// public function mbbs_all_result(Request $request)
// {
//     $results = Result::selectRaw('MAX(id) as id, enrollment_no, semester, MAX(roll_no) as roll_no')
//         ->where('course_id', 49)
//         ->groupBy('enrollment_no', 'semester') // Group by enrollment_no and semester
//         ->orderBy('id', 'DESC'); // Order by id (to get the latest result per enrollment_no and semester)

//     if ($request->search) {
//         $keyword = $request->search;
//         $results->where(function ($q) use ($keyword) {
//             $q->where('roll_no', 'LIKE', '%' . $keyword . '%');
//         });
//     }

//     if (!empty($request->name)) {
//         $results->where('roll_no', 'LIKE', '%' . $request->name . '%');
//     }

//     if (!empty($request->course_id)) {
//         $results->where('course_id', $request->course_id);
//     }

//     if (!empty($request->campus)) {
//         $campus = Campuse::find($request->campus);

//         if ($campus) {
//             $enrollment = Result::whereHas('campus', function ($query) use ($campus) {
//                 $query->where('campus_code', $campus->campus_code);
//             })->pluck('enrollment_no');

//             $results->whereIn('enrollment_no', $enrollment);
//         }
//     }

//     if (!empty($request->semester)) {
//         $semester_ids = Semester::where('name', $request->semester)->pluck('id')->toArray();
//         $results->whereIn('semester', $semester_ids);
//     }

//     $category = Category::all();
//     $course = Course::all();
//     $campus = Campuse::all();
//     $semester = Semester::select('name')->distinct()->get();

//     $data['results'] = $results->paginate(100);
//     $data['categories'] = $category;
//     $data['courses'] = $course;
//     $data['campuselist'] = $campus;
//     $data['semesterlist'] = $semester;

//     return view('ums.reports.mbbs_result', $data);
// }
   //updated function previous not working//

=======
      return view('admin.result.mbbs-results',$data);
    }

>>>>>>> f4765a923a28bfad5b4cade903cdbf51ead6f96d
}
