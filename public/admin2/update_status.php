<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $status = $_POST['status'] ?? '';

    if ($id && $status) {
        $stmt = $pdo->prepare("UPDATE reports SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        header("Location: admin_dashboard.php?msg=updated");
        exit;
    } else {
        echo "Invalid input.";
    }
}
?>
