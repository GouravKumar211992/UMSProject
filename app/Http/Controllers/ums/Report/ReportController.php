<?php

namespace App\Http\Controllers\ums\Report;

use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\Enrollment;
use App\Models\ums\ExamFee;
use App\Models\ums\Subject;
use App\Models\ums\Student;
use App\Models\ums\Phd2023EntranceTest;
use App\Models\ums\ExamForm;
use App\Models\ums\Campuse;
use App\Models\ums\Semester;
use App\Scrutiny;
use App\Models\ums\Course;
use App\Models\ums\Result;
use App\Models\ums\Category;
use App\Models\ums\AcademicSession;
use App\Models\ums\StudentAllFromOldAgency;
use App\Models\ums\StudentSubject;
use App\Models\ums\InternalMark;
use App\Models\ums\Application;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ums\ExportUser;
use App\Exports\ums\EnrollmentListExport;
use App\Models\ums\ExamType;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

  public function all_enrollment_list(Request $request)
  {
    $applications = Application::with(['categories', 'course', 'addresses', 'enrollment'])->where('status', 'Enrolled');
    if ($request->campuse_id) {
      $applications->where('campuse_id', $request->campuse_id);
    }
    $data['campuses'] = Campuse::all();
    $data['enrollments'] = $applications->paginate(10);
    return view('ums.reports.all_enrollment', $data);
  }
  public function enrollmentListExport(Request $request)
  {
    return Excel::download(new EnrollmentListExport($request), 'Enrollments.xlsx');
  }

  public function applicationReportList(Request $request)
  {
    $academic_session = '2024-2025';
    if ($request->academic_session) {
      $academic_session = $request->academic_session;
    }
    $campuses = Campuse::get();
    $courses = Course::where('campus_id', $request->type)
      ->where('category_id', $request->program)
      ->orderBy('name', 'ASC')
      ->get();
    $programs = Category::all();
    $user_ids = Application::select('*')
      ->where('user_id', '!=', 0)
      ->where('academic_session', $academic_session)
      ->where('payment_status', 1)
      ->orderby('campuse_id', 'ASC')
      ->orderby('course_id', 'ASC')
      ->get();
    // dd(count($user_ids));
    $cast_category = Application::where('user_id', '!=', 0)
      ->distinct('category')
      ->pluck('category')
      ->toArray();

    $Application = Application::select('*')
      ->where('user_id', '!=', 0)
      ->where('academic_session', $academic_session)
      ->where('payment_status', 1);
    $Application->orderBy('entrance_roll_number', 'ASC');

    if ($request->search) {
      $keyword = $request->search;
      $Application->where(function ($q) use ($keyword) {
        $q->where('first_Name', 'LIKE', '%' . $keyword . '%')
          ->orWhere('adhar_card_number', 'LIKE', '%' . $keyword . '%')
          ->orWhere('email', 'LIKE', '%' . $keyword . '%')
          ->orWhere('mobile', 'LIKE', '%' . $keyword . '%')
          ->orWhere('application_no', 'LIKE', '%' . $keyword . '%');
      });
    }
    if (!empty($request->type)) {
      $Application->where('campuse_id', $request->type);
    }
    if (!empty($request->course)) {
      $Application->where('course_id', $request->course);
    }
    if (!empty($request->campus)) {
      $Application->where('campuse_id', $request->campus);
    }
    if (!empty($request->filter_course)) {
      $Application->where('course_id', $request->filter_course);
    }
    if (!empty($request->form_date || $request->to_date)) {
      $to_date = date('Y-m-d', strtotime('+1 day', strtotime($request->to_date)));
      $Application->whereBetween('created_at', [$request->form_date, $to_date]);
    }
    if (!empty($request->disability_category)) {
      if ($request->disability_category == 'disabled') {
        $Application->whereNotNull('disability_category');
      }
      if ($request->disability_category == 'non_disabled') {
        $Application->whereNull('disability_category');
      }
    }
    if (!empty($request->category)) {
      $Application->where('category', $request->category);
    }
    $per_page = 10000;
    if ($request->remove_pagination != 'all') {
      $per_page = 10;
    }
    $Application = $Application->paginate($per_page);
    foreach ($Application as $Application_row) {
      $Application_row->equivalent_percentage = ($Application_row->latestEducationDetails) ? $Application_row->latestEducationDetails->equivalent_percentage : '';
    }
    $Application_sort = $Application;
    if ($request->education_order == 'ASC') {
      $Application_sort = $Application->sortBy('equivalent_percentage');
    }
    if ($request->education_order == 'DESC') {
      $Application_sort = $Application->sortByDesc('equivalent_percentage');
    }
    $current_page = 1;
    if ($request->page) {
      $current_page = $request->page;
    }
    return view('ums.reports.Application_Report', [
      'page_title' => "Campuse",
      'sub_title' => "records",
      'applications' => $Application,
      'Application_sort' => $Application_sort,
      'campuses' => $campuses,
      'programs' => $programs,
      'courses' => $courses,
      'cast_category' => $cast_category,
      'per_page' => $per_page,
      'current_page' => $current_page,
      'academic_session' => $academic_session,
    ]);
  }


  public function digiShaktiReport(Request $request)
  {
    $campuses = Campuse::get();
    $years = [
      '21' => '2021',
      '22' => '2022',
      '23' => '2023',
      '24' => '2024',
    ];
    $students = [];
    if ($request->campus_id && $request->submit_form) {
      $students = Enrollment::select('enrollments.*')
        ->join('courses', 'courses.id', 'enrollments.course_id')
        ->where('campus_id', $request->campus_id)
        ->Where('enrollments.roll_number', 'LIKE', $request->year . '%')
        ->distinct()
        ->get();
      }
    return view('ums.reports.digi_shakti_report', [
      'page_title' => "Digi Shakti Report",
      'sub_title' => "records",
      'campuses' => $campuses,
      'years' => $years,
      'students' => $students,
    ]);
  }

  public function exportUsers(Request $request)
  {
    return Excel::download(new ExportUser(), 'users.xlsx');
  }

  public function markFillingReport(Request $request)
  {
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus_id)->orderBy('name')->get();
    $semesters = Semester::withoutTrashed()->where('course_id', $request->course_id)->orderBy('id', 'asc')->get();
    $course = Course::find($request->course_id);
    $sessions = AcademicSession::all();
    $subjects = Subject::limit(10)->get();
    $subjects_query = Subject::select('subjects.*', 'semester_number')
      ->where('subjects.course_id', $request->course_id)
      ->join('semesters', 'semesters.id', 'subjects.semester_id');
    $subjects_clone = clone $subjects_query;
    if ($request->semester_type == 'odd') {
      $subjects_clone->whereIn('semester_number', [1, 3, 5, 7, 9, 11, 13]);
    }
    if ($request->semester_type == 'even') {
      $subjects_clone->whereIn('semester_number', [2, 4, 6, 8, 10, 12]);
    }
    $subjects_clone->orderBy('position', 'ASC');
    if ($request->course_id && $request->academic_session && $request->semester_type && $request->semester_id) {
      $subjects = $subjects_clone->where('semester_id', $request->semester_id)
        ->get();
    }
    if ($request->course_id && $request->academic_session && $request->semester_type && $request->semester_id == null) {
      $subjects = $subjects_clone->get();
    }
    return view('ums.reports.Mark_Filling_Report', compact(
      'campuses',
      'courses',
      'semesters',
      'sessions',
      'subjects',
      'course',
    ));
  }

  public function marksheetPositionUpdate(Request $request)
  {
    $batch = '-';
    if ($request->batch) {
      $batch = substr($request->batch, 2, 2);
    }
    $campuses = Campuse::orderBy('name')->get();
    $courses = Course::where('campus_id', $request->campus_id)->orderBy('name')->get();
    $semesters = Semester::where('course_id', $request->course_id)->orderBy('id', 'asc')->get();
    $results = Result::select('subject_code', 'subject_name')
      ->where('course_id', $request->course_id)
      ->where('semester', $request->semester_id)
      ->where('roll_no', 'LIKE', $batch . '%')
      ->distinct()
      ->orderBy('subject_position', 'ASC')
      ->get();
    return view('ums.reports.mark_sheet_position', compact(
      'campuses',
      'courses',
      'semesters',
      'results',
    ));
  }

  public function filledMarkDetails(Request $request)
  {
    $academic_session = $request->academic_session;
    $subject = Subject::where('semester_id', $request->semester_id)
      ->where('sub_code', $request->sub_code)
      ->first();
    $get_all = $subject->mark_filed_details($academic_session);
    $get_all_students = $get_all->get_all_students;

    $oral_max = implode(',', array_unique($get_all->internalMark_array->pluck('maximum_mark')->toArray()));
    $internal_max = implode(',', array_unique($get_all->internalMark_array->pluck('maximum_mark_assignment')->toArray()));
    $external_max = implode(',', array_unique($get_all->externalMark_array->pluck('maximum_mark')->toArray()));
    $practocal_max = implode(',', array_unique($get_all->practicalMark_array->pluck('maximum_mark')->toArray()));


    return view('report.filled-mark-details', compact(
      'subject',
      'get_all_students',
      'academic_session',
      'oral_max',
      'internal_max',
      'external_max',
      'practocal_max',
    ));
  }

  public function cgpaReport(Request $request)
  {
    $academic_session = $request->academic_session;
    $sessions = AcademicSession::orderBy('academic_session', 'DESC')->get();
    $selected_campus = Campuse::find($request->campus_id);
    $selected_course = Course::find($request->course_id);
    $selected_semester = Semester::find($request->semester_id);
    $campuses = Campuse::orderBy('name')->get();
    $courses = Course::where('campus_id', $request->campus_id)->orderBy('name')->get();
    $semesters = Semester::where('course_id', $request->course_id)->orderBy('semester_number', 'desc')->limit(1)->get();
    $results = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id', 'failed_semester_number', 'result_overall')
      ->where('semester', $request->semester_id)
      ->where('exam_session', $academic_session)
      ->where('result_type', 'new')
      ->where('status', '2')
      ->distinct()
      ->orderBy('roll_no')
      ->get();
      
    return view('ums.reports.cgpa_report', compact(
      'campuses',
      'courses',
      'semesters',
      'results',
      'selected_campus',
      'selected_course',
      'selected_semester',
      'sessions',
    ));
  }

  public function medalListCgpa(Request $request)
  {
    return redirect('cgpa_report');
  }

  public function trSummary(Request $request)
  {
    $examType = ExamType::where('exam_type','back_paper')->first();
    if ($examType) {
      $back_status_text = $examType->result_exam_type;
    } else {
      $back_status_text = null;
    }
    
    $results = Result::select('results.course_id', 'results.semester', 'exam_session', 'status', 'back_status_text')
      ->join('student_subjects', function ($query) use ($request) {
        $query->on('student_subjects.roll_number', 'results.roll_no')
          ->on('student_subjects.course_id', 'results.course_id')
          ->on('student_subjects.semester_id', 'results.semester')
          ->on('student_subjects.session', 'results.exam_session')
          ->where('student_subjects.type', $request->form_type);
      })

    //   ->groupBy('course_id', 'semester', 'status')      //  updated line//

    ->groupBy('results.course_id', 'results.semester', 'results.status', 'results.exam_session', 'results.back_status_text')  
    ->where('result_type', 'new')
    ->where('exam_session', $request->exam_session)
    ->where('back_status_text', $back_status_text)
    ->orderBy('course_id')
    ->orderBy('semester_number')
    ->orderBy('status')
    ->get();                       

    return view('ums.reports.tr_summary', compact('results'));

  }

  public function scrutinyReport(Request $request)
  {
    $from = $request->from;
    $to = $request->to;
    $data = Scrutiny::whereBetween('created_at', [$from, $to])
      ->whereNotNull('bank_name')
      ->where('form_type', 2)
      ->get();
    return view('report.scrutiny-report', compact(
      'data',
    ));
  }

  public function scholarshipReport(Request $request)
  {
    $campuses = Campuse::get();
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus_id)->orderBy('name')->get();
    $course_id = $request->course_id;
    $sessions = AcademicSession::withoutTrashed()->get();
    $semesters = Semester::where('course_id', $course_id)->get();
    $results = Result::select('roll_no', 'enrollment_no', 'course_id')
      ->where('exam_session', $request->academic_session)
      ->where('course_id', $course_id)
      ->where('result_type', 'new')
      ->where('back_status_text', 'REGULAR')
      ->orderBy('roll_no')
      ->distinct('roll_no', 'enrollment_no', 'course_id')
      ->get();
    return view('ums.reports.scholarship_report', compact(
      'campuses',
      'courses',
      'sessions',
      'results',
      'semesters',
    ));
  }
  public function scholarshipReport1(Request $request)
  {
    $campuses = Campuse::get();
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus_id)->orderBy('name')->get();
    $course_id = $request->course_id;
    $sessions = AcademicSession::withoutTrashed()->get();
    $semesters = Semester::where('course_id', $course_id)->get();
    $results = Result::select('roll_no', 'enrollment_no', 'course_id')
      ->where('exam_session', $request->academic_session)
      ->where('course_id', $course_id)
      ->where('result_type', 'new')
      ->where('back_status_text', 'REGULAR')
      ->orderBy('roll_no')
      ->distinct('roll_no', 'enrollment_no', 'course_id')
      ->get();
    return view('ums.reports.scholarship_report', compact(
      'campuses',
      'courses',
      'sessions',
      'results',
      'semesters',
    ));
  }

  public function disabilityReport(Request $request)
  {
    $campuses = Campuse::get();
    // $students = ExamForm::groupBy('rollno')        //new updated query//

    $students = ExamForm::groupBy('rollno', 'exam_forms.name', 'disabilty_category', 'courses.campus_id', 'exam_forms.course_id')
      ->withTrashed()->join('courses', 'courses.id', 'exam_forms.course_id')
      ->select('rollno', 'exam_forms.name', 'disabilty_category', 'courses.campus_id', 'exam_forms.course_id')
      ->where('courses.campus_id', $request->campus_id)
      ->get();

    return view('ums.reports.disability_report_list', compact(
      'campuses',
      'students',
    ));
  }

  public function meritListReport(Request $request)
  {
    $sessions = Result::select('exam_session')->distinct('exam_session')->orderByDesc('exam_session')->get();
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus_id)->orderBy('name')->get();
    $semesters = Semester::withoutTrashed()->where('course_id', $request->course_id)->orderBy('semester_number', 'ASC')->get();
    $results = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id', 'exam_session')
      ->where('exam_session', $request->session)
      ->where('semester', $request->semester_id)
      ->where('course_id', $request->course_id)
      ->where('result_type', 'new')
      ->where('result_overall', 'PASS')
      ->whereNull('failed_semester_number')
      ->distinct()
      ->orderBy('total_obtained_marks', 'DESC')
      ->orderBy('cgpa', 'DESC')
      ->get();
    return view('report.merit_list_report', compact(
      'campuses',
      'courses',
      'sessions',
      'results',
      'semesters',
    ));
  }
  public function degreeListReport(Request $request)
  {
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus_id)->orderBy('name')->get();
    $semesters = Semester::withoutTrashed()->where('course_id', $request->course_id)->orderBy('semester_number', 'ASC')->get();
    // For all coursers
    $result_type_wise = Result::where('back_status_text', $request->back_status_text)
      ->where('exam_session', $request->session)
      ->where('course_id', $request->course_id)
      ->where('back_status_text', $request->back_status_text)
      ->where('result_type', 'new')
      ->distinct()
      ->orderBy('roll_no', 'ASC')
      ->pluck('roll_no')
      ->toArray();
    $results_query = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id',  'result_type')
      ->where('course_id', $request->course_id)
      ->where('semester_final', 1);
    if ($request->back_status_text == 'SPLBACK' || $request->back_status_text == 'BACK') {
      $results_query->whereIn('roll_no', $result_type_wise);
    } else {
      $results_query->where('result_type', $request->result_type)
        ->where('exam_session', $request->session);
    }
    $results = $results_query->where('status', 2)
      ->where(function($query){
        $query->where('result_overall', 'PASS')
        ->orWhere('result_overall', 'PASSED')
        ->orWhere('result_overall', 'P');
      })
      ->distinct('roll_no')
      ->orderBy('roll_no', 'ASC')
      ->get();

      $sessions = ['2020-2021', '2021-2022', '2022-2023', '2023-2024' ,'2024-2025']; //added for sessions //
      // custome data


    // for MMBS
    // $results = Result::select('roll_no','enrollment_no','semester','course_id','exam_session','result_type')
    // ->whereIn('roll_no',['1701961003','1701961004','1701961006','1701961007','1701961008','1701961009','1701961011','1701961012','1701961014','1701961023','1701961033','1701961036','1701961038','1701961039','1701961040','1701961043','1701961044','1701961047','1701961051','1701961052','1701961055','1701961056','1701961060','1701961019','1701961030','1701961046'])
    // ->where('semester_final',1)
    // // ->where('result_overall','PASS')
    // // ->orWhere('result_overall','PASSED')
    // // ->orWhere('result_overall','P')
    // ->distinct()
    // ->orderBy('total_obtained_marks','DESC')
    // ->get();
    return view('ums.reports.degree_report_list', compact(
      'campuses',
      'courses',
      'results',
      'semesters',
      'sessions'
    ));
  }
  public function tabulationChart(Request $request){
    $campuses = Campuse::orderBy('name')->get();
    $courses = Course::where('campus_id', $request->campus_id)
    ->orderBy('name')
    ->get();
    $course_single = Course::find($request->course_id);
    $course_name = '';
    if($course_single){
      $course_name = $course_single->name;
    }
    $campus_single = Campuse::find($request->campus_id);
    $campus_name = '';
    if($campus_single){
      $campus_name = $campus_single->short_name;
    }

    // For all coursers
    $batchPrefixByBatch = batchPrefixByBatch($request->batch);
    $students = array();
    if($batchPrefixByBatch){
      $students = Student::join('enrollments','students.roll_number','enrollments.roll_number')
      ->join('results','results.roll_no','enrollments.roll_number')
      ->select('students.*','enrollments.course_id','is_lateral')
      ->where('enrollments.course_id', $request->course_id)
      ->where('semester_final', 1)
      ->where('students.roll_number','LIKE',$batchPrefixByBatch.'%')
      ->distinct()
      // ->where('students.roll_number', '202340001')
      ->get();
    }
    $semesters = Semester::where('course_id',$request->course_id)->orderBy('semester_number','asc')->get();

    $download_pdf = $request->download_pdf;
    $data['download'] = '';
    $data['campuses'] = $campuses;
    $data['courses'] = $courses;
    $data['semesters'] = $semesters;
    $data['students'] = $students;
    $data['course_name'] = $course_name;
    $data['campus_name'] = $campus_name;
    if($download_pdf=='true'){
      $data['download'] = 'pdf';
      $htmlfile = view('report.tabulation-chart-pdf-view', $data)->render();
      $pdf = app()->make('dompdf.wrapper');
      $pdf->loadHTML($htmlfile,'UTF-8')
			// ->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif'])
			->setWarnings(false)
      ->setPaper('a0', 'landscape');
      // dd($students);
      return $pdf->download('Tabular-Chat-'.$course_name.'-'.$campus_name.'-batch: '.$request->batch.'.pdf');
    }
  
    return view('report.tabulation-chart', $data);
  }
  public function digilockerList(Request $request)
  {
    $sessions = Result::select('exam_session')->distinct('exam_session')->orderByDesc('exam_session')->get();
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus_id)->orderBy('name')->get();
    $semesters = Semester::withoutTrashed()->where('course_id', $request->course_id)->orderBy('semester_number', 'ASC')->get();
    // For all coursers
    $results = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id', 'exam_session', 'result_type')
      ->where('exam_session', $request->session)
      ->where('course_id', $request->course_id)
      ->where('semester_final', 1)
      ->where('result_type', 'new')
      ->where('status', 2)
      ->where('result_overall', 'PASS')
      ->orWhere('result_overall', 'PASSED')
      ->orWhere('result_overall', 'P')
      ->distinct()
      ->orderBy('total_obtained_marks', 'DESC')
      ->get();
      // $sessions = ['2020-2021', '2021-2022', '2022-2023', '2023-2024' ,'2024-2025']; //added for sessions //

    // for MMBS
    // $results = Result::select('roll_no','enrollment_no','semester','course_id','exam_session')
    // ->whereIn('roll_no',['1601247001','1601247010','1601247011','1601247013','1601247016','1601247019','1601247020','1601247022','1601247029','1601247030','1601247031','1601247033','1601247042','1601247044','1601247048','1601247050','1601247057','1601247063','1601247065','1601247068','1601247078','1601247080','1601247088','1601247106','1601247115','1601247117','1601247118','1601247119','1601247128','1601247124','1601247126','1601247131','1601247132','1601247135','1601247140'])
    // ->where('exam_session',$request->session)
    // ->where('semester_final',1)
    // // ->where('result_overall','PASS')
    // // ->orWhere('result_overall','PASSED')
    // // ->orWhere('result_overall','P')
    // ->distinct()
    // ->orderBy('roll_no','ASC')
    // ->get();
    return view('ums.reports.digilocker_list', compact(
      'campuses',
      'courses',
      'sessions',
      'results',
      'semesters',
    ));
  }
  public function phdAttandanceReport(Request $request)
  {
    $subjects = Phd2023EntranceTest::distinct('subject')
      ->select('subject')
      ->orderBy('roll_number')
      ->get();
    return view('report.phdAttandanceReport', compact(
      'subjects',
    ));
  }
  public function regularExamReport(Request $request)
  {
    $sessions = Result::select('exam_session')->distinct('exam_session')->orderByDesc('exam_session')->get();
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus_id)->orderBy('name')->get();
    $semesters = Semester::withoutTrashed()->where('course_id', $request->course_id)->orderBy('semester_number', 'ASC')->get();
    if ($request->course_id == 64) {
      $roll_nos = DB::table('result_old_agency')
        ->select('roll_no')
        ->where('course_id', 51)
        ->whereIn('session', ['2016-2017', '2017-2018'])
        ->where('roll_no', 'LIKE', '16%')
        ->orWhere('roll_no', 'LIKE', '17%')
        ->distinct('roll_no')
        ->orderBy('roll_no', 'ASC')
        ->pluck('roll_no')
        ->toArray();
      $roll_nos = ['177320007', '177320008', '177320023', '177320019', '177320006', '177320009', '177320002', '177320005', '177320011', '177320013', '177320024'];
      $roll_nos = ['1701961002', '1701961005', '1701961010', '1701961013', '1701961016', '1701961018', '1701961020', '1701961021', '1701961022', '1701961024', '1701961025', '1701961027', '1701961028', '1701961029', '1701961031', '1701961032', '1701961034', '1701961037', '1701961042', '1701961048', '1701961050', '1701961053', '1701961057', '1701961058'];
      $results = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id', 'exam_session')
        ->whereIn('roll_no', $roll_nos)
        ->where('semester', 279)
        ->where('course_id', 64)
        ->orderBy('roll_no', 'ASC')
        ->distinct('roll_no')
        ->get();
      // dd($results);
    } elseif ($request->course_id == 96) {
      $roll_nos = DB::table('result_old_agency')
        ->select('roll_no')
        ->where('course_id', 74)
        ->where('session', '2019-2020')
        ->distinct('roll_no')
        ->orderBy('roll_no', 'ASC')
        ->pluck('roll_no')
        ->toArray();
      $results = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id', 'exam_session')
        ->whereIn('roll_no', $roll_nos)
        ->where('semester', 546)
        ->where('course_id', 96)
        ->orderBy('roll_no', 'ASC')
        ->distinct('roll_no')
        ->get();
      // dd($roll_nos);
    } elseif ($request->course_id == 49) {
      $roll_nos = DB::table('result_old_agency')
        ->select('roll_no')
        ->where('course_id', 39)
        ->where('session', '2016-2017')
        ->distinct('roll_no')
        ->orderBy('roll_no', 'ASC')
        ->pluck('roll_no')
        ->toArray();
      $results = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id', 'exam_session')
        ->whereIn('roll_no', $roll_nos)
        ->where('semester', 10)
        ->where('course_id', 49)
        ->orderBy('roll_no', 'ASC')
        ->distinct('roll_no')
        ->get();
    } else {
      $results = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id', 'exam_session')
        ->where('exam_session', $request->session)
        ->where('result_type', 'new')
        ->where('status', 2)
        ->where('result_overall', 'PASS')
        ->orWhere('result_overall', 'PASSED')
        ->distinct()
        ->orderBy('total_obtained_marks', 'DESC')
        ->orderBy('cgpa', 'DESC')
        ->get();
    }
    return view('report.degree_list_report', compact(
      'campuses',
      'courses',
      'sessions',
      'results',
      'semesters',
    ));
  }

  //all enrollment report
  public function chartForMaxMarks(Request $request)
  {
    $sessions = AcademicSession::all();
    $campuses = Campuse::orderBy('id')->get();
    $courses = Course::where('campus_id', $request->campus_id)->orderBy('name', 'ASC')->get();
    return view('ums.reports.chart_for_maximum_marks', compact(
      'campuses',
      'courses',
      'sessions',
    ));
  }

  public function resultAnalysis(Request $request)
  {
  }

  public function passedStudentReport(Request $request)
  {
    $sessions = AcademicSession::all();
    $campuses = Campuse::all();
    $results = null;
    $semesterArray = [2, 4, 6, 8, 10, 12];
    $semesterArrayYearly = [2, 3, 4, 5, 6];
    $courseYearly = [38];
    if ($request->submit_form == 'true' && $request->academic_session && $request->campus) {
      $results_semester = Result::join('courses', 'courses.id', 'results.course_id')
        ->select('campus_id', 'course_id', 'semester', 'roll_no', 'enrollment_no')
        ->where('exam_session', $request->academic_session)
        ->whereIn('semester_number', $semesterArray)
        ->whereNotIn('course_id', $courseYearly)
        ->where('campus_id', $request->campus)
        ->where('semester_final', 0)
        ->where('year_back', 0)
        ->where('result_type', 'new')
        ->orderBy('course_id', 'ASC')
        ->orderBy('roll_no', 'DESC')
        ->distinct();
      $results = Result::join('courses', 'courses.id', 'results.course_id')
        ->select('campus_id', 'course_id', 'semester', 'roll_no', 'enrollment_no')
        ->where('exam_session', $request->academic_session)
        ->whereIn('semester_number', $semesterArrayYearly)
        ->whereIn('course_id', $courseYearly)
        ->where('campus_id', $request->campus)
        ->where('semester_final', 0)
        ->where('year_back', 0)
        ->where('result_type', 'new')
        ->orderBy('course_id', 'ASC')
        ->orderBy('roll_no', 'DESC')
        ->distinct()
        ->union($results_semester)
        ->get();

      // dd($results);
    }
    return view('ums.reports.passed_student_report', compact(
      'sessions',
      'results',
      'campuses',
    ));
  }
  public function studyingStudents(Request $request)
  {
    $campuses = Campuse::get();
    $batch = ['2014-2015', '2015-2016', '2016-2017', '2017-2018', '2018-2019', '2019-2020', '2020-2021', '2021-2022', '2022-2023', '2023-2024'];

    $courses = Course::where('campus_id', $request->campus)
    ->whereNull('course_group')
    ->orderBy('name')
    ->get();
    return view('ums.reports.student_studying', compact(
      'courses',
      'campuses',
      'batch'
    ));
  }
  public function allstudyingStudents(Request $request)
  {
    $academic_session = AcademicSession::all();
    $campuses = Campuse::get();
    $courses = Course::where('campus_id', $request->campus)
    ->whereNull('course_group')
    ->orderBy('name')
    // ->where('id',1)
    ->get();
    return view('ums.reports.all_studying_student', compact(
      'academic_session',
      'courses',
      'campuses',
    ));
  }

  public function regularPaperReport(Request $request)
  {
    $semester_type = $request->semester_type;
    $academic_session = $request->academic_session;
    $course = Course::where('id',$request->course)->first();
    if (($semester_type == 'odd' && $request->academic_session) || ($course && $course->semester_type=='year')) {
      $academic_session_array = explode('-', $academic_session);
      $academic_year = $academic_session_array[0];
      $academic_session = ($academic_year - 1) . '-' . $academic_year;
    }
    $sessions = AcademicSession::all();
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $results = null;
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus)->orderBy('name')->get();
    //dd($courses);
    $semesters = Semester::withoutTrashed()->where('course_id', $request->course)->orderBy('semester_number', 'ASC')->get();
    $studentsNotFilled = collect();
    if ($request->submit_form == 'true' && $request->course && $request->academic_session) {
      // Get students who passed the previous semester and filled the form for the next semester

      $new_students1 = Enrollment::select('enrollment_no', 'roll_number as roll_no', 'enrollments.course_id', DB::raw('0 as year_back'), DB::raw('semesters.id as semester'), 'semester_number', DB::raw('1 as new_student'))
      ->join('semesters', 'semesters.course_id', 'enrollments.course_id')
      ->where('semester_number', 0);
      if($course && ($course->semester_type=='year' && $semester_type == 'odd') || $course->semester_type!='year') {
          $new_students1 = Enrollment::select('enrollment_no', 'roll_number as roll_no', 'enrollments.course_id', DB::raw('0 as year_back'), DB::raw('semesters.id as semester'), 'semester_number', DB::raw('1 as new_student'))
          ->join('semesters', 'semesters.course_id', 'enrollments.course_id')
          ->where('semester_number', 1)
          ->where('is_lateral', 0)
          ->where('enrollments.course_id', $request->course)
          ->where('roll_number', 'LIKE', substr($academic_session, -2) . '%')
          ->distinct('roll_no');
      }
      $new_students2 = Enrollment::select('enrollment_no', 'roll_number as roll_no', 'enrollments.course_id', DB::raw('0 as year_back'), DB::raw('semesters.id as semester'), 'semester_number', DB::raw('1 as new_student'))
        ->join('semesters', 'semesters.course_id', 'enrollments.course_id')
        ->where('semester_number', 3)
        ->where('is_lateral', 1)
        ->where('enrollments.course_id', $request->course)
        ->where('roll_number', 'LIKE', substr($academic_session, -2) . '%')
        ->distinct('roll_no');
      $results = Result::select('enrollment_no', 'roll_no', 'course_id', 'year_back', 'semester', 'semester_number', DB::raw('0 as new_student'))
        ->where('exam_session', $academic_session)
        ->where('course_id', $request->course)
        ->where('semester_final', 0)
        // ->where('year_back', 0)
        ->where('result_type', 'new')
        ->where('back_status_text', 'REGULAR')
        ->union($new_students1)
        ->union($new_students2)
        ->orderBy('roll_no', 'ASC')
        ->distinct();
      $results = $results->get();
    }
    return view('ums.reports.reqular_exam_form_list', compact(
      'sessions',
      'results',
      'campuses',
      'courses',
    ));
  }


  public function allregularPaperReport(Request $request)
  {
    $sessions = AcademicSession::all();
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $results = null;
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus)->orderBy('name')->get();
    $semesters = Semester::withoutTrashed()->where('course_id', $request->course)->orderBy('semester_number', 'ASC')->get();

    return view('report.all-regular-form-report', compact(
      'sessions',
      'results',

      'campuses',
      'courses',
    ));
  }


  //NIRF Report
  public function nirfreport(Request $request)
  {
    $campuses = Campuse::get();
    $courses = Course::where('campus_id', $request->campus)
      ->whereNull('course_group')
      ->orderBy('name')
      // ->limit(5)
      ->get();
    $academic_session = $request->academic_session;
    return view('report.nirf-report', compact(
      'academic_session',
      'campuses',
      'courses',
    ));
  }




  public function passOutStudentReport(Request $request)
  {
    // dd($request->all());

    $sessions = AcademicSession::all();
    if ($request->submit_form && $request->submit_form == 'true' && $request->academic_session) {
      $results = Result::select('roll_no', 'course_id', 'semester', 'enrollment_no')->where('exam_session', $request->academic_session)
        ->Where('result_overall', 'PASS')
        ->where('semester_final', 1)
        ->distinct('roll_no')
        ->get();
        dd($results);
    }
    $results = null;

    return view('ums.reports.pass_out_student_report', compact(
      'sessions',
      'results',
    ));
  }
  public function countEnrolledStudent(Request $request)
  {
    $sessions = AcademicSession::all();
    $campuses = Campuse::all();
    $results = null;
    $counts = null;
    $courses = Course::where('campus_id', $request->campus)->orderBy('name', 'ASC')->get();
    return view('ums.reports.Enrollment_Summary', compact(
      'sessions',
      'campuses',
      'courses',
    ));
  }

  public function batchPrefixByBatch($batch)
	{
		$batchPrefix = [];
		
		switch ($batch) {
			case '2020-2021':
				$batchPrefix = ['2020-2021', '2021-2022'];
				break;
			case '2021-2022':
				$batchPrefix = ['2021-2022', '2022-2023'];
				break;
			case '2022-2023':
				$batchPrefix = ['2022-2023', '2023-2024'];
				break;
			default:
				$batchPrefix = ['2013-2014', '2014-2015', '2015-2016'];
				break;
		}
		
		return $batchPrefix;
	}

  //all enrollment report
  public function allenrollreport(Request $request)
  {
    // dd($request);
    $campuses = Campuse::get();
    $courses = Course::where('campus_id',$request->campus)->get();
    $selected_course = Course::whereId($request->course_id)->first();
    $students = null;
    $sessions = ['2020-2021', '2021-2022', '2022-2023', '2023-2024' ,'2024-2025']; //added for sessions //

    if ($request->campus && $request->course_id && $request->batch && $request->submit_form == 'true') {
      $batchPrefixByBatch = $this->batchPrefixByBatch($request->batch);
      $students = Enrollment::where('course_id', $request->course_id)
      ->where('roll_number','LIKE', $batchPrefixByBatch.'%')
      ->get();
    }
    return view('ums.reports.Enrollment_report', [
      'campuses' => $campuses,
      'courses' => $courses,
      'selected_course' => $selected_course,
      'students' => $students,
      'sessions' => $sessions,
    ]);
  }

  public function mbbsBscNursingReport(Request $request)
  {
    $academic_session = AcademicSession::select('academic_session')
      ->distinct('academic_session')
      ->orderBy('academic_session', 'DESC')
      ->get();
    $form_type = ExamFee::select('form_type')
      ->distinct('form_type')
      ->orderBy('form_type', 'DESC')
      ->get();
    if ($request->form_type == 'regular') {
      $get_allowed_student_data = StudentAllFromOldAgency::where('regular_permission', 'Allowed')
        ->whereIn('course_id', [64, 49])
        ->orderBy('roll_no', 'ASC')
        ->get();
    } else {
      $get_allowed_student_data = StudentAllFromOldAgency::where('supplementary_permission', 'Allowed')
        ->whereIn('course_id', [64, 49])
        ->orderBy('roll_no', 'ASC')
        ->get();
    }
    return view('report.mbbs-bsc-nursing-exam-report', compact('academic_session', 'form_type', 'get_allowed_student_data'));
  }

  public function awardsheet(Request $request)
  {
    $academic_session = $request->academic_session;
    $academic_sessions = AcademicSession::get();
    $selected_campus = Campuse::withoutTrashed()->find($request->campus_id);
    $selected_course = Course::withoutTrashed()->find($request->course_id);
    $selected_semester = Semester::withoutTrashed()->find($request->semester_id);
    $campuses = Campuse::withoutTrashed()->orderBy('name')->get();
    $courses = Course::withoutTrashed()->where('campus_id', $request->campus_id)->orderBy('name')->get();
    $semesters = Semester::withoutTrashed()->where('course_id', $request->course_id)->orderBy('id', 'asc')->get();
    $subjects = Subject::withoutTrashed()->where('semester_id', $request->semester_id)->orderBy('id', 'asc')->get();
    $exam_types = ExamType::get();
    $batch = ['2014-2015','2015-2016','2016-2017','2017-2018','2018-2019','2019-2020','2020-2021','2021-2022','2022-2023','2023-2024'];
    //add batch() not work//
    $batchs = $request->batch;
    $duplicate_roll_no = InternalMark::where('session', $request->academic_session)
      ->where('sub_code', $request->sub_code)
      ->where('course_id', $request->course_id)
      ->where('semester_id', $request->semester_id)
      ->where('type', $request->exam_type)
      ->pluck('roll_number')
      ->toArray();
    $exams_query = $students_query = StudentSubject::select('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
      ->join('exam_fees','exam_fees.id','student_subjects.student_semester_fee_id')
      ->whereNotIn('student_subjects.roll_number', $duplicate_roll_no)
      ->where('student_subjects.sub_code', $request->sub_code)
      ->where('student_subjects.session', $request->academic_session)
      ->where('student_subjects.course_id', $request->course_id)
      ->where('student_subjects.semester_id', $request->semester_id)
      ->where('student_subjects.type', $request->exam_type)
      // ->whereYear('exam_fees.created_at', '2024')
      // ->where(function($query){
      //   $query->whereMonth('exam_fees.created_at', '08')
      //         ->orWhereMonth('exam_fees.created_at', '09');
      // })
      ->whereNull('exam_fees.deleted_at');
    if ($request->batch && in_array('All', $batchs) == false) {
      $exams_query->where(function ($query) use ($batchs) {
        foreach ($batchs as $batch) {
          $query->orWhere('roll_number', 'LIKE', $batch . '%');
        }
      });
    }
    $exams_query->distinct('student_subjects.enrollment_number', 'student_subjects.roll_number', 'student_subjects.session', 'student_subjects.program_id', 'student_subjects.course_id', 'student_subjects.semester_id', 'student_subjects.sub_code', 'student_subjects.sub_name')
      ->orderBy('student_subjects.roll_number', 'asc');
    $exams = $exams_query->get();
    return view('ums.reports.award_sheet_for_all', compact(
      'campuses',
      'courses',
      'semesters',
      'subjects',
      'academic_sessions',
      'exam_types',
      'exams',
      'selected_campus',
      'selected_course',
      'selected_semester',
      'batch'
    ));
  }


  public function dist_disability_students_report(Request $request)
{
  

  if ( $request->submit_form == 'true' && $request->academic_session && $request->campus) {
    $district_wise_counts = [];
    $academic_session = AcademicSession::get();
    $campus = Campuse::get();
    $district_wise_counts = Student::select('students*')
    ->whereNotNull('students.disabilty_category')
    ->where('enrollments.academic_session', $request->academic_session)
    ->where('campuses.id', $request->campus)
    ->selectRaw('IFNULL(students.district, "District Missing") as district')
    ->selectRaw('COUNT(enrollments.roll_number) as disabled_student_count')
    ->groupBy('students.district')
    ->get();


  }


  return view('report.dist_disability-report', compact(
      'campuses',
      'academic_session',
      'district_wise_counts'
  ));
}
 public function ashireport(Request $request){
    $campuses = Campuse::all();
    $religions = Student::select('religion')->distinct()->pluck('religion')->toArray();
    $disabiltys = ['Not Any'];
    $reports = [];
    $report_array = [
      'type'=>0,
      'total_category'=>0,
      'na_category'=>0,
      'general_male'=>0,
      'general_female'=>0,
      'general_trasgender'=>0,
      'ews_male'=>0,
      'ews_female'=>0,
      'ews_trasgender'=>0,
      'sc_male'=>0,
      'sc_female'=>0,
      'sc_trasgender'=>0,
      'st_male'=>0,
      'st_female'=>0,
      'st_trasgender'=>0,
      'obc_male'=>0,
      'obc_female'=>0,
      'obc_trasgender'=>0,
    ];
    foreach($religions as $religion_row){
      $reports[] = $report_array;
    }
    $reports[50] = $report_array;

    $course_array = array();
    $student_array = array();
    $courses = Course::where('campus_id',$request->campus_id)
    ->whereNotIn('id',[17,37,88,94,97,100,101,115,124,125])
    ->get();

    // // Code for Type 1
    // if($request->type==1){
    //   foreach($courses as $course){
    //     $semester = Semester::where('course_id',$course->id)
    //     ->orderBy('semester_number','DESC')
    //     ->offset(1)
    //     ->limit(1)
    //     ->first();
    //     if($semester){
    //       $student_list = Enrollment::select('roll_number')
    //       ->where('course_id',$semester->course_id)
    //       ->where('current_semester',$semester->semester_number)
    //       ->where('is_student_studing','Yes')
    //       ->distinct('roll_number')
    //       ->pluck('roll_number')
    //       ->toArray();
    //       $course_array[$course->name] = $this->ashireport_function($religions,$disabiltys,$student_list,$reports);
    //     }
    //   }
    // }

    // Code for Type 2
    if($request->type==1 ||  $request->type==2 || $request->type==3){
      foreach($courses as $course){
        $semester = Semester::where('course_id',$course->id)
        ->orderBy('semester_number','DESC')
        ->first();
        if($semester){
          $result_array = [];
          $results = Result::select('roll_no', 'enrollment_no', 'semester', 'course_id', 'failed_semester_number', 'result_overall')
          ->where('semester', $semester->id)
          ->where('exam_session', '2022-2023')
          // ->where('result_overall', 'PASS')
          ->where('result_type', 'new')
          ->distinct()
          ->orderBy('roll_no')
          ->get();
          foreach($results as $result){
            if($request->type==1){
              $result_array[] = $result->roll_no;
            }
            if($request->type==2 && $result->result_overall=='PASS'){
              $result_array[] = $result->roll_no;
            }
            if($request->type==3 && $result->result_overall=='PASS'){
              $percentage = (($result->get_semester_result_single()->total_obtained_marks * 100) / $result->get_semester_result_single()->total_required_marks);
              if($percentage>=60){
                $result_array[] = $result->roll_no;
              }
            }

            
          }
          $student_list = array_unique($result_array);
          $course_array[$course->name] = $this->ashireport_function($religions,$disabiltys,$student_list,$reports);
        }
      }
    }


    return view('report.ashireport',compact('campuses','course_array'));
 }

 public function ashireport_function($religions,$disabiltys,$student_array,$reports){
  $student_array = array_unique($student_array);
  $students = Student::whereIn('roll_number',$student_array)->get();
  foreach($religions as $idex=>$religion){
    foreach($students as $student){
      $reports[$idex]['type'] = $religion;
      if($student->religion==$religion){
        $reports[$idex]['total_category'] = $reports[$idex]['total_category'] + 1;
      }
      if($student->category==null && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['na_category'] = $reports[$idex]['na_category'] + 1;
      }

      if($student->category=='General' && $student->gender=='MALE' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['general_male'] = $reports[$idex]['general_male'] + 1;
      }
      if($student->category=='General' && $student->gender=='FEMALE' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['general_female'] = $reports[$idex]['general_female'] + 1;
      }
      if($student->category=='General' && $student->gender=='OTHERS' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['general_trasgender'] = $reports[$idex]['general_trasgender'] + 1;
      }

      if(($student->gender=='MALE' && $student->religion==$religion) && ($student->category=='EWS' || $student->ews_status =='Yes')){
        $reports[$idex]['ews_male'] = $reports[$idex]['ews_male'] + 1;
      }
      if(($student->gender=='FEMALE' && $student->religion==$religion) && ($student->category=='EWS' || $student->ews_status =='Yes')){
        $reports[$idex]['ews_female'] = $reports[$idex]['ews_female'] + 1;
      }
      if(($student->gender=='OTHERS' && $student->religion==$religion) && ($student->category=='EWS' || $student->ews_status =='Yes')){
        $reports[$idex]['ews_trasgender'] = $reports[$idex]['ews_trasgender'] + 1;
      }

      if($student->category=='SC' && $student->gender=='MALE' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['sc_male'] = $reports[$idex]['sc_male'] + 1;
      }
      if($student->category=='SC' && $student->gender=='FEMALE' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['sc_female'] = $reports[$idex]['sc_female'] + 1;
      }
      if($student->category=='SC' && $student->gender=='OTHERS' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['sc_trasgender'] = $reports[$idex]['sc_trasgender'] + 1;
      }

      if($student->category=='ST' && $student->gender=='MALE' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['st_male'] = $reports[$idex]['st_male'] + 1;
      }
      if($student->category=='ST' && $student->gender=='FEMALE' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['st_female'] = $reports[$idex]['st_female'] + 1;
      }
      if($student->category=='ST' && $student->gender=='OTHERS' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['st_trasgender'] = $reports[$idex]['st_trasgender'] + 1;
      }

      if($student->category=='OBC' && $student->gender=='MALE' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['obc_male'] = $reports[$idex]['obc_male'] + 1;
      }
      if($student->category=='OBC' && $student->gender=='FEMALE' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['obc_female'] = $reports[$idex]['obc_female'] + 1;
      }
      if($student->category=='OBC' && $student->gender=='OTHERS' && $student->ews_status !='Yes' && $student->religion==$religion){
        $reports[$idex]['obc_trasgender'] = $reports[$idex]['obc_trasgender'] + 1;
      }
    }
    // $reports[] = $report;
  }
  foreach($disabiltys as $idex=>$disabilty_category){
    $idex = 50;
    foreach($students as $student){
      $reports[$idex]['type'] = $disabilty_category;
      if($student->disabilty_category!=$disabilty_category){
        $reports[$idex]['total_category'] = $reports[$idex]['total_category'] + 1;
      }
      if($student->category==null && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['na_category'] = $reports[$idex]['na_category'] + 1;
      }

      if($student->category=='General' && $student->gender=='MALE' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['general_male'] = $reports[$idex]['general_male'] + 1;
      }
      if($student->category=='General' && $student->gender=='FEMALE' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['general_female'] = $reports[$idex]['general_female'] + 1;
      }
      if($student->category=='General' && $student->gender=='OTHERS' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['general_trasgender'] = $reports[$idex]['general_trasgender'] + 1;
      }

      if(($student->gender=='MALE' && $student->disabilty_category!=$disabilty_category) && ($student->category=='EWS' || $student->ews_status =='Yes')){
        $reports[$idex]['ews_male'] = $reports[$idex]['ews_male'] + 1;
      }
      if(($student->gender=='FEMALE' && $student->disabilty_category!=$disabilty_category) && ($student->category=='EWS' || $student->ews_status =='Yes')){
        $reports[$idex]['ews_female'] = $reports[$idex]['ews_female'] + 1;
      }
      if(($student->gender=='OTHERS' && $student->disabilty_category!=$disabilty_category) && ($student->category=='EWS' || $student->ews_status =='Yes')){
        $reports[$idex]['ews_trasgender'] = $reports[$idex]['ews_trasgender'] + 1;
      }

      if($student->category=='SC' && $student->gender=='MALE' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['sc_male'] = $reports[$idex]['sc_male'] + 1;
      }
      if($student->category=='SC' && $student->gender=='FEMALE' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['sc_female'] = $reports[$idex]['sc_female'] + 1;
      }
      if($student->category=='SC' && $student->gender=='OTHERS' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['sc_trasgender'] = $reports[$idex]['sc_trasgender'] + 1;
      }

      if($student->category=='ST' && $student->gender=='MALE' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['st_male'] = $reports[$idex]['st_male'] + 1;
      }
      if($student->category=='ST' && $student->gender=='FEMALE' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['st_female'] = $reports[$idex]['st_female'] + 1;
      }
      if($student->category=='ST' && $student->gender=='OTHERS' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['st_trasgender'] = $reports[$idex]['st_trasgender'] + 1;
      }

      if($student->category=='OBC' && $student->gender=='MALE' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['obc_male'] = $reports[$idex]['obc_male'] + 1;
      }
      if($student->category=='OBC' && $student->gender=='FEMALE' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['obc_female'] = $reports[$idex]['obc_female'] + 1;
      }
      if($student->category=='OBC' && $student->gender=='OTHERS' && $student->ews_status !='Yes' && $student->disabilty_category!=$disabilty_category){
        $reports[$idex]['obc_trasgender'] = $reports[$idex]['obc_trasgender'] + 1;
      }
    }
    // $reports[] = $report;
  }
  return $reports;
}

public function examWisePassoutStuents(Request $request){
  $campuses = Campuse::all();
  $singleCampuse = Campuse::find($request->campus_id);
  $sessions = AcademicSession::all();
  return view('report.exam-wise-passout-stuents',compact('campuses','singleCampuse','sessions','download_pdf'));
}

}
