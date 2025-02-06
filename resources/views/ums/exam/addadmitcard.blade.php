@extends('ums.admin.admin-meta')
@section('content')
@section('title') AdmitCard Approval :: @parent @stop 

@section('styles')
<style type="text/css">
    .viewapplication-form p {
        color: #6c757d;
        font-weight: bold;
        border: #eee thin solid;
        min-height: 40px;
        border-radius: 4px;
        padding: 6px;
        background: #f9f9f9;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .content-header-title {
        font-size: 24px;
        font-weight: bold;
    }

    .btn-custom {
        padding: 10px 20px;
        font-size: 16px;
        font-weight: 500;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table-bordered {
        border: 1px solid #ddd;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #ddd;
    }

    .img-preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endsection



        <section class="content mb-3 viewapplication-form">
            <form method="POST" action="">
                @csrf
                <div class="container-fluid">
                    <div class="row mt-3 align-items-center">
                        <div class="col-12">
                            <h5 class="font-weight-bold">Admin Card Form</h5>
                        </div>
                        <div class="col-md-12">
                            <div class="border-bottom mt-3 mb-2 border-innerdashed"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Enrollment Number</label>
                                <p>{{$examfee->students->enrollment_no}}</p>
                                <input type="hidden" name="enrollment_no" value="{{$examfee->students->enrollment_no}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Roll Number</label>
                                <p>{{$examfee->students->roll_number}}</p>
                                <input type="hidden" name="roll_no" value="{{$examfee->students->roll_number}}">
                                <input type="hidden" name="exam_fees_id" value="{{$examfee->id}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Student Name</label>
                                <p>{{$examfee->students->full_name}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Gender</label>
                                <p>{{$examfee->students->gender}}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Mobile Number</label>
                                <p>{{$examfee->students->mobile}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <p>{{$examfee->students->email}}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Father's Name</label>
                                <p>{{$examfee->students->father_name}}</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <p>{{$examfee->students->address}}</p>
                            </div>
                        </div>
                    </div>

                    <section class="col-md-6 connectedSortable">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Candidate Photograph</label>
                                    <img src="{{ $examfee->enrollment->application->photo_url ?? $examfee->photo ?? 'path/to/default-image.jpg' }}" class="img-preview" />
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Candidate's Signature</label>
                                    <img src="{{ $examfee->enrollment->application->signature_url ?? $examfee->signature ?? 'path/to/default-signature.jpg' }}" class="img-preview" />
                                </div>
                            </div>
                            
                        </div>
                    </section>

                    <section class="col-md-12 connectedSortable">
                        <div class="form-group">
                            <label class="form-label">Exam Center Address</label>
                            @if($AdmitCard && $AdmitCard->center)
                                <p>{{$AdmitCard->center->center_name}}</p>
                            @else
                                <select class="form-control" name="center_code" required>
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
                        <table class="table table-bordered">
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
@endsection

@section('scripts')
<!-- Custom JS if needed -->
@endsection
