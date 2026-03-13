<?php
// /server/api/reset.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

header('Content-Type: application/json');

$messages = [];

// Disable checks
Capsule::schema()->disableForeignKeyConstraints();

// DROP EVERYTHING (No creation yet) -  We need to drop the child tables first

//Capsule::schema()->dropIfExists('recent_activities');
//Capsule::schema()->dropIfExists('post_likes');
//Capsule::schema()->dropIfExists('post_comments');
//Capsule::schema()->dropIfExists('social_posts');
//Capsule::schema()->dropIfExists('follows');
//Capsule::schema()->dropIfExists('users');

// NOW START THE RESET SCRIPTS (Creation Phase) - Start with the Parent (Users)

require_once __DIR__ . '/../../scripts/reset/user-types.php';
//$messages = array_merge($messages, resetUserTypesTable());

require_once __DIR__ . '/../../scripts/reset/users.php';
//$messages = array_merge($messages, resetUsersTable());

// 4. NOW CREATE THE CHILDREN

require_once __DIR__ . '/../../scripts/reset/social-feed.php';
//$messages = array_merge($messages, resetSocialFeedTables()); // Drops posts, comments, likes

require_once __DIR__ . '/../../scripts/reset/recent-activities.php';
//$messages = array_merge($messages, resetRecentActivitiesTable());

require_once __DIR__ . '/../../scripts/reset/faqs.php';
//$messages = array_merge($messages, resetFaqsTable());

// --- ADVERT SYSTEM ---

require_once __DIR__ . '/../../scripts/reset/adverts-call-to-action.php';
//$messages = array_merge($messages, resetAdvertCtasTable());

require_once __DIR__ . '/../../scripts/reset/advert-packages.php';
//$messages = array_merge($messages, resetAdvertPackagesTable());

// Trigger the Adverts table LAST (Child)
require_once __DIR__ . '/../../scripts/reset/adverts.php';
//$messages = array_merge($messages, resetAdvertsTable());

require_once __DIR__ . '/../../scripts/reset/advert-pics.php';
//$messages = array_merge($messages, resetAdvertPicsTable());

// --- AUTHENTICATION & PARENT TABLES ---

require_once __DIR__ . '/../../scripts/reset/password-resets.php';
//$messages = array_merge($messages, resetPasswordResetsTable());

// --- LOOKUP & SUPPORTING TABLES ---

require_once __DIR__ . '/../../scripts/reset/messages.php';
$messages = array_merge($messages, resetMessagesTable());

require_once __DIR__ . '/../../scripts/reset/countries.php';
//$messages = array_merge($messages, resetCountriesTable());

require_once __DIR__ . '/../../scripts/reset/regions.php';
//$messages = array_merge($messages, resetRegionsTable());

// --- QUOTATIONS SYSTEM ---

require_once __DIR__ . '/../../scripts/reset/contractor-types.php';
//$messages = array_merge($messages, resetContractorTypesTable());

require_once __DIR__ . '/../../scripts/reset/skilled-trades.php';
//$messages = array_merge($messages, resetSkilledTradesTable());

require_once __DIR__ . '/../../scripts/reset/unit-types.php';
//$messages = array_merge($messages, resetUnitTypesTable());

require_once __DIR__ . '/../../scripts/reset/quotation-types.php';
//$messages = array_merge($messages, resetQuotationTypesTable());

require_once __DIR__ . '/../../scripts/reset/quotation-destinations.php';
//$messages = array_merge($messages, resetQuotationDestinationsTable());

require_once __DIR__ . '/../../scripts/reset/house-types.php';
//$messages = array_merge($messages, resetHouseTypesTable());

require_once __DIR__ . '/../../scripts/reset/quotations.php';
//$messages = array_merge($messages, resetQuotationsTable());

require_once __DIR__ . '/../../scripts/reset/quotation-pics.php';
//$messages = array_merge($messages, resetQuotationPicsTable());

// --- MENTOR SYSTEM ---

require_once __DIR__ . '/../../scripts/reset/mentors.php';
//$messages = array_merge($messages, resetMentorsTable());

require_once __DIR__ . '/../../scripts/reset/mentor-requests.php';
$messages = array_merge($messages, resetMentorsRequestsTable());


// --- RE-ENABLE FOREIGN KEY CHECKS ---
Capsule::schema()->enableForeignKeyConstraints();

$deleteAllPicturesAndPDFs = false;

// --- DELETE specific social media content only ---
if ($deleteAllPicturesAndPDFs) {
    // Specifically target ONLY the folders that store transient post data 🍊
    $targetFolders = [
        __DIR__ . '/../../public/images/uploads',
        __DIR__ . '/../../public/videos',
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
