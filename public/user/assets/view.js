document.addEventListener("DOMContentLoaded", () => {

    const viewButtons = document.querySelectorAll(".view-btn");
    const modal = new bootstrap.Modal(document.getElementById("viewModal"));

    viewButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            let report = JSON.parse(btn.dataset.report);

            document.getElementById("modalType").textContent = report.type;
            document.getElementById("modalStatus").textContent = report.status;
            document.getElementById("modalDate").textContent = report.date;
            document.getElementById("modalLocation").textContent = report.location;
            document.getElementById("modalDescription").textContent = report.description;
            document.getElementById("modalImage").src = "../user/uploads/" + report.image;

            modal.show();
        });
    });

    // DEMO ONLY: Edit + Delete (no backend yet)
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            alert("Edit function triggered (demo only).");
        });
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            if (confirm("Are you sure you want to delete this report?")) {
                alert("Report deleted (demo only).");
            }
        });
    });

});
