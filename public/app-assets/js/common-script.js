
$(document).ready(function () {

    document.addEventListener('keydown', function(e) {
        const allowedKeys = [
            'Backspace', 'ArrowLeft', 'ArrowRight', 'Delete', 'Tab', 'Enter', 'Escape'
        ];

        // Check if the target is an input of type number
        if (e.target && e.target.matches('input[type="number"]')) {
            if (
                (!allowedKeys.includes(e.key)) && // Allow special keys
                (e.key < '0' || e.key > '9') && // Allow digits
                (e.key !== '.') // Allow decimal point
            ) {
                e.preventDefault(); // Prevent non-numeric and alphabetic input
            }
        }
    });


    $('body').on("keypress", '.numberonly', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode    
        if (String.fromCharCode(charCode).match(/[^0-9]/g))    
            return false;
    }); 
    
    $('body').on("keypress", '.decimal-only', function(evt) {
        return true;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          
    });

    $('body').on("keypress", '.time-input', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode    
        if (String.fromCharCode(charCode).match(/[^0-9:]/g))    
        return false;
    });

    $('body').on("keyup change", '.time-input', function(e) {
        $('.time-input-error').remove();
        
        var durationInput = $(this).val();
        var regex = /^([0-3]?[0-9]):([0-5]?[0-9]):([0-5]?[0-9])$/;

        if (!regex.test(durationInput)) {
            $(this).after('<label class="control-label text-danger time-input-error" for="name">Please enter in the format HH:MM:SS</label>')
        }
    });
})

function getBaseUrl() {
    const protocol = window.location.protocol;
    const hostname = window.location.hostname; 
    const port = window.location.port ? `:${window.location.port}` : ''; 
    return `${protocol}//${hostname}${port}`;
}

function restrictPastDates(element)
{
    const currentDate = moment();
    const enteredDate = moment(element.value);
    if (enteredDate.isBefore(currentDate, 'day')) {
        element.value = moment().format('YYYY-MM-DD');
        Swal.fire({
            title: 'Error!',
            text: 'Previous date selection is not allowed',
            icon: 'error',
        });
    }
}

function restrictFutureDates(element)
{
    const currentDate = moment();
    const enteredDate = moment(element.value);
    if (currentDate.isBefore(enteredDate, 'day')) {
        element.value = moment().format('YYYY-MM-DD');
        Swal.fire({
            title: 'Error!',
            text: 'Future date selection is not allowed',
            icon: 'error',
        });
    }
}

function restrictBothFutureAndPastDates(element)
{
    const currentDate = moment();
    const enteredDate = moment(element.value);
    if (currentDate.isBefore(enteredDate, 'day') || enteredDate.isBefore(currentDate, 'day')) {
        element.value = moment().format('YYYY-MM-DD');
        Swal.fire({
            title: 'Error!',
            text: 'Future/ Past date selection is not allowed',
            icon: 'error',
        });
    }
}

function allowOnlyUppercase(event)
{
    const input = event.target;
    // Convert the current value to uppercase
    input.value = input.value.toUpperCase();
}

