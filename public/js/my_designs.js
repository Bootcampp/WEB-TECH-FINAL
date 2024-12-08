// Select the lightbox elements
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');
const closeBtn = document.querySelector('.close-btn');

// Handle click on design card
document.querySelectorAll('.design-card').forEach((card) => {
    card.addEventListener('click', (e) => {
        const imgSrc = card.getAttribute('data-image');
        lightboxImg.src = imgSrc;
        lightbox.style.display = 'flex';
    });
});

// Close lightbox
closeBtn.addEventListener('click', () => {
    lightbox.style.display = 'none';
});

// Close lightbox on outside click
lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) {
        lightbox.style.display = 'none';
    }
});
