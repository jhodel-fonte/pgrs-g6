<?php

ob_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../src/data/reports.php';

try {
    $status = isset($_GET['status']) ? trim($_GET['status']) : null;
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $order_by = isset($_GET['order_by']) ? trim($_GET['order_by']) : 'created_at';
    $order = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC' ? 'ASC' : 'DESC';
    
    $options = [
        'status' => $status,
        'user_id' => $user_id,
        'limit' => $limit,
        'offset' => $offset,
        'order_by' => $order_by,
        'order' => $order
    ];
    
    $reports = getAllReports($options);
    
    if ($reports === false) {
        throw new Exception('Failed to fetch reports');
    }
    $response = [
        'success' => true,
        'data' => $reports
    ];
    
    ob_clean();
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    if (ob_get_level() > 0) {
        ob_clean();
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
} finally {

    if (ob_get_level() > 0) {
        ob_end_flush();
    }
}
