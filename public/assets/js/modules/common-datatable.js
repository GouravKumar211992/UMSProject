// Define custom sorting type for "formatted-date"
$.fn.dataTable.ext.type.order['formatted-date-pre'] = function(data) {
    if (!data) return 0; // If data is undefined, return 0 for safe sorting

    // Parse date in the format "04 Nov, 2024" to "YYYY-MM-DD" for sorting
    const [day, month, year] = data.split(' ');
    const monthMap = {
        Jan: '01', Feb: '02', Mar: '03', Apr: '04', May: '05', Jun: '06',
        Jul: '07', Aug: '08', Sep: '09', Oct: '10', Nov: '11', Dec: '12'
    };

    // Ensure month is mapped correctly
    if (!monthMap[month]) return 0;

    return new Date(`${year}-${monthMap[month]}-${day.padStart(2, '0')}`).getTime();
};

function initializeDataTable(selector, ajaxUrl, columns, filters = {}, exportTitle = 'Data', exportColumns = [], defaultOrder = [], pdfPageOrientation = 'portrait') {
    var table = $(selector);
    if (table.length) {
        table.DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: ajaxUrl,
                data: function(d) {
                    // Loop through each filter key-value pair
                    $.each(filters, function(key, value) {
                        d[key] = $(value).val();  // Get the value from the HTML input
                    });
                }
            },
            order: defaultOrder,
            columns: columns,
            dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            buttons: [
                {
                    extend: 'collection',
                    className: 'btn btn-outline-secondary dropdown-toggle',
                    text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + ' Export',
                    buttons: [
                        { extend: 'print', text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + ' Print', className: 'dropdown-item', title: exportTitle, exportOptions: { columns: exportColumns }},
                        { extend: 'csv', text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + ' CSV', className: 'dropdown-item', title: exportTitle, exportOptions: { columns: exportColumns }},
                        { extend: 'excel', text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + ' Excel', className: 'dropdown-item', title: exportTitle, exportOptions: { columns: exportColumns }},
                        { extend: 'pdf', text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + ' PDF', className: 'dropdown-item', title: exportTitle, exportOptions: { columns: exportColumns }, orientation: pdfPageOrientation},
                        { extend: 'copy', text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + ' Copy', className: 'dropdown-item', title: exportTitle, exportOptions: { columns: exportColumns }},
                    ],
                    init: function(api, node, config) {
                        $(node).removeClass('btn-secondary').parent().removeClass('btn-group');
                        setTimeout(function() {
                            $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                        }, 50);
                    }
                }
            ],
            drawCallback: function() {
                feather.replace(); 
                $(document).on('click', '.myrequesttablecbox tbody tr', (e) => {
                    $('tr').removeClass('trselected');
                    $(e.target).closest('tr').addClass('trselected');
                });

                $(document).on('keydown', function(e) { 
                 if (e.which == 38) {
                   $('.trselected').prev('tr').addClass('trselected').siblings().removeClass('trselected');
                 } else if (e.which == 40) {
                   $('.trselected').next('tr').addClass('trselected').siblings().removeClass('trselected');
                 } 
                 // $('html, body').scrollTop($('.trselected').offset().top - 100); 
                });
            },
            language: {
                paginate: { previous: '&nbsp;', next: '&nbsp;' }
            },
            search: { caseInsensitive: true }
        });
    }
}
