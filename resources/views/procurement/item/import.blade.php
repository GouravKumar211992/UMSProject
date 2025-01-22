@extends('layouts.app')

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
                            <h2 class="content-header-title float-start mb-0">Upload Master Data</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>  
                                    <li class="breadcrumb-item active">Item Master</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-sm-end col-md-7 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm mb-50 mb-sm-0"><i data-feather="arrow-left-circle"></i> Back</button> 
						<a class="btn btn-primary btn-sm mb-50 mb-sm-0" href="item-master.html"><i data-feather="check-circle"></i> Upload</a> 
                    </div>
                </div>
            </div>
            <div class="content-body">
                
				<section id="basic-datatable">
                    <div class="row justify-content-center">
                        <div class="col-9">
                        <form class="ajax-input-form" method="POST" action="{{ route('items.import') }}" enctype="multipart/form-data" id="importForm">
                            @csrf
                            <div class="upload-item-masstrerdata">
                                <!-- File Upload Section -->
                                <div class="drapdroparea upload-btn-wrapper text-center">
                                    <i class="uploadiconsvg" data-feather='upload'></i>
                                    <p>Upload the template file with updated data</p>
                                    <button class="btn btn-primary">DRAG AND DROP HERE OR CHOOSE FILE</button>
                                    <input type="file" name="file" accept=".xlsx, .xls, .csv" class="form-control" id="fileUpload"/>
                                </div>

                                <!-- Selected File Name Section (Visible after file selection) -->
                                <div class="drapdroparea drapdroparea-small upload-btn-wrapper text-center" id="fileNameDisplay" style="display: none;">
                                    <span class="badge rounded-pill badge-light-warning fw-bold mb-1 badgeborder-radius d-flex align-items-center" id="selectedFileName"></span>
                                    <button type="submit" class="btn btn-primary">Proceed to Upload</button>
                                </div>

                                <!-- Progress Bar (Visible while uploading) -->
                                <div class="drapdroparea drapdroparea-small upload-btn-wrapper text-center" id="uploadProgress" style="display: none;">
                                    <span class="badge rounded-pill badge-light-warning fw-bold mb-1 badgeborder-radius d-flex align-items-center" id="progressFileName">
                                        <span id="selectedFileName"></span>
                                    </span>
                                    <button class="btn btn-primary" disabled>Proceed to Upload</button>
                                    <div class="w-75 mt-3">
                                        <div class="progress" style="height: 15px">
                                            <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%">75%</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Error Section -->
                                <div class="drapdroparea drapdroparea-small upload-btn-wrapper text-center" id="uploadError" style="display: none;">
                                    <i class="alertdropdatamaster" data-feather='alert-triangle'></i>
                                    <p>The system was unable to read some records from the uploaded file.<br />
                                    Please correct or remove those records from the file and upload again.</p>
                                    <div class="mt-2 downloadtemplate">
                                        <button class="editbtnNew">
                                            <i data-feather='upload'></i> Upload Again
                                        </button>
                                    </div>
                                </div>

                                <!-- Success Section -->
                                <div class="drapdroparea drapdroparea-small upload-btn-wrapper text-center" id="uploadSuccess" style="display: none;">
                                    <i class="itemdatasuccesssmaster" data-feather='check-circle'></i>
                                    <p>All records have been uploaded successfully.<br>
                                    Please proceed to process sales.</p>
                                    <div class="d-flex">
                                        <span class="badge rounded-pill badge-light-success fw-bold me-1 font-small-2 badgeborder-radius">Records Succeeded: 230</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                        
                        <div class="col-md-11 mt-3 col-12">
                            <div class="card  new-cardbox"> 
                                <ul class="nav nav-tabs border-bottom" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#Succeded">Records Succeeded &nbsp;<span>(10)</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#Failed">Records Failed &nbsp;<span>(5)</span></a>
                                    </li> 
                                </ul> 
                                <div class="tab-content">
                                    <div class="tab-pane active" id="Succeded">
                                        <div class="table-responsive candidates-tables">
                                            <table class="datatables-basic table table-striped myrequesttablecbox loanapplicationlist">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                        <th>Inventory Unit</th>
                                                        <th>HSN</th>
                                                        <th>Type</th>
                                                        <th>Sub-Type</th>
                                                        <th>Remarks</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-success">Success</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-success">Success</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-success">Success</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-success">Success</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-success">Success</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="Failed">
                                        <div class="table-responsive candidates-tables">
                                            <table class="datatables-basic table table-striped myrequesttablecbox loanapplicationlist">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                        <th>Inventory Unit</th>
                                                        <th>HSN</th>
                                                        <th>Type</th>
                                                        <th>Sub-Type</th>
                                                        <th>Remarks</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-danger">Error in item name</td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-danger">Error in item name</td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-danger">Error in item name</td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-danger">Error in item name</td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td class="fw-bolder text-dark">IM001</td>
                                                        <td>Account</td>
                                                        <td>KG</td>
                                                        <td>98765</td>
                                                        <td>Goods</td>
                                                        <td><span class="badge rounded-pill badge-light-secondary badgeborder-radius">Raw Material</span></td>
                                                        <td class="text-danger">Error in item name</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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
    $(function () { 

        var dt_basic_table = $('.datatables-basic'),
            assetPath = '../../../app-assets/';

        if ($('body').attr('data-framework') === 'laravel') {
            assetPath = $('body').attr('data-asset-path');
        }

        if (dt_basic_table.length) {
            var dt_basic = dt_basic_table.DataTable({
                order: [[0, 'asc']],
                dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                buttons: [{
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
                    buttons: [{
                        extend: 'print',
                        text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
                        className: 'dropdown-item',
                        exportOptions: { columns: [3, 4, 5, 6, 7] }
                    },
                    {
                        extend: 'csv',
                        text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
                        className: 'dropdown-item',
                        exportOptions: { columns: [3, 4, 5, 6, 7] }
                    },
                    {
                        extend: 'excel',
                        text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
                        className: 'dropdown-item',
                        exportOptions: { columns: [3, 4, 5, 6, 7] }
                    },
                    {
                        extend: 'pdf',
                        text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
                        className: 'dropdown-item',
                        exportOptions: { columns: [3, 4, 5, 6, 7] }
                    },
                    {
                        extend: 'copy',
                        text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
                        className: 'dropdown-item',
                        exportOptions: { columns: [3, 4, 5, 6, 7] }
                    }]
                }],
                language: {
                    paginate: {
                        previous: '&nbsp;',
                        next: '&nbsp;'
                    }
                }
            });
            $('div.head-label').html('<h6 class="mb-0">Event List</h6>');
        }

        
    });
