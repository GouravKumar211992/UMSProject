<?php
namespace App\Http\Controllers\ums\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\HolidayCalenderModel;

class HolidayCalenderController extends Controller
{
   public function holidayCalender()
   {
       $holidayCalendor = HolidayCalenderModel::all();
       return view('ums.master.holiday_calender.holiday_calender',['holidayCalendor'=>$holidayCalendor]);
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


