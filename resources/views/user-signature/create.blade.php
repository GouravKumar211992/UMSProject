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
                    <div class="content-header-left col-md-6 col-6 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">User Signature</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('user-signature.index') }}">Home</a>
                                        </li>
                                        <li class="breadcrumb-item active">Add New</li>


                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right text-end col-md-6 col-6 mb-2 mb-sm-0">
                        <div class="form-group breadcrumb-right">
                            <a href="{{ route('user-signature.index') }}"> <button class="btn btn-secondary btn-sm"><i
                                        data-feather="arrow-left-circle"></i> Back</button> </a>
                            <button hidden class="btn btn-outline-primary btn-sm mb-50 mb-sm-0" type="button" id="save-draft-btn">
                                <i data-feather="save"></i> Save as Draft
                            </button>
                            <button type="submit" form="user-signature-form" class="btn btn-primary btn-sm"
                                id="submit-btn">
                                <i data-feather="check-circle"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <section id="basic-datatable">
                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <form id="user-signature-form" method="POST" action="{{ route('user-signature.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <input type ="hidden" name="book_code" id ="book_code_input">
                                    <input type="hidden" name="doc_number_type" id="doc_number_type">
                                    <input type="hidden" name="doc_reset_pattern" id="doc_reset_pattern">
                                    <input type="hidden" name="doc_prefix" id="doc_prefix">
                                    <input type="hidden" name="doc_suffix" id="doc_suffix">
                                    <input type="hidden" name="doc_no" id="doc_no">

                                    <div class="card-body customernewsection-form">


                                        <div class="border-bottom mb-2 pb-25">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="newheader ">
                                                        <h4 class="card-title text-theme">Basic Information</h4>
                                                        <p class="card-text">Fill the details</p>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-2">
                                                        <label class="form-label">Name <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <select id="employee_id" name="employee_id" class="form-select select2">
                                                            <option value="">Select</option>
                                                            @foreach($employees as $emp)
                                                            <option value="{{$emp->id}}">{{$emp->name}}</option>
                                                            @endforeach
                                                        </select>

                                                        <input type="hidden" id="name" name="name">
                                                    </div>
                                                </div>


                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-2">
                                                        <label class="form-label">Designation <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="designation"
                                                            required id="designation" value="{{ old('designation') }}">
                                                        <input type="hidden" name="document_status" id="document_status"
                                                            value="">
                                                    </div>
                                                </div>



                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-2">
                                                        <label class="form-label">Upload Sign <span
                                                                class="text-danger">*</span></label>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="file" class="form-control" required
                                                            name="sign_upload_file" id="fileInput" accept=".jpg,.jpeg,.png">

                                                        <div id="appenddata">
                                                            <!-- Preview images will be appended here -->
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                            </div>
                            </form>
                        </div>
                    </div>
            </div>
            <!-- Modal to add new record -->

            </section>
        </div>
    </div>
    </div>
    <!-- END: Content-->

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

        document.getElementById('save-draft-btn').addEventListener('click', function() {
            document.getElementById('document_status').value = 'draft';
            document.getElementById('user-signature-form').submit();
        });

        document.getElementById('submit-btn').addEventListener('click', function() {
            document.getElementById('document_status').value = 'submitted';
        });

        let filesArray = [];

        // Maximum file size (5 MB in this example)
        const maxSize = 5 * 1024 * 1024;

        // Allowed file types
        const allowedExtensions = /(\.pdf|\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.webp)$/i;


        document.getElementById('fileInput').addEventListener('change', function(event) {
            const inputElement = event.target; // File input element
            const files = Array.from(inputElement.files); // Convert FileList to Array

            // Filter and validate files
            const validFiles = files.filter(file => {
                if (file.size > maxSize) {
                    alert('File ' + file.name + ' is too large. Maximum size is 5 MB.');
                    return false;
                }
                if (!allowedExtensions.test(file.name)) {
                    alert('Invalid file type for ' + file.name + '. Only PDF files are allowed.');
                    return false;
                }
                return true;
            });

            if (validFiles.length < files.length) {
                // Some files are invalid; reset the input
                inputElement.value = ''; // Clear input to prevent invalid files from being submitted
            }

            // Add new valid files to the array
            filesArray = filesArray.concat(validFiles);
            updateFilePreviews();
            updateFileInput();
            feather.replace(); // Initialize Feather icons after updating the DOM
        });

        function updateFilePreviews() {
            const appendData = document.getElementById('appenddata');
            appendData.innerHTML = ''; // Clear previous previews

            filesArray.forEach((file, index) => {
                const reader = new FileReader();

                // Create a div to hold the file preview
                const fileDiv = document.createElement('div');
                fileDiv.classList.add('image-uplodasection');

                // Create a preview element
                const filePreview = document.createElement('div');
                filePreview.classList.add('filepreview');

                // Create an icon or image for the file type
                const fileIcon = document.createElement('i');
                fileIcon.classList.add('fileuploadicon');

                if (file.type.startsWith('image/')) {
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('preview-image');
                        filePreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Assign the correct icon for non-image files (e.g., PDF, DOCX)
                    let iconType;
                    switch (file.type) {
                        case 'application/pdf':
                            iconType = 'file-text'; // Icon for PDF
                            break;
                        case 'application/msword':
                        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                            iconType = 'file'; // Icon for Word documents
                            break;
                        case 'image/jpeg':
                            iconType = 'image'; // Icon for JPEG images
                            break;
                        case 'image/png':
                            iconType = 'image'; // Icon for PNG images
                            break;
                        case 'image/gif':
                            iconType = 'image'; // Icon for GIF images
                            break;
                        case 'image/bmp':
                            iconType = 'image'; // Icon for BMP images
                            break;
                        case 'image/webp':
                            iconType = 'image'; // Icon for WebP images
                            break;
                        default:
                            iconType = 'file'; // Default icon for other file types
                            break;
                    }
                    fileIcon.setAttribute('data-feather', iconType);
                    filePreview.appendChild(fileIcon);
                }

                // Add a delete button
                const deleteButton = document.createElement('div');
                deleteButton.classList.add('delete-img', 'text-danger');
                deleteButton.innerHTML = '<i data-feather="x"></i>';
                deleteButton.setAttribute('data-index', index); // Store the file index
                deleteButton.addEventListener('click', function() {
                    removeFile(this.getAttribute('data-index'));
                });
                filePreview.appendChild(deleteButton);

                fileDiv.appendChild(filePreview);
                appendData.innerHTML = ''; // Clear previous previews
                appendData.appendChild(fileDiv);
            });

            // Replace feather icons
            feather.replace();
        }


        function removeFile(index) {
            filesArray.splice(index, 1); // Remove the file from the array
            updateFilePreviews(); // Update the previews
            updateFileInput(); // Update the file input
        }
        function updateFileInput() {
            const dataTransfer = new DataTransfer(); // Create a new DataTransfer object

            filesArray.forEach(file => {
                dataTransfer.items.add(file); // Add remaining files to the DataTransfer object
            });

            document.getElementById('fileInput').files = dataTransfer.files; // Update the file input with the new FileList
        }

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

        $(document).ready(function() {
    $('#employee_id').change(function() {
        // Get the text of the selected option
        var selectedText = $('#employee_id option:selected').text();
        $('#name').val(selectedText); // Set the text to the input field with id 'name'
    });
});

    </script>
@endsection
@endsection
