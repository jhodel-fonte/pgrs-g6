<?php
function sendSMS($to, $message) {
    $dotenv = parse_ini_file(__DIR__ . '/../.env');
    $apiKey = $dotenv['IPROGSMS_API_KEY'];
    $sender = $dotenv['IPROGSMS_SENDER_ID'];

    // iProgSMS API endpoint
    $url = "https://api.iprogsms.com/api/v1/sms/send";

    $payload = json_encode([
        "api_key" => $apiKey,
        "sender_id" => $sender,
        "recipient" => $to,
        "message" => $message
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
?>
