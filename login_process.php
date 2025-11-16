<?php
session_start();
require 'config/db.php';

// ----------------------------
// SECURITY: SESSION HARDENING
// ----------------------------
session_regenerate_id(true);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.use_strict_mode', 1);

// ----------------------------
// SECURITY: RATE LIMITING
// ----------------------------
$lockDuration = 60; // seconds

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

if ($_SESSION['login_attempts'] >= 5) {
    $timePassed = time() - $_SESSION['last_attempt'];

    if ($timePassed < $lockDuration) {
        $remaining = $lockDuration - $timePassed;
        echo "<script>alert('Too many attempts. Try again in {$remaining} seconds.'); window.location='login.php';</script>";
        exit;
    } else {
        $_SESSION['login_attempts'] = 0; // reset cooldown
    }
}

// ----------------------------
// LOGIN LOG FUNCTION
// ----------------------------
function logLoginAttempt($pdo, $username, $status, $userId = null)
{
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

        $stmt = $pdo->prepare("
            INSERT INTO login_logs (user_id, username, status, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $username, $status, $ip, $agent]);

        $line = date('[Y-m-d H:i:s] ') . 
            "Login attempt: username={$username}, status={$status}, user_id={$userId}, IP={$ip}, agent={$agent}" . PHP_EOL;
        
        file_put_contents(__DIR__ . '/log/account.log', $line, FILE_APPEND | LOCK_EX);

    } catch (Exception $e) {
        error_log(date('[Y-m-d H:i:s] ') . "Logging Error: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/log/logging_errors.log');
    }
}

// ----------------------------
// LOGIN PROCESS
// ----------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // CAPTCHA required after 3 attempts
    if ($_SESSION['login_attempts'] >= 3) {
        if (!isset($_POST['captcha_answer'])) {
            echo "<script>alert('Please solve the CAPTCHA.'); window.location='login.php';</script>";
            exit;
        }

        $correct = $_SESSION['captcha_a'] + $_SESSION['captcha_b'];
        $answer = (int)$_POST['captcha_answer'];

        if ($answer !== $correct) {
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt'] = time();
            echo "<script>alert('Incorrect CAPTCHA. Try again.'); window.location='login.php';</script>";
            exit;
        }
    }

    // Validate Inputs
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "<script>alert('Please fill in all fields.'); window.location='login.php';</script>";
        exit;
    }

    $username = trim(htmlspecialchars(strip_tags($_POST['username']), ENT_QUOTES, 'UTF-8'));
    $password = $_POST['password'];

    // Dummy hash to prevent timing attacks
    $dummy_hash = '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log(date('[Y-m-d H:i:s] ') . "DB ERROR: " . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/log/database.log');
        echo "<script>alert('Database error. Please try again later.'); window.location='login.php';</script>";
        exit;
    }

    // USER NOT FOUND
    if (!$user) {
        password_verify($password, $dummy_hash); // fake verify
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt'] = time();

        logLoginAttempt($pdo, $username, 'failed');
        echo "<script>alert('Invalid username or password.'); window.location='login.php';</script>";
        exit;
    }

    // INVALID PASSWORD
    if (!password_verify($password, $user['password'])) {
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt'] = time();

        logLoginAttempt($pdo, $username, 'failed', $user['id']);
        echo "<script>alert('Invalid username or password.'); window.location='login.php';</script>";
        exit;
    }

    // SUCCESS — Reset attempts
    $_SESSION['login_attempts'] = 0;
    unset($_SESSION['captcha_a'], $_SESSION['captcha_b']);

    // Normalize
    $role = strtolower(trim($user['role']));
    $status = strtolower(trim($user['status']));
    $isApproved = (int)($user['is_approved'] ?? 0);

    // ACCOUNT APPROVAL CHECK
    if ($role !== 'admin' && !($status === 'approved' || $isApproved === 1)) {
        echo "<script>alert('Your account is still pending approval.'); window.location='login.php';</script>";
        exit;
    }

    // Log Success
    logLoginAttempt($pdo, $username, 'success', $user['id']);

    // Setup Session
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['username'] = htmlspecialchars($user['username']);
    $_SESSION['role'] = $role;

    // ----------------------------
    // CORRECT ROLE REDIRECT
    // ----------------------------
    switch ($role) {
        case 'admin':
            $redirect = "admin/dashboard.php";
            break;

        case 'response_team':
            $redirect = "response_team/dashboard.php";
            break;

        default:
            $redirect = "user/dashboard.php";
            break;
    }

    header("Location: " . $redirect);
    exit;
}

// Invalid Access
header("Location: login.php");
exit;

?>
