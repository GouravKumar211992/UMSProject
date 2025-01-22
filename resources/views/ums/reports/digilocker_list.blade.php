
@extends('admin.admin-meta')

@section('content')
{{-- <body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col=""> --}}
 
    <!-- BEGIN: Content-->

    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Digilocker Report</h2>
                            <div class="breadcrumb-wrapper">
                              <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                
                              </ol>
                          </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right"> 
					<button class="btn btn-primary btn-sm mb-50 mb-sm-0"  type="submit" name="submit_form"><i data-feather="check-circle" ></i>Get Report</button>
                    <button class="btn btn-primary  box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onclick="$('.remove_image').html('')">Remove Image<!--<button-->
        </button>
							<!-- <button class="btn btn-success btn-sm mb-50 mb-sm-0" data-bs-target="#approved" data-bs-toggle="modal"><i data-feather="check-circle" ></i> Assign Team</button> -->
                            <button class="btn btn-warning box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" onclick="window.location.reload();"><i data-feather="refresh-cw"></i>Reset</button>
                            {{-- <a href="" id="dd" class="btn-sm btn-primary  box-shadow-2 btn-sm me-1 mb-sm-0 mb-50" style="display:nones;">Excel Export</a> --}}
                    </div>
                </div>
            </div>
            <div class="row mb-2">
            <div class="col-sm-2">
            <span style="color: black;">Campus:</span>
            <select name="type" id="type" style="border-color: #c0c0c0;" class="form-control">
            <option value="">--Choose Campus--</option>
                            <option value="1">Dr. Shakuntala Misra National Rehabilitation University</option>
                                <option value="2">Kalyanam Karoti, Mathura</option>
                                <option value="3">Nai Subah,Village-Khanav, Post-Bachhav, Varanasi</option>
                                <option value="4">T.S. Misra Medical College and Hospital, Lucknow</option>
                                <option value="5">Hind Mahavidyalaya, Barabanki</option>
                                <option value="6">T.S. Misra College of Nursing, Lucknow</option>
                                <option value="8">K.S. Memorial College for Research &amp; Rehabilitation, Raebareli</option>
                                <option value="9">GRAMODYOG SEWA SANSTHAN</option>
                                <option value="13">PRAMILA KATIYAR SPECIAL EDUCATION INSTITUTE</option>
                                <option value="17">MAA BALIRAJI SEWA SANSTHAN(MIRZAPUR)</option>
                                <option value="18">MAA BALIRAJI SEWA SANSTHAN(ALLAHABAD)</option>
                                <option value="20">Handicapped Development Council, Sikandara, Agra</option>
                                <option value="21">JAY NAND SPECIAL TEACHERS’ TRAINING INSTITUTE, AYODHYA </option>
                                <option value="23">T. S. Misra College of Paramedical Sciences, Lucknow</option>
                                <option value="24">Sarveshwari Shikshan Sansthan, Kausambi</option>
                                <option value="25">SHRI CHANDRABHAN SINGH DEGREE COLLEGE</option>
                                <option value="26">MAHARISHI DAYANAND REHABILITATION INSTITUTE</option>
                                <option value="27">PRAKASH KIRAN EDUCATIONAL INSTITUTION</option>
                                <option value="28">Dr. RPS INSTITUTE OF EDUCATION</option>
                                <option value="29">SOCIETY FOR INSTITUTE OF PSYCHOLOGICAL RESEARCH &amp; HEALTH</option>
                            </select>
        </div>
        <div class="col-md-2">
                <span style="color: black;">Program:</span>
                <select class="form-control" style="border-color: #c0c0c0;" id="program" name="program">
                    <option value="">Select Program</option>
                                        <option value="2">Non Professional</option>
                                        <option value="3">Professional</option>
                                    </select>
        </div>
        <div class="col-sm-2">
            <span style="color: black;">COURSES:</span>
                <select name="course" id="course" style="border-color: #c0c0c0;" class="form-control">
                    <option value="">--Choose Course--</option>
                                        </select>
        </div>
