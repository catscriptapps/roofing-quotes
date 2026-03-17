<?php
// /resources/views/pages/profile.php
use App\Utils\IdEncoder;

/** @var \App\Models\User $user */
$user = $currentUser;
$fullName = $user->full_name;
$initials = strtoupper(substr($user->first_name ?? 'U', 0, 1));
$statusIsActive = ((int)$user->status_id === 1);

$regionName = $user->region->name ?? $user->region->region ?? '';
$countryName = $user->country->name ?? $user->country->country ?? '';

$hasAvatar = !empty($user->avatar_url);
$AVATAR_DIR_PREFIX = $assetBase . 'images/uploads/avatars/';
$avatarUrl = $hasAvatar ? htmlspecialchars($AVATAR_DIR_PREFIX . $user->avatar_url) : '';

if (!isset($GLOBALS['allUserTypes'])) {
    $types = \Src\Controller\UserTypesController::list();
    $GLOBALS['allUserTypes'] = [];
    foreach ($types as $t) { $GLOBALS['allUserTypes'][$t->user_type_id] = $t->user_type; }
}
$primaryRole = 'User Profile';
?>

<div id="partial-profile" class="max-w-6xl mx-auto px-4 pb-10">

    <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 shadow-xl mb-6 group/hero" data-aos="zoom-in">
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-primary-500/10 rounded-full blur-[80px]"></div>
        
        <div class="relative p-6 md:p-10 flex flex-col md:flex-row items-center gap-8">
            <div class="relative" id="avatar-preview-wrapper">
                <div id="avatar-container"
                    data-action="view-avatar"
                    data-img-src="<?= $avatarUrl; ?>"
                    class="h-32 w-32 md:h-36 md:w-36 rounded-[2rem] overflow-hidden ring-4 ring-gray-50 dark:ring-white/5 shadow-2xl bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center transition-all group-hover:scale-105 <?= $hasAvatar ? 'cursor-zoom-in' : ''; ?>">

                    <span id="avatar-initial" class="text-5xl font-black text-white tracking-tighter <?= $hasAvatar ? 'hidden' : 'block'; ?>">
                        <?= $initials; ?>
                    </span>

                    <img id="avatar-img" src="<?= $avatarUrl; ?>" alt="Profile"
                        class="w-full h-full object-cover <?= $hasAvatar ? 'block' : 'hidden'; ?>">

                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" stroke-width="3" /></svg>
                    </div>
                </div>

                <button id="change-avatar-btn" data-action="upload"
                    class="absolute -bottom-1 -right-1 p-3 bg-primary-500 text-white rounded-xl shadow-xl hover:bg-primary-400 transition-all z-10 border-4 border-white dark:border-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" stroke-width="2.5" /></svg>
                </button>
                <input type="file" id="avatar-file-input" class="hidden" accept="image/*">
            </div>

            <div class="flex-1 text-center md:text-left">
                <div class="inline-flex items-center gap-2 px-2 py-0.5 rounded-full bg-primary-500/10 text-primary-500 text-[9px] font-black uppercase tracking-widest mb-2">
                    <?= $primaryRole ?> Portal
                </div>

                <h1 class="text-3xl md:text-5xl font-black text-secondary-900 dark:text-white tracking-tighter leading-tight mb-1" data-field="fullName">
                    <?= htmlspecialchars($fullName); ?>
                </h1>

                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mb-4">
                    Member since <?= $user->created_at->format('M Y') ?>
                </p>

                <div class="flex flex-wrap justify-center md:justify-start gap-3 items-center">
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
                        class="px-6 py-3 bg-secondary-900 dark:bg-white text-white dark:text-secondary-900 rounded-xl font-black text-xs transition-all hover:shadow-lg active:scale-95 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <span>Edit Profile</span>
                    </button>

                    <div class="flex gap-1.5">
                        <?php foreach (($user->user_type_ids ?? []) as $tid): ?>
                            <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-widest bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/5">
                                <?= htmlspecialchars((string)($GLOBALS['allUserTypes'][$tid] ?? 'User')) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-[1.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden">
                <div class="p-6 grid md:grid-cols-2 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-100 dark:divide-white/5">
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary-500/10 text-primary-500 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2.5" /></svg>
                            </div>
                            <h3 class="text-sm font-black text-secondary-900 dark:text-white uppercase tracking-wider">Verification</h3>
                        </div>
                        <div class="px-1">
                            <label class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 block mb-1">Email</label>
                            <div class="flex items-center gap-2">
                                <p class="text-sm text-secondary-900 dark:text-white font-bold truncate" data-field="email"><?= htmlspecialchars($user->email); ?></p>
                                <?php if ($user->email_verified): ?>
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" /></svg>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-6 md:pt-0 md:pl-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-secondary-500/10 text-secondary-500 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2.5" /></svg>
                            </div>
                            <h3 class="text-sm font-black text-secondary-900 dark:text-white uppercase tracking-wider">Activity</h3>
                        </div>
                        <div class="px-1 flex justify-between items-end">
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 block mb-1">Last Auth</label>
                                <p class="text-sm font-bold text-secondary-900 dark:text-white">
                                    <?= $user->user_last_log ? $user->user_last_log->diffForHumans() : 'First Session' ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <label class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 block mb-1">Location</label>
                                <p class="text-[11px] font-bold text-gray-600 dark:text-gray-300 italic">
                                    <?= htmlspecialchars($user->city ?: 'Unset') ?><?= $countryName ? " ($countryName)" : "" ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-4">
            <?php if ($hasAvatar): ?>
            <div id="delete-avatar-container" class="bg-red-50 dark:bg-red-500/5 rounded-2xl p-4 border border-red-100 dark:border-red-500/10 flex items-center justify-between">
                <div>
                    <h4 class="text-red-600 dark:text-red-400 font-black uppercase text-[9px] tracking-widest">Media Control</h4>
                    <p class="text-[10px] text-red-500/70 font-medium">Remove image</p>
                </div>
                <button id="delete-avatar-btn" data-action="delete-avatar" data-id="<?= IdEncoder::encode($user->id); ?>"
                    class="px-4 py-2 bg-white dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg font-black text-[9px] uppercase tracking-wider border border-red-100 dark:border-red-500/20 hover:bg-red-600 hover:text-white transition-all">
                    Delete
                </button>
            </div>
            <?php endif; ?>

            <div class="bg-secondary-950 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10 flex flex-col gap-3">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black uppercase tracking-widest text-primary-400">Account Status</span>
                        <span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-[8px] font-black uppercase">Verified</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-white/5">
                        <span class="text-[10px] font-bold text-gray-500">System Security</span>
                        <span class="text-[10px] font-black text-primary-400 tracking-widest uppercase">AES-256 Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>