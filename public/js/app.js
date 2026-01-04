// Slider functionality
let currentSlide = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

function showSlide(n) {
    slides.forEach((slide) => slide.classList.remove("active"));
    dots.forEach((dot) => dot.classList.remove("active"));

    currentSlide = (n + slides.length) % slides.length;

    if (slides[currentSlide]) {
        slides[currentSlide].classList.add("active");
    }
    if (dots[currentSlide]) {
        dots[currentSlide].classList.add("active");
    }
}

// Next/Previous controls
document.querySelector(".slider-btn.next")?.addEventListener("click", () => {
    showSlide(currentSlide + 1);
});

document.querySelector(".slider-btn.prev")?.addEventListener("click", () => {
    showSlide(currentSlide - 1);
});

// Dot controls
dots.forEach((dot, index) => {
    dot.addEventListener("click", () => {
        showSlide(index);
    });
});

// Auto slide
setInterval(() => {
    showSlide(currentSlide + 1);
}, 5000);

// Category button dropdown (placeholder)
document.querySelector(".category-btn")?.addEventListener("click", () => {
    alert("Category dropdown coming soon!");
});
