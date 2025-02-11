<?php

namespace App\Http\Controllers\ums\Admin\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ums\Faculty;
use App\Models\ums\Campuse;
use Validator;
use App\Exports\FacultyExport;
use App\Models\AcademicSession;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InternalMarksMapping;
use App\Models\ExternalMark;
use App\Models\InternalMark;
use App\Models\PracticalMark;
use App\Models\StudentSubject;
use Hash;


class FacultyController extends Controller
{
  public function index(Request $request)
  { 
    // echo"hello";
   $data = Faculty::orderBy('id', 'DESC');
        if($request->search) {
            $keyword = $request->search;
            $data->where(function($q) use ($keyword){
                $q->where('name', 'LIKE', '%'.$keyword.'%');
                $q->orWhere('email', 'LIKE', '%'.$keyword.'%');
            });
        }
        if(!empty($request->name)){
            
            $data->where('name','LIKE', '%'.$request->name.'%');
            $data->orWhere('email','LIKE', '%'.$request->name.'%');
        }
        $data=$data->paginate(10);
        // dd($data);
    return view('ums.master.faculty',['items'=>$data]);
  }

  public function add_faculty(Request $request){
    $campus=Campuse::all();
    return view('admin.master.faculty.add-faculty',['campus'=>$campus]);
  }


