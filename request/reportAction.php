<?php 

require_once __DIR__ .'../../src/data/config.php';
require_once __DIR__ .'../../src/utillities/common.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    if (isset($_GET['request']) == 'delete' && isset($_GET['id'])) {
        $report_id = sanitizeInput($_GET['id']);

        $sql = "DELETE FROM reports WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$report_id]);

        if ($success) {
            $deleted_rows = $stmt->rowCount();
            header("Location: ../public/user/view.php?action=success");
        } else {
            header("Location: ../public/user/view.php?action=error");
        }

    }
}



?>