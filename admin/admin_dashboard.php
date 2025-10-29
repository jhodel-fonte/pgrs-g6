<?php
session_start();
require_once '../config/config.php';


// Optional: Restrict access if you plan to create an admin login later
// if (!isset($_SESSION['admin_logged_in'])) {
//     header("Location: ../login/login.php");
//     exit();
// }

// Fetch reports
try {
    $stmt = $pdo->query("SELECT * FROM reports ORDER BY created_at DESC");
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}

// Fetch users (fixed: changed fullname → name)
try {
    $user_stmt = $pdo->query("SELECT id, name, email, created_at FROM users ORDER BY created_at DESC");
    $users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("User query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Unity Padre Garcia Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: "Open Sans", sans-serif;
            display: flex;
            background-color: #f5f9ff;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #004aad;
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            display: block;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #1e64d8;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        h1 {
            color: #004aad;
            margin-bottom: 10px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        table th, table td {
            border-bottom: 1px solid #eee;
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #e8f0ff;
            color: #004aad;
        }

        tr:hover {
            background-color: #f1f7ff;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
            font-size: 13px;
        }

        .status-pending { background: #f0ad4e; }
        .status-progress { background: #0275d8; }
        .status-completed { background: #5cb85c; }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            color: white;
        }

        .btn-update { background-color: #004aad; }
        .btn-logout { background-color: #d9534f; }
        .btn-update:hover { background-color: #1e64d8; }
        .btn-logout:hover { background-color: #c9302c; }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php" class="active">Dashboard</a></li>
                <li><a href="../admin/view_report.php">View Report</a></li>
                <li><a href="../login/logout.php">Logout</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h1>Admin Dashboard</h1>
            <p>Monitor and manage user reports</p>

            <!-- Reports Section -->
            <div class="card">
                <h2>All Reports</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Reporter</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?= htmlspecialchars($report['id']) ?></td>
                        <td><?= htmlspecialchars($report['name']) ?></td>
                        <td><?= htmlspecialchars($report['report_type']) ?></td>
                        <td><?= htmlspecialchars($report['location']) ?></td>
                        <td>
                            <span class="status-badge 
                                <?= $report['status'] == 'Pending' ? 'status-pending' : 
                                    ($report['status'] == 'In Progress' ? 'status-progress' : 'status-completed') ?>">
                                <?= htmlspecialchars($report['status']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($report['created_at']) ?></td>
                        <td>
                            <form method="POST" action="update_status.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $report['id'] ?>">
                                <select name="status" style="padding:5px; border-radius:5px;">
                                    <option value="Pending" <?= $report['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="In Progress" <?= $report['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="Resolved" <?= $report['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                </select>
                                <button class="btn btn-update" type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Users Section -->
            <div class="card">
                <h2>Registered Users</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Joined</th>
                    </tr>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
