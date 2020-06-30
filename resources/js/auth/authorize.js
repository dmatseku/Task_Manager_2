$(document).ready(function() {
    $("#register-btn").focusin(function() {
        if ($("#register-form").css("display") === "none") {
            $("#register-btn").removeClass("btn-dark").addClass("btn-light");
            $("#login-btn").removeClass("btn-light").addClass("btn-dark");
            $("#register-form").removeClass("d-none");
            $("#login-form").addClass("d-none");
        }
    });

    $("#login-btn").focusin(function() {
        if ($("#login-form").css("display") === "none") {
            $("#register-btn").removeClass("btn-light").addClass("btn-dark");
            $("#login-btn").removeClass("btn-dark").addClass("btn-light");
            $("#register-form").addClass("d-none");
            $("#login-form").removeClass("d-none");
        }
    });
});
