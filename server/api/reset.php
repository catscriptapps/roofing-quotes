<?php
// /server/api/reset.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

header('Content-Type: application/json');

$messages = [];

// Disable checks
Capsule::schema()->disableForeignKeyConstraints();

// --- 1. DROP EVERYTHING ---
// Added 'quotes' here to clean up before recreation
Capsule::schema()->dropIfExists('quotes'); 
Capsule::schema()->dropIfExists('recent_activities');
Capsule::schema()->dropIfExists('users');

// --- 2. CREATION PHASE: PARENTS ---

require_once __DIR__ . '/../../scripts/reset/user-types.php';
$messages = array_merge($messages, resetUserTypesTable());

require_once __DIR__ . '/../../scripts/reset/users.php';
$messages = array_merge($messages, resetUsersTable());

// --- 3. LOOKUP & SUPPORTING TABLES ---

require_once __DIR__ . '/../../scripts/reset/countries.php';
$messages = array_merge($messages, resetCountriesTable());

require_once __DIR__ . '/../../scripts/reset/regions.php';
$messages = array_merge($messages, resetRegionsTable());

// --- 4. FEATURE TABLES (CHILDREN) ---

// Place it here: After users, countries, and regions are ready
require_once __DIR__ . '/../../scripts/reset/quotes.php';
$messages = array_merge($messages, resetQuotesTable());

require_once __DIR__ . '/../../scripts/reset/recent-activities.php';
$messages = array_merge($messages, resetRecentActivitiesTable());

require_once __DIR__ . '/../../scripts/reset/faqs.php';
$messages = array_merge($messages, resetFaqsTable());

require_once __DIR__ . '/../../scripts/reset/password-resets.php';
$messages = array_merge($messages, resetPasswordResetsTable());

require_once __DIR__ . '/../../scripts/reset/messages.php';
$messages = array_merge($messages, resetMessagesTable());

require_once __DIR__ . '/../../scripts/reset/counters.php';
$messages = array_merge($messages, resetCountersTable());

// --- RE-ENABLE FOREIGN KEY CHECKS ---
Capsule::schema()->enableForeignKeyConstraints();

$deleteAllPicturesAndPDFs = false;

// --- DELETE specific social media content only ---
if ($deleteAllPicturesAndPDFs) {
    // Specifically target ONLY the folders that store transient post data 🍊
    $targetFolders = [
        __DIR__ . '/../../public/images/uploads',
        __DIR__ . '/../../public/videos',
        __DIR__ . '/../../public/pdfs',
    ];

    foreach ($targetFolders as $folder) {
        $resolved = realpath($folder);

        // Skip if the folder doesn't exist to avoid errors
        if ($resolved === false || !is_dir($resolved)) {
            $messages[] = "Skipping: folder not found: $folder";
            continue;
        }

        $messages[] = "cleaning contents of: $resolved";

        $entries = scandir($resolved);
        if ($entries === false) continue;

        $deletedCount = 0;
        foreach ($entries as $entry) {
            // NEVER delete current, parent, or .gitkeep (keeps the folder structure in Git)
            if (in_array($entry, ['.', '..', '.gitkeep'])) continue;

            $path = $resolved . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($path)) {
                // If it's a subfolder inside posts, delete it and its contents
                if (rrmdir($path)) $deletedCount++;
            } else {
                // If it's a file (like an image/video), delete it
                if (unlink($path)) $deletedCount++;
            }
        }

        $messages[] = "purged $deletedCount item(s) from $folder. (Avatars preserved 🍊)";
    }
}

json_response(['success' => true, 'messages' => $messages]);
