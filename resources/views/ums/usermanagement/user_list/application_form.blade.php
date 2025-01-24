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


                    <div class="card">
                        <div class="card-body customernewsection-form">
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
                                                <input type="radio" class="filled-in application_for"
                                                    name="collage" value="1" id="applicatio_for1" checked>
                                                <label for="applicatio_for1" class="form-check-label me-3">
                                                    <strong>IN DSMNRU CAMPUS</strong><span class="text-danger">*</span>
                                                </label>

                                                <input type="radio" class="filled-in application_for"
                                                    name="collage" value="2" id="check"
                                                    >
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
                                            <select class="form-select select2" name="campus_id" id="campus_id">
                                                <option value="">--Select College Name--</option>
                                                <option value="1" style="display: none;">Dr. Shakuntala Misra National
                                                    Rehabilitation University</option>
                                                <option value="2">Kalyanam Karoti, Mathura</option>
                                                <option value="3">Nai Subah, Village-Khanav, Post-Bachhav, Varanasi
                                                </option>
                                                <option value="5">Hind Mahavidyalaya, Barabanki</option>
                                                <option value="8">K.S. Memorial College for Research &amp;
                                                    Rehabilitation, Raebareli</option>
                                                <option value="17">MAA BALIRAJI SEWA SANSTHAN (MIRZAPUR)</option>
                                                <option value="20">Handicapped Development Council, Sikandara, Agra
                                                </option>
                                                <option value="21">JAY NAND SPECIAL TEACHERS’ TRAINING INSTITUTE,
                                                    AYODHYA</option>
                                                <option value="24">Sarveshwari Shikshan Sansthan, Kausambi</option>
                                                <option value="25">SHRI CHANDRABHAN SINGH DEGREE COLLEGE</option>
                                                <option value="26">MAHARISHI DAYANAND REHABILITATION INSTITUTE</option>
                                                <option value="27">PRAKASH KIRAN EDUCATIONAL INSTITUTION</option>
                                                <option value="28">Dr. RPS INSTITUTE OF EDUCATION</option>
                                                <option value="29">SOCIETY FOR INSTITUTE OF PSYCHOLOGICAL RESEARCH &amp;
                                                    HEALTH</option>
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
                                    <div
                                        class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
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
                                            <select class="form-select select2" name="academic_session"
                                                id="academic_session">
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
                                            <select class="form-select select2" name="course_type" id="course_type">
                                                <option value="">--Select Program--</option>
                                                <option value="2">Non Professional</option>
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
                                            <select class="form-select select2">
                                                <option value="">--Select Course--</option>
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
                                    <div
                                        class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
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
                                        <input type="text" class="form-control">
                                    </div>


                                    <div class="col-md-1">
                                        <label class="form-label">Middle Name </label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="text" class="form-control">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Last Name </label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="row align-items-center mb-1">
                                    <div class="col-md-1">
                                        <label class="form-label">DOB <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="date" class="form-control">
                                    </div>


                                    <div class="col-md-1">
                                        <label class="form-label">E-mail ID <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="email" class="form-control">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Mobile No.<span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number" class="form-control">
                                    </div>
                                </div>

                                <div class="row align-items-center mb-1">
                                    <div class="col-md-1">
                                        <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="text" class="form-control">
                                    </div>


                                    <div class="col-md-1">
                                        <label class="form-label">Father's Mobile No. <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="email" class="form-control">
                                    </div>

                                </div>
                                <div class="row align-items-center mb-1">
                                    <div class="col-md-1">
                                        <label class="form-label">Mother's Name <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number" class="form-control">
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Mother's Mobile No. <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number" class="form-control">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body customernewsection-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div
                                        class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
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
                                        <select class="form-control" id="gender" name="gender">

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
                                        <select class="form-control" id="religion">
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
                                        <select class="form-control" id="marital_status" name="marital_status">
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
                                    </div>


                                    <div class="col-md-1">
                                        <label class="form-label">Nationality <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <select class="form-control" id="nationality">
                                            <option value="Indian" selected="">Indian</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label">Domicile<span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <select class="form-control" id="domicile">

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
                                        <select class="form-control" id="enrollment" name="dsmnru_student">
                                            <option value="">--Select Option--</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>


                                    <div class="col-md-1">
                                        <label class="form-label">Aadhar Number <span class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number" class="form-control">
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body customernewsection-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div
                                        class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
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
                                        <select class="form-control" id="category" name="category"
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
                                        <select class="form-control" id="dsmnru_employee" name="dsmnru_employee"
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
                                        <select class="form-control" id="dsmnru_employee_ward"
                                            name="dsmnru_employee_ward" onchange="set_name_and_relation($(this))">
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
                                        <select class="form-control" id="disability" name="disability"
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
                                        <select class="form-control" id="freedom_fighter_dependent"
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
                                        <select class="form-control" id="freedom_fighter_dependent"
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
                                        <select class="form-control" id="nss_cirtificate" name="nss">
                                            <option value="">--Select Option--</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Sports <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="nss_cirtificate" name="nss">
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
                                        <select class="form-control" id="nss_cirtificate" name="nss">
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
                                    <div
                                        class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
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
                                        <input type="address" class="form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Police Station</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Nearest Railway Station</label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="row align-items-center mb-1">
                                    <div class="col-md-1">
                                        <label class="form-label">Country</label>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control" id="p_country" name="country">
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
                                        <select class="form-control" id="p_state_union_territory"
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
                                        <select class="form-control" id="p_district" name="district">

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
                                        <input type="number" class="form-control">
                                    </div>

                                </div>

                                <div class="row align-items-center mb-1">
                                    <div class="col-md-3">
                                        <label class="form-label">Name of Nominee (For Insurance Purpose) <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control">
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label">Local Guardian Name <span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control">
                                    </div>



                                </div>
                                <div class="row align-items-center mb-1">
                                    <div class="col-md-2 ">
                                        <label class="form-label">Local Guardian Mobile <span
                                                class="text-danger">*</span></label>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number" class="form-control">
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
                                        xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-plus-square">
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
                                    <input type="file" class="form-control">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label class="form-label">Signature/Thumb Impression</label>
                                    <input type="file" class="form-control">
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
                                    <input type="checkbox" class="filled-in" name="is_agree" id="agree1"
                                        value="1">
                                    <label for="agree1" class="form-check-label"><strong>I Agree</strong><strong
                                            class="text-danger"> * </strong></label>
                                </p>
                            </div>

                        </div>
                    @endsection
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
                    </script>
