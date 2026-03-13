<?php
// /resources/views/pages/profile.php

use App\Utils\IdEncoder;

/** @var \App\Models\User $user */
$user = $currentUser;

// New Model Mapping
$fullName = $user->full_name; // Uses the getFullNameAttribute() accessor
$initials = strtoupper(substr($user->first_name ?? 'U', 0, 1));
$statusIsActive = ((int)$user->status_id === 1);

// Location Logic
$regionName = $user->region->name ?? $user->region->region ?? '';
$countryName = $user->country->name ?? $user->country->country ?? '';

// Avatar Logic (Preserved)
$hasAvatar = !empty($user->avatar_url);
$AVATAR_DIR_PREFIX = $assetBase . 'images/uploads/avatars/';
$avatarUrl = $hasAvatar ? htmlspecialchars($AVATAR_DIR_PREFIX . $user->avatar_url) : '';

// Role Mapping - DYNAMIC DB FETCH (Replacing static array)
if (!isset($GLOBALS['allUserTypes'])) {
    $types = \Src\Controller\UserTypesController::list();
    $GLOBALS['allUserTypes'] = [];
    foreach ($types as $t) {
        $GLOBALS['allUserTypes'][$t->user_type_id] = $t->user_type;
    }
}

$primaryRole = 'User Profile';
?>