  public function add(Request $request)
  { 
    
     $validator = Validator::make($request->all(),[
    'campus'             => 'required',
    'name'             => 'required',
    'password'         => 'required',
    'email'            => 'required|email',
    'mobile'           => 'required|numeric|digits:10',
    'date_of_birth'    => 'required',
    'gender'           => 'required',
    'marital_status'   => 'required',
    'father_name'   => 'required',
    'aadhar_number'   => 'required',
    'pan_number'   => 'required',
    'department_name'   => 'required',
    
    'designation'=>'required',
    'bank_account_number'   => 'required',
    'ifsc_code'   => 'required',
    /* 
    'research_paper_type'=>'required',
    'number_of_research_paper'=>'required',
    
    'high_school_marksheet'   => 'required',
    'inter_marksheet'   => 'required',
    'graduation_marksheet'   => 'required',
     'high_school_degree'   => 'required',
    'inter_degree'   => 'required',
    'graduation_degree'   => 'required',
    'photo'=>'required',
    */
    ]);
    
     if ($validator->fails()) {    
      return back()->withErrors($validator)->withInput($request->all());
    }
    

    $data= new Faculty;
     $data->campuse_id=$request->campus;
     $data->name=$request->name;
     $data->user_name=explode('@',$request->email)[0];
     $data->password=$request->password;
   	 $data->email=$request->email;
   	 $data->mobile=$request->mobile;
   	 $data->date_of_birth=$request->date_of_birth;
   	 $data->gender=$request->gender;
   	 $data->marital_status=$request->marital_status;  

     $data->department_name=$request->department_name;
     $data->designation=$request->designation;

     $data->father_name=$request->father_name;
     $data->aadhar_number=$request->aadhar_number;
     $data->pan_number=$request->pan_number;
     $data->bank_account_number=$request->bank_account_number;
     $data->ifsc_code=$request->ifsc_code;

     if($request->high_school_marksheet){
      $data->addMediaFromRequest('high_school_marksheet')->toMediaCollection('high_school_marksheet');
    }

    if($request->inter_marksheet){
      $data->addMediaFromRequest('inter_marksheet')->toMediaCollection('inter_marksheet');
    }
    if($request->graduation_marksheet){
      $data->addMediaFromRequest('graduation_marksheet')->toMediaCollection('graduation_marksheet');
    }
    if($request->post_graduation_marksheet){
      $data->addMediaFromRequest('post_graduation_marksheet')->toMediaCollection('post_graduation_marksheet');

    }
    if ($request->post_graduation_marksheet==null) {
      $data->post_graduation_marksheet='NA';
    }

    if($request->high_school_degree){
      $data->addMediaFromRequest('high_school_degree')->toMediaCollection('high_school_degree');
    }


    if($request->inter_degree){
      $data->addMediaFromRequest('inter_degree')->toMediaCollection('inter_degree');
    }
    if($request->graduation_degree){
      $data->addMediaFromRequest('graduation_degree')->toMediaCollection('graduation_degree');
    }
    if($request->post_graduation_degree){
      $data->addMediaFromRequest('post_graduation_degree')->toMediaCollection('post_graduation_degree');
    }
    if ($request->post_graduation_degree==null) {
      $data->post_graduation_degree='NA';
    }

    if($request->phd_marksheet){
      $data->addMediaFromRequest('phd_marksheet')->toMediaCollection('phd_marksheet');
    }
    if ($request->phd_marksheet==null) {
      $data->phd_marksheet='NA';
    }

    if($request->photo){
      $data->addMediaFromRequest('photo')->toMediaCollection('photo');
    }
    if($request->mphil_marksheet)
    {
      $data->addMediaFromRequest('mphil_marksheet')->toMediaCollection('mphil_marksheet');
    } 
    if($request->mphil_degree)
    {
      $data->addMediaFromRequest('mphil_degree')->toMediaCollection('mphil_degree');
    }

    $data->research_paper_type = $request->research_paper_type;
    $data->number_of_research_paper = $request->number_of_research_paper;

    $data->save();
   	 return redirect()->route('faculty')->with('success','faculty Added Successfully.');
   }
    public function edit($id)
   {
   	  $data=Faculty::find($id);
   	 return view('admin.master.faculty.edit-faculty',['info'=>$data]);
   }
    public function delete($id)
   {
   	    $data=Faculty::find($id);
        $data->delete();
       
     return redirect()->route('faculty')->with('success','faculty Data Deleted Successfully.');
    
   }
     public function update(Request $request,$id)
   {

   	$update=Faculty::find($id);
    $update->name=$request->get('name');
    $update->mobile=$request->get('mobile');
    $update->email=$request->get('email');
    $update->date_of_birth=$request->get('date_of_birth');
    $update->gender=$request->get('gender');
    $update->marital_status=$request->get('marital_status');

    $update->department_name=$request->get('department_name');
    $update->designation=$request->get('designation');

    $update->father_name=$request->get('father_name');
    $update->aadhar_number=$request->get('aadhar_number');
    $update->pan_number=$request->get('pan_number');
    $update->bank_account_number=$request->get('bank_account_number');
    $update->ifsc_code=$request->get('ifsc_code');


     if ($request->high_school_marksheet) 
        {
          $update->addMediaFromRequest('high_school_marksheet')->toMediaCollection('high_school_marksheet');
        }
        
        if($request->inter_marksheet){
      $update->addMediaFromRequest('inter_marksheet')->toMediaCollection('inter_marksheet');
    }
    if($request->graduation_marksheet){
      $update->addMediaFromRequest('graduation_marksheet')->toMediaCollection('graduation_marksheet');
    }
    if($request->post_graduation_marksheet){
      $update->addMediaFromRequest('post_graduation_marksheet')->toMediaCollection('post_graduation_marksheet');

    }
    
    if($request->high_school_degree){
      $update->addMediaFromRequest('high_school_degree')->toMediaCollection('high_school_degree');
    }


    if($request->inter_degree){
      $update->addMediaFromRequest('inter_degree')->toMediaCollection('inter_degree');
    }
    if($request->graduation_degree){
      $update->addMediaFromRequest('graduation_degree')->toMediaCollection('graduation_degree');
    }
    if($request->post_graduation_degree){
      $update->addMediaFromRequest('post_graduation_degree')->toMediaCollection('post_graduation_degree');
    }
   
    if($request->phd_marksheet){
      $update->addMediaFromRequest('phd_marksheet')->toMediaCollection('phd_marksheet');
    }
    
    if($request->photo){
      $update->addMediaFromRequest('photo')->toMediaCollection('photo');
    }

    if($request->mphil_marksheet)
    {
      $update->addMediaFromRequest('mphil_marksheet')->toMediaCollection('mphil_marksheet');
    } 
    if($request->mphil_degree)
    {
      $update->addMediaFromRequest('mphil_degree')->toMediaCollection('mphil_degree');
    }
     //$update->high_school_marksheet=$request->get()->file('high_school_marksheet')->store('uploaded-files');
     //$update->inter_marksheet=$request->get()->file('inter_marksheet')->store('uploaded-files');
     //$update->graduation_marksheet=$request->get()->file('graduation_marksheet')->store('uploaded-files');
     //$update->post_graduation_marksheet=$request->get()->file('post_graduation_marksheet')->store('uploaded-files');

      //$update->high_school_degree=$request->get()->file('high_school_degree')->store('uploaded-files');
     //$update->inter_degree=$request->get()->file('inter_degree')->store('uploaded-files');
     //$update->graduation_degree=$request->get()->file('graduation_degree')->store('uploaded-files');
     //$update->post_graduation_degree=$request->get()->file('post_graduation_degree')->store('uploaded-files');

     // $update->phd_marksheet=$request->file('phd_marksheet')get()->store('uploaded-files');
     // $update->photo=$request->file('photo')get()->store('uploaded-files');

     $update->research_paper_type=$request->get('research_paper_type');
     $update->number_of_research_paper=$request->get('number_of_research_paper');

       $update->save();

        return redirect()->route('faculty')->with('success','faculty Updated Successfully.');
   	 
   }

