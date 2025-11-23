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

function toggleProfileMenu() {
    const menu = document.getElementById("profileDropdown");
    menu.style.display = menu.style.display === "flex" ? "none" : "flex";
}

document.addEventListener("click", function (event) {
    const profileMenu = document.getElementById("profileDropdown");
    const profileImg = document.querySelector(".profile-img");

    if (!profileMenu.contains(event.target) && event.target !== profileImg) {
        profileMenu.style.display = "none";
    }
});
// ito yung sa notification bell
function toggleNotifications() {
    // Example: open a dropdown or modal for notifications
    alert("Show notifications panel here!");
}

// Temporary demo notifications array
const demoNotifications = [
    { id: 1, message: "New user registered", time: "2 min ago" },
    { id: 2, message: "Server backup completed", time: "10 min ago" },
    { id: 3, message: "New report submitted", time: "1 hour ago" },
    { id: 4, message: "System maintenance scheduled", time: "3 hours ago" }
];

// Populate dropdown
function populateNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.innerHTML = ''; // Clear existing

    demoNotifications.forEach(notif => {
        const item = document.createElement('div');
        item.classList.add('notification-item');
        item.innerHTML = `
            <strong>${notif.message}</strong><br>
            <small>${notif.time}</small>
        `;
        dropdown.appendChild(item);
    });

    // Update badge count
    const badge = document.getElementById('notificationCount');
    badge.textContent = demoNotifications.length;
}

// Toggle dropdown visibility
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

// Close dropdown if clicked outside
document.addEventListener('click', function(event) {
    const bell = document.querySelector('.notification-bell');
    const dropdown = document.getElementById('notificationDropdown');

    if (!bell.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

// Initialize
populateNotifications();



function updateClock() {
    const dateElement = document.getElementById('dateDisplay');
    const now = new Date();

    // Format date and time
    const options = { 
        weekday: 'short', 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit', 
        minute: '2-digit', 
    };
    
    dateElement.textContent = now.toLocaleString('en-US', options);
}

// Update clock immediately
updateClock();

// Then update every second
setInterval(updateClock, 1000);

