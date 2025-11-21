<?php

ob_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../src/data/reports.php';

try {
    
    $reports = getAllReports();
    $images = getAllReportImages();
    
    if ($reports === false) {
        throw new Exception('Failed to fetch reports');
    }

    if ($images === false) {
        $images = []; // Set to empty array if failed instead of throwing error
    }

    // Group images by report_id
    $imagesByReportId = [];
    foreach ($images as $image) {
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
        'data' => $reports
    ];
    
    ob_clean();
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    containlog('Error', $e->getMessage(), __DIR__, 'reportData.log');
}