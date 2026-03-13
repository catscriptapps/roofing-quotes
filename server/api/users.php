<?php
// /server/api/users.php

declare(strict_types=1);

use Src\Controller\UsersController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Check
$userId = AuthService::userId();
if (!$userId) {
    json_response(['success' => false, 'messages' => ['Authentication required']], 401);
    exit;
}

$input  = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new UsersController();

    // HANDLE SEARCH / FETCH (GET)
    if ($method === 'GET') {
        // The index() method in UsersController handles the ?q= query 
        // and sends its own JSON response.
        $controller->index();
        exit;
    }

    // HANDLE SAVE/DELETE (POST)
    if ($method === 'POST') {
        $override = strtoupper($input['_method'] ?? '');

        if ($override === 'DELETE') {
            // Controller handles internal decoding of the 'id' string
            $result = $controller->delete($input['id'] ?? 0);
        } else {
            // Create or Update (including password hashing logic in controller)
            $result = $controller->save($input);
        }

        // Final UTF-8 Clean for JSON safety (essential for the generated rowHtml)
        if (!empty($result['rowHtml'])) {
            $result['rowHtml'] = mb_convert_encoding($result['rowHtml'], 'UTF-8', 'UTF-8');
        }

        json_response($result);
    } else {
        json_response(['success' => false, 'messages' => ['Method not supported']], 405);
    }
} catch (\Throwable $e) {
    json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
}
