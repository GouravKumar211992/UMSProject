@extends('ums.master.faculty.layouts.app')

{{-- Web site Title --}}
@section('title') Add Internal Marks :: @parent @stop
@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
    <style>
        .head1 td {
            border: #000000 1px solid;
            border-right: none;
            border-top: none;
        }

        .head2 td {
            border: #000000 solid;
            border-left: none;
            border-top: none;
        }

        .head1 p {
            display: inline;
            /*font-size:15px;*/
            margin-left: 5px;

        }

        .head1 input {
            width: auto;
            height: 30px;
            display: inline;
            margin-left: 5px;
        }

        .head2 input {
            width: auto;
            height: 30px;
            display: inline;
            margin-left: 5px;
        }

        .head1 select {
            width: auto;
            height: 45px;
            display: inline;
            margin-left: 5px;
        }

        .head2 select {
            width: auto;
            height: 45px;
            display: inline;
            margin-left: 5px;
        }

        table td {
            padding: 5px 10px;
        }

        .form-control {
            width: 100% !important;
            border: 1px solid #000;
            margin-left: 0px !important;
        }
    </style>

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

    <section class="content mb-3">
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        @if ($msg)
            <div class="alert alert-success">
                {{ $msg }}
            </div>
        @endif
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-4">
                    <a href="javascript:history.back()" class="btn btn-secondary btn-back">Go Back</a>
                </div>
                <div class="col-md-12">
                    <div class="border-bottom mt-3 mb-2 border-innerdashed"> </div>
                </div>
            </div>
            <form method="get" name="internal_form" onsubmit="return validateForm()">
                <table style="border:solid" width="100%">
                    <thead class="head1">
                        <tr align="center" bordercolordark="#000000">
                            <td colspan="8">Dr.SHAKUNTALA MISRA NATIONAL REHABILIATION UNIVERSITY, LUCKNOW</td>
                        </tr>
                        <tr align="center" bordercolordark="#000000">
                            <td colspan="8">AWARD SHEET OF INTERNAL MARKS</td>
                        </tr>
                        <tr align="center">
                            <td colspan="8">MID SEMESTER & ASSIGNMENT /PRESENTATION</td>
                        </tr>
                        <tr>
                            <td colspan="2">Course Code:</td>
                            <td colspan="2">
                                <select id="course" name="course" class="form-control" required>
                                    <option value="">--Select Course--</option>
                                    @foreach ($mapped_Courses as $index => $mapped_Course)
                                        <option
                                            value="{{ $mapped_Course->id }}"@if (Request()->course == $mapped_Course->id) selected @endif>
                                            {{ $mapped_Course->name }} ({{ $mapped_Course->Course->campuse->campus_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="head2" colspan="2">Course Name:</td>
                            <td colspan="2"> <input type="text"
                                    value="{{ $mapped_faculty ? $mapped_faculty->Course->name : '' }}" disabled>
                                <p>{{ $mapped_faculty ? $mapped_faculty->Course->name : '' }}</p>
                            </td>
                        </tr>


                        <tr>
                            <td colspan="2">Semester Name:</td>
                            <td colspan="2">
                                <select id="semester" name="semester" class="form-control" required>
                                    <option value="">--Select Semester--</option>
                                    @if ($mapped_Semesters)
                                        @foreach ($mapped_Semesters as $index => $mapped_Semester)
                                            <option
                                                value="{{ $mapped_Semester->id }}"@if (Request()->semester == $mapped_Semester->id) selected @endif>
                                                {{ $mapped_Semester->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <script></script>
                            <td class="head2" colspan="2">Exam Type:</td>
                            <td colspan="2">
                                <select id="type" name="type" class="form-control" required>
                                    @foreach ($examTypes as $index => $examType)
                                        <option value="{{ $examType }}"
                                            @if (Request()->type == $examType) selected @endif>{{ $examType }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="head2" colspan="2">Session:</td>
                            <td colspan="2">
                                <select id="session" name="session" class="form-control">
                                    <option value="">--Select Session--</option>
                                    @foreach ($sessions as $session)
                                        <option value="{{ $session->academic_session }}"
                                            @if ($session->academic_session == Request()->session) selected @endif>
                                            {{ $session->academic_session }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td colspan="2">Batch :</td>
                            <td colspan="2">
                                <select name="batch" id="batch" style="border-color: #c0c0c0;"
                                    class="form-control js-example-basic-single" required>
                                    <option value="">--Select--</option>
                                    @foreach (batchArray() as $batch)
                                        @php $batch_prefix = substr($batch,2,2); @endphp
                                        <option value="{{ $batch_prefix }}"
                                            @if (Request()->batch == $batch_prefix) selected @endif>{{ $batch }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">Institution Code: </td>
                            <td colspan="2">
                                <p>{{ $mapped_faculty ? $mapped_faculty->campus->campus_code : '' }}</p>
                            </td>
                            <td class="head2" colspan="2">Institution Name:</td>
                            <td colspan="2"> <input type="text" style="float: right" hidden>
                                <p>{{ $mapped_faculty ? $mapped_faculty->campus->name : '' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">Paper Code:</td>
                            <td colspan="2">
                                @if (!Request()->sub_code)
                                    @php $sub_code_name =''; @endphp
                                @endif
                                <select id="sub_code" name="sub_code" class="form-control" style="width: 250px;" required>
                                    <option value="">--Select Subject--</option>
                                    @foreach ($mapped_Subjects as $index => $mapped_Subject)
                                        @if (Request()->sub_code == $mapped_Subject->sub_code)
                                            @php $sub_code_name = $mapped_Subject->name; @endphp
                                        @endif
                                        @if ($mapped_Subject->internal_marking_type == 1)
                                            @if ($index == 0)
                                                <option
                                                    value="{{ $mapped_Subject->sub_code }}"@if (Request()->sub_code == $mapped_Subject->sub_code) selected @endif>
                                                    {{ $mapped_Subject->combined_subject_name }}
                                                    ({{ $mapped_Subject->name }})</option>
                                            @endif
                                        @else
                                            <option
                                                value="{{ $mapped_Subject->sub_code }}"@if (Request()->sub_code == $mapped_Subject->sub_code) selected @endif>
                                                {{ $mapped_Subject->sub_code }} ({{ $mapped_Subject->name }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('sub_code') }}</span>
                            </td>
                            <td colspan="2">Paper Name:</td>
                            <td colspan="2"> <input id="sub_name" name="sub_name" class="form-control"
                                    value="{{ $sub_code_name }}" disabled required /></td>
                        </tr>

                        <tr>
                            <td colspan="2">Date Of Internal Exam:</td>
                            <td colspan="2"> <input type="date" name="date_of_semester" class="form-control"
                                    value="{{ $date_of_semester }}" required></td>
                            <!-- <td class="head2" colspan="2">Date Of Assignment/<br/>Presentation:</td><td colspan="2"> <input type="date" name="date_of_assign" class="form-control" value="{{ $date_of_assign }}" required></td> -->
                            <td class="head2" colspan="2"></td>
                            <td colspan="2"> </td>
                        </tr>
                        <tr>
                            <td colspan="2">Maximum Marks(Mid Term/ UT): </td>
                            <td colspan="2"> <input type="number" min="0" id="internal_maximum"
                                    name="internal_maximum" class="form-control" value="{{ $internal_maximum }}"
                                    required></td>
                            <td class="head2" colspan="2">Maximum Marks(Assignment/<br />Presentation/Practical):</td>
                            <td colspan="2"> <input type="number" class="form-control" min="0"
                                    id="assign_maximum"name="assign_maximum" value="{{ $assign_maximum }}" required></td>
                        </tr>
                        <tr>
                            <td colspan="8">&nbsp; </td>
                        </tr>
                    </thead>


                </table>
                <input type="submit" value="Show Student List" class="btn btn-primary">
            </form>
            @if (count($students) > 0)
                <form method="post" id="main_form" enctype="multipart/form-data">
                    @csrf

                    <br />

                    {{-- @include('partials.notifications') --}}

                    <input type="text" hidden name="campus_id" value="{{ $mapped_faculty->Course->Campuse->id }}">
                    <input type="text" hidden name="campus_name"
                        value="{{ $mapped_faculty->Course->Campuse->name }}">
                    <input type="text" hidden name="program_id" value="{{ $mapped_faculty->program_id }}">
                    <input type="text" hidden name="program_name" value="{{ $mapped_faculty->Category->name }}">
                    <input type="text" hidden name="course_id" value="{{ $mapped_faculty->course_id }}">
                    <input type="text" hidden name="course_name" value="{{ $mapped_faculty->Course->name }}">
                    <input type="text" hidden name="semester_id" value="{{ $mapped_faculty->semester_id }}">
                    <input type="text" hidden name="semester_name" value="{{ $mapped_faculty->Semester->name }}">
                    <input type="text" hidden name="session" value="{{ Request()->session }}">
                    <input type="text" hidden name="faculty_id" value="{{ $mapped_faculty->faculty_id }}">
                    <input type="text" hidden name="subject_code" value="{{ $sub_code }}">
                    <input type="text" hidden name="subject_name" value="{{ $sub_name->name }}">
                    <input type="text" hidden name="semester_date" value="{{ $date_of_semester }}">
                    <input type="text" hidden name="assign_date" value="{{ $date_of_semester }}">
                    <input type="text" hidden name="maximum_internal" value="{{ $internal_maximum }}">
                    <input type="text" hidden name="maximum_assign" value="{{ $assign_maximum }}">

                    <table style="border:solid" class="table1" width="100%">
                        <thead class="head1">
                            <tr>
                                <td rowspan="2"> Sr. No.</td>
                                <td rowspan="2"> Name Of Student</td>
                                <td rowspan="2"> Enrollment No.</td>
                                <td rowspan="2"> Roll No.</td>
                                <td rowspan="2"> Absent Status</td>
                                <td rowspan="2"> Mid-Semester<br>/Theory Marks</td>
                                <td rowspan="2"> Assignment<br>/Presentation<br>/Practical Marks</td>
                                <td rowspan="2" class="comment-heading" style="width: 150px;">Comments</td>
                                <td colspan="2"> Total Marks </td>
                            </tr>
                            <tr class="text-center">
                                <td>In Figure</td>
                                <td>In Words</td>
                            </tr>
                        </thead>

                        <tbody class="head1">
                            @foreach ($students as $key => $subject)
                                @if ($subject->form_type == 'compartment')
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><input type="text" class="form-control" readonly="true"
                                                name="student_name[]" value="{{ $subject->student->first_name }}"></td>
                                        <td><input type="text" class="form-control" readonly="true"
                                                name="enrollment_number[]" value="{{ $subject->student->enrollment_no }}"
                                                hidden><span id="lblPap1ID">{{ $subject->student->enrollment_no }}</span>
                                        </td>
                                        <td><input type="text" class="form-control" readonly="true"
                                                name="roll_number[]" value="{{ $subject->student->roll_number }}"
                                                hidden><span id="lblPap1ID">{{ $subject->student->roll_number }}</span>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="btn btn-info absent_status">
                                            <input type="hidden" value='0' class="absent_status_text"
                                                name="absent_status[]">
                                        </td>
                                        <td><input type="text"
                                                class="form-control numbersOnly fillable obtain-internal-marks"
                                                name="mid_semester_marks[]" value="{{ old('$mid_semester_marks[]') }}"
                                                required></td>
                                        <td><input type="text"
                                                class="form-control numbersOnly fillable obtain-assign-marks"
                                                name="assingnment_mark[]" value="{{ old('$assingnment_mark[]') }}"
                                                required>
                                        </td>
                                        <td><input type="text" class="form-control numbersOnly fillable total_marks"
                                                name="total_marks[]" value="{{ old('$total_marks[]') }}" readonly
                                                style="width: 100px !important;">
                                        </td>
                                        <td><input type="text" class="form-control fillable total_marks_words"
                                                name="total_marks_words[]" value="" readonly
                                                style="width: 250px !important;"></td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><input type="text" class="form-control" readonly="true"
                                                name="student_name[]"
                                                value="{{ $subject->student->first_Name }} {{ $subject->student->middle_Name }} {{ $subject->student->last_Name }}"
                                                hidden><span id="lblPap1ID">{{ $subject->student->first_Name }}
                                                {{ $subject->student->middle_Name }}
                                                {{ $subject->student->last_Name }}</span></td>
                                        <td><input type="text" class="form-control" readonly="true"
                                                name="enrollment_number[]" value="{{ $subject->student->enrollment_no }}"
                                                hidden><span id="lblPap1ID">{{ $subject->student->enrollment_no }}</span>
                                        </td>
                                        <td><input type="text" class="form-control" readonly="true"
                                                name="roll_number[]" value="{{ $subject->student->roll_number }}"
                                                hidden><span id="lblPap1ID">{{ $subject->student->roll_number }}</span>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="btn btn-info absent_status">
                                            <input type="hidden" value="0" class="absent_status_text"
                                                name="absent_status[]">
                                        </td>
                                        <td>
                                            <input type="text"
                                                class="form-control numbersOnly fillable obtain-internal-marks"
                                                name="mid_semester_marks[]" value="{{ old('mid_semester_marks[]') }}"
                                                required>
                                        </td>
                                        <td>
                                            <input type="text"
                                                class="form-control numbersOnly fillable obtain-assign-marks"
                                                name="assingnment_mark[]" value="{{ old('assingnment_mark[]') }}"
                                                oninput="handlecomment($(this))">
                                        </td>
                                        <td class="comment-row">
                                            <textarea class="col-sm-12 result_values" style="width:130px;display: none" rows="3" name="comment[]"></textarea>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control numbersOnly fillable total_marks"
                                                name="total_marks[]" value="{{ old('total_marks[]') }}" readonly>
                                        </td>
                                        <td style="width: 150px">
                                            <span class="total_marks_words_text"></span>
                                            <input type="text" class="form-control fillable total_marks_words"
                                                name="total_marks_words[]" value="" readonly hidden>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-5"></div>
                        <div class="col-sm-2">
                            <input type="button" class="btn btn-success form-control save_pge" value="Submit">
                            <input type="submit" class="btn btn-success form-control save_pge_submit" hidden>
                        </div>
                        <div class="col-sm-5"></div>
                    </div>
                </form>
            @elseif($students)
                <table style="border:solid" class="table1" width="100%">
                    <thead class="head2">
                        <tr>
                            <td rowspan="14">No Students Found</td>
                        </tr>
                    </thead>
                </table>
            @endif


        </div>
        @if ($students)
            {{-- {{$students->appends(Request()->all())->links('partials.pagination')}} --}}
        @endif
    </section>
    <div id="error" title="Error">

    </div>

@endsection

@section('styles')
    <style type="text/css"></style>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {



            $('.obtain-internal-marks').change(function() {

                var internal_maximum = parseInt($('#internal_maximum').val());
                var obtain_marks = $(this).val();
                if ((obtain_marks < 0 || obtain_marks > internal_maximum)) {
                    $("#error").dialog().text('Obtain Marks Must Be Less Than Internal Maximum Marks ');

                    $(this).val('');
                    $(this).css({
                        'border': '1px solid red'
                    });
                } else {
                    $(this).css({
                        'border': '0px solid red'
                    });
                }
            });

            $('.obtain-assign-marks').change(function() {
                var assign_maximum = parseInt($('#assign_maximum').val());
                var obtain_marks = $(this).val();
                if ((obtain_marks < 0 || obtain_marks > assign_maximum)) {
                    $("#error").dialog().text(
                        'Obtain Marks Must Be Less Than Assignment/Presentation Maximum Marks ');

                    $(this).val('');
                    $(this).css({
                        'border': '1px solid red'
                    });
                } else {
                    $(this).css({
                        'border': '0px solid red'
                    });
                }
                var assign_marks = parseInt($(this).val());
                var internal_marks = parseInt($(this).closest('tr').find('.obtain-internal-marks').val());
                var total_marks_object = $(this).closest('tr').find('.total_marks');
                var total_marks_words = $(this).closest('tr').find('.total_marks_words');
                var total_marks_words_text = $(this).closest('tr').find('.total_marks_words_text');
                total_marks_object.val(assign_marks + internal_marks);
                $.ajax({
                    type: 'GET',
                    url: "{{ url('faculty/get_number_in_works') }}/" + total_marks_object.val(),
                    data: '_token = <?php echo csrf_token(); ?>',
                    success: function(data) {
                        total_marks_words.val($.trim(data));
                        total_marks_words_text.text($.trim(data));
                    }
                });

            });


            $('.save_pge').click(function() {
                if (confirm('Are you want to final submit?') == false) {
                    return false;
                }
                //	var check_value = false;
                var check_value = true;
                $('.total_marks').each(function(index, value) {
                    if ($(this).val() == '') {
                        check_value = false;
                        //			check_value = true;
                    }
                });
                if (check_value == true) {
                    $('.save_pge_submit').trigger('click');
                } else {
                    $("#error").dialog().text('Please Fill All Records');

                }
            });

            $('.numbersOnly').keyup(function() {
                this.value = this.value.replace(/[^0-9\.]/g, '');
            });

            $('.absent_status').click(function() {
                var absent_status = $(this).prop('checked');
                var current_tr = $(this).closest('tr');
                if (absent_status == true) {
                    current_tr.find('.absent_status_text').val('1');
                    current_tr.find('.fillable').val('ABSENT');
                } else {
                    current_tr.find('.absent_status_text').val('0');
                    current_tr.find('.fillable').val('');
                }
            });

            // $('#course').change(function(){
            // 	var course=$('#course').val();
            // 	$("#semester").find('option').remove().end();
            // 	var formData = {course:course,"_token": "{{ csrf_token() }}"}; 
            // 	$.ajax({
            // 		url : "{{ url('faculty/getsemester') }}",
            // 		type: "POST",
            // 		data : formData,
            // 		success: function(data, textStatus, jqXHR){
            // 			$('#semester').append(data);

            // 		},
            // 	});

            // if(course == 37){
            // 	$('#strean_id').show();
            // }else{
            // 	$('#strean_id').hide();
            // }

        });




        // });
        $(document).ready(function() {
            // Attach the handlecomment function to the input fields
            $('.obtain-internal-marks, .obtain-assign-marks, #internal_maximum, #assign_maximum').on('input',
                function() {
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
            var thousands = ['', 'Thousand'];

            if (num === 0) return 'Zero';

            if (num < 20) return ones[num];

            if (num < 100) {
                return tens[Math.floor(num / 10)] + (num % 10 !== 0 ? ' ' + ones[num % 10] : '');
            }

            if (num < 1000) {
                return ones[Math.floor(num / 100)] + ' Hundred' + (num % 100 !== 0 ? ' and ' + convertNumberToWords(num %
                    100) : '');
            }

            return ones[Math.floor(num / 1000)] + ' Thousand' + (num % 1000 !== 0 ? ' ' + convertNumberToWords(num % 1000) :
                '');
        }
    </script>
    <script>
        document.getElementById('course').onchange = function() {
            var course = document.getElementById('course').value;
            var semesterDropdown = document.getElementById('semester');
            semesterDropdown.innerHTML = '<option value="">--Select Semester--</option>';
            if (course) {
                fetch(`/getsemester/${course}`, {
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


        // $('#semester').change(function() {
        //     var course = $('#course').val();
        //     var semester = $('#semester').val();
        //     $("#sub_code").find('option').remove().end();
        //     var formData = {
        //         permissions: 1,
        //         semester: semester,
        //         course: course,
        //         "_token": "{{ cs rf_token() }}"
        //     };
        //     $.ajax({
        //         url: "{{ url('getsubject') }}",
        //         type: "POST",
        //         data: formData,
        //         success: function(data, textStatus, jqXHR) {
        //             $('#sub_code').append(data);

        //         },
        //     });
        // });
       
      
    </script>
	<script>
// 		document.getElementById('semester').onchange = function() {
//     var course = document.getElementById('course').value;
//     var semester = document.getElementById('semester').value;
//     var subCodeDropdown = document.getElementById('sub_code');
//     subCodeDropdown.innerHTML = '<option value="">--Select Subject--</option>';  // Reset options

//     if (semester && course) {
//         fetch(`getsubject/${course}/${semester}`, {
//             method: 'GET',
//         })
//         .then(response => response.json())
//         .then(data => {
//             if (data.html) {
//                 subCodeDropdown.innerHTML += data.html;
//             } else {
//                 alert('No subjects found for this course and semester');
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert('An error occurred while fetching subjects');
//         });
//     }
// };
document.getElementById('semester').onchange = function() {
    var course = document.getElementById('course').value;  // Get the selected course value
    var semester = document.getElementById('semester').value;  // Get the selected semester value
    var subCodeDropdown = document.getElementById('sub_code');  // The dropdown to load subjects

    subCodeDropdown.innerHTML = '<option value="">--Select Subject--</option>';  // Reset the dropdown options

    if (course && semester) {  // Check if both course and semester are selected
        // Fetch the subjects from the server based on the selected course and semester
        fetch(`/getsubject`, {
            method: 'GET',  // Sending a GET request
        })
        .then(response => response.json())  // Parse the response as JSON
        .then(data => {
            if (data.data) {  // If HTML options are returned
                subCodeDropdown.innerHTML += data.data;  // Append the options to the dropdown
            } else {
                alert('No subjects found for this course and semester');  // Show alert if no subjects found
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while fetching subjects: ' + error.message);  // Show the detailed error message
        });
    }
};


	</script>
@endsection
