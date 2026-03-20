document.addEventListener("DOMContentLoaded", function () {
    function revealOnScroll() {
        let elements = document.querySelectorAll(".welcomesection, .feature-section, .user-said-section, .animated-section");
        let windowHeight = window.innerHeight;

        elements.forEach(function (el) {
            let elementTop = el.getBoundingClientRect().top;
            if (elementTop < windowHeight - 100) {
                el.classList.add("show");
            }
        });
    }

    window.addEventListener("scroll", revealOnScroll);
    revealOnScroll(); // Run on page load to check if elements are already in view
});
