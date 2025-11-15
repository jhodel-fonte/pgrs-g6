// ========== CHART ========== //
const ctx = document.getElementById('monthlyChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartMonths,
            datasets: [{
                label: "Reports Submitted",
                data: chartTotals,
                backgroundColor: "rgba(0, 255, 255, 0.5)",
                borderColor: "#00eaff",
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true },
                x: { ticks: { color: "#00eaff" } }
            }
        }
    });
}

// ========== COUNTER ANIMATION ========== //
document.querySelectorAll(".count").forEach(el => {
    const target = +el.dataset.value;
    let current = 0;
    let speed = Math.max(1, Math.floor(target / 50));

    let update = setInterval(() => {
        current += speed;
        if (current >= target) {
            current = target;
            clearInterval(update);
        }
        el.textContent = current;
    }, 20);
});

const modal = new bootstrap.Modal(document.getElementById("detailsModal"));
const modalTitle = document.getElementById("modalTitle");
const modalDescription = document.getElementById("modalDescription");
const modalCategory = document.getElementById("modalCategory");
const modalLocation = document.getElementById("modalLocation");
const modalImage = document.getElementById("modalImage");
const mapContainer = document.getElementById("mapContainer");

document.querySelectorAll(".view-details").forEach(btn => {
    btn.addEventListener("click", () => {
        const report = JSON.parse(btn.dataset.report);
        modalTitle.textContent = report.title;
        modalCategory.textContent = "Category: " + report.category;
        modalDescription.textContent = report.description || "No description provided.";
        modalLocation.textContent = report.location;
        modalImage.src = "../uploads/reports/" + report.image;

        // ✅ Show map if coordinates exist
        if (report.latitude && report.longitude) {
            mapContainer.innerHTML = `
                <iframe
                    width="100%"
                    height="100%"
                    style="border:0;"
                    loading="lazy"
                    allowfullscreen
                    src="https://www.google.com/maps?q=${report.latitude},${report.longitude}&hl=es;z=14&output=embed">
                </iframe>`;
        } else {
            mapContainer.innerHTML = "<p class='text-center text-muted'>No map location available.</p>";
        }

        modal.show();
    });
});

// ✅ SweetAlert for Approve / Reject / Delete
function confirmAction(action, id) {
    let msg = {
        approve: "Approve this report?",
        reject: "Reject this report? (It will be deleted.)",
        delete: "Permanently delete this report?"
    }[action];

    Swal.fire({
        title: msg,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#198754",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, continue"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `?action=${action}&id=${id}`;
        }
    });
}