<?php
// /server/api/quotes.php

declare(strict_types=1);

use Src\Controller\QuotesController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Check
$userId = AuthService::userId();
if (!$userId) {
    json_response(['success' => false, 'messages' => ['Authentication required']], 401);
    exit;
}

/**
 * Handle input from both multipart/form-data (PDF uploads)
 * and raw JSON payloads (from the Delete Factory).
 */
$input = $_POST;

// If $_POST is empty, check for JSON input (common with fetch/JSON payloads)
if (empty($input)) {
    $rawPayload = file_get_contents('php://input');
    $jsonData = json_decode($rawPayload, true);
    if (is_array($jsonData)) {
        $input = $jsonData;
    }
}

$files  = $_FILES; 
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new QuotesController();

    // HANDLE FETCH (GET)
    if ($method === 'GET') {
        $controller->index();
        exit;
    }

    // HANDLE POST ACTIONS
    if ($method === 'POST') {
        // Now $input['_method'] will be correctly detected even if sent as JSON
        $override = strtoupper($input['_method'] ?? '');

        if ($override === 'DELETE') {
            $result = $controller->delete($input['id'] ?? 0);
        } 
        // Specialized logic for the Quick Upload (PATCH) from the table row
        elseif ($override === 'PATCH' && isset($files['quote_pdf'])) {
            $result = $controller->uploadPdf($input, $files);
        } 
        // Standard Add (POST) or Edit (PUT)
        else {
            $result = $controller->save($input);
        }

        // UTF-8 Clean for the HTML row strings
        if (!empty($result['rowHtml'])) {
            $result['rowHtml'] = mb_convert_encoding($result['rowHtml'], 'UTF-8', 'UTF-8');
        }

        json_response($result);
    } else {
        json_response(['success' => false, 'messages' => ['Method not supported']], 405);
    }
} catch (\Throwable $e) {
    error_log("Quotes API Exception: " . $e->getMessage());
    json_response(['success' => false, 'messages' => ["Internal Server Error"]], 500);
}