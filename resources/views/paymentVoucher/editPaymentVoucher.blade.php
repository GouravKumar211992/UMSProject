@extends('layouts.app')

@section('styles')
<style>
    .settleInput {
        text-align: right;
    }
</style>
@endsection

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

        <form id="voucherForm" action="{{ route($editUrl,$data->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return check_amount()">
            @csrf
            @method('PUT')

                <input type="hidden" name="status" id="status" value="{{ $data->document_status }}">
                <input type="hidden" name="totalAmount" id="totalAmount" value="{{ $data->amount }}">

                <input type="hidden" name="org_currency_id" id="org_currency_id" value="{{ $data->org_currency_id }}">
                <input type="hidden" name="org_currency_code" id="org_currency_code"
                    value="{{ $data->org_currency_code }}">
                <input type="hidden" name="org_currency_exg_rate" id="org_currency_exg_rate"
                    value="{{ $data->org_currency_exg_rate }}">

                <input type="hidden" name="comp_currency_id" id="comp_currency_id" value="{{ $data->comp_currency_id }}">
                <input type="hidden" name="comp_currency_code" id="comp_currency_code"
                    value="{{ $data->comp_currency_code }}">
                <input type="hidden" name="comp_currency_exg_rate" id="comp_currency_exg_rate"
                    value="{{ $data->comp_currency_exg_rate }}">

                <input type="hidden" name="group_currency_id" id="group_currency_id"
                    value="{{ $data->group_currency_id }}">
                <input type="hidden" name="group_currency_code" id="group_currency_code"
                    value="{{ $data->group_currency_code }}">
                <input type="hidden" name="group_currency_exg_rate" id="group_currency_exg_rate"
                    value="{{ $data->group_currency_exg_rate }}">

                <input type="hidden" name="document_type" id="document_type" value="{{ $data->document_type }}">

            <div class="content-header pocreate-sticky">
                <div class="row">
                    <div class="content-header-left col-md-6 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Edit {{ Str::ucfirst($data->document_type) }} Voucher</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                        <li class="breadcrumb-item"><a href="{{ $indexUrl }}" >{{ Str::ucfirst($data->document_type) }} Vouchers</a></li>
                                        <li class="breadcrumb-item active">Edit New</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                            <div class="form-group breadcrumb-right">
                                <a onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm"><i
                                        data-feather="arrow-left-circle"></i> Back</a>
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
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-x-circle">
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
                                @if ($buttons['voucher'])
                                    <button type="button" onclick="onPostVoucherOpen('posted');"
                                        class="btn btn-dark btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-file-text">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg> Voucher</button>
                                @endif

                                @if ($buttons['post'])
                                    <button onclick = "onPostVoucherOpen();" type = "button"
                                        class="btn btn-warning btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-check-circle">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg> Post</button>
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
                                                    class="newheader d-flex justify-content-between align-items-end border-bottom mb-2 pb-25">
                                                    <div>
                                                        <h4 class="card-title text-theme">Basic Information</h4>
                                                        <p class="card-text">Fill the details</p>
                                                    </div>
                                                    <div class="header-right">
                                                        @php
                                                            use App\Helpers\Helper;
                                                            $mainBadgeClass = match ($data->document_status) {
                                                                'approved' => 'success',
                                                                'approval_not_required' => 'success',
                                                                'draft' => 'warning',
                                                                'submitted' => 'info',
                                                                'partially_approved' => 'warning',
                                                                default => 'danger',
                                                            };
                                                        @endphp
                                                        <span
                                                            class="badge rounded-pill badge-light-{{ $mainBadgeClass }}">{{ $data->document_status == 'approval_not_required' ? 'Approved' : Helper::formatStatus($data->document_status) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Series <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <select class="form-select" id="book_id" name="book_id"
                                                            required onchange="get_voucher_details()" disabled>
                                                            <option disabled selected value="">Select</option>
                                                            @foreach ($books as $book)
                                                                <option value="{{ $book->id }}"
                                                                    @if ($data->book_id == $book->id) selected @endif>
                                                                    {{ $book->book_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Document No. <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" id="voucher_no"
                                                            name="voucher_no" required value="{{ $data->voucher_no }}"
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
                                                        <input type="date" class="form-control" readonly
                                                            name="date" id="date" required
                                                            value="{{ $data->date }}" max="{{ date('Y-m-d') }}" />
                                                    </div>

                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Payment Type <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <div class="demo-inline-spacing">
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio" id="Bank" value="Bank"
                                                                    name="payment_type" class="form-check-input"
                                                                    @if ($data->payment_type == 'Bank') checked @endif>
                                                                <label class="form-check-label fw-bolder"
                                                                    for="Bank">Bank</label>
                                                            </div>
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio" id="Cash" value="Cash"
                                                                    name="payment_type" class="form-check-input"
                                                                    @if ($data->payment_type == 'Cash') checked @endif>
                                                                <label class="form-check-label fw-bolder"
                                                                    for="Cash">Cash</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Payment Date <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="date" class="form-control" name="payment_date"
                                                            id="payment_date" required value="{{ $data->payment_date }}"
                                                            max="{{ date('Y-m-d') }}" />
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1 bankfield"
                                                    @if ($data->payment_type == 'Cash') style="display: none" @endif>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Bank Name <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-3 mb-1 mb-sm-0">
                                                        <select class="form-control select2 bankInput" name="bank_id"
                                                            id="bank_id" onchange="getAccounts()"
                                                            @if ($data->payment_type == 'Bank') required @endif>
                                                            <option selected disabled value="">Select Bank</option>
                                                            @foreach ($banks as $bank)
                                                                <option value="{{ $bank->id }}"
                                                                    @if ($data->bank_id == $bank->id) selected @endif>
                                                                    {{ $bank->bank_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="form-label">A/c No. <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select class="form-control select2 bankInput" name="account_id"
                                                            id="account_id"
                                                            @if ($data->payment_type == 'Bank') required @endif>
                                                            <option selected disabled value="">Select Bank Account
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1 bankfield">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Payment Mode <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-3 mb-1 mb-sm-0">
                                                        <select class="form-control select2 bankInput" name="payment_mode"
                                                            @if ($data->payment_type == 'Bank') required @endif>
                                                            <option value="">Select</option>
                                                            <option @if ('IMPS/RTGS' == $data->payment_mode) selected @endif>
                                                                IMPS/RTGS</option>
                                                            <option @if ('NEFT' == $data->payment_mode) selected @endif>NEFT
                                                            </option>
                                                            <option @if ('By Cheque' == $data->payment_mode) selected @endif>By
                                                                Cheque</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label class="form-label">Ref No. <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control bankInput"
                                                            name="reference_no" value="{{ $data->reference_no }}"
                                                            @if ($data->payment_type == 'Bank') required @endif />
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1 cashfield"
                                                    @if ($data->payment_type == 'Bank') style="display: none" @endif>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Ledger <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <select class="form-control select2" name="ledger_id"
                                                            id="ledger_id"
                                                            @if ($data->payment_type == 'Cash') required @endif>
                                                            <option disabled selected value="">Select Ledger</option>
                                                            @foreach ($ledgers as $ledger)
                                                                <option value="{{ $ledger->id }}"
                                                                    @if ($ledger->id == $data->ledger_id) selected @endif>
                                                                    {{ $ledger->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Currency <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-5 mb-1 mb-sm-0">
                                                        <select class="form-control select2" name="currency_id"
                                                            id="currency_id" onchange="getExchangeRate()">
                                                            <option>Select Currency</option>
                                                            @foreach ($currencies as $currency)
                                                                <option value="{{ $currency->id }}"
                                                                    @if ($data->currency_id == $currency->id) selected @endif>
                                                                    {{ $currency->name . ' (' . $currency->short_name . ')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label mt-50">Exchange Rates</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="d-flex align-items-center">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="d-flex">
                                                                        <input type="text" class="form-control"
                                                                            readonly id="base_currency_code"
                                                                            value="{{ $data->org_currency_code }}"
                                                                            style="text-transform:uppercase;width: 80px; border-right: none; border-radius: 7px 0 0 7px" />

                                                                        <input type="text" class="form-control"
                                                                            readonly id="orgExchangeRate"
                                                                            id="orgExchangeRate"
                                                                            value="{{ round($data->org_currency_exg_rate, 2) }}"
                                                                            style="width: 80px;  border-radius:0 7px 7px 0" />


                                                                    </div>
                                                                    <label class="form-label">Base</label>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="d-flex">
                                                                        <input type="text" class="form-control"
                                                                            readonly id="company_currency_code"
                                                                            value="{{ $data->comp_currency_code }}"
                                                                            style="text-transform:uppercase;width: 80px; border-right: none; border-radius: 7px 0 0 7px" />

                                                                        <input type="text" class="form-control"
                                                                            readonly id="company_exchange_rate"
                                                                            value="{{ round($data->comp_currency_exg_rate, 2) }}"
                                                                            style="width: 80px;  border-radius:0 7px 7px 0" />


                                                                    </div>
                                                                    <label class="form-label">Company</label>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="d-flex">
                                                                        <input type="text" class="form-control"
                                                                            readonly id="grp_currency_code"
                                                                            value="{{ $data->group_currency_code }}"
                                                                            style="text-transform:uppercase;width: 80px; border-right: none; border-radius: 7px 0 0 7px" />

                                                                        <input type="text" class="form-control"
                                                                            readonly id="grp_exchange_rate"
                                                                            value="{{ round($data->group_currency_exg_rate, 2) }}"
                                                                            style="width: 80px;  border-radius:0 7px 7px 0" />


                                                                    </div>
                                                                    <label class="form-label">Group</label>
                                                                </div>
                                                            </div>



                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div
                                                    class="step-custhomapp bg-light p-1 customerapptimelines customerapptimelinesapprovalpo">
                                                    <h5
                                                        class="mb-2 text-dark border-bottom pb-50 d-flex align-items-center justify-content-between">
                                                        <strong><i data-feather="arrow-right-circle"></i> Approval
                                                            History</strong>
                                                        <strong
                                                            class="badge rounded-pill badge-light-secondary amendmentselect">Rev.
                                                            No.
                                                            <select class="form-select revisionNumber">
                                                                <option value="">None</option>
                                                                @foreach ($revisionNumbers as $revisionNumber)
                                                                    <option
                                                                        @if ($currNumber == $revisionNumber) selected @endif>
                                                                        {{ $revisionNumber }}</option>
                                                                @endforeach
                                                            </select>
                                                        </strong>
                                                    </h5>
                                                    <ul class="timeline ms-50 newdashtimline ">
                                                        @foreach ($history as $his)
                                                            <?php
                                                            $badgeClass = match ($his->approval_type) {
                                                                'approve' => 'success',
                                                                'approval_not_required' => 'success',
                                                                'draft' => 'warning',
                                                                'submitted' => 'info',
                                                                'partially_approved' => 'warning',
                                                                default => 'danger',
                                                            };
                                                            ?>
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
                                                                    <p>
                                                                        {{-- @if ($his) <a href="#"><i data-feather="download"></i></a> @endif --}}
                                                                        {{ $his->remarks }}
                                                                    </p>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="border-top mt-2 pt-2 mb-1">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="newheader ">
                                                                <h4 class="card-title text-theme">Payment Detail</h4>
                                                                <p class="card-text">Fill the details</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 text-sm-end">
                                                            <a href="#"
                                                                class="btn btn-sm btn-outline-primary add-row">
                                                                <i data-feather="plus"></i> Add New</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="table-responsive pomrnheadtffotsticky">
                                                    <table
                                                        class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad">
                                                        <thead>
                                                            <tr>
                                                                <th width="50px">#</th>
                                                                <th width="300px">Party Code</th>
                                                                <th width="300px">Party Name</th>
                                                                <th width="300px">Reference</th>
                                                                <th width="200px" class="text-end">Amount (<span
                                                                        id="selectedCurrencyName">{{ $data->currencyCode }}</span>)
                                                                </th>
                                                                <th width="200px" class="text-end">Amount (<span
                                                                        id="orgCurrencyName"></span>)</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="mrntableselectexcel">
                                                            @foreach ($data->details as $index => $item)
                                                                @php
                                                                    $no = $index + 1;
                                                                @endphp
                                                                <tr class="approvlevelflow" id="{{ $no }}">
                                                                    <td>{{ $no }}</td>
                                                                    <td class="poprod-decpt">
                                                                        <input type="text" placeholder="Select"
                                                                            class="form-control mw-100 ledgerselect mb-25"
                                                                            required data-id="{{ $no }}"
                                                                            value="{{ $item->party_type == 'App\Models\Vendor' ? $item->party->vendor_code : $item->party->customer_code }}" />
                                                                        <input type="hidden" name="party_id[]"
                                                                            type="hidden"
                                                                            id="party_id{{ $no }}"
                                                                            class="ledgers"
                                                                            value="{{ $item->party_id }}" />
                                                                    </td>
                                                                    <td class="poprod-decpt"><input type="text"
                                                                            disabled placeholder="Select"
                                                                            class="form-control mw-100 mb-25 partyName"
                                                                            id="party_name{{ $no }}"
                                                                            value="{{ $item->party->display_name }}" />
                                                                    </td>
                                                                    <td>
                                                                        <div class="position-relative d-flex align-items-center">
                                                                            <select class="form-select mw-100 invoiceDrop drop{{ $no }}" data-id="{{ $no }}" name="reference[]">
                                                                                {{-- <option value="">Select</option> --}}
                                                                                <option @if($item->reference=="Invoice") selected @endif>Invoice</option>
                                                                                <option @if($item->reference=="Advance") selected @endif>Advance</option>
                                                                                <option @if($item->reference=="On Account") selected @endif>On Account</option>
                                                                            </select>
                                                                            <div class="ms-50 flex-shrink-0">
                                                                                <button type="button" class="btn p-25 btn-sm btn-outline-secondary invoice{{ $no }}" style="font-size: 10px" onclick="openInvoice({{ $no }})" @if($item->reference!="Invoice") disabled @endif>Invoice</button>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><input type="text" min="0"
                                                                            class="form-control mw-100 text-end amount"
                                                                            name="amount[]"
                                                                            id="excAmount{{ $no }}"
                                                                            value="{{ $item->currentAmount }}" required />
                                                                    </td>
                                                                    <td><input type="text" readonly
                                                                            class="form-control mw-100 text-end amount_exc excAmount{{ $no }}"
                                                                            name="amount_exc[]"
                                                                            value="{{ $item->orgAmount }}" required />
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="totalsubheadpodetail">
                                                                <td colspan="4" class="text-end">Total</td>
                                                                <td class="text-end currentCurrencySum">0</td>
                                                                <td class="text-end orgCurrencySum">0</td>
                                                                <td></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-md-4 mb-1">
                                                        <label class="form-label">Document</label>
                                                        <input type="file" class="form-control" name="document" />
                                                        @if ($data->document)
                                                            <a href="{{ asset('voucherPaymentDocuments') . '/' . $data->document }}"
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
            <div class="modal fade text-start show" id="postvoucher" tabindex="-1" aria-labelledby="postVoucherModal"
                aria-modal="true" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="postVoucherModal">
                                    Voucher
                                    Details</h4>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label class="form-label">Series <span class="text-danger">*</span></label>
                                        <input id = "voucher_book_code" class="form-control" disabled="">
                                        <input type="hidden" class="form-control" name="data" id="ldata">
                                        <input type="hidden" class="form-control" name="doc" id="doc">
                                        <input type="hidden" class="form-control" name="loan_data" id="loan_data">
                                        <input type="hidden" class="form-control" name="remakrs" id="remakrs">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label class="form-label">Voucher No <span class="text-danger">*</span></label>
                                        <input id = "voucher_doc_no" class="form-control" disabled="" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label class="form-label">Voucher Date <span class="text-danger">*</span></label>
                                        <input id = "voucher_date" class="form-control" disabled="" value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-1">
                                        <label class="form-label">Currency <span class="text-danger">*</span></label>
                                        <input id = "voucher_currency" class="form-control" disabled=""
                                            value="">
                                    </div>
                                </div>

                                <div class="col-md-12">


                                    <div class="table-responsive">
                                        <table
                                            class="mt-1 table table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Group</th>
                                                    <th>Leadger Code</th>
                                                    <th>Leadger Name</th>
                                                    <th class="text-end">Debit</th>
                                                    <th class="text-end">Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody id = "posting-table">


                                            </tbody>


                                        </table>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="modal-footer text-end">
                            <button onclick = "postVoucher(this);" id = "posting_button" type = "button"
                                class="btn btn-primary btn-sm waves-effect waves-float waves-light"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="feather feather-check-circle">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg> Submit</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="shareProjectTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form class="ajax-input-form" method="POST" action="{{ route('approvePaymentVoucher') }}"
                            data-redirect="{{ $indexUrl }}" enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name="action_type" id="action_type">
                            <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="modal-header">
                                <div>
                                    <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="myModalLabel17">
                                        Approve Voucher</h4>
                                    <p class="mb-0 fw-bold voucehrinvocetxt mt-0">
                                        {{ Carbon\Carbon::now()->format('d-m-Y') }}</p>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
    <div class="modal fade text-start" id="invoice" tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="myModalLabel17">Select Pending Invoices</h4>
                        <p class="mb-0">Settled Amount from the below list</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div class="row">

                         <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="voucherDate" max="{{ date('Y-m-d') }}"/>
                            </div>
                        </div>

                         <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Book Code <span class="text-danger">*</span></label>
                                 <select class="form-select select2" id="book_code">
                                    <option value="">Select Book Code</option>
                                    @foreach ($books as $book)
                                        <option>{{ $book->book_code }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                         <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Document No. <span class="text-danger">*</span></label>
                                <input type="text" id="document_no" class="form-control" />
                            </div>
                        </div>

                         <div class="col-md-3  mb-1">
                              <label class="form-label">&nbsp;</label><br/>
                             <button type="button" class="btn btn-warning btn-sm" onclick="getLedgers()"><i data-feather="search"></i> Search</button>
                         </div>
                         <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="mt-1 table myrequesttablecbox table-striped po-order-detail">
                                    <thead>
                                         <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Book Code</th>
                                            <th>Document No.</th>
                                            <th class="text-end">Amount</th>
                                            <th class="text-end">Balance</th>
                                            <th class="text-end" width="150px">Settle Amt</th>
                                             <th class="text-center">
                                                 <div class="form-check form-check-inline me-0">
                                                    <input class="form-check-input" type="checkbox" name="podetail" id="inlineCheckbox1">
                                                </div>
                                             </th>
                                          </tr>
                                        </thead>
                                        <tbody id="vouchersBody">
                                       </tbody>
                                       <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-end">Total</td>
                                                <td class="fw-bolder text-dark text-end settleTotal">0</td>
                                                <td></td>
                                            </tr>
                                       </tfoot>
                                </table>
                            </div>
                        </div>
                     </div>
                </div>
                <div class="modal-footer text-end">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i data-feather="x-circle"></i> Cancel</button>
                    <button class="btn btn-primary btn-sm" data-bs-dismiss="modal" type="button" onclick="setAmount()"><i data-feather="check-circle"></i> Process</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="currentParty">
    <input type="hidden" id="currentRow">
    <input type="hidden" id="LedgerId">

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
    <script>

        function onPostVoucherOpen(type = "not_posted") {
            resetPostVoucher();

            const apiURL = "{{ route('paymentVouchers.getPostingDetails') }}";
            const remarks = $("#remarks").val();
            $.ajax({
                url: apiURL + "?book_id=" + "{{ $data->book_id }}" + "&document_id=" + "{{ $data->id }}" +
                    "&remarks=" + remarks + "&type={{ $data->document_type }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (!data.data.status) {
                        Swal.fire({
                            title: 'Error!',
                            text: data.data.message,
                            icon: 'error',
                        });
                        return;
                    }
                    const voucherEntries = data.data.data;
                    var voucherEntriesHTML = ``;
                    Object.keys(voucherEntries.ledgers).forEach((voucher) => {
                        voucherEntries.ledgers[voucher].forEach((voucherDetail, index) => {
                            voucherEntriesHTML += `
                    <tr>
                    <td>${voucher}</td>
                    <td class="fw-bolder text-dark">${voucherDetail.ledger_group_code ? voucherDetail.ledger_group_code : ''}</td>
                    <td>${voucherDetail.ledger_code ? voucherDetail.ledger_code : ''}</td>
                    <td>${voucherDetail.ledger_name ? voucherDetail.ledger_name : ''}</td>
                    <td class="text-end">${voucherDetail.debit_amount > 0 ? parseFloat(voucherDetail.debit_amount).toFixed(2) : ''}</td>
                    <td class="text-end">${voucherDetail.credit_amount > 0 ? parseFloat(voucherDetail.credit_amount).toFixed(2) : ''}</td>
					</tr>
                    `
                        });
                    });
                    voucherEntriesHTML += `
            <tr>
                <td colspan="4" class="fw-bolder text-dark text-end">Total</td>
                <td class="fw-bolder text-dark text-end">${voucherEntries.total_debit.toFixed(2)}</td>
                <td class="fw-bolder text-dark text-end">${voucherEntries.total_credit.toFixed(2)}</td>
			</tr>
            `;
                    document.getElementById('posting-table').innerHTML = voucherEntriesHTML;
                    document.getElementById('voucher_doc_no').value = voucherEntries.document_number;
                    document.getElementById('voucher_date').value = moment(voucherEntries.document_date).format(
                        'D/M/Y');
                    document.getElementById('voucher_book_code').value = voucherEntries.book_code;
                    document.getElementById('voucher_currency').value = voucherEntries.currency_code;
                    if (type === "posted") {
                        document.getElementById('posting_button').style.display = 'none';
                    } else {
                        document.getElementById('posting_button').style.removeProperty('display');
                    }
                    $('#postvoucher').modal('show');
                }
            });

        }

        function resetPostVoucher() {
            document.getElementById('voucher_doc_no').value = '';
            document.getElementById('voucher_date').value = '';
            document.getElementById('voucher_book_code').value = '';
            document.getElementById('voucher_currency').value = '';
            document.getElementById('posting-table').innerHTML = '';
            document.getElementById('posting_button').style.display = 'none';
        }

        function postVoucher(element) {
            const bookId = "{{ $data->book_id }}";
            const type = "{{ $data->document_type }}"
            const documentId = "{{ $data->id }}";
            const postingApiUrl = "{{ route('paymentVouchers.post') }}";
            const remarks = $("#remarks").val();
            console.log(bookId);
            console.log(documentId);
            if (bookId && documentId) {
                $.ajax({
                    url: postingApiUrl,
                    type: "POST",
                    dataType: "json",
                    contentType: "application/json", // Specifies the request payload type
                    data: JSON.stringify({
                        // Your JSON request data here
                        book_id: bookId,
                        document_id: documentId,
                        remarks: remarks,
                        type: type,

                    }),
                    success: function(data) {
                        const response = data.data;
                        if (response.status) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                            });
                            if ("{{$data->document_type}}" === 'Receipt' || "{{$data->document_type}}" === 'receipts' )
                            location.href = '/receipts';
                            else
                                location.href = '/payments';


                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Some internal error occured',
                            icon: 'error',
                        });
                    }
                });

            }
        }
        var banks = {!! json_encode($banks) !!};
        var currencies = {!! json_encode($currencies) !!};
        var orgCurrency = {{ $orgCurrency }};
        var count = 2;
        var orgCurrencyName = '';


        function setAmount() {
            $('#excAmount' + $('#currentRow').val()).val($('.settleTotal').text());
            $('#excAmount' + $('#currentRow').val()).trigger('keyup');
            $('#invoice').modal('toggle');

            var selectedVouchers = [];
            const preSelected = $('.vouchers:checked').map(function() {
                selectedVouchers.push({
                    "party_id": $('#LedgerId').val(),
                    "voucher_id": this.value,
                    "amount": $('.settleAmount' + this.value).val()
                });
                return this.value;
            }).get();
            $('#party_vouchers' + $('#currentRow').val()).val(JSON.stringify(selectedVouchers));
        }

        $(document).on('input', '.settleInput', function(e) {
            let max = parseInt(e.target.max);
            let value = parseInt(e.target.value);

            if (value > 0) {
                $('.voucherCheck' + $(this).attr('data-id')).attr('checked', true);
            } else {
                $('.voucherCheck' + $(this).attr('data-id')).attr('checked', false);
            }

            if (value > max) {
                e.target.value = max;
            }
        });

        function openInvoice(id) {
            if ($('#party_id' + id).val() != "") {
                $('.drop' + id).val('Invoice');
                const comingParty = $('#party_id' + id).val();
                if (comingParty != $('#currentParty').val()) {
                    $('#vouchersBody').empty();
                    $("#inlineCheckbox1").attr('checked', false);
                    calculateSettle();
                    $('#voucherDate').val('');
                }
                $('#currentParty').val(comingParty);
                $('#currentRow').val(id);
                getLedgers();
                $('#invoice').modal('toggle');
            } else {
                $('.drop' + id).val('');
                alert('Select party to select invoice!!');
            }
        }

        function getLedgers() {
            $('.vouchers:not(:checked)').map(function() {
                $('#' + this.value).remove();
            }).get();
            updateVoucherNumbers();

            const preSelected = $('.vouchers:checked').map(function() {
                return this.value;
            }).get();

            var preData = [];
            const partyData = $('#party_vouchers' + $('#currentRow').val()).val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('getLedgerVouchers') }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    date: $('#voucherDate').val(),
                    '_token': '{!! csrf_token() !!}',
                    partyCode: $('.partyCode' + $('#currentRow').val()).val(),
                    book_code: $('#book_code').val(),
                    document_no: $('#document_no').val(),
                    type: $('#document_type').val()
                },
                success: function(response) {
                    if (response.data.length > 0) {
                        var html = '';
                        $.each(response.data, function(index, val) {
                            if (!preSelected.includes(val['id'].toString())) {

                                var amount = 0.00;
                                var checked = "";
                                var dataAmount = parseFloat(val['balance']).toFixed(2);
                                if (partyData != "" && partyData != undefined) {
                                    $.each(JSON.parse(partyData), function(indexP, valP) {
                                        if (valP['voucher_id'].toString() == val['id']) {
                                            amount = (parseFloat(valP['amount'])).toFixed(2);
                                            checked = "checked";
                                            dataAmount = (parseFloat(valP['amount'])).toFixed(
                                            2);
                                        }
                                    });
                                }

                                if (val['balance'] < 1 && checked == "") {
                                    console.log('hii' + val['id']);
                                } else {
                                    html += `<tr id="${val['id']}" class="voucherRows">
                                            <td>${index+1}</td>
                                            <td>${val['date']}</td>
                                            <td class="fw-bolder text-dark">${val['series']['book_code']}</td>
                                            <td>${val['voucher_no']}</td>
                                            <td class="text-end">${val['amount'].toLocaleString('en-IN')}</td>
                                            <td class="text-end">${parseFloat(val['balance']).toFixed(2).toLocaleString('en-IN')}</td>
                                            <td class="text-end">
                                                <input type="number" class="form-control mw-100 settleInput settleAmount${val['id']}" data-id="${val['id']}" value="${amount}"/>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-check-inline me-0">
                                                    <input class="form-check-input vouchers voucherCheck${val['id']}" data-id="${val['id']}" type="checkbox" ${checked} name="vouchers" value="${val['id']}" data-amount="${dataAmount}">
                                                </div>
                                            </td>
                                        </tr>`;
                                }
                            }
                        });
                        $('#LedgerId').val(response.ledgerId);
                        $('#vouchersBody').append(html);
                        updateVoucherNumbers();
                    }
                    calculateSettle();
                }
            });
        }

        function updateVoucherNumbers() {
            $('.voucherRows').each(function(index) {
                var level = index + 1;
                $(this).find('td:first-child').text(level);
            });
        }

        function calculateSettle() {
            let settleSum = 0;
            $('.vouchers:checked').map(function() {
                const value = parseFloat($('.settleAmount' + this.value).val()) || 0;
                settleSum = parseFloat(settleSum) + value;
            }).get();
            $('.settleTotal').text(parseFloat(settleSum).toFixed(2));
        }

        $(function() {
            $('#inlineCheckbox1').click(function() {
                $('.vouchers').prop('checked', this.checked);
                selectAllVouchers();
            });
            $(".revisionNumber").change(function() {
                window.location.href = "{{ route($editUrlString, $data->id) }}?revisionNumber=" +
                    $(this).val();
            });
        });

        $(document).on('click', '#amendmentSubmit', (e) => {
            let actionUrl = "{{ route('paymentVouchers.amendment', $data->id) }}";
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

        function check_amount() {
            let rowCount = document.querySelectorAll('.mrntableselectexcel tr').length;
            for (let index = 1; index <= rowCount; index++) {
                if (parseFloat($('#excAmount' + index).val()) == 0) {
                    alert('Can not save party with amount 0');
                    return false;
                }
            }

            if (parseFloat($('.currentCurrencySum').text()) == 0) {
                alert('Total amount should be greater than 0');
                return false;
            }
        }

        function selectAllVouchers() {
            $('.vouchers').each(function() {
                if (this.checked) {
                    $(".settleAmount" + this.value).val($(this).attr('data-amount'));
                } else {
                    $(".settleAmount" + this.value).val('0.00');
                }
            });
            calculateSettle();
        }

        $(document).on('change', '.invoiceDrop', function() {
            if ($(this).val() == "Invoice") {
                $('.invoice' + $(this).attr('data-id')).attr('disabled', false);
                $('#excAmount' + $(this).attr('data-id')).attr('readonly', true);
                openInvoice($(this).attr('data-id'));
            } else {
                $('.invoice' + $(this).attr('data-id')).attr('disabled', true);
                $('#excAmount' + $(this).attr('data-id')).attr('readonly', false);
                $('#party_vouchers' + $(this).attr('data-id')).val('[]');
            }
        });

        $(document).on('click', '.vouchers', function() {
            if (this.checked) {
                $(".settleAmount" + this.value).val($(this).attr('data-amount'));
            } else {
                $(".settleAmount" + this.value).val('0.00');
            }
            calculateSettle();
        });

        $(document).on('keyup keydown', '.settleInput', function() {
            let value = parseInt($(this).val());
            if (value > 0) {
                $('.voucherCheck' + $(this).attr('data-id')).prop('checked', true);
            } else {
                $('.voucherCheck' + $(this).attr('data-id')).prop('checked', false);
            }
            calculateSettle();
        });

        function setApproval() {
            document.getElementById('action_type').value = "approve";
        }

        function setReject() {
            document.getElementById('action_type').value = "reject";
        }

        $(document).ready(function() {
            if (orgCurrency != "") {
                $.each(currencies, function(key, value) {
                    if (value['id'] == orgCurrency) {
                        orgCurrencyName = value['short_name'];
                    }
                });
                $('#orgCurrencyName').text(orgCurrencyName);
            }
            if ($('#org_currency_id').val() == "")
                getExchangeRate();
            getAccounts();
            calculateTotal();
        });

        $(function() {
            $("input[name='payment_type']").click(function() {
                if ($("#Bank").is(":checked")) {
                    $(".bankfield").show();
                    $(".cashfield").hide();
                    $('.bankInput').attr('required', true);
                    $('#ledger_id').attr('required', false);
                } else {
                    $(".cashfield").show();
                    $(".bankfield").hide();
                    $('.bankInput').attr('required', false);
                    $('#ledger_id').attr('required', true);
                }
            });
        });

        $(function() {
            function initializeAutocomplete() {
                $(".ledgerselect").autocomplete({
                    source: function(request, response) {
                        // Get all pre-selected ledgers
                        var preLedgers = [];
                        $(".ledgers").each(function() {
                            if ($(this).val() != "") {
                                preLedgers.push($(this).val());
                            }
                        });

                        $.ajax({
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            url: "{{ route('getParties') }}",
                            type: "POST",
                            dataType: "json",
                            data: {
                                keyword: request.term,
                                ids: preLedgers,
                                type: $("#document_type").val(),
                                _token: "{!! csrf_token() !!}",
                            },
                            success: function(data) {
                                response(data); // Pass the data to the response callback
                            },
                            error: function() {
                                response(
                            []); // Respond with an empty array in case of error
                            },
                        });
                    },
                    minLength: 0,
                    select: function(event, ui) {
                        $(this).val(ui.item.code);

                        const id = $(this).attr("data-id");
                        $("#party_id" + id).val(ui.item.value);
                        $("#party_vouchers" + id).val("");
                        $("#excAmount" + id).val("0.00");
                        $(".drop" + id).val("");
                        $(".excAmount" + id).val("0.00");
                        $("#vouchersBody").empty();
                        $("#inlineCheckbox1").attr("checked", false);
                        calculateTotal();
                        calculateSettle();
                        $("#party_name" + id).val(ui.item.label);
                        return false;
                    },
                    change: function(event, ui) {
                        if (!ui.item) {
                            $(this).val("");
                            const id = $(this).attr("data-id");
                            $("#party_id" + id).val("");
                        }
                    },
                    focus: function() {
                        return false; // Prevents default behavior
                    },
                }).focus(function() {
                    if (this.value == "") {
                        $(this).autocomplete("search");
                    }
                    return false; // Prevents default behavior
                });
            }
            initializeAutocomplete();
            // Monitor input field for empty state
            $(".ledgerselect").on('input', function() {
                var inputValue = $(this).val();
                if (inputValue.trim() === '') {
                    const id = $(this).attr("data-id");
                    $('#party_id' + id).val('');
                }
            });

            $('.mrntableselectexcel').on('click', '.deleteRow', function(e) {
                e.preventDefault();
                let row = $(this).closest('tr');
                row.remove();
                updateLevelNumbers();
                calculateTotal();
            });

            $('.add-row').click(function(e) {
                e.preventDefault();
                let rowCount = document.querySelectorAll('.mrntableselectexcel tr').length + 1;
                let newRow = `
                    <tr class="approvlevelflow">
                        <td>${rowCount}</td>
                        <td class="poprod-decpt">
                            <input type="text" placeholder="Select" class="form-control mw-100 ledgerselect mb-25" partyCode${count} required data-id="${count}"/>
                            <input type="hidden" name="party_id[]" type="hidden" id="party_id${count}" class="ledgers"/>
                            <input type="hidden" name="party_vouchers[]" type="hidden" id="party_vouchers${count}" class="party_vouchers"/>

                            </td>
                        <td class="poprod-decpt"><input type="text" disabled placeholder="Select" class="form-control mw-100 mb-25 partyName" id="party_name${count}"/></td>
                        <td>
                            <div class="position-relative d-flex align-items-center">
                                <select class="form-select mw-100 invoiceDrop drop${count}" data-id="${count}" name="reference[]">
                                    <option value="">Select</option>
                                    <option>Invoice</option>
                                    <option>Advance</option>
                                    <option>On Account</option>
                                </select>
                                <div class="ms-50 flex-shrink-0">
                                    <button type="button" class="btn p-25 btn-sm btn-outline-secondary invoice${count}" style="font-size: 10px" onclick="openInvoice(${count})">Invoice</button>
                                </div>
                            </div>
                        </td>
                        <td><input type="text" value="0" class="form-control mw-100 text-end amount" name="amount[]" id="excAmount${count}" required/></td>
                        <td><input type="text" value="0" readonly class="form-control mw-100 text-end amount_exc excAmount${count}" name="amount_exc[]" required/></td>
                        <td><a href="#" class="text-danger deleteRow"><i data-feather="trash-2"></i></a></td>
                    </tr>`;
                $('.mrntableselectexcel').append(newRow);
                initializeAutocomplete();

                updateLevelNumbers();
                feather.replace({
                    width: 14,
                    height: 14
                });

                $('.select2').select2();
                count++;
            });

            $(document).on('keyup keydown', '.amount', function() {
                if ($('#orgExchangeRate').val() == "") {
                    alert('Select currency first!!');
                    return false;
                }
                const inVal = parseFloat($(this).val()) || 0;
                if (inVal > 0) {
                    $("." + $(this).attr('id')).val($(this).val() * $('#orgExchangeRate').val());
                }
                calculateTotal();
            });

            $('#orgExchangeRate').change(function() {
                resetCalculations();
            });

            // $('#document_type').change(function() {
            //     $('.ledgerselect').val('');
            //     $('.ledgers').val('');
            //     $('.partyName').val('');
            // });
        });

        function updateLevelNumbers() {
            $('.approvlevelflow').each(function(index) {
                var level = index + 1;
                $(this).find('td:first-child').text(level);
            });
        }

        function updateLevelNumbers() {
            $('.approvlevelflow').each(function(index) {
                var level = index + 1;
                $(this).find('td:first-child').text(level);
            });
        }

        function submitForm(status) {
            $('#status').val(status);
            $('#submitButton').click();
        }

        function getAccounts() {
            var accounts = [];
            $('#account_id').empty();
            $('#account_id').prepend('<option disabled selected value="">Select Bank Account</option>');

            const bank_id = $('#bank_id').val();
            $.each(banks, function(key, value) {
                if (value['id'] == bank_id) {
                    accounts = value['bank_details'];
                }
            });

            const preSelected = "{{ $data->account_id }}";
            $.each(accounts, function(key, value) {
                if (value['id'] == parseInt(preSelected)) {
                    $("#account_id").append("<option value ='" + value['id'] + "' selected>" + value[
                        'account_number'] + " </option>");
                } else {
                    $("#account_id").append("<option value ='" + value['id'] + "'>" + value['account_number'] +
                        " </option>");
                }
            });
        }

        function getExchangeRate() {
            if ($('#currency_id').val() != "") {
                $.each(currencies, function(key, value) {
                    if (value['id'] == $('#currency_id').val()) {
                        $('#selectedCurrencyName').text(value['short_name']);
                    }
                });
            }

            if (orgCurrency != "") {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('getExchangeRate') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        date: $('#date').val(),
                        '_token': '{!! csrf_token() !!}',
                        currency: $('#currency_id').val()
                    },
                    success: function(response) {
                        if (response.status) {

                            $('#orgExchangeRate').val(response.data.org_currency_exg_rate).trigger('change');

                            $('#org_currency_id').val(response.data.org_currency_id);
                            $('#org_currency_code').val(response.data.org_currency_code);
                            $('#org_currency_exg_rate').val(response.data.org_currency_exg_rate);

                            $('#comp_currency_id').val(response.data.comp_currency_id);
                            $('#comp_currency_code').val(response.data.comp_currency_code);
                            $('#comp_currency_exg_rate').val(response.data.comp_currency_exg_rate);

                            $('#group_currency_id').val(response.data.group_currency_id);
                            $('#group_currency_code').val(response.data.group_currency_code);
                            $('#group_currency_exg_rate').val(response.data.group_currency_exg_rate);

                            $('#base_currency_code').val(response.data.org_currency_code);
                            $('#company_currency_code').val(response.data.comp_currency_code);
                            $('#company_exchange_rate').val(response.data
                                .comp_currency_exg_rate);
                            $('#grp_currency_code').val(response.data.group_currency_code);
                            $('#grp_exchange_rate').val(response.data
                                .group_currency_exg_rate);

                        } else {
                            resetCurrencies();
                            $('#orgExchangeRate').val('');
                            alert(response.message);
                        }
                    }
                });

            } else {
                alert('Organization currency is not set!!');
            }
        }

        function resetCurrencies() {
            $('#org_currency_id').val('');
            $('#org_currency_code').val('');
            $('#org_currency_exg_rate').val('');

            $('#comp_currency_id').val('');
            $('#comp_currency_code').val('');
            $('#comp_currency_exg_rate').val('');

            $('#group_currency_id').val('');
            $('#group_currency_code').val('');
            $('#group_currency_exg_rate').val('');
        }

        function resetCalculations() {
            $('.amount').each(function() {
                if ($(this).val() != "") {
                    const inVal = parseFloat($(this).val()) || 0;
                    if (inVal > 0) {
                        $("." + $(this).attr('id')).val($(this).val() * $('#orgExchangeRate').val());
                    }
                }
            });
            calculateTotal();
        }

        function calculateTotal() {
            let currentCurrencySum = 0;
            $('.amount').each(function() {
                const value = parseFloat($(this).val()) || 0;
                currentCurrencySum = parseFloat(parseFloat(currentCurrencySum + value).toFixed(2));
            });
            $('.currentCurrencySum').text(currentCurrencySum);

            let orgCurrencySum = 0;
            $('.amount_exc').each(function() {
                const value = parseFloat($(this).val()) || 0;
                orgCurrencySum = parseFloat(parseFloat(orgCurrencySum + value).toFixed(2));
            });
            $('.orgCurrencySum').text(orgCurrencySum);
            $('#totalAmount').val(orgCurrencySum);
        }

        function get_voucher_details() {
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
    <script>

        function onPostVoucherOpen(type = "not_posted") {
            resetPostVoucher();

            const apiURL = "{{ route('paymentVouchers.getPostingDetails') }}";
            const remarks = $("#remarks").val();
            $.ajax({
                url: apiURL + "?book_id=" + "{{ $data->book_id }}" + "&document_id=" + "{{ $data->id }}" +
                    "&remarks=" + remarks + "&type={{ $data->document_type }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (!data.data.status) {
                        Swal.fire({
                            title: 'Error!',
                            text: data.data.message,
                            icon: 'error',
                        });
                        return;
                    }
                    const voucherEntries = data.data.data;
                    var voucherEntriesHTML = ``;
                    Object.keys(voucherEntries.ledgers).forEach((voucher) => {
                        voucherEntries.ledgers[voucher].forEach((voucherDetail, index) => {
                            voucherEntriesHTML += `
                    <tr>
                    <td>${voucher}</td>
                    <td class="fw-bolder text-dark">${voucherDetail.ledger_group_code ? voucherDetail.ledger_group_code : ''}</td>
                    <td>${voucherDetail.ledger_code ? voucherDetail.ledger_code : ''}</td>
                    <td>${voucherDetail.ledger_name ? voucherDetail.ledger_name : ''}</td>
                    <td class="text-end">${voucherDetail.debit_amount > 0 ? parseFloat(voucherDetail.debit_amount).toFixed(2) : ''}</td>
                    <td class="text-end">${voucherDetail.credit_amount > 0 ? parseFloat(voucherDetail.credit_amount).toFixed(2) : ''}</td>
					</tr>
                    `
                        });
                    });
                    voucherEntriesHTML += `
            <tr>
                <td colspan="4" class="fw-bolder text-dark text-end">Total</td>
                <td class="fw-bolder text-dark text-end">${voucherEntries.total_debit.toFixed(2)}</td>
                <td class="fw-bolder text-dark text-end">${voucherEntries.total_credit.toFixed(2)}</td>
			</tr>
            `;
                    document.getElementById('posting-table').innerHTML = voucherEntriesHTML;
                    document.getElementById('voucher_doc_no').value = voucherEntries.document_number;
                    document.getElementById('voucher_date').value = moment(voucherEntries.document_date).format(
                        'D/M/Y');
                    document.getElementById('voucher_book_code').value = voucherEntries.book_code;
                    document.getElementById('voucher_currency').value = voucherEntries.currency_code;
                    if (type === "posted") {
                        document.getElementById('posting_button').style.display = 'none';
                    } else {
                        document.getElementById('posting_button').style.removeProperty('display');
                    }
                    $('#postvoucher').modal('show');
                }
            });

        }

        function resetPostVoucher() {
            document.getElementById('voucher_doc_no').value = '';
            document.getElementById('voucher_date').value = '';
            document.getElementById('voucher_book_code').value = '';
            document.getElementById('voucher_currency').value = '';
            document.getElementById('posting-table').innerHTML = '';
            document.getElementById('posting_button').style.display = 'none';
        }

        function postVoucher(element) {
            const bookId = "{{ $data->book_id }}";
            const type = "{{ $data->document_type }}"
            const documentId = "{{ $data->id }}";
            const postingApiUrl = "{{ route('paymentVouchers.post') }}";
            const remarks = $("#remarks").val();
            console.log(bookId);
            console.log(documentId);
            if (bookId && documentId) {
                $.ajax({
                    url: postingApiUrl,
                    type: "POST",
                    dataType: "json",
                    contentType: "application/json", // Specifies the request payload type
                    data: JSON.stringify({
                        // Your JSON request data here
                        book_id: bookId,
                        document_id: documentId,
                        remarks: remarks,
                        type: type,

                    }),
                    success: function(data) {
                        const response = data.data;
                        if (response.status) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                            });
                            if ("{{$data->document_type}}" === 'Receipt' || "{{$data->document_type}}" === 'receipts' )
                                location.href = '/receipt-vouchers';
                            else
                                location.href = '/payment-vouchers';


                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Some internal error occured',
                            icon: 'error',
                        });
                    }
                });

            }
        }

    </script>
@endsection
