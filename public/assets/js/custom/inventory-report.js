// /*=========================================================================================
//     File Name: po-report.js
//     Description: Purchase report page content with filter
//     ----------------------------------------------------------------------------------------
//     Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
// ==========================================================================================*/

// //------------ Filter data --------------------
// //---------------------------------------------

function isObject(value) {
    return typeof value === 'object' && value !== null;
}

async function fetchPurchaseOrders(filterData = {}) {
    try {
        
        if (!filterData.columnOrder) {
            const columnOrder = getColumnVisibilitySettings();
            var columnOrderList = columnOrder;
        } else {
            var columnOrderList = filterData.columnOrder;
        }
        delete filterData.columnOrder;
        
        const params = new URLSearchParams();
        
        Object.entries(filterData).forEach(([key, value]) => {
            if(!isObject(value) && value){
                params.append(key, value);
            }else if(isObject(value)){
                value.forEach((attribute, index) => {
                    params.append(`attribute_name[]`, attribute.groupId);
                    params.append(`attribute_value[]`, attribute.val);
                });
            }

        });
        var reportFilterUrl = window.routes.poReport;

        const url = `${reportFilterUrl}?${params.toString()}`;
        
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        // Call the function to update the table
        updateTable(data, columnOrderList || []);
    } catch (error) {
        console.error("Error fetching purchase orders:", error);
    }
}

