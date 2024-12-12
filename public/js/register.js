document.addEventListener("DOMContentLoaded", function () {
    const signupForm = document.getElementById("signupForm");

    signupForm.addEventListener("submit", function (event) {
        // Get form field values
        const fullName = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        const role = document.getElementById("role_id").value;

        // Initialize validation state and error messages
        let isValid = true;
        let errorMessages = [];

        // Full Name Validation
        if (fullName === "") {
            isValid = false;
            errorMessages.push("Full Name is required.");
        }

        // Email Validation: Must end with @gmail.com
        const emailRegex = /^[^\s@]+@gmail\.com$/;
        if (!emailRegex.test(email)) {
            isValid = false;
            errorMessages.push("Email must end with @gmail.com.");
        }

        // Password Validation
        const passwordRegex = /^(?=.*[A-Z])(?=(.*\d){3,})(?=.*[!@#\$%\^&\*\|])[A-Za-z\d!@#\$%\^&\*\|]{8,}$/;
        if (!passwordRegex.test(password)) {
            isValid = false;
            errorMessages.push(
                "Password must be at least 8 characters long, contain an uppercase letter, at least 3 digits, and a special character (!@#$%^&*|)."
            );
        }

        // Confirm Password Validation
        if (password !== confirmPassword) {
            isValid = false;
            errorMessages.push("Passwords do not match.");
        }

        // Role Validation
        if (role === "") {
            isValid = false;
            errorMessages.push("Please select a role.");
        }

        // If validation fails, prevent form submission and show errors
        if (!isValid) {
            event.preventDefault(); // Prevent form submission
            alert("Form submission failed:\n\n" + errorMessages.join("\n"));
        }
        // If valid, the form will submit normally
    });
});