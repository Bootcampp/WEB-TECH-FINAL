document.addEventListener("DOMContentLoaded", () => {
    const saveButtons = document.querySelectorAll(".save-btn");
    const purchaseButtons = document.querySelectorAll(".purchase-btn");

    // Handle "Save to Favorites"
    saveButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            alert("Design saved to your favorites!");
        });
    });

    // Handle "Purchase"
    purchaseButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            alert("Redirecting to purchase page...");
            // Redirect to purchase page or process purchase logic
        });
    });
});
