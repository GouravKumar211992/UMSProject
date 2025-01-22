@extends('layouts.app')

@section('content')
<!-- BEGIN: Content -->
<form class="ajax-input-form" method="POST" action="{{ route('tax.update', $tax->id) }}" data-redirect="{{ url('/taxes') }}">
    @csrf
    @method('PUT')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header pocreate-sticky">
                <div class="row">
                    <div class="content-header-left col-md-6 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Edit Tax</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                        <li class="breadcrumb-item active">Edit</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right text-sm-end col-md-6 mb-50 mb-sm-0">
                    <a href="{{ route('tax.index') }}" class="btn btn-secondary btn-sm"><i data-feather="arrow-left-circle"></i> Back</a>
                        <button type="button" class="btn btn-danger btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light delete-btn"
                            data-url="{{ route('tax.destroy', $tax->id) }}" 
                            data-redirect="{{ route('tax.index') }}"
                            data-message="Are you sure you want to delete this tax?">
                            <i data-feather="trash-2" class="me-50"></i> Delete
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm mb-50 mb-sm-0"><i data-feather="check-circle"></i> Update</button>
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
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Applicability Type <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="demo-inline-spacing">
                                                        @foreach ($applicationTypes as $type)
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio" id="application_type_{{ $loop->index }}" name="applicability_type" class="form-check-input"
                                                                {{ $tax->applicability_type === $type ? 'checked' : '' }} value="{{ $type }}">
                                                                <label class="form-check-label fw-bolder" for="application_type_{{ $loop->index }}">{{ ucfirst($type) }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Tax Group <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="tax_group" class="form-control" value="{{ $tax->tax_group ??'' }}" />
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Description</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <textarea name="description" class="form-control">{{ $tax->description ??'' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mb-1">
                                                <div class="col-md-3">
                                                    <label class="form-label">Status</label>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="demo-inline-spacing">
                                                        @foreach ($statuses as $status)
                                                            <div class="form-check form-check-primary mt-25">
                                                                <input type="radio" id="status_{{ $loop->index }}" name="status" value="{{ $status }}" class="form-check-input"
                                                                {{ $tax->status === $status ? 'checked' : '' }}>
                                                                <label class="form-check-label fw-bolder" for="status_{{ $loop->index }}">{{ ucfirst($status) }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="newheader d-flex justify-content-between align-items-end mt-2 border-top pt-2">
                                                <div class="header-left">
                                                    <h4 class="card-title text-theme">Edit Tax Details</h4>
                                                    <p class="card-text">Edit the details</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive-md">
                                                <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable">
                                                    <thead>
                                                        <tr>
                                                            <th>S.NO.</th>
                                                            <th>Tax Type <span class="text-danger">*</span></th>
                                                            <th>Tax %age <span class="text-danger">*</span></th>
                                                            <th>Place of Supply <span class="text-danger">*</span></th>
                                                            <th width="200px">Transaction Type <span class="text-danger">*</span></th>
                                                            <th width="200px">Ledger Name</th>
                                                            <th width="200px">Ledger Group</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tax-details-body">
                                                        @if($tax->taxDetails->isEmpty())
                                                            <tr data-index="0">
                                                                <td>1</td>
                                                                <td>
                                                                    <select name="tax_details[0][tax_type]" class="form-select mw-100">
                                                                        <option>Select</option>
                                                                        @foreach ($taxTypes as $type)
                                                                            <option value="{{ $type }}">{{ $type }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="tax_details[0][tax_percentage]" class="form-control mw-100" value="">
                                                                </td>
                                                                <td>
                                                                    <select name="tax_details[0][place_of_supply]" class="form-select mw-100">
                                                                        <option>Select</option>
                                                                        @foreach ($supplyTypes as $type)
                                                                            <option value="{{ $type }}">{{ $type }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="demo-inline-spacing">
                                                                        <div class="form-check form-check-primary mt-25">
                                                                            <input type="checkbox" name="tax_details[0][is_purchase]" value="1" class="form-check-input">
                                                                            <label class="form-check-label fw-bolder">Purchase</label>
                                                                        </div>
                                                                        <div class="form-check form-check-primary mt-25">
                                                                            <input type="checkbox" name="tax_details[0][is_sale]" value="1" class="form-check-input">
                                                                            <label class="form-check-label fw-bolder">Sale</label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="autocomplete-ledgr form-control mw-100" data-id="ledger_id_0" value="">
                                                                    <input type="hidden" id="ledger_id_0" name="tax_details[0][ledger_id]" value="">
                                                                </td>
                                                                <td>
                                                                    <select id="ledger_group_id_0" name="tax_details[0][ledger_group_id]" class="form-control mw-100 ledger-group-select">
                                                                        <option value="">Select Ledger Group</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="text-danger delete-row"><i data-feather="trash-2"></i></a>
                                                                    <a href="#" class="text-primary add-row"><i data-feather="plus-square"></i></a>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            @foreach ($tax->taxDetails as $index => $detail)
                                                                <tr data-index="{{ $index }}"  data-id="{{ $detail->id }}">
                                                                 <input type="hidden" name="tax_details[{{ $index }}][id]" value="{{ $detail->id }}">
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <td>
                                                                        <select name="tax_details[{{ $index }}][tax_type]" class="form-select mw-100">
                                                                            <option>Select</option>
                                                                            @foreach ($taxTypes as $type)
                                                                                <option value="{{ $type }}" {{ $detail->tax_type === $type ? 'selected' : '' }}>{{ $type ?? '' }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="tax_details[{{ $index }}][tax_percentage]" class="form-control mw-100" value="{{ $detail->tax_percentage ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <select name="tax_details[{{ $index }}][place_of_supply]" class="form-select mw-100">
                                                                            <option>Select</option>
                                                                            @foreach ($supplyTypes as $type)
                                                                                <option value="{{ $type }}" {{ $detail->place_of_supply === $type ? 'selected' : '' }}>{{ $type ?? '' }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <div class="demo-inline-spacing">
                                                                            <div class="form-check form-check-primary mt-25">
                                                                                <input type="checkbox" name="tax_details[{{ $index }}][is_purchase]" value="1" class="form-check-input" {{ $detail->is_purchase ? 'checked' : '' }}>
                                                                                <label class="form-check-label fw-bolder">Purchase</label>
                                                                            </div>
                                                                            <div class="form-check form-check-primary mt-25">
                                                                                <input type="checkbox" name="tax_details[{{ $index }}][is_sale]" value="1" class="form-check-input" {{ $detail->is_sale ? 'checked' : '' }}>
                                                                                <label class="form-check-label fw-bolder">Sale</label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="autocomplete-ledgr form-control mw-100" data-id="ledger_id_{{ $index }}" value="{{ $detail->ledger->name ?? '' }}">
                                                                        <input type="hidden" id="ledger_id_{{ $index }}" name="tax_details[{{ $index }}][ledger_id]" value="{{ $detail->ledger_id ?? '' }}">
                                                                    </td>
                                                                    <!-- <td>
                                                                        <input type="text" class="autocomplete-ledgr-group form-control mw-100" data-id="ledger_group_id_{{ $index }}" value="{{ $detail->ledgerGroup->name ?? '' }}">
                                                                        <input type="hidden" id="ledger_group_id_{{ $index }}" name="tax_details[{{ $index }}][ledger_group_id]" value="{{ $detail->ledger_group_id ?? '' }}">
                                                                    </td> -->
                                                                    <td>
                                                                        <select id="ledger_group_id_{{ $index }}" name="tax_details[{{ $index }}][ledger_group_id]" class="form-control mw-100 ledger-group-select">
                                                                            <option value="">Select Ledger Group</option>
                                                                            @foreach($ledgerGroups as $group)
                                                                                <option value="{{ $group->id }}" 
                                                                                        {{ isset($detail) && $detail->ledger_group_id == $group->id ? 'selected' : '' }}>
                                                                                    {{ $group->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <input type="hidden" id="hidden_ledger_group_id_{{ $index }}" value="{{ $detail->ledger_group_id ?? '' }}">
                                                                    </td>
                                                                    <td>
                                                                        <a href="#" class="text-danger delete-row"><i data-feather="trash-2"></i></a>
                                                                        <a href="#" class="text-primary add-row"><i data-feather="plus-square"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
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
    function initializeAutocomplete(selector, url, hiddenInputId) {
        $(selector).autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    data: { q: request.term },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                id: item.id,
                                label: item.name,
                                value: item.name,
                                code: item.code
                            };
                        }));
                    },
                    error: function(xhr) {
                        console.error('Error fetching data:', xhr.responseText);
                    }
                });
            },
            minLength: 0,
            select: function(event, ui) {
                $(this).val(ui.item.label);
                var rowId = $(this).data('id');
                $('#' + hiddenInputId).val(ui.item.id);
                updateLedgerGroupDropdown(ui.item.id, $(this).closest('tr'));
                return false;
            },
            change: function(event, ui) {
                if (!ui.item) {
                    $(this).val("");
                    var rowId = $(this).data('id');
                    $('#' + rowId).val('');
                }
            }
        }).focus(function() {
            if (this.value === "") {
                $(this).autocomplete("search", "");
            }
        });
    }
    function updateLedgerGroupDropdown(ledgerId, $row) {
        var selectedGroupId = $row.find("#hidden_ledger_group_id_" + $row.index()).val(); 
        if (ledgerId) {
            $.ajax({
                url: '/ledgers/' + ledgerId + '/groups', 
                method: 'GET',
                success: function(data) {
                    var ledgerGroupSelect = $row.find(".ledger-group-select");
                    ledgerGroupSelect.empty().append('<option value="">Select Ledger Group</option>');
                    
                    if (data && Array.isArray(data)) {
                        data.forEach(function(group) {
                            var isSelected = (String(group.id) === String(selectedGroupId)) ? 'selected' : '';
                            ledgerGroupSelect.append('<option value="' + group.id + '" ' + isSelected + '>' + group.name + '</option>');
                        });
                    } else {
                        console.error("No groups found for this ledger.");
                    }
                },
                error: function(xhr) {
                    console.error('Error fetching Ledger Groups:', xhr.responseText);
                    alert('An error occurred while fetching the ledger groups.');
                }
            });
        }
    }

    function initializeLedgerAutocomplete(selector, rowIndex) {
        initializeAutocomplete(selector, "{{ url('/search/ledger') }}", "ledger_id_" + rowIndex);
    }
    function initializeLedgerGroupsOnPageLoad() {
        $('#tax-details-body tr').each(function() {
            var ledgerId = $(this).find('input[name^="tax_details"][name$="[ledger_id]"]').val();
            if (ledgerId) {
                updateLedgerGroupDropdown(ledgerId, $(this)); 
            }
           
        });
    }

    function addRow() {
        var newRow = $('#tax-details-body tr:first').clone();
        var rowCount = $('#tax-details-body tr').length;
        newRow.find('td:first').text(rowCount + 1);
        newRow.attr('id', 'row-' + rowCount);

        newRow.find('input[type="checkbox"]').prop('checked', false); 
        newRow.find('input, select').each(function() {
            $(this).val('');
            var id = $(this).attr('id');
            if (id) {
                $(this).attr('id', id.replace(/\d+$/, rowCount));
            }
        });

        $('#tax-details-body').append(newRow);
        initializeLedgerAutocomplete(newRow.find(".autocomplete-ledgr"), rowCount);
        updateRowIndices();
    }
    function updateRowIndices() {
        $('#tax-details-body tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
            $(this).find('input, select').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + index + ']'));
                }
                var id = $(this).attr('id');
                if (id) {
                    $(this).attr('id', id.replace(/\d+$/, index));
                }
            });
            $(this).attr('id', 'row-' + index);
            $(this).find('.delete-row').show();
            $(this).find('.add-row').toggle(index === 0);
        });
    }

    $('#tax-details-body').on('click', '.add-row', function(e) {
        e.preventDefault();
        addRow();
    });

    $('#tax-details-body').on('click', '.delete-row', function(e) {
        e.preventDefault();
        var $row = $(this).closest('tr');
        var taxDetailId = $row.data('id');
        if (taxDetailId) {
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
                        url: '/taxes/tax-detail/' + taxDetailId,
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
                                Swal.fire('Error!', response.message || 'Could not delete record.', 'error');
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
    initializeLedgerAutocomplete("#tax-details-body .autocomplete-ledgr", 0);
    initializeLedgerGroupsOnPageLoad();
    function handleCheckboxes() {
        $('#tax-details-body').on('change', 'input[type="checkbox"]', function() {
            if ($(this).is(':checked')) {
                $(this).val('1');
            } else {
                $(this).removeAttr('value');
            }
        });
    }

    handleCheckboxes();
    updateRowIndices();
});

</script>
@endsection
