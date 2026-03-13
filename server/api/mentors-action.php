<?php
// /server/api/mentors-action.php

declare(strict_types=1);

use Src\Controller\MentorsController;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

try {
    $controller = new MentorsController();

    // We expect 'request_id' and 'action' (accepted/declined)
    $result = $controller->handleRequestAction($input);

    json_response($result, $result['success'] ? 200 : 400);
} catch (\Throwable $e) {
    json_response([
        'success' => false,
        'message' => 'Action Error: ' . $e->getMessage()
    ], 500);
}
