
function getCsrfToken() {
    const token = $('meta[name="csrf-token"]').attr('content');
    if (!token) {
        console.warn('CSRF token not found');
    }
    return token;
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOEKN': getCsrfToken()
    }
});

$(document)
    .ajaxStart(function () {
        $('#loader-div').show();
    })
    .ajaxStop(function () {
        $('#loader-div').hide();
    });

$(document).on('submit', '.ajax-input-form', function (e) {
    e.preventDefault();
    const currentFrom = this;
     var submitButton = (e.originalEvent && e.originalEvent.submitter) 
                        || $(this).find(':submit');
    var submitButtonHtml = submitButton.innerHTML; 
    submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    submitButton.disabled = true;
    var method = $(this).attr('method');
    var url = $(this).attr('action');
    var redirectUrl = $(this).data('redirect');
    var data = new FormData($(this)[0]);

    /*Additional append data*/
    const keys = [
        'deletedItemDiscTedIds',
        'deletedHeaderDiscTedIds',
        'deletedHeaderExpTedIds',
        'deletedPiItemIds',
        'deletedSoItemIds',
        'deletedSiItemIds',
        'deletedAttachmentIds',
        'deletedDelivery'
    ];

    keys.forEach(key => {
        const value = localStorage.getItem(key);
        if (value) {
            data.append(key, value);
            // localStorage.removeItem(key);
        }
    });

    const bomkeys = [
        'deletedItemOverheadIds',
        'deletedHeaderOverheadIds',
        'deletedBomItemIds'
    ];

    bomkeys.forEach(key => {
        const value = localStorage.getItem(key);
        if (value) {
            data.append(key, value);
            // localStorage.removeItem(key);
        }
    });

    //Only for Sales module
    if (this.classList.contains('sales_module_form')) {
        const items = document.getElementsByClassName('comp_item_code');
        const itemLocations = document.getElementsByClassName('item_store_locations');
        for (let index = 0; index < items.length; index++) {
            data.append(`item_attributes[${index}]`, items[index].getAttribute('attribute-array'));
        }
        for (let index = 0; index < itemLocations.length; index++) {
            data.append(`item_locations[${index}]`, (decodeURIComponent(itemLocations[index].getAttribute('data-stores'))));
        }
    }

    // if (typeof selectedAttachmentsMain !== 'undefined')
    // {
    //     selectedAttachmentsMain.forEach((element, index) => {
    //         data.append(`attachments[${index}]`, element);
    //     });
    // }

    var formObj = $(this);
    

    $.ajax({
        url,
        type: method,
        data,
        contentType: false,
        processData: false,
        success: function (res) {
            submitButton.disabled = false;
            submitButton.innerHTML = submitButtonHtml;
            $('.ajax-validation-error-span').remove();
            $(".is-invalid").removeClass("is-invalid");
            $(".help-block").remove();
            $(".waves-ripple").remove();
            Swal.fire({
                title: 'Success!',
                text: res.message,
                icon: 'success',
            });
            setTimeout(() => {
                if (res.store_id) {
                    location.href = `/stores/${res.store_id}/edit`;
                } else if (redirectUrl) {
                    location.href = redirectUrl;
                } else {
                    location.reload();
                }
            }, 1500);
            
        },
        error: function (error) {
            if (currentFrom.dataset.completionfunction) {
                window[currentFrom.dataset.completionfunction]();
            }
            submitButton.disabled = false;
            submitButton.innerHTML = submitButtonHtml;
            $('.ajax-validation-error-span').remove();
            $(".is-invalid").removeClass("is-invalid");
            $(".help-block").remove();
            $(".waves-ripple").remove();
            let res = error.responseJSON || {};
            if (error.status === 422 && res.errors) {
                if (
                    Object.size(res) > 0 &&
                    Object.size(res.errors) > 0
                ) {
                    show_validation_error(res.errors);
                }
                // let errors = res.errors;
                // for (const [key, errorMessages] of Object.entries(errors)) {
                //     var name = key.replace(/\./g, "][").replace(/\]$/, "");
                //     formObj.find(`[name="${name}"]`).parent().append(
                //         `<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">${errorMessages[0]}</span>`
                //     );
                // }

            } else {
                Swal.fire({
                    title: 'Error!',
                    text: res.message || 'An unexpected error occurred.',
                    icon: 'error',
                });
            }
        }
    });
});

