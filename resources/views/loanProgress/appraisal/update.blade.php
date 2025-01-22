@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!-- BEGIN: Content-->
<div class="app-content content ">

    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>

    <div class="content-wrapper container-xxl p-0">

        <!-- Header -->
        <div class="content-header pocreate-sticky">
            <div class="row">
                <div class="content-header-left col-md-6 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Update Appraisal</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                    <li class="breadcrumb-item active">Add New</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0"><i
                                data-feather="arrow-left-circle"></i> Back</button>
                        <button onClick="javascript: history.go(-1)"
                            class="btn btn-outline-primary btn-sm mb-50 mb-sm-0"><i data-feather="check-circle"></i>
                            Save as Draft</button>
                        <button onClick="javascript: history.go(-1)" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i
                                data-feather="check-circle"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Body -->
        <div class="content-body">
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body customernewsection-form">

                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="newheader border-bottom mb-2 pb-25 d-flex flex-wrap justify-content-between">
                                            <div>
                                                <h4 class="card-title text-theme">Basic Information</h4>
                                                <p class="card-text">Fill the details</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Application No. <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" disabled value="HL/2024/001" />
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Name of Unit <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" value="M/s NB HOTEL" />
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Name of Proprietor <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" disabled value="Mrs. Binolis Nongsiej" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" value="MAWBYRSHEM, NEW NONGSTOIN WEST KHASI HILLS DISTRICT." class="form-control">
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Project Cost<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Term Loan<span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Promotor's Contribution <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Cibil Score <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Interest Rate (P.A) <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Loan Peroid <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Repayment Type <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-select mw-100">
                                                    <option>Select</option>
                                                    <option selected="">Yearly</option>
                                                    <option>Half-Yearly</option>
                                                    <option>Monthly</option>
                                                    <option>Quarterly</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">No. of Installment(s) <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" disabled value="5">
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Repayment Start After <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-select">
                                                    <option>Select</option>
                                                    <option>1st Disbursement</option>
                                                    <option>2nd Disbursement</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-4">
                                                <label class="form-label">Repayment Start Period <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-3 pe-0">
                                                <input type="text" class="form-control">
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Year</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="mt-2">

                                        <div class="step-custhomapp bg-light">
                                            <ul class="nav nav-tabs my-25 custapploannav" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#Report">Detail Project Report</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#Disbursal">Disbursal Schedule</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#Recovery">Recovery Schedule</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#Documentsupload">KYC Documents</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="tab-content ">
                                            <div class="tab-pane active" id="Report">

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">DPR Template<span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <select class="form-select">
                                                                    <option>Select</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Constitution<span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Proposed Project<span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Project Site<span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Debt Equity Ratio <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Capacity <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Capacity Utilization <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Average D.S.C.R. <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Break Even Point <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Internal Rate of Return <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Collateral Type</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row align-items-center mb-1">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Collateral Value</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane" id="Disbursal">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Disbursal Milestone <span class="text-danger">*</span></th>
                                                                        <th>Disbursal Amount <span class="text-danger">*</span></th>
                                                                        <th>Remarks <span class="text-danger">*</span>
                                                                        </th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td><input type="text"
                                                                                class="form-control mw-100"></td>
                                                                        <td><input type="number"
                                                                                class="form-control mw-100"></td>
                                                                        <td><input type="text"
                                                                                class="form-control mw-100"></td>
                                                                        <td><a href="#" class="text-primary"><i
                                                                                    data-feather="plus-square"
                                                                                    class="me-50"></i></a></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>2</td>
                                                                        <td><input type="text"
                                                                                value="First Floor Construction"
                                                                                class="form-control mw-100"></td>
                                                                        <td><input type="number" value="500000"
                                                                                class="form-control mw-100"></td>
                                                                        <td><input type="text"
                                                                                value="Repayment after 24 months"
                                                                                class="form-control mw-100"></td>
                                                                        <td><a href="#" class="text-danger"><i
                                                                                    data-feather="trash-2"
                                                                                    class="me-50"></i></a></td>
                                                                    </tr>


                                                                </tbody>


                                                            </table>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="tab-pane" id="Recovery">
                                                <div class="row">
                                                    <div class="col-md-12">


                                                        <div class="table-responsive">
                                                            <table
                                                                class="table myrequesttablecbox table-striped po-order-detail custnewpo-detail border">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th width="100px">Year</th>
                                                                        <th class="text-end">Amt. at Start</th>
                                                                        <th class="text-end">Interest Amt.</th>
                                                                        <th class="text-end">Repayemnt Amt.</th>
                                                                        <th class="text-end">Amount at End <span
                                                                                class="text-danger">*</span></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>1st</td>
                                                                        <td><input type="number" value="500000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="50000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="0"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="500000" disabled
                                                                                class="form-control mw-100 text-end">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>2</td>
                                                                        <td>2nd</td>
                                                                        <td><input type="number" value="500000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="50000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="125000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="375000" disabled
                                                                                class="form-control mw-100 text-end">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>3</td>
                                                                        <td>3rd</td>
                                                                        <td><input type="number" value="375000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="37500"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="125000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="250000" disabled
                                                                                class="form-control mw-100 text-end">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>4</td>
                                                                        <td>4th</td>
                                                                        <td><input type="number" value="250000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="25000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="125000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="125000" disabled
                                                                                class="form-control mw-100 text-end">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>5</td>
                                                                        <td>5th</td>
                                                                        <td><input type="number" value="125000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="12500"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="125000"
                                                                                class="form-control mw-100 text-end"
                                                                                disabled></td>
                                                                        <td><input type="number" value="0" disabled
                                                                                class="form-control mw-100 text-end">
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td colspan="3"
                                                                            class="text-end fw-bolder text-dark">Total
                                                                        </td>
                                                                        <td class="fw-bolder text-dark text-end">175000
                                                                        </td>
                                                                        <td class="fw-bolder text-dark text-end">500000
                                                                        </td>
                                                                    </tr>
                                                                </tbody>


                                                            </table>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane" id="Documentsupload">
                                                <div class="table-responsive-md">
                                                    <table
                                                        class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Document Name</th>
                                                                <th>Upload File</th>
                                                                <th>Attachments</th>
                                                                <th width="40px">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>
                                                                    <select class="form-select mw-100">
                                                                        <option>Select</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="file" multiple
                                                                        class="form-control mw-100">
                                                                </td>
                                                                <td>
                                                                    <div class="image-uplodasection expenseadd-sign">
                                                                        <i data-feather="file-text"
                                                                            class="fileuploadicon"></i>
                                                                        <div class="delete-img text-danger">
                                                                            <i data-feather="x"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="image-uplodasection expenseadd-sign">
                                                                        <i data-feather="file-text"
                                                                            class="fileuploadicon"></i>
                                                                        <div class="delete-img text-danger">
                                                                            <i data-feather="x"></i>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><a href="#" class="text-primary"><i
                                                                            data-feather="plus-square"></i></a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>
                                                                    <select class="form-select mw-100">
                                                                        <option>Select</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="file" multiple
                                                                        class="form-control mw-100">
                                                                </td>
                                                                <td>
                                                                    <div class="image-uplodasection expenseadd-sign">
                                                                        <i data-feather="file-text"
                                                                            class="fileuploadicon"></i>
                                                                        <div class="delete-img text-danger">
                                                                            <i data-feather="x"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="image-uplodasection expenseadd-sign">
                                                                        <i data-feather="file-text"
                                                                            class="fileuploadicon"></i>
                                                                        <div class="delete-img text-danger">
                                                                            <i data-feather="x"></i>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><a href="#" class="text-danger"><i
                                                                            data-feather="trash-2"></i></a></td>
                                                            </tr>


                                                        </tbody>


                                                    </table>
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
    </div>