// Function to update the table dynamically
function updateTable(inventory_reports = [], columnVisibility = []) {
    const tbody = document.querySelector("tbody");
    const thead = document.querySelector("thead");

    const dataTableSelector = ".datatables-basic";

    // Destroy existing DataTable instance if it exists
    if ($.fn.DataTable.isDataTable(dataTableSelector)) {
        const table = $(dataTableSelector).DataTable();
        table.destroy();
    }

    tbody.innerHTML = ""; // Clear existing table rows
    thead.innerHTML = ""; // Clear existing table headings
    // console.log('columnVisibility', columnVisibility);
    // Create a mapping of column indices from visibility settings
    const visibleColumnIndices = columnVisibility
        .filter((column) => column.visible)
        .map((column) => getColumnIndexById(column.id))
        .filter((index) => index !== -1); // Ensure valid indices
        console.log('visibleColumnIndices', visibleColumnIndices);
    // Ensure the Index (0), Action (10) and Action (11) columns are always visible
    if (!visibleColumnIndices.includes(0)) visibleColumnIndices.unshift(0); // Index column
    if (!visibleColumnIndices.includes(10)) visibleColumnIndices.push(10); // Confirmed Stock column
    if (!visibleColumnIndices.includes(11)) visibleColumnIndices.push(11); // Unconfirmed Stock column

    // Define table headers corresponding to the columns
    const day_1 = $('#day1').val();
    const day_2 = $('#day2').val();
    const day_3 = $('#day3').val();
    const day_4 = $('#day4').val();
    const day_5 = $('#day5').val();
    const tableHeaders = [
        `<th>#</th>`, // Index
        `<th>Document No</th>`, // Doc No
        `<th>Document Date</th>`, // Doc Date
        `<th>Item</th>`, // Item
        `<th>Item Code</th>`, // Item Code
        `<th>Attributes</th>`, // Attributes
        `<th>Store</th>`, // Store
        `<th>Rack</th>`, // Rack
        `<th>Shelf</th>`, // Shelf
        `<th>Bin</th>`, // Bin
        `<th>Confirmed Stock</th>`, // Confirmed Stock
        `<th>Unconfirmed Stock</th>`, // Unconfirmed Stock
        `<th>0-${day_1} Days</th>`, 
        `<th>${day_1}-${day_2} Days</th>`,
        `<th>${day_2}-${day_3} Days</th>`,
        `<th>${day_3}-${day_4} Days</th>`,
        `<th>${day_4}-${day_5} Days</th>`,
        `<th>Above ${day_5} Days</th>`,
    ];

    // Generate table heading HTML based on visible columns
    const headingRow = document.createElement("tr");
    const headingHTML = visibleColumnIndices
        .map((index) => tableHeaders[index] || `<th>N/A</th>`)
        .join("");

    headingRow.innerHTML = headingHTML;
    thead.appendChild(headingRow); // Append the heading row to thead

    // Loop through the purchase order data and update the table
    inventory_reports.forEach((report, index) => {
        const tr = document.createElement("tr");
        // Parse the item_attributes JSON string
        let attributesHTML = "N/A"; // Default value if attributes are not present or invalid
        try {
            const itemAttributes = JSON.parse(report.item_attributes);
            if (Array.isArray(itemAttributes) && itemAttributes.length > 0) {
                attributesHTML = itemAttributes.map(attr => {
                    const attributeName = attr.attribute_name ?? "N/A";
                    const attributeValue = attr.attribute_value ?? "N/A";
                    return `<span class="badge rounded-pill badge-light-secondary badgeborder-radius">
                        ${attributeName}: ${attributeValue}
                    </span>`;
                }).join(""); // Join the HTML for all attributes
            }
        } catch (error) {
            console.error("Error parsing item_attributes:", error);
        }
        
        // Create table data cells based on column visibility
        const cells = [
            `<td>${index + 1}</td>`, // Index
            `<td class="fw-bolder text-dark">${report.document_number}</td>`, // PO No
            `<td>${formatDate(report.document_date)}</td>`, // PO Date
            `<td>${report?.item_name ?? "N/A"}</td>`, // Item Name
            `<td>${report?.item_code ?? "N/A"}</td>`, // Item Code
            `<td>${attributesHTML}</td>`, // Attributes
            `<td>${report?.store ?? "N/A"}</td>`, // Store
            `<td>${report?.rack ?? "N/A"}</td>`, // Rack
            `<td>${report?.shelf ?? "N/A"}</td>`, // Shelf
            `<td>${report?.bin ?? "N/A"}</td>`, // Bin
            `<td>${report?.confirmed_stock ?? 0.00}</td>`, // Confirmed Stock
            `<td>${report?.unconfirmed_stock ?? 0.00}</td>`, // Unconfirmed Stock
            `<td>${report?.confirmed_stock_day1_days ?? 0.00}</td>`, // 10 Days Ago
            `<td>${report?.confirmed_stock_day2_days ?? 0.00}</td>`, // 15 Days Ago
            `<td>${report?.confirmed_stock_day3_days ?? 0.00}</td>`, // 20 Days Ago
            `<td>${report?.confirmed_stock_day4_days ?? 0.00}</td>`, // 10 Days Ago
            `<td>${report?.confirmed_stock_day5_days ?? 0.00}</td>`, // 15 Days Ago
            `<td>${report?.confirmed_stock__more_than_day5_days ?? '0.00'}</td>`, // 20 Days Ago
        ];

        // Construct row based on column visibility settings
        const rowHTML = visibleColumnIndices
            .map((index) => cells[index] || "<td>N/A</td>")
            .join("");

        tr.innerHTML = rowHTML;
        tbody.appendChild(tr);
        feather.replace();
    });

    // Reinitialize DataTable after updating the table rows
    try {
        // Reinitialize DataTable after updating the table rows
        $(dataTableSelector).DataTable({
            order: [[0, "asc"]],
            dom: '<"d-flex justify-content-between align-items-center mx-2 row"<"col-sm-12 col-md-3"l><"col-sm-12 col-md-6 withoutheadbuttin dt-action-buttons text-end pe-0"B><"col-sm-12 col-md-3"f>>t<"d-flex justify-content-between mx-2 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            displayLength: 8,
            lengthMenu: [8, 10, 25, 50, 75, 100],
            buttons: [
                {
                    extend: "collection",
                    className: "btn btn-outline-secondary dropdown-toggle",
                    text:
                        feather.icons["share"].toSvg({
                            class: "font-small-3 me-50",
                        }) + "Export",
                    buttons: [
                        {
                            extend: "excel",
                            text:
                                feather.icons["file"].toSvg({
                                    class: "font-small-4 me-50",
                                }) + "Excel",
                            className: "dropdown-item",
                            filename: function () {
                                return (
                                    "Material_Receipt_" +
                                    new Date().toISOString().slice(0, 10)
                                ); // Custom filename logic
                            },
                            exportOptions: {
                                columns: function (idx, data, node) {
                                    return true; // Include all columns
                                },
                            },
                        },
                        {
                            extend: "copy",
                            text:
                                feather.icons["mail"].toSvg({
                                    class: "font-small-4 me-50",
                                }) + "Mail",
                            className: "dropdown-item",
                            action: function (e, dt, button, config) {
                                var exportedData = dt.buttons.exportData(); // Get the export data
                                var dataToSend = JSON.stringify(exportedData); // Convert data to JSON

                                $.ajax({
                                    url: window.routes.reportSendMail, // Laravel route to send email
                                    type: "GET",
                                    success: function (response) {
                                        // Show success message
                                        const Toast = Swal.mixin({
                                            toast: true,
                                            position: "top-end",
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            didOpen: (toast) => {
                                                toast.onmouseenter =
                                                    Swal.stopTimer;
                                                toast.onmouseleave =
                                                    Swal.resumeTimer;
                                            },
                                        });
                                        Toast.fire({
                                            icon: "success",
                                            title: response.success,
                                        });
                                    },
                                    error: function (error) {
                                        const Toast = Swal.mixin({
                                            toast: true,
                                            position: "top-end",
                                            showConfirmButton: false,
                                            timer: 3000,
                                            timerProgressBar: true,
                                            didOpen: (toast) => {
                                                toast.onmouseenter =
                                                    Swal.stopTimer;
                                                toast.onmouseleave =
                                                    Swal.resumeTimer;
                                            },
                                        });
                                        Toast.fire({
                                            icon: "error",
                                            title: error.responseJSON.message,
                                        });
                                    },
                                });
                            },
                        },
                    ],
                    init: function (api, node, config) {
                        $(node).removeClass("btn-secondary");
                        $(node).parent().removeClass("btn-group");
                        setTimeout(function () {
                            $(node)
                                .closest(".dt-buttons")
                                .removeClass("btn-group")
                                .addClass("d-inline-flex");
                        }, 50);
                    },
                },
            ],
            language: {
                search: "",
                searchPlaceholder: "Search...",
                paginate: {
                    // remove previous & next text from pagination
                    previous: "&nbsp;",
                    next: "&nbsp;",
                },
            },
        });
    } catch (error) {
        console.error("Error initializing DataTable:", error);
    }

    $('[data-bs-toggle="tooltip"]').tooltip();
}