$(document).on('click', '.submit-button', (e) => {
    let status = e.target.closest('button').value;
    $('#document_status').val(status);
});

$('#save-draft-button').on('click', function (e) {
    $(this).data('clicked', true); 
    document.getElementById('document_status').value = 'draft';
    $('.ajax-input-form').submit();
    $(this).data('clicked', false);
});

$('#submit-button').on('click', function (e) {
    $(this).data('clicked', false);
    document.getElementById('document_status').value = 'submitted'; 
    $('.ajax-input-form').submit();
});

function show_validation_error(msg) {
    if ($.isPlainObject(msg)) {
        $data = msg;
    } else {
        $data = $.parseJSON(msg);
    }

    $.each($data, function (index, value) {
        var name = index.replace(/\./g, "][");
        
        if (index.indexOf(".") !== -1) {
            name = name + "]";
            name = name.replace("]", "");
        }
        if (index === "sub_types" || index === "sub_types[]") {  
            if ($('form [name="sub_types[]"]:checked').length === 0) {
                var checkboxGroupContainer = $('form [name="sub_types[]"]').first().closest('.demo-inline-spacing');
                if (checkboxGroupContainer.find('.ajax-validation-error-span').length === 0) {
                    checkboxGroupContainer.after(
                        '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' + value[0] + '</span>'
                    );
                    checkboxGroupContainer.addClass("is-invalid error"); 
                }
            }
        } else if (name.indexOf("[]") !== -1) {
            $('form [name="' + name + '"]')
                .last()
                .closest("")
                .addClass("is-invalid error");
            $('form [name="' + name + '"]')
                .last()
                // .closest(".input-group")
                .find("")
                .append(
                    '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                    value +
                    "</span>"
                );
        } else if ($('form [name="' + name + '[]"]').length > 0) {
            $('form [name="' + name + '[]"]')
                // .closest(".input-group")
                .addClass("is-invalid error");
            $('form [name="' + name + '[]"]')
                .parent()
                .after(
                    '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                    value +
                    "</span>"
                );
        } else if ($('form [name="' + name + '"]').length > 0) {
            if ($('form [name="' + name + '"]').is('select')) {
                $('form [name="' + name + '"]').addClass("is-invalid error");
                $('form [name="' + name + '"]').next('.select2-container').after(
                    '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                    value +
                    "</span>"
                );
            } else {
                $('form [name="' + name + '"]').addClass("is-invalid");
                $('form [name="' + name + '"]').after(
                    '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px" role="alert">' +
                    value +
                    "</span>"
                );
            }
        } else {

            if (
                $('form [name="' + name + '"]').attr("type") == "checkbox" ||
                $('form [name="' + name + '"]').attr("type") == "radio") {
                    if (
                        $('form [name="' + name + '"]').attr("type") == "checkbox"
                    ) {

                        $('form [name="' + name + '"]')
                            // .closest(".form-group")
                            .addClass("is-invalid error");
                        $('form [name="' + name + '"]')
                            .parent()
                            .after(
                                '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                                value +
                                "</span>"
                            );
                    } else {
                        $('form [name="' + name + '"]')
                            // .closest(".input-group")
                            .addClass("is-invalid error");
                        $('form [name="' + name + '"]')
                            .parent()
                            .parent()
                            .append(
                                '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                                value +
                                "</span>"
                            );
                    }
            } else if ($('form [name="' + name + '"]').get(0)) {
                
                if (
                    $('form [name="' + name + '"]').get(0).tagName == "SELECT"
                ) {
                   
                    $('form [name="' + name + '"]')
                        // .closest(".form-group")
                        .addClass("is-invalid error");
                    $('form [name="' + name + '"]')
                        // .parent()
                        .after(
                            '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                            value +
                            "</span>"
                        );
                } else if (
                    $('form [name="' + name + '"]').attr("type") ==
                    "password" &&
                    $('form [name="' + name + '"]').hasClass(
                        "hideShowPassword-field"
                    )
                ) {
                    $('form [name="' + name + '"]')
                        // .closest(".input-group")
                        .addClass("is-invalid error");
                    $('form [name="' + name + '"]')
                        .parent()
                        .after(
                            '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                            value +
                            "</span>"
                        );
                } else {
                    let ckeditor = document.querySelector(`div[id="cke_${name}"]`);
                    if (ckeditor) {
                        $(ckeditor).after(
                            '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px" role="alert">' +
                            value +
                            "</span>"
                        );
                    } else {
                        $('form [name="' + name + '"]')
                            // .closest(".input-group")
                            .addClass("is-invalid");
                        $('form [name="' + name + '"]').after(
                            '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px" role="alert">' +
                            value +
                            "</span>"
                        );
                    }
                }
            } else {
                $('form [name="' + name + '"]')
                    .closest(".input-group")
                    .addClass("is-invalid error");
                $('form [name="' + name + '"]').after(
                    '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                    value +
                    "</span>"
                );
                if (name == 'anwser_required') {
                    toast("warning", value);
                }
                name = name.replace(/\[\d+\]$/, '');
                if ($(`[name='${name}[]']`).length) {
                    $('form [name="' + name + '"]')
                        .closest(".input-group")
                        .addClass("is-invalid error");
                    $('form [name="' + name + '[]' + '"]').after(
                        '<span class="ajax-validation-error-span form-label text-danger" style="font-size:12px">' +
                        value +
                        "</span>"
                    );
                }
                //Approvers
                // if (name === 'user' || name === 'level_organization_id' || name === 'min_value' || name === 'rights')
                if (name == "custom_error")
                {
                    Swal.fire({
                        title: 'Error!',
                        text: value,
                        icon: 'error',
                    });
                    return;
                }
            }
        }
        // $('.error-message').html($('.error-message').text().replace(".,",". "));
    });

    /*SCROLLING TO THE INPUT BOX*/
    // scroll();
}

