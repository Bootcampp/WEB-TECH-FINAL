document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const nameInput = document.getElementById("name");
    const styleSelect = document.getElementById("style");
    const newStyleInput = document.getElementById("new_style");
    const priceInput = document.getElementById("price");
    const imageInput = document.getElementById("image");

    form.addEventListener("submit", function (event) {
        let isValid = true;
        let errorMessages = [];

        // Validate Dress Name
        if (nameInput.value.trim() === "") {
            isValid = false;
            errorMessages.push("Dress name is required.");
        }

        // Validate Style Selection or New Style
        if (styleSelect.value === "" && newStyleInput.value.trim() === "") {
            isValid = false;
            errorMessages.push("Please select a style or enter a new style.");
        }

        // Validate New Style (if entered)
        if (newStyleInput.value.trim() !== "" && !/^[a-zA-Z\s]+$/.test(newStyleInput.value.trim())) {
            isValid = false;
            errorMessages.push("New style must be a string containing only letters and spaces.");
        }

        // Validate Price
        if (priceInput.value.trim() === "" || isNaN(priceInput.value) || parseFloat(priceInput.value) <= 0) {
            isValid = false;
            errorMessages.push("Please enter a valid positive price.");
        }

        // Validate Image Upload
        if (imageInput.files.length === 0) {
            isValid = false;
            errorMessages.push("Please upload an image of the design.");
        } else {
            // Check if file type is valid
            const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
            const fileName = imageInput.files[0].name;
            if (!allowedExtensions.exec(fileName)) {
                isValid = false;
                errorMessages.push("Please upload a valid image file (JPG, JPEG, PNG, or GIF).");
            }
        }

        // Prevent Form Submission if Validation Fails
        if (!isValid) {
            event.preventDefault();
            alert("Form submission failed:\n\n" + errorMessages.join("\n"));
        }
    });
});
