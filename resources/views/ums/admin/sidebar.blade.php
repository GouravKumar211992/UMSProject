<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow erpnewsidemenu" data-scroll-to-active="true">

    <div class="shadow-bottom"></div>
    <div class="main-menu-content newmodulleftmenu">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item"><a class="d-flex align-items-center" href="dashboard"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    <span class="menu-title text-truncate"
                        >Dashboard</span></a>
            </li>
            <li class="nav-item">
                <a class="d-flex align-items-center" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span class="menu-title text-truncate">User Management</span>
                </a>
                <ul class="menu-content">
                    <li><a class="d-flex align-items-center" href="{{ url('/admins') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Admin List</span></a></li>
                    <li><a class="d-flex align-items-center" href="{{ url('/users') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">User List</span></a></li>
                    <li><a class="d-flex align-items-center" href="{{ url('/students') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Student List</span></a></li>
                    <li><a class="d-flex align-items-center" href="{{ url('/student-hindi-name') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Student Name (Hindi/English)</span></a></li>
                    <li><a class="d-flex align-items-center" href="{{ url('#') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Enrollment List</span></a></li>
                    <li><a class="d-flex align-items-center" href="{{ url('/email-template') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Email Template</span></a></li>
                </ul>
            </li>
            
       {{-- master start  --}}
            <li class="nav-item">
                <a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
              <span class="menu-title text-truncate" >
                Master</span></a>
        <ul class="menu-content">
            <li><a class="d-flex align-items-center" href="{{url('campus_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Campus List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('category_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Category List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('course_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Course List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('stream_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Stream List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('fees_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Fee List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('semester_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Samester List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('period_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Period List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('subject_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Subject List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('shift_list')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Shift List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('notification')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Notifiction</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('phd_entrance_exam')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Ph.d Entrance Exam</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('entrance_exam')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate"> Entrance Exam Schedule </span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('exam_center')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate"> Exam Center</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('faculty')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Faculty</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('department_faculty')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Department-Faculty</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('department')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Department</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('affiliate_circular')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Affiliate-Circular</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('question_bank')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Question-Bank</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('holiday_calender')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Holiday Calender</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{url('old_grading')}}"><i data-feather="circle"></i><span
                        class="menu-item text-truncate">Old Grading</span></a>
            </li>

        </ul>
    </li>   

    {{-- master end  --}}
    
     {{-- setting start  --}}
    <li class="nav-item"><a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >Setting
         </span></a>
 <ul class="menu-content">
     <li><a class="d-flex align-items-center" href="{{url('open_addmission_form')}}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Open Admission Form</span></a>
     </li>
     <li><a class="d-flex align-items-center" href="{{url('open_admission_edit_form')}}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Open Admission Edit Form</span></a>
     </li>
     <li><a class="d-flex align-items-center" href="{{url('open_exam_form')}}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Open Exam Form</span></a>
     </li>
     

 </ul>
</li>

  {{-- setting end  --}}

  {{-- Admissions start  --}}

  <li class="nav-item"><a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
    <span class="menu-title text-truncate" >Admissions
     </span></a>
     <ul class="menu-content">
        <li><a class="d-flex align-items-center" href="{{ url('/Application_Report') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Admission List</span></a>
        </li>
        <li><a class="d-flex align-items-center" href="{{ url('/entrance_exam_schedule') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Entrance Exam Schedule</span></a>
        </li>
        <li><a class="d-flex align-items-center" href="{{ url('/admission_counselling') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Admission Counselling</span></a>
        </li>
        <li><a class="d-flex align-items-center" href="{{ url('/council_data') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Counselled student</span></a>
        </li>
        <li><a class="d-flex align-items-center" href="{{ url('/enrolled_student') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Enrolled Student</span></a>
        </li>
        <li><a class="d-flex align-items-center" href="{{ url('/course_transfer') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Course Transfer</span></a>
        </li>
    </ul>
    
</li>

{{-- Admissions end  --}}

{{-- Exam start  --}}
<li class="nav-item">
    <a class="d-flex align-items-center" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
            <polyline points="10 9 9 9 8 9"></polyline>
        </svg>
        <span class="menu-title text-truncate">Exam</span>
    </a>
    <ul class="menu-content">
        <li><a class="d-flex align-items-center" href="{{ url('/Exam_list') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Exam List or Exam Edit</span></a></li>
        <li><a class="d-flex align-items-center" href="{{ url('/reqular_exam_form_list') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Regular Exam Form Report</span></a></li>
        <li><a class="d-flex align-items-center" href="{{url('/regular_mark_filling')}}"><i data-feather="circle"></i><span class="menu-item text-truncate">Regular Mark Filling Report</span></a></li>
        <li><a class="d-flex align-items-center" href="{{ url('/back_paper_report') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Back Exam Report</span></a></li>
        <li><a class="d-flex align-items-center" href="{{ url('/Exam_Schedule') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Exam Schedule</span></a></li>
        <li><a class="d-flex align-items-center" href="{{ url('/Exam_paper_approvel_system') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Exam Papers Approval System</span></a></li>
        <li><a class="d-flex align-items-center" href="{{ url('/check_eligibalty') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">Back / Final / Special Back Paper Eligibility</span></a></li>
        <li><a class="d-flex align-items-center" href="{{ url('/mbbs_exam_report') }}"><i data-feather="circle"></i><span class="menu-item text-truncate">MBBS / B.Sc.(Nursing) Exam Form Report</span></a></li>
    </ul>
</li>


