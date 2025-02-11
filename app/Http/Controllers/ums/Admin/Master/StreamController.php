<?php

namespace App\Http\Controllers\ums\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\ums\AdminController;

use App\Models\ums\Course;
use App\Models\ums\Category;
use App\Models\ums\Stream;
use App\Models\ums\Campuse;
use App\Exports\StreamExport;
use Maatwebsite\Excel\Facades\Excel;

use Auth;

class StreamController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   

        $streams = Stream::with(['category','course'])->orderBy('id', 'DESC');
            
        if($request->search) {
            $keyword = $request->search;
            $streams->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $streams->where('name','LIKE', '%'.$request->name.'%');
        }
        if (!empty($request->course_id)) {
            $streams->where('course_id',$request->course_id);
        }
        if (!empty($request->category_id)) {
            
            $streams->where('category_id',$request->category_id);
        }
		if (!empty($request->campus)) {
			$course=Course::where('campus_id',$request->campus)->pluck('id')->toArray();
            //dd($course);
            $streams->whereIn('course_id',$course);
        }
        
        $streams = $streams->paginate(10);
        $category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();
        return view('ums.master.stream.stream_list', [
            'page_title' => "Stream",
            'sub_title' => "records",
            'all_stream' => $streams,
            'categories' => $category,
            'courses' => $course,
			'campuselist' => $campuse,
        ]);
    }

    public function add(Request $request)
    {
        $category = Category::all();
        $course = Course::all();
        $campuse = Campuse::all();

        return view('ums.master.stream.add_stream_list', [
            'page_title' => "Add New",
            'sub_title' => "Stream",
            'categorylist' => $category,
            'courselist' => $course,
            'campuselist' => $campuse,
        ]);
    }

    public function addStream(Request $request)
    {
        $request->validate([
            'stream_name' => 'required',
            'category_id' => 'required',
            'course_id'   => 'required',
        ]);
        $data = $request->all();
        $stream = $this->create($data);
        return redirect()->route('stream_list')->with('success','Added Successfully.');
    }

    public function create(array $data)
    {
		$stream_code=Course::where(['id'=>$data['course_id']])->first();
		//dd($stream_code->color_code);
      return Stream::create([
        'name' => $data['stream_name'],
        'category_id' => $data['category_id'],
        'course_id' => $data['course_id'],
        'stream_code' => $stream_code->color_code,
        'created_by' => 1,
        //'created_by' => Auth::guard('admin')->user()->id,
      ]);
    }

    public function editStream(Request $request)
    {

        $request->validate([
            'stream_name' => 'required',
            'category_id' => 'required',
            'course_id'   => 'required',
        ]);
		$stream_code=Course::where(['id'=>$request->course_id])->first();
        $update_category = Stream::where('id', $request->stream_id)->update(['name' => $request->stream_name, 'category_id' => $request->category_id,'course_id' => $request->course_id,'stream_code'=>$stream_code->color_code, 'updated_by' => 1]);
        return redirect()->route('stream_list')->with('success','Updated Successfully.');
    }


    public function editstreams(Request $request, $slug)
    {
        $selectedStream = Stream::Where('id', $slug)->first();
        $category = Category::all();
        $course = Course::all();
		 $campuse = Campuse::all();
        return view('ums.master.stream.stream_list_edit', [
            'page_title'        => $selectedStream->name,
            'sub_title'         => "Edit Information",
            'categorylist'      => $category,
            'courseList'        => $course,
			'campuselist' => $campuse,
            'selected_stream'   => $selectedStream
        ]);
    }


    public function show()
    {
        return view('admin.ProductCategory.view');
    }

    public function edit($id)
    {
        $productCategory = ProductCategory::find($id);
        $parents = ProductCategory::whereNull('parent_id')->get();


        return view(
            'admin.master.category.edit',
            array(
                'parents' => $parents,
                'productCategory' => $productCategory
            )
        );
    }

    public function softDelete(Request $request,$slug) {
        
        Stream::where('id', $slug)->delete();
        return redirect()->route('stream_list')->with('success','Deleted Successfully.');
        
    }
    public function getCourseList(Request $request)
    {
		$html='<option value="">--Select Course--</option>';
        $query = Course::Where(['category_id'=> $request->id,'campus_id'=>$request->university])->get();
       foreach($query as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		return $html;

    }
    
    // public function getCourseList(Request $request)
    // {
    //     $html = '<option value="">--Select Course--</option>';
        
    //     // Remove category_id and filter based on campus_id only
    //     $query = Course::where('campus_id', $request->university)->get();
    
    //     foreach ($query as $course) {
    //         $html .= '<option value="' . $course->id . '">' . $course->name . '</option>';
    //     }
        
    //     return response()->json($html);  // Return the HTML for course dropdown
    // }
    
    
    public function getStreamList(Request $request)
    {
		$html='<option value="">--Select Course--</option>';
        $query = Stream::join('courses','courses.id','streams.course_id')
        ->where('courses.campus_id',$request->slug);
        if($request->course_id){
            $query->where('streams.course_id',$request->course_id);
        }
        $streams = $query->get();
        foreach($streams as $sc){
			$html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
		}
		return $html;

    }
    public function streamExport(Request $request)
    {
        return Excel::download(new StreamExport($request), 'Stream.xlsx');
    } 
}