function debounce(func, wait) {
    let timeout;
    return function(...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

$(document).ready(function() {
    const baseUrl = getBaseUrl();
    function initializeAutocomplete(selector, config) {
        if ($(selector).length) {
        $(selector).autocomplete({
            source: function(request, response) {
                const url = typeof config.url === 'function' ? config.url() : config.url;
                const data = {
                    q: request.term,
                    type: config.type,
                    categoryId: config.categoryId ? config.categoryId() : null,
                    ...config.extraParams ? config.extraParams() : {}
                };
                $.ajax({
                    url: baseUrl + url,
                    method: 'GET',
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        response($.map(data, function(item) {
                            var result = {
                                id: item.id,
                                label: item[config.labelField],
                                cat_initials: item.cat_initials || '', 
                                sub_cat_initials: item.sub_cat_initials || '',    
                            };
                            if (config.additionalFields) {
                                config.additionalFields.forEach(function(field) {
                                    result[field] = item[field] || '';
                                });
                            }
                            return result;
                        }));
                    },
                    error: function(xhr) {
                        console.error('Error fetching data:', xhr.responseText);
                    }
                });
            },
            minLength: config.minLength || 0,
            select: function(event, ui) {
                $(this).val(ui.item.label);
                $(config.hiddenFieldSelector.call(this)).val(ui.item.id);
                if (config.onSelect) {
                    config.onSelect(ui.item);
                }
                return false;
            },
            change: function(event, ui) {
                if (!ui.item) {
                    $(this).val("");
                    $(config.hiddenFieldSelector.call(this)).val('');
                }
            }
        }).focus(function() {
            if (this.value === "") {
                $(this).autocomplete("search", "");
            }
        });
        }
    }
    initializeAutocomplete(".category-autocomplete", {
        url: '/search',
        type: 'category',
        labelField: 'name',
        hiddenFieldSelector: function() { return '.category-id'; },
        minLength: 0,
        extraParams: function() {
            return {
                category_type: $('.category-type').val() 
            };
        },
        onSelect: function(selectedItem) {
        $('.cat_initials-id').val(selectedItem.cat_initials).change(); 
        $('.subcategory-autocomplete').val(''); 
        $('.sub_cat_initials-id').val(''); 
        $('.subcategory-id').val('');
        }
    });
    initializeAutocomplete(".subcategory-autocomplete", {
        url: '/search',
        type: 'subcategory',
        labelField: 'name',
        hiddenFieldSelector: function() { return '.subcategory-id'; },
        minLength: 0,
        categoryId: function() {
            return $('input[name="category_id"]').val();
        },
        extraParams: function() {
            return {
                category_type: $('.category-type').val() 
            };
        },
        onSelect: function(selectedItem) {
            $('.sub_cat_initials-id').val(selectedItem.sub_cat_initials).change(); 
        }
    });

    initializeAutocomplete(".hsn-autocomplete", {
        url: '/search',
        type: 'hsn',
        labelField: 'code',
        hiddenFieldSelector: function() { return '.hsn-id'; },
        minLength: 0,
        additionalFields: ['description']
    });

    initializeAutocomplete(".ladger-autocomplete", {
        url: '/search',
        type: 'ladger',
        labelField: 'name',
        hiddenFieldSelector: function() { return '.ladger-id'; },
        minLength: 0,
        additionalFields: ['description'],
    });

    initializeAutocomplete(".ledger-group-autocomplete", {
        url: '/search',
        type: 'ledgerGroup',
        labelField: 'name',
        hiddenFieldSelector: function() { return '.ledger-group-id'; },
        minLength: 0,
        additionalFields: ['description'],
        source: []
    });

    initializeAutocomplete("#service_provider_ledger_id", {
        url: '/search',
        type: 'ladger',
        labelField: 'name',
        hiddenFieldSelector: function() { return '#ledger_id_service_provider'; },
        minLength: 0,
        additionalFields: ['description'],
    });
    

    initializeAutocomplete(".sales-person-autocomplete", {
        url: '/search',
        type: 'salesPerson',
        labelField: 'name',
        hiddenFieldSelector: function() { return '.sales-person-id'; },
        minLength: 0,
        additionalFields: ['description']
    });

    function updateLedgerGroupDropdown(ledgerId) {
        var $ledgerGroupSelect = $(".ledger-group-select");
        $ledgerGroupSelect.empty().append('<option value="">Select Ledger Group</option>');

        $.ajax({
            url: '/ledgers/' + ledgerId + '/groups', 
            method: 'GET',
            success: function(data) {
                if (Array.isArray(data) && data.length) {
                    data.forEach(function(group) {
                        var option = new Option(group.name, group.id);
                        $ledgerGroupSelect.append(option);
                    });
                    var preselectedGroupId = $(".ledger-group-id").val();
                    if (preselectedGroupId) {
                        $ledgerGroupSelect.val(preselectedGroupId).trigger('change');
                    }
                } else {
                    console.error('No groups found for this ledger');
                }
            },
            error: function() {
                alert('An error occurred while fetching Ledger Groups.');
            }
        });
    }

    $(".ladger-autocomplete").on("autocompleteselect", function(event, ui) {
        var ledgerId = ui.item.id;
        if (ledgerId) {
            $(".ledger-group-select").val("");
            $(".ledger-group-id").val("");
            updateLedgerGroupDropdown(ledgerId); 
        }
    });

    var initialLedgerId = $(".ladger-id").val();
    if (initialLedgerId) {
        updateLedgerGroupDropdown(initialLedgerId);
    }
    $(document).ready(function() {
        function loadSubcategories(categoryId, selectedSubcategoryId, subcategorySelect) {
            if (categoryId) {
                $.ajax({
                    url: '/categories/subcategories/' + categoryId,
                    method: 'GET',
                    success: function(response) {
                        subcategorySelect.empty();
                        subcategorySelect.append('<option value="">Select Sub-Category</option>');
                        $.each(response, function(index, subcategory) {
                            subcategorySelect.append(
                                '<option value="' + subcategory.id + '"' + 
                                (subcategory.id == selectedSubcategoryId ? ' selected' : '') + '>' + 
                                subcategory.name + '</option>'
                            );
                        });
                    },
                    error: function() {
                        alert('An error occurred while fetching subcategories.');
                    }
                });
            } else {
                subcategorySelect.empty();
                subcategorySelect.append('<option value="">Select Sub-Category</option>');
            }
        }
        $('select[name="category_id"]').change(function() {
            var categoryId = $(this).val();
            var subcategorySelect = $('select[name="subcategory_id"]'); 
            var selectedSubcategoryId = subcategorySelect.data('selected-id');
            loadSubcategories(categoryId, selectedSubcategoryId, subcategorySelect);
        });
    
        function initializeSubcategories() {
            var categoryId = $('select[name="category_id"]').val(); 
            var subcategorySelect = $('select[name="subcategory_id"]'); 
            var selectedSubcategoryId = subcategorySelect.data('selected-id');
            loadSubcategories(categoryId, selectedSubcategoryId, subcategorySelect);
        }
       
       function initializeLedgerGroupsOnPageLoad() {
        var ledgerId = $('input[name="ledger_id"]').val();
        if (ledgerId) {
            updateLedgerGroupAutocomplete(ledgerId);
        }
       }
        $(".ladger-autocomplete").on("autocompleteselect", function(event, ui) {
            var ledgerId = ui.item.id;
            if (ledgerId) {
                $(".ledger-group-autocomplete").val(""); 
                $(".ledger-group-id").val("");
                updateLedgerGroupAutocomplete(ledgerId); 
            }
        });
        $(".ladger-autocomplete").on("input", function() {
            $(".ledger-group-autocomplete").val("");  
            $(".ledger-group-id").val("");  
            $(".ledger-group-autocomplete").autocomplete("option", "source", []);  
        });
        function updateLedgerGroupAutocomplete(ledgerId) {
            $.ajax({
                url: '/ledgers/' + ledgerId + '/groups',
                method: 'GET',
                success: function(data) {
                    var ledgerGroupAutocomplete = $(".ledger-group-autocomplete");
                    if (Array.isArray(data)) {
                        ledgerGroupAutocomplete.autocomplete("option", "source", data.map(function(group) {
                            return {
                                label: group.name, 
                                value: group.name,
                                id: group.id    
                            };
                        }));
                    } 
                    else if (data && data[0]) {
                        ledgerGroupAutocomplete.autocomplete("option", "source", [{
                            label: data[0].name,
                            value: data[0].name,
                            id: data[0].id
                        }]);
                    } else {
                        console.error("Unexpected data format:", data);
                    }
                },
                error: function() {
                    alert('An error occurred while fetching Ledger Groups.');
                }
            });
        }
        initializeLedgerGroupsOnPageLoad();
        initializeSubcategories();
    });
    
});













