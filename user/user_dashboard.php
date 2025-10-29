<?php
session_start();
require_once '../config/config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit;
}

// Fetch counts from reports table
try {
    $total = $pdo->query("SELECT COUNT(*) FROM reports")->fetchColumn();
    $pending = $pdo->query("SELECT COUNT(*) FROM reports WHERE status = 'Pending'")->fetchColumn();
    $resolved = $pdo->query("SELECT COUNT(*) FROM reports WHERE status = 'Resolved'")->fetchColumn();
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Unity Padre Garcia Report System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Open Sans", sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #004aad;
            color: #fff;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 22px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #003380;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px;
            background: #f9f9f9;
        }

        .main-content header {
            margin-bottom: 30px;
        }

        .main-content header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }

        .main-content header p {
            color: #555;
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 28px;
            font-weight: 700;
        }

        .card.total {
            border-left: 5px solid #007bff;
        }
        .card.pending {
            border-left: 5px solid #ffc107;
        }
        .card.resolved {
            border-left: 5px solid #28a745;
        }
    </style>
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <h2>Unity Padre Garcia</h2>
        <ul>
            <li><a href="user_dashboard.php" class="active">Dashboard</a></li>
            <li><a href="../user/report.php">Submit Report</a></li>
            <li><a href="../user/view.php">My Reports</a></li>
            <li><a href="../login/logout.php">Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header>
            <h1>Dashboard</h1>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
        </header>

        <section class="cards">
            <div class="card total">
                <h3>Total Reports</h3>
                <p><?php echo $total; ?></p>
            </div>
            <div class="card pending">
                <h3>Pending Reports</h3>
                <p><?php echo $pending; ?></p>
            </div>
            <div class="card resolved">
                <h3>Resolved Reports</h3>
                <p><?php echo $resolved; ?></p>
            </div>
        </section>
    </main>
</div>
</body>
</html>
