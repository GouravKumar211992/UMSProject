@extends('layouts.app')

@section('styles')
@endsection

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-5 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Voucher Entry</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                <li class="breadcrumb-item active">Voucher List</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-end col-md-7 mb-2 mb-sm-0">
                <div class="form-group breadcrumb-right">
                    <button class="btn btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button>
                    <a class="btn btn-primary btn-sm" href="{{ route('vouchers.create') }}"><i data-feather="plus-circle"></i> Add New</a>
                </div>
            </div>
        </div>

        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="datatables-basic table myrequesttablecbox">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Document Type</th>
                                        <th>Series</th>
                                        <th>Voucher No.</th>
                                        <th>Amount</th>
                                        <th>Document</th>
                                        <th>Approval Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        use App\Helpers\Helper;
                                    @endphp

                                    @foreach ($data as $item)
                                        @php
                                            $mainBadgeClass = match($item->approvalStatus) {
                                                'approved'           => 'success',
                                                'approval_not_required' =>'success',
                                                'draft'             => 'warning',
                                                'submitted'         => 'info',
                                                'partially_approved' => 'warning',
                                                default             => 'danger',
                                            };
                                        @endphp
                                        <tr>
                                            <td class="fw-bolder text-dark">{{ date('d-m-Y',strtotime($item->date)) }}</td>
                                            <td>{{ $item->series->service->name??"-" }}</td>
                                            <td>{{ $item->series->book_code??"-" }}</td>
                                            <td>{{ $item->voucher_no??'-' }}</td>
                                            <td style="text-align: end;">{{ Helper::formatIndianNumber($item->amount) ??"-" }}</td>
                                            <td>
                                                @php $documents = $item->document? json_decode($item->document, true): [];
                                                if(!is_array($documents) && $item->document)
                                                $documents[] = $item->document;
                                                                
                                                @endphp
                                            @if($documents)
                                            <span style="display: flex;margin:0;padding:0">
                                            @foreach($documents as $doc)

                                                <a style="display: flex;margin:0;padding:0" class="dropdown-item" href="voucherDocuments/{{$doc}}" target="_blank">
                                                <i data-feather="file-text" class="fileuploadicon"></i>
                                            </a>
                                       
                                        
                                            @endforeach
                                        </span>
                                            @endif

                                        <td>
                                                <span class="badge rounded-pill badge-light-{{ $mainBadgeClass }}">{{ $item->approvalStatus=="approval_not_required" ? "Approved" : Helper::formatStatus($item->approvalStatus) }}</span>
                                            </td>
                                            <td>
                                                {{$item->remarks??"-"}}
                                            </td>

                                            <td class="tableactionnew">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                                        <i data-feather="more-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('vouchers.edit', ['voucher' => $item->id]) }}">
                                                            <i data-feather="edit-3" class="me-50"></i>
                                                            <span>View</span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
                <div class="modal-dialog sidebar-sm">
                    <form class="add-new-record modal-content pt-0">
                        <div class="modal-header mb-1">
                            <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">×</button>
                        </div>
                        <div class="modal-body flex-grow-1">
                            <div class="mb-1">
                                <label class="form-label" for="fp-range">Select Date</label>
                                <input type="text" id="fp-range" class="form-control flatpickr-range bg-white" name="date"
                                    placeholder="YYYY-MM-DD to YYYY-MM-DD" value="{{ $date }}" />
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Document Type</label>
                                <select class="form-select" id="book_type" name="book_type">
                                    <option value="">Select</option>
                                    @foreach ($bookTypes as $bookType)
                                        <option value="{{ $bookType->id }}" @if($bookType->id==$book_type) selected @endif>{{ $bookType->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Voucher Name</label>
                                <input class="form-control" type="text" name="voucher_name" id="voucher_name" value="{{ $voucher_name }}"/>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Voucher No.</label>
                                <input class="form-control" type="text" name="voucher_no" id="voucher_no" value="{{ $voucher_no }}"/>
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Company</label>
                                <select id="filter-organization" class="form-select select2" multiple name="filter_organization">
                                    <option value="" disabled>Select</option>
                                    @foreach($mappings as $organization)
                                        <option value="{{ $organization->organization->id }}"
                                            {{ $organization->organization->id == $organizationId ? 'selected' : '' }}>
                                            {{ $organization->organization->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer justify-content-start">
                            <button type="submit"
                                class="btn btn-primary data-submit mr-1 apply-filter">Apply</button>
                            <button type="reset" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

        </section>
    </div>
</div>
<!-- END: Content-->

<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
         <form class="ajax-input-form" method="POST" action="{{ route('approveVoucher') }}" data-redirect="{{ route('vouchers.index') }}" enctype='multipart/form-data'>
           @csrf
           <input type="hidden" name="action_type" id="action_type">
           <input type="hidden" name="id" id="id">
          <div class="modal-header">
             <div>
                <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="myModalLabel17">Approve Voucher</h4>
                <p class="mb-0 fw-bold voucehrinvocetxt mt-0">{{Carbon\Carbon::now() -> format('d-m-Y')}}</p>
             </div>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body pb-2">
             <div class="row mt-1">
                <div class="col-md-12">
                   <div class="mb-1">
                      <label class="form-label">Remarks <span class="text-danger">*</span></label>
                      <textarea name="remarks" class="form-control"></textarea>
                   </div>
                   <div class="mb-1">
                      <label class="form-label">Upload Document</label>
                      <input type="file"  multiple class="form-control" />
                   </div>
                </div>
             </div>
          </div>
          <div class="modal-footer justify-content-center">
             <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
             <button type="submit" class="btn btn-primary" id="submit-button">Submit</button>
          </div>
        </form>
       </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function setApproval(id)
    {
        document.getElementById('id').value = id;
        document.getElementById('action_type').value = "approve";
    }

    function setReject(id)
    {
        document.getElementById('id').value = id;
        document.getElementById('action_type').value = "reject";
    }

    $(function () {
        var dt_basic_table = $('.datatables-basic'),
			dt_date_table = $('.dt-date'),
			dt_complex_header_table = $('.dt-complex-header'),
			dt_row_grouping_table = $('.dt-row-grouping'),
			dt_multilingual_table = $('.dt-multilingual'),
			assetPath = '../../../app-assets/';
		if ($('body').attr('data-framework') === 'laravel') {
			assetPath = $('body').attr('data-asset-path');
		}
		var keyword='';

        // DataTable with server-side processing
        if (dt_basic_table.length) {
            var dt_basic = dt_basic_table.DataTable({
                processing: true,
                serverSide: false,
                ordering:false,
                dom:
					'<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"><"col-sm-12 col-md-6 text-end withoutheadbuttin dt-action-buttons text-end"B>>t',
					// '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t',
                buttons: [
                    {
                        extend: 'collection',
                        className: 'btn btn-outline-secondary dropdown-toggle',
                        text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
                        buttons: [
                            {
                                extend: 'print',
                                text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 6,7] },
                                filename: 'Vouchers Report'
                            },
                            {
                                extend: 'csv',
                                text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 6,7] },
                                filename: 'Vouchers Report'
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 6,7] },
                                filename: 'Vouchers Report'
                            },
                            {
                                extend: 'pdf',
                                text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 6,7] },
                                filename: 'Vouchers Report'
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
                                className: 'dropdown-item',
                                exportOptions: { columns: [1, 2, 3, 4, 6,7] },
                                filename: 'Vouchers Report'
                            }
                        ],
                        init: function (api, node, config) {
							$(node).removeClass('btn-secondary');
							$(node).parent().removeClass('btn-group');
							setTimeout(function () {
								$(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
							}, 50);
						},
                    },
                ],
                language: {
                    paginate: {
                        previous: '&nbsp;',
                        next: '&nbsp;'
                    }
                }
            });
            $('div.head-label').html('<h6 class="mb-0">Event List</h6>');
        }
        if (dt_date_table.length) {
			dt_date_table.flatpickr({
				monthSelectorType: 'static',
				dateFormat: 'm/d/Y'
			});
		}

        // Filter functionality
        $('.datatables-basic tbody').on('click', '.delete-record', function () {
			dt_basic.row($(this).parents('tr')).remove().draw();
		});

        $(".apply-filter").on("click", function () {
            dt_basic.draw(); // Redraw the table with new filters
            $(".modal").modal("hide"); // Close the filter modal
        });
    });

</script>

@endsection
