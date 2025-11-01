$(document).ready(function() {
    // Registration
    $("#registerForm").validate({
        rules: {
            name: { required: true, minlength: 3 },
            email: { required: true, email: true },
            password: { required: true, minlength: 6 },
            confirm_password: { required: true, equalTo: "#password" },
        },
        messages: {
            name: { required: "Please enter your name", minlength: "Name must be at least 3 characters" },
            email: { required: "Please enter your email", email: "Please enter a valid email" },
            password: { required: "Please provide a password", minlength: "Password must be at least 6 characters" },
            confirm_password: { required: "Please confirm your password", equalTo: "Passwords do not match" }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.closest(".input-group"));
        },
        submitHandler: function(form) {
            localStorage.setItem("registeredUser", JSON.stringify({
                name: $("#name").val().trim(),
                email: $("#email").val().trim(),
                password: $("#password").val().trim()
            }));
            alert(" Registration successful! You can now Login.");
            window.location.href = "index.html";
        }
    });

    // Login
    $("#loginForm").validate({
        rules: {
            email: { required: true, email: true },
            password: { required: true, minlength: 6 }
        },
        messages: {
            email: { required: "Please enter your email", email: "Enter a valid email address" },
            password: { required: "Please provide your password", minlength: "Password must be at least 6 characters" }
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element.closest(".input-group"));
        },
        submitHandler: function(form) {
            let email = $("#email").val().trim();
            let password = $("#password").val().trim();
            const user = JSON.parse(localStorage.getItem("registeredUser"));

            if (user && email === user.email && password === user.password) {
                alert("Login successful!");
                localStorage.setItem("loggedInUser", JSON.stringify(user));
                window.location.href = "landing.html";
            } else {
                alert("Invalid email or password");
            }
        }
    });

    // Landing Page
    if ($(".landing-container").length) {
        const user = JSON.parse(localStorage.getItem("loggedInUser"));
        
        if (!user) {
            alert("Please login first." );
            window.location.href = "index.html";
            return;
        }
        $("#username").text(user.name);

        $("#logoutBtn").click(function () {
            localStorage.removeItem("loggedInUser");
            alert("You have logged out!");
            window.location.href = "index.html";
        });
    }
    
})