Object.size = function (obj) {
    var size = 0,
        key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

$(document).on('click', '.delete-btn', function (e) {
    e.preventDefault();
    let $this = $(this);
    let url = $this.data('url');
    let message = $this.data('message') || 'Are you sure you want to delete this category?';
    let redirectUrl = $this.data('redirect') || window.location.pathname;

    Swal.fire({
        title: 'Alert!',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url,
                type: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: () => $('#loaderDiv').show(),
                success: (res) => {
                    $('#loaderDiv').hide();
                    Swal.fire({
                        title: 'Success!',
                        text: res.message,
                        icon: 'success'
                    });

                    $this.closest('tr').fadeOut(500, function () {
                        $(this).remove();
                    });

                    setTimeout(() => {
                        if (redirectUrl) {
                            window.location.replace(redirectUrl);
                        } else {
                            location.reload();
                        }
                    }, 1500);
                },
                error: (error) => {
                    $('#loaderDiv').hide();
                    let res = error.responseJSON || {};
                    Swal.fire({
                        title: 'Error!',
                        text: res.message || 'An unexpected error occurred.',
                        icon: 'error'
                    });
                }
            });
        }
    });
});

$(document).ready(function () {
    function updateFileIcons() {
        $('.file-link').each(function () {
            var fileUrl = $(this).attr('href');
            var fileExtension = fileUrl.split('.').pop().toLowerCase();
            var $iconElement = $(this).find('.file-icon');
            $iconElement.removeClass('fa-file-pdf fa-file-word fa-file-excel fa-file-powerpoint fa-file-image fa-file-alt fa-file');
            switch (fileExtension) {
                case 'pdf':
                    $iconElement.addClass('fa-file-pdf');
                    break;
                case 'doc':
                case 'docx':
                    $iconElement.addClass('fa-file-word');
                    break;
                case 'xls':
                case 'xlsx':
                    $iconElement.addClass('fa-file-excel');
                    break;
                case 'ppt':
                case 'pptx':
                    $iconElement.addClass('fa-file-powerpoint');
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                    $iconElement.addClass('fa-file-image');
                    break;
                case 'txt':
                    $iconElement.addClass('fa-file-alt');
                    break;
                default:
                    $iconElement.addClass('fa-file');
                    break;
            }
        });
    }

    // Update file icons on page load
    updateFileIcons();

    // Handle file input change event
    $('#document-upload').on('change', function () {
        updateFileIcons();
    });
});

$('#save-draft-button').click(function() {
    $('#document_status').val('draft');
});
