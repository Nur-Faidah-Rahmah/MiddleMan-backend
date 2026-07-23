<?php
$loginData = json_encode(['email' => 'admin@sidequest.com', 'password' => 'password']);
$ctx = stream_context_create(['http' => ['method' => 'POST', 'header' => "Content-Type: application/json\r\nAccept: application/json\r\n", 'content' => $loginData, 'ignore_errors' => true]]);
$loginResult = json_decode(file_get_contents('http://localhost:8000/api/v1/login', false, $ctx), true);
$token = $loginResult['data']['access_token'];

$ctx2 = stream_context_create(['http' => ['method' => 'GET', 'header' => "Authorization: Bearer $token\r\nAccept: application/json\r\n", 'ignore_errors' => true]]);
$cats = file_get_contents('http://localhost:8000/api/v1/categories', false, $ctx2);
echo $cats;
