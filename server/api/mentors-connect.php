<?php
// /server/api/mentors-connect.php

declare(strict_types=1);

use Src\Controller\MentorsController;

require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json; charset=utf-8');

// Get input from JSON body or POST
$input = array_merge($_POST, json_decode(file_get_contents('php://input'), true) ?: []);

try {
    // 💎 Route everything to the Controller we just fixed!
    $controller = new MentorsController();
    $result = $controller->sendRequest($input);

    // Output the controller's response
    $status = $result['success'] ? 200 : ($result['status'] ? $result['status'] : 400);
    json_response($result, $status);
} catch (\Throwable $e) {
    json_response([
        'success' => false,
        'message' => 'API Error: ' . $e->getMessage()
    ], 500);
}
