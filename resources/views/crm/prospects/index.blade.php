@extends('layouts.crm')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-8 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <div class="breadcrumb-wrapper">
                            <h2 class="content-header-title float-start mb-0">Propects</h2>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('crm.home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Propect List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-sm-end col-md-4 mb-50 mb-sm-0">
                <div class="form-group breadcrumb-right">
                    <button onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0"><i
                            data-feather="arrow-left-circle"></i> Back</button>
                    <a href="{{ route('prospects.csv') }}{{ Request::getQueryString() ? '?' . Request::getQueryString() : '' }}" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather="file-text"></i> Export to CSV</a>
                    <a href="{{ route('notes.create') }}" class="btn btn-dark btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><i data-feather="plus-square"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            @include('crm.partials.table-header',[
                                'searchPlaceholder' => 'Search by order number.'
                            ])
                            <div class="table-responsive">
                                <table class="table myrequesttablecbox loanapplicationlist">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Customer Name</th>
                                            <th>Lead Status</th>
                                            <th>Customer Value</th>
                                            <th>Industry</th>
                                            <th>Current Supplier Split</th>
                                            <th>Last Contact Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers as $key => $customer)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="fw-bolder text-dark">
                                                <a href="{{ route('prospects.view',['customerCode' => $customer->customer_code]) }}">
                                                    {{ $customer->company_name }}
                                                </a>
                                            </td>
                                            <td>{{ isset($customer->meetingStatus->title) ? $customer->meetingStatus->title : '-' }}</td>
                                            <td>{{ $customer->sales_figure }}</td>
                                            <td>{{ isset($customer->industry->name) ? $customer->industry->name : '-' }}</td>
                                            <td></td>
                                            <td>{{ isset($customer->latestDiary->created_at) ? App\Helpers\GeneralHelper::dateFormat($customer->latestDiary->created_at) : '-' }}</td>
                                            <td>
                                                <a href="{{ route('prospects.view',['customerCode' => $customer->customer_code]) }}">
                                                    <i data-feather="eye" class="me-50"></i>
                                                </a>  
                                            </td>
                                        </tr>
                                        @empty
                                            <tr><td class="text-danger text-center" colspan="12">No record(s) found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end mx-1 mt-50">
                                {{-- Pagination --}}
                                {{ $customers->appends(request()->input())->links('crm.partials.pagination') }}
                                {{-- Pagination End --}}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection