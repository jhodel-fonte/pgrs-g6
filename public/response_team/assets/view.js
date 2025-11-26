/* rteam.js
   Replace existing rteam.js with this. Requires:
   - Leaflet loaded BEFORE this file
   - bootstrap bundle loaded (for modal)
   - HTML structure from assigned_reports.php (buttons with .btn-view / .btn-start / .btn-resolve etc.)
*/

const GEOAPIFY_KEY = "6cbe5b314ed44817b7e1e51d35b6ec27"; // <-- your key

// Basic DOM refs
const filterBtns = document.querySelectorAll(".filter-btn");
const cards = document.querySelectorAll(".report-card");
const searchInput = document.getElementById("searchInput");

// Filtering and search
filterBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        const active = document.querySelector(".filter-btn.active");
        if (active) active.classList.remove("active");
        btn.classList.add("active");
        filterCards();
    });
});
searchInput && searchInput.addEventListener("input", filterCards);

function filterCards() {
    const filter = document.querySelector(".filter-btn.active")?.dataset.filter || "All";
    const search = searchInput?.value?.toLowerCase() || "";

    cards.forEach(card => {
        const status = card.dataset.status;
        const type = card.dataset.type || "";
        const location = card.dataset.location || "";

        const matchesSearch = type.includes(search) || location.includes(search);
        const matchesFilter = (filter === "All") || (status === filter);

        card.style.display = (matchesSearch && matchesFilter) ? "block" : "none";
    });
}

// Modal map + route variables
let modalMap = null;
let routeLayer = null;
let markerLayer = null;

// Helper: update the status badge DOM in a card
function updateCardStatus(cardEl, newStatus) {
    cardEl.dataset.status = newStatus;
    const statusSpan = cardEl.querySelector(".status");
    if (!statusSpan) return;
    statusSpan.textContent = newStatus;

    // normalize for classes
    statusSpan.className = "status " + newStatus.toLowerCase().replace(/\s+/g, '-');

    // update buttons shown
    const startBtn = cardEl.querySelector(".btn-start");
    const resolveBtn = cardEl.querySelector(".btn-resolve");

    if (newStatus === "Pending") {
        startBtn && (startBtn.style.display = "inline-block");
        resolveBtn && (resolveBtn.style.display = "none");
    } else if (newStatus === "In Progress") {
        startBtn && (startBtn.style.display = "none");
        resolveBtn && (resolveBtn.style.display = "inline-block");
    } else { // Resolved / Completed
        startBtn && (startBtn.style.display = "none");
        resolveBtn && (resolveBtn.style.display = "none");
    }
}

// Action buttons (delegation)
document.addEventListener("click", async (e) => {
    // Start Response
    if (e.target.matches(".btn-start")) {
        const card = e.target.closest(".report-card");
        if (!card) return;
        if (!confirm("Start response for this report?")) return;

        updateCardStatus(card, "In Progress");
        e.target.textContent = "Started";

        refreshModalTimelineIfOpen(card);
    }

    // Mark Resolved
    if (e.target.matches(".btn-resolve")) {
        const card = e.target.closest(".report-card");
        if (!card) return;
        if (!confirm("Mark this report as Resolved?")) return;

        updateCardStatus(card, "Resolved");
        e.target.textContent = "Resolved";
        refreshModalTimelineIfOpen(card);
    }
});

// When clicking view
document.querySelectorAll(".btn-view").forEach(btn => {
    btn.addEventListener("click", async () => {
        const r = JSON.parse(btn.dataset.report);

        // Fill modal fields
        document.getElementById("modalType").textContent = r.type;
        document.getElementById("modalStatus").textContent = r.status;
        document.getElementById("modalLocation").textContent = r.location;
        document.getElementById("modalDate").textContent = r.date;
        document.getElementById("modalNotes").textContent = r.notes || "";
        document.getElementById("modalImage").src = "../assets/uploads/img/" + (r.image || "");
        document.getElementById("modalImage").alt = r.type;

        // Store coords globally for full map navigation
        window.modalLocationLat = r.lat;
        window.modalLocationLng = r.lng;

        // Show modal
        const bsModal = new bootstrap.Modal(document.getElementById("viewModal"));
        bsModal.show();

        // Map + timeline
        setTimeout(() => {
            openModalMapAndRoute(r);
            loadTimeline(r.status);
        }, 300);
    });
});

