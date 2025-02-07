<?php

namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\Department;
use App\Exports\DepartmentExport;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class DepartmentController extends Controller
{
      public function index()
    {
        $data = Department::all();
        return view('ums.master.department.department',['items'=>$data]);
    }

    public function addPage()
    {
        return view('ums.master.department.department_add');
    }

    public function add(Request $request)
    { 
      $validator = Validator::make($request->all(),[
          'name'   => 'required',
          'dean'   => 'required',
          'head'   => 'required',
          'faculty'   => 'required',
          'contact'   => 'required',
          'email'   => 'required',
        ]);
        if ($validator->fails()) {    
        return back()->withErrors($validator)->withInput($request->all());
      }
        $data = new Department;
        $data->name = $request->name;
        $data->dean = $request->dean;
        $data->head = $request->head;
        $data->faculty = $request->faculty;
        $data->contact = $request->contact;
        $data->email = $request->email;
        $data->created_at = date('Y-m-d');
        $data->save();
<<<<<<< HEAD
        return redirect('get-department')->with('success','Added Successfully.');
=======
        return back()->with('success','Added Successfully.');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }
    public function edit($id)
    {
        $data = Department::find($id);
        return view('ums.master.department.department_edit',['data'=>$data]);
    }
     public function delete($id)
    {
        $data = Department::find($id);
        $data->delete();
        return back()->with('success','Deleted Successfully.');
      }
    public function update(Request $request,$id)
    {
        
          $data = Department::find($id);
          $data->name = $request->name;
          $data->dean = $request->dean;
          $data->head = $request->head;
          $data->faculty = $request->faculty;
          $data->contact = $request->contact;
          $data->email = $request->email;
          $data->updated_at = date('Y-m-d');
          $data->save();
<<<<<<< HEAD
          return redirect('get-department')->with('success','Update Successfully.');
=======
          return redirect('department')->with('success','Update Successfully.');
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
      }
       public function departmentExport(Request $request)
    {
        return Excel::download(new DepartmentExport($request), 'Department.xlsx');
    } 
 
}
