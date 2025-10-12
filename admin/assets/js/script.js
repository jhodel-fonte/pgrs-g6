// =============================
// Admin Dashboard JS
// =============================

let map;

document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("map")) {
    initMap();
    loadDashboardData();
  }

  if (document.getElementById("reportTable")) {
    loadReportTable();
  }
});

// =============================
// Sidebar Toggle
// =============================
function toggleSidebar() {
  document.querySelector(".sidebar").classList.toggle("active");
}

// =============================
// Initialize OpenStreetMap
// =============================
function initMap() {
  const defaultLat = 13.8799;
  const defaultLng = 121.2169;

  map = L.map("map").setView([defaultLat, defaultLng], 13);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "© OpenStreetMap contributors"
  }).addTo(map);

  fetch("get_reports.php")
    .then(res => res.json())
    .then(data => {
      data.forEach(r => {
        L.marker([r.latitude, r.longitude])
          .addTo(map)
          .bindPopup(`<strong>${r.service_type}</strong><br>${r.location}<br>Status: ${r.status}`);
      });
    });
}

// =============================
// Dashboard Stats (Counts)
// =============================
function loadDashboardData() {
  fetch("get_reports.php")
    .then(res => res.json())
    .then(data => {
      const total = data.length;
      const pending = data.filter(r => r.status === "Pending").length;
      const completed = data.filter(r => r.status === "Completed").length;

      document.getElementById("totalReports").textContent = total;
      document.getElementById("pendingReports").textContent = pending;
      document.getElementById("completedReports").textContent = completed;
      document.getElementById("activeStaff").textContent = "5"; // placeholder
    });
}

// =============================
// Load Reports Table
// =============================
function loadReportTable() {
  fetch("get_reports.php")
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector("#reportTable tbody");
      tbody.innerHTML = "";

      data.forEach(r => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${r.id}</td>
          <td>${r.service_type}</td>
          <td>${r.location}</td>
          <td>${r.status}</td>
          <td>${r.date_reported}</td>
          <td>
            <button class="btn btn-sm btn-primary" onclick="viewReport(${r.id})">View</button>
          </td>
        `;
        tbody.appendChild(tr);
      });
    });
}

function viewReport(id) {
  Swal.fire({
    title: "Report #" + id,
    text: "This will open the detailed report view soon.",
    icon: "info"
  });
}

document.addEventListener("DOMContentLoaded", () => {
  loadReportTable();
});

function toggleSidebar() {
  document.querySelector(".sidebar").classList.toggle("active");
}

function loadReportTable() {
  fetch("get_reports.php")
    .then(res => res.json())
    .then(data => {
      const tbody = document.querySelector("#reportTable tbody");
      tbody.innerHTML = "";

      data.forEach(r => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
          <td>${r.id}</td>
          <td>${r.service_type}</td>
          <td>
            <div style="font-size:0.9em; color:#333;">${r.location}</div>
            <div id="map-${r.id}" class="mini-map mt-2"></div>
          </td>
          <td>
            ${r.photo 
              ? `<img src="../uploads/${r.photo}" class="photo-thumb" alt="Report Photo">`
              : `<span class="text-muted">No photo</span>`}
          </td>
          <td>${r.status}</td>
          <td>${r.date_reported}</td>
          <td>
            <button class="btn btn-sm btn-primary" onclick="viewReport(${r.id})">View</button>
          </td>
        `;
        tbody.appendChild(tr);

        // Initialize mini map for each row
        if (r.latitude && r.longitude) {
          const map = L.map(`map-${r.id}`, {
            attributionControl: false,
            zoomControl: false,
            dragging: false
          }).setView([r.latitude, r.longitude], 14);
          L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19
          }).addTo(map);
          L.marker([r.latitude, r.longitude]).addTo(map);
        }
      });
    })
    .catch(err => console.error("Error loading reports:", err));
}

function viewReport(id) {
  Swal.fire({
    title: "Report #" + id,
    text: "Detailed report view coming soon!",
    icon: "info"
  });
}
