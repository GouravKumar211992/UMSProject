<?php
<<<<<<< HEAD

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HolidayCalenderModel;
=======
namespace App\Http\Controllers\ums\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\HolidayCalenderModel;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

class HolidayCalenderController extends Controller
{
   public function holidayCalender()
   {
       $holidayCalendor = HolidayCalenderModel::all();
<<<<<<< HEAD
       return view('holidaycalender',['holidayCalendor'=>$holidayCalendor]);
=======
       return view('ums.master.holiday_calender.holiday_calender',['holidayCalendor'=>$holidayCalendor]);
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
   }
   public function holidayCalenderForStudent()
   {
       $holidayCalendor = HolidayCalenderModel::all();
<<<<<<< HEAD
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
=======
       return view('ums.master.faculty.holiday_calender',['holidayCalendor'=>$holidayCalendor]);
   }

public function holidayCalenderSave(Request $request)
{
    $HolidayCalenderModel = new HolidayCalenderModel;
    $HolidayCalenderModel->year = $request->year;
    $HolidayCalenderModel->file = $request->file;

    // Check if file is uploaded
    if ($request->hasFile('holiday_calendar')) {
        $HolidayCalenderModel->addMediaFromRequest('holiday_calendar')
                             ->toMediaCollection('holiday_calendar');
    }

    $HolidayCalenderModel->save();

    return back()->with('success', 'Holiday calendar data saved.');
}



//    public function holidayCalenderDelete($id)
//    {
//     $HolidayCalenderModel = HolidayCalenderModel::find($id);
//     $HolidayCalenderModel->delete();
//     return back();
//    }

public function holidayCalenderDelete($id)
{
    $HolidayCalenderModel = HolidayCalenderModel::find($id);
    if ($HolidayCalenderModel) {
        $HolidayCalenderModel->delete(); // Soft delete
        return back()->with('success', 'Holiday calendar deleted successfully.');
    }
    return back()->with('error', 'Holiday calendar not found.');
}



}


>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
