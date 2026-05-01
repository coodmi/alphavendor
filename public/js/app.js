document.addEventListener('DOMContentLoaded', function() {
    // Slider functionality
    let currentSlide = 0;
    const slides = document.querySelectorAll(".slide");
    const dots   = document.querySelectorAll(".dot");

    function showSlide(n) {
        slides.forEach(s => s.classList.remove("active"));
        dots.forEach(d => d.classList.remove("active"));
        currentSlide = (n + slides.length) % slides.length;
        if (slides[currentSlide]) slides[currentSlide].classList.add("active");
        if (dots[currentSlide])   dots[currentSlide].classList.add("active");
    }

    document.querySelector(".slider-btn.next")?.addEventListener("click", () => showSlide(currentSlide + 1));
    document.querySelector(".slider-btn.prev")?.addEventListener("click", () => showSlide(currentSlide - 1));
    dots.forEach((dot, i) => dot.addEventListener("click", () => showSlide(i)));

    if (slides.length > 1) {
        setInterval(() => showSlide(currentSlide + 1), 5000);
    }
});
