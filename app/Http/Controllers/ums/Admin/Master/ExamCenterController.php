<?php

namespace App\Http\Controllers\ums\Admin\Master;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExamCenterExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\ExamCenter;
use Validator;

class ExamCenterController extends Controller
{
    public function index()
   {  	
   	  $data=ExamCenter::all();
   	  return view('ums.master.exam_center.exam_center',['items'=>$data]);
   }
    public function add(Request $request)
   { 
     $validator = Validator::make($request->all(),[
        'center_code' => 'required',
    'center_name' => 'required',
    'status'   => 'required',
   
        ]);
     if ($validator->fails()) {    
      return back()->withErrors($validator)->withInput($request->all());
    }
   	 $data= new ExamCenter;
     $data->center_code=$request->center_code;
     $data->center_name=$request->center_name;
   	 $data->status=$request->status;
     $data->save();
   	 return redirect()->route('exam-center')->with('success','Exam-Center Added Successfully.');
   }
     public function delete($id)
   {
   	    $data=ExamCenter::find($id);
        $data->delete();
       
     return redirect()->route('exam-center')->with('success','exam-center Data Deleted Successfully.');
    
   }
    public function edit($id)
   {
   	  $data=ExamCenter::find($id);
   	  return view('admin.master.examcenter.edit-exam-center',['info'=>$data]);
   }
  
     public function update(Request $request,$id)
   {
   	  $update=ExamCenter::find($id);
       $update->center_code=$request->get('center_code');
       $update->center_name=$request->get('center_name');
       $update->status=$request->get('status');
       $update->save();

        return redirect()->route('exam-center')->with('success','Exam Center Updated Successfully.');
   	 
   }
     public function examEenterExport(Request $request)
    {
        return Excel::download(new ExamCenterExport($request), 'ExamCenter.xlsx');
    } 

}
