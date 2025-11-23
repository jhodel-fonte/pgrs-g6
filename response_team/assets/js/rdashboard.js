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
    document.querySelectorAll(".sidebar a").forEach(link => {
        link.addEventListener("click", () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove("active");
            }
        });
    });
});
