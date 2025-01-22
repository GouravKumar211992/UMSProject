@extends('layouts.app')

@section('content')
<form class="ajax-input-form" method="POST" action="{{ route('hsn.store') }}" data-redirect="{{ url('/hsn') }}">
    @csrf
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
                                <h2 class="content-header-title float-start mb-0"> HSN/SAC</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                                        <li class="breadcrumb-item active">Add New</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right text-end col-md-6 col-6 mb-2 mb-sm-0">
                        <div class="form-group breadcrumb-right">
                            <a href="{{ route('hsn.index') }}" class="btn btn-secondary btn-sm"><i data-feather="arrow-left-circle"></i> Back</a>
                            <button type="submit" class="btn btn-primary btn-sm" id="submit-button"><i data-feather="check-circle"></i> Create</button>
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
                                            <!-- Code Type -->
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Code Type <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="demo-inline-spacing">
                                                      @foreach ($hsnCodeType as $type)
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio"  id="{{ $type }}"  name="type"  value="{{ $type }}"  class="form-check-input"  {{ $type === 'Hsn' ? 'checked' : '' }}>
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
                                                    <input type="text" name="code" class="form-control" />
                                                </div>
                                            </div>

                                        
                                            <!-- Description -->
                                            <div class="row mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Description</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <textarea name="description" class="form-control"></textarea>
                                                </div>
                                            </div>

                                            <!-- Status -->
                                           
                                        </div>

                                        <div class="col-md-3 border-start">
                                        <div class="row align-items-center mb-1">
                                                <div class="col-md-12">
                                                    <label class="form-label">Status</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="demo-inline-spacing">
                                                        @foreach ($status as $statusOption)
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio"id="status_{{ $statusOption }}" name="status" value="{{ $statusOption }}" class="form-check-input"  {{ $statusOption === 'active' ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bolder" for="status_{{ $statusOption }}">
                                                                    {{ ucfirst($statusOption) }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                         </div>
                                        <!-- Tax Pattern Section -->
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
                                                <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable" id="taxPatternsTable">
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
                                                        <tr class="tax-pattern-row">
                                                            <td class="row-index">1</td>
                                                            <td><input type="text" name="tax_patterns[0][from_price]" class="form-control numberonly mw-100"></td>
                                                            <td><input type="text" name="tax_patterns[0][upto_price]" class="form-control numberonly mw-100"></td>
                                                            <td>
                                                                <select name="tax_patterns[0][tax_group_id]" class="form-select mw-100 select2">
                                                                    <option value="" >Select</option>
                                                                    @foreach($taxGroups as $taxGroup)
                                                                        <option value="{{ $taxGroup->id }}">{{ $taxGroup->tax_group}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <a href="#" class="text-danger remove-row"><i data-feather="trash-2"></i></a>
                                                                <a href="#" class="text-primary add-row"><i data-feather="plus-square"></i></a>
                                                            </td>
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
                    <!-- Modal to add new record -->
                </section>
            </div>
        </div>
    </div>
</form>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    let rowIndex = 1;
    function initializeSelect2() {
        $('.select2').select2({
            placeholder: "Select",  
            allowClear: true       
        });  
    }
    initializeSelect2();
    function addRow() {
        rowIndex++;
        const rowHtml = `
            <tr class="tax-pattern-row">
                <td class="row-index">${rowIndex}</td>
                <td><input type="text" name="tax_patterns[${rowIndex}][from_price]" class="form-control numberonly mw-100"></td>
                <td><input type="text" name="tax_patterns[${rowIndex}][upto_price]" class="form-control numberonly mw-100"></td>
                <td>
                    <select name="tax_patterns[${rowIndex}][tax_group_id]" class="form-select mw-100 select2">
                        <option value="">Select</option>
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
    }
    function removeRow($row) {
        $row.remove();
        updateRowIndexes();
    }
    function updateRowIndexes() {
        $('#taxPatternsTable tbody .tax-pattern-row').each(function(index) {
            $(this).find('.row-index').text(index + 1);
            $(this).find('input, select').each(function() {
                const newName = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', newName);
            });
            $(this).find('.remove-row').show(); 
            $(this).find('.add-row').toggle(index === 0);
        });
    }
    $('#taxPatternsTable').on('click', '.add-row', function(e) {
        e.preventDefault();
        addRow();
        updateRowIndexes(); 
        feather.replace();
    });

    $('#taxPatternsTable').on('click', '.remove-row', function(e) {
        e.preventDefault();
        removeRow($(this).closest('tr'));
        updateRowIndexes();
    });
    feather.replace();
});
</script>
@endsection
