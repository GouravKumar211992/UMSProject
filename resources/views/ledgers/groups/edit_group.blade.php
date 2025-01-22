@extends('layouts.app')

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
                            <h2 class="content-header-title float-start mb-0">Edit Group</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('ledger-groups.index') }}">Groups</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit</li>
                                </ol>
                            </div>
                        </div>
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
                                        <div class="newheader border-bottom mb-2 pb-25">
                                            <h4 class="card-title text-theme">Basic Information</h4>
                                            <p class="card-text">Fill the details</p>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <form action="{{ route('ledger-groups.update', $data->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')                                             
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Parent Group</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <select name="parent_group_id"
                                                        class="form-select">
                                                        <option selected value="">Select</option>
                                                        @foreach ($parents as $parent)
                                                            <option value="{{ $parent->id }}" @if($parent->id == $data->parent_group_id) selected @endif>{{ $parent->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Group Name</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="name" class="form-control" required
                                                        value="{{ $data->name }}" />
                                                    @error('name')
                                                        <span class="alert alert-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Status</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="demo-inline-spacing">
                                                        <div class="form-check form-check-primary mt-25">
                                                            <input type="radio" id="customColorRadio3" name="status"
                                                                value="active" class="form-check-input"
                                                                @if($data->status == "active") checked @endif>
                                                            <label class="form-check-label fw-bolder"
                                                                for="customColorRadio3">Active</label>
                                                        </div>
                                                        <div class="form-check form-check-primary mt-25">
                                                            <input type="radio" id="customColorRadio4" name="status"
                                                                value="inactive" class="form-check-input"
                                                                @if($data->status == "inactive") checked @endif>
                                                            <label class="form-check-label fw-bolder"
                                                                for="customColorRadio4">Inactive</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <button type="button" onClick="javascript: history.go(-1)"
                                                    class="btn btn-secondary btn-sm">
                                                    <i data-feather="arrow-left-circle"></i> Back
                                                </button>
                                                <button type="submit" class="btn btn-primary btn-sm ms-1">
                                                    <i data-feather="check-circle"></i> Submit
                                                </button>
                                            </div>
                                        </form>
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
    document.addEventListener('DOMContentLoaded', function () {
        const masterSelect = document.getElementById('master_group_id');
        const parentSelect = document.getElementById('parent_group_id');

        const parentOptions = @json($parents);

        function updateParents() {
            const masterGroupId = masterSelect.value;

            parentSelect.innerHTML = '<option disabled selected>Select</option>';

            parentOptions.forEach(function (parent) {
                if (parent.master_group_id == masterGroupId) {
                    const option = document.createElement('option');
                    option.value = parent.id;
                    option.textContent = parent.name;
                    parentSelect.appendChild(option);
                }
            });

            // Set the currently selected parent group if available
            if (parentSelect.dataset.selectedParent) {
                parentSelect.value = parentSelect.dataset.selectedParent;
            }
        }

        masterSelect.addEventListener('change', updateParents);

        // Initialize parent options based on the default master group
        updateParents();

        // Set the selected parent group on page load after options are populated
        parentSelect.addEventListener('change', function () {
            parentSelect.dataset.selectedParent = parentSelect.value;
        });

        // Initialize selected value from data
        parentSelect.dataset.selectedParent = "{{ $data->parent_group_id }}";
        updateParents();  // Ensure the options are updated
    });
</script>

@endsection