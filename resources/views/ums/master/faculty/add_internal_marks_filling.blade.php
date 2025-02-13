@extends('ums.master.faculty.faculty-meta')

@section('content')

<script>
    function validateForm() {
        let sem_date = document.forms["internal_form"]["date_of_semester"].value;
        let assign_date = document.forms["internal_form"]["date_of_semester"].value;
        let max_internal = document.forms["internal_form"]["internal_maximum"].value;
        let maximum_assign = document.forms["internal_form"]["assign_maximum"].value;

        if (sem_date == "") {
            $("#error").dialog().text("Date Of Semester must be filled out");
            return false;
        }
        if (assign_date == "") {
            $("#error").dialog().text("Date Of Assignment/Presentation must be filled out");
            return false;
        }
        if (max_internal == "") {
            $("#error").dialog().text("Maximum Marks must be filled out");
            return false;
        }
        if (maximum_assign == "") {
            $("#error").dialog().text("Maximum Marks Assignment/Presentation must be filled out");
            return false;
        }
    }
</script>

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static menu-collapsed" data-open="click" data-menu="vertical-menu-modern" data-col="">
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Internal Marks</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Internal Marks</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="history.go(-1)">
                            <i data-feather="arrow-left-circle"></i> Go Back
                        </button>
                        <button type="submit" form="form_submit" name="internal_form" class="btn btn-primary btn-sm mb-50 mb-sm-0">
                            <i data-feather="check-circle" style="font-size: 40px;"></i> Show Student list
                        </button>
                    </div>
                </div>
            </div>

            <form method="get" id="form_submit" name="internal_form" action="" onsubmit="return validateForm()">
                @csrf
                <div class="content-body bg-white p-4 shadow">
                    <div style="text-align: center;">
                        <h3>Dr. Rekha Shakuntala Misra National Rehabilitation University, Lucknow</h3>
                        <h3>Award Sheet of Internal Marks</h3>
                        <h3>Mid Semester & Assignment / Presentation</h3>
                    </div>
                    <div class="row gy-0 mt-3 p-2">
                        <div class="col-md-6">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Course Code<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select id="course" name="course" class="form-control">
                                        <option value="">--Select Course--</option>
                                        @foreach($mapped_Courses as $mapped_Course)
                                            <option value="{{$mapped_Course->id}}" @if(Request()->course==$mapped_Course->id) selected @endif>{{$mapped_Course->name}} ({{$mapped_Course->Course->campuse->campus_code}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Semester Name<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select id="semester" name="semester" class="form-control">
                                        <option value="">--Select Sem--</option>
                                        @if($mapped_Semesters)
                                            @foreach($mapped_Semesters as $mapped_Semester)
                                                <option value="{{$mapped_Semester->id}}" @if(Request()->semester==$mapped_Semester->id) selected @endif>{{$mapped_Semester->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Course Name<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" value="{{($mapped_faculty)?$mapped_faculty->Course->name:''}}" hidden>
                                    <p>{{($mapped_faculty)?$mapped_faculty->Course->name:''}}</p>
                                </div>
                            </div>
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Exam Type<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select id="type" name="type" class="form-control">
                                        @foreach($examTypes as $examType)
                                            <option value="{{$examType}}" @if(Request()->type==$examType) selected @endif>{{$examType}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Session<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select id="session" name="session" class="form-control">
                                        <option value="">--Select Session--</option>
                                        @foreach($sessions as $session)
                                            <option value="{{$session->academic_session}}" @if($session->academic_session==Request()->session) selected @endif>{{$session->academic_session}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Institution Code:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" placeholder="Enter here" value="{{($mapped_faculty)?$mapped_faculty->campus->campus_code:''}}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Batch<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select name="batch" id="batch" required class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach(batchArray() as $batch)
                                            @php $batch_prefix = substr($batch, 2, 2); @endphp
                                            <option value="{{$batch_prefix}}" @if(Request()->batch == $batch_prefix) selected @endif>{{$batch}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Institution Name:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" placeholder="Enter here" value="{{($mapped_faculty)?$mapped_faculty->campus->name:''}}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Paper Code<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <select id="sub_code" name="sub_code" class="form-control" required>
                                        <option value="">--Select Subject--</option>
                                        @foreach($mapped_Subjects as $mapped_Subject)
                                            <option value="{{$mapped_Subject->sub_code}}" @if(Request()->sub_code==$mapped_Subject->sub_code) selected @endif>{{$mapped_Subject->sub_code}} ({{$mapped_Subject->name}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Date Of Internal Exam:<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="date" class="form-control" name="date_of_semester" value="{{$date_of_semester}}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Maximum Marks(Mid Term/ UT):<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" placeholder="Enter here" class="form-control" id="internal_maximum" name="internal_maximum" value="{{$internal_maximum}}" required>
                                </div>
                            </div>

                            <div class="row align-items-center mb-1">
                                <div class="col-md-3">
                                    <label class="form-label">Maximum Marks(Assignment/Presentation/Practical):<span class="text-danger">*</span></label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" placeholder="Enter here" id="assign_maximum" name="assign_maximum" value="{{$assign_maximum}}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            @if(count($students) > 0)
                <form method="post" id="main_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="campus_id" value="{{$mapped_faculty->Course->Campuse->id}}">
                    <input type="hidden" name="campus_name" value="{{$mapped_faculty->Course->Campuse->name}}">
                    <input type="hidden" name="program_id" value="{{$mapped_faculty->program_id}}">
                    <input type="hidden" name="program_name" value="{{$mapped_faculty->Category->name}}">
                    <input type="hidden" name="course_id" value="{{$mapped_faculty->course_id}}">
                    <input type="hidden" name="course_name" value="{{$mapped_faculty->Course->name}}">
                    <input type="hidden" name="semester_id" value="{{$mapped_faculty->semester_id}}">
                    <input type="hidden" name="semester_name" value="{{$mapped_faculty->Semester->name}}">
                    <input type="hidden" name="session" value="{{Request()->session}}">
                    <input type="hidden" name="faculty_id" value="{{$mapped_faculty->faculty_id}}">
                    <input type="hidden" name="subject_code" value="{{$sub_code}}">
                    <input type="hidden" name="subject_name" value="{{$sub_name->name}}">
                    <input type="hidden" name="semester_date" value="{{$date_of_semester}}">
                    <input type="hidden" name="assign_date" value="{{$date_of_semester}}">
                    <input type="hidden" name="maximum_internal" value="{{$internal_maximum}}">
                    <input type="hidden" name="maximum_assign" value="{{$assign_maximum}}">

                    <div class="content-body">
                        <section id="basic-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="table-responsive">
                                            <table class="datatables-basic table myrequesttablecbox loanapplicationlist">
                                                <thead>
                                                    <tr>
                                                        <td rowspan="2">Sr. No.</td>
                                                        <td rowspan="2">Name Of Student</td>
                                                        <td rowspan="2">Enrollment No.</td>
                                                        <td rowspan="2">Roll No.</td>
                                                        <td rowspan="2">Absent Status</td>
                                                        <td rowspan="2">Mid-Semester<br>/Theory Marks</td>
                                                        <td rowspan="2">Assignment<br>/Presentation<br>/Practical Marks</td>
                                                        <td rowspan="2" class="comment-heading" style="width: 150px;">Comments</td>
                                                        <td colspan="2">Total Marks</td>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <td>In Figure</td>
                                                        <td>In Words</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="head1">
                                                    @foreach($students as $key => $subject)
                                                        <tr>
                                                            <td>{{$key + 1}}</td>
                                                            <td><input type="text" class="form-control" readonly="true" name="student_name[]" value="{{$subject->student->first_name}}"></td>
                                                            <td><input type="text" class="form-control" readonly="true" name="enrollment_number[]" value="{{$subject->student->enrollment_no}}" hidden><span id="lblPap1ID">{{$subject->student->enrollment_no}}</span></td>
                                                            <td><input type="text" class="form-control" readonly="true" name="roll_number[]" value="{{$subject->student->roll_number}}" hidden><span id="lblPap1ID">{{$subject->student->roll_number}}</span></td>
                                                            <td>
                                                                <input type="checkbox" class="btn btn-info absent_status">
                                                                <input type="hidden" value='0' class="absent_status_text" name="absent_status[]">
                                                            </td>
                                                            <td><input type="text" class="form-control numbersOnly fillable obtain-internal-marks" name="mid_semester_marks[]" value="{{old('mid_semester_marks[]')}}" required></td>
                                                            <td><input type="text" class="form-control numbersOnly fillable obtain-assign-marks" name="assingnment_mark[]" value="{{old('assingnment_mark[]')}}" required></td>
                                                            <td><input type="text" class="form-control numbersOnly fillable total_marks" name="total_marks[]" value="{{old('total_marks[]')}}" readonly style="width: 100px !important;"></td>
                                                            <td><input type="text" class="form-control fillable total_marks_words" name="total_marks_words[]" value="" readonly style="width: 250px !important;"></td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
    <script>
        $(document).ready(function(){
            
            
            
            $('.obtain-internal-marks').change(function(){
                
                var internal_maximum=parseInt($('#internal_maximum').val());
                var obtain_marks=$(this).val();
                if((obtain_marks < 0 || obtain_marks > internal_maximum))
                {	
                    $("#error").dialog().text('Obtain Marks Must Be Less Than Internal Maximum Marks ');
                    
                    $(this).val('');
                    $(this).css({'border':'1px solid red'});
                }else{
                    $(this).css({'border':'0px solid red'});
                }
            });
            
            $('.obtain-assign-marks').change(function(){
                var assign_maximum=parseInt($('#assign_maximum').val());
                var obtain_marks=$(this).val();
                if((obtain_marks < 0 || obtain_marks > assign_maximum))
                {	
                    $("#error").dialog().text('Obtain Marks Must Be Less Than Assignment/Presentation Maximum Marks ');
                    
                    $(this).val('');
                    $(this).css({'border':'1px solid red'});
                }else{
                    $(this).css({'border':'0px solid red'});
                }
                var assign_marks = parseInt($(this).val());
                var internal_marks = parseInt($(this).closest('tr').find('.obtain-internal-marks').val());
                var total_marks_object = $(this).closest('tr').find('.total_marks');
                var total_marks_words = $(this).closest('tr').find('.total_marks_words');
                var total_marks_words_text = $(this).closest('tr').find('.total_marks_words_text');
                total_marks_object.val(assign_marks+internal_marks);
                $.ajax({
                    type:'GET',
                    url:"{{url('faculty/get_number_in_works')}}/"+total_marks_object.val(),
                    data:'_token = <?php echo csrf_token() ?>',
                    success:function(data) {
                        total_marks_words.val($.trim(data));
                        total_marks_words_text.text($.trim(data));
                    }
                });
                
            });
            
            
        $('.save_pge').click(function(){
            if(confirm('Are you want to final submit?') == false){
                return false;
            }
        //	var check_value = false;
        var check_value = true;
            $('.total_marks').each(function(index,value){
                if($(this).val() == ''){
                    check_value = false;
        //			check_value = true;
                }
            });
            if(check_value==true){
                $('.save_pge_submit').trigger('click');
            }else{
                $("#error").dialog().text('Please Fill All Records');
                
            }
        });
        
        $('.numbersOnly').keyup(function () { 
            this.value = this.value.replace(/[^0-9\.]/g,'');
        });
        
        $('.absent_status').click(function(){
            var absent_status = $(this).prop('checked');
            var current_tr = $(this).closest('tr');
            if(absent_status==true){
                current_tr.find('.absent_status_text').val('1');
                current_tr.find('.fillable').val('ABSENT');
            }else{
                current_tr.find('.absent_status_text').val('0');
                current_tr.find('.fillable').val('');
            }
        });
        
        document.getElementById('course').onchange = function() {
            var course = document.getElementById('course').value;
            // window.location.href=`/semester/${course}`;
            var semesterDropdown = document.getElementById('semester');
            semesterDropdown.innerHTML = '<option value="">--Select Semester--</option>';
            if (course) {
                fetch(`/semester/${course}`, {
                        method: 'GET',
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            semesterDropdown.innerHTML += data.html;
                        } else {
                            alert('No semesters found for this course');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while fetching semesters');
                    });
            }
      
        };
        
        
            
        });
        $(document).ready(function() {
            // Attach the handlecomment function to the input fields
            $('.obtain-internal-marks, .obtain-assign-marks, #internal_maximum, #assign_maximum').on('input', function() {
                handlecomment($(this));
            });
            $('#internal_maximum, #assign_maximum').on('input', function() {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
        });
        
        function handlecomment($this) {
            // Parse the maximum values and ensure they are numbers
            var internal_maximum = parseInt($('#internal_maximum').val().trim(), 10);
            var assign_maximum = parseInt($('#assign_maximum').val().trim(), 10);
        
            // Default to 0 if the parsed value is NaN
            if (isNaN(internal_maximum)) {
                internal_maximum = 0;
                $('#internal_maximum').val('0');
            }
            if (isNaN(assign_maximum)) {
                assign_maximum = 0;
                $('#assign_maximum').val('0');
            }
        
            // Parse the marks values and ensure they are numbers
            var internal_marks = parseFloat($this.closest('tr').find('.obtain-internal-marks').val().trim());
            var obtain_marks = parseFloat($this.closest('tr').find('.obtain-assign-marks').val().trim());
        
            // Default to 0 if the parsed value is NaN
            if (isNaN(internal_marks)) {
                internal_marks = 0;
                $this.closest('tr').find('.obtain-internal-marks').val('0');
            }
            if (isNaN(obtain_marks)) {
                obtain_marks = 0;
                $this.closest('tr').find('.obtain-assign-marks').val('0');
            }
        
            // Calculate total maximum and obtained marks
            var total_maximum = internal_maximum + assign_maximum;
            var total_obtain_marks = internal_marks + obtain_marks;
        
            // Calculate the percentage, ensuring total_maximum is not zero
            var percentage = total_maximum ? (total_obtain_marks / total_maximum) * 100 : 0;
        
            // Update the total marks field
            $this.closest('tr').find('.total_marks').val(total_obtain_marks);
        
            // Convert total_obtain_marks to words regardless of its value
            var total_marks_words = convertNumberToWords(total_obtain_marks);
            $this.closest('tr').find('.total_marks_words').val(total_marks_words);
            $this.closest('tr').find('.total_marks_words_text').text(total_marks_words);
        
            // Check if percentage is valid and show/hide result values accordingly
            if (percentage > 80 || percentage < 40) {
                $this.closest('tr').find('.result_values').show();
                $this.closest('tr').find('.result_values').prop('required', true);
                return true;
            } else {
                $this.closest('tr').find('.result_values').hide();
                $this.closest('tr').find('.result_values').prop('required', false);
                return false;
            }
        }
        
        // Function to convert number to words
        function convertNumberToWords(num) {
            var ones = [
                '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
            ];
            var tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
            var thousands = ['','Thousand'];
        
            if (num === 0) return 'Zero';
        
            if (num < 20) return ones[num];
        
            if (num < 100) {
                return tens[Math.floor(num / 10)] + (num % 10 !== 0 ? ' ' + ones[num % 10] : '');
            }
        
            if (num < 1000) {
                return ones[Math.floor(num / 100)] + ' Hundred' + (num % 100 !== 0 ? ' and ' + convertNumberToWords(num % 100) : '');
            }
        
            return ones[Math.floor(num / 1000)] + ' Thousand' + (num % 1000 !== 0 ? ' ' + convertNumberToWords(num % 1000) : '');
        }
        
        
        
        
        
        
        </script>
</body>

@endsection