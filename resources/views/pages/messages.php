<?php
// /resources/views/pages/messages.php

declare(strict_types=1);

use Src\Controller\MessagesController;
use Src\Service\AuthService;

if (AuthService::isLoggedIn()) {
    $pageKey = 'messages';
    $pageTitle = 'Messages';
    $pageDescription = 'View, organize, and respond to your incoming messages.';
    $isAdmin = AuthService::isAdmin();
    $controllerClass = MessagesController::class;

    // Define folders based on role
    $folders = [];

    // ONLY Admins see the general site Inbox
    if ($isAdmin) {
        $folders['inbox'] = ['label' => 'Inbox', 'icon' => 'M20 13V5a2 2 0 00-2-2H6a2 2 0 00-2-2v8m16 0h-5m5 0l-1.5 1.5M11 13H4m7 0l1.5 1.5M4 13l1.5-1.5'];
    }

    // Everyone sees Handshakes and Sent items
    $folders['handshakes'] = ['label' => 'Handshakes', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 005.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'];
    $folders['unread']     = ['label' => 'Unread', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'];
    $folders['sent']       = ['label' => 'Sent', 'icon' => 'M3 10l9-7 9 7-9 7-9-7zm0 0v8a2 2 0 002 2h14a2 2 0 002-2v-8'];
    $folders['archived']   = ['label' => 'Archived', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'];

    // Default folder logic: Admins go to inbox, Users go to handshakes
    $defaultFolder = $isAdmin ? 'inbox' : 'handshakes';
    $currentFolder = $_GET['folder'] ?? $defaultFolder;

    $perPage = 50;
    $items = MessagesController::paginate($currentFolder, $perPage);
?>

    <div class="space-y-6 font-sans">
        <?php include __DIR__ . '/../components/messages/header.php'; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <aside class="lg:col-span-3 space-y-2">
                <?php include __DIR__ . '/../components/messages/sidebar-nav.php'; ?>
            </aside>

            <main class="lg:col-span-9">
                <?php include __DIR__ . '/../components/messages/list.php'; ?>
            </main>
        </div>
    </div>

<?php include __DIR__ . '/../components/messages/slide-over.php';
} else {
    if (!AuthService::isLoggedIn()) include __DIR__ . '/auth-required.php';
    elseif (!AuthService::isAdmin()) include __DIR__ . '/access-denied.php';
}
