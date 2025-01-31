@extends('ums.admin.admin-meta')

@section('content')
    <!-- Content Start -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Edit Exam Center</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Edit Exam Center</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-dark btn-sm mb-50 mb-sm-0" onclick="location.href='{{ url('/exam_center') }}'">
                            <i data-feather="arrow-left-circle"></i> GO BACK
                        </button>
                    </div>
                </div>
            </div>
            <div class="content-body bg-white py-4 mb-4 shadow">
                <form action="{{ url('exam_center/update',$data->id) }}" method="POST">
                    @csrf
                    @method('PUT') 
                    <div class="row g-0 mt-3 mb-3 text-center px-5">
                        <div class="col-md-12 text-center ms-md-4">
                            <div class="row align-items-center mb-1">
                                <div class="col-md-2 text-start">
                                    <label class="form-label">Center Code:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="center_code" class="form-control" value="{{ $data->center_code }}" required>
                                </div>
                            </div>
                            <div class="row align-items-center mb-1">
                                <div class="col-md-2 text-start">
                                    <label class="form-label">Center Name:<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="center_name" class="form-control" value="{{ $data->center_name }}" required>
                                </div>
                            </div>
                            <div class="row align-items-center mb-1">
                                <div class="col-md-2 text-start">
                                    <label class="form-label">Status<span class="text-danger m-0">*</span></label>
                                </div>
                                <div class="col-md-8">
                                    <select name="status" class="form-control" required>
                                        <option value="active" {{ $data->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $data->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Exam Center</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Content End -->
@endsection
