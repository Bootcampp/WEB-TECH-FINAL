document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".edit-btn");
    const editModal = document.getElementById("editModal");
    const cancelModal = document.getElementById("cancelModal");
    const imageInput = document.getElementById("designImage");
    const imagePreview = document.getElementById("imagePreview");

    // Ensure all necessary elements exist
    if (!editModal || !cancelModal) {
        console.error("Modal elements not found.");
        return;
    }

    // Helper function to show modal
    function showModal() {
        editModal.style.display = "block";
        editModal.classList.add("modal"); // Add modal class for styling
    }

    // Helper function to hide modal
    function hideModal() {
        editModal.style.display = "none";
        editModal.classList.remove("modal");
    }

    // Open the modal when the "Edit" button is clicked
    editButtons.forEach(button => {
        button.addEventListener("click", (event) => {
            const card = button.closest(".design-card");

            if (!card) {
                console.error("Unable to find design card for button.");
                return;
            }

            const designData = JSON.parse(card.getAttribute("data-design"));

            document.getElementById("dressId").value = designData.dress_id;
            document.getElementById("designName").value = designData.name;
            document.getElementById("designStyle").value = designData.style_name;
            document.getElementById("designPrice").value = designData.price;

            // Set image preview (if any)
            if (designData.image_url) {
                imagePreview.style.display = 'block';
                imagePreview.src = designData.image_url;
            } else {
                imagePreview.style.display = 'none';
            }

            showModal();
        });
    });

    // Close the modal when the "Cancel" button is clicked
    cancelModal.addEventListener("click", hideModal);

    // Close modal if clicking outside the modal content
    editModal.addEventListener("click", (event) => {
        if (event.target === editModal) {
            hideModal();
        }
    });

    // Handle image preview on file selection
    imageInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };

            reader.readAsDataURL(file);
        }
    });
});