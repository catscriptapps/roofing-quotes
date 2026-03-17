<?php
// /resources/views/components/quotes/data-row.php

/** @var array $rowItem */
/** @var string $assetBase */

$assetBase = $GLOBALS['assetBase'] ?? '/';
$owner = $rowItem['owner'] ?? null;

// Move this UP so the array can use it
$updatedAt = !empty($rowItem['updated_at']) ? date('M j, g:i a', strtotime($rowItem['updated_at'])) : 'N/A';

// Inspector Logic
$hasAvatar = !empty($owner['avatar_url']);
$AVATAR_DIR_PREFIX = $assetBase . 'images/uploads/avatars/';
$avatarUrl = $hasAvatar ? htmlspecialchars($AVATAR_DIR_PREFIX . $owner['avatar_url']) : '';
$ownerFullName = $owner ? trim(($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown';
$inspectorInitial = strtoupper(substr($owner['first_name'] ?? 'U', 0, 1));

// Property Logic
$propertyAddress = $rowItem['property_address'] ?? 'No Address';
$propertyInitial = strtoupper(substr(ltrim($propertyAddress, '0123456789 '), 0, 1)) ?: '#';
$hasPdf = !empty($rowItem['pdf_file_name']);

$fullAddress = sprintf(
    "%s - %s, %s %s",
    $propertyAddress,
    $rowItem['city'] ?? 'Barrie',
    $rowItem['region_name'] ?? 'Ontario',
    $rowItem['postal_code'] ?? ''
);

$quoteDataAttrs = [
    'encoded-id'   => $rowItem['encoded_id'] ?? '',
    'quote-number' => $rowItem['quote_number'] ?? '',
    'address'      => $propertyAddress,
    'city'         => $rowItem['city'] ?? 'Barrie',
    'postal-code'  => $rowItem['postal_code'] ?? '',
    'region-name'  => $rowItem['region_name'] ?? 'Ontario',
    'country-name' => $rowItem['country_name'] ?? 'Canada',
    'access-code'  => $rowItem['access_code'] ?: '----', 

    'pdf-file'     => $rowItem['pdf_file_name'] ?? '',
    'status-label' => (int)($rowItem['status_id'] ?? 1) === 2 ? 'Posted' : 'Draft',
    'created-at'   => $rowItem['created_at_formatted'] ?? 'N/A',
    'updated-at'   => $updatedAt, 

    // Owner Data 
    'owner-name'                    => $ownerFullName,
    'owner-avatar'                  => $avatarUrl,
    'owner-initial'                 => $inspectorInitial,
    'owner-region'                  => $rowItem['owner_region'] ?? 'Unknown Region',
    'owner-country'                 => $rowItem['owner_country'] ?? 'Unkown Country',
    'user-types'                    => $rowItem['user_types_json'] ?? '["Client"]',
];

$editClass = 'edit-quote-btn';
$deleteClass = 'delete-quote-btn';

$statusBadge = match ((int)($rowItem['status_id'] ?? 1)) {
    2       => '<span class="inline-flex items-center rounded-full bg-gray-900 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-white border border-black">Posted</span>',
    default => '<span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-900/10 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-red-600 border border-red-100 dark:border-red-900/30">Draft</span>'
};

$updatedAt = !empty($rowItem['updated_at']) ? date('M j, g:i a', strtotime($rowItem['updated_at'])) : 'N/A';
?>

<tr id="quote-row-<?= $rowItem['quote_id'] ?? '0' ?>" 
    data-encoded-id="<?= $rowItem['encoded_id'] ?? '' ?>"
    class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors group border-b border-gray-100 dark:border-gray-800 font-sans">
    
    <td class="px-6 py-4 min-w-0">
        <div class="flex items-start lg:items-center min-w-0">
            <input type="file" class="hidden quick-pdf-input" accept="application/pdf" data-id="<?= $rowItem['encoded_id'] ?>">

            <div class="h-10 w-10 flex-shrink-0 relative">
                <div class="h-10 w-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 font-black text-lg border border-gray-200 dark:border-gray-700 shadow-sm">
                    <?= $propertyInitial ?>
                </div>
                
                <button type="button" 
                        class="trigger-quick-pdf absolute -bottom-1 -right-1 h-5 w-5 rounded-full flex items-center justify-center shadow-sm border border-white dark:border-gray-900 transition-all hover:scale-110 <?= $hasPdf ? 'bg-red-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' ?>"
                        title="<?= $hasPdf ? 'Replace PDF' : 'Upload PDF' ?>">
                    <i class="bi <?= $hasPdf ? 'bi-file-earmark-check-fill' : 'bi-file-earmark-plus' ?> text-[10px]"></i>
                </button>
            </div>
            
            <div class="ml-4 flex-1 min-w-0">
                <div class="view-quote-trigger cursor-pointer block min-w-0"
                    <?php foreach ($quoteDataAttrs as $key => $val): ?>
                    data-<?= $key ?>='<?= htmlspecialchars((string)$val, ENT_QUOTES) ?>'
                    <?php endforeach; ?>>
                    
                    <div class="flex items-center justify-between lg:block">
                        <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-red-600 transition-colors truncate max-w-[200px] md:max-w-md" title="<?= htmlspecialchars($fullAddress) ?>">
                            <?= htmlspecialchars($fullAddress) ?>
                        </div>
                        <div class="lg:hidden flex-shrink-0 ml-2">
                            <?= $statusBadge ?>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-xs text-red-600 font-bold tracking-tight">
                            <?= htmlspecialchars($rowItem['quote_number'] ?? 'PENDING') ?>
                        </span>
                        <?php if($hasPdf): ?>
                            <span class="text-[10px] text-gray-400 font-black uppercase flex items-center gap-1">
                                <i class="bi bi-file-earmark-pdf"></i> PDF Attached
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mt-3 lg:hidden flex flex-col gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">
                            By: <?= htmlspecialchars($ownerFullName) ?>
                        </span>
                        <span class="px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-[10px] font-mono font-bold text-gray-500 border border-gray-200 dark:border-gray-700">
                            <?= htmlspecialchars($rowItem['access_code'] ?: '----') ?>
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <?php 
                            $isMobile = true; 
                            $dataAttrs = $quoteDataAttrs;
                            include __DIR__ . '/../ui/action-buttons.php'; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </td>

    <td class="px-6 py-4 hidden lg:table-cell">
        <div class="flex items-center">
            <?php if ($hasAvatar): ?>
                <img class="h-8 w-8 rounded-full object-cover border border-gray-200 dark:border-gray-700" src="<?= $avatarUrl ?>" alt="">
            <?php else: ?>
                <div class="h-8 w-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400 font-bold text-xs">
                    <?= $inspectorInitial ?>
                </div>
            <?php endif; ?>
            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300"><?= htmlspecialchars($ownerFullName) ?></span>
        </div>
    </td>

    <td class="px-6 py-4 hidden md:table-cell">
        <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 text-xs font-mono font-bold text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
            <?= htmlspecialchars($rowItem['access_code'] ?: '----') ?>
        </span>
    </td>

    <td class="px-6 py-4 hidden lg:table-cell">
        <div class="text-[11px] text-gray-600 dark:text-gray-400">
            <span class="block font-bold text-gray-400 uppercase text-[9px] tracking-widest">Created</span>
            <?= $rowItem['created_at_formatted'] ?? 'N/A' ?>
        </div>
        <div class="text-[11px] text-gray-600 dark:text-gray-400 mt-1">
            <span class="block font-bold text-gray-400 uppercase text-[9px] tracking-widest">Updated</span>
            <?= $updatedAt ?>
        </div>
    </td>

    <td class="px-6 py-4 hidden lg:table-cell">
        <?= $statusBadge ?>
    </td>

    <td class="px-6 py-4 text-right hidden lg:table-cell">
        <div class="flex items-center justify-end gap-3">
             <?php if($hasPdf): ?>
                <i class="bi bi-file-pdf-fill text-red-600 text-lg" title="Document Attached"></i>
             <?php endif; ?>
             <?php 
                $isMobile = false; 
                $dataAttrs = $quoteDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php'; 
            ?>
        </div>
    </td>
</tr>