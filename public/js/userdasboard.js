document.addEventListener("DOMContentLoaded", () => {
    const designCards = document.querySelectorAll(".design-card");
    const saveButtons = document.querySelectorAll(".save-btn");
    const purchaseButtons = document.querySelectorAll(".purchase-btn");
    const modal = document.getElementById("dress-details-modal");
    const modalClose = document.querySelector(".modal-close");

    // Handle design card click to show full details
    designCards.forEach((card) => {
        card.addEventListener("click", function(event) {
            // Prevent modal from opening if save or purchase button is clicked
            if (event.target.classList.contains('save-btn') || 
                event.target.classList.contains('purchase-btn')) {
                return;
            }

            const dressName = this.querySelector('h3').textContent;
            const designerName = this.querySelector('.designer-name').textContent;
            const styleName = this.querySelector('.style-name').textContent;
            const price = this.querySelector('.price').textContent;
            const imageUrl = this.querySelector('.design-image').src;

            // Populate modal with dress details
            document.getElementById('modal-dress-image').src = imageUrl;
            document.getElementById('modal-dress-name').textContent = dressName;
            document.getElementById('modal-designer-name').textContent = designerName;
            document.getElementById('modal-style-name').textContent = styleName;
            document.getElementById('modal-price').textContent = price;

            // Show the modal
            modal.style.display = 'block';
        });
    });

    // Close modal when close button is clicked
    modalClose.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside of it
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Handle "Save to Favorites"
    saveButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            alert("Design saved to your favorites!");
        });
    });

    // Handle "Purchase"
    purchaseButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const dressId = this.getAttribute('data-dress-id');
            const designCard = this.closest('.design-card');
            const dressName = designCard.querySelector('h3').textContent;
            const dressPrice = designCard.querySelector('.price').textContent;
            
            // Confirm purchase with more details
            const confirmPurchase = confirm(`Are you sure you want to purchase:\n\n${dressName}\n${dressPrice}?`);
            
            if (!confirmPurchase) {
                return;
            }

            // Send purchase request
            fetch('../actions/purchase.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `dress_id=${dressId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Create a more detailed success message
                    alert(`Purchase Successful!\n\nDress: ${data.dress_name}\nStyle: ${data.style_name}\nPrice: $${data.price}`);
                    
                    // Remove the purchased dress from the gallery
                    designCard.remove();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during purchase.');
            });
        });
    });
});