</script>
<script>
  $(document).ready(function() {
   var fileInput = $("#fileUpload");
    $(".drapdroparea").on("dragover", function(event) {
        event.preventDefault();
        $(this).addClass("dragging");
    });
    $(".drapdroparea").on("dragleave", function(event) {
        event.preventDefault();
        $(this).removeClass("dragging");
    });
    $(".drapdroparea").on("drop", function(event) {
        event.preventDefault();
        $(this).removeClass("dragging");
        
        var files = event.originalEvent.dataTransfer.files;
        if (files.length) {
            fileInput[0].files = files;
            handleFileSelected(files[0]);
        }
    });
    fileInput.on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            handleFileSelected(file);
        }
    });
    function handleFileSelected(file) {
        var fileName = file.name;
        $('#selectedFileName').text(fileName);
        $('#fileNameDisplay').show();
        $('#uploadError').hide();
        $('#uploadProgress').hide();
        $('#uploadSuccess').hide();
    }
    $('#importForm').submit(function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        $('#uploadProgress').show();
        $('#fileNameDisplay').hide();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#uploadProgress').hide();
                $('#uploadSuccess').show();
            },
            error: function(response) {
                $('#uploadProgress').hide();
                $('#uploadError').show();
            }
        });
    });
});
  </script>
@endsection
