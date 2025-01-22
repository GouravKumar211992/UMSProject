$(document).ready(function () {
    $(document).on('keypress', '.numberonly', function (e) {
        var charCode = (e.which) ? e.which : event.keyCode;

        if (String.fromCharCode(charCode).match(/[^0-9]/g)) {
            return false;
        }
    });

    $(document).on('keypress', '.decimalNumberonly', function (e) {
        var charCode = (e.which) ? e.which : event.keyCode;

        // Allow only numbers and one decimal point
        if (charCode !== 46 && (charCode < 48 || charCode > 57)) {
            return false; // Not a number or decimal point
        }

        // Check if the decimal point is already present
        if (charCode === 46 && $(this).val().indexOf('.') !== -1) {
            return false; // Prevent multiple decimal points
        }
    });

    $(".summernote").summernote({
        toolbar: [
            ["style", ["bold", "italic", "underline", "clear"]]
        ],
        height: 100,
        placeholder: 'Write your description here...',
        // tabsize: 2,
        minHeight: null,
        maxHeight: null,
        focus: true,
    });

    /*-----ADD & UPDATE DATA--------*/
    $(document).on("click", '[data-request="ajax-submit"]', function () {
        /*REMOVING PREVIOUS ALERT AND ERROR CLASS*/
        $(".is-invalid").removeClass("is-invalid");
        $(".help-block").remove();
        var $this = $(this);
        var $target = $this.data("target");
        var $url = $(this).data("action") ? $(this).data("action") : $($target).attr("action");
        var $method = $(this).data("action") ? "POST" : $($target).attr("method");
        var $redirect = $($target).attr("redirect");
        var $reload = $($target).attr("reload");
        var $callback = $($target).attr("callback");
        // console.log($callback);
        var $data = new FormData($($target)[0]);
        if (!$method) {
            $method = "get";
        }
        $this.prop('disabled', true);

        $.ajax({
            url: $url,
            data: $data,
            cache: false,
            type: $method,
            dataType: "JSON",
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loaderDiv').show();
            },
            success: function ($response) {
                $('#loaderDiv').hide();
                if ($response.status === 200) {
                    $($target).trigger("reset");
                    $this.prop('disabled', false);

                    Swal.fire("Success!", $response.message, "success");
                    console.log($callback);
                    if ($callback) {
                        console.log('call');
                        data = $response.data;
                        eval($callback);
                    }
                    setTimeout(function () {
                        if ($redirect) {
                            window.location.href = $redirect;
                        } else if ($reload) {
                            location.reload();
                        }
                    }, 2200);
                }
            },
            error: function ($response) {
                $('#loaderDiv').hide();
                $this.prop('disabled', false);
                if ($response.status === 422) {
                    if (
                        Object.size($response.responseJSON) > 0 &&
                        Object.size($response.responseJSON.errors) > 0
                    ) {
                        show_validation_error($response.responseJSON.errors);
                    }
                } else {
                    Swal.fire(
                        "Info!",
                        $response.responseJSON.message,
                        "warning"
                    );
                    setTimeout(function () { }, 1200);
                }
            },
        });
    });
});

$(window).on('load', function () {
    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
})

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
        if (name.indexOf("[]") !== -1) {
            // $('form [name="' + name + '"]').last().closest("").addClass("is-invalid error");
            $('form [name="' + name + '"]').last().find("").append('<span class="help-block text-danger fw-bolder">' + value + "</span>");
        } else if ($('form [name="' + name + '[]"]').length > 0) {
            $('form [name="' + name + '[]"]').addClass("is-invalid error");
            $('form [name="' + name + '[]"]').parent().after('<span class="help-block text-danger fw-bolder">' + value + "</span>");
        } else {
            if ($('form [name="' + name + '"]').attr("type") == "checkbox" || $('form [name="' + name + '"]').attr("type") == "radio") {
                if ($('form [name="' + name + '"]').attr("type") == "checkbox") {
                    // $('form [name="' + name + '"]').addClass("is-invalid error");
                    $('form [name="' + name + '"]').parent().after('<span class="help-block text-danger fw-bolder">' + value + "</span>");
                } else {
                    // $('form [name="' + name + '"]').addClass("is-invalid error");
                    $('form [name="' + name + '"]').parent().parent().append('<span class="help-block text-danger fw-bolder">' + value + "</span>");
                }
            } else if ($('form [name="' + name + '"]').get(0)) {
                if ($('form [name="' + name + '"]').get(0).tagName == "SELECT") {
                    // $('form [name="' + name + '"]').addClass("is-invalid error");
                    $('form [name="' + name + '"]').parent().after('<span class="help-block text-danger fw-bolder">' + value + "</span>");
                } else if ($('form [name="' + name + '"]').attr("type") == "password" && $('form [name="' + name + '"]').hasClass("hideShowPassword-field")) {
                    // $('form [name="' + name + '"]').addClass("is-invalid error");
                    $('form [name="' + name + '"]').parent().after('<span class="help-block text-danger fw-bolder">' + value + "</span>");
                } else if ($('form [name="' + name + '"]').attr("type") == "file") {
                    // $('form [name="' + name + '"]').addClass("is-invalid error");
                    $('form [name="' + name + '"]').closest('.attachment-container').find('#preview')
                        .before('<span class="help-block text-danger fw-bolder">' + value + '</span><br>');
                } else {
                    // $('form [name="' + name + '"]').addClass("is-invalid error");
                    $('form [name="' + name + '"]').after('<span class="help-block text-danger fw-bolder" role="alert">' + value + "</span>");
                }
            } else {
                // $('form [name="' + name + '"]').addClass("is-invalid error");
                $('form [name="' + name + '"]').after('<span class="help-block text-danger fw-bolder">' + value + "</span>");
            }
        }
    });

    /*SCROLLING TO THE INPUT BOX*/
    scroll();
}

Object.size = function (obj) {
    var size = 0,
        key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

function dropdown(url, selected_id, selected_value) {
    $.ajax({
        beforeSend: function (xhr) { },
        url: url,
        method: "GET",
        dataType: "json",
        success: function (response) {
            $("#" + selected_id + "").empty("");
            var options = '<option value="">select</option>';
            if (response.status === 200) {
                var option_list = response.data;
                $.each(option_list, function (index, value) {
                    // let selected = parseInt(selected_value) === value.id ? "selected" : "";
                    let selected = selected_value.includes(value.id.toString()) ? "selected" : "";
                    let name = value.name;
                    options += '<option value="' + value.id + '" ' + selected + ">" + name + "</option>";
                });
                $("#" + selected_id + "").append(options);

            }
        },
    });
}