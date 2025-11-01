<?php 

require __DIR__ . "../../../vendor/autoload.php";

// $client = new GuzzleHttp\Client(); 

$apiKey = "6216B84B7051465CB62FFC9C55767F6E";
$apiSecret = "C6pUnqgfl3OwYGf9E0zpY03a7MVlH6IreUrSC6X0";

// $response = $client->request("POST", "https://api.cloudsms.io/v1/messages", [
//     "headers" => [
//         "Content-type" => "application/json"
//     ],
//     "auth" => [$apiKey, $apiSecret],
//     "json" => [
//         "destination" => "09949751617",
//         "message" => "Your One-Time PIN is {otp}. Please do not share this with anyone. Ref# ABC-000000"
//     ]
// ]);

// if ($response->getStatusCode() == 200) {
//     print_r($response->getBody());
// }

// $client = new GuzzleHttp\Client(); 

// $response = $client->request("POST", "https://api.cloudsms.io/v1/messages", [
//     "headers" => [
//         "Content-type" => "application/json"
//     ],
//     "auth" => [$apiKey, $apiSecret],
//     "json" => [
//         "destination" => "09949751617",
//         "message" => "Sample text message",
//         "type" => "sms"
//     ]
// ]);

// if ($response->getStatusCode() == 200) {
//     print_r($response->getBody());
// }

// ?>

<?php
$mocean = new \Mocean\Client(
        new \Mocean\Client\Credentials\Basic(['apiToken' => 'API_TOKEN_HERE'])
);

$result = $mocean->message()->send([
    'mocean-to' => '60123456789',
    'mocean-from' => 'MOCEAN',
    'mocean-text' => 'Hello World',
    'mocean-resp-format' => 'json'
]);

echo $result;
?>