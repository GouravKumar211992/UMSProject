<?php

namespace App\Http\Controllers\Admin\Master;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;

use App\Models\Shift;
use App\Exports\ShiftExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class ShiftController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {   
        $shifts = Shift::orderBy('id', 'DESC');

        if($request->search) {
            $keyword = $request->search;
            $shifts->where(function($q) use ($keyword){

                $q->where('name', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $shifts->where('name','LIKE', '%'.$request->name.'%');
        }
        
        $shifts = $shifts->paginate(10);
              
        return view('admin.master.shift.index', [
            'page_title' => "Shift",
            'sub_title' => "records",
            'all_shifts' => $shifts,

        ]);
    }

    public function add(Request $request)
    {

        return view('admin.master.shift.addshift', [
            'page_title' => "Add New",
            'sub_title' => "Shift",
        ]);
    }

    public function addShift(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);

        $data = $request->all();
        $shift = new Shift();
        $shift->fill($data);
        $shift->save();
        return redirect()->route('get-shift')->with('success','Added Successfully');
    }

    public function editShift(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ]);
        $update_category = Shift::where('id', $request->shift_id)->update(['name' => $request->name,'start_time' => $request->start_time,'end_time' => $request->end_time]);
        return redirect()->route('get-shift')->with('success','Updated Successfully');
    }


    public function editshifts(Request $request, $slug)
    {
        $selectedShift = Shift::where('id', $slug)->first();
         return view('admin.master.shift.editshift', [
            'page_title' => $selectedShift->name,
            'sub_title' => "Edit Information",
            'selected_shift' => $selectedShift,

        ]);
    }

    public function softDelete(Request $request,$slug) {
        
        Shift::where('id', $slug)->delete();
        return redirect()->route('get-shift')->with('success','Deleted Successfully');;
        
    }
    public function shiftExport(Request $request)
    {
        return Excel::download(new ShiftExport($request), 'Shift.xlsx');
    } 
}