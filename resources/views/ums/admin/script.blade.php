<!-- Include necessary JS files -->
<script src="../../../app-assets/vendors/js/vendors.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
<script src="../../../app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
<script src="../../../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<script src="../../../app-assets/vendors/js/forms/select/select2.full.min.js"></script>


<!-- BEGIN: Theme JS -->
<script src="../../../app-assets/js/core/app-menu.js"></script>
<script src="../../../app-assets/js/core/app.js"></script>
<!-- END: Theme JS -->
<script src="../../../app-assets/vendors/js/charts/apexcharts.min.js"></script>
    <script src="../../../app-assets/vendors/js/charts/chart.min.js"></script>
    <script src="../../../app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
<!-- BEGIN: Page JS -->
<script src="../../../app-assets/js/scripts/cards/card-advance.js"></script>
<script src="../../../app-assets/js/scripts/forms/pickers/form-pickers.js"></script>
<script src="../../../app-assets/js/scripts/charts/chart-chartjs.js"></script>
<script src="../../../app-assets/js/scripts/charts/chart-apex.js"></script>
<script src="../../../app-assets/js/scripts/cards/card-statistics.js"></script>
<script src="../../../app-assets/js/scripts/pages/dashboard-ecommerce.js"></script>
<!-- END: Page JS -->
{{-- rekha --}}
<script src="../../../app-assets/js/scripts/forms/form-select2.js"></script>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    });
</script>
<!-- Include the necessary script for Chart.js (Place this in the head section or before closing </body>) -->


<!-- Your existing code for modal, tables, etc. -->

