@extends('ums.admin.admin-meta')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content todo-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            <div class="content-header row">
                <div class="content-header-left col-md-6  mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Application Form</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a>
                                    </li>

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <div class="form-group breadcrumb-right">
                            <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50"
                                onclick="window.location.reload();"><i data-feather="refresh-cw"></i>Reset</button>
                            <button type="button"
                                class="btn btn-primary box-shadow-2 btn-sm me-1 mb-sm-0 mb-50 data-submit">Submit</button>

                        </div>
                    </div>
                </div>
                @include('ums.admin.notifications')

                <div class="card">
                    <div class="card-body customernewsection-form">
                        <form method="POST" action="{{ url('application-form') }}" id="myform_application"
                            enctype="multipart/form-data" autocomplete="off">

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
                    </div>
                </div>

                <div class="card">
                    <div class="card-body customernewsection-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                                    <div>
                                        <h4 class="card-title text-theme">Program Details</h4>
                                        <p class="card-text">Fill the details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row align-items-center mb-1 exisitng">
                                    <div class="col-md-3">
                                        <label class="form-label">
                                            Academic Session<span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <select class="form-select select2" name="academic_session" id="academic_session">
                                            <!-- <option value="">--Select Academic Session--</option> -->
                                            <!-- <option value="2022-2023">2022-2023</option> -->
                                            <option value="2023-2024">2023-2024</option>
                                            <!-- <option value="2024-2025">2024-2025</option> -->
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row align-items-center mb-1 exisitng">
                                    <div class="col-md-3">
                                        <label class="form-label">
                                            Programme/Course Type<span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <select class=" form-control select2 select2" name="course_type" id="course_type">
                                            <option value="">--Select Program--</option>
                                            @foreach ($programm_types as $programm_type)
                                                <option value="{{ $programm_type->id }}"
                                                    @if ($programm_type_id == $programm_type->id) selected @endif>
                                                    {{ $programm_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row align-items-center mb-1 exisitng">
                                    <div class="col-md-3">
                                        <label class="form-label">
                                            Name of Programme/Course<span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <select class=" form-control select2" name="course_id" id="course_id"
                                            onChange="btech($(this).val())">
                                            <option value="">--Select Course--</option>
                                            @foreach ($courses as $course)
                                                @if ($course_id == $course->id)
                                                    <option value="{{ $course->id }}"
                                                        @if ($course_id == $course->id) selected @endif>
                                                        {{ $course->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4" id="hidelateral_entry" style="display: none;">
                                        <label for="">Lateral Entry <span class="text-danger">*</span></label>
                                        <select class=" form-control select2" name="lateral_entry" id="lateral_entry"
                                            onchange="checkLeteralCourse()">
                                            @if (Request()->course_id == 126)
                                                <option value="yes">Yes</option>
                                            @else
                                                <option value="no">No</option>
                                            @endif
                                        </select>
                                        <div class="invalid-feedback text-danger lateral_entry_application"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body customernewsection-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                                    <div>
                                        <h4 class="card-title text-theme">Student Details</h4>
                                        <p class="card-text">Fill the details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class=" form-control  uppercase" name="student_first_Name"
                                        value="{{ Auth::user()->first_name }}" maxlength="20" required readonly />

                                </div>


                                <div class="col-md-1">
                                    <label class="form-label">Middle Name </label>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class=" form-control  uppercase" name="student_middle_Name"
                                        value="{{ Auth::user()->middle_name }}" maxlength="20" readonly />
                                    <div class="invalid-feedback text-danger student_middle_Name_application"></div>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Last Name </label>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class=" form-control  uppercase" name="student_last_Name"
                                        value="{{ Auth::user()->last_name }}" maxlength="20" readonly />
                                    <div class="invalid-feedback text-danger student_last_Name_application"></div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">DOB <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Date of Birth <span class="text-danger">*</span></label>
                                    @php $lat_10_year = date('Y')-10; @endphp
                                    @php $max_dob = $lat_10_year.date('-m-d'); @endphp
                                    <input type="date" class=" form-control " name="date_of_birth"
                                        max="{{ $max_dob }}" />
                                    <div class="invalid-feedback text-danger date_of_birth_application"></div>
                                </div>


                                <div class="col-md-1">
                                    <label class="form-label">E-mail ID <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="email" class=" form-control " name="student_email"
                                        value="{{ Auth::user()->email }}" readonly />
                                    <div class="invalid-feedback text-danger student_email_application"></div>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Mobile No.<span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class=" form-control  numbersOnly" name="student_mobile"
                                        maxlength="10" value="{{ Auth::user()->mobile }}" readonly />
                                    <div class="invalid-feedback text-danger student_mobile_application"></div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class=" form-control  uppercase" name="father_name"
                                        maxlength="30" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger father_name_application"></div>
                                </div>


                                <div class="col-md-1">
                                    <label class="form-label">Father's Mobile No. <span
                                            class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class=" form-control  numbersOnly" name="father_mobile"
                                        maxlength="10" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger father_mobile_application"></div>
                                </div>

                            </div>
                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class=" form-control  uppercase" name="mother_name"
                                        maxlength="30" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger mother_name_application"></div>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Mother's Mobile No. <span
                                            class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class=" form-control  numbersOnly" name="mother_mobile"
                                        maxlength="10" autocomplete="__away" />
                                    <div class="invalid-feedback text-danger mother_mobile_application"></div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body customernewsection-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                                    <div>
                                        <h4 class="card-title text-theme">Personal Information</h4>
                                        <p class="card-text">Fill the details</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <select class=" form-control select2" id="gender" name="gender">

                                        <option value="">--Select Gender--</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Transgender">Transgender</option>
                                    </select>
                                </div>


                                <div class="col-md-1">
                                    <label class="form-label">Religion </label>
                                </div>

                                <div class="col-md-3">
                                    <select class=" form-control select2" id="religion">
                                        <option value="">--Select Religion--</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Muslim">Muslim</option>
                                        <option value="Sikh">Sikh</option>
                                        <option value="Christian">Christian</option>
                                        <option value="Other">Other</option>

                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Marital Status </label>
                                </div>

                                <div class="col-md-3">
                                    <select class=" form-control select2" id="marital_status" name="marital_status">
                                        <option value="">--Select Marital Status--</option>
                                        <option value="Married">Married</option>
                                        <option value="Unmarried">Unmarried</option>
                                        <option value="Divorcee">Divorcee</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">Blood Group</label>
                                </div>

                                <div class="col-md-3">
                                    <select class=" form-control select2" id="blood_group" name="blood_group">

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
                                </div>


                                <div class="col-md-1">
                                    <label class="form-label">Nationality <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <select class=" form-control select2" id="nationality">
                                        <option value="Indian" selected="">Indian</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>

                                <div class="col-md-1">
                                    <label class="form-label">Domicile<span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <select class=" form-control select2" id="domicile">

                                        <option value="">--Select Domicile--</option>
                                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">DSMNRU Student?
                                        <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <select class=" form-control select2" id="enrollment" name="dsmnru_student">
                                        <option value="">--Select Option--</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>


                                <div class="col-md-1">
                                    <label class="form-label">Aadhar Number <span class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="number" class=" form-control">
                                </div>

                            </div>


                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body customernewsection-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                                    <div>
                                        <h4 class="card-title text-theme">Category</h4>
                                        <p class="card-text text-danger">
                                            (File can be uploaded only in JPG, PNG, or PDF format from 200KB to 500KB)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="category" name="category"
                                        onchange="check_caste_certificate_number($(this).val())">
                                        <option value="">--Select Category--</option>
                                        <option value="General">General</option>
                                        <option value="OBC">OBC</option>
                                        <option value="SC">SC</option>
                                        <option value="ST">ST</option>
                                        <option value="EWS">EWS</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">DSMNRU Employee</label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="dsmnru_employee" name="dsmnru_employee"
                                        onchange="open_dsmnru_relationship($(this).val())">
                                        <option value="">--Select Option--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">DSMNRU Employee Ward</label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="dsmnru_employee_ward" name="dsmnru_employee_ward"
                                        onchange="set_name_and_relation($(this))">
                                        <option value="">--Select Option--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">Disability</label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="disability" name="disability"
                                        onchange="disability_cat_open($(this).val())">
                                        <option value="">--Select Option--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Freedom Fighter Dependent <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="freedom_fighter_dependent"
                                        name="freedom_fighter_dependent">
                                        <option value="">--Select Option--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">NCC (C-Certificate) <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="freedom_fighter_dependent"
                                        name="freedom_fighter_dependent">
                                        <option value="">--Select Option--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">NSS (240 hrs and 1 camp) <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="nss_cirtificate" name="nss">
                                        <option value="">--Select Option--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Sports <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="nss_cirtificate" name="nss">
                                        <option value="">--Select Option--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Hostel Facility Required <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="nss_cirtificate" name="nss">
                                        <option value="">--Select Option--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body customernewsection-form">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                                    <div>
                                        <h4 class="card-title text-theme">Permanent Address</h4>

                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <input type="address" class=" form-control ">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Police Station</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class=" form-control ">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">Nearest Railway Station</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class=" form-control ">
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">Country</label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="p_country" name="country">
                                        <option value="">--Select Option--</option>
                                        <option value="Afghanistan">Afghanistan </option>
                                        <option value="Albania">Albania </option>
                                        <option value="Algeria">Algeria </option>
                                        <option value="American Samoa">American Samoa </option>
                                        <option value="Andorra">Andorra </option>
                                        <option value="Angola">Angola </option>
                                        <option value="Anguilla">Anguilla </option>
                                        <option value="Antarctica">Antarctica </option>
                                        <option value="Antigua And Barbuda">Antigua And Barbuda </option>
                                        <option value="Argentina">Argentina </option>
                                        <option value="Armenia">Armenia </option>
                                        <option value="Aruba">Aruba </option>
                                        <option value="Australia">Australia </option>
                                        <option value="Austria">Austria </option>
                                        <option value="Azerbaijan">Azerbaijan </option>
                                        <option value="Bahamas The">Bahamas The </option>
                                        <option value="Bahrain">Bahrain </option>
                                        <option value="Bangladesh">Bangladesh </option>
                                        <option value="Barbados">Barbados </option>
                                        <option value="Belarus">Belarus </option>
                                        <option value="Belgium">Belgium </option>
                                        <option value="Belize">Belize </option>
                                        <option value="Benin">Benin </option>
                                        <option value="Bermuda">Bermuda </option>
                                        <option value="Bhutan">Bhutan </option>
                                        <option value="Bolivia">Bolivia </option>
                                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina </option>
                                        <option value="Botswana">Botswana </option>
                                        <option value="Bouvet Island">Bouvet Island </option>
                                        <option value="Brazil">Brazil </option>
                                        <option value="British Indian Ocean Territory">British Indian Ocean Territory
                                        </option>
                                        <option value="Brunei">Brunei </option>
                                        <option value="Bulgaria">Bulgaria </option>
                                        <option value="Burkina Faso">Burkina Faso </option>
                                        <option value="Burundi">Burundi </option>
                                        <option value="Cambodia">Cambodia </option>
                                        <option value="Cameroon">Cameroon </option>
                                        <option value="Canada">Canada </option>
                                        <option value="Cape Verde">Cape Verde </option>
                                        <option value="Cayman Islands">Cayman Islands </option>
                                        <option value="Central African Republic">Central African Republic </option>
                                        <option value="Chad">Chad </option>
                                        <option value="Chile">Chile </option>
                                        <option value="China">China </option>
                                        <option value="Christmas Island">Christmas Island </option>
                                        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands </option>
                                        <option value="Colombia">Colombia </option>
                                        <option value="Comoros">Comoros </option>
                                        <option value="Republic Of The Congo">Republic Of The Congo </option>
                                        <option value="Democratic Republic Of The Congo">Democratic Republic Of The
                                            Congo </option>
                                        <option value="Cook Islands">Cook Islands </option>
                                        <option value="Costa Rica">Costa Rica </option>
                                        <option value="Cote D'Ivoire (Ivory Coast)">Cote D'Ivoire (Ivory Coast)
                                        </option>
                                        <option value="Croatia (Hrvatska)">Croatia (Hrvatska) </option>
                                        <option value="Cuba">Cuba </option>
                                        <option value="Cyprus">Cyprus </option>
                                        <option value="Czech Republic">Czech Republic </option>
                                        <option value="Denmark">Denmark </option>
                                        <option value="Djibouti">Djibouti </option>
                                        <option value="Dominica">Dominica </option>
                                        <option value="Dominican Republic">Dominican Republic </option>
                                        <option value="East Timor">East Timor </option>
                                        <option value="Ecuador">Ecuador </option>
                                        <option value="Egypt">Egypt </option>
                                        <option value="El Salvador">El Salvador </option>
                                        <option value="Equatorial Guinea">Equatorial Guinea </option>
                                        <option value="Eritrea">Eritrea </option>
                                        <option value="Estonia">Estonia </option>
                                        <option value="Ethiopia">Ethiopia </option>
                                        <option value="External Territories of Australia">External Territories of
                                            Australia </option>
                                        <option value="Falkland Islands">Falkland Islands </option>
                                        <option value="Faroe Islands">Faroe Islands </option>
                                        <option value="Fiji Islands">Fiji Islands </option>
                                        <option value="Finland">Finland </option>
                                        <option value="France">France </option>
                                        <option value="French Guiana">French Guiana </option>
                                        <option value="French Polynesia">French Polynesia </option>
                                        <option value="French Southern Territories">French Southern Territories
                                        </option>
                                        <option value="Gabon">Gabon </option>
                                        <option value="Gambia The">Gambia The </option>
                                        <option value="Georgia">Georgia </option>
                                        <option value="Germany">Germany </option>
                                        <option value="Ghana">Ghana </option>
                                        <option value="Gibraltar">Gibraltar </option>
                                        <option value="Greece">Greece </option>
                                        <option value="Greenland">Greenland </option>
                                        <option value="Grenada">Grenada </option>
                                        <option value="Guadeloupe">Guadeloupe </option>
                                        <option value="Guam">Guam </option>
                                        <option value="Guatemala">Guatemala </option>
                                        <option value="Guernsey and Alderney">Guernsey and Alderney </option>
                                        <option value="Guinea">Guinea </option>
                                        <option value="Guinea-Bissau">Guinea-Bissau </option>
                                        <option value="Guyana">Guyana </option>
                                        <option value="Haiti">Haiti </option>
                                        <option value="Heard and McDonald Islands">Heard and McDonald Islands </option>
                                        <option value="Honduras">Honduras </option>
                                        <option value="Hong Kong S.A.R.">Hong Kong S.A.R. </option>
                                        <option value="Hungary">Hungary </option>
                                        <option value="Iceland">Iceland </option>
                                        <option value="India">India </option>
                                        <option value="Indonesia">Indonesia </option>
                                        <option value="Iran">Iran </option>
                                        <option value="Iraq">Iraq </option>
                                        <option value="Ireland">Ireland </option>
                                        <option value="Israel">Israel </option>
                                        <option value="Italy">Italy </option>
                                        <option value="Jamaica">Jamaica </option>
                                        <option value="Japan">Japan </option>
                                        <option value="Jersey">Jersey </option>
                                        <option value="Jordan">Jordan </option>
                                        <option value="Kazakhstan">Kazakhstan </option>
                                        <option value="Kenya">Kenya </option>
                                        <option value="Kiribati">Kiribati </option>
                                        <option value="Korea North">Korea North </option>
                                        <option value="Korea South">Korea South </option>
                                        <option value="Kuwait">Kuwait </option>
                                        <option value="Kyrgyzstan">Kyrgyzstan </option>
                                        <option value="Laos">Laos </option>
                                        <option value="Latvia">Latvia </option>
                                        <option value="Lebanon">Lebanon </option>
                                        <option value="Lesotho">Lesotho </option>
                                        <option value="Liberia">Liberia </option>
                                        <option value="Libya">Libya </option>
                                        <option value="Liechtenstein">Liechtenstein </option>
                                        <option value="Lithuania">Lithuania </option>
                                        <option value="Luxembourg">Luxembourg </option>
                                        <option value="Macau S.A.R.">Macau S.A.R. </option>
                                        <option value="Macedonia">Macedonia </option>
                                        <option value="Madagascar">Madagascar </option>
                                        <option value="Malawi">Malawi </option>
                                        <option value="Malaysia">Malaysia </option>
                                        <option value="Maldives">Maldives </option>
                                        <option value="Mali">Mali </option>
                                        <option value="Malta">Malta </option>
                                        <option value="Man (Isle of)">Man (Isle of) </option>
                                        <option value="Marshall Islands">Marshall Islands </option>
                                        <option value="Martinique">Martinique </option>
                                        <option value="Mauritania">Mauritania </option>
                                        <option value="Mauritius">Mauritius </option>
                                        <option value="Mayotte">Mayotte </option>
                                        <option value="Mexico">Mexico </option>
                                        <option value="Micronesia">Micronesia </option>
                                        <option value="Moldova">Moldova </option>
                                        <option value="Monaco">Monaco </option>
                                        <option value="Mongolia">Mongolia </option>
                                        <option value="Montserrat">Montserrat </option>
                                        <option value="Morocco">Morocco </option>
                                        <option value="Mozambique">Mozambique </option>
                                        <option value="Myanmar">Myanmar </option>
                                        <option value="Namibia">Namibia </option>
                                        <option value="Nauru">Nauru </option>
                                        <option value="Nepal">Nepal </option>
                                        <option value="Netherlands Antilles">Netherlands Antilles </option>
                                        <option value="Netherlands The">Netherlands The </option>
                                        <option value="New Caledonia">New Caledonia </option>
                                        <option value="New Zealand">New Zealand </option>
                                        <option value="Nicaragua">Nicaragua </option>
                                        <option value="Niger">Niger </option>
                                        <option value="Nigeria">Nigeria </option>
                                        <option value="Niue">Niue </option>
                                        <option value="Norfolk Island">Norfolk Island </option>
                                        <option value="Northern Mariana Islands">Northern Mariana Islands </option>
                                        <option value="Norway">Norway </option>
                                        <option value="Oman">Oman </option>
                                        <option value="Pakistan">Pakistan </option>
                                        <option value="Palau">Palau </option>
                                        <option value="Palestinian Territory Occupied">Palestinian Territory Occupied
                                        </option>
                                        <option value="Panama">Panama </option>
                                        <option value="Papua new Guinea">Papua new Guinea </option>
                                        <option value="Paraguay">Paraguay </option>
                                        <option value="Peru">Peru </option>
                                        <option value="Philippines">Philippines </option>
                                        <option value="Pitcairn Island">Pitcairn Island </option>
                                        <option value="Poland">Poland </option>
                                        <option value="Portugal">Portugal </option>
                                        <option value="Puerto Rico">Puerto Rico </option>
                                        <option value="Qatar">Qatar </option>
                                        <option value="Reunion">Reunion </option>
                                        <option value="Romania">Romania </option>
                                        <option value="Russia">Russia </option>
                                        <option value="Rwanda">Rwanda </option>
                                        <option value="Saint Helena">Saint Helena </option>
                                        <option value="Saint Kitts And Nevis">Saint Kitts And Nevis </option>
                                        <option value="Saint Lucia">Saint Lucia </option>
                                        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon </option>
                                        <option value="Saint Vincent And The Grenadines">Saint Vincent And The
                                            Grenadines </option>
                                        <option value="Samoa">Samoa </option>
                                        <option value="San Marino">San Marino </option>
                                        <option value="Sao Tome and Principe">Sao Tome and Principe </option>
                                        <option value="Saudi Arabia">Saudi Arabia </option>
                                        <option value="Senegal">Senegal </option>
                                        <option value="Serbia">Serbia </option>
                                        <option value="Seychelles">Seychelles </option>
                                        <option value="Sierra Leone">Sierra Leone </option>
                                        <option value="Singapore">Singapore </option>
                                        <option value="Slovakia">Slovakia </option>
                                        <option value="Slovenia">Slovenia </option>
                                        <option value="Smaller Territories of the UK">Smaller Territories of the UK
                                        </option>
                                        <option value="Solomon Islands">Solomon Islands </option>
                                        <option value="Somalia">Somalia </option>
                                        <option value="South Africa">South Africa </option>
                                        <option value="South Georgia">South Georgia </option>
                                        <option value="South Sudan">South Sudan </option>
                                        <option value="Spain">Spain </option>
                                        <option value="Sri Lanka">Sri Lanka </option>
                                        <option value="Sudan">Sudan </option>
                                        <option value="Suriname">Suriname </option>
                                        <option value="Svalbard And Jan Mayen Islands">Svalbard And Jan Mayen Islands
                                        </option>
                                        <option value="Swaziland">Swaziland </option>
                                        <option value="Sweden">Sweden </option>
                                        <option value="Switzerland">Switzerland </option>
                                        <option value="Syria">Syria </option>
                                        <option value="Taiwan">Taiwan </option>
                                        <option value="Tajikistan">Tajikistan </option>
                                        <option value="Tanzania">Tanzania </option>
                                        <option value="Thailand">Thailand </option>
                                        <option value="Togo">Togo </option>
                                        <option value="Tokelau">Tokelau </option>
                                        <option value="Tonga">Tonga </option>
                                        <option value="Trinidad And Tobago">Trinidad And Tobago </option>
                                        <option value="Tunisia">Tunisia </option>
                                        <option value="Turkey">Turkey </option>
                                        <option value="Turkmenistan">Turkmenistan </option>
                                        <option value="Turks And Caicos Islands">Turks And Caicos Islands </option>
                                        <option value="Tuvalu">Tuvalu </option>
                                        <option value="Uganda">Uganda </option>
                                        <option value="Ukraine">Ukraine </option>
                                        <option value="United Arab Emirates">United Arab Emirates </option>
                                        <option value="United Kingdom">United Kingdom </option>
                                        <option value="United States">United States </option>
                                        <option value="United States Minor Outlying Islands">United States Minor
                                            Outlying Islands </option>
                                        <option value="Uruguay">Uruguay </option>
                                        <option value="Uzbekistan">Uzbekistan </option>
                                        <option value="Vanuatu">Vanuatu </option>
                                        <option value="Vatican City State (Holy See)">Vatican City State (Holy See)
                                        </option>
                                        <option value="Venezuela">Venezuela </option>
                                        <option value="Vietnam">Vietnam </option>
                                        <option value="Virgin Islands (British)">Virgin Islands (British) </option>
                                        <option value="Virgin Islands (US)">Virgin Islands (US) </option>
                                        <option value="Wallis And Futuna Islands">Wallis And Futuna Islands </option>
                                        <option value="Western Sahara">Western Sahara </option>
                                        <option value="Yemen">Yemen </option>
                                        <option value="Yugoslavia">Yugoslavia </option>
                                        <option value="Zambia">Zambia </option>
                                        <option value="Zimbabwe">Zimbabwe </option>

                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">State/Union Territory <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="p_state_union_territory"
                                        name="state_union_territory">

                                        <option value="">--Select State--</option>
                                        <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands
                                        </option>
                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                        <option value="Assam">Assam</option>
                                        <option value="Bihar">Bihar</option>
                                        <option value="Chandigarh">Chandigarh</option>
                                        <option value="Chhattisgarh">Chhattisgarh</option>
                                        <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli
                                            and Daman and Diu</option>
                                        <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli
                                            and Daman and Diu</option>
                                        <option value="Delhi">Delhi</option>
                                        <option value="Goa">Goa</option>
                                        <option value="Gujarat">Gujarat</option>
                                        <option value="Haryana">Haryana</option>
                                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                                        <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                        <option value="Jharkhand">Jharkhand</option>
                                        <option value="Karnataka">Karnataka</option>
                                        <option value="Kerala">Kerala</option>
                                        <option value="Lakshadweep">Lakshadweep</option>
                                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                                        <option value="Maharashtra">Maharashtra</option>
                                        <option value="Manipur">Manipur</option>
                                        <option value="Meghalaya">Meghalaya</option>
                                        <option value="Mizoram">Mizoram</option>
                                        <option value="Nagaland">Nagaland</option>
                                        <option value="Nagaland">Nagaland</option>
                                        <option value="Odisha">Odisha</option>
                                        <option value="Puducherry	">Puducherry </option>
                                        <option value="Punjab">Punjab</option>
                                        <option value="Rajasthan">Rajasthan</option>
                                        <option value="Sikkim">Sikkim</option>
                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                        <option value="Telangana">Telangana</option>
                                        <option value="Tripura">Tripura</option>
                                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                                        <option value="Uttarakhand">Uttarakhand</option>
                                        <option value="West Bengal">West Bengal</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">District <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <select class=" form-control select2" id="p_district" name="district" required>

                                        <option value="">--Select District--</option>
                                        <option>Agra</option>
                                        <option>Aligarh</option>
                                        <option>Allahabad</option>
                                        <option>Ambedkar Nagar</option>
                                        <option>Amethi</option>
                                        <option>Amroha</option>
                                        <option>Auraiya</option>
                                        <option>Azamgarh</option>
                                        <option>Badaun</option>
                                        <option>Bagpat</option>
                                        <option>Bahraich</option>
                                        <option>Ballia</option>
                                        <option>Balrampur</option>
                                        <option>Banda</option>
                                        <option>Barabanki</option>
                                        <option>Bareilly</option>
                                        <option>Basti</option>
                                        <option>Bijnor</option>
                                        <option>Bulandshahr</option>
                                        <option>Chandauli</option>
                                        <option>Chitrakoot</option>
                                        <option>Deoria</option>
                                        <option>Etah</option>
                                        <option>Etawah</option>
                                        <option>Faizabad</option>
                                        <option>Farrukhabad</option>
                                        <option>Fatehpur</option>
                                        <option>Firozabad</option>
                                        <option>Gautam Buddha Nagar</option>
                                        <option>Ghaziabad</option>
                                        <option>Ghazipur</option>
                                        <option>Gonda</option>
                                        <option>Gorakhpur</option>
                                        <option>Hamirpur</option>
                                        <option>Hapur</option>
                                        <option>Hardoi</option>
                                        <option>Hathras</option>
                                        <option>Jalaun</option>
                                        <option>Jaunpur</option>
                                        <option>Jhansi</option>
                                        <option>Kannauj</option>
                                        <option>Kanpur Dehat</option>
                                        <option>Kanpur Nagar</option>
                                        <option>Kasganj</option>
                                        <option>Kaushambi</option>
                                        <option>Kushinagar</option>
                                        <option>Lakhimpur Kheri</option>
                                        <option>Lalitpur</option>
                                        <option>Lucknow</option>
                                        <option>Maharajganj</option>
                                        <option>Mahoba</option>
                                        <option>Mainpuri</option>
                                        <option>Mathura</option>
                                        <option>Mau</option>
                                        <option>Meerut</option>
                                        <option>Mirzapur</option>
                                        <option>Moradabad</option>
                                        <option>Muzaffarnagar</option>
                                        <option>Pilibhit</option>
                                        <option>Pratapgarh</option>
                                        <option>Rae Bareli</option>
                                        <option>Rampur</option>
                                        <option>Saharanpur</option>
                                        <option>Sant Kabir Nagar</option>
                                        <option>Sant Ravidas Nagar</option>
                                        <option>Sambhal</option>
                                        <option>Shahjahanpur</option>
                                        <option>Shamli</option>
                                        <option>Shravasti</option>
                                        <option>Siddharthnagar</option>
                                        <option>Sitapur</option>
                                        <option>Sonbhadra</option>
                                        <option>Sultanpur</option>
                                        <option>Unnao</option>
                                        <option>Varanasi (Kashi)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-1">
                                    <label class="form-label">PIN Code <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class=" form-control ">
                                </div>

                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Name of Nominee (For Insurance Purpose) <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class=" form-control ">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">Local Guardian Name <span
                                            class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class=" form-control ">
                                </div>



                            </div>
                            <div class="row align-items-center mb-1">
                                <div class="col-md-2 ">
                                    <label class="form-label">Local Guardian Mobile <span
                                            class="text-danger">*</span></label>
                                </div>

                                <div class="col-md-3">
                                    <input type="number" class=" form-control ">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">

                        <div class="p-2">
                            <h4>Educational Qualification(s) from 10th Std. Onwards</h4>
                        </div>


                        <div class="table-responsive">
                            <table class="table myrequesttablecbox loanapplicationlist">
                                <thead>
                                    <tr>

                                        <th>Name of Exam</th>
                                        <th>Degree Name</th>
                                        <th>Board / University</th>
                                        <th>Status</th>
                                        <th>Passing Year</th>
                                        <th>Mark Type</th>
                                        <th>Total Marks / CGPA</th>
                                        <th>Marks/CGPA Obtained</th>
                                        <th>Equivalent Percentage</th>
                                        <th>Subjects</th>
                                        <th>Roll Number</th>
                                        <th>Upload Files *
                                            Only JPG or PDF files from 200KB to 500KB</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                        <div class="form-group breadcrumb-right">
                            <button class="btn btn-dark btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-plus-square">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2">
                                    </rect>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg> Click Here To Add More Degrees</button>
                        </div>
                        <div class="p-2">

                        </div>

                    </div>
                </div>
                <div class="row align-items-center mb-1">
                    <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                        <div>
                            <h4 class="card-title text-theme">Upload Photo and Signature</h4>
                            <p class="text-danger">(Upload only JPG/PNG file upto 50 KB.)</p>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label">Recent Coloured Passport Size Photo</label>
                                <input type="file" class=" form-control ">
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="mb-1">
                                <label class="form-label">Signature/Thumb Impression</label>
                                <input type="file" class=" form-control ">
                            </div>

                        </div>

                    </div>

                    <div class="row align-items-center mb-1">
                        <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                            <div>
                                <h4 class="card-title text-theme">DECLARATION</h4>
                                <p>I do hereby, solemn and affirm that details provided by me in this application form
                                    under various heads are true & correct to the best of my knowledge and information.
                                    I affirm that no part of information has been concealed, fabricated or manipulated
                                    and that I have read University’s regulations for eligibility & admission procedure.
                                    In the event that information provided by me is found incorrect, inappropriate,
                                    false, manipulated or fabricated, the University shall have right to withdraw
                                    admission provided to me through this application and to take legal action against
                                    me as may be warranted by law.

                                    I also acknowledge hereby that I have read general instructions for application,
                                    procedure of admission, general code of conduct, hostel rules, examination rules,
                                    anti-ragging guidelines issued by UGC or Dr. Shakuntala Misra National
                                    Rehabilitation University and that I shall abide by them at all points of time. If
                                    my involvement in activities relating to discipline in University is found evident,
                                    University shall have all rights to take appropriate action against me. I also
                                    acknowledge that I am not suffering from any contagious disease that poses potential
                                    threat to health and safety of students of the University and shall always treat
                                    students with special needs (differently-abled), girls students and economically/
                                    socially deprived with compassion and cooperation.</p>

                            </div>
                            <p class="mt-4">
                                <input type="checkbox" class="filled-in" name="is_agree" id="agree1" value="1">
                                <label for="agree1" class="form-check-label"><strong>I Agree</strong><strong
                                        class="text-danger"> * </strong></label>

                            </p>
                        </div>
                        <div class="mt-5 p-3 text-center">
                            <button
                                class="btn btn-warning box-shadow-2 btn-xl me-1 mb-sm-0 mb-50 data-submit"
                                type="submit">Submit</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>

        </div>

    </div>


    </div>
    <script>
        window.onload = function() {
            var checkRadio = document.getElementById('check');
            var selectElement = document.getElementById('select');
            if (selectElement) {
                selectElement.style.display = "none";
                var radios = document.getElementsByName('collage');
                radios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        if (checkRadio.checked) {
                            selectElement.style.display = "block";
                        } else {
                            selectElement.style.display = "none";
                        }
                    });
                });
            }
        };



        
        <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
    <script>
      

        $(document).ajaxStart(function() {
                $("#loading-image").show();
            })
            .ajaxStop(function() {
                $("#loading-image").hide();
            });

        $('#success-alert-modal').on('hidden.bs.modal', function() {
            window.location.href = "{{ route('admin-dashboard') }}";
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
                url: "{{ route('application-form') }}",
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
                        $('#dashboard').attr('href', "{{ route('user-dashboard') }}");


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

</script>

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

    <script>
        $(document).ready(function() {
            $(".darktheme").click(function() {
                $("body").addClass("dark-theme");
            });
            $(".maintheme").click(function() {
                $("body").removeClass("dark-theme");
            });
        });
    </script>
@endsection
