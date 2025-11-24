<?php
session_start();

$userId = $_SESSION['user']['userprofile']['pgCode'];

$reports = [
    ["id" => 1, "user_id" => 1, "status" => "Pending"],
    ["id" => 2, "user_id" => 1, "status" => "Resolved"],
    ["id" => 3, "user_id" => 1, "status" => "Pending"],
    ["id" => 4, "user_id" => 1, "status" => "Resolved"],
    ["id" => 5, "user_id" => 2, "status" => "Pending"],
];
$total = 0;
$pending = 0;
$resolved = 0;

foreach ($reports as $r) {
    if ($r["user_id"] == $userId) {
        $total++;
        if ($r["status"] === "Pending") $pending++;
        if ($r["status"] === "Resolved") $resolved++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Unity PGSRS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../user/assets/user.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

<div class="dash-content">
    <div class="container">

        <h2 class="text-center dashboard-title">Dashboard Overview</h2>

        <div class="row g-4 mb-4 mt-2 justify-content-center">

            <div class="col-md-3">
                <div class="admin-card">
                    <h1 class="count" ><?= $total ?></h1>
                    <p>Total Reports</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="admin-card">
                    <h1 class="count"><?= $pending ?></h1>
                    <p>Pending Reports</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="admin-card">
                        <h1 class="count"><?= $resolved ?></h1>
                    <p>Resolved Reports</p>
                </div>
           </div>
        </div>

            </section>
        </main>
    </div>
</div>

<script src="../user/assets/user.js"></script>
</body>
</html>
