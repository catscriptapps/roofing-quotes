<?php
// /resources/views/partials/layout-header.php

use Src\Service\AuthService;
?>

<header class="sticky top-0 z-40 flex h-20 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white/80 backdrop-blur-md px-4 shadow-sm sm:gap-x-6 sm:px-8 dark:bg-gray-900/80 dark:border-gray-800 transition-all duration-300">

    <button @click="mobileMenuOpen = true" type="button" class="-m-2.5 p-2.5 text-secondary-700 lg:hidden dark:text-gray-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
    </button>

    <div class="flex flex-1 items-center">
        <div class="relative w-full max-w-md group cursor-pointer"
            id="search-trigger">

            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-400 transition-colors duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>

            <input type="text"
                readonly
                placeholder="Search..."
                class="block w-full py-3 pl-12 pr-4 text-sm text-secondary-900 border border-gray-200 rounded-2xl bg-gray-50/50 cursor-pointer focus:outline-none dark:bg-secondary-900/50 dark:border-secondary-800 dark:text-white dark:placeholder-gray-400 group-hover:border-primary-400 group-hover:bg-white dark:group-hover:bg-secondary-800 transition-all duration-300 shadow-inner group-hover:shadow-md">

            <div class="hidden md:flex absolute inset-y-0 right-0 items-center pr-3 pointer-events-none">
                <kbd class="px-2 py-1 text-[10px] font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg dark:bg-secondary-800 dark:border-secondary-700 dark:text-gray-400">
                    ⌘K
                </kbd>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-x-2 lg:gap-x-3">
        <?php if ($isLoggedIn) : ?>

            <?php if (AuthService::isCat()) : ?>
                <button data-reset-button data-tooltip="DB Reset"
                    class="hidden md:block group p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>

                <div class="relative group">
                    <button id="messages-btn" aria-label="Messages" data-tooltip="Messages"
                        class="p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:scale-110 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0L12 13.5 2.25 6.75" />
                        </svg>
                    </button>
                    <span id="messages-badge" class="absolute -top-1 -right-1 bg-primary-400 text-white text-[10px] font-black h-5 w-5 flex items-center justify-center rounded-full hidden border-2 border-white dark:border-secondary-900 shadow-lg"></span>
                </div>
            <?php endif; ?>

            <button data-tooltip="History" id="history-btn"
                class="group p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm text-secondary-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:rotate-12 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
        <?php endif ?>

        <button id="dark-toggle"
            class="group p-3 rounded-2xl bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-300 shadow-sm"
            title="Toggle Theme">
            <svg class="w-6 h-6 text-secondary-400 block dark:hidden group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg class="w-6 h-6 text-primary-400 hidden dark:block group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</header>