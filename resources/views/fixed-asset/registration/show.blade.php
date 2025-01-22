@extends('layouts.app')
@section('css')
    <style type="text/css">
        .image-uplodasection {
            position: relative;
            margin-bottom: 10px;
        }

        .fileuploadicon {
            font-size: 24px;
        }



        .delete-img {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }

        .preview-image {
            max-width: 100px;
            max-height: 100px;
            display: block;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
                <div class="row">
                    <div class="content-header-left col-md-6 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Asset</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active">View Details</li>


                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right text-end col-md-6 col-6 mb-2 mb-sm-0">
                        <div class="form-group breadcrumb-right">
                            <a href="{{ route('finance.fixed-asset.registration.index') }}"> <button
                                    class="btn btn-secondary btn-sm"><i data-feather="arrow-left-circle"></i> Back</button>
                            </a>
                             </div>
                    </div>
                </div>
            </div>
            <div class="content-body">



                <section id="basic-datatable">
                    <div class="row">
                        <form id="fixed-asset-registration-form"
                            enctype="multipart/form-data">


                            <input type="hidden" name="document_status" id="document_status" value="">
                            <input type="hidden" name="mrn_detail_id" id="mrn_detail_id" value="{{$data->mrn_detail_id}}">
                            <input type="hidden" name="mrn_header_id" id="mrn_header_id" value="{{$data->mrn_header_id}}">
                            <input type="hidden" name="page" value="edit">


                            <div class="col-12">

                                <div class="card">
                                    <div class="card-body customernewsection-form">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="newheader border-bottom mb-2 pb-25  ">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h4 class="card-title text-theme">Basic Information</h4>
                                                            <p class="card-text">Fill the details</p>
                                                        </div>




                                                    </div>
                                                </div>

                                            </div>




                                            <div class="col-md-8">
                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="book_id">Series <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <select class="form-select" name="book_id" id="book_id" disabled required>
                                                            @if ($series)
                                                                @foreach ($series as $index => $ser)
                                                                    <option value="{{ $ser->id }}"
                                                                        {{ $data->book_id == $ser->id ? 'selected' : '' }}>
                                                                        {{ $ser->book_code }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="document_number">Doc No <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="document_number"
                                                            id="document_number" value="{{ $data->document_number}}"
                                                            disabled required>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="document_date">Doc Date <span
                                                                class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="date" class="form-control" name="document_date"
                                                            id="document_date" disabled
                                                            value="{{ $data->document_date ?? date('Y-m-d') }}" required>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="reference_no">Reference No.</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control" name="reference_no"
                                                            id="reference_no" value="{{ $data->reference_no }}">
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="reference_from">Reference From
                                                            <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-3 action-button">
                                                        <a data-bs-toggle="modal" data-bs-target="#rescdule"
                                                            class="btn btn-outline-primary btn-sm mb-0 w-100"><i
                                                                data-feather="plus-square"></i> GRN</a>
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-2">
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="status">Status</label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="demo-inline-spacing">
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio" id="customColorRadio3"
                                                                    name="status" class="form-check-input"
                                                                    value="active"
                                                                    {{ $data->status == 'active' ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bolder"
                                                                    for="customColorRadio3">Active</label>
                                                            </div>
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio" id="customColorRadio4"
                                                                    name="status" class="form-check-input"
                                                                    value="inactive"
                                                                    {{ $data->status == 'inactive' ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bolder"
                                                                    for="customColorRadio4">Inactive</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                    </div>
                                </div>




                                <div class="row customernewsection-form">
                                    <div class="col-md-12">
                                        <div class="card quation-card">
                                            <div class="card-header newheader">
                                                <div>
                                                    <h4 class="card-title">Asset Details</h4>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Category <span
                                                                    class="text-danger">*</span></label>
                                                                    <select class="form-select select2" name="category_id" id="category" required>
                                                                        <option value="" {{ $data->category_id ? '' : 'selected' }}>Select</option>
                                                                        @foreach($categories as $category)
                                                                            <option value="{{ $category->id }}" {{ $data->category_id == $category->id ? 'selected' : '' }}>
                                                                                {{ $category->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Asset Name <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="asset_name"
                                                                id="asset_name"
                                                                value="{{ $data->asset_name }}" required />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Asset Code <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="asset_code"
                                                                id="asset_code" value="{{ $data->asset_code }}"
                                                                required />
                                                        </div>
                                                    </div>


                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Quantity <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="quantity"
                                                                id="quantity" value="{{ $data->quantity }}"
                                                                readonly />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Ledger <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-select select2" name="ledger_id"
                                                                id="ledger" required>
                                                                <option value=""
                                                                    {{ $data->ledger_id ? '' : 'selected' }}>Select</option>
                                                                @foreach ($ledgers as $ledger)
                                                                    <option value="{{ $ledger->id }}"
                                                                        {{ $data->ledger_id === $ledger->id ? 'selected' : '' }}>
                                                                        {{ $ledger->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Ledger Group <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-select select2" name="ledger_group_id"
                                                                id="ledger_group" required>
                                                                </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Capitalize Date <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" class="form-control"
                                                                name="capitalize_date" id="capitalize_date"
                                                                value="{{ $data->capitalize_date }}" required />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Maint. Schedule <span
                                                                    class="text-danger">*</span></label>
                                                            <select class="form-select" name="maintenance_schedule"
                                                                id="maintenance_schedule" required>
                                                                <option value=""
                                                                    {{ $data->maintenance_schedule ? '' : 'selected' }}>
                                                                    Select</option>
                                                                <option value="Yearly"
                                                                    {{ $data->maintenance_schedule == 'Yearly' ? 'selected' : '' }}>
                                                                    Yearly</option>
                                                                <option value="Monthly"
                                                                    {{ $data->maintenance_schedule == 'Monthly' ? 'selected' : '' }}>
                                                                    Monthly</option>
                                                                <option value="Weekly"
                                                                    {{ $data->maintenance_schedule == 'Weekly' ? 'selected' : '' }}>
                                                                    Weekly</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Est. Useful Life (yrs) <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="useful_life"
                                                                id="useful_life" value="{{ $data->useful_life }}"
                                                                required />
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Salvage Value <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="salvage_value" id="salvage_value"
                                                                value="{{ $data->salvage_value }}" required />
                                                        </div>
                                                    </div>


                                                    <div class="col-md-3" hidden>
                                                        <div class="mb-1">
                                                            <label class="form-label">Current Value <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="current_value" id="current_value"
                                                                value="{{ $data->current_value}}" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row customernewsection-form">
                                    <div class="col-md-12">
                                        <div class="card quation-card">
                                            <div class="card-header newheader">
                                                <div>
                                                    <h4 class="card-title">Vendor Details</h4>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Vendor <span
                                                                    class="text-danger">*</span></label>
                                                                    <select class="form-select select2" disabled style="pointer-events: none;" id="vendor" required>
                                                                        <option value="">Select</option>
                                                                        @foreach ($vendors as $vendor)
                                                                            <option value="{{ $vendor->id }}" {{ $data->vendor_id ? 'selected' : '' }}>
                                                                                {{ $vendor->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <input type="hidden" name="vendor_id" id="vendor_id" value="{{$data->vendor_id}}">

                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Currency <span
                                                                    class="text-danger">*</span></label>
                                                                    <select class="form-select" name="currency_id" id="currency" disabled required>
                                                                        <option value="">Select</option>
                                                                        @foreach ($currencies as $currency)
                                                                            <option value="{{ $currency->id }}" {{ $data->currency_id ? 'selected' : '' }}>
                                                                                {{ $currency->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Supplier Invoice No. <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="supplier_invoice_no" id="supplier_invoice_no"
                                                                value="{{$data->supplier_invoice_no}}" required readonly />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Supplier Invoice Date <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" class="form-control"
                                                                name="supplier_invoice_date"  id="supplier_invoice_date" value="{{$data->supplier_invoice_date}}" required readonly />

                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Sub Total <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="sub_total"
                                                                id="sub_total" value="{{$data->sub_total}}" required readonly />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label w-100">Tax <span
                                                                    class="text-danger">*</span>
                                                                <a href="#taxdetail" class="float-end"
                                                                    data-bs-toggle="modal">
                                                                    <i data-feather="info"></i>
                                                                </a>
                                                            </label>
                                                            <input type="text" class="form-control" name="tax"
                                                                id="tax" value="{{$data->tax}}" required readonly />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Purchase Amt <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="purchase_amount" id="purchase_amount"
                                                                value="{{$data->purchase_amount}}" required readonly />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-1">
                                                            <label class="form-label">Book Date <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="book_date"
                                                                id="book_date" value="{{$data->book_date}}" required readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </form>
                    </div>
                    <!-- Modal to add new record -->

                </section>


            </div>
        </div>
    </div> <!-- END: Content-->

    <div class="modal fade text-start alertbackdropreadonly" id="amendmentconfirm" tabindex="-1"
        aria-labelledby="myModalLabel1" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body alertmsg text-center warning">
                    <i data-feather='alert-circle'></i>
                    <h2>Are you sure?</h2>
                    <p>Are you sure you want to <strong>Amendment</strong> this <strong>MRN</strong>? After Amendment this
                        action cannot be undone.</p>
                    <button type="button" class="btn btn-secondary me-25" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade text-start" id="rescdule" tabindex="-1" aria-labelledby="myModalLabel17"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="myModalLabel17">Select Item
                        </h4>
                        <p class="mb-0">Select from the below list</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col">
                            <div class="mb-1">
                                <label class="form-label">GRN No. <span class="text-danger">*</span></label>
                                <select class="form-select filter" name="grn_no" id="grn_no" >
                                    <option value="">Select</option>
                                    @foreach ($grns->unique('document_number') as $grn)
                                        <option value="{{ $grn->document_number }}">{{ $grn->document_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Code <span class="text-danger">*</span></label>
                                <select class="form-select filter" name="vendor_code" id="vendor_code">
                                    <option value="">Select</option>
                                    @foreach ($grns->unique('vendor_code') as $grn)
                                    <option value="{{ $grn->vendor_code }}">{{ $grn->vendor_code }}</option>
                                @endforeach
                           </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Vendor Name <span class="text-danger">*</span></label>
                                <select class="form-select filter" id="vendor_name" name="vendor_name">
                                    <option value="">Select</option>
                                    @foreach ($grns->unique('vendor_id') as $grn)
                                    <option value="{{ $grn->vendor->display_name }}">{{ $grn->vendor->display_name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col">
                            <div class="mb-1">
                                <label class="form-label">Item Name <span class="text-danger">*</span></label>
                                <select class="form-select filter" id="item_name" name="item_name">
                                    <option value="">Select</option>
                                    @foreach ($grn_details->unique('item_id') as $item)
                                    <option value="{{ $item->item->item_id }}">{{ $item->item->item_name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col  mb-1">
                            <label class="form-label">&nbsp;</label><br />
                            <button class="btn btn-warning btn-sm" id="searchButton"><i data-feather="search"></i> Search</button>
                        </div>

                        <div class="col-md-12">


                            <div class="table-responsive">
                                <table id="grn_table" class="mt-1 table myrequesttablecbox table-striped po-order-detail">
                                    <thead>
                                        <tr>
                                            <th>
                                            </th>
                                            <th>GRN No.</th>
                                            <th>GRN Date</th>
                                            <th>Vendor Code</th>
                                            <th>Vendor Name</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($grn_details as $grn)
                                        @isset($grn->header->vendor->display_name)
                                        <tr>

                                                <td>
                                                    <div class="form-check form-check-inline me-0">
                                                        <input class="form-check-input" type="radio" name="grn_id" id="grn_{{ $loop->index }}" value="{{ $grn->id }}" @if($grn->id ==$data->mrn_detail_id) checked @endif data-grn="{{json_encode($grn)}}">
                                                    </div>
                                                </td>

                                            <td>{{$grn->header->document_number}}</td>
                                            <td>{{ $grn->header->created_at->format('d-m-Y') }}</td>
                                            <td class="fw-bolder text-dark">{{$grn->header->vendor_code}}</td>
                                            <td>{{$grn->header->vendor->display_name}}</td>
                                            <td>{{$grn->item->item_name}}</td>
                                            <td>{{$grn->accepted_qty}}</td>
                                        </tr>
                                        @endisset
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No data available</td>
                                        </tr>
                                        @endforelse

                                    </tbody>


                                </table>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i
                            data-feather="x-circle"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade text-start" id="postvoucher" tabindex="-1" aria-labelledby="myModalLabel17"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="myModalLabel17">Post Voucher
                        </h4>
                        <p class="mb-0">View Details</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Series <span class="text-danger">*</span></label>
                                <input class="form-control" readonly value="VOUCH/2024" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-1">
                                <label class="form-label">Voucher No <span class="text-danger">*</span></label>
                                <input class="form-control" readonly value="098" />
                            </div>
                        </div>

                        <div class="col-md-12">


                            <div class="table-responsive">
                                <table
                                    class="mt-1 table table-striped po-order-detail custnewpo-detail border newdesignerptable newdesignpomrnpad">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Leadger Code</th>
                                            <th>Leadger Name</th>
                                            <th class="text-end">Debit</th>
                                            <th class="text-end">Credit</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="fw-bolder text-dark">2901</td>
                                            <td>Finance</td>
                                            <td class="text-end">10000</td>
                                            <td class="text-end">0</td>
                                            <td>Remarks come here...</td>
                                        </tr>

                                        <tr>
                                            <td>2</td>
                                            <td class="fw-bolder text-dark">2901</td>
                                            <td>Finance</td>
                                            <td class="text-end">0</td>
                                            <td class="text-end">10000</td>
                                            <td>Remarks come here...</td>
                                        </tr>

                                        <tr>
                                            <td colspan="3" class="fw-bolder text-dark text-end">Total</td>
                                            <td class="fw-bolder text-dark text-end">10000</td>
                                            <td class="fw-bolder text-dark text-end">10000</td>
                                            <td></td>
                                        </tr>





                                    </tbody>


                                </table>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i
                            data-feather="x-circle"></i> Cancel</button>
                    <button class="btn btn-primary btn-sm" data-bs-dismiss="modal"><i data-feather="check-circle"></i>
                        Submit</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="discount" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Add Discount</h1>
                    <p class="text-center">Enter the details below.</p>


                    <div class="text-end"><a href="#" class="text-primary add-contactpeontxt mt-50"><i
                                data-feather='plus'></i> Add Discount</a></div>

                    <div class="table-responsive-md customernewsection-form">
                        <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="150px">Discount Name</th>
                                    <th>Discount Type</th>
                                    <th>Discount %</th>
                                    <th>Discount Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#</td>
                                    <td>
                                        <select class="form-select mw-100">
                                            <option>Select</option>
                                            <option>Discount 1</option>
                                            <option>Discount 2</option>
                                            <option>Discount 3</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select mw-100">
                                            <option>Select</option>
                                            <option>Fixed</option>
                                            <option>Percentage</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control mw-100" /></td>
                                    <td><input type="text" class="form-control mw-100" /></td>
                                    <td>
                                        <a href="#" class="text-danger"><i data-feather="trash-2"></i></a>
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-dark"><strong>Total</strong></td>
                                    <td class="text-dark"><strong>1000</strong></td>
                                    <td></td>
                                </tr>


                            </tbody>


                        </table>
                    </div>

                </div>

                <div class="modal-footer justify-content-center">
                    <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
                    <button type="reset" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-address" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Edit Address</h1>
                    <p class="text-center">Enter the details below.</p>


                    <div class="row mt-2">
                        <div class="col-md-12 mb-1">
                            <label class="form-label">Select Address <span class="text-danger">*</span></label>
                            <select class="select2 form-select">
                                <option value="AK" selected>56, Sector 44 Rd Gurugram, Haryana, Pin Code - 122022,
                                    India</option>
                                <option value="HI">Noida, U.P</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-1">
                            <label class="form-label">Country <span class="text-danger">*</span></label>
                            <select class="select2 form-select">
                                <option>Select</option>
                                <option>India</option>
                            </select>
                        </div>


                        <div class="col-md-6 mb-1">
                            <label class="form-label">State <span class="text-danger">*</span></label>
                            <select class="select2 form-select">
                                <option>Select</option>
                                <option>Gautam Budh Nagar</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-1">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <select class="select2 form-select">
                                <option>Select</option>
                                <option>Noida</option>
                            </select>
                        </div>


                        <div class="col-md-6 mb-1">
                            <label class="form-label w-100">Pincode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="201301" placeholder="Enter Pincode" />
                        </div>

                        <div class="col-md-12 mb-1">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" placeholder="Enter Address">56, Sector 44 Rd, Kanhai Colony, Sector 52</textarea>
                        </div>

                    </div>



                </div>

                <div class="modal-footer justify-content-center">
                    <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
                    <button type="reset" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Remarks" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Add/Edit Remarks</h1>
                    <p class="text-center">Enter the details below.</p>


                    <div class="row mt-2">


                        <div class="col-md-12 mb-1">
                            <label class="form-label">Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" placeholder="Enter Remarks"></textarea>
                        </div>

                    </div>



                </div>

                <div class="modal-footer justify-content-center">
                    <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
                    <button type="reset" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="expenses" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Add Expenses</h1>
                    <p class="text-center">Enter the details below.</p>

                    <div class="text-end"> <a href="#" class="text-primary add-contactpeontxt mt-50"><i
                                data-feather='plus'></i> Add Expenses</a></div>

                    <div class="table-responsive-md customernewsection-form">
                        <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th width="150px">Expense Name</th>
                                    <th>Expense Type</th>
                                    <th>Expense %</th>
                                    <th>Expense Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#</td>
                                    <td>
                                        <select class="form-select mw-100">
                                            <option>Select</option>
                                            <option>Expense 1</option>
                                            <option>Expense 2</option>
                                            <option>Expense 3</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select mw-100">
                                            <option>Select</option>
                                            <option>Fixed</option>
                                            <option>Percentage</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control mw-100" /></td>
                                    <td><input type="text" class="form-control mw-100" /></td>
                                    <td>
                                        <a href="#" class="text-danger"><i data-feather="trash-2"></i></a>
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="3"></td>
                                    <td class="text-dark"><strong>Total</strong></td>
                                    <td class="text-dark"><strong>1000</strong></td>
                                    <td></td>
                                </tr>


                            </tbody>


                        </table>
                    </div>

                </div>

                <div class="modal-footer justify-content-center">
                    <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
                    <button type="reset" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delivery" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" style="max-width: 900px">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Store Location</h1>
                    <p class="text-center">Enter the details below.</p>


                    <div class="text-end"><a href="#" class="text-primary add-contactpeontxt mt-50"><i
                                data-feather='plus'></i> Add Quantity</a></div>

                    <div class="table-responsive-md customernewsection-form">
                        <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail">
                            <thead>
                                <tr>
                                    <th width="80px">#</th>
                                    <th>Store</th>
                                    <th>Rack</th>
                                    <th>Shelf</th>
                                    <th>Bin</th>
                                    <th width="50px">Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#</td>
                                    <td>
                                        <select class="form-select mw-100 select2">
                                            <option>Select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select mw-100 select2">
                                            <option>Select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select mw-100 select2">
                                            <option>Select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select mw-100 select2">
                                            <option>Select</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control mw-100" /></td>
                                    <td>
                                        <a href="#" class="text-danger"><i data-feather="trash-2"></i></a>
                                    </td>
                                </tr>


                                <tr>
                                    <td colspan="4"></td>
                                    <td class="text-dark"><strong>Total Qty</strong></td>
                                    <td class="text-dark"><strong>20</strong></td>
                                    <td></td>
                                </tr>


                            </tbody>


                        </table>
                    </div>

                </div>

                <div class="modal-footer justify-content-center">
                    <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
                    <button type="reset" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="taxdetail" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" style="max-width: 700px">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Taxes</h1>
                    <div class="table-responsive-md customernewsection-form">
                        <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail"
                            id = "order_tax_main_table">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th width="150px">Tax</th>
                                    <th>Taxable Amount</th>
                                    <th>Tax %</th>
                                    <th>Tax Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>IGST</td>
                                    <td class="sub_total">{{$data->mrnDetail->net_value??0}}</td>
                                    <td id="igst_per">{{$data->mrnDetail->igst_percentage??0}}</td>
                                    <td id="igst_tax">{{(($data->mrnDetail->net_value??0)*($data->mrnDetail->igst_percentage??0))??0}}</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>CGST</td>
                                    <td class="sub_total">{{$data->mrnDetail->net_value??0}}</td>
                                    <td id="cgst_per">{{$data->mrnDetail->cgst_percentage??0}}</td>
                                    <td id="cgst_tax">{{(($data->mrnDetail->net_value??0)*($data->mrnDetail->cgst_percentage??0))??0}}</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>SGST</td>
                                    <td class="sub_total">{{$data->mrnDetail->net_value??0}}</td>
                                    <td id="sgst_per">{{$data->mrnDetail->sgst_percentage??0}}</td>
                                    <td id="sgst_tax">{{(($data->mrnDetail->net_value??0)*($data->mrnDetail->sgst_percentage??0))??0}}</td>
                                 </tr>
                             </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="attribute" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-0 bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-sm-2 mx-50 pb-2">
                    <h1 class="text-center mb-1" id="shareProjectTitle">Select Attribute</h1>
                    <p class="text-center">Enter the details below.</p>

                    <div class="table-responsive-md customernewsection-form">
                        <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail">
                            <thead>
                                <tr>
                                    <th>Attribute Name</th>
                                    <th>Attribute Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Color</td>
                                    <td>
                                        <select class="form-select select2">
                                            <option>Select</option>
                                            <option>Black</option>
                                            <option>White</option>
                                            <option>Red</option>
                                            <option>Golden</option>
                                            <option>Silver</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Size</td>
                                    <td>
                                        <select class="form-select select2">
                                            <option>Select</option>
                                            <option>5.11"</option>
                                            <option>5.10"</option>
                                            <option>5.09"</option>
                                            <option>5.00"</option>
                                            <option>6.20"</option>
                                        </select>
                                    </td>
                                </tr>





                            </tbody>


                        </table>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="reset" class="btn btn-outline-secondary me-1">Cancel</button>
                    <button type="reset" class="btn btn-primary">Select</button>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
    <script>
        function resetParametersDependentElements(data) {
            let backDateAllowed = false;
            let futureDateAllowed = false;

            if (data != null) {
                console.log(data.parameters.back_date_allowed);
                if (Array.isArray(data?.parameters?.back_date_allowed)) {
                    for (let i = 0; i < data.parameters.back_date_allowed.length; i++) {
                        if (data.parameters.back_date_allowed[i].trim().toLowerCase() === "yes") {
                            backDateAllowed = true;
                            break; // Exit the loop once we find "yes"
                        }
                    }
                }
                if (Array.isArray(data?.parameters?.future_date_allowed)) {
                    for (let i = 0; i < data.parameters.future_date_allowed.length; i++) {
                        if (data.parameters.future_date_allowed[i].trim().toLowerCase() === "yes") {
                            futureDateAllowed = true;
                            break; // Exit the loop once we find "yes"
                        }
                    }
                }
                //console.log(backDateAllowed, futureDateAllowed);

            }

            const dateInput = document.getElementById("document_date");

            // Determine the max and min values for the date input
            const today = moment().format("YYYY-MM-DD");

            if (backDateAllowed && futureDateAllowed) {
                dateInput.removeAttribute("min");
                dateInput.removeAttribute("max");
            } else if (backDateAllowed) {
                dateInput.setAttribute("max", today);
                dateInput.removeAttribute("min");
            } else if (futureDateAllowed) {
                dateInput.setAttribute("min", today);
                dateInput.removeAttribute("max");
            } else {
                dateInput.setAttribute("min", today);
                dateInput.setAttribute("max", today);
            }
        }

        $('#book_id').on('change', function() {
            resetParametersDependentElements(null);
            let currentDate = new Date().toISOString().split('T')[0];
            let document_date = $('#document_date').val();
            let bookId = $('#book_id').val();
            let actionUrl = '{{ route('book.get.doc_no_and_parameters') }}' + '?book_id=' + bookId +
                "&document_date=" + document_date;
            fetch(actionUrl).then(response => {
                return response.json().then(data => {
                    if (data.status == 200) {
                        resetParametersDependentElements(data.data);
                        $("#book_code_input").val(data.data.book_code);
                        if (!data.data.doc.document_number) {
                            $("#document_number").val('');
                            $('#doc_number_type').val('');
                            $('#doc_reset_pattern').val('');
                            $('#doc_prefix').val('');
                            $('#doc_suffix').val('');
                            $('#doc_no').val('');
                        } else {
                            $("#document_number").val(data.data.doc.document_number);
                            $('#doc_number_type').val(data.data.doc.type);
                            $('#doc_reset_pattern').val(data.data.doc.reset_pattern);
                            $('#doc_prefix').val(data.data.doc.prefix);
                            $('#doc_suffix').val(data.data.doc.suffix);
                            $('#doc_no').val(data.data.doc.doc_no);
                        }
                        if (data.data.doc.type == 'Manually') {
                            $("#document_number").attr('readonly', false);
                        } else {
                            $("#document_number").attr('readonly', true);
                        }

                    }
                    if (data.status == 404) {
                        $("#document_number").val('');
                        $('#doc_number_type').val('');
                        $('#doc_reset_pattern').val('');
                        $('#doc_prefix').val('');
                        $('#doc_suffix').val('');
                        $('#doc_no').val('');
                        alert(data.message);
                    }
                });
            });
        });
        //$('#book_id').trigger('change');





        $(".mrntableselectexcel tr").click(function() {
            $(this).addClass('trselected').siblings().removeClass('trselected');
            value = $(this).find('td:first').html();
        });

        $('#ledger').change(function() {

            let groupDropdown = $('#ledger_group');
            $.ajax({
                url: '{{ route('finance.fixed-asset.getLedgerGroups') }}',
                method: 'GET',
                data: {
                    ledger_id: $(this).val(),
                    _token: $('meta[name="csrf-token"]').attr(
                        'content') // CSRF token
                },
                success: function(response) {
                    groupDropdown.empty(); // Clear previous options

                    response.forEach(item => {
                        let selected = ({{ $data->ledger_group_id ?? 'null' }} === item.id) ? 'selected' : '';
                        groupDropdown.append(`<option value="${item.id}" ${selected}>${item.name}</option>`);

                    });

                },
                error: function() {
                    alert('Error fetching group items.');
                }
            });

        });
        $('#ledger').val("{{$data->ledger_id}}").trigger('change');

        document.addEventListener('DOMContentLoaded', function () {
    const searchButton = document.querySelector('#searchButton');
    const table = document.querySelector('#grn_table tbody');

    searchButton.addEventListener('click', function (event) {
        event.preventDefault(); // Prevent form submission if within a form

        // Get selected values or default to an empty string
        const grnNo = document.querySelector('#grn_no').value.toLowerCase() || "";
        const vendorCode = document.querySelector('#vendor_code').value.toLowerCase() || "";
        const vendorName = document.querySelector('#vendor_name').value.toLowerCase() || "";
        const itemName = document.querySelector('#item_name').value.toLowerCase() || "";

        // Check if all filters are empty
        const allFiltersEmpty = !grnNo && !vendorCode && !vendorName && !itemName;

        Array.from(table.rows).forEach(row => {
            const radioButton = row.querySelector('input[type="radio"]');
            if (allFiltersEmpty) {
                // If no filters are selected, display all rows
                row.style.display = (radioButton && radioButton.checked) ? '' : 'none';
            } else {
                // Destructure table cells
                const [ , grnCell, , vendorCodeCell, vendorNameCell, itemCell ] = row.cells;

                // Apply filters only if a value is selected, otherwise skip
                const matchGRN = grnNo ? grnCell.textContent.toLowerCase().includes(grnNo) : true;
                const matchVendorCode = vendorCode ? vendorCodeCell.textContent.toLowerCase().includes(vendorCode) : true;
                const matchVendorName = vendorName ? vendorNameCell.textContent.toLowerCase().includes(vendorName) : true;
                const matchItemName = itemName ? itemCell.textContent.toLowerCase().includes(itemName) : true;

                // Show the row only if all selected filters match
                row.style.display = (matchGRN && matchVendorCode && matchVendorName && matchItemName) ? '' : 'none';
            }
        });

    });
    $('#searchButton').trigger('click');
});
document.addEventListener('DOMContentLoaded', function () {
    const processButton = document.querySelector('#submit_grns');
    const radioButtons = document.querySelectorAll('input[name="grn_id"]');

    processButton.addEventListener('click', function (event) {
        // Check if any radio button is selected
        const selectedRadio = Array.from(radioButtons).find(radio => radio.checked);

        if (!selectedRadio) {
            event.preventDefault(); // Prevent further processing
            alert('Please select a GRN before proceeding.');
        } else {
            // Retrieve and log the data-grn attribute of the selected radio button
            const grnData = selectedRadio.dataset.grn; // Access the data-grn attribute

            // Make sure grnData is available
            if (grnData) {
                $('#mrn_detail_id').val(selectedRadio.value);
                const parsedGrnData = JSON.parse(grnData); // Parse the JSON data
                console.log(parsedGrnData);
                $('#mrn_header_id').val(parsedGrnData['header']['id']);
                $('#quantity').val(parsedGrnData['accepted_qty']); // Log the parsed data
                $('#vendor').val(parsedGrnData['header']['vendor']['id']).select2();
                $('#currency').val(parsedGrnData['header']['vendor']['currency_id']).select2();
                $('#vendor_id').val(parsedGrnData['header']['vendor']['id']);
                $('#currency_id').val(parsedGrnData['header']['vendor']['currency_id']);
                $('#sub_total').val(parsedGrnData['sub_total']);
                $('#tax').val(parsedGrnData['tax_value']);
              // Parse the date from the ISO 8601 string
const createdAt = parsedGrnData['created_at'];
const formattedDate = createdAt.split('T')[0]; // Extract the date part (yyyy-MM-dd)

// Set the formatted date to the input field
            $('#book_date').val(formattedDate);

            } else {
                console.error('data-grn attribute not found on the selected radio button');
            }
        }
    });
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
        $('form').find('input, select').prop('readonly', true).prop('disabled', true);


    </script>
@endsection
@endsection
