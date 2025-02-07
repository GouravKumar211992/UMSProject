<<<<<<< HEAD
 @extends('ums.admin.admin-meta')
<!-- END: Head-->
=======
@extends('ums.admin.admin-meta')

>>>>>>> 102b6cb77da26819a1831c7b3f50e8457416cce7
@section('content')

<!-- Page Title and Navigation -->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Department Faculties</h2>
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
                    <a class="btn btn-dark btn-sm mb-50 mb-sm-0" href="{{ route('department_faculty_add') }}"><i data-feather="file-text"></i> Add</a>
                </div>
            </div>
        </div>

        <!-- Table displaying Department Faculties -->
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
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td class="tableactionnew">
                <div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown">
                        <i data-feather="more-vertical"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ url('/department_faculty/edit/' . $item->id) }}">
                            <i data-feather="edit" class="me-50"></i>
                            <span>Edit</span>
                        </a>
                        <a class="dropdown-item" href="{{ url('/department_faculty/delete/' . $item->id) }}" onclick="return confirm('Are you sure you want to delete this item?');">
                            <i data-feather="trash-2" class="me-50"></i>
                            <span>Delete</span>
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
