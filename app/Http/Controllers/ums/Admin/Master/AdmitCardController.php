<?php

namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\ums\AdminController;

use App\Models\ums\Campuse;
use App\Models\ums\ExamFee;
use App\Models\ums\ExamCenter;
use App\Models\ums\Subject;
use App\Models\ums\AcademicSession;
use App\Models\ums\ExamForm;
use App\Models\ums\AdmitCard;
use App\Models\ums\Course;
use App\Models\ums\Category;
use App\Models\ums\Semester;
use App\Models\ums\MbbsExamForm;
use App\Models\ums\EntranceExam;
use App\Exports\AdmitCardExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Validator;
use App\Imports\AdmitcardImport;
use App\Models\ums\Enrollment;
use Carbon\Carbon;

class AdmitCardController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $examfee = ExamFee::whereId($request->id)->first();
        $AdmitCard = AdmitCard::where('exam_fees_id', $examfee->id)->first();
        $subjects = $examfee->getAdmitCardSubjects();
        $examCenters = ExamCenter::all();
        return view('ums.exam.addadmitcard', [
            'page_title' => "Admit Card",
            'sub_title' => "records",
            'examfee' => $examfee,
            'examCenters' => $examCenters,
            'subjects' => $subjects,
            'AdmitCard' => $AdmitCard,
        ]);
    }

    public function cardList(Request $request)
    {
        // dd($request->all());
        $cards = ExamFee::whereNotNull('enrollment_no')->orderBy('id', 'DESC');
        $campuse = Campuse::all();
        $programs = Category::all();
        $course = Course::all();
        $semesters = Semester::all();
        if ($request->search) {
            $keyword = $request->search;
            $cards->where(function ($q) use ($keyword) {
                $q->where('enrollment_no', 'LIKE', '%' . $keyword . '%');
                $q->orWhere('roll_no', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (!empty($request->name)) {
            $cards->where('enrollment_no', 'LIKE', '%' . $request->name . '%');
            $cards->orWhere('roll_no', 'LIKE', '%' . $request->name . '%');
        }
        if (!empty($request->campus)) {
            $cards->where('campuse_id', $request->campus);
        }
        if (!empty($request->course)) {
            $cards->where('course_id', $request->course);
        }
        // if (!empty($request->semester)) {
        //    $cards->where('semester_id',$request->semester);
        // }
        if (!empty($request->semester)) {
            $semester_ids = Semester::where('id', $request->semester)->pluck('id')->toArray();
            // dd($semester_ids);
            $cards->where('semester', $semester_ids);
        }
        // if (!empty($request->campus)) {
        //     $enrollment[]=null;
        //     $campus=Campuse::find($request->campus);
        //    //dd($campus);
        //     foreach($cards->get() as $key=> $card){
        //       if($campus->campus_code==campus_name($card->enrollment_no)){
        //      $enrollment[]=$card->enrollment_no;}
        //       }
        //     $cards->whereIn('enrollment_no',$enrollment);
        //       }

        $cards = $cards->paginate(10);
        $semester = Semester::select('name')->distinct()->get();
<<<<<<< HEAD
        return view('admin.master.admitcard.card-list', [
=======
        return view('ums.admitcard.admit_card_list', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Admit Card",
            'sub_title' => "records",
            'cards' => $cards,
            'campuselist' => $campuse,
            'programs' => $programs,
            'courses' => $course,
            'semesters' => $semesters,
        ]);
    }

    public function adminCardView(Request $request)
    {
        $enrollment_no = $request->id;
        $enrollment = Enrollment::where('enrollment_no', $enrollment_no)->first();
        $roll_no = $enrollment->roll_number;
        $campus_id = campus_name($enrollment_no);
        $examFee_old = ExamFee::withTrashed()->where(['roll_no' => $roll_no])->first();
        $AdmitCard = AdmitCard::where('enrollment_no', $enrollment_no)->orderBy('id', 'desc')->first();
        $examfee = ExamFee::whereId($AdmitCard->exam_fees_id)->first();

        $subjects = ExamForm::select('subjects.*', 'exam_schedules.date', 'exam_schedules.shift')
            ->join('subjects', function ($join) {
                $join->on('subjects.sub_code', 'exam_forms.sub_code')
                    ->on('subjects.course_id', 'exam_forms.course_id')
                    ->on('subjects.semester_id', 'exam_forms.semester');
            })
            ->leftJoin('exam_schedules', function ($join) {
                $join->on('exam_schedules.paper_code', 'subjects.sub_code')
                    ->on('exam_schedules.courses_id', 'subjects.course_id');
            })
            ->where('subjects.deleted_at', null)
            ->where('rollno', $examfee->roll_no)
            ->orderBy('date', 'asc')
            ->distinct()
            ->get();
        $subjects->each(function ($item, $key) {
            if ($item->date == null) {
                $item->date_order = 1;
            } else {
                $item->date_order = 2;
            }
        });
        $subjects = $subjects->sortByDesc('date_order');
        dd($subjects);
        if ($examfee->course_id == 49 || $examfee->course_id == 64) {
            $subjects = MbbsExamForm::select('subjects.*', 'exam_schedules.date', 'exam_schedules.shift')
                ->join('subjects', function ($join) {
                    $join->on('subjects.sub_code', 'mbbs_exam_forms.sub_code')
                        ->on('subjects.course_id', 'mbbs_exam_forms.course_id')
                        ->on('subjects.semester_id', 'mbbs_exam_forms.semester');
                })
                ->leftJoin('exam_schedules', function ($join) {
                    $join->on('exam_schedules.paper_code', 'subjects.sub_code')
                        ->on('exam_schedules.courses_id', 'subjects.course_id');
                })
                ->where('exam_schedules.deleted_at', null)
                ->where('subjects.deleted_at', null)
                ->where('rollno', $examfee->roll_no)
                ->orderBy('date', 'asc')
                ->distinct()
                ->get();
            $subjects->each(function ($item, $key) {
                if ($item->date == null) {
                    $item->date_order = 1;
                } else {
                    $item->date_order = 2;
                }
            });
            $subjects = $subjects->sortByDesc('date_order');
            //dd($subjects);
            return view('ums.exam.mbbs-admitcard-page', [
                'page_title' => "Admit Card",
                'sub_title' => "records",
                'examfee' => $examfee,
                'subjects' => $subjects,
                'AdmitCard' => $AdmitCard,
                'examFee_old' => $examFee_old,
            ]);
        }

<<<<<<< HEAD
        return view('ums.exam.', [
=======
        return view('ums.exam.addadmitcard', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => "Admit Card",
            'sub_title' => "records",
            'examfee' => $examfee,
            'subjects' => $subjects,
            'AdmitCard' => $AdmitCard,
            'examFee_old' => $examFee_old,
        ]);
    }

    public function adminCardApproval(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_fees_id' => 'required',
            'enrollment_no' => 'required',
            'roll_no' => 'required',
            'center_code' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }
        $exam_data = ExamFee::find($request->exam_fees_id);
        if (!$exam_data) {
            return back()->with('error', 'Exam ID is invalid.');
        }
        if ($exam_data->bank_name == null) {
            return back()->with('error', 'Payment is not submitted.');
        }
        $AdmitCard = AdmitCard::where('exam_fees_id', $request->exam_fees_id)->first();
        if ($AdmitCard) {
            return back()->with('error', 'Admit Card is Already Approved.');
        }
        $AdmitCard = new AdmitCard;
        $AdmitCard->exam_fees_id = $request->exam_fees_id;
        $AdmitCard->enrollment_no = $request->enrollment_no;
        $AdmitCard->roll_no = $request->roll_no;
        $AdmitCard->center_code = $request->center_code;
        $AdmitCard->save();
        return back()->with('success', 'Admit Card Approved.');
    }

    public function add(Request $request)
    {
        return view('admin.master.campus.addcampus', [
            'page_title' => "Add New",
            'sub_title' => "Campus",
        ]);
    }

    public function addCampus(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);
        $data = $request->all();

        $campuses = new Campuse();
        $campuses->fill($data);
        $campuses->save();
        return redirect()->route('get-campus');
    }

    public function editCampuse(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'type' => 'required',
        ]);
        $update_category = Campuse::where('id', $request->course_id)->update(['name' => $request->course_name, 'category_id' => $request->category_id, 'updated_by' => 1, 'course_description' => $request->course_description, 'campus_id' => $request->campus_id, 'color_code' => $request->color_code]);
        return redirect()->route('get-course');
    }


    public function editcourses(Request $request, $slug)
    {
        $selectedCampuse = Campuse::where('id', $slug)->first();
<<<<<<< HEAD
        return view('admin.master.course.editcourse', [
=======
        return view('ums.admitcard.admit_card_edit', [
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
            'page_title' => $campuses->name,
            'sub_title' => "Edit Information",
            'selected_campuse' => $campuses,
        ]);
    }

    public function softDelete(Request $request, $slug)
    {

        Campuse::where('id', $slug)->delete();
        return redirect()->route('get-course');
    }
    public function admitcardExport(Request $request)
    {
        //dd($request->all());
        return Excel::download(new AdmitCardExport($request), 'admitcard.xlsx');
    }

    public function deleteAdmitCard(Request $request, $exam_id)
    {
        $AdmitCard = AdmitCard::where('exam_fees_id', $exam_id)->first();
        $AdmitCard->delete();
        return back()->with('success', 'AdmitCard Stopped Succesfully');
    }

    public function bulkAdmitcard(Request $request)
    {
        return view('admin.cards.admitcard-bulk');
    }

    public function bulkAdmitcardSave(Request $request)
    {
        $request->validate([
            'admitcard_file' => 'required',
        ]);
        if ($request->hasFile('admitcard_file')) {
            //dd($request->all());
            $ec = Excel::import(new AdmitcardImport, $request->file('admitcard_file'));
            //dd($ec);
        }

        return back()->with('success', 'Records Saved!');
    }


    public function bulk_approve(Request $request)
    {
        $campuses = Campuse::all();
        if ($request->campus_id) {
            $courses = Course::where('campus_id', $request->campus_id)->get();
        } else {
            $courses = Course::all();
        }
        if ($request->course) {
            $semesters = Semester::where('course_id', $request->course)
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $semesters = [];
        }
        $sessions = AcademicSession::orderBy('id', 'asc')->get();
        $centers = ExamCenter::all();
        if ($request->paper_type && $request->course && $request->semester && $request->session && $request->center && $request->month && $request->year && $request->save_admit_card) {
            $this->bulk_approve_post($request);
        }
<<<<<<< HEAD
        return view('admin.master.admitcard.bulk-approve', ['campuses' => $campuses, 'courses' => $courses, 'semesters' => $semesters, 'sessions' => $sessions, 'centers' => $centers]);
=======
        return view('ums.admitcard.Bulk_Admit_Card_Approval', ['campuses' => $campuses, 'courses' => $courses, 'semesters' => $semesters, 'sessions' => $sessions, 'centers' => $centers]);
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
    }
    public function bulk_approve_post(Request $request)
    {
        $form_data_query = ExamFee::where('course_id', $request->course)
            ->where('academic_session', $request->session)
            ->where('form_type', $request->paper_type)
            ->whereNotNull('bank_name');
        if ($request->semester != 'ALL') {
            $form_data_query->where('semester', $request->semester);
        }
        $form_data = $form_data_query->get();
        // dd($form_data->count());
        $array_data = [];
        foreach ($form_data as $index => $form) {
            $AdmitCard = AdmitCard::where('exam_fees_id', $form->id)->first();
            if (!$AdmitCard) {
                $array_data[$index]['exam_fees_id']    = $form->id;
                $array_data[$index]['enrollment_no']    = $form->enrollment_no;
                $array_data[$index]['roll_no']    = $form->roll_no;
                $array_data[$index]['center_code']    = $request->center;
                $array_data[$index]['created_at']    = Carbon::now();
                $array_data[$index]['current_exam_session']    = $request->month . '-' . $request->year;
            }
        }
        if (count($array_data) > 0) {
            $approval = AdmitCard::insert($array_data);
            return back()->with('success', count($array_data) . ' admit cards are approved.');
        } else {
            return back()->with('success', 'All Admit Card Already Approved.');
        }
    }
    public function entrance_admitcard(Request $request)
    {
        //dd($request->all());
        $admitcard = EntranceExam::all();
        //dd($admitcard);
        return view('student.admitcard.admitcard-view', [
            'page_title' => "Admit Card",
            'sub_title' => "records",
            'AdmitCards' => $admitcard,
        ]);
    }
}
