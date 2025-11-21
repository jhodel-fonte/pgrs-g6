<?php

ob_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../src/data/profile.php';
require_once __DIR__ . '/../src/data/res_teams.php';
require_once __DIR__ . '/../src/utillities/log.php';

try {
    $teamClass = new Teams();
    $teams = $teamClass->getAllTeams();

    $memberClass = new profileMng();
    $member = $memberClass->getUserByRole(2);
    
    if (isset($teams['success']) && $teams['success'] == false ) {
        throw new Exception('Failed to fetch reports');
    }

    if ($members === false) {
        $images = []; // Set to empty array if failed instead of throwing error
    }

    // Group members
    $members = [];
    foreach ($member as $members) {
        $reportId = $image['report_id'] ?? null;
        if ($reportId) {
            if (!isset($imagesByReportId[$reportId])) {
                $imagesByReportId[$reportId] = [];
            }
            $imagesByReportId[$reportId][] = $image;
        }
    }
    
    // Merge images into each report
    foreach ($reports as &$report) {
        $reportId = $report['id'] ?? null;
        $report['images'] = $imagesByReportId[$reportId] ?? [];
    }
    unset($report); // Unset reference to avoid issues

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