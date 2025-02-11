<?php

namespace App\Http\Controllers\ums\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\AcademicSession;
use App\Models\ums\Result;
use App\Models\ums\Course;
use App\Models\ums\Campuse; 
use App\Models\ums\Category;
use App\Models\ums\Semester;
use Auth;

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
		// if(Auth::guard('admin')->check()==false){
		// 	return back()->with('error','Not Allowed');
		// }
        $students = array();
        $batchPrefix = batchPrefixByBatch($batch);
        if($course_id!='' && $semester_id!='' && $batch!=''){
            
        // $array_roll_no = [
        //     '1901247001',
        //     '1901247010',
        //     '1901247018',
        //     '1901247044',
        // ];

            $students_query = Result::select('roll_no','course_id','semester')
//            ->where('exam_session',$batch)
            ->where('roll_no','LIKE',$batchPrefix.'%')
            // ->whereIn('roll_no',$array_roll_no)
            ->where('course_id',$course_id)
            ->where('semester',$semester_id)
            ->where('status',2);
        //     if(Auth::guard('admin')->check()==true){
        //         $students_query->whereIn('result_type',['new','old']);
        // }
            // else{
            //     $students_query->where('result_type','new');
            // }
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
		return view('ums.master.mbbsparanursing.mbbs_result',compact('students','campuses','courses','semesters','batch','batchPrefix'));
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
        // Start building the query
        $results = Result::where('course_id', '49')
                         ->orderBy('id', 'DESC');
    
        // Apply search filter if provided
        if ($request->search) {
            $keyword = $request->search;
            $results->where(function($q) use ($keyword) {
                $q->where('roll_no', 'LIKE', '%' . $keyword . '%');
            });
        }
    
        // Apply name filter if provided
        if (!empty($request->name)) {
            $results->where('roll_no', 'LIKE', '%' . $request->name . '%');
        }
    
        // Apply course filter if provided
        if (!empty($request->course_id)) {
            $results->where('course_id', $request->course_id);
        }
    
        // Apply campus filter if provided
        if (!empty($request->campus)) {
            $campus = Campuse::find($request->campus);
            if ($campus) {
                // Filter results by campus code
                $results->whereIn('enrollment_no', function ($query) use ($campus) {
                    $query->select('enrollment_no')
                          ->from('results')
                          ->whereRaw('campus_code = ?', [$campus->campus_code]);
                });
            }
        }
    
        // Apply semester filter if provided
        if (!empty($request->semester)) {
            $semester_ids = Semester::where('name', $request->semester)->pluck('id')->toArray();
            $results->whereIn('semester', $semester_ids);
        }
    
        // Get additional data for view
        $category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();
        $semester = Semester::select('name')->distinct()->get();
    
        // Paginate the results (100 results per page)
        $data['results'] = $results->paginate(100);
        $data['categories'] = $category;
        $data['courses'] = $course;
        $data['campuselist'] = $campuse;
        $data['semesterlist'] = $semester;
    
        // Return view with the data
        return view('ums.mbbsparanursing.all_mbbs_result', $data);
    }
    

}
