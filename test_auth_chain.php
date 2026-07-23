<?php
// Step 1: Login
$loginData = json_encode(['email' => 'admin@sidequest.com', 'password' => 'password']);
$ctx = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content' => $loginData,
        'ignore_errors' => true,
    ]
]);
$loginResult = json_decode(file_get_contents('http://localhost:8000/api/v1/login', false, $ctx), true);
$token = $loginResult['data']['access_token'] ?? null;
echo "Token: " . ($token ? substr($token, 0, 20) . "..." : "NULL") . "\n";

// Step 2: Call /me
$ctx2 = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer $token\r\nAccept: application/json\r\n",
        'ignore_errors' => true,
    ]
]);
$meResult = file_get_contents('http://localhost:8000/api/v1/me', false, $ctx2);
echo "/me response: " . $meResult . "\n";

// Step 3: Call /jobs
$ctx3 = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer $token\r\nAccept: application/json\r\n",
        'ignore_errors' => true,
    ]
]);
$jobsResult = file_get_contents('http://localhost:8000/api/v1/jobs', false, $ctx3);
echo "/jobs response (first 200 chars): " . substr($jobsResult, 0, 200) . "\n";
