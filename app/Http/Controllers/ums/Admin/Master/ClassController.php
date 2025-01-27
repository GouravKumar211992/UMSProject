<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classe;
use Validator;
use App\Models\Course;
use App\Models\Category;
use App\Models\Semester;
use App\Models\Subject;

class ClassController extends Controller
{
     public function index()
   {  	
   	   $data=Classe::all();
   	 return view('admin.master.class.show',['data'=>$data]);
   }
   public function addview()
   {    $programs=Category::all();
       return view('admin.master.class.add',['programs'=>$programs]);   	
   }
    public function add(Request $request)
   { 
     $validator = Validator::make($request->all(),[
        'program_id' => 'required',
    'course_id' => 'required',
    'semester_id'   => 'required',
       'subject_code' => 'required',
    'class_name'   => 'required',
   
        ]);
     if ($validator->fails()) {    
      return back()->withErrors($validator)->withInput($request->all());
    }

   

   	 $data= new Classe;
     $data->program_id=$request->program_id;
     $data->course_id=$request->course_id;
   	 $data->semester_id=$request->semester_id;
   	 $data->sub_code=$request->subject_code;
     $data->sub_name=$request->subject_name;
   	 $data->class_name=$request->subject_code.$request->class_name;
   	 $data->from=$request->from;
   	 $data->to=$request->to;
     $data->save();
   	 return redirect()->route('class')->with('success','Class Added Successfully.');
    
   }
    public function edit($id)
   {
   	  $data=Classe::find($id);
   	 return view('admin.master.class.edit',['data'=>$data]);
   }
    public function delete($id)
   {
   	    $data=Classe::find($id);
        $data->delete();
       
     return redirect()->route('class')->with('success','Class  Deleted Successfully.');
    
   }
     public function update(Request $request,$id)
   {

   	  $update=Classe::find($id);
     $update->program_id=$request->get('program_id');
     $update->course_id=$request->get('course_id');
   	 $update->semester_id=$request->get('semester_id');
   	 $update->sub_code=$request->get('subject_code');
     $update->sub_name=$request->get('subject_name');
   	 $update->class_name=$request->get('class_name');
   	 $update->from=$request->get('from');
   	 $update->to=$request->get('to');
   	 
       $update->save();

        return redirect()->route('class')->with('success','Class Updated Successfully.');
   	 
   }
   public function get_programm(Request $request)
  {
    $html='<option value="">--Select Course--</option>';
    $query= Course::where('category_id', $request->program_id)->get();
    foreach($query as $sc){
      $html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
    }
    return $html;
  }
   public function get_semester(Request $request)
  {
    $html='<option value="">--Select Semester--</option>';
    $query= Semester::where(['program_id'=> $request->program_id,'course_id'=>$request->course_id])->get();
    foreach($query as $sc){
      $html.='<option value="'.$sc->id.'">'.$sc->name.'</option>';
    }
    return $html;
  }
  public function get_subject(Request $request)
  {
    $html='<option value="">--Select Subject--</option>';
    $query= Subject::where(['program_id'=> $request->program_id,'course_id'=>$request->course_id,'semester_id'=>$request->semester_id])->get();
    foreach($query as $sc){
      $html.='<option value="'.$sc->sub_code.'">'.$sc->name.'</option>';
    }
    return $html;
  }
}
