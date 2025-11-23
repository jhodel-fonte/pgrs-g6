document.addEventListener("DOMContentLoaded", () => {
    // =========================
    // SIDEBAR TOGGLE
    // =========================
    const toggleBtn = document.querySelector(".sidebar-toggle");
    const sidebar = document.querySelector(".sidebar");

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("active");
        });
    }

    // =========================
    // AUTO CLOSE SIDEBAR ON LINK CLICK (MOBILE)
    // =========================
    const sidebarLinks = document.querySelectorAll(".sidebar a");
    sidebarLinks.forEach(link => {
        link.addEventListener("click", () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove("active");
            }
        });
    });

    // =========================
    // REPORT CARD HOVER EFFECT
    // Optional: subtle scale effect on hover
    // =========================
    const cards = document.querySelectorAll(".report-card");
    cards.forEach(card => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "scale(1.03)";
        });
        card.addEventListener("mouseleave", () => {
            card.style.transform = "scale(1)";
        });
    });
});
