<?php
// /src/Service/PdfUploadService.php

declare(strict_types=1);

namespace Src\Service;

class PdfUploadService
{
    protected string $uploadDir;
    protected array $allowedMimes = ['application/pdf'];

    public function __construct(string $uploadDir)
    {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';

        if (!is_dir($this->uploadDir)) {
            // The 'true' parameter allows recursive creation (e.g., pdfs/quotes/)
            if (!mkdir($this->uploadDir, 0755, true) && !is_dir($this->uploadDir)) {
                throw new \RuntimeException("Failed to create directory: {$this->uploadDir}");
            }
        }

        // Critical check: even if it exists, can we actually write to it?
        if (!is_writable($this->uploadDir)) {
            throw new \RuntimeException("Upload directory is not writable: {$this->uploadDir}");
        }
    }

    /**
     * Handles single file upload and returns the new filename
     */
    public function upload(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        
        if (!in_array($mime, $this->allowedMimes)) {
            throw new \RuntimeException("Invalid file type. Only PDFs are allowed.");
        }

        // Generic unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'pdf';
        $newFileName = bin2hex(random_bytes(8)) . '-' . time() . '.' . $extension;
        $destination = $this->uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $newFileName;
        }

        return null;
    }
}