<div id="partial-profile" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

    <div class="relative overflow-hidden rounded-[3rem] bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 shadow-2xl mb-12 group/hero" data-aos="zoom-in">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-500/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-secondary-500/10 rounded-full blur-[100px] animate-pulse-slow"></div>

        <div class="relative p-8 md:p-14 flex flex-col md:flex-row items-center gap-10">

            <div class="relative" id="avatar-preview-wrapper" data-aos="fade-right" data-aos-delay="200">
                <div id="avatar-container"
                    data-action="view-avatar"
                    data-img-src="<?= $avatarUrl; ?>"
                    class="h-40 w-40 md:h-48 md:w-48 rounded-[2.5rem] overflow-hidden ring-8 ring-gray-50 dark:ring-white/5 shadow-3xl bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center transition-all duration-500 group-hover:scale-105 group-hover:rotate-2 <?= $hasAvatar ? 'cursor-zoom-in' : ''; ?>">

                    <span id="avatar-initial" class="text-7xl font-black text-white tracking-tighter <?= $hasAvatar ? 'hidden' : 'block'; ?>">
                        <?= $initials; ?>
                    </span>

                    <img id="avatar-img" src="<?= $avatarUrl; ?>" alt="Profile"
                        class="w-full h-full object-cover <?= $hasAvatar ? 'block' : 'hidden'; ?>">

                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" stroke-width="3" />
                        </svg>
                    </div>
                </div>

                <button id="change-avatar-btn" data-action="upload"
                    class="absolute -bottom-2 -right-2 p-4 bg-primary-500 text-white rounded-2xl shadow-2xl hover:bg-primary-400 hover:scale-110 transition-all active:scale-95 z-10 border-4 border-white dark:border-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2.5" />
                    </svg>
                </button>
                <input type="file" id="avatar-file-input" class="hidden" accept="image/*">
            </div>

            <div class="flex-1 text-center md:text-left" data-aos="fade-up" data-aos-delay="400">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-500/10 text-primary-500 text-[10px] font-black uppercase tracking-widest mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                    </span>
                    <?= $primaryRole ?> Portal
                </div>

                <h1 class="text-4xl md:text-6xl font-black text-secondary-900 dark:text-white tracking-tighter leading-none mb-4" data-field="fullName">
                    <?= htmlspecialchars($fullName); ?>
                </h1>

                <p class="text-lg text-gray-500 dark:text-gray-400 font-medium mb-4">
                    Member since <?= $user->created_at->format('M Y') ?>
                </p>

                <div class="flex flex-wrap justify-center md:justify-start gap-2 mb-8">
                    <?php if (!empty($user->user_type_ids)): ?>
                        <?php foreach ($user->user_type_ids as $tid): ?>
                            <?php $label = $GLOBALS['allUserTypes'][$tid] ?? 'User'; ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                <?= htmlspecialchars((string)$label) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-gray-400 text-xs italic">No roles assigned</span>
                    <?php endif; ?>
                </div>

                <div class="flex justify-center md:justify-start gap-3">
                    <button
                        data-action="edit-user-profile"
                        data-encoded-id="<?= IdEncoder::encode($user->id); ?>"
                        data-first-name="<?= htmlspecialchars($user->first_name); ?>"
                        data-last-name="<?= htmlspecialchars($user->last_name); ?>"
                        data-full-name="<?= htmlspecialchars($user->full_name); ?>"
                        data-email="<?= htmlspecialchars($user->email); ?>"
                        data-city="<?= htmlspecialchars($user->city ?? ''); ?>"
                        data-country-id="<?= $user->country_id ?? 0; ?>"
                        data-region-id="<?= $user->region_id ?? 0; ?>"
                        data-is-active="<?= $statusIsActive ? '1' : '0'; ?>"
                        data-avatar-url="<?= htmlspecialchars($user->avatar_url ?? ''); ?>"
                        data-user-type-ids='<?= json_encode($user->user_type_ids ?? []); ?>'
                        class="px-8 py-4 bg-secondary-900 dark:bg-white text-white dark:text-secondary-900 rounded-2xl font-black text-sm transition-all hover:-translate-y-1 active:scale-95 shadow-xl flex items-center gap-2 group">

                        <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit Profile</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-8 space-y-6" data-aos="fade-up" data-aos-delay="600">

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm hover:shadow-xl transition-all">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-primary-500/10 text-primary-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2.5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-secondary-900 dark:text-white tracking-tight">Personal Verification</h3>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Email Address</label>
                        <div class="flex items-center gap-2">
                            <p class="text-lg text-secondary-900 dark:text-white font-bold" data-field="email"><?= htmlspecialchars($user->email); ?></p>
                            <?php if ($user->email_verified): ?>
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Location Signature</label>
                        <p class="text-lg text-secondary-900 dark:text-white font-bold">
                            <?= htmlspecialchars($user->city ?: 'Unset') ?><?= $regionName ? ", $regionName" : "" ?>
                            <span class="text-gray-400 font-medium"><?= $countryName ? "($countryName)" : "" ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] p-8 border border-gray-100 dark:border-white/5 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-secondary-500/10 text-secondary-500 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2.5" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-secondary-900 dark:text-white tracking-tight">Access History</h3>
                </div>
                <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-white/5">
                    <span class="text-sm font-bold text-gray-500">Last System Authentication</span>
                    <span class="text-sm font-black text-secondary-900 dark:text-white">
                        <?= $user->user_last_log ? $user->user_last_log->diffForHumans() : 'First Session' ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6" data-aos="fade-left" data-aos-delay="800">

            <div id="delete-avatar-container"
                class="bg-red-50 dark:bg-red-500/5 rounded-[2.5rem] p-8 border border-red-100 dark:border-red-500/10 text-center"
                style="display: <?= $hasAvatar ? 'block' : 'none'; ?>;">
                <h4 class="text-red-600 dark:text-red-400 font-black uppercase text-[10px] tracking-widest mb-4">Media Control</h4>
                <p class="text-xs text-red-500/70 mb-6 font-medium">Remove your profile image from the Gonachi cloud servers.</p>
                <button id="delete-avatar-btn"
                    data-action="delete-avatar"
                    data-id="<?= IdEncoder::encode($user->id); ?>"
                    class="w-full py-4 bg-white dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all hover:bg-red-600 hover:text-white active:scale-95 shadow-sm">
                    Delete Avatar
                </button>
            </div>

            <div class="bg-secondary-950 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                <div class="absolute -right-4 -bottom-4 opacity-10 rotate-12">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-7.618 3.04c0 4.833 3.07 9.363 7.618 11.016a11.955 11.955 0 017.618-11.016z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-widest text-primary-400 mb-2">System Integrity</p>
                    <h3 class="text-2xl font-black mb-6">Account Status</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-400">Status</span>
                            <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-lg text-[10px] font-black uppercase tracking-tighter">Verified</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-bold text-gray-400">Encryption</span>
                            <span class="text-sm font-black text-primary-400 tracking-widest">AES-256</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>