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
                        <h2 class="content-header-title float-start mb-0">Balance Sheet</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="`    breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="index.html">Finance</a></li>
                                <li class="breadcrumb-item active">Balance Sheet View</li>
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
                        <a class="dropdown-item" href="javascript:;" onclick="exportBalanceSheet(1)">
                            <i data-feather="upload" class="me-50"></i>
                            <span>Level1</span>
                        </a>
                        <a class="dropdown-item" href="javascript:;" onclick="exportBalanceSheet(2)">
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
                                                <h4 class="card-title text-theme text-dark" id="company_name">{{ $organization }}</h4>
                                                <p class="card-text"><span id="endDate"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-sm-end">
                                        <a href="#" class="trail-exp-allbtnact" id="expand-all">
                                            <i data-feather='plus-circle'></i> Expand All
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
                                                        <th>Liabilities</th>
                                                        <th class="text-end">Amount</th>
                                                        <th>Assets</th>
                                                        <th class="text-end">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="2" width="50%" class="liabilitysec">
                                                            <table width="100%" id="liabilitiesDiv"></table>
                                                        </td>


                                                        <td colspan="2" width="50%" class="liabilitysec border-0">
                                                            <table width="100%" id="assetsDiv"></table>
                                                        </td>
                                                    </tr>
                                                </tbody>


                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" class="liabilitysec">
                                                            <table width="100%">
                                                                    <tr>
                                                                        <td>Total</td>
                                                                        <td>&nbsp;</td>
                                                                        <td class="text-end" id="liabilities_total">0</td>
                                                                    </tr>
                                                            </table>
                                                        </td>
                                                        <td colspan="2" class="liabilitysec border-0">
                                                            <table width="100%">
                                                                    <tr>
                                                                        <td>Total</td>
                                                                        <td>&nbsp;</td>
                                                                        <td class="text-end" id="assets_total">0</td>
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
                      <label class="form-label" for="fp-range">Select Date</label>
                      <input type="date" id="fp-range" class="form-control bg-white" placeholder="YYYY-MM-DD" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"/>
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
                </div>  --}}

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
    function exportBalanceSheet(level){
        var obj={ date:"1970-01-01 to "+$('#fp-range').val(),currency:$('#currency').val(),'_token':'{!!csrf_token()!!}',filter_organization:$('#filter-organization').val(),level:level};
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
            url     :"{{route('finance.exportBalanceSheet')}}",
            data: obj,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data, status, xhr) {
                var link = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                link.href = url;
                link.download = 'balanceSheetReport.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            error: function(xhr, status, error) {
                console.log('Export failed:', error);
            }
        });
    }

    $(document).ready(function () {

        var reservesSurplusValue=0;

        $(document).ready(function() {
            getInitialGroups();
        });

        // Filter record
		$(".apply-filter").on("click", function () {
			// Hide the modal
			$(".modal").modal("hide");
            $('.collapse').click();
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
            $('#liabilitiesDiv').html('');
            $('#assetsDiv').html('');

            var obj={ date:"1970-01-01 to "+$('#fp-range').val(),currency:$('#currency').val(),'_token':'{!!csrf_token()!!}',filter_organization:$('#filter-organization').val()};
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
				url     :"{{route('finance.balanceSheetInitialGroups')}}",
				dataType:"JSON",
				data    :obj,
                success: function(data) {
                    reservesSurplusValue=data['reservesSurplus'];
                    if (data['liabilitiesData'].length > 0) {
                        let html = '';
                        for (let i = 0; i < data['liabilitiesData'].length; i++) {
                            const groupUrl="{{ route('trial_balance') }}/"+data['liabilitiesData'][i].id;
                            if (data['liabilitiesData'][i].name=="Reserves & Surplus") {
                                html +=`<tr class="trail-bal-tabl-none" id="${data['liabilitiesData'][i].id}">
                                        <input type="hidden" id="check${data['liabilitiesData'][i].id}">
                                        <td>
                                            <a href="#" class="trail-open-new-listplus-btn expand exp${data['liabilitiesData'][i].id}" data-id="${data['liabilitiesData'][i].id}"><i data-feather='plus-circle'></i></a>
                                            <a href="#" class="trail-open-new-listminus-btn collapse"><i data-feather='minus-circle'></i></a>
                                            <span id="name${data['liabilitiesData'][i].id}">${data['liabilitiesData'][i].name}</span>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td class="text-end liabilitiesAmount">${parseFloat(data['reservesSurplus']).toLocaleString('en-IN')}</td>
                                    </tr>`;
                            } else{
                                html +=`<tr class="trail-bal-tabl-none" id="${data['liabilitiesData'][i].id}">
                                        <input type="hidden" id="check${data['liabilitiesData'][i].id}">
                                        <td>
                                            <a href="#" class="trail-open-new-listplus-btn expand exp${data['liabilitiesData'][i].id}" data-id="${data['liabilitiesData'][i].id}"><i data-feather='plus-circle'></i></a>
                                            <a href="#" class="trail-open-new-listminus-btn collapse"><i data-feather='minus-circle'></i></a>
                                            <a href="${groupUrl}">
                                                ${data['liabilitiesData'][i].name}
                                            </a>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td class="text-end liabilitiesAmount">${parseFloat(data['liabilitiesData'][i].closing).toLocaleString('en-IN')}</td>
                                    </tr>`;
                            }
                        }
                        $('#liabilitiesDiv').append(html);
                    }

                    if (data['assetsData'].length > 0) {
                        let html = '';
                        for (let i = 0; i < data['assetsData'].length; i++) {
                            const groupUrl="{{ route('trial_balance') }}/"+data['assetsData'][i].id;

                            html +=`<tr class="trail-bal-tabl-none" id="${data['assetsData'][i].id}">
                                        <input type="hidden" id="check${data['assetsData'][i].id}">
                                        <td>
                                            <a href="#" class="trail-open-new-listplus-btn expand exp${data['assetsData'][i].id}" data-id="${data['assetsData'][i].id}"><i data-feather='plus-circle'></i></a>
                                            <a href="#" class="trail-open-new-listminus-btn collapse"><i data-feather='minus-circle'></i></a>
                                            <a href="${groupUrl}">
                                                ${data['assetsData'][i].name}
                                            </a>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td class="text-end assetsAmount">${parseFloat(data['assetsData'][i].closing).toLocaleString('en-IN')}</td>
                                    </tr>`;
                        }
                        $('#assetsDiv').append(html);
                    }

                    $('#startDate').text(data['startDate']);
                    $('#endDate').text(data['endDate']);

                    if (feather) {
                        feather.replace({
                            width: 14,
                            height: 14
                        });
                    }

                    calculate_cr_dr();
                }
            });
        }

        function calculate_cr_dr() {
            let liabilities_sum = 0;
            $('.liabilitiesAmount').each(function() {
                const value = parseNumberWithCommas($(this).text()) || 0;
                liabilities_sum = parseFloat(parseFloat(liabilities_sum + value).toFixed(2));
            });
            $('#liabilities_total').text(liabilities_sum.toLocaleString('en-IN'));

            let assets_sum = 0;
            $('.assetsAmount').each(function() {
                const value = parseNumberWithCommas($(this).text()) || 0;
                assets_sum = parseFloat(parseFloat(assets_sum + value).toFixed(2));
            });
            $('#assets_total').text(assets_sum.toLocaleString('en-IN'));
        }

        function parseNumberWithCommas(str) {
            return parseFloat(str.replace(/,/g, ""));
        }

        $(document).on('click', '.expand', function() {
            const id = $(this).attr('data-id');
            if ($('#name' + id).text()=="Reserves & Surplus") {
                let html= `<tr class="pandlosssubaccount parent-${id}">
                            <td>Profit & Loss</td>
                            <td class="text-end">${parseFloat(reservesSurplusValue).toLocaleString('en-IN')}</td>
                            <td>&nbsp;</td>
                        </tr>`;
                $('#' + id).closest('tr').after(html);
            } else {
                if ($('#check' + id).val() == "") {
                    var obj={ id:id,date:"1970-01-01 to "+$('#fp-range').val(),currency:$('#currency').val(),'_token':'{!!csrf_token()!!}'};
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
                        url     :"{{route('finance.getBalanceSheetLedgers')}}",
                        dataType:"JSON",
                        data    :obj,
                        success: function(data) {
                            $('#check' + id).val(id);
                            if (data['data'].length > 0) {
                                let html = '';
                                for (let i = 0; i < data['data'].length; i++) {
                                    const ledgerUrl="{{ url('trailLedger') }}/"+data['data'][i].id;
                                    html += `<tr class="pandlosssubaccount parent-${id}">
                                                <td>
                                                    <a href='${ledgerUrl}'>
                                                        <i data-feather='arrow-right'></i>${data['data'][i].name}
                                                    </a>
                                                </td>
                                                <td class="text-end">${parseFloat(data['data'][i].closing).toLocaleString('en-IN')}</td>
                                                <td>&nbsp;</td>
                                            </tr>`;
                                }
                                $('#' + id).closest('tr').after(html);
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

                var obj={ ids:trIds,date:"1970-01-01 to "+$('#fp-range').val(),currency:$('#currency').val(),'_token':'{!!csrf_token()!!}'};
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
                    url     :"{{route('finance.getBalanceSheetLedgersMultiple')}}",
                    dataType:"JSON",
                    data    :obj,
                    success: function(res) {
                        console.log(res);
                        if (res['data'].length > 0) {
                            res['data'].forEach(data => {
                                $('#check'+data['id']).val(data['id']);
                                const parentPadding = parseInt($('.exp'+data['id']).closest('td').css('padding-left'));

                                if ($('#name' + data['id']).text()=="Reserves & Surplus") {
                                    let html = `<tr class="pandlosssubaccount parent-${data['id']}">
                                                <td>Profit & Loss</td>
                                                <td class="text-end">${parseFloat(reservesSurplusValue).toLocaleString('en-IN')}</td>
                                                <td>&nbsp;</td>
                                            </tr>`;
                                    $('#' + data['id']).closest('tr').after(html);
                                } else {
                                    if (data['data'].length > 0) {
                                        let html = '';
                                        for (let i = 0; i < data['data'].length; i++) {
                                            const ledgerUrl="{{ url('trailLedger') }}/"+data['data'][i].id;
                                            html += `<tr class="pandlosssubaccount parent-${data['id']}">
                                                        <td>
                                                            <a href='${ledgerUrl}'>
                                                                <i data-feather='arrow-right'></i>${data['data'][i].name}
                                                            </a>
                                                        </td>
                                                        <td class="text-end">${parseFloat(data['data'][i].closing).toLocaleString('en-IN')}</td>
                                                        <td>&nbsp;</td>
                                                    </tr>`;
                                        }
                                        $('#'+data['id']).closest('tr').after(html);
                                    }
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
                console.log("id in collapse",id);
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
    $(document).ready(function () {
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

    // Arrow key navigation
    // $(document).keydown(function (e) {
    //     const rows = $('tbody tr');
    //     if (rows.length === 0) return;

    //     let currentIndex = rows.index(selectedRow);
    //     let nextIndex = currentIndex;

    //     switch (e.which) {
    //         case 38: // Up arrow key
    //             if (currentIndex > 0) {
    //                 nextIndex = currentIndex - 1;
    //                 while (nextIndex >= 0 && rows.eq(nextIndex).is(':hidden')) {
    //                     nextIndex--;
    //                 }
    //                 if (nextIndex >= 0) {
    //                     setSelectedRow(rows.eq(nextIndex));
    //                 }
    //             }
    //             break;
    //         case 40: // Down arrow key
    //             if (currentIndex < rows.length - 1) {
    //                 nextIndex = currentIndex + 1;
    //                 while (nextIndex < rows.length && rows.eq(nextIndex).is(':hidden')) {
    //                     nextIndex++;
    //                 }
    //                 if (nextIndex < rows.length) {
    //                     setSelectedRow(rows.eq(nextIndex));
    //                 }
    //             }
    //             break;
    //         case 37: // Left arrow key
    //             if (selectedRow) {
    //                 collapseRow(selectedRow);
    //             }
    //             break;
    //         case 39: // Right arrow key
    //             if (selectedRow) {
    //                 expandRow(selectedRow);
    //             }
    //             break;
    //     }
    // });

    // // Initial row selection
    // $('tbody tr').click(function () {
    //     setSelectedRow($(this));
    // });

});
</script>
@endsection
