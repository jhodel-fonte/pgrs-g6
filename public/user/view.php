<?php
// TEMP DATA (Replace later with database)
$myReports = [
    [
        "id" => 1,
        "type" => "Fire",
        "status" => "Pending",
        "description" => "Fire near barangay hall.",
        "date" => "Nov 25, 2025",
        "location" => "Barangay Hall",
        "image" => "fire.jpg"
    ],
    [
        "id" => 2,
        "type" => "Accident",
        "status" => "Resolved",
        "description" => "Two vehicles collided.",
        "date" => "Nov 20, 2025",
        "location" => "National Highway",
        "image" => "accident.jpg"
    ],
    [
        "id" => 3,
        "type" => "Rescue",
        "status" => "Pending",
        "description" => "Dog stuck in drainage.",
        "date" => "Nov 23, 2025",
        "location" => "San Jose St.",
        "image" => "rescue.jpg"
    ],
    [
        "id" => 4,
        "type" => "Others",
        "status" => "Resolved",
        "description" => "Fallen tree blocking road.",
        "date" => "Nov 18, 2025",
        "location" => "Brgy. Mabini",
        "image" => "tree.jpg"
    ],
    [
        "id" => 5,
        "type" => "Rescue",
        "status" => "Pending",
        "description" => "Dog stuck in drainage.",
        "date" => "Nov 23, 2025",
        "location" => "San Jose St.",
        "image" => "rescue.jpg"
    ],
    [
        "id" => 3,
        "type" => "Rescue",
        "status" => "Pending",
        "description" => "Dog stuck in drainage.",
        "date" => "Nov 23, 2025",
        "location" => "San Jose St.",
        "image" => "rescue.jpg"
    ],
    [
        "id" => 3,
        "type" => "Rescue",
        "status" => "Pending",
        "description" => "Dog stuck in drainage.",
        "date" => "Nov 23, 2025",
        "location" => "San Jose St.",
        "image" => "rescue.jpg"
    ],
    [
        "id" => 3,
        "type" => "Rescue",
        "status" => "Pending",
        "description" => "Dog stuck in drainage.",
        "date" => "Nov 23, 2025",
        "location" => "San Jose St.",
        "image" => "rescue.jpg"
    ],
    [
        "id" => 3,
        "type" => "Rescue",
        "status" => "Pending",
        "description" => "Dog stuck in drainage.",
        "date" => "Nov 23, 2025",
        "location" => "San Jose St.",
        "image" => "rescue.jpg"
    ],

];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Reports | Unity Padre Garcia</title>

<link rel="stylesheet" href="../user/assets/user.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

</head>
<body>

<?php include 'sidebar.php'; ?>

<main class="user-main">

    <div class="page-wrapper">
        <h1 class="page-title">My Reports</h1>

        <div class="report-list-card scrollable-table shadow-sm">

            <table class="table table-hover align-middle">
                <thead class="table-dark ">
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($myReports as $r): ?>
                    <tr>
                        <td><?= $r["id"]; ?></td>
                        <td><?= $r["type"]; ?></td>
                        <td>
                            <?php if ($r["status"] === "Resolved"): ?>
                                <span class="badge bg-success">Resolved</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $r["date"]; ?></td>

                        <td class="text-center">

                            <!-- View Button -->
                            <button 
                                class="btn btn-sm btn-primary view-btn"
                                data-report='<?= json_encode($r); ?>'>
                                View
                            </button>

                            <!-- Edit + Delete only if NOT resolved -->
                            <?php if ($r["status"] !== "Resolved"): ?>
                                <button class="btn btn-sm btn-outline-secondary edit-btn">Edit</button>
                                <button class="btn btn-sm btn-outline-danger delete-btn">Delete</button>
                            <?php endif; ?>

                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

</main>



<!-- VIEW MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Report Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p><strong>Type:</strong> <span id="modalType"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Date:</strong> <span id="modalDate"></span></p>
        <p><strong>Location:</strong> <span id="modalLocation"></span></p>
        <p><strong>Description:</strong></p>
        <p id="modalDescription"></p>

        <img id="modalImage" class="img-fluid rounded mt-3" alt="Report Image">
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>

    </div>

  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../user/assets/user.js"></script>
</body>
</html>
