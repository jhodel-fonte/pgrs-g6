<?php
// Simple test for ML backend
$description = "There is a small fire in the kitchen";

try {
    $ch = curl_init('http://localhost:5000/analyze_report'); // Make sure your Flask server is running
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['description' => $description]));
    
    $response = curl_exec($ch);
    if(curl_errno($ch)){
        throw new Exception(curl_error($ch));
    }
    curl_close($ch);

    $ml_result = json_decode($response, true);
    
    echo "<pre>";
    print_r($ml_result);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