</div>


            <div class="content-body">
                 
				<section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
								
								   
                                <div class="table-responsive">
                                        <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                        <thead>
                                    <tr class="text-center">
                                        <th>SERIAL</th>
                                        <th>ORG_CODE</th>
                                        <th>ORG_NAME</th>
                                        <th>ORG_NAME_L</th>
                                        <th>ORG_ADDRESS</th>
                                        <th>ORG_CITY</th>
                                        <th>ORG_STATE</th>
                                        <th>ORG_PIN</th>
                                        <th>ACADEMIC_COURSE_ID</th>
                                        <th>COURSE_NAME</th>
                                        <th>COURSE_NAME_L</th>
                                        <th>COURSE_SUBTITLE</th>
                                        <th>STREAM</th>
                                        <th>STREAM_L</th>
                                        <th>STREAM_SECOND</th>
                                        <th>STREAM_SECOND_L</th>
                                        <th>SESSION</th>
                                        <th>REGN_NO</th>
                                        <th>RROLL</th>
                                        <th>AADHAAR_NO</th>
                                        <th>LOCKER_ID</th>
                                        <th>CNAME</th>
                                        <th>GENDER</th>
                                        <th>DOB</th>
                                        <th>BLOOD_GROUP</th>
                                        <th>CASTE</th>
                                        <th>RELIGION</th>
                                        <th>NATIONALITY</th>
                                        <th>PH</th>
                                        <th>MOBILE</th>
                                        <th>EMAIL</th>
                                        <th>FNAME</th>
                                        <th>MNAME</th>
                                        <th>GNAME</th>
                                        <th>STUDENT_ADDRESS</th>
                                        <th>PHOTO</th>
                                        <th>MRKS_REC_STATUS</th>
                                        <th>RESULT</th>
                                        <th>YEAR</th>
                                        <th>MONTH</th>
                                        <th>DIVISION</th>
                                        <th>GRADE</th>
                                        <th>PERCENT</th>
                                        <th>DOR</th>
                                        <th>DOI</th>
                                        <th>DOV</th>
                                        <th>CERT_NO</th>
                                        <th>EXAM_TYPE</th>
                                        <th>CGPA</th>
                                        <th>OGPA</th>
                                        <th>THESIS</th>
                                        <th>GRADUATION_LEVEL</th>
                                        <th>REMARKS</th>
                                        <th class="text-center remove_image">Image</th> 
                                    </tr>
                                
                                            <tbody>
                                            <tr class="text-center">
                                        <td>1</td>
                                        <td></td>
                                        <td>T.S. Misra Medical College and Hospital, Lucknow</td>
                                                                                                                        <td></td>
                                        <td>Mohaan Road</td>
                                        <td>Lucknow</td>
                                        <td>Uttar Pradesh</td>
                                        <td>226017</td>
                                        <td>47</td>
                                        <td>BACHELOR OF MEDICINE AND BACHELOR OF SURGERY</td>
                                        <td></td>
                                        <td>MBBS</td>
                                        <td>MBBS</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>2023</td>
                                        <td>SA1601200400</td>
                                        <td>1601247125</td>
                                        <td></td>
                                        <td></td>
                                        <td>SHUBHAM SINGH</td>
                                        <td>MALE</td>
                                                                                <td>12-09-1996</td>
                                        <td></td>
                                        <td>N/A</td>
                                        <td></td>
                                        <td>Indian</td>
                                        <td></td>
                                        <td>9935611877</td>
                                        <td>subhsingh82.ss@gmail.com</td>
                                        <td>PREM CHANDRA</td>
                                        <td>KRISHNA DEVI</td>
                                        <td></td>
                                        <td>N/A</td>
                                        <td></td>
                                        <td></td>
                                        <td>PASSED</td>
                                        <td>2021-2022</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            0.00
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>0.00</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="remove_image">
                                                                                                                                <img src="https://thebasmatihouse.com/storage/16624/Screenshot_20220329-121048_Photos.jpg" class="photo_download_image">
                                            <a href="https://thebasmatihouse.com/storage/16624/Screenshot_20220329-121048_Photos.jpg" download="1601247125.jpg" data-roll_no="1601247125" class="btn btn-success photo_download">Download</a>
                                                                                    </td>
                                    </tr>
                                            </tbody>
                                        </table>
                                    </div>
								
                            </div>

                          
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                    <div class="modal modal-slide-in fade" id="modals-slide-in">
                        <div class="modal-dialog sidebar-sm">
                            <form class="add-new-record modal-content pt-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                                <div class="modal-header mb-1">
                                    <h5 class="modal-title" id="exampleModalLabel">New Record</h5>
                                </div>
                                <div class="modal-body flex-grow-1">
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                        <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="John Doe" aria-label="John Doe" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-post">Post</label>
                                        <input type="text" id="basic-icon-default-post" class="form-control dt-post" placeholder="Web Developer" aria-label="Web Developer" />
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-email">Email</label>
                                        <input type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" />
                                        <small class="form-text"> You can use letters, numbers & periods </small>
                                    </div>
                                    <div class="mb-1">
                                        <label class="form-label" for="basic-icon-default-date">Joining Date</label>
                                        <input type="text" class="form-control dt-date" id="basic-icon-default-date" placeholder="MM/DD/YYYY" aria-label="MM/DD/YYYY" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="basic-icon-default-salary">Salary</label>
                                        <input type="text" id="basic-icon-default-salary" class="form-control dt-salary" placeholder="$12000" aria-label="$12000" />
                                    </div>
                                    <button type="button" class="btn btn-primary data-submit me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
                 

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

	
     
    
    <div class="modal fade" id="reallocate" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header p-0 bg-transparent">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body px-sm-4 mx-50 pb-2">
					<h1 class="text-center mb-1" id="shareProjectTitle">Re-Allocate Incident</h1>
					<p class="text-center">Enter the details below.</p>

					<div class="row mt-2"> 
						
						<div class="col-md-12 mb-1">
							<label class="form-label">Re-Allocate To <span class="text-danger">*</span></label>
							<select class="form-select select2">
                                <option>Select</option>
                            </select>
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">Re-Allocate Dept. <span class="text-danger">*</span></label>
							<select class="form-select select2">
                                <option>Select</option>
                            </select>
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">PDC Date <span class="text-danger">*</span></label>
							<input type="date" class="form-control" placeholder="Enter Name" />
						</div>
                        
                        <div class="col-md-12 mb-1">
							<label class="form-label">Remarks <span class="text-danger">*</span></label>
							<textarea class="form-control"></textarea>
						</div>
                          
                         
				    </div>
                </div>
				
				<div class="modal-footer justify-content-center">  
						<button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
					<button type="reset" class="btn btn-primary">Re-Allocate</button>
				</div>
			</div>
		</div>
	</div>
    
      
    
	 
    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
		<div class="modal-dialog sidebar-sm">
			<form class="add-new-record modal-content pt-0"> 
				<div class="modal-header mb-1">
					<h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
				</div>
				<div class="modal-body flex-grow-1">
					<div class="mb-1">
						  <label class="form-label" for="fp-range">Select Date Range</label>
						  <input type="text" id="fp-range" class="form-control flatpickr-range" placeholder="YYYY-MM-DD to YYYY-MM-DD" />
					</div>
					
					<div class="mb-1">
						<label class="form-label">Select Incident No.</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Select Customer</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Assigned To</label>
						<select class="form-select select2">
							<option>Select</option>
						</select>
					</div> 
                    
                    <div class="mb-1">
						<label class="form-label">Status</label>
						<select class="form-select">
							<option>Select</option> 
							<option>Open</option>
							<option>Close</option>
							<option>Re-Allocatted</option>
						</select>
					</div> 
					 
				</div>
				<div class="modal-footer justify-content-start">
					<button type="button" class="btn btn-primary data-submit mr-1">Apply</button>
					<button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

   
{{-- </body> --}}
@endsection
