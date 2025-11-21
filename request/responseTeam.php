<?php

ob_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../src/data/profile.php';
require_once __DIR__ . '/../src/utillities/log.php';

try {
    $profile = new profileMng();
    $teams = $profile->getUserByRole(2);
    // var_dump($teams);
    
    if (isset($teams['success']) && $teams['success'] == false ) {
        throw new Exception('Failed to fetch reports');
    }

    $response = [
        'success' => true,
        'data' => $teams
    ];
    
} catch (Exception $e) {
    containlog('Error', $e->getMessage(), __DIR__, 'reportData.log');
        $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
}

ob_clean();
echo json_encode($response, JSON_PRETTY_PRINT);