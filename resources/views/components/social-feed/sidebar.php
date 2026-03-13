<?php
// /resources/views/components/social-feed/sidebar.php

/** @var \App\Models\User $currentUser Already available from index.php */
$currentUserName = $currentUser->full_name ?? $currentUser->name ?? 'User';
$currentUserInitials = strtoupper(substr($currentUserName, 0, 1));
$currentUserAvatar = $currentUser->avatar_url ?? null;
$assetBase = getAssetBase();
?>

<aside class="hidden lg:block lg:col-span-4 sticky top-6 self-start space-y-6">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="h-16 bg-gradient-to-r from-orange-400 to-primary-600"></div>
        <div class="px-5 pb-5">
            <div class="relative -mt-8 mb-3">
                <?php if ($currentUserAvatar): ?>
                    <img src="<?= $assetBase ?>images/uploads/avatars/<?= $currentUserAvatar ?>"
                        class="h-16 w-16 rounded-2xl border-4 border-white dark:border-gray-900 object-cover shadow-sm">
                <?php else: ?>
                    <div class="h-16 w-16 rounded-2xl border-4 border-white dark:border-gray-900 bg-primary-600 flex items-center justify-center text-white text-2xl font-bold shadow-sm">
                        <?= $currentUserInitials ?>
                    </div>
                <?php endif; ?>
            </div>

            <h2 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">
                <?= htmlspecialchars($currentUserName) ?>
            </h2>
            <p class="text-xs text-gray-500 mb-4">@<?= $currentUser->username ?? 'username' ?></p>

            <div class="flex items-center space-x-6 border-t border-gray-100 dark:border-gray-800 pt-4">
                <div class="text-center">
                    <span id="following-count" class="block text-sm font-bold text-gray-900 dark:text-white">0</span>
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider font-sans">Following</span>
                </div>
                <div class="text-center">
                    <span id="followers-count" class="block text-sm font-bold text-gray-900 dark:text-white">0</span>
                    <span class="text-[10px] text-gray-500 uppercase tracking-wider font-sans">Followers</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 p-5">
        <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center text-sm font-sans uppercase tracking-tight">
            <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Who to follow
        </h3>

        <div id="suggested-follows" class="space-y-4">
            <div class="animate-pulse space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="rounded-full bg-gray-100 dark:bg-gray-800 h-10 w-10"></div>
                    <div class="flex-1 space-y-2 py-1">
                        <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded w-3/4"></div>
                        <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded w-1/2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 text-[11px] text-gray-400 uppercase tracking-widest font-bold font-sans">
        © <?= date('Y') . ' • ' . ($appName ?? 'CatScript') ?> • Social Stack
    </div>
</aside>