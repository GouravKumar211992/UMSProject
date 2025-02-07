<<<<<<< HEAD
 @extends('ums.admin.admin-meta')
=======
@extends('ums.admin.admin-meta')
>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7

<!-- BEGIN: Body-->
@section('content')
    <!-- Content Section -->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        @include('ums.admin.notifications')
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Entrance-Exam-Schedule</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('dashboard')}}">Home</a></li>  
                                    <li class="breadcrumb-item active text-nowrap">Entrance-Exam-Schedule</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="{{url('phd_entrance_add')}}">
                            <i data-feather="file-text"></i> Add Entrance-Exam-Schedule 
                        </a> 
                    </div>
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
                                            <tr>
                                                <th>ID#</th>
                                                <th>Program Name</th>
                                                <th>Program Code</th>
                                                <th>Exam Date</th>
                                                <th>Exam Timing</th>
                                                <th>Exam Ending Timing</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $item)
                                            <tr>
                                                <td>{{$item['id']}}</td>
                                                <td class="fw-bolder text-dark">{{$item['program_name']}}</td>
                                                <td>{{$item['program_code']}}</td>
                                                <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">{{$item['exam_date']}}</span></td>
                                                <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">{{$item['exam_time']}}</span></td>
                                                <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">{{$item['exam_ending_time']}}</span></td>
                                                <td class="tableactionnew">
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown">
                                                            <i data-feather="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{ url('phd_entrance_edit/' . $item->id) }}">
                                                                <i data-feather="edit" class="me-50"></i>
                                                                <span>Edit</span>
                                                            </a>
                                                            <a class="dropdown-item" onclick="deletePhd('{{$item->id}}')" ">
                                                                <i data-feather="trash-2" class="me-50"></i>
                                                                <span onclick="window.confirm('Are you sure ? delere this data')">Delete</span>
                                                            </a>
                                                        </div>
                                                    </div> 
                                                </td>
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
        </div>
    </div>

    @endsection

    <script>
        // Function to confirm deletion
        function confirm_delete() {
            var v = confirm('Do you really want to delete this entrance exam schedule?');
            if (v == true) {
                return true;
            } else {
                return false;
            }
        }

        // Function to export data
        function exportdata() {
            var fullUrl_count = "{{ count(explode('?', urldecode(url()->full()))) }}";
            var fullUrl = "{{ url()->full() }}";

            if (fullUrl_count > 1) {
                fullUrl = fullUrl.split('?')[1]; // Get the query string
                fullUrl = fullUrl.replace(/&amp;/g, '&'); // Fix &amp; to &
                fullUrl = '?' + fullUrl; // Add the query string back
            } else {
                fullUrl = ''; // No query parameters
            }

            var url = "{{ url('admin/master/entranceexamschedule/entranceexamschedule-export') }}" + fullUrl;
            window.location.href = url; // Redirect to the export URL
        }

        // Function to edit fee
        function editFee(slug) {
            var url = "{{ url('phd_entrance_edit') }}/" + slug;
            window.location.href = url; // Redirect to the edit page
        }

        // Function to delete fee
        function deletePhd(slug) {
            var url = "{{ url('phd_entrance_delete') }}/" + slug;
            window.location.href = url; // Redirect to the delete page
        }
    </script>
