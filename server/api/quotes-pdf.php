<?php
// /server/api/quotes-pdf.php

declare(strict_types=1);

use App\Models\Quote;
use App\Utils\IdEncoder;

/**
 * PDF Proxy for Roofing Quotes
 * Securely streams PDFs from /server/storage/quotes/
 */

// 1. Get the encoded ID from the GET parameter
$encodedId = $_GET['id'] ?? null;

if (!$encodedId) {
    http_response_code(400);
    exit('Missing Quote ID');
}

try {
    // 2. Decode the ID using your project's IdEncoder
    $quoteId = IdEncoder::decode($encodedId);

    // 3. Fetch Quote
    $quote = Quote::find($quoteId);
    if (!$quote || empty($quote->pdf_file_name)) {
        http_response_code(404);
        exit('Quote or associated PDF not found.');
    }

    // 4. Resolve Path (Mirroring the Controller logic)
    $filename = $quote->pdf_file_name;
    $pdfPath = realpath(__DIR__ . '/../storage/pdfs/quotes/') . DIRECTORY_SEPARATOR . $filename;

    // 5. Verify File Existence
    if (!file_exists($pdfPath)) {
        http_response_code(404);
        exit('The PDF file is missing from storage.');
    }

    // 6. Stream PDF securely
    // Clear any previous output to prevent PDF corruption
    if (ob_get_level()) ob_end_clean();

    header('Content-Type: application/pdf');
    // 'inline' allows viewing in browser, 'attachment' would force download
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Length: ' . filesize($pdfPath));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    readfile($pdfPath);
    exit;

} catch (\Throwable $e) {
    http_response_code(500);
    exit('Server Error: ' . $e->getMessage());
}