{{-- <script>
    $(document).ready(function () {
        // Initialize DataTable only once (Your existing DataTable code remains unchanged)
        var dt_initialized = false;
        $('#myModal').on('shown.bs.modal', function () {
            if (!dt_initialized) {
                var dt_basic_table = $('.datatables-basic'),
                    assetPath = '../../../app-assets/';

                if ($('body').attr('data-framework') === 'laravel') {
                    assetPath = $('body').attr('data-asset-path');
                }

                var dt_basic = $('#myTable').DataTable({
                    order: [
                        [0, 'asc']
                    ],
                    dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-2"p>>',
                    displayLength: 7,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    buttons: [{
                        extend: 'collection',
                        className: 'btn btn-outline-secondary dropdown-toggle',
                        text: feather.icons['share'].toSvg({
                            class: 'font-small-4 mr-50'
                        }) + 'Export',
                        buttons: [{
                                extend: 'print',
                                text: feather.icons['printer'].toSvg({
                                    class: 'font-small-4 mr-50'
                                }) + 'Print',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'csv',
                                text: feather.icons['file-text'].toSvg({
                                    class: 'font-small-4 mr-50'
                                }) + 'Csv',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'excel',
                                text: feather.icons['file'].toSvg({
                                    class: 'font-small-4 mr-50'
                                }) + 'Excel',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'pdf',
                                text: feather.icons['clipboard'].toSvg({
                                    class: 'font-small-4 mr-50'
                                }) + 'Pdf',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            },
                            {
                                extend: 'copy',
                                text: feather.icons['copy'].toSvg({
                                    class: 'font-small-4 mr-50'
                                }) + 'Copy',
                                className: 'dropdown-item',
                                exportOptions: {
                                    columns: [0, 1, 2, 3, 4]
                                }
                            }
                        ]
                    }],
                    language: {
                        paginate: {
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        }
                    }
                });

                dt_initialized = true; // Flag to track if DataTable is initialized
            }
        });
    });

    // Initialize the charts once the DOM is fully loaded
    $(document).ready(function () {
        // Line Chart
        var ctx1 = document.getElementById('leavebar-chart').getContext('2d');
        var leaveChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'], // Change labels as needed
                datasets: [{
                    label: 'Applications Submitted',
                    data: [65, 59, 80, 81, 56, 55], // Example data
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Donut Chart
        var ctx2 = document.getElementById('donut-opentask').getContext('2d');
        var donutChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Failed'],
                datasets: [{
                    label: 'Tasks',
                    data: [300, 50, 100], // Example data
                    backgroundColor: ['#36a2eb', '#ff6384', '#ffcd56']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script> --}}

    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS--> 

    <!-- END: Page JS-->




<!-- END: Theme JS -->




    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
		$(function () { 

  var dt_basic_table = $('.datatables-basic'),
    dt_date_table = $('.dt-date'),
    dt_complex_header_table = $('.dt-complex-header'),
    dt_row_grouping_table = $('.dt-row-grouping'),
    dt_multilingual_table = $('.dt-multilingual'),
    assetPath = '../../../app-assets/';

  if ($('body').attr('data-framework') === 'laravel') {
    assetPath = $('body').attr('data-asset-path');
  }

  // DataTable with buttons
  // --------------------------------------------------------------------

  if (dt_basic_table.length) {
    var dt_basic = dt_basic_table.DataTable({
      
      order: [[0, 'asc']],
      searching: true,
      dom: 
        '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-3 withoutheadbuttin dt-action-buttons text-end"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      displayLength: 7,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle',
          text: feather.icons['share'].toSvg({ class: 'font-small-4 mr-50' }) + 'Export',
          buttons: [
            {
              extend: 'print',
              text: feather.icons['printer'].toSvg({ class: 'font-small-4 mr-50' }) + 'Print',
              className: 'dropdown-item',
              exportOptions: {
                // This will export all columns by removing the 'columns' restriction
                modifier: {
                  page: 'all' // Ensures the print/export includes all rows and columns
                }
              }
            },
            {
              extend: 'csv',
              text: feather.icons['file-text'].toSvg({ class: 'font-small-4 mr-50' }) + 'Csv',
              className: 'dropdown-item',
              exportOptions: {
                // Export all columns
                modifier: {
                  page: 'all'
                }
              }
            },
            {
              extend: 'excel',
              text: feather.icons['file'].toSvg({ class: 'font-small-4 mr-50' }) + 'Excel',
              className: 'dropdown-item',
              exportOptions: {
                // Export all columns
                modifier: {
                  page: 'all'
                }
              }
            },
            {
              extend: 'pdf',
              text: feather.icons['clipboard'].toSvg({ class: 'font-small-4 mr-50' }) + 'Pdf',
              className: 'dropdown-item',
              exportOptions: {
                // Export all columns
                modifier: {
                  page: 'all'
                }
              }
            },
            {
              extend: 'copy',
              text: feather.icons['copy'].toSvg({ class: 'font-small-4 mr-50' }) + 'Copy',
              className: 'dropdown-item',
              exportOptions: {
                // Export all columns
                modifier: {
                  page: 'all'
                }
              }
            }
          ]
        },
      ],
      
      language: {
        paginate: {
          previous: '&nbsp;',
          next: '&nbsp;'
        }
      }
    });
    
}



  // Flat Date picker
  if (dt_date_table.length) {
    dt_date_table.flatpickr({
      monthSelectorType: 'static',
      dateFormat: 'm/d/Y'
    });
  }

  // Add New record
  // ? Remove/Update this code as per your requirements ?
  var count = 101;
  $('.data-submit').on('click', function () {
    var $new_name = $('.add-new-record .dt-full-name').val(),
      $new_post = $('.add-new-record .dt-post').val(),
      $new_email = $('.add-new-record .dt-email').val(),
      $new_date = $('.add-new-record .dt-date').val(),
      $new_salary = $('.add-new-record .dt-salary').val();

    if ($new_name != '') {
      dt_basic.row
        .add({
          responsive_id: null,
          id: count,
          full_name: $new_name,
          post: $new_post,
          email: $new_email,
          start_date: $new_date,
          salary: '$' + $new_salary,
          status: 5
        })
        .draw();
      count++;
      $('.modal').modal('hide');
    }
  });

  // Delete Record
  $('.datatables-basic tbody').on('click', '.delete-record', function () {
    dt_basic.row($(this).parents('tr')).remove().draw();
  });
	
	 
 
});
	

     // Initialize the charts once the DOM is fully loaded
    //  $(document).ready(function () {
    //      // Line Chart
    //      var ctx1 = document.getElementById('leavebar-chart').getContext('2d');
    //      var leaveChart = new Chart(ctx1, {
    //          type: 'line',
    //          data: {
    //              labels: ['January', 'February', 'March', 'April', 'May', 'June'], // Change labels as needed
    //              datasets: [{
    //                  label: 'Applications Submitted',
    //                  data: [65, 59, 80, 81, 56, 55], // Example data
    //                  borderColor: 'rgba(75, 192, 192, 1)',
    //                  fill: false
    //              }]
    //          },
    //          options: {
    //              responsive: true,
    //              maintainAspectRatio: false
    //          }
    //      });
 
    //      // Donut Chart
    //      var ctx2 = document.getElementById('donut-opentask').getContext('2d');
    //      var donutChart = new Chart(ctx2, {
    //          type: 'doughnut',
    //          data: {
    //              labels: ['Completed', 'Pending', 'Failed'],
    //              datasets: [{
    //                  label: 'Tasks',
    //                  data: [300, 50, 100], // Example data
    //                  backgroundColor: ['#36a2eb', '#ff6384', '#ffcd56']
    //              }]
    //          },
    //          options: {
    //              responsive: true,
    //              maintainAspectRatio: false
    //          }
    //      });
    //  });		
		
    </script>
{{-- </body>
<!-- END: Body-->

</html> --}}