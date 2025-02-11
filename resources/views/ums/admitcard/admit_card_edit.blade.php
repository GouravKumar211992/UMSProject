@extends("ums.admin.admin-meta")
@section("content")

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    @include('ums.admin.notifications')
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Admit Card Form</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                                <li class="breadcrumb-item">Admin form</li>  
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right"> 
                    @if($AdmitCard)
                        <button type="submit" form="cat_form" class="btn btn-primary btn-sm">
                            Admit Card Already Generated
                        </button>
                    @else
                        <button type="submit" form="cat_form" class="btn btn-primary btn-sm">
                            Generate Admit Card
                        </button>
                    @endif
                </div>
            </div>
        </div>
       
        <section class="content mb-3 viewapplication-form ms-3" style="background-color: white; padding: 20px; border-radius: 8px;">
            <form method="POST" action="">
                @csrf
                <div class="container ms-3">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-10 col-lg-8">
                            <div class="row mt-3 align-items-center">
                                <div class="col-12">
                                    <h5 class="font-weight-bold">Admin Card Form</h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="border-bottom mt-3 mb-2 border-innerdashed"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Enrollment Number</label>
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$examfee->students->enrollment_no}}</p>
                                        <input type="hidden" name="enrollment_no" value="{{$examfee->students->enrollment_no}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Roll Number</label>
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$examfee->students->roll_number}}</p>
                                        <input type="hidden" name="roll_no" value="{{$examfee->students->roll_number}}">
                                        <input type="hidden" name="exam_fees_id" value="{{$examfee->id}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Student Name</label>
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$examfee->students->full_name}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Gender</label>
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$examfee->students->gender}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Mobile Number</label>
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$examfee->students->mobile}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$examfee->students->email}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Father's Name</label>
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$examfee->students->father_name}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$examfee->students->address}}</p>
                                    </div>
                                </div>
                            </div>

                            <section class="col-md-12 connectedSortable">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Candidate Photograph</label>
                                            <img src="{{ $examfee->enrollment->application->photo_url ?? $examfee->photo ?? 'path/to/default-image.jpg' }}" class="img-fluid img-preview" />
                                        </div>
                                    </div>
                                  
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Candidate's Signature</label>
                                            <img src="{{ $examfee->enrollment->application->signature_url ?? $examfee->signature ?? 'path/to/default-signature.jpg' }}" class="img-fluid img-preview" />
                                        </div>
                                    </div>
                                  
                                </div>
                            </section>

                            <section class="col-md-12 connectedSortable">
                                <div class="form-group">
                                    <label class="form-label">Exam Center Address</label>
                                    @if($AdmitCard && $AdmitCard->center)
                                        <p class="bg-white p-2 rounded border shadow-sm">{{$AdmitCard->center->center_name}}</p>
                                    @else
                                        <select class="form-control bg-white border shadow-sm" name="center_code" required>
                                            <option value="">Please Select Exam Center</option>
                                            @foreach($examCenters as $examCenter)
                                                <option value="{{$examCenter->center_code}}">{{$examCenter->center_code}} - {{$examCenter->center_name}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </section>

                            <section class="col-md-12 connectedSortable">
                                <hr />
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject Name</th>
                                            <th>Subject Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subjects as $subject)
                                        <tr>
                                            <td>{{$subject->sub_code}}</td>
                                            <td>{{$subject->name}}</td>
                                            <td>{{ucwords($subject->subject_type)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </section>
                        </div>
                   

                <div class="text-center mt-3">
                    @if($AdmitCard)
                        <button type="button" class="btn btn-success btn-custom">
                            <i class="fa fa-send" aria-hidden="true"></i> Admit Card Already Generated
                        </button>
                        @else
                        <button type="submit" class="btn btn-warning btn-custom">
                            <i class="fa fa-send" aria-hidden="true"></i> Generate Admit Card
                        </button>
                    @endif
                   
                </div>
            </form>
        </section>

    </div>
</div>
<!-- END: Content-->
@endsection
