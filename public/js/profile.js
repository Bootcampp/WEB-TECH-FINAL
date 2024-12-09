document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const fullNameInput = document.getElementById("full_name");
    const emailInput = document.getElementById("email");
    const contactNumberInput = document.getElementById("contact_number");
    const bioInput = document.getElementById("bio");
    const profilePictureInput = document.getElementById("profile_picture");

    form.addEventListener("submit", function (event) {
        let isValid = true;
        let errorMessages = [];

        // Validate Full Name
        if (fullNameInput.value.trim() === "") {
            isValid = false;
            errorMessages.push("Full Name is required.");
        }

        // Validate Email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value.trim())) {
            isValid = false;
            errorMessages.push("Please enter a valid email address.");
        }

        // Validate Contact Number
        const contactPattern = /^\+?\d{10,15}$/; // Supports international numbers
        if (!contactPattern.test(contactNumberInput.value.trim())) {
            isValid = false;
            errorMessages.push("Please enter a valid contact number (10-15 digits).");
        }

        // Validate Bio
        if (bioInput.value.trim() === "") {
            isValid = false;
            errorMessages.push("Bio cannot be empty. Please tell us about yourself.");
        }

        // Validate Profile Picture File Type
        if (profilePictureInput.files.length > 0) {
            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (!allowedTypes.includes(profilePictureInput.files[0].type)) {
                isValid = false;
                errorMessages.push("Profile picture must be a JPEG, PNG, or GIF image.");
            }
        }

        // Prevent Form Submission if Validation Fails
        if (!isValid) {
            event.preventDefault();
            alert("Form submission failed:\n\n" + errorMessages.join("\n"));
        }
    });
});
