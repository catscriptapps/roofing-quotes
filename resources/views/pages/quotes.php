<?php

declare(strict_types=1);

$controller = new \Src\Controller\QuotesController();
$controller->index();

$quoteRows = $GLOBALS['quoteRows'] ?? '';
?>

<div class="space-y-6 max-w-full overflow-x-hidden">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white font-sans">Quotes</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Manage PDF uploads, property details, and tracking for the completed estimates project.
            </p>
        </div>

        <div class="mt-4 md:mt-0 flex flex-row gap-3 items-center">
            <div class="relative flex-1 md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="quotes-search"
                    class="block w-full rounded-xl border-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 py-2 pl-10 pr-3 text-sm placeholder-gray-400 focus:border-red-500 focus:ring-red-500 text-gray-900 dark:text-white transition-all font-sans"
                    placeholder="Search address or quote #...">
            </div>

            <button type="button" id="add-quote-btn" data-tooltip="Generate New Quote"
                class="shrink-0 flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-red-700 transition-all active:scale-95 focus:ring-2 focus:ring-red-400 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                <svg class="w-5 h-5 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden xs:inline md:inline uppercase tracking-wide">New Quote</span>
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 shadow-sm rounded-2xl flex flex-col">
        
        <div class="w-full overflow-x-auto md:overflow-x-clip border border-gray-200 dark:border-gray-800 rounded-t-2xl no-scrollbar">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-800 table-fixed">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest lg:w-[35%]">
                            Property Details & Quote #
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hidden lg:table-cell w-[22%]">
                            Inspector
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hidden md:table-cell w-[10%]">
                            Access Code
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hidden lg:table-cell w-[13%]">
                            Timeline
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest hidden lg:table-cell w-[10%]">
                            Status
                        </th>
                        <th class="relative px-6 py-4 text-right w-24 hidden lg:table-cell">
                            <span class="sr-only">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="quotes-tbody" class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
                    <?php if (empty($quoteRows)): ?>
                        <tr class="empty-state-row">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-sans">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="font-bold text-gray-400 italic">No quotes found</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?= $quoteRows ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="border-x border-b border-gray-200 dark:border-gray-800 rounded-b-2xl bg-white dark:bg-gray-900">
            <?php 
                $footerCountName = 'quotes';
                include __DIR__ . '/../components/ui/footer-count.php'; 
            ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/quotes/view-quote-modal.php'; ?>