// Helper function to format date
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString("en-GB"); // Format as dd-mm-yyyy
}

/**
 * Get the current order of columns based on their checkboxes.
 * @returns {Array} columnOrder - Array of column IDs in their current order
 */
function getColumnVisibilitySettings() {
    const columnVisibility = [];
    $(".sortable .form-check-input").each(function () {
        columnVisibility.push({
            id: $(this).attr("id"),
            visible: $(this).is(":checked"),
        });
    });

    $(".sortable .aging-visibility").each(function () {
        let visibleId = $(this).attr("id");
        let visibleValue = ($(this).val() == 1) ? 1 : 0;
        columnVisibility.push({
            id: visibleId,
            visible: visibleValue ? true : false,
        });
    });

    return columnVisibility;
}

// Example: Map column IDs to indices
function getColumnIndexById(columnId) {
    // Map column IDs to their index positions
    const columnMapping = {
        "document-no": 1,
        "document-date": 2,
        item: 3,
        "item-code": 4,
        "attributes":5,
        "store": 6,
        "rack": 7,
        "shelf": 8,
        "bin": 9,
        "confirmed-stock": 10,
        "unconfirmed-stock": 11,
        "day1_visibility": 12,
        "day2_visibility": 13,
        "day3_visibility": 14,
        "day4_visibility": 15,
        "day5_visibility": 16,
        "day6_visibility": 17,
    };
    console.clear();
    console.log('columnMapping', columnMapping);

    return columnMapping[columnId] || -1;
}

// Call fetchPurchaseOrders when the page loads
document.addEventListener("DOMContentLoaded", function () {
    fetchPurchaseOrders();

    const selectAllCheckbox = document.getElementById("selectAll");
    const checkboxes = document.querySelectorAll(".sortable .form-check-input");

    // Update the "Select All" checkbox state based on individual checkboxes
    const updateSelectAllState = () => {
        const allChecked = Array.from(checkboxes).every(
            (checkbox) => checkbox.checked
        );
        const someChecked = Array.from(checkboxes).some(
            (checkbox) => checkbox.checked
        );

        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = !allChecked && someChecked;
    };

    // Handle "Select All" checkbox change
    const handleSelectAllChange = () => {
        checkboxes.forEach(
            (checkbox) => (checkbox.checked = selectAllCheckbox.checked)
        );
        updateSelectAllState();
    };

    // Initialize event listeners
    selectAllCheckbox.addEventListener("change", handleSelectAllChange);
    checkboxes.forEach((checkbox) =>
        checkbox.addEventListener("change", updateSelectAllState)
    );

    // Initial update of "Select All" checkbox state
    updateSelectAllState();
});

$(document).on('change', '.store_code', function() {
    var store_code_id = $(this).val();
    $('#store_id').val(store_code_id).select2();
    
    var data = {
        store_code_id: store_code_id
    };
    
    $.ajax({
        type: 'POST',
        data: data,
        url: '/material-receipts/get-store-racks',
        success: function(data) {
            $('#rack_id').empty();
            $('#rack_id').append('<option value="">Select</option>');
            $.each(data.storeRacks, function(key, value) {
                $('#rack_id').append('<option value="'+ key +'">'+ value +'</option>');
            });
            $('#rack_id').trigger('change');
            
            $('#bin_id').empty();
            $('#bin_id').append('<option value="">Select</option>');
            $.each(data.storeBins, function(key, value) {
                $('#bin_id').append('<option value="'+ key +'">'+ value +'</option>');
            });
        }
    });
});

$(document).on('change', '.rack_code', function() {
    var rack_code_id = $(this).val();
    $('#rack_id').val(rack_code_id).select2();
    
    var data = {
        rack_code_id: rack_code_id
    };
    
    $.ajax({
        type: 'POST',
        data: data,
        url: '/material-receipts/get-rack-shelfs',
        success: function(data) {
            $('#shelf_id').empty();
            $('#shelf_id').append('<option value="">Select</option>');
            $.each(data.storeShelfs, function(key, value) {
                $('#shelf_id').append('<option value="'+ key +'">'+ value +'</option>');
            });

            $('#shelf_id').trigger('change');
        }
    });
});

// Get Attribute Values
$(document).on('change', '.attribute_name', function() {
    var attribute_name = $(this).val();
    $('#attribute_name').val(attribute_name).select2();
    
    var data = {
        attribute_name: attribute_name
    };
    
    $.ajax({
        type: 'POST',
        data: data,
        url: '/inventory-reports/get-attribute-values',
        success: function(data) {
            $('#attribute_value').empty();
            $.each(data.attributeValues, function(key, value) {
                $('#attribute_value').append('<option value="'+ key +'">'+ value +'</option>');
            });
        }
    });
});
