@extends('layouts.app')

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            <form action="{{ route('ledgers.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="content-header pocreate-sticky">
                    <div class="row">
                        <div class="content-header-left col-md-6 col-6 mb-2">
                            <div class="row breadcrumbs-top">
                                <div class="col-12">
                                    <h2 class="content-header-title float-start mb-0">Edit Ledger</h2>
                                    <div class="breadcrumb-wrapper">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('ledgers.index') }}">Ledger List</a></li>
                                            <li class="breadcrumb-item active">Edit Ledger</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content-header-right text-end col-md-6 col-6 mb-2 mb-sm-0">
                            <div class="form-group breadcrumb-right">
                                <button onClick="javascript: history.go(-1)" class="btn btn-secondary btn-sm"><i
                                        data-feather="arrow-left-circle"></i> Back</button>
                                <button type="submit" class="btn btn-primary btn-sm"><i
                                        data-feather="check-circle"></i>Submit</button>
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
                                                <div class="newheader  border-bottom mb-2 pb-25">
                                                    <h4 class="card-title text-theme">Basic Information</h4>
                                                    <p class="card-text">Fill the details</p>
                                                </div>
                                            </div>

                                                <div class="col-md-9">



                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Ledger Code <span class="text-danger">*</span></label>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <input type="text" name="code" class="form-control" required value="{{ $data->code }}"/>
                                                            @error('code')
                                                                <span class="alert alert-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Ledger Name <span
                                                                    class="text-danger">*</span></label>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <input type="text" name="name" class="form-control" required value="{{ $data->name }}"/>
                                                            @error('name')
                                                                <span class="alert alert-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Group <span
                                                                    class="text-danger">*</span></label>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <select class="form-select select2" name="ledger_group_id[]" id="ledger_group_id" multiple required>
                                                                @foreach ($groups as $group)
                                                                    <option value="{{ $group->id }}"
                                                                            @if(is_array(json_decode($data->ledger_group_id, true)) && in_array($group->id, json_decode($data->ledger_group_id, true)))
                                                                                selected
                                                                            @elseif($group->id == $data->ledger_group_id)
                                                                                selected
                                                                            @endif>
                                                                        {{ $group->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                             </div>
                                                        <div class="col-md-3">
                                                            <a href="{{ route('ledger-groups.create') }}" class="voucehrinvocetxt mt-0">Add Group</a>
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center mb-1">
                                                        <div class="col-md-3">
                                                            <label class="form-label">Cost Center</label>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <select name="cost_center_id" class="form-select select2">
                                                                <option value="">Select</option>
                                                                @foreach ($costCenters as $costCenter)
                                                                    <option value="{{ $costCenter->id }}" @if($costCenter->id==$data->cost_center_id) selected @endif>{{ $costCenter->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>



                                                </div>

                                                <div class="col-md-3 border-start">
                                                    <div class="row align-items-center mb-2">
                                                        <div class="col-md-12">
                                                            <label class="form-label text-primary"><strong>Status</strong></label>
                                                            <div class="demo-inline-spacing">
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input type="radio" id="customColorRadio3" value="1"
                                                                        name="status" class="form-check-input"
                                                                        @if($data->status==1) checked @endif>
                                                                    <label class="form-check-label fw-bolder"
                                                                        for="customColorRadio3">Active</label>
                                                                </div>
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input type="radio" id="customColorRadio4" value="0"
                                                                        name="status" class="form-check-input" @if($data->status==0) checked @endif>
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
                            </div>
                        </div>
                    </section>
                </div>
            </form>

        </div>
    </div>
    <!-- END: Content-->

@endsection
@section('scripts')
<script>
     $(document).ready(function() {
        $('#ledger_group_id').select2();

});
</script>
@endsection
