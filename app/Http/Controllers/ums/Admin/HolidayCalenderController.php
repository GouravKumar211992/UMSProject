<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HolidayCalenderModel;

class HolidayCalenderController extends Controller
{
   public function holidayCalender()
   {
       $holidayCalendor = HolidayCalenderModel::all();
       return view('holidaycalender',['holidayCalendor'=>$holidayCalendor]);
   }
   public function holidayCalenderForStudent()
   {
       $holidayCalendor = HolidayCalenderModel::all();
       return view('student.dashboard.calender',['holidayCalendor'=>$holidayCalendor]);
   }
   
   public function holidayCalenderSave(Request $request)
   {
        $HolidayCalenderModel = new HolidayCalenderModel;
        $HolidayCalenderModel->year = $request->year;
        $HolidayCalenderModel->addMediaFromRequest('HolidayCalenderModel_doc')->toMediaCollection('HolidayCalenderModel_doc');
        $HolidayCalenderModel->save();
        return back()->with('success','data saved');
   }
   public function holidayCalenderDelete($id)
   {
    $HolidayCalenderModel = HolidayCalenderModel::find($id);
    $HolidayCalenderModel->delete();
    return back();
   }
}
