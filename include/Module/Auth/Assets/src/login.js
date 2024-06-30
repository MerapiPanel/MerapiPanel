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

        __.http.post(window.location.href, {
            ...MAuth.payload,
            ...{
                email: email,
                password: password
            }
        })
            .then((response) => {
                if (response.status && __.cookie.cookie_get("auth")) {
                    window.location.reload();
                } else {
                    __.toast("Login failed, please contact support!", 5, 'text-danger');
                }
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

$("#login-form").on("submit", submitHandler);
