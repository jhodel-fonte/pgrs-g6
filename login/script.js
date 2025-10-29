$(document).ready(function() {
    console.log("Script loaded!");

    // Registration Validation
    $("#registerForm").validate({
        rules: {
            name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            password: { required: true, minlength: 6 },
            confirm_password: { required: true, equalTo: "#password" },
        },
        messages: {
            name: { required: "Please enter your name", minlength: "Name must be at least 3 characters" },
            email: { required: "Please enter your email", email: "Enter a valid email address" },
            password: { required: "Please provide a password", minlength: "Password must be at least 6 characters" },
            confirm_password: { required: "Please confirm your password", equalTo: "Passwords do not match" }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.closest(".input-group"));
        },
        highlight: function(element) {
            $(element).css("border-color", "red");
        },
        unhighlight: function(element) {
            $(element).css("border-color", "#ccc");
        },
    });
});
