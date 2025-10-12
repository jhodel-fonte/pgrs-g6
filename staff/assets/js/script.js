// ==========================
// Unity Padre Garcia Service Report System
// Frontend Map + Location Script (Free Version - OpenStreetMap)
// ==========================

// Initialize map variables
let map;
let marker;

// Initialize the map after page loads
document.addEventListener("DOMContentLoaded", () => {
  initMap();
});

function initMap() {
  // Default location (Padre Garcia)
  const defaultLat = 13.8799;
  const defaultLng = 121.2169;

  // Create the map
  map = L.map("map").setView([defaultLat, defaultLng], 15);

  // Add OpenStreetMap tiles
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "© OpenStreetMap contributors"
  }).addTo(map);

  // Add a draggable marker
  marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

  // Try to get user's location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function (position) {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        map.setView([userLat, userLng], 16);
        marker.setLatLng([userLat, userLng]);
        updateLocation(userLat, userLng);
      },
      function () {
        updateLocation(defaultLat, defaultLng);
      }
    );
  } else {
    updateLocation(defaultLat, defaultLng);
  }

  // Update when marker is moved
  marker.on("dragend", function (e) {
    const newPos = marker.getLatLng();
    updateLocation(newPos.lat, newPos.lng);
  });
}

// ==========================
// Reverse Geocoding Function
// ==========================
function updateLocation(lat, lng) {
  document.getElementById("latitude").value = lat;
  document.getElementById("longitude").value = lng;

  // Use OpenStreetMap Nominatim API to get address
  fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`, {
    headers: {
      "User-Agent": "UnityPGServiceReport/1.0 (your_email@example.com)"
    }
  })
    .then((response) => response.json())
    .then((data) => {
      if (data && data.display_name) {
        document.getElementById("location").value = data.display_name;
      } else {
        document.getElementById("location").value = "Address not found";
      }
    })
    .catch((error) => {
      console.error("Geocoding error:", error);
      document.getElementById("location").value = "Unable to get address";
    });
}
