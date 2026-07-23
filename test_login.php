<?php
$data = json_encode(['email' => 'admin@sidequest.com', 'password' => 'password']);
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content' => $data,
        'ignore_errors' => true,
    ]
]);
$result = file_get_contents('http://localhost:8000/api/v1/login', false, $context);
echo $result;
