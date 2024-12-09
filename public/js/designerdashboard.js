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

        // Validate Price
        if (priceInput.value.trim() === "" || parseFloat(priceInput.value) <= 0) {
            isValid = false;
            errorMessages.push("Please enter a valid positive price.");
        }

        // Validate Image Upload
        if (imageInput.files.length === 0) {
            isValid = false;
            errorMessages.push("Please upload an image of the design.");
        }

        // Prevent Form Submission if Validation Fails
        if (!isValid) {
            event.preventDefault();
            alert("Form submission failed:\n\n" + errorMessages.join("\n"));
        }
    });
});
