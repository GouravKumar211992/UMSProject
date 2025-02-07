<?php

<<<<<<< HEAD
namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
=======
namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\Notification;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotificationExport;

use DB;
use Validator;

class NotificationController extends Controller
{
   public function index()
   {  	
   	   $data=Notification::all();
<<<<<<< HEAD
   	 return view('admin.master.notification.show',['items'=>$data]);
=======
   	 return view('ums.master.notification.notification',['items'=>$data]);
   }
   public function index2()
   {  	
   	   $data=Notification::all();
   	 return view('ums.master.faculty.notification',['items'=>$data]);
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
   }
    public function add(Request $request)
   { 
     $validator = Validator::make($request->all(),[
        'notification_description' => 'required',
        'notification_start' => 'required',
        'notification_end'   => 'required',
   
        ]);
     if ($validator->fails()) {    
      return back()->withErrors($validator)->withInput($request->all());
    }
   	 $data= new Notification;
     $data->notification_description=$request->notification_description;
     $data->notification_start=$request->notification_start;
   	 $data->notification_end=$request->notification_end;
     $data->save();
   	 return redirect()->route('notification')->with('success','Notification Added Successfully.');
   }
    public function edit($id)
   {
   	  $data=Notification::find($id);
<<<<<<< HEAD
   	 return view('admin.master.notification.edit-notification',['info'=>$data]);
=======
   	 return view('ums.master.notification.notification_edit',['info'=>$data]);
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
   }
    public function delete($id)
   {
   	    $data=Notification::find($id);
        $data->delete();
       
     return redirect()->route('notification')->with('success','Notification  Deleted Successfully.');
    
   }
     public function update(Request $request,$id)
   {

   	  $update=Notification::find($id);
       $update->notification_description=$request->get('notification_description');
       $update->notification_start=$request->get('notification_start');
       $update->notification_end=$request->get('notification_end');
       $update->save();

        return redirect()->route('notification')->with('success','Notification Updated Successfully.');
   	 
   }
    public function notificationExport(Request $request)
    {
        return Excel::download(new NotificationExport($request), 'Notification.xlsx');
    } 

}