   public function facultyExport(Request $request) 
  {
    return Excel::download(new FacultyExport($request), 'faculty.xlsx');
  }
 
//  public function allfacultyreport(Request $request){

//   $user=Auth::guard('faculty')->user()->id;
//   $internal_mapped_papers=InternalMarksMapping::where('faculty_id',$user)
// 							->orderBy('id', 'DESC')->get();
//   $external_mapped_papers=ExternalMark::where('faculty_id',$user)
// 							->orderBy('id', 'DESC')->get();
//   $practical_mapped_papers=PracticalMark::where('faculty_id',$user)
// 							->orderBy('id', 'DESC')->get();
//               $student=StudentSubject::join("internal_marks_mappings",function($join){
//                 $join->on("internal_marks_mappings.sub_code","=","student_subjects.sub_code")
//                     ->on("internal_marks_mappings.course_id","=","student_subjects.course_id")
//                     ->on("internal_marks_mappings.semester_id","=","student_subjects.semester_id");
//               })
//               ->select('student_subjects.roll_number')
//               ->distinct()
//               ->where('internal_marks_mappings.faculty_id',$user)
//               ->where('student_subjects.session',$request->session);
//               $duplicate_roll_no_internal = InternalMark::where(['faculty_id'=>$user,'session'=>$request->session])
// 				->pluck('roll_number')
// 				->toArray();
//         $duplicate_roll_no_external = ExternalMark::where(['faculty_id'=>$user,'session'=>$request->session])
// 				->pluck('roll_number')
// 				->toArray();
//         $duplicate_roll_no_practical = PracticalMark::where(['faculty_id'=>$user,'session'=>$request->session])
// 				->pluck('roll_number')
// 				->toArray();
//   $session=AcademicSession::get();
//   $internal_marks_filled=InternalMark::where(['faculty_id'=>$user,'session'=>$request->session])
// 			->get();
//       $external_marks_filled=ExternalMark::where(['faculty_id'=>$user,'session'=>$request->session])
// 			->get();
//       $practical_marks_filled=PracticalMark::where(['faculty_id'=>$user,'session'=>$request->session])
// 			->get();
//       $internal_paper_code=$internal_mapped_papers->pluck('sub_code')->toArray();
//       $external_paper_code=$external_mapped_papers->pluck('sub_code')->toArray();
//       $practical_paper_code=$practical_mapped_papers->pluck('sub_code')->toArray();
//       $datas['internal_paper_code']=count($internal_paper_code);
//       $datas['external_paper_code']=count($external_paper_code);
//       $datas['practical_paper_code']=count($practical_paper_code);
//       $datas['sessions']=$session;
//       $datas['internal_marks_filled']=count($internal_marks_filled);
//       $datas['external_marks_filled']=count($external_marks_filled);
//       $datas['practical_marks_filled']=count($practical_marks_filled);
//       $internal_pending = $student->whereNotIn('roll_number',$duplicate_roll_no_internal);
//       $external_pending = $student->whereNotIn('roll_number',$duplicate_roll_no_external);
//       $practical_pending = $student->whereNotIn('roll_number',$duplicate_roll_no_practical);
//       $data['internal_pending']=count($internal_pending ->get());
//       $data['external_pending']=count($external_pending ->get());
//       $data['practical_pending']=count($practical_pending ->get());

//   $data = Faculty::orderBy('id', 'DESC');
//   if($request->search) {
//       $keyword = $request->search;
//       $data->where(function($q) use ($keyword){
//           $q->where('name', 'LIKE', '%'.$keyword.'%');
//           $q->orWhere('email', 'LIKE', '%'.$keyword.'%');
//       });
//   }
//   if(!empty($request->name)){
      
//       $data->where('name','LIKE', '%'.$request->name.'%');
//       $data->orWhere('email','LIKE', '%'.$request->name.'%');
//   }

// $data=$data->paginate(10);
// return view('admin.master.faculty.allfaculty',['items'=>$data]);
//  }

 
  public function setDefaultPasswordFaculty($id){
    $faculty = Faculty::find($id);
    $faculty->password = 'dsmnru@123';
    $faculty->save();
    return back()->with('success','Password reset successfully');
  }


}
