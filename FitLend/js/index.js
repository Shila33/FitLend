// index.js

document.addEventListener('DOMContentLoaded', () => {
    const reviewsSlider = document.querySelector('.reviews-slider');
    const reviewCards = Array.from(reviewsSlider.children);
    const slideSpeed = 0.3; // Speed of sliding
    const slideInterval = 3000; // Time between each slide in milliseconds

    let currentIndex = 0;

    function slideReviews() {
        // Calculate the width of two review cards
        const slideWidth = reviewCards[0].offsetWidth + reviewCards[1].offsetWidth;
        
        // Set the transform style to slide the reviews to the left
        reviewsSlider.style.transition = `transform ${slideSpeed}s ease`;
        reviewsSlider.style.transform = `translateX(-${slideWidth}px)`;

        // After the transition, move the first two reviews to the end and reset the transform
        setTimeout(() => {
            reviewsSlider.style.transition = 'none';
            reviewsSlider.style.transform = 'translateX(0)';
            // Move the first two reviews to the end of the list
            for (let i = 0; i < 2; i++) {
                reviewsSlider.appendChild(reviewCards.shift());
                reviewCards.push(reviewsSlider.children[reviewCards.length - 1]);
            }
        }, slideSpeed * 1000);
    }

    // Set an interval to continuously slide the reviews
    setInterval(slideReviews, slideInterval);
});
