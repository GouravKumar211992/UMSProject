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
    
     private function batchArray() {
        return ['2023-2024AUG', '2023-2024JUL', '2023-2024', '2022-2023', '2021-2022','2020-2021','2019-2020','2018-2019','2017-2018','2016-2017','2015-2016','2014-2015']; 
    }
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
            $batch = $this->batchPrefixByBatch($result_query_string->roll_no); 
            $roll_no = $result_query_string->roll_no;
        }
    
        $campuses = Campuse::whereIn('id',$campus_ids)->get();
        $courses = Course::withoutTrashed()->whereIn('campus_id',$campus_ids)->orderBy('name')->get();
        $semesters = Semester::withoutTrashed()->where('course_id',$course_id)->orderBy('semester_number')->get();
    
        $students = array();
        $batchPrefix = $this->batchPrefixByBatch($batch); 
    
        if(!empty($course_id) && !empty($semester_id) && !empty($batch)){
            $students_query = Result::select('roll_no','course_id','semester')
                ->where('roll_no','LIKE',$batchPrefix.'%')
                ->where('course_id',$course_id)
                ->where('semester',$semester_id)
                ->where('status',2);
    
            if(!empty($roll_no)){
                $students_query->where('roll_no',$roll_no);
            }
    
            $students = $students_query->distinct('roll_no','course_id','semester')
                ->orderBy('roll_no')
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
                
                $results = $result_single ? $result_single->get_semester_result(1) : [];
    
                $student->exam_year_array = $exam_year_array;
                $student->result_single = $result_single;
                $student->results = $results;
            }
        }
        $batches = $this->batchArray();
        return view('ums.reports.Mbbs_bulk_result',compact('students','campuses','courses','semesters','batch','batchPrefix','batches'));
    }
    
    private function batchPrefixByBatch($batch) {
        return substr($batch, 0, 4); 
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
