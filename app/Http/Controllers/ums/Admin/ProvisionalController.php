<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provisional;
use App\Models\Enrollment;
use App\Models\Icard;

use Validator;


class ProvisionalController extends Controller
{
   public function show($id)
   { 
   	  $data=Enrollment::find($id);
     // dd($data);
      $details=Icard::where('enrolment_number',$data->enrollment_no)->first();
   	 //dd($details);
   	 return view('admin.enrollment.provisional-certificate',['detail'=>$details]);
   }

    public function add( Request $request)
   {
   	   $validator = Validator::make($request->all(),[
        'date_of_provisional_certificate' => 'required',   
        ]);
     if ($validator->fails()) 
     {    
      return back()->withErrors($validator)->withInput($request->all());
     }
   	 $data= new Provisional;
     $data->enrollment_number=$request->enrollment_number;
     $data->roll_number=$request->roll_number;
   	 $data->student_name=$request->student_name;
   	 $data->course=$request->course;
   	 $data->branch=$request->branch;
   	 $data->batch=$request->batch;
     $data->serial_no=$request->serialno;
   	 $data->date_of_provisional_certificate=$request->date_of_provisional_certificate;
     $data->save();
     return redirect()->route('get-enrollment')->with('success','Provisional  Data Save Successfully');
   }
}
