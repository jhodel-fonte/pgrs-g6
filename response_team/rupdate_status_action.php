<?php
session_start();

require_once __DIR__ . '/../src/data/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: rmy_reports.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
$status = isset($_POST['status']) ? trim($_POST['status']) : null;

if (!$id || !$status) {
    $_SESSION['flash_error'] = 'Missing report id or status.';
    header('Location: rupdate_status.php?id=' . ($id ?: ''));
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE reports SET status = ?, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$status, $id]);
    $_SESSION['flash_success'] = 'Status updated.';
} catch (PDOException $e) {
    error_log('Failed updating report status: ' . $e->getMessage());
    $_SESSION['flash_error'] = 'Failed to update status.';
}

header('Location: rmy_reports.php');
exit;
