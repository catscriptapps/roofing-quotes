<?php
// /server/helpers.php

use Src\Config\NavigationConfig;
use App\Models\Invoice;

/**
 * Sends a successful JSON response and logs the API call.
 *
 * @param array $data The response payload to return to the client.
 * @param int   $code Optional HTTP status code (default is 200).
 */
function json_response(array $data, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    log_api_call($_SERVER['REQUEST_URI'] ?? '', $code, $_SERVER['REQUEST_METHOD']);
    exit;
}

/**
 * Logs details about each API call to a file.
 */
function log_api_call(string $path, int $status, string $method, ?string $error = null): void {}

/**
 * Generates a unique, incrementing quote number in the format DCU-QTYY-XXXX.
 * Uses the Counter model to ensure numbers are never reused after deletion.
 */
function generateQuoteNumber(): string
{
    $yearPrefix = date('y'); // e.g., "26"
    $type = 'quote';

    $counter = \App\Models\Counter::where('type', $type)->where('year', $yearPrefix)->first();
    $nextNumber = ($counter) ? $counter->last_value + 1 : 1;

    return "DCU-QT{$yearPrefix}-" . str_pad((string)$nextNumber, 4, '0', STR_PAD_LEFT);
}

/**
 * Returns the asset base for either DEV or PRODUCTION.
 */
function getAssetBase()
{
    $untrimmedBasePath = $_ENV['APP_BASE_PATH'] ?? '';
    $baseUrl = '/' . (trim($untrimmedBasePath, '/') ? trim($untrimmedBasePath, '/') . '/' : '');
    $assetBase = $baseUrl;

    return rtrim($assetBase, '/') . '/';
}

/**
 * Returns the limit for media upload.
 */
function getMediaLimit()
{
    return 8;
}

/**
 * Returns the human‑readable role names for a given owner's user type IDs.
 *
 * This function loads all user types once and caches them in $GLOBALS['allUserTypes']
 * to avoid repeated lookups. It then maps the owner's `user_type_ids` to their
 * corresponding role names. If a type ID does not exist in the cached list,
 * the fallback role name "User" is used.
 *
 * @param object $owner  An object containing a `user_type_ids` array property.
 *
 * @return string  JSON‑encoded array of role names associated with the owner.
 */
function getUserRoles($owner)
{
    if (!isset($GLOBALS['allUserTypes'])) {
        $types = \Src\Controller\UserTypesController::list();
        $GLOBALS['allUserTypes'] = [];
        if ($types) {
            foreach ($types as $t) {
                $GLOBALS['allUserTypes'][$t->user_type_id] = $t->user_type;
            }
        }
    }

    $typeIds = $owner->user_type_ids ?? [];

    return json_encode(array_map(function ($tid) {
        return $GLOBALS['allUserTypes'][$tid] ?? 'User';
    }, $typeIds));
}

/**
 * Normalize the incoming URI by removing the base path (if present).
 */
function normalizePath(string $uri, string $basePath): string
{
    $uri = '/' . ltrim($uri, '/');

    if ($basePath && str_starts_with($uri, '/' . $basePath)) {
        $uri = substr($uri, strlen('/' . $basePath));
    }

    $path = '/' . ltrim($uri, '/');
    return $path === '/' ? '/home' : $path;
}

/**
 * Handle API route resolution and execution.
 */
function resolveApiRoute(string $path): bool
{
    if (!str_starts_with($path, '/api/')) return false;

    $segments = explode('/', ltrim($path, '/'));
    $apiFile = __DIR__ . '/../server/api/' . ($segments[1] ?? 'index') . '.php';

    if (file_exists($apiFile)) {
        include $apiFile;
        exit;
    }

    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'API endpoint not found']);
    exit;
}

/**
 * Resolve metadata (icon + URL) for a given page title.
 */
function resolvePageMeta(string $pageTitle, bool $isLoggedIn = false): array
{
    $icons = NavigationConfig::getIcons();
    $links = NavigationConfig::getNavLinks($isLoggedIn);

    return [
        'icon' => $icons[$pageTitle] ?? '',
        'url'  => $links[$pageTitle] ?? '',
    ];
}

/**
 * Resolve a page route and derive a human-friendly title.
 */
function resolvePageRoute(string $path): array
{
    if (preg_match('#^/([^/]+)/([^/]+)$#', $path, $m)) {
        $resource = $m[1];
        $id = urldecode($m[2]);

        $detailFile = __DIR__ . "/../resources/views/pages/{$resource}/detail.php";

        if (file_exists($detailFile)) {
            $GLOBALS['encodedId'] = $id;
            return [$detailFile, ucfirst($resource) . ' Details'];
        }
    }

    $pageFile = __DIR__ . '/../resources/views/pages/' . ltrim($path, '/') . '.php';

    if (!file_exists($pageFile)) {
        http_response_code(404);
        $pageFile = __DIR__ . '/../resources/views/pages/404.php';
        $title = 'Page Not Found';
    } else {
        $slug = basename($path);
        $title = ucwords(str_replace(['-', '_'], ' ', $slug));
    }

    return [$pageFile, $title];
}

/**
 * Recursively delete a directory and its contents.
 */
function rrmdir(string $dir): bool
{
    if (!is_dir($dir)) return false;

    $items = scandir($dir);
    if ($items === false) return false;

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($path)) {
            if (!rrmdir($path)) return false;
        } else {
            if (!unlink($path)) return false;
        }
    }
    return rmdir($dir);
}

/**
 * Convert an absolute file path to a format suitable for mPDF image source.
 */
function pdfImageSrc(string $absolute): ?string
{
    if (!file_exists($absolute)) return null;
    return str_replace('\\', '/', $absolute);
}

/**
 * Render a PHP view file with provided variables and return the output as a string.
 */
function renderView(string $path, array $vars = []): string
{
    if (!file_exists($path)) return "<p>View not found: {$path}</p>";

    ob_start();
    extract($vars);
    include $path;
    return ob_get_clean();
}
