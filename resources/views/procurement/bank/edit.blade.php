@extends('layouts.app')

@section('content')
    <!-- BEGIN: Content-->
    <form class="ajax-input-form" method="POST" action="{{ route('bank.update', $bank->id) }}" data-redirect="{{ url('/banks') }}">
        @csrf
        @method('PUT')
        <div class="app-content content">
            <div class="content-overlay"></div>
            <div class="header-navbar-shadow"></div>
            <div class="content-wrapper container-xxl p-0">
                <div class="content-header pocreate-sticky">
                    <div class="row">
                        <div class="content-header-left col-md-6 col-6 mb-2">
                            <div class="row breadcrumbs-top">
                                <div class="col-12">
                                    <h2 class="content-header-title float-start mb-0">Edit Bank Details</h2>
                                    <div class="breadcrumb-wrapper">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{ route('bank.index') }}">Home</a></li>
                                            <li class="breadcrumb-item active">Edit</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content-header-right text-end col-md-6 col-6 mb-2 mb-sm-0">
                           <a href="{{ route('bank.index') }}" class="btn btn-secondary btn-sm"><i data-feather="arrow-left-circle"></i> Back</a>
                            <button type="button" class="btn btn-danger btn-sm mb-50 mb-sm-0 waves-effect waves-float waves-light delete-btn"
                                    data-url="{{ route('bank.destroy', $bank->id) }}" 
                                    data-redirect="{{ route('bank.index') }}"
                                    data-message="Are you sure you want to delete this bank?">
                                <i data-feather="trash-2" class="me-50"></i> Delete
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm"><i data-feather="check-circle"></i> Update</button>
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
                                                        <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" name="bank_name" class="form-control" placeholder="Enter Bank Name" value="{{ old('bank_name', $bank->bank_name) }}" />
                                                        @error('bank_name')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                ` <div class="col-md-3">
                                                        <label for="ledger_name" class="form-label">Ledger</label>
                                                    </div>
                                                    <div class="col-md-5">        <input type="text" id="ledger_name" name="ledger_name" class="form-control ladger-autocomplete" placeholder="Type to search..." value="{{$bank->ledger->name ?? ''}}">
                                                        <input type="hidden" id="ledger_id" name="ledger_id" class="ledger-id" value="{{ $bank->ledger_id ?? ''}}">
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label for="ledger_group_name" class="form-label">Ledger Group</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="text" id="ledger_group_name" name="ledger_group_name" class="form-control ledger-group-autocomplete" placeholder="Type to search..." value="{{$bank->ledgerGroup->name ?? ''}}">
                                                        <input type="hidden" id="ledger_group_id" name="ledger_group_id" class="ledger-group-id" value="{{ $bank->ledger_group_id ?? ''}}">
                                                    </div>
                                                </div>

                                                <div class="row align-items-center mb-1">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Status</label>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="demo-inline-spacing">
                                                            @foreach ($status as $statusOption)
                                                                <div class="form-check form-check-primary mt-25">
                                                                    <input
                                                                        type="radio"
                                                                        id="status_{{ $statusOption }}"
                                                                        name="status"
                                                                        value="{{ $statusOption }}"
                                                                        class="form-check-input"
                                                                        {{ old('status', $bank->status) == $statusOption ? 'checked' : '' }}
                                                                    >
                                                                    <label class="form-check-label fw-bolder" for="status_{{ $statusOption }}">
                                                                        {{ ucfirst($statusOption) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @error('status')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="table-responsive-md">
                                                    <table class="mt-1 table myrequesttablecbox table-striped po-order-detail custnewpo-detail border newdesignerptable">
                                                        <thead>
                                                            <tr>
                                                                <th>S.NO.</th>
                                                                <th>Account Number</th>
                                                                <th>Branch Name</th>
                                                                <th>Branch Address</th>
                                                                <th>IFSC Code</th>
                                                                <th>Ledgar Name</th>
                                                                <th>Ledgar Group</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="sub-category-box">
                                                            @forelse($bank->bankDetails as $index => $detail)
                                                                <tr data-id="{{ $detail->id }}">
                                                                    <td>{{ $index + 1 }}</td>
                                                                    <input type="hidden" name="bank_details[{{ $index }}][id]" value="{{ $detail->id }}">
                                                                    <td>
                                                                        <input type="text" name="bank_details[{{ $index }}][account_number]" class="form-control mw-100" placeholder="Enter Account Number" value="{{ $detail->account_number ?? '' }}" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bank_details[{{ $index }}][branch_name]" class="form-control mw-100" placeholder="Enter Branch Name" value="{{ $detail->branch_name ?? '' }}" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bank_details[{{ $index }}][branch_address]" class="form-control mw-100" placeholder="Enter Branch Address" value="{{ $detail->branch_address ?? '' }}" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bank_details[{{ $index }}][ifsc_code]" class="form-control mw-100 ifsc-code" placeholder="Enter IFSC Code" value="{{ $detail->ifsc_code ?? '' }}" />
                                                                    </td>
                                                                    <!-- Ledger Name and ID -->
                                                                    <td>
                                                                        <input type="text" class="autocomplete-ledgr form-control mw-100" data-id="ledger_id_{{ $index }}" placeholder="Enter Ledger ID" value="{{ $detail->ledger->name ?? '' }}">
                                                                        <input type="hidden" class="ledger-id" id="ledger_id_{{ $index }}" name="bank_details[{{ $index }}][ledger_id]" value="{{ $detail->ledger_id ?? '' }}">
                                                                    </td>

                                                                    <td>
                                                                        <input type="text" class="autocomplete-ledgr-group form-control mw-100" data-id="ledger_group_id_{{ $index }}" placeholder="Enter Ledger Group ID" value="{{ $detail->ledgerGroup->name ?? '' }}">
                                                                        <input type="hidden" class="ledger-group-id" id="ledger_group_id_{{ $index }}" name="bank_details[{{ $index }}][ledger_group_id]" value="{{ $detail->ledger_group_id ?? '' }}">
                                                                    </td>
            
                                                                    <td>
                                                                        <a href="#" class="text-danger delete-address"><i data-feather="trash-2"></i></a>
                                                                        <a href="#" class="text-primary add-address"><i data-feather="plus-square"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr class="bank-detail-row">
                                                                    <td></td>
                                                                    <td>
                                                                        <input type="text" name="bank_details[0][account_number]" class="form-control mw-100" placeholder="Enter Account Number" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bank_details[0][branch_name]" class="form-control mw-100" placeholder="Enter Branch Name" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bank_details[0][branch_address]" class="form-control mw-100" placeholder="Enter Branch Address" />
                                                                        <input type="hidden" name="bank_details[0][branch_address_id]" class="branch-address-id" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bank_details[0][ifsc_code]" class="form-control mw-100 ifsc-code" placeholder="Enter IFSC Code" />
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="autocomplete-ledgr form-control mw-100" data-id="ledger_id_0" placeholder="Enter Ledger ID" value="">
                                                                        <input type="hidden" class="ledger-id" id="ledger_id_0" name="bank_details[0][ledger_id]" value="">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="autocomplete-ledgr-group form-control mw-100" data-id="ledger_group_id_0" placeholder="Enter Ledger Group ID" value="">
                                                                        <input type="hidden" class="ledger-group-id" id="ledger_group_id_0" name="bank_details[0][ledger_group_id]" value="">
                                                                    </td>
                                                                    <td>
                                                                        <a href="#" class="text-danger delete-address"><i data-feather="trash-2"></i></a>
                                                                        <a href="#" class="text-primary add-address"><i data-feather="plus-square"></i></a>
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
        var ledgerName, ledgerId, ledgerGroupName, ledgerGroupId;
        function updateLedgerValues() {
            ledgerName = $('#ledger_name').val(); 
            ledgerId = $('#ledger_id').val();  
            ledgerGroupName = $('#ledger_group_name').val(); 
            ledgerGroupId = $('#ledger_group_id').val();  
            $('#ledger_id').val(ledgerId);  
            if (ledgerId) {
            $('#sub-category-box tr').each(function() {
                var ledgerGroupInput = $(this).find(".autocomplete-ledgr-group");
                updateLedgerGroupAutocomplete(ledgerId, ledgerGroupInput);
            });
        }
        }

        function updateExistingRows() {
            $('table tr').each(function() {
                $(this).find('.autocomplete-ledgr').val(ledgerName);  
                $(this).find('.ledger-id').val(ledgerId); 
                $(this).find('.autocomplete-ledgr-group').val(ledgerGroupName); 
                $(this).find('.ledger-group-id').val(ledgerGroupId); 
            });
        }
        $('#ledger_name').on("autocompleteselect", function(event, ui) {
            ledgerName = ui.item.label;  
            ledgerId = ui.item.id;  
            $('#ledger_name').val(ledgerName);
            updateLedgerValues();
        });

        $('#ledger_group_name').on("autocompleteselect", function(event, ui) {
            ledgerGroupName = ui.item.label;
            ledgerGroupId = ui.item.id;  

            $('#ledger_group_name').val(ledgerGroupName);  
            $('#ledger_group_id').val(ledgerGroupId);  
            updateLedgerValues();
        });

        $('#ledger_name, #ledger_id').on('input change', function() {
            updateLedgerValues(); 
            updateExistingRows(); 
        });

        $('#ledger_group_name, #ledger_group_id').on('input change', function() {
            updateLedgerValues();  
            updateExistingRows(); 
        });

        function updateRowIndices($tableBody) {
            if (!$tableBody || $tableBody.length === 0) return; 
            $tableBody.find('tr').each(function(index) {
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
                $(this).find('.add-address').toggle(index === 0);

                initializeLedgerAutocomplete($(this).find(".autocomplete-ledgr"), index);
                initializeLedgerGroupAutocomplete($(this).find(".autocomplete-ledgr-group"), index);
            });
        }

        function addNewRow($tableBody) {
            var rowCount = $tableBody.children().length;
            var $currentRow = $tableBody.find('tr:last');
            var $newRow = $currentRow.clone();
            $newRow.find('input').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    $(this).attr('name', name.replace(/\[\d+\]/, '[' + rowCount + ']'));
                }
                $(this).val(''); 
            });

            updateLedgerValues(); 
            if (ledgerName && ledgerId) {
                $newRow.find('.autocomplete-ledgr').val(ledgerName);
                $newRow.find('.ledger-id').val(ledgerId);
            }
            if (ledgerGroupName && ledgerGroupId) {
                $newRow.find('.autocomplete-ledgr-group').val(ledgerGroupName);
                $newRow.find('.ledger-group-id').val(ledgerGroupId);
            }
            $tableBody.append($newRow);
            updateRowIndices($tableBody); 

            feather.replace();  
            initializeLedgerAutocomplete($newRow.find(".autocomplete-ledgr"), rowCount); 
            initializeLedgerGroupAutocomplete($newRow.find(".autocomplete-ledgr-group"), rowCount);  
        }

        function fetchIfscDetails(ifscCode, $row) {
                if (!ifscCode) return; 
                $.ajax({
                    url: '/banks/ifsc/' + ifscCode,
                    method: 'GET',
                    success: function(data) {
                        if (data.status) {
                            $row.find('input[name*="[branch_name]"]').val(data.data.BRANCH);
                            $row.find('input[name*="[branch_address]"]').val(data.data.ADDRESS);
                        } else {
                            alert('Invalid IFSC code. Please try again.');
                            $row.find('input[name*="[branch_name]"]').val('');
                            $row.find('input[name*="[branch_address]"]').val('');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching IFSC details:', textStatus, errorThrown);
                        alert('An error occurred while fetching IFSC details. Please try again.');
                        $row.find('input[name*="[branch_name]"]').val('');
                        $row.find('input[name*="[branch_address]"]').val('');
                    }
                });
        }
        var $subStationBox = $('#sub-station-box');
        if ($subStationBox.children().length === 0) addNewRow($subStationBox);
        $subStationBox.on('click', '.add-address', function(e) {
            e.preventDefault();
            addNewRow($subStationBox);
        });

        $(document).on('click', '.delete-address', function(e) {
            e.preventDefault();
            var $row = $(this).closest('tr');
            var bankDetailId = $row.data('id'); 
            if (bankDetailId) {
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
                            url: '/banks/bank-detail/' + bankDetailId, 
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
                                    Swal.fire('Error!', response.message || 'Could not delete bank detail.', 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message || 'An error occurred while deleting the bank detail.', 'error');
                            }
                        });
                    }
                });
            } else {
                $row.remove();
                updateRowIndices();
            }
        });

        updateRowIndices($subStationBox);
        var $subCategoryBox = $('#sub-category-box');
        if ($subCategoryBox.children().length === 0) addNewRow($subCategoryBox);
        $subCategoryBox.on('click', '.add-address', function(e) {
            e.preventDefault();
            addNewRow($subCategoryBox);
        });
        updateRowIndices($subCategoryBox);
        $subCategoryBox.on('keyup', 'input.ifsc-code', function() {
            var ifscCode = $(this).val();
            var $row = $(this).closest('tr');
            clearTimeout($.data(this, 'timer'));
            var wait = setTimeout(function() {
                fetchIfscDetails(ifscCode, $row);
            }, 300);
            $(this).data('timer', wait);
        });
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
                                    code: item.code,
                                    item_id: item.id
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

        function initializeLedgerAutocomplete(selector, rowIndex) {
            initializeAutocomplete(selector, "{{ url('/search/ledger') }}", "ledger_id_" + rowIndex);
        }

        function initializeLedgerGroupAutocomplete(selector, rowIndex) {
            initializeAutocomplete(selector, "{{ url('/search/group') }}", "ledger_group_id_" + rowIndex);
        }

        initializeLedgerAutocomplete("#sub-category-box .autocomplete-ledgr", 0);
        initializeLedgerGroupAutocomplete("#sub-category-box .autocomplete-ledgr-group", 0);

        $('#sub-category-box').on("autocompleteselect", ".autocomplete-ledgr", function(event, ui) {
            var ledgerId = ui.item.id;
            if (ledgerId) {
                var $row = $(this).closest('tr');
                $row.find(".autocomplete-ledgr-group").val(""); 
                $row.find(".ledger-group-id").val("");  
                updateLedgerGroupAutocomplete(ledgerId, $row.find(".autocomplete-ledgr-group"));
            }
        });

        $('#sub-category-box').on("input", ".autocomplete-ledgr", function() {
            var $row = $(this).closest('tr');
            var ledgerGroupInput = $row.find(".autocomplete-ledgr-group");
            var ledgerGroupIdInput = $row.find(".ledger-group-id");
            if ($(this).val() === "") {
                ledgerGroupInput.val("");  
                ledgerGroupIdInput.val(""); 
                fetchAllGroups(ledgerGroupInput);  
            } else {
                ledgerGroupInput.val("");
                ledgerGroupIdInput.val("");
                ledgerGroupInput.autocomplete("option", "source", []);  
            }
        });

        function fetchAllGroups(ledgerGroupInput) {
            $.ajax({
                url: '/search/group',
                method: 'GET',
                success: function(data) {
                    if (data && Array.isArray(data)) {
                        ledgerGroupInput.autocomplete("option", "source", data.map(function(group) {
                            return {
                                label: group.name,
                                value: group.name,
                                id: group.id
                            };
                        }));
                    } else {
                        console.error("Unexpected data format:", data);
                    }
                },
                error: function() {
                    alert('An error occurred while fetching the groups.');
                }
            });
        }

        function updateLedgerGroupAutocomplete(ledgerId, ledgerGroupInput) {
        $.ajax({
            url: '/ledgers/' + ledgerId + '/groups',
            method: 'GET',
            success: function(data) {
                let autocompleteData = [];
                if (Array.isArray(data)) {
                    autocompleteData = data.map(item => ({
                        label: item.name,
                        value: item.name,
                        id: item.id
                    }));
                } 
                else if (data && typeof data === 'object') {
                    autocompleteData = [{
                        label: data.name,
                        value: data.name,
                        id: data.id
                    }];
                } 
                else {
                    console.error("Unexpected data format:", data);
                    return;
                }
                ledgerGroupInput.autocomplete("option", "source", autocompleteData);
            },
            error: function() {
                alert('An error occurred while fetching Ledger Groups.');
            }
        });
    }

        function initializeLedgerGroupsOnPageLoad() {
            $('#sub-category-box tr').each(function() {
                var ledgerId = $(this).find('input[name^="bank_details"][name$="[ledger_id]"]').val();
                if (ledgerId) {
                    var ledgerGroupInput = $(this).find(".autocomplete-ledgr-group");
                    updateLedgerGroupAutocomplete(ledgerId, ledgerGroupInput);
                }
            });
        }
        initializeLedgerGroupsOnPageLoad();
        updateRowIndices($subCategoryBox);
    });
</script>
@endsection
