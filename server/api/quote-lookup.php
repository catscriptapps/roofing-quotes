<?php
// /server/api/quote-lookup.php

declare(strict_types=1);

use App\Models\Quote;

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method !== 'POST') {
        json_response(['status' => 'error', 'message' => 'Method not allowed'], 405);
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $accessCode = trim($input['access_code'] ?? '');

    if ($accessCode === '') {
        json_response([
            'status' => 'invalid',
            'message' => 'Access code invalid'
        ], 422);
    }

    $quote = Quote::where('access_code', $accessCode)->first();

    // ❌ SCENARIO 1: invalid code
    if (!$quote) {
        json_response([
            'status' => 'invalid',
            'message' => 'Access code invalid'
        ], 404);
    }

    // ❌ SCENARIO 2: expired code
    // This will work perfectly once we add the expiry field and isExpired() method
    if (method_exists($quote, 'isExpired') && $quote->isExpired()) {
        json_response([
            'status' => 'expired',
            'message' => 'Access code has expired'
        ], 410);
    }

    // ✅ SCENARIO 3: valid → stream PDF
    // Using [quote_number].pdf as per our standard
    $pdfPath = __DIR__ . '/../storage/quotes/' . $quote->quote_number . '.pdf';

    if (!file_exists($pdfPath)) {
        json_response([
            'status' => 'error',
            'message' => 'PDF not found'
        ], 404);
    }

    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $quote->quote_number . '.pdf"');
    header('Content-Length: ' . filesize($pdfPath));

    readfile($pdfPath);
    exit;
} catch (Throwable $e) {
    json_response([
        'status' => 'error',
        'message' => 'Server error'
    ], 500);
}