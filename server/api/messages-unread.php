<?php
// /server/api/messages-unread.php

declare(strict_types=1);

use Src\Controller\MessagesController;
use Src\Service\AuthService;

// Bootstrap the app
require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json; charset=utf-8');


try {
    /** * 💎 Role-Based Logic handled inside MessagesController::getUnreadCount():
     * - Admin: Unread Contact Forms + Unread Handshakes (Sent/Received)
     * - User: ONLY Unread Handshakes where they are the receiver
     */
    $unreadCount = MessagesController::getUnreadCount();

    json_response([
        'success' => true,
        'unread_count' => $unreadCount,
        'timestamp' => date('Y-m-d H:i:s'),
    ]);
} catch (Throwable $e) {
    json_response([
        'success' => false,
        'message' => 'Failed to fetch unread messages: ' . $e->getMessage(),
    ], 500);
}
