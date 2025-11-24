<?php
session_start();

/* -------------------------------------------------
   🔧 SIMULATED USER SESSION (DEMO)
   Remove this when login system exists
--------------------------------------------------- */
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        "userprofile" => [
            "pgCode" => 1,
            "username" => "DemoUser"
        ]
    ];
}

$userId = $_SESSION['user']['userprofile']['pgCode'];

/* -------------------------------------------------
   📦 DEMO REPORT ARRAY (REPLACES DATABASE)
--------------------------------------------------- */
$reports = [
    ["id" => 1, "user_id" => 1, "status" => "Pending"],
    ["id" => 2, "user_id" => 1, "status" => "Resolved"],
    ["id" => 3, "user_id" => 1, "status" => "Pending"],
    ["id" => 4, "user_id" => 1, "status" => "Resolved"],
    ["id" => 5, "user_id" => 2, "status" => "Pending"], // belongs to another user
];

/* -------------------------------------------------
   📊 COUNT USER REPORTS BASED ON ARRAY
--------------------------------------------------- */
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

<div class="container">
    <?php include 'sidebar.php'; ?>

    <main class="main-content">

        <section class="cards p-4">

            <div class="card total">
                <h3>Total Reports</h3>
                <p><?= $total; ?></p>
            </div>
            
            <div class="card pending">
                <h3>Pending Reports</h3>
                <p><?= $pending; ?></p>
            </div>

            <div class="card resolved">
                <h3>Resolved Reports</h3>
                <p><?= $resolved; ?></p>
            </div>

        </section>
    </main>
</div>

<script src="../user/assets/user.js"></script>
</body>
</html>
