/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_BookAnAppointment
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

require([
    'jquery',
    'Magento_Ui/js/modal/modal',
], function ($, modal) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        modalClass: "appointment__popup--wrapper",
        buttons: []
    };

    var popup = modal(options, $('#book-appointment-popup'));
    $("#click-appointment").on('click',function(){
        $("#click-appointment").css("display", "block");
        $("#book-appointment-popup").modal("openModal");
    });

    $("#appointment-book-form").on('submit', function (e) {
        e.preventDefault();
        var name = $("#appointment-book-form input[name='name']").val(),
            email = $("#appointment-book-form input[name='email']").val(),
            comment = $("#appointment-book-form textarea[name='comment']").val(),
            phone = $("#appointment-book-form input[name='telephone']").val();

        if (name === "" || email === "" || comment === "" || phone === "" || name === "undefined" || email === "undefined" || comment === "undefined" || phone === "undefined") {
            return false;
        }

        var data = {
            name: name,
            email: email,
            telephone: phone,
            comment: comment
        };

        $('body').trigger('processStart');
        $.ajax({
            url: $("#appointment-book-form").attr("action"),
            type: "POST",
            data: data,
            success: function (result) {
                if (result.success === true) {
                    $(".appointment-msg-div").html(successDiv('Appointment booked successfully. Team will contact you soon.'));
                    scrollToMsg();
                    clearAppointmentForm();
                    startMsgClearTimeout();
                }
                $('body').trigger('processStop');
            },
            error: function () {
                $(".appointment-msg-div").html(errorDiv('<strong>Sorry!</strong> There was an error in booking appointment.'));
                scrollToMsg();
                $('body').trigger('processStop');
                startMsgClearTimeout();
            }
        });
    });

    function successDiv(msg) {
        var html = '';
        html += '<div class="alert alert-success custom-success">';
        html += msg;
        html += '</div>';
        return html;
    }

    function errorDiv(msg) {
        var html = '';
        html += '<div class="alert alert-danger custom-error">';
        html += msg;
        html += '</div>';
        return html
    }

    function startMsgClearTimeout() {
        setTimeout(() => {
            $(".appointment-msg-div .alert").fadeOut(1000, () => $(".appointment-msg-div").html(""));
            $("#book-appointment-popup").modal("closeModal");
        }, 3000)
    }

    function scrollToMsg() {
        var x = $(".appointment-msg-div").position();
        window.scrollTo(x.left, x.top);
    }

    function clearAppointmentForm() {
        $("#appointment-book-form input[name='name']").val("")
        $("#appointment-book-form input[name='email']").val("")
        $("#appointment-book-form input[name='telephone']").val("")
        $("#appointment-book-form textarea[name='comment']").val("")
    }
});
