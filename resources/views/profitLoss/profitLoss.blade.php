@php
    use App\Helpers\CurrencyHelper;
@endphp

@extends('layouts.app')

@section('styles')
@endsection

@section('content')
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-6  mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Profit & Loss Account</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a></li>
                                    <li class="breadcrumb-item active">Profit & Loss View</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-end col-md-6 mb-50 mb-sm-0">
                    <div class="form-group breadcrumb-right">
                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle exportcustomdrop" data-bs-toggle="dropdown">
                            <i data-feather="share"></i> Export
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript:;" onclick="exportPLLevel(1)">
                                <i data-feather="upload" class="me-50"></i>
                                <span>Level1</span>
                            </a>
                            <a class="dropdown-item" href="javascript:;" onclick="exportPLLevel(2)">
                                <i data-feather="upload" class="me-50"></i>
                                <span>Level2</span>
                            </a>
                        </div>
                        <button class="btn btn-warning btn-sm mb-50 mb-sm-0" data-bs-target="#filter" data-bs-toggle="modal"><i data-feather="filter"></i> Filter</button>
                        <button class="btn btn-primary  btn-sm mb-50 mb-sm-0"><i data-feather="printer"></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="newheader">
                                            <div>
                                                <h4 class="card-title text-theme text-dark" id="company_name">All Companies</h4>
                                                <p class="card-text"><span id="startDate"></span> to <span id="endDate"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-sm-end">
                                        <a href="#" class="trail-exp-allbtnact" id="expand-all">
                                            <i data-feather='plus-circle'>

                                            </i>
                                            Expand All
                                        </a>
                                        <a href="#" class="trail-col-allbtnact" id="collapse-all">
                                            <i data-feather='minus-circle'></i> Collapse All
                                        </a>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 earn-dedtable trail-balancefinance pandlac trailbalnewdesfinance">
                                        <div class="table-responsive">
                                            <table class="table border">
                                                <thead>
                                                    <tr>
                                                        <th>Particulars</th>
                                                        <th class="text-end">Amount</th>
                                                        <th>Particulars</th>
                                                        <th class="text-end">Amount</th>
                                                    </tr>
                                                </thead>
												<tbody>
                                                    <tr>
													    <td colspan="2" width="50%" class="liabilitysec">
                                                        <table width="100%">
                                                            <tr class="trail-bal-tabl-none" id="1">
                                                                <input type="hidden" id="check1">
                                                                <td>
                                                                    <a href="#" class="trail-open-new-listplus-btn expand exp1" id="1" data-id="1">
                                                                        <i data-feather='plus-circle'></i>
                                                                    </a>
                                                                    <a href="#"  class="trail-open-new-listminus-btn collapse" style="display:none">
                                                                        <i data-feather='minus-circle'></i>
                                                                    </a> Opening Stock
                                                                </td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="opening">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none" id="2">
                                                                <input type="hidden" id="check2">
                                                                <td>
                                                                    <a href="#" class="trail-open-new-listplus-btn expand exp2" id="2" data-id="2">
                                                                        <i data-feather='plus-circle'></i>
                                                                    </a>
                                                                    <a href="#" class="trail-open-new-listminus-btn collapse" style="display:none">
                                                                        <i data-feather='minus-circle'></i>
                                                                    </a> Purchase Accounts
                                                                </td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="purchase">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none" id="3">
                                                                <input type="hidden" id="check3">
                                                                <td>
                                                                    <a href="#" class="trail-open-new-listplus-btn expand exp3" id="3" data-id="3">
                                                                        <i data-feather='plus-circle'></i>
                                                                    </a>
                                                                    <a href="#"  class="trail-open-new-listminus-btn collapse" style="display:none">
                                                                        <i data-feather='minus-circle'></i>
                                                                    </a> Direct Expenses
                                                                </td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="directExpense">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>Gross Profit c/o</td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end grossProfit">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end subTotal">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>Gross Loss b/f</td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end grossLoss">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none" id="4">
                                                                <input type="hidden" id="check4">
                                                                <td>
                                                                    <a href="#" class="trail-open-new-listplus-btn expand exp4" id="4" data-id="4">
                                                                        <i data-feather='plus-circle'></i>
                                                                    </a>
                                                                    <a href="#"  class="trail-open-new-listminus-btn collapse" style="display:none">
                                                                        <i data-feather='minus-circle'></i>
                                                                    </a> Indirect Expenses
                                                                </td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="indirectExpense">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>Net Profit</td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="netProfit">0</td>
                                                            </tr>
                                                        </table>
                                                    </td>

                                                        <td colspan="2" width="50%" class="liabilitysec border-0">
                                                        <table width="100%">
                                                            <tr class="trail-bal-tabl-none" id="5">
                                                                <input type="hidden" id="check5">
                                                                <td>
                                                                    <a href="#" class="trail-open-new-listplus-btn expand exp5" id="5" data-id="5">
                                                                        <i data-feather='plus-circle'></i>
                                                                    </a>
                                                                    <a href="#"  class="trail-open-new-listminus-btn collapse" style="display:none">
                                                                        <i data-feather='minus-circle'></i>
                                                                    </a>
                                                                        Sales Accounts
                                                                </td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="salesAccount">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none" id="6">
                                                                <input type="hidden" id="check6">
                                                                <td>
                                                                    <a href="#" class="trail-open-new-listplus-btn expand exp6" id="6" data-id="6">
                                                                        <i data-feather='plus-circle'></i>
                                                                    </a>
                                                                    <a href="#"  class="trail-open-new-listminus-btn collapse" style="display:none">
                                                                        <i data-feather='minus-circle'></i>
                                                                    </a> Direct Income
                                                                </td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="directIncome">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>
                                                                    <a href="#" class="trail-open-new-listplus-btn">
                                                                        <i data-feather='plus-circle'></i>
                                                                    </a>Closing Stock
                                                                </td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="closing">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>Gross Loss c/o</td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end grossLoss">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end subTotal">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>Gross Profit b/f</td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end grossProfit">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none" id="7">
                                                                <input type="hidden" id="check7">
                                                                <td>
                                                                    <a href="#" class="trail-open-new-listplus-btn expand exp7" id="7" data-id="7">
                                                                        <i data-feather='plus-circle'></i>
                                                                    </a>
                                                                    <a href="#"  class="trail-open-new-listminus-btn collapse" style="display:none">
                                                                        <i data-feather='minus-circle'></i>
                                                                    </a>Indirect Income
                                                                </td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="indirectIncome">0</td>
                                                            </tr>
                                                            <tr class="trail-bal-tabl-none">
                                                                <td>Net Loss</td>
                                                                <td>&nbsp;</td>
                                                                <td class="text-end" id="netLoss">0</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    </tr>
												</tbody>

                                                <tfoot class="mt-3">
                                                    <tr>
                                                        <td colspan="2" class="liabilitysec">
                                                            <table width="100%">
                                                                <tr>
                                                                    <td>Total</td>
                                                                    <td>&nbsp;</td>
                                                                    <td class="text-end overAllTotal">0</td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td colspan="2" class="liabilitysec border-0">
                                                            <table width="100%">
                                                                <tr>
                                                                    <td>Total</td>
                                                                    <td>&nbsp;</td>
                                                                    <td class="text-end overAllTotal">0</td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tfoot>
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
    </div>
    <!-- END: Content-->


    <div class="modal modal-slide-in fade filterpopuplabel" id="filter">
        <div class="modal-dialog sidebar-sm">
            <form class="add-new-record modal-content pt-0">
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Apply Filter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="fp-range">Select Period</label>
                        <input type="text" id="fp-range" class="form-control flatpickr-range bg-white"
                            placeholder="YYYY-MM-DD to YYYY-MM-DD" />
                    </div>

                    <div class="mb-1">
                                    <label class="form-label">Company</label>
                                    <select id="organization_id" class="form-select select2" multiple>
                                        <option value="" disabled>Select</option>
                                        @foreach($companies as $organization)
                                            <option value="{{ $organization->organization->id }}"
                                                {{ $organization->organization->id == $organizationId ? 'selected' : '' }}>
                                                {{ $organization->organization->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="mb-1">
                                    <label class="form-label">Currency</label>
                                    <select id="currency" class="form-select select2">
                                        <option value="org"> {{strtoupper(CurrencyHelper::getOrganizationCurrency()->short_name) ?? ""}} (Organization)</option>
                                        <option value="comp">{{strtoupper(CurrencyHelper::getCompanyCurrency()->short_name)??""}} (Company)</option>
                                        <option value="group">{{strtoupper(CurrencyHelper::getGroupCurrency()->short_name)??""}} (Group)</option>
                                    </select>
                                </div>


                    {{-- <div class="mb-1">
                        <label class="form-label">Group</label>
                        <select class="form-select">
                            <option>Select</option>
                        </select>
                    </div>

                    <div class="mb-1">
                        <label class="form-label">Ledger Name</label>
                        <select class="form-select">
                            <option>Select</option>
                        </select>
                    </div> --}}
                </div>
                <div class="modal-footer justify-content-start">
                    <button type="button" class="btn btn-primary data-submit mr-1 apply-filter">Apply</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function exportPLLevel(level){
        var obj={ date:$('#fp-range').val(),'_token':'{!!csrf_token()!!}',level:level};
        var selectedValues = $('#organization_id').val() || [];
        var filteredValues = selectedValues.filter(function(value) {
            return value !== null && value.trim() !== '';
        });
        if (filteredValues.length>0) {
            obj.organization_id=filteredValues
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type    :"POST",
            url     :"{{route('finance.exportPLLevel')}}",
            data: obj,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data, status, xhr) {
                var link = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                link.href = url;
                link.download = 'plReport.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            error: function(xhr, status, error) {
                console.log('Export failed:', error);
            }
        });
    }

    $(document).ready(function() {

        getInitialGroups();

        // Filter record
        $(".apply-filter").on("click", function() {
            // Hide the modal
            $(".modal").modal("hide");
            $('#collapse-all').click();
            $('#tableData').html('');
            getInitialGroups();

            var selectedValues = $('#organization_id').val() || [];
            if (selectedValues.length === 0) {
                $('#company_name').text('All Companies');
            } else {
                $('#company_name').text(
                    $('#organization_id option:selected')
                        .map(function() {
                            return $(this).text();
                        })
                        .get()
                        .join(', ')
                );
            }
        })

        function getInitialGroups() {

            var obj={ date:$('#fp-range').val(),'_token':'{!!csrf_token()!!}'};
            var selectedValues = $('#organization_id').val() || [];
            var filteredValues = selectedValues.filter(function(value) {
                return value !== null && value.trim() !== '';
            });
            if (filteredValues.length>0) {
                obj.organization_id=filteredValues
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: "{{ route('finance.getPLInitialGroups') }}",
                dataType: "JSON",
                data: obj,
                success: function(data) {
                    $('#opening').text((data['data']['opening']).toLocaleString('en-IN'));
                    $('#purchase').text((data['data']['purchase']).toLocaleString('en-IN'));
                    $('#directExpense').text((data['data']['directExpense']).toLocaleString('en-IN'));
                    $('#indirectExpense').text((data['data']['indirectExpense']).toLocaleString('en-IN'));
                    $('#salesAccount').text((data['data']['salesAccount']).toLocaleString('en-IN'));
                    $('#directIncome').text((data['data']['directIncome']).toLocaleString('en-IN'));
                    $('#indirectIncome').text((data['data']['indirectIncome']).toLocaleString('en-IN'));
                    $('.grossProfit').text((data['data']['grossProfit']).toLocaleString('en-IN'));
                    $('.subTotal').text((data['data']['subTotal']).toLocaleString('en-IN'));
                    $('.grossLoss').text((data['data']['grossLoss']).toLocaleString('en-IN'));
                    $('.overAllTotal').text((data['data']['overAllTotal']).toLocaleString('en-IN'));
                    $('#netProfit').text((data['data']['netProfit']).toLocaleString('en-IN'));
                    $('#netLoss').text((data['data']['netLoss']).toLocaleString('en-IN'));
                    $('#startDate').text((data['startDate']).toLocaleString('en-IN'));
                    $('#endDate').text((data['endDate']).toLocaleString('en-IN'));

                    if (feather) {
                        feather.replace({
                            width: 14,
                            height: 14
                        });
                    }
                }
            });
        }

        function parseNumberWithCommas(str) {
            return parseFloat(str.replace(/,/g, ""));
        }

        function getIncrementalPadding(parentPadding) {
            return parentPadding + 10; // Increase padding by 10px
        }

        $(document).on('click', '.expand', function() {
            const id = $(this).attr('data-id');
            const parentPadding = parseInt($(this).closest('td').css('padding-left'));

            if ($('#check' + id).val() == "") {

                var obj={ id:id,date:$('#fp-range').val(),currency:$('#currency').val(),'_token':'{!!csrf_token()!!}'};
                var selectedValues = $('#organization_id').val() || [];
                var filteredValues = selectedValues.filter(function(value) {
                    return value !== null && value.trim() !== '';
                });
                if (filteredValues.length>0) {
                    obj.organization_id=filteredValues
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('finance.getPLGroupLedgers') }}",
                    dataType: "JSON",
                    data: obj,
                    success: function(data) {
                        $('#check'+id).val(id);
                        if (data['data'].length > 0) {
                            let html = '';
                            for (let i = 0; i < data['data'].length; i++) {
                                const padding = getIncrementalPadding(parentPadding);
                                const closing = data['data'][i].details_sum_debit_amt - data['data'][i].details_sum_credit_amt;

                                const ledgerUrl="{{ url('trailLedger') }}/"+data['data'][i].id;

                                html += `
                                    <tr class="pandlosssubaccount parent-${id}">
                                        <td>
                                            <a href='${ledgerUrl}'>
                                            <i data-feather='arrow-right'></i>${data['data'][i].name}</a>
                                        </td>
                                        <td class="text-end">${parseFloat(closing < 0 ? -closing : closing).toLocaleString('en-IN')}</td>
                                        <td>&nbsp;</td>
                                    </tr>`;
                            }
                            $('#check'+id).closest('tr').after(html);
                        }

                        if (feather) {
                            feather.replace({
                                width: 14,
                                height: 14
                            });
                        }
                    }
                });
            }

            // Expand all direct children of this row
            $('.parent-' + id).show();
            $(this).hide();
            $(this).siblings('.collapse').show();
        });

        $(document).on('click', '.collapse', function() {
            const id = $(this).closest('tr').attr('id');

            // Collapse all children of this row recursively and hide their expand icons
            function collapseChildren(parentId) {
                $(`.parent-${parentId}`).each(function() {
                    const childId = $(this).attr('id');
                    $(this).hide(); // Hide the child row
                    $(this).find('.collapse').hide(); // Hide the collapse icon
                    $(this).find('.expand').show(); // Show the expand icon
                    collapseChildren(childId); // Recursively collapse the child's children
                });
            }

            collapseChildren(id);

            $(this).hide();
            $(this).siblings('.expand').show();
        });

        $('#expand-all').click(function() {
            $('.expand').hide();

            var trIds = $('tbody tr').map(function() {
                return this.id; // Return the ID of each tr element
            }).get().filter(function(id) {
                return id !== "" && $('#check' + id).val() == ""; // Filter out any empty IDs
            });

            if (trIds.length>0) {

                var obj={ ids:trIds,date:$('#fp-range').val(),currency:$('#currency').val(),'_token':'{!!csrf_token()!!}'};
                var selectedValues = $('#organization_id').val() || [];
                var filteredValues = selectedValues.filter(function(value) {
                    return value !== null && value.trim() !== '';
                });
                if (filteredValues.length>0) {
                    obj.organization_id=filteredValues
                }

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type    :"POST",
                    url     :"{{route('finance.getPLGroupLedgersMultiple')}}",
                    dataType:"JSON",
                    data    :obj,
                    success: function(res) {
                        console.log(res);
                        if (res['data'].length > 0) {
                            res['data'].forEach(data => {
                                $('#check'+data['id']).val(data['id']);
                                const parentPadding = parseInt($('.exp'+data['id']).closest('td').css('padding-left'));

                                if (data['data'].length > 0) {
                                    let html = '';
                                    for (let i = 0; i < data['data'].length; i++) {
                                        const padding = getIncrementalPadding(parentPadding);
                                        const closing = data['data'][i].details_sum_debit_amt - data['data'][i].details_sum_credit_amt;

                                        const ledgerUrl="{{ url('trailLedger') }}/"+data['data'][i].id;

                                        html += `
                                            <tr class="pandlosssubaccount parent-${data['id']}">
                                                <td>
                                                    <a href='${ledgerUrl}'>
                                                    <i data-feather='arrow-right'></i>${data['data'][i].name}</a>
                                                </td>
                                                <td class="text-end">${parseFloat(closing < 0 ? -closing : closing).toLocaleString('en-IN')}</td>
                                                <td>&nbsp;</td>
                                            </tr>`;
                                    }
                                    $('#check'+data['id']).closest('tr').after(html);
                                }
                            });
                        }

                        if (feather) {
                            feather.replace({
                                width: 14,
                                height: 14
                            });
                        }
                    }
                });
            }

            $('.collapse').show();
            $('.pandlosssubaccount').show();
        });

        // Collapse All rows
        $('#collapse-all').click(function() {
            $('tbody tr').each(function() {
                const id = $(this).attr('id');
                if (id) {
                    collapseChildren(id); // Collapse all children for each parent row
                }
            });
            $('.collapse').hide();
            $('.expand').show();
        });

        // Recursive collapse function
        function collapseChildren(parentId) {
            $(`.parent-${parentId}`).each(function() {
                const childId = $(this).attr('id');
                $(this).hide(); // Hide the child row
                $(this).find('.collapse').hide(); // Hide the collapse icon
                $(this).find('.expand').show(); // Show the expand icon
                collapseChildren(childId); // Recursively collapse the child's children
            });
        }
    });
    // selected arrow using down, up key
    $(document).ready(function() {
        let selectedRow = null;

        function setSelectedRow(row) {
            if (selectedRow) {
                selectedRow.removeClass('trselected');
            }
            selectedRow = row;
            selectedRow.addClass('trselected');
        }

        function expandRow(row) {
            const id = row.attr('id');
            $('.parent-' + id).show();
            row.find('.expand').hide();
            row.find('.collapse').show();
        }

        function collapseRow(row) {
            const id = row.attr('id');
            collapseChildren(id);
            row.find('.expand').show();
            row.find('.collapse').hide();
        }

        function collapseChildren(parentId) {
            $(`.parent-${parentId}`).each(function() {
                const childId = $(this).attr('id');
                $(this).hide();
                $(this).find('.collapse').hide();
                $(this).find('.expand').show();
                collapseChildren(childId);
            });
        }
    });
</script>
@endsection
