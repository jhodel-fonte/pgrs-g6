document.addEventListener("DOMContentLoaded", () => {

    const toggleBtn = document.querySelector(".sidebar-toggle");
    const sidebar = document.querySelector(".sidebar");
    const container = document.querySelector(".container");

    // =========================
    // SIDEBAR TOGGLE
    // =========================
    if (toggleBtn && sidebar && container) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("active");
            container.classList.toggle("shifted");
        });
    }

    // =========================
    // CLOSE SIDEBAR WHEN CLICKING ANY LINK (MOBILE ONLY)
    // =========================
    document.querySelectorAll(".sidebar a").forEach(link => {
        link.addEventListener("click", () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove("active");
                container.classList.remove("shifted");
            }
        });
    });

    // =========================
    // REMOVE SHIFT WHEN RESIZING BACK TO DESKTOP
    // =========================
    window.addEventListener("resize", () => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove("active");
            container.classList.remove("shifted");
        }
    });

});
