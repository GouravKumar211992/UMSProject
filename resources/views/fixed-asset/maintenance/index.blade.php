@extends('layouts.app')


@section('title', 'Fixed Asset')

@section('content')

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-5 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Maint. & Condition</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('finance.fixed-asset.maintenance.index') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Asset List</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-end col-md-7 mb-2 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button class="btn btn-primary btn-sm mb-50 mb-sm-0" data-bs-target="#filter"
                            data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button>
                        <a class="btn btn-dark btn-sm mb-50 mb-sm-0"
                            href="{{ route('finance.fixed-asset.maintenance.create') }}"><i data-feather="file-text"></i>
                            Add New</a>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">


                                <div class="table-responsive">
                                    <table class="datatables-basic table myrequesttablecbox ">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Asset NAme</th>
                                                <th>Asset Code</th>
                                                <th>Verf. Date</th>
                                                <th>Condition</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($data as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item->asset->asset_name }}</td>
                                                    <td>{{ $item->asset->asset_code }}</td>
                                                    <td>{{ $item->verf_date }}</td>
                                                    <td>{{ $item->condition }}</td>
                                                    <td class="tableactionnew">
                                                        <div class="dropdown">
                                                            <button type="button"
                                                                class="btn btn-sm dropdown-toggle hide-arrow py-0"
                                                                data-bs-toggle="dropdown">
                                                                <i data-feather="more-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item"
                                                                    href="{{ route('finance.fixed-asset.maintenance.show', $item->id) }}">
                                                                    <i data-feather="edit" class="me-50"></i>
                                                                    <span>View Detail</span>
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('finance.fixed-asset.maintenance.edit', $item->id) }}">
                                                                    <i data-feather="edit-3" class="me-50"></i>
                                                                    <span>Edit</span>
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">No data found</td>
                                                </tr>
                                            @endforelse
                                        </tbody>


                                    </table>
                                </div>





                            </div>
                        </div>
                    </div>
                    <!-- Modal to add new record -->
                </section>
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
        <div class="modal-dialog sidebar-sm">
            <!-- Assuming the form is in the same Blade view -->
            <form class="add-new-record modal-content pt-0" method="GET"
                action="{{ route('finance.fixed-asset.maintenance.index') }}">
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="fp-range">Select Date Range</label>
                        <input type="text" id="fp-range" name="date_range" class="form-control flatpickr-range"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" value="{{ old('date_range') }}" />
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="asset">Asset Code</label>
                        <select name="asset" id="asset" class="form-select select2">
                            <option value="" {{ old('asset') == '' ? 'selected' : '' }}>Select</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" {{ old('asset') == $asset->id ? 'selected' : '' }}>
                                    {{ $asset->asset_code }} ({{ $asset->asset_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-1">
                        <label class="form-label" for="condition">Condition</label>
                        <select class="form-select" name="condition">
                            <option value="">Select</option>
                            <option value="excellent">Excellent</option>
                            <option value="good">Good</option>
                            <option value="average">Average</option>

                        </select>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="submit" class="btn btn-primary data-submit mr-1">Apply</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
            </form>


        </div>
    </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
        $(function() {
    var dt_basic_table = $('.datatables-basic'); // Select the table with the given class

    if (dt_basic_table.length) {
        var dt_basic = dt_basic_table.DataTable({
            order: [], // Disable default sorting
            columnDefs: [
                {
                    orderable: false,
                    targets: [0, -1] // Disable sorting for the first (#) and last (Action) columns
                },
                {
                    targets: 4, // Condition column (adjust if necessary based on your structure)
                    render: function(data, type, row, meta) {
                        if (type === 'export') {
                            return data; // Return raw data for export
                        }
                        // Apply badges for Condition
                        return `<span class="badge rounded-pill bg-${data === 'excellent' ? 'success' : data === 'average' ? 'warning' : data === 'poor' ? 'danger' : 'secondary'}">${data}</span>`;
                    }
                },
                {
                    targets: 1, // Condition column (adjust if necessary based on your structure)
                    render: function(data, type, row, meta) {
                        if (type === 'export') {
                            return data; // Return raw data for export
                        }
                        // Apply badges for Condition
                        return `<span class="fw-bolder text-dark">${data}</span>`;
                    }
                }
            ],
            dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 10, // Set initial row display count
            lengthMenu: [10, 25, 50, 75, 100], // Row count options
            buttons: [{
                extend: 'collection',
                className: 'btn btn-outline-secondary dropdown-toggle',
                text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
                buttons: [{
                    extend: 'csv',
                    text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
                    className: 'dropdown-item',
                    filename: 'Asset_TrackingReport',
                    exportOptions: {
                        columns: ':not(:first-child):not(:last-child)', // Exclude the first (#) and last (Action) columns
                        format: {
                            header: function(data, columnIdx) {
                                const headers = ['Asset Name', 'Asset Code', 'Verf. Date', 'Condition', 'Action'];
                                return headers[columnIdx - 1] || data;
                            }
                        }
                    }
                },
                {
                    extend: 'excel',
                    text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                    className: 'dropdown-item',
                    filename: 'Asset_TrackingReport',
                    exportOptions: {
                        columns: ':not(:first-child):not(:last-child)', // Exclude the first (#) and last (Action) columns
                        format: {
                            header: function(data, columnIdx) {
                                const headers = ['Asset Name', 'Asset Code', 'Verf. Date', 'Condition', 'Action'];
                                return headers[columnIdx - 1] || data;
                            }
                        }
                    }
                }]
            }],
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary');
                $(node).parent().removeClass('btn-group');
                setTimeout(function() {
                    $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                }, 50);
            },
            language: {
                paginate: {
                    previous: '&nbsp;',
                    next: '&nbsp;'
                }
            }
        });

        // Make the second row bold
        $('#datatable tbody tr:nth-child(2)').css('font-weight', 'bold');

        // Set table header label
        $('div.head-label').html('<h6 class="mb-0">Asset Tracking Report</h6>');

        // Handle delete record functionality (if needed)
        $('.datatables-basic tbody').on('click', '.delete-record', function() {
            dt_basic.row($(this).parents('tr')).remove().draw();
        });
    }
});



        function showToast(icon, title) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
            });
            Toast.fire({
                icon,
                title
            });
        }

        @if (session('success'))
            showToast("success", "{{ session('success') }}");
        @endif

        @if (session('error'))
            showToast("error", "{{ session('error') }}");
        @endif

        @if ($errors->any())
            showToast('error',
                "@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach"
            );
        @endif
    </script>

@endsection
