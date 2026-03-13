<?php
// /src/Service/VideoUploadService.php

declare(strict_types=1);

namespace Src\Service;

use RuntimeException;

/* PLEASE NOTE: PHP finfo EXTENSION IS REQUIRED IN ORDER FOR THIS SERVICE TO WORK */

class VideoUploadService
{
    private string $uploadDir;
    private array $allowedMimes = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];
    private int $maxSize; // In bytes

    public function __construct(string $uploadDir, int $maxSizeMb = 50)
    {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        $this->maxSize = $maxSizeMb * 1024 * 1024;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function upload(array $file): array
    {
        // Check for PHP upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException($this->getErrorMessage($file['error']));
        }

        // Validate Size
        if ($file['size'] > $this->maxSize) {
            throw new RuntimeException("Video exceeds maximum size of " . ($this->maxSize / 1024 / 1024) . "MB");
        }

        // Validate MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        if (!in_array($mimeType, $this->allowedMimes)) {
            throw new RuntimeException("Invalid video format: {$mimeType}");
        }

        // Generate safe name
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = 'vid_' . bin2hex(random_bytes(10)) . '.' . $extension;
        $targetPath = $this->uploadDir . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException("Failed to move uploaded file.");
        }

        return [
            'fileName' => $safeName,
            'originalName' => $file['name'],
            'mime' => $mimeType,
            'size' => $file['size']
        ];
    }

    private function getErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE => "File exceeds upload_max_filesize in php.ini",
            UPLOAD_ERR_FORM_SIZE => "File exceeds MAX_FILE_SIZE in HTML form",
            UPLOAD_ERR_PARTIAL => "File was only partially uploaded",
            UPLOAD_ERR_NO_FILE => "No file was uploaded",
            default => "Unknown upload error",
        };
    }
}
