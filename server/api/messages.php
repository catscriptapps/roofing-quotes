<?php
// /server/api/messages.php

declare(strict_types=1);

use Src\Controller\MessagesController;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
// Merge inputs so we can find 'id' or 'action' regardless of source
$input = array_merge($_GET, $_POST, json_decode(file_get_contents('php://input'), true) ?: []);

try {
    if ($method === 'GET') {
        // Handle "View Message"
        if (!empty($input['id'])) {
            $data = MessagesController::getForView((int)$input['id']);
            if (!$data) json_response(['success' => false, 'message' => 'Not found'], 404);
            json_response(['success' => true, ...$data]);
        }

        // Handle "List/Folder"
        $folder = $input['folder'] ?? 'inbox';
        $paginated = MessagesController::paginate($folder, (int)($input['per_page'] ?? 50));
        json_response([
            'success' => true,
            'messages' => $paginated->items(),
            'total' => $paginated->total()
        ]);
    }

    if ($method === 'POST') {
        $action = $input['action'] ?? 'create';
        $id = isset($input['id']) ? (int)$input['id'] : null;

        switch ($action) {
            case 'create':
                // Pass the input directly to the controller
                $message = MessagesController::create($input);

                // We return rowHtml so the JS can append it to the table immediately
                json_response([
                    'success' => true,
                    'message' => 'Message sent!',
                    'data'    => $message,
                    'rowHtml' => MessagesController::renderRow($message)
                ]);
                break;

            case 'delete':
                if (!$id || !MessagesController::delete($id)) {
                    json_response(['success' => false, 'message' => 'Delete failed'], 400);
                }
                json_response(['success' => true, 'message' => 'Deleted']);
                break;

            case 'mark-read':
                if ($id && MessagesController::markAsRead($id)) {
                    json_response(['success' => true]);
                }
                break;

            case 'archive':
                if ($id && Src\Controller\MessagesController::archive($id)) {
                    json_response(['success' => true, 'message' => 'Archived']);
                }
                json_response(['success' => false, 'message' => 'Archive failed'], 400);
                break;
            default:
                // Handle standard message creation/sending here if needed
                json_response(['success' => false, 'message' => 'Action not recognized'], 400);
                break;
        }
    }
} catch (Throwable $e) {
    json_response(['success' => false, 'message' => $e->getMessage()], 500);
}
