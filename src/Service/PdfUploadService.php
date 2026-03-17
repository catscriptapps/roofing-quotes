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
            mkdir($this->uploadDir, 0755, true);
        }
        if (!is_writable($this->uploadDir)) {
            throw new \RuntimeException("Upload directory is not writable: {$this->uploadDir}");
        }
    }

    /**
     * Handles single file upload. 
     * If $preferredName is provided, it uses it exactly.
     */
    public function upload(array $file, ?string $preferredName = null): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        
        if (!in_array($mime, $this->allowedMimes)) {
            throw new \RuntimeException("Invalid file type. Only PDFs are allowed.");
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'pdf';
        
        // Use exactly what the controller gives us, or fallback
        $newFileName = ($preferredName ? $preferredName : bin2hex(random_bytes(8)) . '-' . time()) . '.' . $extension;
        
        $destination = $this->uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $newFileName;
        }

        return null;
    }
}