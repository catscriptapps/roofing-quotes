<?php
// /server/api/post-video-upload.php

declare(strict_types=1);

use Src\Service\VideoUploadService;
use Src\Service\AuthService; // Using your AuthService pattern

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Check
$userId = AuthService::userId() ?? $_SESSION['user_id'] ?? 1;
if (!$userId) {
    json_response(['success' => false, 'message' => 'Authentication required'], 401);
    exit;
}

// 2. Validate 'video' key
if (empty($_FILES['video']) || empty($_FILES['video']['tmp_name'])) {
    json_response(['success' => false, 'message' => 'No video file found in request.'], 400);
    exit;
}

try {
    // 3. Resolve Directories
    $uploadDir = realpath(__DIR__ . '/../../public/videos/');
    if (!$uploadDir) {
        $uploadDir = __DIR__ . '/../../public/videos/';
    }

    $publicPathPrefix = '/videos/';

    // 4. Service Execution (The 71-line version with robust errors)
    $service = new VideoUploadService($uploadDir, 50);
    $result = $service->upload($_FILES['video']);

    // 5. Success Response via your helper
    json_response([
        'success'   => true,
        'message'   => 'Video uploaded successfully.',
        'filename'  => $result['fileName'],
        'url'       => $publicPathPrefix . $result['fileName'],
        'files'     => [
            ['url' => $publicPathPrefix . $result['fileName']]
        ]
    ]);
} catch (\Throwable $e) {
    // 6. Error Response via your helper
    json_response([
        'success' => false,
        'message' => $e->getMessage()
    ], 500);
}
