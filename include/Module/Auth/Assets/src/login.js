const __ = window.__;

const MAuth = __.Auth;
MAuth.payload = {
    latitude: null,
    longitude: null,
    email: null
}

var delay;
function submitHandler(e) {
    if (delay) clearTimeout(delay);

    e.preventDefault();
    if (!this.checkValidity()) {
        return;
    }

    $("#loading").removeClass("d-none");
    $("#login-btn").prop("disabled", true);
    $("#error").addClass("d-none");


    var form = $(this);
    var url = form.attr("action");
    var email = form.find("[name='email']").val();
    var password = form.find("[name='password']").val();
    if (!email || email.length == 0) {
        __.toast("Email is required", 5, 'text-danger');
        $("#loading").addClass("d-none");
        $("#login-btn").prop("disabled", false);
        $("#error").removeClass("d-none");
        $("#error").text("Email is required");
        return;
    }
    if (!password || password.length == 0) {
        __.toast("Password is required", 5, 'text-danger');
        $("#loading").addClass("d-none");
        $("#login-btn").prop("disabled", false);
        $("#error").removeClass("d-none");
        $("#error").text("Password is required");
        return;
    }


    delay = setTimeout(function () {

        __.http.post(url, {
            ...MAuth.payload,
            ...{
                email: email,
                password: password
            }
        })
            .then((response) => {
                window.location.reload();
            })
            .catch((error) => {
                __.toast(error.message || "Error: Please try again!", 5, 'text-danger');
                $("#loading").addClass("d-none");
                $("#login-btn").prop("disabled", false);
                $("#error").removeClass("d-none");
                $("#error").text(error.message || "Error: Please try again!");
            })
        $("#login-form").off("submit", submitHandler).on("submit", submitHandler);

    }, 1000);
}



if (MAuth.config.geo) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (location) {
            MAuth.payload.latitude = location.coords.latitude;
            MAuth.payload.longitude = location.coords.longitude;
            $("#login-form").on("submit", submitHandler);
        }, function (error) {
            __.toast(error.message || "Error getting location", 5, "text-danger");
            $("#login-form").off("submit", submitHandler);
            $("[type='submit']").prop("disabled", true);
            $("input").prop("disabled", true);
        });
    } else {
        __.toast("Geolocation is not supported by this browser.", 5, "text-danger");
        $("#login-form").off("submit", submitHandler);
        $("[type='submit']").prop("disabled", true);
        $("input").prop("disabled", true);
    }
} else {
    $("#login-form").on("submit", submitHandler);
}

$("#login-form").on("submit", submitHandler);