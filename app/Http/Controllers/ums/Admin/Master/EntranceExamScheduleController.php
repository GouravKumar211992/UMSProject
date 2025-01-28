<?php

namespace App\Http\Controllers\Admin\Master;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EntranceExamScheduleExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EntranceExamSchedule;
use Validator;

class EntranceExamScheduleController extends Controller
{
    public function index()
   {    
       $data=EntranceExamSchedule::all();
     return view('admin.master.entrance-exam-schedule.show',['items'=>$data]);
   }
    public function add(Request $request)
   { 
     $validator = Validator::make($request->all(),[
        'program_name' => 'required',
    'program_code' => 'required',
    'exam_date'   => 'required',
     'exam_time'   => 'required',
     'exam_ending_time'   => 'required',
   
        ]);
     if ($validator->fails()) {    
      return back()->withErrors($validator)->withInput($request->all());
    }
     $data= new EntranceExamSchedule;
     $data->program_name=$request->program_name;
     $data->program_code=$request->program_code;
     $data->exam_date=$request->exam_date;
     $data->exam_time=$request->exam_time;
      $data->exam_ending_time=$request->exam_ending_time;
     $data->save();

     return redirect()->route('entranceexamschedule')->with('success',' Added Successfully.');
   }
    public function edit($id)
   {
      $data=EntranceExamSchedule::find($id);
     return view('admin.master.entrance-exam-schedule.edit',['info'=>$data]);
   }
    public function delete($id)
   {
        $data=EntranceExamSchedule::find($id);
        $data->delete();
       
     return redirect()->route('entranceexamschedule')->with('success','  Deleted Successfully.');
    
   }
     public function update(Request $request,$id)
   {

      $update=EntranceExamSchedule::find($id);
       $update->program_name=$request->get('program_name');
       $update->program_code=$request->get('program_code');
       $update->exam_date=$request->get('exam_date');
       $update->exam_time=$request->get('exam_time');
       $update->exam_ending_time=$request->get('exam_ending_time');
       $update->save();

        return redirect()->route('entranceexamschedule')->with('success',' Updated Successfully.');
     
   }

    public function entranceExamScheduleExport(Request $request)
    {
        return Excel::download(new EntranceExamScheduleExport($request), 'EntranceExamSchedule.xlsx');
    } 
}
