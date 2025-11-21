document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.user-sidebar');
    const toggleBtn = document.querySelector('.sidebarToggle');

    if (!sidebar || !toggleBtn) return;

    // Toggle sidebar on mobile
    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('active');
    });

    // Click outside to close (mobile only)
    document.addEventListener('click', (e) => {
        if (window.innerWidth > 768) return; // desktop: do nothing
        if (!sidebar.classList.contains('active')) return;

        if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });

    // Remove active when resizing to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
        }
    });
});
