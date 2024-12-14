document.addEventListener('DOMContentLoaded', function() {
    // Add to Cart Functionality
    const addToCartButtons = document.querySelectorAll('.btn.save-btn');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const designCard = this.closest('.design-card');
            const dressId = designCard.getAttribute('data-dress-id');
            
            fetch('../actions/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `dress_id=${dressId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Dress added to cart successfully!');
                    // Optionally update cart count
                } else {
                    alert('Failed to add dress to cart. ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding to cart.');
            });
        });
    });

    // Dress Details Modal Functionality
    const designCards = document.querySelectorAll('.design-card');
    const modal = document.getElementById('dress-details-modal');
    const modalClose = document.querySelector('.modal-close');

    designCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Prevent modal if clicking on buttons
            if (e.target.classList.contains('btn')) return;

            const dressName = card.querySelector('h3').textContent;
            const designerName = card.querySelector('.designer-name').textContent;
            const styleName = card.querySelector('.style-name').textContent;
            const price = card.querySelector('.price').textContent;
            const imageSrc = card.querySelector('.design-image').src;

            document.getElementById('modal-dress-image').src = imageSrc;
            document.getElementById('modal-dress-name').textContent = dressName;
            document.getElementById('modal-designer-name').textContent = designerName;
            document.getElementById('modal-style-name').textContent = styleName;
            document.getElementById('modal-price').textContent = price;

            modal.style.display = 'block';
        });
    });

    // Close Modal
    modalClose.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Close modal if clicked outside
    window.addEventListener('click', function(e) {
        if (e.target == modal) {
            modal.style.display = 'none';
        }
    });
});