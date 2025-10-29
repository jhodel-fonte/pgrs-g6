<?php
require_once '../config/config.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ✅ Backend Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Email is already registered.";
        } else {
            // Insert new user (role_id = 3 = regular user)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, 3)");
            $stmt->execute([$name, $email, $hashed_password]);
            $success = "Account created successfully! You can now log in.";
        }
    }
}
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unity Register</title>
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
                <h2>Register</h2>

                <!-- Display messages -->
                <?php if ($error): ?>
                    <div class="alert" style="color: red; text-align: center; margin-bottom: 10px;">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert" style="color: green; text-align: center; margin-bottom: 10px;">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>

                <!-- Registration Form -->
                <form id="registerForm" method="POST" action="">
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="name" name="name" placeholder="Full Name" required>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="input-group">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>

                    <button type="submit" class="btn">Register</button>

                    <div class="signup">
                        Already have an account? <a href="../login/login.php">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery Validation -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="../login/script.js"></script>
</body>
</html>
