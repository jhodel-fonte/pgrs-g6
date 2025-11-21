<?php

ob_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../src/data/reports.php';
require_once __DIR__ . '/../src/data/res_teams.php';
require_once __DIR__ . '/../src/utillities/log.php';

$response = [
    'success' => false,
    'message' => 'Access Denied!'
];

try {

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['data']) && $_GET['data'] == 'report') {
            
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
                
        }

        //Get Response Team List
        if (isset($_GET['data']) && $_GET['data'] == 'teams') {

            $teamClass = new Teams();
            $teams = $teamClass->getAllTeams();

            $members = $teamClass->getAllTeamMembers();
            
            if (isset($teams['success']) && $teams['success'] == false ) {
                throw new Exception('Failed to fetch teams');
            }

            if (isset($members['success']) && $members['success'] == false ) {
                throw new Exception('Failed to fetch team members');
            }

            // Group members by team_id
            $membersByTeam = [];
            if (is_array($members)) {
                foreach ($members as $member) {
                    $teamId = $member['team_id'] ?? null;
                    if (!$teamId) {
                        continue;
                    }
                    if (!isset($membersByTeam[$teamId])) {
                        $membersByTeam[$teamId] = [];
                    }
                    $membersByTeam[$teamId][] = $member;
                }
            }

            // Merge members into each team
            foreach ($teams as &$team) {
                $teamId = $team['team_id'] ?? $team['id'] ?? null;
                $team['members'] = $teamId && isset($membersByTeam[$teamId]) ? $membersByTeam[$teamId] : [];
            }
            unset($team);

            $response = [
                'success' => true,
                'data' => $teams
            ];

        }

    }

} catch (Exception $e) {
    $response = [
    'success' => false,
    'message' => $e->getMessage()];
    containlog('Error', $e->getMessage(), __DIR__, 'reportData.log');
}

ob_clean();
echo json_encode($response, JSON_PRETTY_PRINT);

