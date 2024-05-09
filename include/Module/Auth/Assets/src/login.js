import { toast, cookie } from "@il4mb/merapipanel";

navigator.geolocation.getCurrentPosition(function(location) {
    console.log(location.coords.latitude);
    console.log(location.coords.longitude);
});


$("#login-form").on("submit", function (e) {
    
    e.preventDefault();

    if (!this.checkValidity()) {
        return;
    }


    $("#loading").removeClass("d-none");
    $("#login-btn").prop("disabled", true);
    $("#error").addClass("d-none");

    
    var form = $(this);
    var data = form.serialize();
    var url = form.attr("action");
    var method = form.attr("method");


    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function (response) {

            setTimeout(() => {
                if (cookie.cookie_get(response.data["cookie-name"])) {
                    window.location.reload();
                } else {
                    toast("Can't start session, make sure you have enabled cookies in your browser", 10, "text-danger");
                    $("#error").removeClass("d-none");
                    $("#error").text("Can't start session, make sure you have enabled cookies in your browser");
                }

                $("#loading").addClass("d-none");
                $("#login-btn").prop("disabled", false);

            }, 1000);

            toast(response.message, 5, "text-success");
        },
        error: function (response) {
            toast(response.responseJSON?.message || response.statusText, 5, "text-danger");
            $("#loading").addClass("d-none");
            $("#login-btn").prop("disabled", false);
            $("#error").removeClass("d-none");
            $("#error").text(response.responseJSON?.message || response.statusText);
        },
    });

});