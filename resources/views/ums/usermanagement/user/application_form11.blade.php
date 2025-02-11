@extends('ums.admin.admin-meta')
{{-- 'ums.usermanagement.user.frontend.layouts.app' --}}
@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

    @php $btech_course = array(114,133,134); @endphp
    <!--SECTION START-->
    <section>
        <div class="container com-sp pad-bot-70 pg-inn pt-3">
            <div class="n-form-com admiss-form">

                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mt-0 mb-3 mobileadmintxt">Application Form</h2>
                        <hr />
                    </div>
                </div>

                <form method="POST" action="{{ url('application-form') }}" id="myform_application"
                    enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="invalid-feedback text-danger error_application"></div>
{{-- 
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="">Application For <span class="text-danger">(Please click the correct
                                    box)</span></label>
                            <p class="newboxciradio">
                                <input type="radio" class="filled-in application_for" name="application_for"
                                    value="1" id="applicatio_for1" @if ($course_single == null || $course_single->campus_id == 1) checked @endif>
                                <label for="applicatio_for1" class="form-check-label"><strong>IN DSMNRU CAMPUS</strong><span
                                        class="text-danger">*</span></label>

                                <input type="radio" class="filled-in application_for" name="application_for"
                                    value="2" id="applicatio_for2" @if ($course_single && $course_single->campus_id > 1) checked @endif>
                                <label for="applicatio_for2" class="form-check-label"><strong>AFFILIATED
                                        COLLEGE</strong><span class="text-danger">*</span> <i
                                        class="text-danger">(Admissions are subject to the approval of the program from RCI
                                        and affiliation by DSMNRU)</i></label>


                                <!-- <label for="applicatio_for2" class="form-check-label"><strong class="text-danger">Note:- </strong><span class="text-danger">Admissions in the affiliated colleges are subjected to the approval of affiliation from the university.</span></label> -->

                            <div class="invalid-feedback text-danger application_for_application"></div>
                            </p>

                        </div>
                    </div>
                    <div class="row college_name">
                        <div class="col-md-4 mb-5">
                            <label for="">College Name<span class="text-danger">*</span></label>
                            <select class="form-control" name="campus_id" id="campus_id">
                                <option value="">--Select College Name--</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}" @if ($course_single && $course_single->campus_id == $college->id) selected @endif>
                                        {{ $college->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback text-danger college_name_application"></div>
                        </div>
                    </div> --}}
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div
                                class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                                <div>
                                    <h4 class="card-title text-theme">
                                        Application For <span class="text-danger">(Please click the correct
                                            box)</span>
                                    </h4>
                                    <p class="card-text">
                                    <div class="newboxciradio">
                                        <input type="radio" class="filled-in application_for" name="collage"
                                            value="1" id="applicatio_for1" checked>
                                        <label for="applicatio_for1" class="form-check-label me-3">
                                            <strong>IN DSMNRU CAMPUS</strong><span class="text-danger">*</span>
                                        </label>

                                        <input type="radio" class="filled-in application_for" name="collage"
                                            value="2" id="check">
                                        <label for="check" class="form-check-label">
                                            <strong>AFFILIATED COLLEGE</strong><span class="text-danger">*</span>
                                            <i class="text-danger">(Admissions are subject to the approval of the
                                                program from RCI and affiliation by DSMNRU)</i>
                                        </label>
                                    </div>
                                    <div class="invalid-feedback text-danger application_for_application"></div>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8" style="display: none" id="select">
                            <div class="row align-items-center mb-1 exisitng">
                                <div class="col-md-3">
                                    <label class="form-label">
                                        College Name<span class="text-danger">*</span>
                                    </label>
                                </div>
                                <div class="col-md-5">
                                    <select class=" form-control select2" name="campus_id" id="campus_id">
                                        <option value="">--Select College Name--</option>
                                        @foreach ($colleges as $college)
                                            <option value="{{ $college->id }}"
                                                @if ($course_single && $course_single->campus_id == $college->id) selected @endif>
                                                {{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="front-form-head">Program Details</h5>
                        </div>
                        <div class="col-md-12 row">
                            <div class="col-md-4 row">
                                <div class="form-group">
                                    <label for="">Academic Session<span class="text-danger">*</span></label>
                                    <select class="form-control" name="academic_session" id="academic_session">
                                        <!-- <option value="">--Select Academic Session--</option> -->
                                        <!-- <option value="2022-2023">2022-2023</option> -->
                                        <!-- <option value="2023-2024">2023-2024</option> -->
                                        <option value="2024-2025">2024-2025</option>
                                    </select>
                                    <div class="invalid-feedback text-danger academic_session_application"></div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <label for="">Programme/Course Type<span class="text-danger">*</span></label>
                            <select class="form-control" name="course_type" id="course_type">
                                <option value="">--Select Program--</option>
                                @foreach ($programm_types as $programm_type)
                                    <option value="{{ $programm_type->id }}"
                                        @if ($programm_type_id == $programm_type->id) selected @endif>{{ $programm_type->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback text-danger course_type_application"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="">Name of Programme/Course<span class="text-danger">*</span></span></label>
                            <select class="form-control" name="course_id" id="course_id" onChange="btech($(this).val())">
                                <option value="">--Select Course--</option>
                                @foreach ($courses as $course)
                                    @if ($course_id == $course->id)
                                        <option value="{{ $course->id }}"
                                            @if ($course_id == $course->id) selected @endif>{{ $course->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="invalid-feedback text-danger course_id_application"></div>
                        </div>
                        <div class="col-md-4" id="hidelateral_entry" style="display: none;">
                            <label for="">Lateral Entry <span class="text-danger">*</span></label>
                            <select class="form-control" name="lateral_entry" id="lateral_entry"
                                onchange="checkLeteralCourse()">
                                @if (Request()->course_id == 126)
                                    <option value="yes">Yes</option>
                                @else
                                    <option value="no">No</option>
                                @endif
                            </select>
                            <div class="invalid-feedback text-danger lateral_entry_application"></div>
                        </div>
                        @include('ums.usermanagement.user.frontend.index.application-course-preference')
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <h5 class="front-form-head">Student Details</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control uppercase" name="student_first_Name"
                                    value="{{ Auth::user()->first_name }}" maxlength="20" required readonly />
                                <div class="invalid-feedback text-danger student_first_Name_application"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" class="form-control uppercase" name="student_middle_Name"
                                    value="{{ Auth::user()->middle_name }}" maxlength="20" readonly />
                                <div class="invalid-feedback text-danger student_middle_Name_application"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control uppercase" name="student_last_Name"
                                    value="{{ Auth::user()->last_name }}" maxlength="20" readonly />
                                <div class="invalid-feedback text-danger student_last_Name_application"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Date of Birth <span class="text-danger">*</span></label>
                                @php $lat_10_year = date('Y')-10; @endphp
                                @php $max_dob = $lat_10_year.date('-m-d'); @endphp
                                <input type="date" class="form-control" name="date_of_birth"
                                    max="{{ $max_dob }}" />
                                <div class="invalid-feedback text-danger date_of_birth_application"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">E-mail ID <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="student_email"
                                    value="{{ Auth::user()->email }}" readonly />
                                <div class="invalid-feedback text-danger student_email_application"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Mobile Number (10 digit) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control numbersOnly" name="student_mobile"
                                    maxlength="10" value="{{ Auth::user()->mobile }}" readonly />
                                <div class="invalid-feedback text-danger student_mobile_application"></div>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-2">

                        <div class="col-md-6">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Father's Name<span
                                    class="text-danger">*</span></h5>
                            <input type="text" class="form-control uppercase" name="father_name" maxlength="30"
                                autocomplete="__away" />
                            <div class="invalid-feedback text-danger father_name_application"></div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Father's Mobile Number<span
                                    class="text-danger">*</span></h5>
                            <input type="text" class="form-control numbersOnly" name="father_mobile" maxlength="10"
                                autocomplete="__away" />
                            <div class="invalid-feedback text-danger father_mobile_application"></div>
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-6">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Mother's Name<span
                                    class="text-danger">*</span></h5>
                            <input type="text" class="form-control uppercase" name="mother_name" maxlength="30"
                                autocomplete="__away" />
                            <div class="invalid-feedback text-danger mother_name_application"></div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Mother's Mobile Number</h5>
                            <input type="text" class="form-control numbersOnly" name="mother_mobile" maxlength="10"
                                autocomplete="__away" />
                            <div class="invalid-feedback text-danger mother_mobile_application"></div>
                        </div>

                    </div>

                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h5 class="front-form-head">Personal Information</h5>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label>Gender<span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <select class="form-control" id="gender" name="gender">

                                <option value="">--Select Gender--</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Transgender">Transgender</option>
                            </select>
                            <div class="invalid-feedback text-danger gender_application"></div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label>Religion<span class="text-danger">*</span></label>
                                <div class="form-group" id="religion1" style="margin-top: 20px;" hidden>
                                    <label>Enter Your Religion<span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <select class="form-control" id="religion">
                                <option value="">--Select Religion--</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Muslim">Muslim</option>
                                <option value="Sikh">Sikh</option>
                                <option value="Christian">Christian</option>
                                <option value="Other">Other</option>

                            </select>
                            <input type="text" class="filled-in" name="religion" value="" id="religion2"
                                autocomplete="__away" hidden>
                            <div class="invalid-feedback text-danger religion_application"></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label>Marital Status<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <select class="form-control" id="marital_status" name="marital_status">
                                <option value="">--Select Marital Status--</option>
                                <option value="Married">Married</option>
                                <option value="Unmarried">Unmarried</option>
                                <option value="Divorcee">Divorcee</option>
                            </select>
                            <div class="invalid-feedback text-danger marital_status_application"></div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label>Blood Group<span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <select class="form-control" id="blood_group" name="blood_group">

                                <option value="">--Select Option--</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <!--option value="NA">NA</option-->
                            </select>


                            <div class="invalid-feedback text-danger blood_group_application"></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label>Nationality<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <select class="form-control" id="nationality">
                                <option value="Indian" selected>Indian</option>
                                <option value="Others">Others</option>
                            </select>
                            <div class="invalid-feedback text-danger nationality_application"></div>
                        </div>
                        <div class="col-md-2 mb-2" id="nationality1" hidden>
                            <div class="form-group">
                                <label>Enter Your Nationality<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2" id="nationality11" hidden>
                            <div class="form-group">
                                <input type="text" class="form-control" name="nationality" value="Indian"
                                    value="In" id="nationality_value" autocomplete="__away" />
                                <div class="invalid-feedback text-danger nationality_application"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label>Domicile<span class="text-danger">*</span></label>
                                <div class="form-group" id="domicile_cirtificate1" style="margin-top: 20px;" hidden>
                                    <label>Upload Your Domicile<span class="text-danger">*</span></label>
                                </div>
                                <div class="form-group" id="domicile1" style="margin-top: 20px;" hidden>
                                    <label>Enter Your Domicile<span class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-2">
                            <select class="form-control" id="domicile">

                                <option value=>--Select Domicile--</option>
                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="Others">Others</option>
                            </select>
                            <input type="text" class="filled-in" name="domicile" id="domicile2"
                                autocomplete="__away" hidden>
                            <div class="invalid-feedback text-danger domicile_application"></div>
                            <input type="file" class="form-control" name="domicile_cirtificate" value=""
                                id="domicile_cirtificate2" autocomplete="__away" hidden accept="image/*">
                            <div class="invalid-feedback text-danger domicile_cirtificate_application"></div>
                        </div>

                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label>DSMNRU Student ?<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <select class="form-control" id="enrollment" name="dsmnru_student">
                                <option value="">--Select Option--</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            <div class="invalid-feedback text-danger dsmnru_student_application"></div>
                        </div>
                        <div class="col-md-2 mb-2" id="enrollment1" hidden>
                            <div class="form-group">
                                <label>Enter Your Enrollment Number<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2" id="enrollment11" hidden>
                            <div class="form-group">
                                <input type="text" class="form-control" name="enrollment_number"
                                    id="enrollment_value" autocomplete="__away" />
                                <div class="invalid-feedback text-danger enrollment_number_application"></div>
                            </div>
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-md-4 mb-2 hidden">
                            <div class="form-group">
                                <label>Sub Category</label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-2 hidden">
                            <div class="form-group">
                                <select class="form-control" name="sub_category">
                                    <option>Select</option>
                                    <option>Son/Daughter/Spouse of Teachers'/Employees' of DSMNRU</option>
                                    <option>Physically Handicapped</option>
                                    <option>Dependent of Freedom Fighter</option>
                                    <option>Defence Personnel</option>
                                    <option>Sports</option>
                                    <option>NCC ‘B’ / NSS Certified
                                </select>
                                <div class="invalid-feedback text-danger sub_category_application"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-md-2 mb-2">
                            <div class="form-group">
                                <label>Aadhar Number<span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="adhar_card_number" class="form-control numbersOnly"
                                maxlength="12" />
                            <div class="invalid-feedback text-danger adhar_card_number_application"></div>
                        </div>

                        <div class="col-md-12">
                            <h5 class="front-form-head">Category <code>(File can be uploaded only in JPG,PNG or PDF format
                                    from 200KB to 500KB)</code></h5>
                        </div>
                        {{-- <div class="col-md-12">
                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>Category<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="category" name="category"
                                    onChange="check_caste_certificate_number($(this).val())">
                                    <option value="">--Select Category--</option>
                                    <option value="General">General</option>
                                    <option value="OBC">OBC</option>
                                    <option value="SC">SC</option>
                                    <option value="ST">ST</option>
                                    <option value="EWS">EWS</option>
                                </select>
                                <div class="invalid-feedback text-danger category_application"></div>
                            </div>
                            <div class="col-md-2 mb-2 certificate_no_text" id="certificate_no1" style="display:none;">
                                <div class="form-group">
                                    <label>Certificate Number<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 certificate_no_text" id="certificate_no" style="display:none;">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="caste_certificate_number"
                                        autocomplete="__away" maxlength="15" />
                                    <div class="invalid-feedback text-danger caste_certificate_number_application"></div>
                                </div>
                            </div>

                            <div class="col-md-2 mb-2 certificate_no_text" id="certificate_no3" style="display:none;">
                                <div class="form-group">
                                    <label>Upload Caste Certificate<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 certificate_no_text" id="certificate_no3" style="display:none;">
                                <div class="form-group">
                                    <input type="file" class="form-control" name="upload_caste_certificate"
                                        accept="image/*" autocomplete="__away" maxlength="15" />
                                    <div class="invalid-feedback text-danger upload_caste_certificate_application"></div>
                                </div>
                            </div>

                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>DSMNRU Employee<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="dsmnru_employee" name="dsmnru_employee"
                                    onChange="open_dsmnru_relationship($(this).val())">
                                    <option value="">--Select Option--</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <div class="invalid-feedback text-danger dsmnru_employee_application"></div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-2 mb-2 dsmnru_relationship" hidden>
                                <div class="form-group">
                                    <label>DSMNRU Designation<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 dsmnru_relationship" hidden>
                                <input type="text" class="form-control" name="dsmnru_relation" id="dsmnru_relation">
                                <div class="invalid-feedback text-danger dsmnru_relation_application"></div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>DSMNRU Employee Ward<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="dsmnru_employee_ward" name="dsmnru_employee_ward"
                                    onChange="set_name_and_relation($(this))">
                                    <option value="">--Select Option--</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <div class="invalid-feedback text-danger dsmnru_employee_ward_application"></div>
                            </div>

                            <div class="row ward_emp_name_and_relation" style="display:none;">
                                <div class="clearfix"></div>

                                <div class="col-md-2 mb-2">
                                    <div class="form-group">
                                        <label>DSMNRU Employee Name<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <input type="text" class="form-control" name="ward_emp_name"
                                        autocomplete="__away" />
                                    <div class="invalid-feedback text-danger ward_emp_name_application"></div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <div class="form-group">
                                        <label>Relation With DSMNRU Employee<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <input type="text" class="form-control" name="ward_emp_relation"
                                        autocomplete="__away" />
                                    <div class="invalid-feedback text-danger ward_emp_relation_application"></div>
                                </div>

                            </div>


                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>Disability<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="disability" name="disability"
                                    onChange="disability_cat_open($(this).val())">
                                    <option value="">--Select Option--</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <div class="invalid-feedback text-danger disability_application"></div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-2 mb-2 disability_category_box" hidden>
                                <div class="form-group">
                                    <label>Disability Category<span class="text-danger ">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2 disability_category_box" hidden>
                                <select class="form-control " id="disability_category" name="disability_category"
                                    onChange="change_disability_category($(this).val())">
                                    <option value="">--Select Option--</option>
                                    @foreach ($disabilities as $disability)
                                        @if ($disability->short_name != 'NA')
                                            <option
                                                value="{{ $disability->disability_category }}-{{ $disability->short_name }}">
                                                {{ $disability->disability_category }}-{{ $disability->short_name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="invalid-feedback text-danger disability_category_application"></div>
                            </div>

                            <div class="col-md-2 mb-2 disability_certificate_no_text" id="certificate_no1"
                                style="display:none;">
                                <div class="form-group">
                                    <label>Percentage of Disability<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 disability_certificate_no_text" id=""
                                style="display:none;">
                                <div class="form-group">
                                    <input type="text" class="form-control numbersOnly percentage_type"
                                        placeholder="%" name="percentage_of_disability" maxlength="5"
                                        autocomplete="__away" maxlength="18" />
                                    <div class="invalid-feedback text-danger percentage_of_disability_application"></div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-2 mb-2 disability_certificate_no_text" id="certificate_no1"
                                style="display:none;">
                                <div class="form-group">
                                    <label>UDID Number</label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 disability_certificate_no_text" id="certificate_no"
                                style="display:none;">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="udid_number" autocomplete="__away"
                                        maxlength="18" />
                                    <div class="invalid-feedback text-danger udid_number_application"></div>
                                </div>
                            </div>

                            <div class="col-md-2 mb-2 disability_certificate_no_text" id="certificate_no3"
                                style="display:none;">
                                <div class="form-group">
                                    <label>Upload Disability Certificate<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2 disability_certificate_no_text" id="certificate_no3"
                                style="display:none;">
                                <div class="form-group">
                                    <input type="file" class="form-control" name="upload_disability_certificate"
                                        accept="image/*" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger upload_disability_certificate_application">
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>Freedom Fighter Dependent<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="freedom_fighter_dependent"
                                    name="freedom_fighter_dependent">
                                    <option value="">--Select Option--</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <div class="invalid-feedback text-danger freedom_fighter_dependent_application"></div>
                            </div>


                            <div class="col-md-2 mb-2" id="freedom_fighter_dependent1" hidden>
                                <div class="form-group">
                                    <label>Upload Cirtificate<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2" id="freedom_fighter_dependent11" hidden>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="freedom_fighter_dependent_file"
                                        accept="image/*" id="freedom_fighter_dependent_value" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger freedom_fighter_dependent_file_application">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>NCC<br>(C-Certificate)<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="ncc_cirtificate" name="ncc">

                                    <option value="">--Select Option--</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>

                                <div class="invalid-feedback text-danger ncc_application"></div>
                            </div>

                            <div class="col-md-2 mb-2" id="ncc_cirtificate1" hidden>
                                <div class="form-group">
                                    <label>Upload NCC Cirtificate<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2" id="ncc_cirtificate11" hidden>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="ncc_cirtificate" accept="image/*"
                                        id="ncc_cirtificate_value" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger ncc_cirtificate_application"></div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>NSS <br /> (240 hrs and 1 camp)<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="nss_cirtificate" name="nss">

                                    <option value="">--Select Option--</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <div class="invalid-feedback text-danger nss_application"></div>
                            </div>

                            <div class="col-md-2 mb-2" id="nss_cirtificate1" hidden>
                                <div class="form-group">
                                    <label>Upload NSS Cirtificate<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2" id="nss_cirtificate11" hidden>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="nss_cirtificate" accept="image/*"
                                        id="nss_cirtificate_value" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger nss_cirtificate_application"></div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>Sports<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="sports" name="sports">

                                    <option value="">--Select Option--</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <div class="invalid-feedback text-danger sports_application"></div>
                            </div>

                            <div class="col-md-2 mb-2" id="sports1" hidden>
                                <div class="form-group">
                                    <label>Sport Level<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2" id="sports11" hidden>
                                <div class="form-group">
                                    <select class="form-control" id="nss_cirtificate" name="sport_level">
                                        <option value="">--Select Option--</option>
                                        <option value="National">National</option>
                                        <option value="State">State</option>
                                        <option value="International">International</option>
                                    </select>
                                    <div class="invalid-feedback text-danger sport_level_application"></div>
                                </div>

                            </div>
                            <div class="col-md-2 mb-2" id="sportt_cirtificate1" hidden>
                                <div class="form-group">
                                    <label>Upload Sport Cirtificate<span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2" id="sportt_cirtificate11" hidden>
                                <div class="form-group">
                                    <input type="file" class="form-control" name="sportt_cirtificate"
                                        id="sportt_cirtificate1_value" accept="image/*" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger sportt_cirtificate_application"></div>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label>Hostel Facility Required<span class="text-danger">*</span></label>
                                </div>
                            </div>

                            <div class="col-md-4 mb-2">
                                <select class="form-control" id="hostal_options" name="hostel_facility_required">

                                    <option value="">--Select Option--</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                                <div class="invalid-feedback text-danger sports_application"></div>
                            </div>

                            <div class="col-md-2 mb-2" id="hostal_options1" hidden>
                                <div class="form-group">
                                    <label>How many years staying in DSMNRU Hostel<span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2" id="hostal_options11" hidden>
                                <div class="form-group">
                                    <input type="text" class="form-control numbersOnly" name="hostel_for_years"
                                        maxlength="2" id="hostel_distence1_value" autocomplete="__away" />

                                    <div class="invalid-feedback text-danger hostal_options_application"></div>
                                </div>

                            </div>
                        </div> --}}
                        <div class="col-md-12">
                            <!-- Row 1: Category, DSMNRU Employee, Disability -->
                            <div class="row">
                                <!-- Category -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Category<span class="text-danger">*</span></label>
                                        <select class="form-control" id="category" name="category" onChange="check_caste_certificate_number($(this).val())">
                                            <option value="">--Select Category--</option>
                                            <option value="General">General</option>
                                            <option value="OBC">OBC</option>
                                            <option value="SC">SC</option>
                                            <option value="ST">ST</option>
                                            <option value="EWS">EWS</option>
                                        </select>
                                        <div class="invalid-feedback text-danger category_application"></div>
                                    </div>
                                </div>
                        
                                <!-- DSMNRU Employee -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>DSMNRU Employee<span class="text-danger">*</span></label>
                                        <select class="form-control" id="dsmnru_employee" name="dsmnru_employee" onChange="open_dsmnru_relationship($(this).val())">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="invalid-feedback text-danger dsmnru_employee_application"></div>
                                    </div>
                                </div>
                        
                                <!-- Disability -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Disability<span class="text-danger">*</span></label>
                                        <select class="form-control" id="disability" name="disability" onChange="disability_cat_open($(this).val())">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="invalid-feedback text-danger disability_application"></div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Conditional Fields Row 1 (Category-related) -->
                            <div class="row certificate_no_text" style="display:none;">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Certificate Number<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="caste_certificate_number" autocomplete="__away" maxlength="15" />
                                        <div class="invalid-feedback text-danger caste_certificate_number_application"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Upload Caste Certificate<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="upload_caste_certificate" accept="image/*" autocomplete="__away" />
                                        <div class="invalid-feedback text-danger upload_caste_certificate_application"></div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Row 2: DSMNRU Employee Ward, Freedom Fighter, NCC -->
                            <div class="row">
                                <!-- DSMNRU Employee Ward -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>DSMNRU Employee Ward<span class="text-danger">*</span></label>
                                        <select class="form-control" id="dsmnru_employee_ward" name="dsmnru_employee_ward" onChange="set_name_and_relation($(this))">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="invalid-feedback text-danger dsmnru_employee_ward_application"></div>
                                    </div>
                                </div>
                        
                                <!-- Freedom Fighter -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Freedom Fighter Dependent<span class="text-danger">*</span></label>
                                        <select class="form-control" id="freedom_fighter_dependent" name="freedom_fighter_dependent">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="invalid-feedback text-danger freedom_fighter_dependent_application"></div>
                                    </div>
                                </div>
                        
                                <!-- NCC -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>NCC (C-Certificate)<span class="text-danger">*</span></label>
                                        <select class="form-control" id="ncc_cirtificate" name="ncc">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="invalid-feedback text-danger ncc_application"></div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Row 3: NSS, Sports, Hostel -->
                            <div class="row">
                                <!-- NSS -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>NSS (240 hrs and 1 camp)<span class="text-danger">*</span></label>
                                        <select class="form-control" id="nss_cirtificate" name="nss">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="invalid-feedback text-danger nss_application"></div>
                                    </div>
                                </div>
                        
                                <!-- Sports -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Sports<span class="text-danger">*</span></label>
                                        <select class="form-control" id="sports" name="sports">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="invalid-feedback text-danger sports_application"></div>
                                    </div>
                                </div>
                        
                                <!-- Hostel -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Hostel Facility Required<span class="text-danger">*</span></label>
                                        <select class="form-control" id="hostal_options" name="hostel_facility_required">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                        <div class="invalid-feedback text-danger sports_application"></div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Conditional Fields for Disability -->
                            <div class="row disability_certificate_no_text" style="display:none;">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Percentage of Disability<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control numbersOnly percentage_type" placeholder="%" 
                                            name="percentage_of_disability" maxlength="5" autocomplete="__away" />
                                        <div class="invalid-feedback text-danger percentage_of_disability_application"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>UDID Number</label>
                                        <input type="text" class="form-control" name="udid_number" autocomplete="__away" maxlength="18" />
                                        <div class="invalid-feedback text-danger udid_number_application"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label>Upload Disability Certificate<span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="upload_disability_certificate" accept="image/*" autocomplete="__away" />
                                        <div class="invalid-feedback text-danger upload_disability_certificate_application"></div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Additional conditional fields (maintain similar patterns for other conditional sections) -->
                            <!-- ... -->
                        </div>
                        <div class="col-md-2 mb-2" id="hostel_distence1" hidden>
                            <div class="form-group">
                                <label>Distance from your residence to University campus<span
                                        class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2" id="hostel_distence11" hidden>
                            <div class="form-group">
                                <input type="text" class="form-control numbersOnly placeholder_right" placeholder="KM"
                                    name="distance_from_university" maxlength="5" id="hostel_distence1_value"
                                    autocomplete="__away" />
                                <div class="invalid-feedback text-danger hostel_distence1_application"></div>
                            </div>
                        </div>
                        <div class="row mb-5" id="ded">
                            <div class="col-md-12">
                                <h5 class="front-form-head">D.Ed. Spl.Ed. (HI/VI/ID) Fill These Fields</h5>
                            </div>
                            <div class="col-md-2 mb-2" id="">
                                <div class="form-group">
                                    <label>AIOT Score</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="aiot_score" id="aiot_score"
                                        autocomplete="__away" />
                                    <div class="invalid-feedback text-danger aiot_score_application"></div>
                                </div>
                            </div>

                            <div class="col-md-2 mb-2" id="">
                                <div class="form-group">
                                    <label>AIOT Rank</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="aiot_rank" id="aiot_rank"
                                        autocomplete="__away" />
                                    <div class="invalid-feedback text-danger aiot_rank_application"></div>
                                </div>
                            </div>

                            <div class="row mb-5" id="ded">
                                <div class="col-md-2 mb-2" id="">
                                    <div class="form-group">
                                        <label>AIOT Score Card
                                            <span class="text-danger f-12 normal-line-height">(Uploaded doc should not be
                                                more than 100KB and only Image or PDF format accepted)</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="aiot_score_card"
                                            accept="image/*,application/pdf">
                                        <div class="invalid-feedback text-danger aiot_score_card_application"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (in_array(Request()->course_id, $btech_course))
                            <div class="row mb-5" id="admission_applying_through">
                                <div class="col-md-12">
                                    <h5 class="front-form-head">In which national/state level entrance test you appeared.
                                    </h5>
                                </div>
                                <div class="col-md-2 mb-2" id="">
                                    <div class="form-group">
                                        <label>Select Process<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" id="admission_through" name="admission_through"
                                            onChange="admissionthrough()">
                                            <option value="">--Select Option--</option>
                                            <option value="JEE(MAIN)">JEE(MAIN)</option>
                                            <option value="CUET">CUET</option>
                                            <option value="OTHER STATE LEVEL EXAM">OTHER STATE LEVEL EXAM</option>
                                        </select>
                                        <div class="invalid-feedback text-danger admission_through_application"></div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2 jee jeee_roll_number_class">
                                    <div class="form-group">
                                        <label>Roll Number<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4 jee jeee_roll_number_class">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="jeee_roll_number"
                                            id="jeee_roll_number" autocomplete="__away" />
                                        <div class="invalid-feedback text-danger jeee_roll_number_application"></div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2 admission_through_exam_name_class" style="display:none;">
                                    <div class="form-group">
                                        <label>Name of Exam<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4 admission_through_exam_name_class" style="display:none;">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="admission_through_exam_name"
                                            id="admission_through_exam_name" autocomplete="__away" />
                                        <div class="invalid-feedback text-danger admission_through_exam_name_application">
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-2 mb-2 jee appeared_or_passed_class">
                                    <div class="form-group">
                                        <label>Appeared or Passed<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4 jee appeared_or_passed_class">
                                    <div class="form-group">
                                        <select name="appeared_or_passed" id="appeared_or_passed" class="form-control"
                                            onchange="admissionthrough()">
                                            <option value="">Select</option>
                                            <option value="Passed">Passed</option>
                                            <option value="Appeared">Appeared</option>
                                        </select>
                                        <div class="invalid-feedback text-danger appeared_or_passed_application"></div>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2 jee">
                                    <div class="form-group">
                                        <label>Date of Examination<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4 jee">
                                    <div class="form-group">
                                        <input type="date" class="form-control" name="jeee_date_of_examination"
                                            id="jeee_date_of_examination" autocomplete="__away" />
                                        <div class="invalid-feedback text-danger jeee_date_of_examination_application">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2 jee">
                                    <div class="form-group">
                                        <label>Score<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4 jee">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="jeee_score" id="jeee_score"
                                            autocomplete="__away" />
                                        <div class="invalid-feedback text-danger jeee_score_application"></div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-2 mb-2 jee">
						<div class="form-group">
							<label>Merit<span class="text-danger">*</span></label>
						</div>
					</div>
					<div class="col-md-4 jee">
						<div class="form-group">
							<input type="text" class="form-control" name="jeee_merit" id="jeee_merit" autocomplete="__away" />
							<div class="invalid-feedback text-danger jeee_merit_application"></div>
						</div> 
					</div> --}}
                                <div class="col-md-2 mb-2 jee">
                                    <div class="form-group">
                                        <label>Rank<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4 jee">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="jeee_rank" id="jeee_rank"
                                            autocomplete="__away" />
                                        <div class="invalid-feedback text-danger jeee_rank_application"></div>
                                    </div>
                                </div>

                            </div>
                        @endif

                    </div>

                    @include('ums.usermanagement.user.frontend.index.application-form.address')

                    {{-- <div class="row mb-5">
                        <div class="col-md-6">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Name of Nominee (For Insurance
                                Purpose)<span class="text-danger">*</span></h5>
                            <input type="text" class="form-control uppercase" name="nominee_name" maxlength="30"
                                autocomplete="__away" />
                            <div class="invalid-feedback text-danger nominee_name_application"></div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Local Guardian Name<span
                                    class="text-danger">*</span></h5>
                            <input type="text" class="form-control uppercase" name="guardian_name" maxlength="30"
                                autocomplete="__away" />
                            <div class="invalid-feedback text-danger guardian_name_application"></div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Local Guardian Mobile<span
                                    class="text-danger">*</span></h5>
                            <input type="text" class="form-control numbersOnly" name="guardian_mobile" maxlength="10"
                                autocomplete="__away" />
                            <div class="invalid-feedback text-danger guardian_mobile_application">
                            </div>
                        </div>
                    </div> --}}
                    <div class="row mb-5">
                        <div class="col-md-4">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Name of Nominee (For Insurance Purpose)<span class="text-danger">*</span></h5>
                            <input type="text" class="form-control uppercase" name="nominee_name" maxlength="30" autocomplete="__away" />
                            <div class="invalid-feedback text-danger nominee_name_application"></div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Local Guardian Name<span class="text-danger">*</span></h5>
                            <input type="text" class="form-control uppercase" name="guardian_name" maxlength="30" autocomplete="__away" />
                            <div class="invalid-feedback text-danger guardian_name_application"></div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="front-form-head no-bg pl-0 font-weight-bold"> Local Guardian Mobile<span class="text-danger">*</span></h5>
                            <input type="text" class="form-control numbersOnly" name="guardian_mobile" maxlength="10" autocomplete="__away" />
                            <div class="invalid-feedback text-danger guardian_mobile_application"></div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                @include('ums.usermanagement.user.frontend.index.application-form.educational-qualification')
                                <button type="button" class="btn btn-primary" onclick="addNewEducation($(this))">Click
                                    here to add more degrees <i class="fa fa-plus-square"></i></button>
                                <br />
                                <br />
                            </div>
                            @include('ums.usermanagement.user.frontend.index.application-form.cuet-details')

                            <div class="table-responsive">
                                <table class="table table-bordered uploadtable">
                                    <tr>
                                        <td colspan="2"><b>Upload Photo and Signature <span
                                                    class="text-danger">*</span></b>
                                            <br />
                                            <span class="text-danger f-12 normal-line-height">(Upload only JPG/PNG file
                                                upto 50 KB.)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Recent Coloured Passport Size Photo<span class="text-danger">*</span></td>
                                        <td>
                                            <input type="file" class="form-control" name="upload_photo"
                                                accept="image/*">
                                            <div class="invalid-feedback text-danger upload_photo_application"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Signature/Thumb Impression<span class="text-danger">*</span></td>
                                        <td>
                                            <input type="file" class="form-control" name="upload_signature"
                                                accept="image/*">
                                            <div class="invalid-feedback text-danger upload_signature_application"></div>
                                        </td>
                                    </tr>
                                    <!--tr>
                 <td>Upload Your Aadhar<span class="text-danger">*</span></td>
                 <td>
                  <input type="file" class="form-control" name="upload_adhar" accept="application/pdf,image*">
                  <div class="invalid-feedback text-danger upload_adhar_application"></div>
                 </td>
                </tr>
                <tr>
                 <td>Caste Certificate (Excluding General)</td>
                 <td>
                  <input type="file" class="form-control" name="caste_certificate" accept="application/pdf,image/*">
                  <div class="invalid-feedback text-danger caste_certificate_application"></div>
                 </td>
                </tr>
                <tr>
                 <td>Disability Certificate</td>
                 <td>
                  <input type="file" class="form-control" name="disability_certificate" accept="application/pdf,image/*">
                  <div class="invalid-feedback text-danger disability_certificate_application"></div>
                 </td>
                </tr>
                <tr>
                 <td>Income Certificate (EWS)</td>
                 <td>
                  <input type="file" class="form-control" name="income_certificate" accept="application/pdf,image/*">
                  <div class="invalid-feedback text-danger income_certificate_application"></div>
                 </td>
                </tr>
                <tr>
                 <td>Any Other</td>
                 <td>
                  <input type="file" class="form-control" name="any_other" accept="application/pdf,image/*">
                  <div class="invalid-feedback text-danger any_other_application"></div>
                 </td>
                </tr-->
                                    <!--tr>
               <td></td>
               <td><a href="#" class="text-primary"><i class="fa fa-plus-square f-20 mt-2" aria-hidden="true"></i> Add More Row</a></td>
              </tr-->
                                </table>

                            </div>

                            <h5><b>DECLARATION</b></h5>

                            <p class="text-justify">I do hereby, solemn and affirm that details provided by me in this
                                application form under various heads are true & correct to the best of my knowledge and
                                information. I affirm that no part of information has been concealed, fabricated or
                                manipulated and that I have read University’s regulations for eligibility & admission
                                procedure. In the event that information provided by me is found incorrect, inappropriate,
                                false, manipulated or fabricated, the University shall have right to withdraw admission
                                provided to me through this application and to take legal action against me as may be
                                warranted by law.</p>
                            <p class="text-justify">I also acknowledge hereby that I have read general instructions for
                                application, procedure of admission, general code of conduct, hostel rules, examination
                                rules, anti-ragging guidelines issued by UGC or Dr. Shakuntala Misra National Rehabilitation
                                University and that I shall abide by them at all points of time. If my involvement in
                                activities relating to discipline in University is found evident, University shall have all
                                rights to take appropriate action against me. I also acknowledge that I am not suffering
                                from any contagious disease that poses potential threat to health and safety of students of
                                the University and shall always treat students with special needs (differently-abled), girls
                                students and economically/ socially deprived with compassion and cooperation.</p>
                            <div>
                                <p class="mt-4">
                                    <input type="checkbox" class="filled-in" name="is_agree" id="agree1"
                                        value="1">
                                    <label for="agree1" class="form-check-label"><strong>I Agree</strong><strong
                                            class="text-danger"> * </strong></label>
                                <div class="invalid-feedback text-danger is_agree_application"></div>
                                </p>
                            </div>

                            <!--

              <h5 class="front-form-head">Fee Details & Payment Submission</h5>
              <p><strong>Note:</strong> In case you get disconnected while making online payment or your payment gets failed, please login again with your registration ID and Password sent your email and Mobile to continue with the Online Payment process.</p>


              <h5 class="f-16"><strong><i class="fa fa-rupee"></i> 500/-</strong> Payable Amount </h3>
               <h5 class="f-16 border-bottom pb-3 mb-4"><strong><i class="fa fa-rupee"></i> 50/-</strong> Bank Transaction Charges</h3>
                <h4 class="f-18 mb-5 text-warning"><strong><i class="fa fa-rupee"></i> 550/-</strong> Total Amount </h3>



                 
                <div class="row">
                 <div class="col-md-12 mb-5">
                  <label for="">Payment Mode:<span class="text-danger">*</span></label>
                  <p class="newboxciradio">
                   <input type="radio" class="filled-in" name="payment" value="1" id="online">
                   <label for="online" class="form-check-label"><strong>Online (Credit card/Debit card/Net Banking )</strong></label>

                   <input type="radio" class="filled-in" name="payment" value="2" id="offline">
                   <label for="offline" class="form-check-label ml-5"><strong>Offline ( to Pay Offline the details given below)</strong></label>
                  <span class="text-danger">{{ $errors->first('payment') }}</span>
                  </p>
                 </div>
                </div>
                 
                 <div class="row mb-5">
                  <div class="col-md-6" id="bank_online">
                   <button class="btn btn-dark mt-4 " style="margin:auto;" type="button">make payment</button>
                  </div>
                 </div>

                <div class="row" id="bank_offline">

                 <h5 class="mb-3"><strong class="f-16">Bank Detail</strong></h5>
                 
                 <table class="table table-bordered" >
                  <tr>
                   <td width="20%">Account Holder<span class="text-danger">*</span></td>
                   <td>
                    <p class="f-16 text-black"><strong>DSMNRU</strong></p>
                   </td>
                  </tr>
                  <tr>
                   <td>Account Holder’s Name<span class="text-danger">*</span></td>
                   <td>
                    <p class="f-16 text-black"><strong>University</strong></p>
                   </td>
                  </tr>
                  <tr>
                   <td>Name of the Bank</td>
                   <td>
                    <p class="f-16 text-black"><strong>Axis Bank</strong></p>
                   </td>
                  </tr>
                  <tr>
                   <td>Account No.</td>
                   <td>
                    <p class="f-16 text-black"><strong>098787655655</strong></p>
                   </td>
                  </tr>
                  <tr>
                   <td>IFSC Code</td>
                   <td>
                    <p class="f-16 text-black"><strong>UTIB0000654</strong></p>
                   </td>
                  </tr>
                 </table>

                 <h5 class="mb-3"><strong class="f-16">After Payment: If Online than Auto else Candidate will fill offline Fee Details.</strong></h5>


                 <table class="table table-bordered bottomform">
                  <tr>
                   <td width="20%">ORDER ID <strong class="text-danger">* </strong></td>
                   <td>
                    <input type="text" class="form-control" name="order_id">
                    <div class="invalid-feedback text-danger order_id_application"></div>
                   </td>
                  </tr>
                  <tr>
                   <td>TRANSACTION ID <strong class="text-danger">* </strong></td>
                   <td>
                    <input type="text" class="form-control" name="transaction_id">
                    <div class="invalid-feedback text-danger transaction_id_application"></div>
                   </td>
                  </tr>
                  <tr>
                   <td>AMOUNT PAYABLE <strong class="text-danger">* </strong></td>
                   <td>
                    <input type="text" class="form-control numbersOnly" name="paid_amount" readonly value="550">
                    <div class="invalid-feedback text-danger paid_amount_application"></div>
                   </td>
                  </tr>
                  <tr>
                   <td>TXN DATE <strong class="text-danger">* </strong></td>
                   <td>
                    <input type="date" class="form-control" name="txn_date">
                    <div class="invalid-feedback text-danger txn_date_application"></div>
                   </td>
                  </tr>
                  <tr>
                   <td>TXN STATUS <strong class="text-danger">* </strong></td>
                   <td>
                    <input type="text" class="form-control" name="txn_status">
                    <div class="invalid-feedback text-danger txn_status_application"></div>
                   </td>
                  </tr>
                 </table>
                </div>-->
                            <div>
                                <div class="print_hide">



                                    <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                        {{-- <div class="col-md-6">
                                {!! RecaptchaV3::field('admitcard') !!}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div> --}}
                                    </div>
                                    <br />
                                    <button type="button" class="btn btn-default mr-3"><i class="fa fa-undo"
                                            aria-hidden="true"></i> Reset</button>
                                    <!-- <button type="button" id="priview" class="btn btn-primary mr-3"><i class="fa fa-undo"></i> Priview</button> -->

                                    <button type="submit" class="btn btn-warning">
                                        <i class="fa fa-send" aria-hidden="true"></i> Submit
                                    </button>
                                    <!-- <a id="download_application_form" class="btn btn-info" onClick="window.print();"><i class="fa fa-print"></i> Download and print the application form</a> -->
                                </div>


                            </div>



                </form>





            </div>
        </div>
    </section>
    <!--SECTION END-->

    <div id="error" title="Error!!!">

    </div>
    <!-- Success Alert Modal -->
    <div id="application-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-l modal-success">
            <div class="modal-content modal-filled bg-success">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-checkmark h1 text-white"></i>
                        <h4 class="mt-2 text-white" style="color:white;">Well Done!</h4>
                        <p class="mt-3 text-white" style="color:white;">Application Submitted Successfully.</p>
                        <!-- <a id="more_courses" class="btn btn-info my-2">Click For The Apply More Courses</a> -->
                        <a id="payment_url" class="btn btn-info my-2">Click For The Payment</a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div id="application-alert-modal-false" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-l modal-success">
            <div class="modal-content modal-filled bg-success">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-checkmark h1 text-white"></i>
                        <h4 class="mt-2 text-white" style="color:white;">Your Application Already Submitted For Selected
                            Course </h4>
                        <a id="dashboard" class="btn btn-info my-2">Click Here To Go To Dashboard</a>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

<script>
    window.onload = function() {
    var checkRadio = document.getElementById('check'); // "AFFILIATED COLLEGE" radio button
    var selectElement = document.getElementById('select'); // The select dropdown to show/hide
    
    if (selectElement) {
        // Initially hide the select element
        selectElement.style.display = "none";
        
        // Get all radio buttons with name 'collage'
        var radios = document.getElementsByName('collage');
        
        // Loop through the radio buttons and add the event listener
        for (var i = 0; i < radios.length; i++) {
            radios[i].addEventListener('change', function() {
                // If the "AFFILIATED COLLEGE" radio button is checked, show the select dropdown
                if (checkRadio.checked) {
                    selectElement.style.display = "block";  // Show the select element
                } else {
                    selectElement.style.display = "none";   // Hide the select element
                }
            });
        }
    }
};

     </script>
@section('scripts')
        <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script>
        /*============ Ajax Data Save ============*/

        $(document).ajaxStart(function() {
                $("#loading-image").show();
            })
            .ajaxStop(function() {
                $("#loading-image").hide();
            });

        $('#success-alert-modal').on('hidden.bs.modal', function() {
            window.location.href = "{{ route('user.dashboard') }}";
        })

        $('#myform_application').submit(function(e) {
            e.preventDefault();
            $('.error_application').text("").css({
                'display': 'none'
            });
            var formData = new FormData(this);
            $('.invalid-feedback').text('');
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                type: 'POST',
                url: "{{ url('application-form') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    //grecaptcha.reset();
                    console.log(data);
                    if (data.status == true) {
                        $('#application-alert-modal').addClass('show');
                        $('#application-alert-modal').addClass('in');
                        $('#more_courses').attr('href',
                            "{{ route('add-application-form') }}?application_id=" + data
                            .application_id);
                        $('#payment_url').attr('href', "{{ route('pay-now') }}?id=" + data
                            .application_id);
                    } else if (data.status == false) {
                        $('#application-alert-modal-false').addClass('show');
                        $('#application-alert-modal-false').addClass('in');
                        $('#dashboard').attr('href', "{{ route('user.dashboard') }}");


                    } else {
                        var first_error = '';
                        var counter = 0;
                        var errors = "";
                        $.each(data, function(index, val) {

                            if ($('.' + index + '_application').parent().length > 0) {

                                ++counter;
                                if (counter == 1) {
                                    first_error = index + '_application';
                                }
                                $('.' + index + '_application').text(val).css({
                                    'display': 'block'
                                });
                            } else {
                                errors += val + " <br/>";
                            }
                        });
                        if (errors) {
                            $('.error_application').html(errors).css({
                                'display': 'block'
                            });
                        }

                        if (first_error == '' & errors != "") {
                            first_error = 'error_application';
                        }

                        if ($("." + first_error).parent().length > 0) {

                            $('html, body').animate({
                                scrollTop: $("." + first_error).parent().offset().top - 100
                            }, 2000);
                        }
                    }
                },
                error: function(request, status, error) {
                    $('.error_application').text(error).css({
                        'display': 'block'
                    });
                }
            });
        });


        $(document).ready(function() {
            // $("#download_application_form").hide();
            $("#bank_offline").hide();
            $("#bank_online").hide();
            $("#affiliated").hide();
            $("#affiliated_collage").val(1)
            $("#domicile_cirtificate2").hide();
            $("#domicile_cirtificate1").hide();
            // $("#admission_applying_through").hide();
            // $("#hidelateral_entry").hide();
            $("#ded").hide();
            $(".jee").hide();
            $('.upsee').hide();



            $('#offline').on('click', function() {
                $("#bank_offline").show();
                $("#bank_online").hide();
            });

            $('#online').on('click', function() {
                $("#bank_offline").hide();
                $("#bank_online").show();
            });


            /*============ Address Code ============*/
            $("#is_correspondence_same").on("click", function() {
                if (this.checked) {
                    $('.correspondence_address_application,.correspondence_district_application').text('');
                    $('.correspondence_police_station_application,.correspondence_nearest_railway_station_application')
                        .text('');
                    $('.correspondence_country_application,.correspondence_state_union_territory_application')
                        .text('');
                    $('.correspondence_pin_code_application,.correspondence_mobile_no_application').text(
                        '');
                    $('.correspondence_landline_application').text('');
                    $("#correspondence_address").val($("#p_address").val());
                    $("#correspondence_district").html('<option >' + $("#p_district").val() + '</option>');
                    $("#correspondence_police_station").val($("#p_police_station").val());
                    $("#correspondence_nearest_railway_station").val($("#p_nearest_railway_station").val());
                    $("#correspondence_country").val($("#p_country").val());
                    $("#correspondence_state_union_territory").html('<option >' + $(
                        "#p_state_union_territory").val() + '</option>');
                    $("#correspondence_pin_code").val($("#p_pin_code").val());
                    $("#correspondence_mobile_no").val($("#p_mobile_no").val());
                    $("#correspondence_landline").val($("#p_landline").val());
                }
            });



            $('#applicatio_for2').on('click', function() {
                $("#affiliated").show();

            });
            $('#applicatio_for1').on('click', function() {
                $("#affiliated").hide();
                $("#affiliated_collage").val(1)

            });

            /*============ Course Type Code ============*/
            $('#course_type').change(function() {
                setCourse();
            });
            $('#campus_id').change(function() {
                setCourse();
            });
            $('.application_for').click(function() {
                setCourse();
            });
            setCollege();

            function setCollege() {
                var affiliated = $('.application_for:checked').val();
                if (affiliated == 1) {
                    $('.college_name').hide();
                    $(".college_name option[value=" + 1 + "]").show();
                    $(".college_name option[value=" + 1 + "]").prop('selected', true);
                } else {
                    $('.college_name').show();
                    $(".college_name option[value=" + 1 + "]").hide();
                    $(".college_name option[value=" + 1 + "]").prop('selected', false);
                }
            }

            function setCourse() {
                setCollege();
                var affiliated = $('.application_for:checked').val();
                var course_type = $('#course_type').val();
                var campus_id = $('#campus_id').val();
                if (affiliated == undefined) {
                    $('.application_for_application').text('The application for field is required.');
                    return false;
                }
                if (course_type == '') {
                    return false;
                }
                if (campus_id == '') {
                    $('.college_name_application').text('The College Name field is required.');
                    return false;
                } else {
                    $('.college_name_application').text('');
                }

                $("#course_id").find('option').remove().end();
                var formData = {
                    campus_id: campus_id,
                    affiliated: affiliated,
                    course_type: course_type,
                    "_token": "{{ csrf_token() }}"
                }; //Array 
                $.ajax({
                    url: "{{ url('application-course-list') }}",
                    type: "POST",
                    data: formData,
                    success: function(data, textStatus, jqXHR) {
                        $('#course_id').append(data);
                    },
                });
            }

            $('#correspondence_country').change(function() {
                var country_id = $('#correspondence_country').val();
                $("#correspondence_state_union_territory").find('option').remove().end();
                var formData = {
                    country_id: country_id,
                    "_token": "{{ csrf_token() }}"
                }; //Array 
                $.ajax({
                    url: "{{ url('get-state') }}",
                    type: "POST",
                    data: formData,
                    success: function(data, textStatus, jqXHR) {
                        $('#correspondence_state_union_territory').append(data);
                    },
                });

            });
            $('#correspondence_country').change(function() {
                correspondence_country();

            });
            $('#correspondence_state_union_territory').change(function() {
                var state_id = $('#correspondence_state_union_territory').val();
                $("#correspondence_district").find('option').remove().end();
                var formData = {
                    state_id: state_id,
                    "_token": "{{ csrf_token() }}"
                }; //Array 
                $.ajax({
                    url: "{{ url('get-district') }}",
                    type: "POST",
                    data: formData,
                    success: function(data, textStatus, jqXHR) {
                        $('#correspondence_district').append(data);
                    },
                });
            });
            $('#p_country').change(function() {
                var country_id = $('#p_country').val();
                $("#p_state_union_territory").find('option').remove().end();
                var formData = {
                    country_id: country_id,
                    "_token": "{{ csrf_token() }}"
                }; //Array 
                $.ajax({
                    url: "{{ url('get-state') }}",
                    type: "POST",
                    data: formData,
                    success: function(data, textStatus, jqXHR) {
                        $('#p_state_union_territory').append(data);
                    },
                });
            });
            $('#p_state_union_territory').change(function() {
                var state_id = $('#p_state_union_territory').val();
                $("#p_district").find('option').remove().end();
                var formData = {
                    state_id: state_id,
                    "_token": "{{ csrf_token() }}"
                }; //Array 
                $.ajax({
                    url: "{{ url('get-district') }}",
                    type: "POST",
                    data: formData,
                    success: function(data, textStatus, jqXHR) {
                        $('#p_district').append(data);
                    },
                });
            });
            $('#domicile').change(function() {

                var dom = $('#domicile').val();
                if (dom == '') {
                    return false;
                }
                if (dom == "Uttar Pradesh") {
                    $("#domicile2").val(dom);
                    $("#domicile1").hide();
                    $("#domicile2").hide();
                    $("#domicile_cirtificate2").show();
                    $("#domicile_cirtificate1").show();

                } else {
                    $("#domicile_cirtificate2").hide();
                    $("#domicile_cirtificate1").hide();
                    $("#domicile2").val("");
                    $("#domicile1").show();
                    $("#domicile2").show();
                }
            });



            $('#religion').change(function() {

                var dom = $('#religion').val();
                if (dom == '') {
                    return false;
                }
                if (dom == "Hindu") {
                    $("#religion2").val(dom);
                    $("#religion1").hide();
                    $("#religion2").hide();
                } else if (dom == "Muslim") {
                    $("#religion2").val(dom);
                    $("#religion1").hide();
                    $("#religion2").hide();
                } else if (dom == "Sikh") {
                    $("#religion2").val(dom);
                    $("#religion1").hide();
                    $("#religion2").hide();
                } else if (dom == "Christian") {
                    $("#religion2").val(dom);
                    $("#religion1").hide();
                    $("#religion2").hide();
                } else {
                    $("#religion2").val("");
                    $("#religion1").show();
                    $("#religion2").show();
                }
            });


            $('#nationality').change(function() {
                var nationality = $('#nationality').val();
                if (nationality == "Indian") {
                    $("#nationality1").hide();
                    $("#nationality11").hide();
                    $("#nationality_value").val('Indian');
                } else {
                    $("#nationality1").show();
                    $("#nationality11").show();
                    $("#nationality_value").val('');
                }
            });

            $('#enrollment').change(function() {
                var enrollment = $('#enrollment').val();
                if (enrollment == "Yes") {
                    $("#enrollment1").show();
                    $("#enrollment11").show();
                    $("#enrollment_value").val('');
                } else {
                    $("#enrollment1").hide();
                    $("#enrollment11").hide();
                    $("enrollment_value").val('Yes');
                }
            });

            $('#freedom_fighter_dependent').change(function() {
                var freedom_fighter_dependent = $('#freedom_fighter_dependent').val();
                if (freedom_fighter_dependent == "yes") {
                    $("#freedom_fighter_dependent1").show();
                    $("#freedom_fighter_dependent11").show();
                    $("#freedom_fighter_dependent_value").val('');
                } else {
                    $("#freedom_fighter_dependent1").hide();
                    $("#freedom_fighter_dependent11").hide();
                    $("freedom_fighter_dependent_value").val('yes');
                }
            });

            $('#ncc_cirtificate').change(function() {
                var ncc_cirtificate = $('#ncc_cirtificate').val();
                if (ncc_cirtificate == "yes") {
                    $("#ncc_cirtificate1").show();
                    $("#ncc_cirtificate11").show();
                    $("#ncc_cirtificate_value").val('');
                } else {
                    $("#ncc_cirtificate1").hide();
                    $("#ncc_cirtificate11").hide();
                    $("ncc_cirtificate_value").val('yes');
                }
            });

            $('#nss_cirtificate').change(function() {
                var nss_cirtificate = $('#nss_cirtificate').val();
                if (nss_cirtificate == "yes") {
                    $("#nss_cirtificate1").show();
                    $("#nss_cirtificate11").show();
                    $("#nss_cirtificate_value").val('');
                } else {
                    $("#nss_cirtificate1").hide();
                    $("#nss_cirtificate11").hide();
                    $("nss_cirtificate_value").val('yes');
                }
            });

            $('#sports').change(function() {
                var sports = $('#sports').val();
                if (sports == "yes") {
                    $("#sports1").show();
                    $("#sports11").show();
                    $("#sportt_cirtificate1").show();
                    $("#sportt_cirtificate11").show();
                    $("#sports_value").val('');
                } else {
                    $("#sports1").hide();
                    $("#sports11").hide();
                    $("#sportt_cirtificate1").hide();
                    $("#sportt_cirtificate11").hide();
                    $("sports_value").val('yes');
                }
            });

            $('#hostal_options').change(function() {
                var hostal_options = $('#hostal_options').val();
                if (hostal_options == "yes") {
                    $("#hostal_options1").show();
                    $("#hostal_options11").show();
                    $("#hostel_distence1").show();
                    $("#hostel_distence11").show();
                    // $("#sports_value").val('');
                } else {
                    $("#hostal_options1").hide();
                    $("#hostal_options11").hide();
                    $("#hostel_distence1").hide();
                    $("#hostel_distence11").hide();
                    // $("sports_value").val('yes');
                }
            });

            $("input").keypress(function() {
                $(this).parent().find('.invalid-feedback').text('');
            });

            $("select").keypress(function() {
                $(this).parent().find('.invalid-feedback').text('');
            });


            $("input").on('change', function() {
                $(this).parent().find('.invalid-feedback').text('');
            });

            $("select").on('change', function() {
                $(this).parent().find('.invalid-feedback').text('');
            });

            $('input:radio, input:checkbox').on('click', function() {
                $(this).parent().parent().find('.invalid-feedback').text('');
            });

            $('input[type=file]').on('change', function() {

                var count = 0;
                var max_size = 100; /* max size in kb */
                const size = (this.files[0].size / 1024 / 1024).toFixed(2);

                var size_in_kb = Math.floor(this.files[0].size / 1000);
                if (size_in_kb > max_size) {
                    // $("#error").dialog().text('File must be less than the size of '+max_size+' KB');
                    // //alert("File must be less than the size of 500 KB");
                    // $(this).closest('input').val('');
                }
            });

            $('.numbersOnly').keyup(function() {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            });

        });



        function addNewEducation($this) {
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                type: 'GET',
                url: "{{ route('education-single-row') }}?rows=" + $('.educationtable tbody tr').length,
                success: function(data) {
                    console.log(data.html);
                    $('.educationtable tbody tr:last').after(data.html);
                }
            });

        }

        function delete_education($this) {
            $this.closest('tr').remove();
        }

        function check_caste_certificate_number(category) {
            if (category == '' || category == 'General') {
                $('.certificate_no_text').hide();
            } else {
                $('.certificate_no_text').show();

            }
        }

        function change_disability_category(disability) {
            if (disability == '') {
                $('.disability_certificate_no_text').hide();
            } else {
                $('.disability_certificate_no_text').show();

            }
        }

        function disability_cat_open(disabilityesno) {
            if (disabilityesno == 'yes') {
                $('.disability_category_box').show();
            } else {
                $('.disability_category_box').hide();
                $('.disability_certificate_no_text').hide();
                $("#disability_category")[0].selectedIndex = 0;
            }
        }

        function open_dsmnru_relationship(dsmnrurelation) {
            if (dsmnrurelation == 'yes') {
                $('.dsmnru_relationship').show();
            } else {
                $('.dsmnru_relationship').hide();
                // $("#disability_category")[0].selectedIndex = 0;
            }
        }

        function set_name_and_relation($this) {
            if ($this.val() == 'yes') {
                $('.ward_emp_name_and_relation').show();
            } else {
                $('.ward_emp_name_and_relation').hide();
                $('.ward_emp_name_and_relation').find('input').val('');
            }
        }

        $(document).ready(function() {
            $('#p_country').val('India');
            var country = 'India';
            //	$("#p_state_union_territory").val('Uttar Pradesh');
            p_country();

        });

        function p_country() {
            var country_id = $('#p_country').val();
            $("#p_state_union_territory").find('option').remove().end();
            var formData = {
                country_id: country_id,
                "_token": "{{ csrf_token() }}"
            }; //Array 
            $.ajax({
                url: "{{ url('get-state') }}",
                type: "POST",
                data: formData,
                success: function(data, textStatus, jqXHR) {
                    $('#p_state_union_territory').append(data);
                    p_state(country_id);
                    p_district();
                },
            });
        }

        function p_state(country) {
            if (country == 'India') {
                //alert(country);
                $("#p_state_union_territory").val('Uttar Pradesh');
            }
        }

        function p_district() {
            $state = 'Uttar Pradesh';
            var state_id = 'Uttar Pradesh';
            $("#p_district").find('option').remove().end();
            var formData = {
                state_id: state_id,
                "_token": "{{ csrf_token() }}"
            }; //Array 
            $.ajax({
                url: "{{ url('get-district') }}",
                type: "POST",
                data: formData,
                success: function(data, textStatus, jqXHR) {
                    $('#p_district').append(data);
                },
            });


        }

        function btech(value) {
            var application_for = $('.application_for:checked').val();
            var academic_session = $('#academic_session').val();
            var course_type = $('#course_type').val();
            var course_id = $('#course_id').val();
            // reload current page for the selected course 
            var url_with_course = "{{ url('application-form') }}?application_for=" + application_for +
                "&academic_session=" + academic_session + "&course_type=" + course_type + "&course_id=" + course_id;
            window.location.href = url_with_course;
        }

        function admissionthrough() {
            var admission_through = $('#admission_through').val();
            var appeared_or_passed = $('#appeared_or_passed').val();
            if (admission_through == '' || appeared_or_passed == 'Appeared') {
                $('.jee').hide();
            } else {
                $('.jee').show();
                $('.admission_through_exam_name_class').hide();
                if (admission_through == 'OTHER STATE LEVEL EXAM') {
                    $('.admission_through_exam_name_class').show();
                }
            }
            if (appeared_or_passed == 'Appeared') {
                $('.jeee_roll_number_class').show();
            }
            $('.appeared_or_passed_class').show();
        }

        /*=============Education Code =========*/
        function calculate_marks($this, cgpa_check) {
            var current_tr = $this.closest('tr');
            $this.closest('tr').find('.cgpa_document').prop('required', false);
            $this.closest('tr').find('.cgpa_document_container, .cgpa_document').hide();
            if (current_tr.find('.cgpa_or_marks:checked').val() == 1) {
                current_tr.find('.equivalent_percentage').prop('readonly', true);
                var total_marks_cgpa = parseFloat(current_tr.find('.total_marks_cgpa').val());
                var cgpa_optain_marks = parseFloat(current_tr.find('.cgpa_optain_marks').val());
                if (total_marks_cgpa < cgpa_optain_marks) {
                    current_tr.find('.cgpa_optain_marks').val('');
                    return false;
                }
                if (Number.isInteger(total_marks_cgpa) && Number.isInteger(cgpa_optain_marks)) {
                    var equivalent_percentage = ((cgpa_optain_marks / total_marks_cgpa) * 100);
                    current_tr.find('.equivalent_percentage').val(equivalent_percentage.toFixed(2));
                } else {
                    current_tr.find('.equivalent_percentage').val('');
                }
            } else {
                $this.closest('tr').find('.cgpa_document').prop('required', true);
                $this.closest('tr').find('.cgpa_document_container, .cgpa_document').show();
                var total_marks_cgpa = parseFloat(current_tr.find('.total_marks_cgpa').val());
                var cgpa_optain_marks = parseFloat(current_tr.find('.cgpa_optain_marks').val());
                if (total_marks_cgpa < cgpa_optain_marks) {
                    current_tr.find('.cgpa_optain_marks').val('');
                    return false;
                }


                current_tr.find('.equivalent_percentage').prop('readonly', false);
                if (cgpa_check == true) {
                    current_tr.find('.total_marks_cgpa').val('');
                    current_tr.find('.cgpa_optain_marks').val('');
                    current_tr.find('.equivalent_percentage').val('');
                }
            }
        }

        function set_passing_status($this) {
            var passing_status_true = $this.closest('tr').find('.passing_status_true');
            if ($this.val() == 2) {
                passing_status_true.find('input').fadeOut();
                passing_status_true.find('.passing_status_hide').fadeOut();
                $('.education_btn').fadeOut();
                passing_status_true.find('input').prop('required', false);
            } else {
                passing_status_true.find('input').fadeIn();
                passing_status_true.find('.passing_status_hide').fadeIn();
                $('.education_btn').fadeIn();
                passing_status_true.find('input').prop('required', true);
            }
        }

        function max_percentage($this) {
            if (parseFloat($this.val()) > 100) {
                $this.val('');
            }
        }

        $('.percentage_type').change(function() {
            max_percentage($(this));
        });


        $("#priview").on("click", function() {
            $("body").scrollTop(0);
        });

        checkLeteralCourse();

        function checkLeteralCourse() {
            var lateral_entry = $('#lateral_entry').val();
            if (lateral_entry == 'yes') {
                $('.forLateralCourses').hide();
                $('.forLateralCouseText1').text('Select Department');
                $('.forLateralCouseText2').show();
                $('.forLateralCouseText3').hide();
                $('#admission_applying_through').hide();
            } else {
                $('.forLateralCouseText1').text('Course Preference');
                $('.forLateralCouseText2').hide();
                $('.forLateralCouseText3').show();
                $('.forLateralCourses').show();
                $('#admission_applying_through').show();
            }
        }
    </script>
@endsection



@endsection
