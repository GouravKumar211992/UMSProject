<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Migration;
use App\Models\Enrollment;
use App\Models\Icard;
//use DB;
use Validator;


class MigrationController extends Controller
{
   public function show($id)
   {  
   	//$dt=DB::connection()->getDatabaseName();
   	   //dd($id);
   	      $data=Enrollment::find($id);
		//dd($data);
      $details=Icard::where('enrolment_number',$data->enrollment_no)->first();
   	 //dd($details);
   	 return view('admin.enrollment.migration-certificate',['detail'=>$details]);
   }
   public function add( Request $request)
   {
   	   $validator = Validator::make($request->all(),[
        'date_of_migration_certificate' => 'required',   
        ]);
     if ($validator->fails()) 
     {    
      return back()->withErrors($validator)->withInput($request->all());
     }
   	 $data= new Migration;
     $data->enrollment_number=$request->enrollment_number;
     $data->roll_number=$request->roll_number;
   	 $data->student_name=$request->student_name;
   	 $data->father_name=$request->father_name;
   	 $data->mother_name=$request->mother_name;
   	 $data->course=$request->course;
   	 $data->branch=$request->branch;
     $data->batch=$request->batch;
   	 $data->serial_no=$request->serialno;
   	 $data->date_of_migration_certificate=$request->date_of_migration_certificate;
     $data->save();
     return redirect()->route('get-enrollment')->with('success','Migration Data Save Successfully');

   }
}
