@extends('layouts.app')
@section('styles')
    <style type="text/css">
        #map {
            width: 100%;
            height: 550px;
            border: 10px solid #fff;
            box-shadow: 0 0px 20px rgba(0, 0, 0, 0.1);
        }
    </style>

    <style type="text/css">
        #pac-input {
            margin-top: 10px;
            padding: 10px;
            width: 95% !important;
            font-size: 16px;
            position: relative !important;
            left: 0 !important;
            top: 51px !important;
            border: #eee thin solid;
            font-size: 14px;
            border-radius: 6px;
            margin-left: 11px;
        }
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places" async defer>
    </script>
@section('content')
   <!-- BEGIN: Content-->
   <div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header pocreate-sticky">
            <div class="row">
                <div class="content-header-left col-md-6  mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Issue/Transfer Asset</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('finance.fixed-asset.issue-transfer.index') }}l">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active">Add New</li>


                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-end col-md-6 ">

                </div>
            </div>
        </div>
        <div class="content-body">



            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">

                        <div class="card">
                             <div class="card-body customernewsection-form">
                                <form id="fixed-asset-issue-transfer-form" method="POST"
                                action="{{ route('finance.fixed-asset.issue-transfer.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="newheader border-bottom mb-2 pb-25">
                                            <h4 class="card-title text-theme">Basic Information</h4>
                                            <p class="card-text">Fill the details</p>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <!-- Asset Code & Name -->
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-3">
                                                <label for="asset_id" class="form-label">Asset Code & Name <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-5">
                                                <select name="asset_id" id="asset_id" class="form-select select2" required>
                                                    <option value="" {{ old('asset_id') == '' ? 'selected' : '' }}>Select</option>
                                                    @foreach($assets as $asset)
                                                        <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                                            {{ $asset->asset_code }} ({{ $asset->asset_name }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-3">
                                                <label class="form-label">Status</label>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="demo-inline-spacing">
                                                    <div class="form-check form-check-primary mt-25">
                                                        <input
                                                            type="radio"
                                                            id="issue"
                                                            name="status"
                                                            value="issue"
                                                            class="form-check-input"
                                                            {{ old('status', 'issue') == 'issue' ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bolder" for="issue">Issue</label>
                                                    </div>
                                                    <div class="form-check form-check-primary mt-25">
                                                        <input
                                                            type="radio"
                                                            id="transfer"
                                                            name="status"
                                                            value="transfer"
                                                            class="form-check-input"
                                                            {{ old('status') == 'transfer' ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bolder" for="transfer">Transfer</label>
                                                    </div>
                                                    <div class="form-check form-check-primary mt-25">
                                                        <input
                                                            type="radio"
                                                            id="revoke"
                                                            name="status"
                                                            value="revoke"
                                                            class="form-check-input"
                                                            {{ old('status') == 'revoke' ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-bolder" for="revoke">Revoke</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Location -->
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-3">
                                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-5">
                                                <select name="location" id="location" required class="form-select">
                                                    <option value="" {{ old('location') == '' ? 'selected' : '' }}>Select</option>
                                                    <option value="2100" {{ old('location') == '2100' ? 'selected' : '' }}>2100, Noida</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="transferLocation">
                                        <!-- Transfer Location -->
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-3">
                                                <label for="transfer_location" class="form-label">Transfer Location <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-5">
                                                <select name="transfer_location" id="transfer_location" class="form-select select2">
                                                    <option value="" {{ old('transfer_location') == '' ? 'selected' : '' }}>Select</option>
                                                    <option value="2100" {{ old('transfer_location') == '2100' ? 'selected' : '' }}>2100, Noida</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                        <!-- Authorized Person -->
                                        <div class="row align-items-center mb-1">
                                            <div class="col-md-3">
                                                <label for="authorized_person" required class="form-label">Authorized Person <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-md-5">
                                                <select name="authorized_person" id="authorized_person" class="form-select select2">
                                                    <option value="" {{ old('authorized_person') == '' ? 'selected' : '' }}>Select</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}" {{ old('authorized_person') == $employee->id ? 'selected' : '' }}>
                                                            {{ $employee->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="mt-3">
                                            <a onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm">
                                                <i data-feather="arrow-left-circle"></i> Back
                                            </a>
                                            <button type="submit" class="btn btn-primary btn-sm ms-1">
                                                <i data-feather="check-circle"></i> Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>


                                 </form>



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
@section('scripts')

<script type="text/javascript">
    function initMap() {
        // Initialize autocomplete for 'location' and 'transfer_location' fields
        var locationInput = document.getElementById('location');
        var locationAutocomplete = new google.maps.places.Autocomplete(locationInput);
        locationAutocomplete.setFields(['address_component', 'geometry']);

        locationAutocomplete.addListener('place_changed', function () {
            var place = locationAutocomplete.getPlace();
            if (place.geometry) {
                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }
        });

        var transferLocationInput = document.getElementById('transfer_location');
        var transferLocationAutocomplete = new google.maps.places.Autocomplete(transferLocationInput);
        transferLocationAutocomplete.setFields(['address_component', 'geometry']);

        transferLocationAutocomplete.addListener('place_changed', function () {
            var place = transferLocationAutocomplete.getPlace();
            if (place.geometry) {
                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                document.getElementById('transfer_latitude').value = lat;
                document.getElementById('transfer_longitude').value = lng;
            }
        });
    }

    const statusRadios = document.querySelectorAll('input[name="status"]');
    const transferLocationField = document.getElementById('transferLocation');
    const transferLocationSelect = document.getElementById('transfer_location');

    // Function to handle showing/hiding the transfer location field and making it required
    function handleStatusChange() {
        const selectedStatus = document.querySelector('input[name="status"]:checked').value;

        if (selectedStatus === 'transfer') {
            // Show transfer location and make it required
            transferLocationField.style.display = 'block';
            transferLocationSelect.setAttribute('required', true);
        } else {
            // Hide transfer location and remove the required attribute
            transferLocationField.style.display = 'none';
            transferLocationSelect.removeAttribute('required');
        }
    }

    // Add event listeners to all radio buttons
    statusRadios.forEach(radio => {
        radio.addEventListener('change', handleStatusChange);
    });

    // Trigger the function on page load in case the form is prefilled
    handleStatusChange();

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

@endsection
