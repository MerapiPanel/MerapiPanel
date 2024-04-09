import { toast } from "@il4mb/merapipanel";

$("#login-form").on("submit", function (e) {
    e.preventDefault();

    if (!this.checkValidity()) {
        return;
    }
    var form = $(this);
    var data = form.serialize();
    var url = form.attr("action");
    var method = form.attr("method");



    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function (response) {
            if (response) {
                window.location.reload();
            }
        },
        error: function (response) {
            toast(response.responseJSON?.message || response.statusText, 5, "text-danger");
        },
    });

});