<?php
session_start();
require_once '../config/config.php'; // DB connection

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ HARD-CODED ADMIN LOGIN
    $admin_email = "admin@unitypg.com";
    $admin_password = "admin123"; // You can change this

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['user_id'] = 0; // no DB ID needed
        $_SESSION['name'] = "System Administrator";
        $_SESSION['role'] = "admin";
        header("Location: ../admin/admin_dashboard.php");
        exit;
    }

    // ✅ NORMAL USER LOGIN (from database)
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['fullname'] ?? $user['name'];
            $_SESSION['role'] = "user";
            header("Location: ../user/user_dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Padre Garcia - Login</title>
    <link rel="stylesheet" href="../login/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="../img/logo.png" alt="Unity logo">
        </div>

        <div class="form-section">
            <div class="card">
                <h2>Login</h2>

                <?php if ($error): ?>
                    <div class="alert" style="color: red; text-align: center; margin-bottom: 10px;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn">Sign In</button>
                    <div class="signup">
                        New User? <a href="register.php">Sign up here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