{{-- Exam end  --}}

    {{-- Admit card start  --}}
    

    <li class="nav-item"><a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >Admit Card
         </span></a>
 <ul class="menu-content">
     <li><a class="d-flex align-items-center" href="{{ url('/admit_card_list') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Admit Card List</span></a>
     </li>
 </ul>
    </li>

     {{-- Admit card end  --}}

     {{-- faculty maping start  --}}

     <li class="nav-item"><a class="d-flex align-items-center" href="{{ url('/faculty_mapping') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >Faculty Mapping System
         </span></a>
 <ul class="menu-content">
     <li><a class="d-flex align-items-center" href="faculty_mapping"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Faculty Subject Mapping</span></a>
     </li>
 </ul>
     </li>
    {{-- faculty maping end  --}}

     {{-- Result start  --}}
     <li class="nav-item"><a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >Results
         </span></a>
         <ul class="menu-content">
            <li><a class="d-flex align-items-center" href="{{ url('/result_list') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Result List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/regular_tr_generate') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Regular TR Generate</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/regular_tr_view') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Regular TR View</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/final_back_tr_generate') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Back/Final Back TR Generate</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/final_back_tr_view') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Back/Final Back TR View</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/md_tr_generate') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">MD TR Generate</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/award_sheet_for_all') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Award Sheet For All</span></a>
            </li>
        </ul>
        
     </li>

     {{-- Result end  --}}

     {{-- mbbs start  --}}
     <li class="nav-item"><a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >MBBS/Para/Nursing
         </span></a>
         <ul class="menu-content">
            <li><a class="d-flex align-items-center" href="{{ url('/bpt_bmlt_tr') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">RBPT/BMLT/Nursing TR</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/dpharma_tr') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">D.Pharma</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/mbbs_tr') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">MBBS TR</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/MBBS_RT_2019_2020_2020_2021') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">MBBS RT 2019-2020 & 2020-2021</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/mbbs_tr_third') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">MBBS-TR-THIRD-BSC</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/all_mbbs_result') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">MBBS Result</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('/mbbs_allowed_students') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Mbbs Allowed Students</span></a>
            </li>
        </ul>
        
     </li>
     {{-- mbbs end  --}}

     {{-- challenge start  --}}
     <li class="nav-item"><a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >Challenge Form
         </span></a>
 <ul class="menu-content">
     <li><a class="d-flex align-items-center" href="{{ url('/allowed_student_for_challenge') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Allowed Students For Challenge</span></a>
     </li>
 </ul>
     </li>
     {{-- challenge end  --}}

     {{-- icard start  --}}
     <li class="nav-item"><a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >iCards
         </span></a>
 <ul class="menu-content">
     <li><a class="d-flex align-items-center" href="{{ url('/card_list') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Card List</span></a>
     </li>
     <li><a class="d-flex align-items-center" href="{{ url('/bulkuploads') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Bulk Uploading</span></a>
     </li>
</ul>
     </li>
     {{-- icard end  --}}

     {{-- student fees start  --}}
     <li class="nav-item"><a class="d-flex align-items-center" href="{{ url('/studentfees') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >Student Fee
         </span></a>
 <ul class="menu-content">
     <li><a class="d-flex align-items-center" href="{{ url('/semester_fee') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Samester Fee</span></a>
     </li>
    
</ul>
     </li>
     {{-- student fees end  --}}

     {{-- student form start  --}}

     <li class="nav-item"><a class="d-flex align-items-center" href="{{ url('/student_form') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >Student Form
         </span></a>
 <ul class="menu-content">
     <li><a class="d-flex align-items-center" href="{{ url('/challengeform') }}"><i data-feather="circle"></i><span
                 class="menu-item text-truncate">Challenge Form</span></a>
     </li>
    
</ul>
     </li>
     {{-- student form end  --}}

     {{-- Affiliate information start  --}}
     <li><a class="d-flex align-items-center" href="{{ url('/affiliate_information_view') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg><span
        class="menu-item text-truncate">Affiliate Informations view</span></a>
</li>

     {{-- Affiliate information end  --}}

     {{-- grievance start  --}}
     <li><a class="d-flex align-items-center" href="{{ url('/grievance') }}"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg><span
        class="menu-item text-truncate">Grievance</span></a>
</li>
     {{-- grievance end  --}}

     {{-- Report start  --}}

     <li class="nav-item"><a class="d-flex align-items-center" href=""><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        <span class="menu-title text-truncate" >Report
         </span></a>
         <ul class="menu-content">
            <li><a class="d-flex align-items-center" href="{{ url('all_enrollment') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">All Enrollment</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('mbbs_result') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">MBBS Result</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('Application_Report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Application Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('Mark_Filling_Report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Mark Filling Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('mark_sheet_position') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Marksheet Position</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('cgpa_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">CGPA Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('medal_list') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Medal List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('tr_summary') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">TR Summary</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('mbbs_security_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">MBBS Scrutiny Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('scholarship_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Scholarship Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('scholarship_report_new') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Scholarship Report new</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('digi_shakti_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">DIGI Shakti MIS Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('degree_report_list') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Degree Report List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('digilocker_list') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Digilocker Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('disability_report_list') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Disability Report List</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('passed_student_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Passed Student Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('studying_student_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Studying Student Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('all_studying_student') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">All Studying Student Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('pass_out_student_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Pass Out Student Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('enrollment_summary') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Enrollment Summary</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('enrollment_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Enrollment Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('regular_exam_form_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Regular Exam Form Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('award_sheet_for_all') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Award Sheet For All</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('chart_for_maximum_marks') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Chart for Maximum Marks</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('all_faculty_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">All Faculty Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('nirf_report') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">NIRF Report</span></a>
            </li>
            <li><a class="d-flex align-items-center" href="{{ url('exam_wise_passout_students') }}"><i data-feather="circle"></i><span
                     class="menu-item text-truncate">Exam Wise Passout Students</span></a>
            </li>
        </ul>
        
     </li>

     {{-- Report end  --}}


     
     
     
            
   
        </ul>
    </div>

</div>
