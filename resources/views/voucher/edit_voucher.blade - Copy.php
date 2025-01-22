@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ url('/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" href="{{ url('/app-assets/js/jquery-ui.css') }}">
@section('styles')
@endsection

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            <form id="voucherForm" action="{{ route('vouchers.update', $data->id) }}" method="POST"
                enctype="multipart/form-data" onsubmit="return check_amount()">
                @csrf
                @method('PUT')

                <input type="hidden" name="status" id="status">

                <div class="content-header pocreate-sticky">
                    <div class="row">
                        <div class="content-header-left col-md-6 col-6 mb-2">
                            <div class="row breadcrumbs-top">
                                <div class="col-12">
                                    <h2 class="content-header-title float-start mb-0">Edit Voucher</h2>
                                    <div class="breadcrumb-wrapper">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('vouchers.index') }}">Vouchers
                                                    List</a></li>
                                            <li class="breadcrumb-item active">Edit Voucher</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content-header-right text-end col-md-6 col-6 mb-2 mb-sm-0">
                            <div class="form-group breadcrumb-right">
                                <button onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm"><i
                                        data-feather="arrow-left-circle"></i> Back</button>
                                @if ($buttons['draft'])
                                    <button type="button" onclick = "submitForm('draft');"
                                        class="btn btn-outline-primary btn-sm mb-50 mb-sm-0" id="submit-button"
                                        name="action" value="draft"><i data-feather='save'></i> Save as Draft</button>
                                @endif
                                @if ($buttons['submit'])
                                    <button type="button" onclick = "submitForm('submitted');"
                                        class="btn btn-primary btn-sm" id="submit-button" name="action"
                                        value="submitted"><i data-feather="check-circle"></i> Submit</button>
                                @endif
                                @if ($buttons['approve'])
                                    <button type="button" id="reject-button" data-bs-toggle="modal"
                                        data-bs-target="#approveModal" onclick = "setReject();"
                                        class="btn btn-danger btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg> Reject</button>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#approveModal" onclick = "setApproval();"><i
                                            data-feather="check-circle"></i> Approve</button>
                                @endif
                                @if ($buttons['amend'])
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#amendmentconfirm"
                                        class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather='edit'></i>
                                        Amendment</button>
                                @endif
                                <input id="submitButton" type="submit" value="Submit" class="hidden" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content-body">
                    <section id="basic-datatable">
                        <div class="row">
                            <div class="col-12">

                                <div class="card">
                                    <div class="card-body customernewsection-form">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div
                                                    class="newheader  d-flex justify-content-between align-items-end border-bottom mb-2 pb-25">
                                                    <div>
                                                        <h4 class="card-title text-theme">Basic Information</h4>
                                                        <p class="card-text">Fill the details</p>
                                                    </div>

                                                    <div class="header-right">
                                                        @php
                                                            use App\Helpers\Helper;
                                                            $mainBadgeClass = match ($data->approvalStatus) {
                                                                'approved' => 'success',
                                                                'approval_not_required' => 'success',
                                                                'draft' => 'warning',
                                                                'submitted' => 'info',
                                                                'partially_approved' => 'warning',
                                                                default => 'danger',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge rounded-pill badge-light-{{ $mainBadgeClass }}">{{ $data->approvalStatus == 'approval_not_required' ? 'Approved' : Helper::formatStatus($data->approvalStatus) }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-8">
                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Document Type <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <select class="form-select" name="book_type_id" id="book_type_id"
                                                            required onchange="getBooks()" disabled>
                                                            <option disabled selected value="">Select</option>
                                                            @foreach ($bookTypes as $bookType)
                                                                <option value="{{ $bookType->id }}"
                                                                    @if ($bookType->id == $data->book_type_id) selected @endif>
                                                                    {{ $bookType->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Series <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <select class="form-select" id="book_id" name="book_id"
                                                            required onchange="get_voucher_details()" disabled>
                                                            @foreach ($books as $alias => $bookSeries)
                                                                @foreach ($bookSeries as $book)
                                                                    <option value="{{ $book->id }}"
                                                                        {{ $data->book_id == $book->id ? 'selected' : '' }}>
                                                                        {{ $book->book_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Voucher Name <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="voucher_name"
                                                            id="voucher_name" required value="{{ $data->voucher_name }}"
                                                            readonly />
                                                        @error('voucher_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Voucher No. <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="voucher_no"
                                                            id="voucher_no" required value="{{ $data->voucher_no }}"
                                                            readonly />
                                                        @error('voucher_no')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>


                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Date <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <input type="date" class="form-control" name="date"
                                                            required value="{{ $data->date }}"
                                                            max="{{ date('Y-m-d') }}" />
                                                        @error('date')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div
                                                    class="step-custhomapp bg-light p-1 customerapptimelines customerapptimelinesapprovalpo">
                                                    <h5
                                                        class="mb-2 text-dark border-bottom pb-50 d-flex align-items-center justify-content-between">
                                                        <strong><i data-feather="arrow-right-circle"></i> Approval History
                                                            {{ $currNumber }}</strong>
                                                        <strong
                                                            class="badge rounded-pill badge-light-secondary amendmentselect">Rev.
                                                            No.
                                                            <select class="form-select revisionNumber">
                                                                <option value=""
                                                                    @if ($currNumber == '') selected @endif>None
                                                                </option>
                                                                @isset($revisionNumbers)
                                                                @foreach ($revisionNumbers as $revisionNumber)
                                                                    <option
                                                                        @if ($currNumber == $revisionNumber) selected @endif
                                                                        value="{{ $revisionNumber }}">
                                                                        {{ $revisionNumber }}</option>
                                                                @endforeach
                                                                @endisset
                                                            </select>
                                                        </strong>
                                                    </h5>
                                                    <ul class="timeline ms-50 newdashtimline ">
                                                        @isset($history)
                                                        @foreach ($history as $his)
                                                            <?php
                                                            $badgeClass = match ($his->approval_type) {
                                                                'approve' => 'success',
                                                                'approval_not_required' => 'success',
                                                                'draft' => 'warning',
                                                                'submitted' => 'info',
                                                                'partially_approved' => 'warning',
                                                                default => 'danger',
                                                            }; ?>
                                                            <li class="timeline-item">
                                                                <span
                                                                    class="timeline-point timeline-point-indicator timeline-point-{{ $badgeClass }}"></span>
                                                                <div class="timeline-event">
                                                                    <div
                                                                        class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                                        <h6>{{ $his->user->name }}</h6>
                                                                        <span
                                                                            class="badge rounded-pill badge-light-{{ $badgeClass }}">{{ ucfirst($his->approval_type) }}</span>
                                                                    </div>
                                                                    <h5>({{ $his->approval_date }})</h5>
                                                                    <p>{{ $his->remarks }}</p>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                        @endisset
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="newheader d-flex justify-content-between align-items-end mt-2 border-top pt-2">
                                            <div class="header-left">
                                                <h4 class="card-title text-theme">Item Wise Detail</h4>
                                                <p class="card-text">Fill the details</p>
                                            </div>
                                            <div class="header-right">
                                                <a href="{{ route('ledgers.create') }}"
                                                    class="btn btn-outline-primary btn-sm" target="_blank"><i
                                                        data-feather="plus"></i> Add Ledger</a>
                                            </div>
                                        </div>


                                        <div class="">
                                            <table
                                                class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th width="550px">Ledger Name</th>
                                                        <th width="150px">Debit Amt</th>
                                                        <th width="150px">Credit Amt</th>
                                                        <th>Cost Center</th>
                                                        <th width="80px">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="item-details-body">
                                                    @foreach ($data->items as $index => $item)
                                                        {{-- <input type="hidden" name="item_id[]" value="{{ $item->id }}"> --}}
                                                        @php
                                                            $no = $index + 1;
                                                        @endphp
                                                        <tr valign="top" id="{{ $no }}">
                                                            <td>{{ $no }}</td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control mw-100 ledgerselect"
                                                                    placeholder="Select Ledger"
                                                                    name="ledger_name{{ $no }}" required
                                                                    id="ledger_name{{ $no }}"
                                                                    data-id="{{ $no }}"
                                                                    value="{{ $item->ledger->name }}" />
                                                                <input type="hidden" name="ledger_id[]" type="hidden"
                                                                    id="ledger_id{{ $no }}" class="ledgers"
                                                                    value="{{ $item->ledger_id }}" />
                                                                <input type="text" class="form-control mw-100 mt-50"
                                                                    name="notes{{ $no }}"
                                                                    value="{{ $item->notes }}" />
                                                            </td>
                                                            <td><input type="text"
                                                                    class="form-control mw-100 dbt_amt debt_{{ $no }}"
                                                                    name="debit_amt[]" id="crd_{{ $no }}"
                                                                    min="0" value="{{ $item->debit_amt }}"
                                                                    @if ($item->debit_amt < 1) readonly @endif />
                                                            </td>
                                                            <td><input type="text"
                                                                    class="form-control mw-100 crd_amt crd_{{ $no }}"
                                                                    name="credit_amt[]" id="debt_{{ $no }}"
                                                                    min="0" value="{{ $item->credit_amt }}"
                                                                    @if ($item->credit_amt < 1) readonly @endif />
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    class="form-control mw-100 centerselecct"
                                                                    placeholder="Select Cost Center"
                                                                    name="cost_center_name{{ $no }}"
                                                                    id="cost_center_name{{ $no }}"
                                                                    data-id="{{ $no }}"
                                                                    @if ($item->costCenter) value="{{ $item->costCenter->name }}" @endif />
                                                                <input type="hidden"
                                                                    name="cost_center_id{{ $no }}"
                                                                    type="hidden" id="cost_center_id{{ $no }}"
                                                                    value="{{ $item->cost_center_id }}" />
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-danger remove-item"><i
                                                                        data-feather="trash-2" class="me-50"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tr class="voucher-tab-foot">
                                                    <td colspan="2" class="text-end text-primary"><strong>Total
                                                            Amt.</strong></td>
                                                    <td>
                                                        <div class="quottotal-bg">
                                                            <h5 id="dbt_total">0.00</h5>
                                                            <input type="hidden" name="amount" id="amount">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="quottotal-bg">
                                                            <h5 id="crd_total">0.00</h5>
                                                        </div>
                                                    </td>
                                                    <td colspan="6"></td>
                                                </tr>
                                            </table>
                                        </div>

                                        <div>
                                            <a href="#" class="text-primary add-contactpeontxt add-item-row"><i
                                                    data-feather='plus'></i> Add New Item</a>
                                        </div>


                                        <div class="row mt-2">

                                            <div class="col-md-4 mb-1">
                                                <label class="form-label">Document</label>
                                                <input type="file" class="form-control" name="document" />
                                                @if ($data->document)
                                                    <a href="{{ asset('voucherDocuments') . '/' . $data->document }}"
                                                        target="_blank">View Uploaded Doc</a>
                                                @endif
                                            </div>


                                            <div class="col-md-12">
                                                <div class="mb-1">
                                                    <label class="form-label">Final Remarks</label>
                                                    <textarea type="text" rows="4" class="form-control" placeholder="Enter Remarks here..." name="remarks">{{ $data->remarks }}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
        </div>
        <!-- Modal to add new record -->

        </section>

    </div>
    </form>

    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="ajax-input-form" method="POST" action="{{ route('approveVoucher') }}"
                    data-redirect="{{ route('vouchers.index') }}" enctype='multipart/form-data'>
                    @csrf
                    <input type="hidden" name="action_type" id="action_type">
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="modal-header">
                        <div>
                            <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="myModalLabel17">Approve
                                Voucher</h4>
                            <p class="mb-0 fw-bold voucehrinvocetxt mt-0">{{ Carbon\Carbon::now()->format('d-m-Y') }}
                            </p>
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
                                    <input type="file" multiple class="form-control" />
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

    </div>
    </div>
    <!-- END: Content-->

    {{-- Amendment Modal --}}
    <div class="modal fade text-start alertbackdropdisabled" id="amendmentconfirm" tabindex="-1"
        aria-labelledby="myModalLabel1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body alertmsg text-center warning">
                    <i data-feather='alert-circle'></i>
                    <h2>Are you sure?</h2>
                    <p>Are you sure you want to <strong>Amendment</strong> this <strong>Voucher</strong>? After Amendment
                        this action cannot be undone.</p>
                    <button type="button" class="btn btn-secondary me-25" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="amendmentSubmit" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('/app-assets/js/jquery-ui.js') }}"></script>
    <script>
        var costcenters = {!! json_encode($cost_centers) !!};
        var bookTypes = {!! json_encode($bookTypes) !!};
        var books = {!! json_encode($books) !!};

        $(document).on('click', '#amendmentSubmit', (e) => {
            let actionUrl = "{{ route('vouchers.amendment', $data->id) }}";
            fetch(actionUrl).then(response => {
                return response.json().then(data => {
                    if (data.status == 200) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error'
                        });
                    }
                    location.reload();
                });
            });
        });

        function setApproval() {
            document.getElementById('action_type').value = "approve";
        }

        function setReject() {
            document.getElementById('action_type').value = "reject";
        }

        function submitForm(status) {
            $('#status').val(status);
            $('#submitButton').click();
        }

        $(document).ready(function() {
            calculate_cr_dr();
        });

        $(function() {
            $(".revisionNumber").change(function() {
                window.location.href =
                    "{{ route('vouchers.edit', ['voucher' => $data->id]) }}?revisionNumber=" + $(this)
                    .val();
            });
        });

        $(function() {
            $(".ledgerselect").autocomplete({
                source: function(request, response) {
                    // get all pre selected ledgers
                    var preLedgers = [];
                    $('.ledgers').each(function() {
                        if ($(this).val() != "") {
                            preLedgers.push($(this).val());
                        }
                    });

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ route('ledgers.search') }}',
                        type: "POST",
                        dataType: "json",
                        data: {
                            keyword: request.term,
                            ids: preLedgers,
                            '_token': '{!! csrf_token() !!}'
                        },
                        success: function(data) {
                            response(data); // Pass the data to the response callback
                        },
                        error: function() {
                            response([]); // Respond with an empty array in case of error
                        }
                    });
                },
                minLength: 0,
                select: function(event, ui) {
                    $(this).val(ui.item.label);

                    // This function is called when an item is selected from the list
                    console.log("Selected: " + ui.item.label + " with ID: " + ui.item.value);
                    // console.log(ui.item);

                    // You can also perform other actions here
                    const id = $(this).attr("data-id");
                    $('#ledger_id' + id).val(ui.item.value);
                    if (ui.item.cost_center_id != "") {
                        console.log(ui.item.cost_center_id);
                        $.each(costcenters, function(ckey, cvalue) {
                            if (ui.item.cost_center_id == cvalue['value']) {
                                $("#cost_center_name" + id).val(cvalue['label']);
                                $("#cost_center_id" + id).val(cvalue['value']);
                            }
                        });
                    }

                    return false;
                },
                change: function(event, ui) {
                    // If the selected item is invalid (i.e., user has not selected from the list)
                    if (!ui.item) {
                        // Clear the input field
                        $(this).val("");

                        // You can also perform other actions here
                        const id = $(this).attr("data-id");
                        $('#ledger_id' + id).val('');
                    }
                },
                focus: function(event, ui) {
                    // Prevent value from being inserted on focus
                    return false; // Prevents default behavior
                },
            }).focus(function() {
                if (this.value == "") {
                    $(this).autocomplete("search");
                }
                return false; // Prevents default behavior
            });

            // Monitor input field for empty state
            $(".ledgerselect").on('input', function() {
                var inputValue = $(this).val();
                if (inputValue.trim() === '') {
                    $('#message').text('Input field is empty.'); // Show message if empty
                    const id = $(this).attr("data-id");
                    $('#ledger_id' + id).val('');
                }
            });

            $(".centerselecct").autocomplete({
                source: costcenters,
                minLength: 0,
                select: function(event, ui) {
                    $(this).val(ui.item.label);

                    // This function is called when an item is selected from the list
                    console.log("Selected: " + ui.item.label + " with ID: " + ui.item.value);
                    console.log(ui.item);

                    // You can also perform other actions here
                    const id = $(this).attr("data-id");
                    $('#cost_center_id' + id).val(ui.item.value);

                    return false;
                },
                change: function(event, ui) {
                    // If the selected item is invalid (i.e., user has not selected from the list)
                    if (!ui.item) {
                        // Clear the input field
                        $(this).val("");

                        // You can also perform other actions here
                        const id = $(this).attr("data-id");
                        $('#cost_center_id' + id).val('');
                    }
                }
            }).focus(function() {
                if (this.value == "") {
                    $(this).autocomplete("search");
                }
            });
        });

        $(document).bind('ctrl+n', function() {
            // $('.add-item-row').click();
            // $('#addnew').click();
            document.getElementById('addnew').click();
            console.log('hii');
        });

        function check_amount() {
            let rowCount = document.querySelectorAll('#item-details-body tr').length;
            for (let index = 1; index <= rowCount; index++) {
                if (parseFloat($('#crd_' + index).val()) == 0 && parseFloat($('#debt_' + index).val()) == 0) {
                    alert('Can not save ledgers with having Credit and Debit amount 0');
                    return false;
                }
            }

            if (parseFloat($('#crd_total').text()) == 0 || parseFloat($('#dbt_total').text()) == 0) {
                alert('Debit and credit amount should be greater than 0');
                return false;
            }
            if (parseFloat($('#crd_total').text()) == parseFloat($('#dbt_total').text())) {
                return true;
            } else {
                alert('Debit and credit amount total should be same!!');
                return false;
            }
        }

        $(document).on('keyup keydown', '.dbt_amt, .crd_amt', function() {
            const inVal = parseFloat($(this).val()) || 0;
            if (inVal > 0) {
                $("." + $(this).attr('id')).val(0);
            }
            calculate_cr_dr();
        });

        // Moving between input fields on pressing ENTER
        $(document).on('keydown', function(event) {
            if (event.keyCode === 13) {
                var activeElement = document.activeElement;
                if (activeElement.tagName === 'INPUT' || activeElement.tagName === 'TEXTAREA') {
                    // Check if the input is not hidden
                    if (activeElement.type !== 'hidden') {
                        event.preventDefault(); // Prevent default enter key behavior

                        // Get the next sibling in the current row
                        var nextField = activeElement.nextElementSibling;
                        while (nextField && nextField.type === 'hidden') {
                            nextField = nextField.nextElementSibling;
                        }

                        // If there's a next field in the row, focus on it
                        if (nextField) {
                            nextField.focus();
                            return; // Stop further navigation within the row
                        }

                        // Otherwise, find the first input in the next column
                        var nextColumn = activeElement.closest('td').nextElementSibling;
                        if (nextColumn) {
                            nextField = nextColumn.querySelector('input, textarea');
                            if (nextField) {
                                nextField.focus();
                                return; // Stop further navigation within the row
                            }
                        }

                        // Otherwise, find the first input in the next row
                        var nextRow = activeElement.closest('tr').nextElementSibling;
                        if (nextRow) {
                            nextField = nextRow.querySelector('input, textarea');
                            if (nextField) {
                                nextField.focus();
                            }
                        }
                    }
                }
            }
        });

        // Remove item row
        $(document).on('click', '.remove-item', function() {
            $(this).parent().parent().remove();
            calculate_cr_dr();
        });

        function calculate_cr_dr() {
            let cr_sum = 0;
            $('.crd_amt').each(function() {
                const value = parseFloat($(this).val()) || 0;
                cr_sum = parseFloat(parseFloat(cr_sum + value).toFixed(2));
            });
            $('#crd_total').text(cr_sum);

            let dr_sum = 0;
            $('.dbt_amt').each(function() {
                const value = parseFloat($(this).val()) || 0;
                dr_sum = parseFloat(parseFloat(dr_sum + value).toFixed(2));
            });
            $('#dbt_total').text(dr_sum);
            $('#amount').val(dr_sum);
        }

        var books = [];
        document.addEventListener('DOMContentLoaded', function() {
            // Add new item row
            document.querySelector('.add-item-row').addEventListener('click', function(e) {
                e.preventDefault();

                var cr_amount = 0;
                var dr_amount = 0;

                if (parseFloat($('#crd_total').text()) == parseFloat($('#dbt_total').text())) {} else if (
                    parseFloat($('#crd_total').text()) > parseFloat($('#dbt_total').text())) {
                    dr_amount = parseFloat($('#crd_total').text()) - parseFloat($('#dbt_total').text());
                } else {
                    cr_amount = parseFloat($('#dbt_total').text()) - parseFloat($('#crd_total').text());
                }

                let rowCount = document.querySelectorAll('#item-details-body tr').length;
                let newRow = `
            <tr valign="top" id="${rowCount + 1}">
                <td>${rowCount + 1}</td>
                <td>
                    <input type="text" class="form-control mw-100 ledgerselect" placeholder="Select Ledger" name="ledger_name${rowCount + 1}" required id="ledger_name${rowCount + 1}" data-id="${rowCount + 1}"/>
                    <input type="hidden" name="ledger_id[]" type="hidden" id="ledger_id${rowCount + 1}" class="ledgers"/>
                    <input placeholder="Line Notes" type="text" class="form-control mw-100 mt-50" name="notes${rowCount + 1}"/>
                </td>
                <td><input type="number" class="form-control mw-100 dbt_amt debt_${rowCount + 1}" name="debit_amt[]" id="crd_${rowCount + 1}" value="${dr_amount}" min="0"/></td>
                <td><input type="number" class="form-control mw-100 crd_amt crd_${rowCount + 1}" name="credit_amt[]" id="debt_${rowCount + 1}" value="${cr_amount}" min="0"/></td>
                <td>
                    <input type="text" class="form-control mw-100 centerselecct" placeholder="Select Cost Center" name="cost_center_name${rowCount + 1}" id="cost_center_name${rowCount + 1}" data-id="${rowCount + 1}"/>
                    <input type="hidden" name="cost_center_id${rowCount + 1}" type="hidden" id="cost_center_id${rowCount + 1}"/>
                </td>
                <td>
                    <a href="#" class="text-danger remove-item"><i data-feather="trash-2" class="me-50"></i></a>
                </td>
            </tr>`;
                document.querySelector('#item-details-body').insertAdjacentHTML('beforeend', newRow);
                calculate_cr_dr();

                feather.replace({
                    width: 14,
                    height: 14
                });

                $(".ledgerselect").autocomplete({
                    source: function(request, response) {
                        // get all pre selected ledgers
                        var preLedgers = [];
                        $('.ledgers').each(function() {
                            if ($(this).val() != "") {
                                preLedgers.push($(this).val());
                            }
                        });

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            url: '{{ route('ledgers.search') }}',
                            type: "POST",
                            dataType: "json",
                            data: {
                                keyword: request.term,
                                ids: preLedgers,
                                '_token': '{!! csrf_token() !!}'
                            },
                            success: function(data) {
                                response(
                                data); // Pass the data to the response callback
                            },
                            error: function() {
                                response(
                            []); // Respond with an empty array in case of error
                            }
                        });
                    },
                    minLength: 0,
                    select: function(event, ui) {
                        $(this).val(ui.item.label);

                        // This function is called when an item is selected from the list
                        console.log("Selected: " + ui.item.label + " with ID: " + ui.item
                        .value);
                        // console.log(ui.item);

                        // You can also perform other actions here
                        const id = $(this).attr("data-id");
                        $('#ledger_id' + id).val(ui.item.value);
                        if (ui.item.cost_center_id != "") {
                            console.log(ui.item.cost_center_id);
                            $.each(costcenters, function(ckey, cvalue) {
                                if (ui.item.cost_center_id == cvalue['value']) {
                                    $("#cost_center_name" + id).val(cvalue['label']);
                                    $("#cost_center_id" + id).val(cvalue['value']);
                                }
                            });
                        }

                        return false;
                    },
                    change: function(event, ui) {
                        // If the selected item is invalid (i.e., user has not selected from the list)
                        if (!ui.item) {
                            // Clear the input field
                            $(this).val("");

                            // You can also perform other actions here
                            const id = $(this).attr("data-id");
                            $('#ledger_id' + id).val('');
                        }
                    },
                    focus: function(event, ui) {
                        // Prevent value from being inserted on focus
                        return false; // Prevents default behavior
                    },
                }).focus(function() {
                    if (this.value == "") {
                        $(this).autocomplete("search");
                    }
                    return false; // Prevents default behavior
                });

                // Monitor input field for empty state
                $(".ledgerselect").on('input', function() {
                    var inputValue = $(this).val();
                    if (inputValue.trim() === '') {
                        $('#message').text('Input field is empty.'); // Show message if empty
                        const id = $(this).attr("data-id");
                        $('#ledger_id' + id).val('');
                    }
                });

                $(".centerselecct").autocomplete({
                    source: costcenters,
                    minLength: 0,
                    select: function(event, ui) {
                        $(this).val(ui.item.label);

                        // This function is called when an item is selected from the list
                        console.log("Selected: " + ui.item.label + " with ID: " + ui.item
                        .value);
                        console.log(ui.item);

                        // You can also perform other actions here
                        const id = $(this).attr("data-id");
                        $('#cost_center_id' + id).val(ui.item.value);

                        return false;
                    },
                    change: function(event, ui) {
                        // If the selected item is invalid (i.e., user has not selected from the list)
                        if (!ui.item) {
                            // Clear the input field
                            $(this).val("");

                            // You can also perform other actions here
                            const id = $(this).attr("data-id");
                            $('#cost_center_id' + id).val('');
                        }
                    }
                }).focus(function() {
                    if (this.value == "") {
                        $(this).autocomplete("search");
                    }
                });
            });
        });

        function getBooks() {
            $('#book_id').empty();
            $('#book_id').prepend('<option disabled selected value="">Select Series</option>');

            const book_type_id = $('#book_type_id').val();
            $.each(bookTypes, function(key, value) {
                if (value['id'] == book_type_id) {
                    books = value['books'];
                }
            });

            $.each(books, function(key, value) {
                $("#book_id").append("<option value ='" + value['id'] + " '>" + value['book_name'] + " </option>");
            });
        }

        function get_voucher_details() {
            $.each(books, function(key, value) {
                if (value['id'] == $('#book_id').val()) {
                    $('#voucher_name').val(value['book_name']);
                }
            });

            $.ajax({
                url: '{{ url('get_voucher_no') }}/' + $('#book_id').val(),
                type: 'GET',
                success: function(data) {
                    if (data.type == "Auto") {
                        $("#voucher_no").attr("readonly", true);
                        $('#voucher_no').val(data.voucher_no);
                    } else {
                        $("#voucher_no").attr("readonly", false);
                    }
                }
            });
        }
    </script>
@endsection
