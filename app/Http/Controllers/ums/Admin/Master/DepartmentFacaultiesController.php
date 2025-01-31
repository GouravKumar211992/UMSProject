<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use App\Models\DepartmentFacaulties;
use Validator;
use App\Exports\DepartmentFacultyExport;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentFacaultiesController extends AdminController
{
    public function index()
    {
        $data = DepartmentFacaulties::all();
        return view('admin.master.department-facaulty.show',['items'=>$data]);
    }

    public function addPage()
    {
        return view('admin.master.department-facaulty.add');
    }

    public function add(Request $request)
    { 
      $validator = Validator::make($request->all(),[
          'name'   => 'required|unique:department_facaulties,name',
        ]);
        if ($validator->fails()) {    
        return back()->withErrors($validator)->withInput($request->all());
      }
        $data = new DepartmentFacaulties;
        $data->name = $request->name;
        $data->created_at = date('Y-m-d');
        $data->save();
        return back()->with('success','Added Successfully.');
    }
    public function edit($id)
    {
        $data = DepartmentFacaulties::find($id);
        return view('admin.master.department-facaulty.edit',['data'=>$data]);
    }
     public function delete($id)
    {
        $data = DepartmentFacaulties::find($id);
        $data->delete();
        return back()->with('success','Deleted Successfully.');
      }
    public function update(Request $request,$id)
    {
          $validator = Validator::make($request->all(),[
            'name'   => 'required',
          ]);
          if ($validator->fails()) {    
          return back()->withErrors($validator)->withInput($request->all());
        }
          $data = DepartmentFacaulties::find($id);
          $data->name = $request->name;
          $data->updated_at = date('Y-m-d');
          $data->save();
          return back()->with('success','Update Successfully.');
      }
 
     public function departmentFacultyExport(Request $request)
    {
        return Excel::download(new DepartmentFacultyExport($request), 'DepartmentFaculties.xlsx');
    } 
 }