// Map + Route
async function openModalMapAndRoute(report) {
    const mapEl = document.getElementById("modalMap");
    if (!mapEl) return;

    if (!modalMap) {
        modalMap = L.map("modalMap", { zoomControl: true, attributionControl: false }).setView([report.lat, report.lng], 14);

        L.tileLayer(`https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=${GEOAPIFY_KEY}`, {
            maxZoom: 19,
            tileSize: 512,
            zoomOffset: -1
        }).addTo(modalMap);
    } else {
        if (routeLayer) modalMap.removeLayer(routeLayer);
        if (markerLayer) modalMap.removeLayer(markerLayer);
    }

    const reportLat = report.lat;
    const reportLng = report.lng;

    markerLayer = L.marker([reportLat, reportLng]).addTo(modalMap).bindPopup(`${report.type} — ${report.location}`).openPopup();

    if ("geolocation" in navigator) {
        try {
            const pos = await getCurrentPositionPromise({ enableHighAccuracy: true, timeout: 8000 });

            const originLat = pos.coords.latitude;
            const originLng = pos.coords.longitude;

            const waypoints = `${originLng},${originLat}|${reportLng},${reportLat}`;
            const url = `https://api.geoapify.com/v1/routing?waypoints=${encodeURIComponent(waypoints)}&mode=drive&format=geojson&apiKey=${GEOAPIFY_KEY}`;

            const resp = await fetch(url);
            const data = await resp.json();

            routeLayer = L.geoJSON(data, { style: { color: "#4e89ff", weight: 5 } }).addTo(modalMap);

            modalMap.fitBounds(routeLayer.getBounds(), { padding: [60, 60] });

            L.circleMarker([originLat, originLng], { radius: 6, fillColor: "#333", color: "#fff", weight: 2 }).addTo(modalMap);

        } catch (e) {
            modalMap.setView([reportLat, reportLng], 14);
        }
    } else {
        modalMap.setView([reportLat, reportLng], 14);
    }

    setTimeout(() => modalMap.invalidateSize(), 350);
}

function getCurrentPositionPromise(options = {}) {
    return new Promise((resolve, reject) =>
        navigator.geolocation.getCurrentPosition(resolve, reject, options)
    );
}

// Timeline rendering
function loadTimeline(status) {
    const timeline = document.getElementById("timeline");
    if (!timeline) return;

    timeline.innerHTML = "";
    const steps = ["Pending", "In Progress", "Completed"];

    steps.forEach(step => {
        const li = document.createElement("li");
        li.textContent = step;
        li.className = "timeline-step";

        if (status === "Pending" && step === "Pending") li.classList.add("done");
        if (status === "In Progress" && (step === "Pending" || step === "In Progress")) li.classList.add("done");
        if ((status === "Resolved" || status === "Completed")) li.classList.add("done");

        timeline.appendChild(li);
    });
}

function refreshModalTimelineIfOpen(card) {
    const modalVisible = document.querySelector("#viewModal.show");
    if (modalVisible) {
        const status = card.dataset.status;
        loadTimeline(status);
        const modalStatus = document.getElementById("modalStatus");
        if (modalStatus) modalStatus.textContent = status;
    }
}

// INITIAL filter pass
filterCards();

/* ----------------------------------------------------------
   🔥 NEW FEATURE ADDED — OPEN FULL SCREEN ROUTE MAP BUTTON
----------------------------------------------------------- */

document.getElementById("openRouteBtn")?.addEventListener("click", () => {
    if (!window.modalLocationLat || !window.modalLocationLng) {
        alert("Location coordinates missing.");
        return;
    }

    const destLat = window.modalLocationLat;
    const destLng = window.modalLocationLng;

    // Default starting point (or later replace with team GPS)
    const teamLat = 13.8345;
    const teamLng = 121.2190;

    // Redirect to full route map
    window.location.href =
        `route_map.php?teamLat=${teamLat}&teamLng=${teamLng}&destLat=${destLat}&destLng=${destLng}`;
});
