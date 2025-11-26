// ===============================
// TEMP DATA (replace with backend)
// ===============================
const assignedReports = [
    {
        id: 101,
        type: "Fire Incident",
        priority: "High",
        status: "Pending",
        location: "Brgy. Malaya",
        lat: 13.835,
        lng: 121.218,
        assigned: "2 mins ago"
    },
    {
        id: 102,
        type: "Medical Emergency",
        priority: "Medium",
        status: "In Progress",
        location: "Brgy. Sta. Cruz",
        lat: 13.832,
        lng: 121.221,
        assigned: "10 mins ago"
    },
    {
        id: 103,
        type: "Road Accident",
        priority: "Low",
        status: "Pending",
        location: "Brgy. Banay-banay",
        lat: 13.830,
        lng: 121.215,
        assigned: "25 mins ago"
    }
];

const urgentReports = assignedReports.filter(r => r.priority === "High");


// ===============================
// Fill Dashboard Numbers
// ===============================
document.getElementById("pendingCount").textContent =
    assignedReports.filter(r => r.status === "Pending").length;

document.getElementById("inProgressCount").textContent =
    assignedReports.filter(r => r.status === "In Progress").length;

document.getElementById("completedTodayCount").textContent = 4;  // demo value
document.getElementById("totalCompletedCount").textContent = 89; // demo value


// ===============================
// Fill Latest Assigned Table
// ===============================
const latestBody = document.getElementById("latestReportsBody");

assignedReports.forEach(r => {
    latestBody.innerHTML += `
        <tr>
            <td>${r.id}</td>
            <td>${r.type}</td>
            <td>${r.status}</td>
            <td>${r.priority}</td>
            <td>${r.location}</td>
            <td>${r.assigned}</td>
        </tr>
    `;
});


// ===============================
// Fill Urgent Reports Table
// ===============================
const urgentBody = document.getElementById("urgentReportsBody");

urgentReports.forEach(r => {
    urgentBody.innerHTML += `
        <tr>
            <td>${r.id}</td>
            <td>${r.type}</td>
            <td style="color:#e74c3c; font-weight:700;">${r.priority}</td>
            <td>${r.location}</td>
        </tr>
    `;
});


// ===============================
// MAP INITIALIZATION
// ===============================
const map = L.map("reportMap").setView([13.834, 121.218], 13);

L.tileLayer("https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=6cbe5b314ed44817b7e1e51d35b6ec27")
    .addTo(map);

// Add markers for each report
assignedReports.forEach(report => {
    L.marker([report.lat, report.lng])
        .addTo(map)
        .bindPopup(`
            <b>${report.type}</b><br>
            Priority: ${report.priority}<br>
            Status: ${report.status}<br>
            Location: ${report.location}
        `);
});
