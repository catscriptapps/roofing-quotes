<?php
// /resources/views/components/quotes/data-row.php

/** @var array $rowItem */
/** @var string $assetBase */

$assetBase = $GLOBALS['assetBase'] ?? '/';
$owner = $rowItem['owner'] ?? null;

// Avatar Logic
$hasAvatar = !empty($owner['avatar_url']);
$AVATAR_DIR_PREFIX = $assetBase . 'images/uploads/avatars/';
$avatarUrl = $hasAvatar ? htmlspecialchars($AVATAR_DIR_PREFIX . $owner['avatar_url']) : '';

$ownerFullName = $owner ? trim(($owner['first_name'] ?? '') . ' ' . ($owner['last_name'] ?? '')) : 'Unknown User';
$initial = strtoupper(substr($owner['first_name'] ?? 'U', 0, 1));

// Check if PDF exists
$hasPdf = !empty($rowItem['pdf_file_name']);

// Concatenated Address: 123 Cat Street - Barrie, Ontario L4N 0R5
$fullAddress = sprintf(
    "%s - %s, %s %s",
    $rowItem['property_address'] ?? 'No Address',
    $rowItem['city'] ?? 'Barrie',
    $rowItem['region_name'] ?? 'Ontario',
    $rowItem['postal_code'] ?? ''
);

$quoteDataAttrs = [
    'encoded-id'   => $rowItem['encoded_id'] ?? '',
    'quote-number' => $rowItem['quote_number'] ?? '',
    'address'      => $rowItem['property_address'] ?? '',
    'city'         => $rowItem['city'] ?? '',
    'postal-code'  => $rowItem['postal_code'] ?? '',
    'access-code'  => $rowItem['access_code'] ?? '',
    'country-id'   => $rowItem['country_id'] ?? 1,
    'region-id'    => $rowItem['region_id'] ?? 1,
    'status-id'    => $rowItem['status_id'] ?? 1,
    'pdf-file'     => $rowItem['pdf_file_name'] ?? '',
    'owner-name'   => $ownerFullName,
    'created-at'   => $rowItem['created_at_formatted'] ?? 'N/A',
];

// Status Badge Logic (Red for Draft/Primary, Black for Posted/Secondary)
$statusBadge = match ((int)($rowItem['status_id'] ?? 1)) {
    2       => '<span class="inline-flex items-center rounded-full bg-gray-900 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-white border border-black">Posted</span>',
    default => '<span class="inline-flex items-center rounded-full bg-red-50 dark:bg-red-900/10 px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-red-600 border border-red-100 dark:border-red-900/30">Draft</span>'
};
?>

<tr id="quote-row-<?= $rowItem['quote_id'] ?? '0' ?>" 
    class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors group">
    
    <td class="px-6 py-4">
        <div class="flex items-center">
            <input type="file" 
                   class="hidden quick-pdf-input" 
                   accept="application/pdf" 
                   data-id="<?= $rowItem['encoded_id'] ?>">

            <div class="h-10 w-10 flex-shrink-0 relative">
                <?php if ($hasAvatar): ?>
                    <img class="h-10 w-10 rounded-xl object-cover border-2 border-white dark:border-gray-800 shadow-sm" 
                         src="<?= $avatarUrl ?>" alt="<?= $ownerFullName ?>">
                <?php else: ?>
                    <div class="h-10 w-10 rounded-xl bg-red-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        <?= $initial ?>
                    </div>
                <?php endif; ?>
                
                <button type="button" 
                        class="trigger-quick-pdf absolute -bottom-1 -right-1 h-5 w-5 rounded-full flex items-center justify-center shadow-sm border border-white dark:border-gray-900 transition-all hover:scale-110 <?= $hasPdf ? 'bg-red-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' ?>"
                        title="<?= $hasPdf ? 'Replace PDF' : 'Upload PDF' ?>">
                    <i class="bi <?= $hasPdf ? 'bi-file-earmark-check-fill' : 'bi-file-earmark-plus' ?> text-[10px]"></i>
                </button>
            </div>
            
            <div class="ml-4 overflow-hidden">
                <div class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-md" title="<?= htmlspecialchars($fullAddress) ?>">
                    <?= htmlspecialchars($fullAddress) ?>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-red-600 font-bold tracking-tight">
                        <?= htmlspecialchars($rowItem['quote_number'] ?? 'PENDING') ?>
                    </span>
                    <?php if($hasPdf): ?>
                        <a href="<?= $assetBase ?>uploads/quotes/<?= $rowItem['pdf_file_name'] ?>" target="_blank" class="text-[10px] text-gray-500 hover:text-red-600 font-black hover:underline flex items-center gap-0.5 transition-colors">
                            <i class="bi bi-file-pdf"></i> VIEW PDF
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="mt-2 md:hidden space-y-1">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-mono font-bold text-gray-400 italic">Code: <?= htmlspecialchars($rowItem['access_code'] ?? '----') ?></span>
                <?= $statusBadge ?>
            </div>
        </div>
    </td>

    <td class="px-6 py-4 hidden md:table-cell">
        <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-800 text-xs font-mono font-bold text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
            <?= htmlspecialchars($rowItem['access_code'] ?: 'N/A') ?>
        </span>
    </td>

    <td class="px-6 py-4 hidden lg:table-cell">
        <span class="text-sm text-gray-500 dark:text-gray-400 italic">
            <?= $rowItem['created_at_formatted'] ?? 'N/A' ?>
        </span>
    </td>

    <td class="px-6 py-4 hidden md:table-cell">
        <?= $statusBadge ?>
    </td>

    <td class="px-6 py-4 text-right">
        <div class="flex items-center justify-end gap-3">
             <?php if($hasPdf): ?>
                <i class="bi bi-file-pdf-fill text-red-600 text-lg hidden lg:block" title="Document Attached"></i>
             <?php endif; ?>
             <?php 
                $isMobile = false; // We use JS/CSS for visibility, logic handled in action-buttons
                $dataAttrs = $quoteDataAttrs;
                include __DIR__ . '/../ui/action-buttons.php'; 
            ?>
        </div>
    </td>
</tr>