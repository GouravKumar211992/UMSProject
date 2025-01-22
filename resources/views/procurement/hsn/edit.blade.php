@extends('layouts.app')

@section('content')
<form class="ajax-input-form" method="POST" action="{{ route('hsn.update', $hsn->id) }}" data-redirect="{{ url('/hsn') }}">
    @csrf
    @method('PUT')

    <!-- BEGIN: Content -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
                <div class="row">
                    <div class="content-header-left col-md-6 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Edit HSN/SAC</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('hsn.index') }}">Home</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('hsn.index') }}">HSN/SAC</a></li>
                                        <li class="breadcrumb-item active">Edit</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right text-end col-md-6 col-6 mb-2 mb-sm-0">
                        <div class="form-group breadcrumb-right">
                        <a href="{{ route('hsn.index') }}" class="btn btn-secondary btn-sm"><i data-feather="arrow-left-circle"></i> Back</a>
                            <button type="button" class="btn btn-danger btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light delete-btn"
                                    data-url="{{ route('hsn.destroy', $hsn->id) }}" 
                                    data-redirect="{{ route('hsn.index') }}"
                                    data-message="Are you sure you want to delete this hsn?">
                                <i data-feather="trash-2" class="me-50"></i> Delete
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm" id="submit-button"><i data-feather="check-circle"></i> Update</button>
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
                                                <p class="card-text">Edit the details</p>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <!-- Code Type -->
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Code Type <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="demo-inline-spacing">
                                                      @foreach ($hsnCodeType as $type)
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio"  id="{{ $type }}"  name="type"  value="{{ $type }}"  class="form-check-input"   {{ $hsn->type == $type ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bolder" for="{{ $type }}">
                                                                    {{ ucfirst($type) }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- HSN/SAC Code -->
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">HSN/SAC Code <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-5">
                                                    <input
                                                        type="text"
                                                        name="code"
                                                        class="form-control"
                                                        value="{{ $hsn->code }}"
                                                    />
                                                </div>
                                            </div>
                                            <!-- Description -->
                                            <div class="row mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Description</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <textarea
                                                        name="description"
                                                        class="form-control"
                                                    >{{ $hsn->description }}</textarea>
                                                </div>
                                            </div>

                                            <!-- Status -->
                                         
                                        </div>
                                        <div class="col-md-3 border-start">
                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Status</label>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="demo-inline-spacing">
                                                            @foreach ($status as $statusOption)
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input
                                                                        type="radio"
                                                                        id="status_{{ $statusOption }}"
                                                                        name="status"
                                                                        value="{{ $statusOption }}"
                                                                        class="form-check-input"
                                                                        {{ $hsn->status == $statusOption ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label fw-bolder" for="status_{{ $statusOption }}">
                                                                        {{ ucfirst($statusOption) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                         </div>

                                        <div class="col-md-12">
                                            <div class="newheader d-flex justify-content-between align-items-end mt-2 border-top pt-2">
                                                <div class="header-left">
                                                    <h4 class="card-title text-theme">Tax Pattern</h4>
                                                    <p class="card-text">Fill the details</p>
                                                </div> 
                                            </div>
                                        </div>
                                            
                                        <div class="col-md-8">
                                            <div class="table-responsive-md">
                                                <table id="taxPatternsTable" class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable"> 
                                                    <thead>
                                                        <tr>
                                                            <th>S.NO.</th>
                                                            <th width="200">From Price <span class="text-danger">*</span></th>
                                                            <th width="200">Upto Price <span class="text-danger">*</span></th>
                                                            <th>Tax Group <span class="text-danger">*</span></th>
                                                            <th>Action</th> 
                                                        </tr>
                                                    </thead>
                                                    <tbody> 
                                                        @forelse ($hsn->taxPatterns as $index => $taxDetail)
                                                            <tr data-id="{{ $taxDetail->id }}" class="tax-pattern-row">
                                                             <input type="hidden" name="tax_patterns[{{ $index }}][id]" value="{{ $taxDetail->id }}">
                                                                <td class="row-index">{{ $index + 1 }}</td>
                                                                <td>
                                                                    <input type="text" name="tax_patterns[{{ $index }}][from_price]" value="{{ $taxDetail->from_price }}" class="form-control numberonly mw-100">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="tax_patterns[{{ $index }}][upto_price]" value="{{ $taxDetail->upto_price }}" class="form-control numberonly mw-100">
                                                                </td>
                                                                <td>
                                                                    <select name="tax_patterns[{{ $index }}][tax_group_id]" class="form-select mw-100 select2">
                                                                        <option value="">Select</option>
                                                                        @foreach ($taxGroups as $group)
                                                                            <option value="{{ $group->id }}" {{ $taxDetail->tax_group_id == $group->id ? 'selected' : '' }}>
                                                                                {{ $group->tax_group }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td> 
                                                                <td>
                                                                    <a href="#" class="text-danger remove-row"><i data-feather="trash-2"></i></a>
                                                                    <a href="#" class="text-primary add-row"><i data-feather="plus-square"></i></a>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr class="tax-pattern-row">
                                                                <td class="row-index">1</td>
                                                                <td><input type="text" name="tax_patterns[0][from_price]" class="form-control numberonly mw-100"></td>
                                                                <td><input type="text" name="tax_patterns[0][upto_price]" class="form-control numberonly mw-100"></td>
                                                                <td>
                                                                    <select name="tax_patterns[0][tax_group_id]" class="form-select mw-100 select2">
                                                                        <option value="">Select</option>
                                                                        @foreach ($taxGroups as $group)
                                                                            <option value="{{ $group->id }}">{{ $group->tax_group }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="text-danger remove-row"><i data-feather="trash-2"></i></a>
                                                                    <a href="#" class="text-primary add-row"><i data-feather="plus-square"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
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
</form>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    function initializeSelect2() {
        $('.select2').select2({
            placeholder: "Select",
            allowClear: true
        });
    }
    initializeSelect2();
    function addRow() {
        const rowCount = $('#taxPatternsTable tbody tr').length;
        const rowIndex = rowCount;
        const rowHtml = `
            <tr class="tax-pattern-row">
                <td class="row-index">${rowIndex + 1}</td>
                <td><input type="text" name="tax_patterns[${rowIndex}][from_price]" class="form-control numberonly mw-100"></td>
                <td><input type="text" name="tax_patterns[${rowIndex}][upto_price]" class="form-control numberonly mw-100"></td>
                <td>
                    <select name="tax_patterns[${rowIndex}][tax_group_id]" class="form-select mw-100 select2">
                        <option value="" disabled>Select</option>
                        @foreach($taxGroups as $taxGroup)
                            <option value="{{ $taxGroup->id }}">{{ $taxGroup->tax_group }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <a href="#" class="text-danger remove-row"><i data-feather="trash-2"></i></a>
                    <a href="#" class="text-primary add-row"><i data-feather="plus-square"></i></a>
                </td>
            </tr>
        `;
        $('#taxPatternsTable tbody').append(rowHtml);
        feather.replace();
        initializeSelect2(); 
        updateRowIndexes(); 
    }
    function removeRow($row) {
        $row.remove();
        updateRowIndexes(); 
    }

    function updateRowIndexes() {
        $('#taxPatternsTable tbody .tax-pattern-row').each(function(index) {
            $(this).find('.row-index').text(index + 1); 
            $(this).find('input, select').each(function() {
                const currentName = $(this).attr('name');
                const newName = currentName.replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', newName);
            });
            $(this).find('.remove-row').show(); 
            $(this).find('.add-row').toggle(index === 0);
        });
    }
    $('#taxPatternsTable').on('click', '.add-row', function(e) {
        e.preventDefault();
        addRow(); 
    });

  $('#taxPatternsTable').on('click', '.remove-row', function(e) {
    e.preventDefault();
    var $row = $(this).closest('tr');
    var hsnDetailId = $row.data('id'); 
        if (hsnDetailId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to delete this record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/hsn/hsn-detail/' + hsnDetailId,  
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'), 
                        },
                        success: function(response) {
                            if (response.status) {
                                $row.remove();
                                Swal.fire('Deleted!', response.message, 'success');
                                updateRowIndices();
                            } else {
                                Swal.fire('Error!', response.message || 'Could not delete record detail.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message || 'An error occurred while deleting the record.', 'error');
                        }
                    });
                }
            });
        } else {
            $row.remove();
            updateRowIndices();
        }
  });
    feather.replace(); 
    updateRowIndexes();
});
</script>
@endsection
