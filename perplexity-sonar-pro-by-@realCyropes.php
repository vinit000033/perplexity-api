<?php
file_put_contents('sonar-pro-api.log', date('Y-m-d H:i:s')."\n", FILE_APPEND);


header('Content-Type: text/html; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

// Get the prompt from the query string
$prompt = isset($_GET['prompt']) ? $_GET['prompt'] : '';
if (empty($prompt)) {
    // 🔴 @bizft: Return error if no message provided
    echo json_encode([
        'error' => 'Missing Parameter',
        ]);
    exit;
}
// Prepare the JSON payload
$data = [
    "messages" => [
        ["role" => "system", "content" => ""],
        ["role" => "user", "content" => $prompt]
    ],
    "modelName" => "pplx-sonar-pro",
    "currentPagePath" => "/perplexity-sonar-pro"
];

// Initialize cURL
$ch = curl_init('https://askai.free/api/search');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: */*'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute the request
$response = curl_exec($ch);
curl_close($ch);

// Decode original response
$decoded = json_decode($response, true);

// Reorder to place "response" at the top
$final = [];
if (isset($decoded['response'])) {
    $final['response'] = $decoded['response'];
}
foreach ($decoded as $key => $value) {
    if ($key !== 'response') {
        $final[$key] = $value;
    }
}

// Output the modified JSON
echo json_encode($final, JSON_UNESCAPED_UNICODE);
?>