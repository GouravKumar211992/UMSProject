@extends("ums.admin.admin-meta")
@section("content")

        <div class="row print_hide mb-3">
            <div class="col-md-12 text-center">
                <button onClick="window.print()" class="btn btn-primary" style="font-size: 17px; font-weight: 700; width: 12%;">Print</button>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <!-- Header Section -->
                    <div class="card-header text-center" style="background-color: #ff7703; border-radius: 5px 5px 0 0;">
                        <div class="row">
                            <div class="col-md-2">
                                <img src="{{asset('assets/frontend/images/icon.png')}}" style="height:45px;width:45px; margin-top: 10px; margin-left: 1px;">
                            </div>
                            <div class="col-md-10 text-white">
                                <h6>Dr. Shakuntala Misra National Rehabilitation University</h6>
                                <p>Mohaan Road, Lucknow U.P. 226017</p>
                                <p>Toll Free No. : 1800 180 0987</p>
                                <p>Website : https://dsmru.up.nic.in/</p>
                            </div>
                        </div>
                    </div>

                    <!-- ID Card Content -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-9">
                                <table class="table table-borderless table-sm" style="font-size: 12px;">
                                    <tr>
                                        <td><strong>ID Card</strong> : {{$icard->enrolment_number}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Validity</strong> : Academic</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Name</strong> : {{$icard->student_name}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date of Birth</strong> : {{date("d/m/Y", strtotime($icard->dob))}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Father's Name</strong> : {{$icard->father_name}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Course</strong> : {{$icard->program}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Disability</strong> : {{$icard->disablity}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gender</strong> : {{$icard->gender}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Student's Contact No</strong> : {{$icard->student_mobile}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nationality</strong> : {{($icard->nationality)?$icard->nationality:'Indian'}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Blood Group</strong> : {{$icard->blood_group}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mailing Address</strong> : <span style="font-size:8px;">{{substr($icard->mailing_address,0,65)}}</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Local Guardian's Name</strong> : {{$icard->local_guardian_name}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Guardian's Contact No</strong> : {{$icard->local_guardian_mobile}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-3 text-center">
                                <div class="photo_here mb-3" style="border: 1px solid #ccc; border-radius: 5px; overflow: hidden; width: 100px; height: 100px; margin: 0 auto;">
                                    @php
                                        $examData = \App\Models\ums\ExamFee::withTrashed()->where('roll_no',$icard->roll_no)->first();
                                        $student_details = \App\Models\ums\Student::withTrashed()->where('roll_number',$icard->roll_no)->first();
                                    @endphp
                                    @if($student_details && $student_details->photo)
                                        <img src="{{$student_details->photo}}" class="img-fluid" alt="Student Photo">
                                    @elseif($examData && $examData->photo)
                                        <img src="{{$examData->photo}}" class="img-fluid" alt="Exam Photo">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-6 col-sm-12">
                <div class="card border-0 shadow-sm">
                    <!-- ID Card Content 2 -->
                    <div class="card-body">
                        <h4 class="text-center">Instructions</h4>
                        <ol class="list-unstyled" style="font-size: 12px; padding-left: 20px;">
                            <li>This identity card is non-transferable</li>
                            <li>Students must carry this card in the University Campus and are required to show on demand.</li>
                            <li>Misuse of the card is liable for punishment as per the University rules.</li>
                            <li>In case of loss/damage of this card, Rs. 200/- will be charged for issuing a duplicate card.</li>
                        </ol>

                        <div class="text-center">
                            @php
                                $qr_text = 'ID Card : '.$icard->enrolment_number.' Name : '.$icard->student_name.' Contact : '.$icard->student_mobile;
                            @endphp
                            <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{$qr_text}}&choe=UTF-8" style="width:100px;height:100px;" alt="QR Code">
                        </div>
                        <div class="photo_here mb-3" style="border: 1px solid #ccc; border-radius: 5px; overflow: hidden; width: 100px; height: 100px; margin: 0 auto;">
                                                                                                        </div>

                        <div class="mt-3 text-right">
                            <img src="{{asset('signatures/vk_singh.png')}}" style="width: 100px; height:auto;" alt="Signature">
                            <div class="font-italic">( Prof. V. K. Singh )</div>
                            <div>Proctor</div>
                        </div>
                        

                        <div class="mt-3">
                            <strong>Note:</strong> This card is property of the University. If found misplaced, please return to the office of the issuing authority.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
