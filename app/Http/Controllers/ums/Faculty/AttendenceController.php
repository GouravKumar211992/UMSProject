<?php

<<<<<<< HEAD
namespace App\Http\Controllers\ums\Faculty;
=======
namespace app\Http\Controllers\UMS\Faculty;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

use View;
use Auth;
use App\User;
use App\Exports\AttendencesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

<<<<<<< HEAD
use App\Models\InternalMarksMapping;
use App\Models\InternalMark;
use App\Models\StudentSubject;
use App\Models\ums\Attendence;
use App\Models\AcademicSession;
=======
use App\Models\ums\InternalMarksMapping;
use App\Models\ums\InternalMark;
use App\Models\ums\StudentSubject;
use App\Models\ums\Attendence;
use App\Models\ums\AcademicSession;
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
use Validator;


class AttendenceController extends Controller
{

    public function index(Request $request)
    {
        // $user=Auth::guard('faculty')->user()->id;
         $attendence = Attendence::with(['faculty','course','semester'])->orderBy('id', 'DESC');

        if($request->search) {
            $keyword = $request->search;
            $attendence->where(function($q) use ($keyword){

                $q->where('roll_no', 'LIKE', '%'.$keyword.'%');
            });
            //dd($attendence);
        }
        if(!empty($request->search)){

            $attendence->where('roll_no','LIKE', '%'.$request->search.'%');
        }
        if (!empty($request->date_of_attendence)) {
           // dd($request->date_of_attendence);
            $attendence->where('date_of_attendence',$request->date_of_attendence);
           // dd($attendence->get());
        }
       if (!empty($request->roll_no)) {
           // dd($request->date_of_attendence);
            $attendence->where('roll_no',$request->roll_no);
<<<<<<< HEAD
           // dd($attendence->get());
        }
        $attendence=$attendence->paginate(10);

        // dd($attendence);
=======
        }
       
        // dd($attendence);
        $attendence=$attendence->paginate(10);
        
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    return view('ums.master.faculty.attendance',[
        'attendence'=>$attendence,

    ]);
    }
    public function add(Request $request)
    {
        $user=Auth::guard('faculty')->user();
        //dd($user);
           $data['sub_code']= $data['sub_name']= $data['date_of_semester']= $data['date_of_assign']= $data['assign_maximum']=$data['date_of_attendence']= $data['mapped_faculty']= $mapped_faculty =$data['mapped_Semesters']= $data['students'] = null;
            $students= [];
 $data['mapped_Subjects']=InternalMarksMapping::select('subjects.name','subjects.sub_code')
                ->join('subjects',function($join){
                    $join->on('subjects.sub_code','internal_marks_mappings.sub_code')
                    //->on('subjects.course_id','internal_marks_mappings.course_id')
                    ->on('subjects.semester_id','internal_marks_mappings.semester_id');
                })
//              ->join('subjects','subjects.sub_code','internal_marks_mappings.sub_code')
                ->distinct()
                ->orderBy('sub_code')
                ->where('faculty_id',$user->id)
                ->get();
                $data['mapped_Courses']=InternalMarksMapping::select('courses.name','courses.id','internal_marks_mappings.course_id')
        ->join('courses','courses.id','internal_marks_mappings.course_id')
        ->distinct()
        ->where('faculty_id',$user->id)
        ->get();
                $data['mapped_Semesters']=InternalMarksMapping::select('semesters.name','semesters.id','internal_marks_mappings.semester_id')
        ->join('semesters','semesters.id','internal_marks_mappings.semester_id')
        ->distinct()
        ->where('faculty_id',$user->id)
        ->where('internal_marks_mappings.course_id',$request->course)
        ->get();


        //dd($data['mapped_Semester']);
        $data['sessions']=AcademicSession::all();

        $msg=null;
        $subject=null;
         $duplicate_roll_no = Attendence::where('session',$request->session)->where('date_of_attendence',$request->date_of_attendence)->where('subject_code',$request->sub_code)->pluck('roll_no')->toArray();
        if($request->sub_code!=null)
        {   // dd($duplicate_roll_no);
            $students = StudentSubject::has('student')
                            ->select('enrollment_number', 'roll_number', 'session', 'program_id', 'course_id', 'semester_id', 'sub_code', 'sub_name')
                            ->where('sub_code',$request->sub_code)
                            ->whereNotIn('roll_number',$duplicate_roll_no)
                            ->distinct('enrollment_number', 'roll_number', 'session', 'program_id', 'course_id', 'semester_id', 'sub_code', 'sub_name')
                            ->orderBy('roll_number','asc')
                            ->paginate(40);
            //dd($students);
            //dd($students);
            $mapped_faculty=InternalMarksMapping::where('faculty_id',$user->id)
                                                ->where('sub_code',$request->sub_code)
                                                ->first();
        $data['students'] = $students;
        $data['mapped_faculty'] = $mapped_faculty;
        //dd($students);
        $data['date_of_attendence']=$request->date_of_attendence;
        }


        //dd($students);
       return view('faculty.attendence.add',$data);

    }
    public function addAttendence(Request $request)
    {
         $validator = Validator::make($request->all(),[
        'attendence.*' => 'required',

        ]);
          if ($validator->fails()) {
      return back()->withErrors($validator)->withInput($request->all());
    }
       $array_attendence = [];

     foreach($request->enrollment_number as $index=>$enrollment_no){

      $array_attendence[$index]['subject_code']=$request->sub_code[$index];
      $array_attendence[$index]['faculty_id']=$request->faculty_id[$index];
      $array_attendence[$index]['date_of_attendence']=$request->date_of_attendence[$index];
      $array_attendence[$index]['course_id']=$request->course_id[$index];
      $array_attendence[$index]['semester_id']=$request->semester_id[$index];
      $array_attendence[$index]['session']=$request->session[$index];
      $array_attendence[$index]['students_name']=$request->student_name[$index];
      $array_attendence[$index]['enrollment_no']=$request->enrollment_number[$index];
      $array_attendence[$index]['roll_no']=$request->roll_number[$index];
      $array_attendence[$index]['attendence_status']=$request->attendence[$index];
      //dd($attendence['attendence_status']);
      }
     Attendence::insert( $array_attendence);
      return redirect()->route('get-attendence')->with('success',"Attendence Added Successfully.");

    }
    public function searchAttendence(Request $request)
    {
<<<<<<< HEAD
        $sessionsList=Attendence::groupBy('session')->get()->pluck('session')->toArray();
        // dd($sessionsList);
        $searchAttendence=Attendence::where('session',$request->session)->where('date_of_attendence',$request->date_of_attendence)->paginate(10);
        //echo( $searchAttendence);
        return view('faculty.attendence.view',[
=======
        $sessionsList=Attendence::select('session')->groupBy('session')->get()->pluck('session')->toArray();
        // dd($sessionsList);
        $searchAttendence=Attendence::where('session',$request->session)->where('date_of_attendence',$request->date_of_attendence)->paginate(10);
        //echo( $searchAttendence);
        return view('ums.master.faculty.show_Attendance',[
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'searchAttendence'=>$searchAttendence,
            'sessionsList' => $sessionsList,
        ]);

    }
    public function attendenceExport(Request $request)
    {
        return Excel::download(new AttendencesExport($request), 'Attendances.xlsx');

    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