</div>
<!-- END: Content-->

<div class="modal fade text-start" id="rescdule" tabindex="-1" aria-labelledby="myModalLabel17" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title fw-bolder text-dark namefont-sizenewmodal" id="myModalLabel17">Select Pending
                        Disbursal</h4>
                    <p class="mb-0">Select from the below list</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-3">
                        <div class="mb-1">
                            <label class="form-label">Loan Type</label>
                            <select class="form-select">
                                <option>Select</option>
                                <option>Home Loan</option>
                                <option>Vehicle Loan</option>
                                <option>Term Loan</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-1">
                            <label class="form-label">Customer Name</label>
                            <select class="form-select select2">
                                <option>Select</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-1">
                            <label class="form-label">Application No.</label>
                            <select class="form-select select2">
                                <option>Select</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3  mb-1">
                        <label class="form-label">&nbsp;</label><br />
                        <button class="btn btn-warning btn-sm"><i data-feather="search"></i> Search</button>
                    </div>

                    <div class="col-md-12">


                        <div class="table-responsive">
                            <table class="mt-1 table myrequesttablecbox table-striped po-order-detail">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Application No.</th>
                                        <th>Date</th>
                                        <th>Customer Name</th>
                                        <th>Loan Type</th>
                                        <th>Disbursal Milestone</th>
                                        <th>Disbursal Amt.</th>
                                        <th>Mobile No.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-primary">
                                                <input type="radio" id="customColorRadio3" name="customColorRadio3"
                                                    class="form-check-input" checked="">
                                            </div>
                                        </td>
                                        <td>HL/2024/001</td>
                                        <td>20-07-2024</td>
                                        <td class="fw-bolder text-dark">Kundan Kumar</td>
                                        <td>Term</td>
                                        <td>1st floor completed</td>
                                        <td>200000</td>
                                        <td>9876787656</td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-check form-check-primary">
                                                <input type="radio" id="customColorRadio3" name="customColorRadio3"
                                                    class="form-check-input" checked="">
                                            </div>
                                        </td>
                                        <td>HL/2024/001</td>
                                        <td>20-07-2024</td>
                                        <td class="fw-bolder text-dark">Kundan Kumar</td>
                                        <td>Term</td>
                                        <td>2nd floor completed</td>
                                        <td>200000</td>
                                        <td>nishu@gmail.com</td>
                                    </tr>





                                </tbody>


                            </table>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer text-end">
                <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal"><i data-feather="x-circle"></i>
                    Cancel</button>
                <button class="btn btn-primary btn-sm" data-bs-dismiss="modal"><i data-feather="check-circle"></i>
                    Process</button>
            </div>
        </div>
    </div>
</div>

@endsection



@section('scripts')

<script>
    $(window).on('load', function () {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })


    $(function () {
        $(".ledgerselecct").autocomplete({
            source: [
                "Furniture (IT001)",
                "Chair (IT002)",
                "Table (IT003)",
                "Laptop (IT004)",
                "Bags (IT005)",
            ],
            minLength: 0
        }).focus(function () {
            if (this.value == "") {
                $(this).autocomplete("search");
            }
        });
    });

</script>

@endsection
