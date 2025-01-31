<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campuse;
use App\Models\Course;
use App\Models\AdmissionMerit;
use Validator;
use Auth;

class AdmissionMeritController extends Controller
{
    public function meritList(Request $request){
        $meritlists = AdmissionMerit::orderBy('id','desc')->get();
        return view('admission.admission-merit-list',compact('meritlists'));
    }
    public function meritListSave(Request $request)
    {
        if(Auth::guard('admin')->check()==false){
            return back()->with('error','Please login as an admin.');
        }
        $validator = Validator::make($request->all(),[
        'merit_name' => 'required',
        'merit_file' => 'required|mimes:pdf',
        ]);
        $attributes = array(
            'merit_name' => 'Merit Name',
        );
        $validator->setAttributeNames($attributes);
    
        if ($validator->fails()) {    
            return back()->withErrors($validator)->withInput($request->all());
        }

        if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->email=='dsmnruadmin@gmail.com' && Auth::guard('admin')->user()->role==1){
            $data= new AdmissionMerit;
            $data->merit_name=$request->merit_name;
            $data->addMediaFromRequest('merit_file')->toMediaCollection('merit_file');
            $data->save();
            return back()->with('success','Merit is added successfully.')->withInput($request->all());
        }else{
            return back()->with('error','Access Denied');
        }
    }
    public function meritListDelete(Request $request){
        if(Auth::guard('admin')->check()==false){
            return back()->with('error','Please login as an admin.');
        }
        $data = AdmissionMerit::find($request->id);
        if(!$data){
            return back()->with('error','Merit ID is invalid. Data can not be deleted.');
        }
        if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->email=='dsmnruadmin@gmail.com' && Auth::guard('admin')->user()->role==1) {
            $data->delete();
            return back()->with('success','Merit is deleted successfully.');
        }else{
            return back()->with('error','Access Denied');
        }
    }
}
