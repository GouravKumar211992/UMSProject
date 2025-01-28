<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComplaintForm;
use App\Exports\CompaintsExport;
use Maatwebsite\Excel\Facades\Excel;

class GrievanceController extends Controller
{  

    public function complaints(Request $request)
    {   
        $complaints = ComplaintForm::orderBy('id', 'DESC');
        $data = ComplaintForm::select('roll_number')->distinct()->get();
        if($request->has('search') && $request->search !== null) {
            $keyword = $request->search;
            $complaints->where(function($q) use ($keyword){
                $q->where('first_Name', 'LIKE', '%'.$keyword.'%');
                $q->orWhere('roll_number', 'LIKE', '%'.$keyword.'%');
            });
        }
        if($request->has('first_Name') && !empty($request->name)){
            
            $complaints->where('first_Name','LIKE', '%'.$request->first_Name.'%');
            $complaints->orWhere('email','LIKE', '%'.$request->first_Name.'%');
        }
    
         $complaints=$complaints->paginate(10);
         return view('admin.grievance.complaints',compact('complaints','data'));
    }
    public function complaintList(Request $request)
    {
        $roll_number = $request->roll_number;
        $data = ComplaintForm::select('complaint_no')
        ->where('roll_number',$roll_number)
        ->distinct('complaint_no')
        ->orderBy('complaint_no','ASC')
        ->orderBy('id','DESC')
        ->get();
        return view('grievance.admin-complaint-list',compact('data'));
    }
    public function complaintsExport(Request $request)
    {
        return Excel::download(new CompaintsExport($request),'Complaints.xlsx');
    }

    public function complaintDetails(Request $request){
        $complaint_no = $request->complaint_no;
        $data = ComplaintForm::where('complaint_no',$complaint_no)->orderBy('id','ASC')->get();
        return view('grievance.admin-complaint-details',compact('data'));
    }
    public function complaintDetailsSave(Request $request){
        $complaint_no = $request->complaint_no;
        $data = ComplaintForm::where('complaint_no',$complaint_no)->orderBy('id','DESC')->first();
        if($data){
            $complaint = new ComplaintForm;
            $complaint->complaint_no = $data->complaint_no;
            $complaint->enrollment_no = $data->enrollment_no;
            $complaint->roll_number = $data->roll_number;
            $complaint->responder_id = $data->responder_id;
            $complaint->responder_type = 1;
            $complaint->course_id = $data->course_id;
            $complaint->first_Name = $data->first_Name;
            $complaint->complaint = $request->complaint;
            $complaint->status = $request->status;
            $complaint->save();
        }
        return back()->with('success','You have replied successfully');
    }

}
