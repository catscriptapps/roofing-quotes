<?php
// /resources/views/pages/quotations.php

// Page Icon: Document with a pricing/check-list feel
$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
?>

<div id="quotations-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300 overflow-hidden">

    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-[0.2em] mb-6">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                        </span>
                        Bidding Engine Active
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.95] mb-6">
                        Smart <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Contractor Quotes</span>
                    </h1>

                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium max-w-md">
                        Fill out your request, upload media, and receive competitive bids from verified contractors instantly.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <?php
                        // Added w-full (mobile) and sm:w-auto (desktop) to the shared class
                        $btnClass = "inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 font-black rounded-xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 gap-2 text-sm no-underline border-none";
                        ?>

                        <?php if ($isLoggedIn): ?>
                            <a href="javascript:" id="request-quotation-btn" class="<?= $btnClass ?> !bg-primary-600 hover:!bg-primary-500 !text-white">
                                Request a Quotation
                                <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                            <a href="<?= $baseUrl ?>my-quotations" data-partial class="<?= $btnClass ?> !bg-secondary-800 dark:!bg-white/5 !text-white">
                                View My Requests
                            </a>
                        <?php else: ?>
                            <a href="<?= $baseUrl ?>login" data-login-button class="<?= $btnClass ?> !bg-primary-600 hover:!bg-primary-500 !text-white">
                                Request a Quotation
                                <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                            <a href="<?= $baseUrl ?>login" data-login-button class="<?= $btnClass ?> !bg-secondary-800 dark:!bg-white/5 !text-white">
                                View My Requests
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 opacity-10 dark:opacity-5 transform rotate-12 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-64 h-64"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 grid grid-rows-2 gap-6">
                <div class="rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 relative overflow-hidden group" data-aos="fade-left">
                    <div class="absolute top-0 right-0 p-6">
                        <div class="px-3 py-1 rounded-full bg-primary-500/20 text-primary-400 text-[8px] font-black uppercase tracking-widest border border-primary-500/20">Network Status</div>
                    </div>
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Active Contractors</span>
                    <div class="text-6xl font-black text-white mt-2 tracking-tighter">1,200<span class="text-primary-500">+</span></div>
                    <div class="w-full h-2 bg-white/5 rounded-full mt-4 overflow-hidden">
                        <div class="h-full bg-primary-500 w-[70%] animate-shimmer" style="background-size: 200% 100%;"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <span class="text-primary-500 text-[9px] font-black uppercase tracking-widest leading-none">Avg Response</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-2">2.4<span class="text-xs ml-1">hrs</span></div>
                    </div>
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 flex flex-col justify-center items-center text-center shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-secondary-400 text-[9px] font-black uppercase tracking-widest leading-none">Jobs Done</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-2">8.9k</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $steps = [
                ['Describe Need', 'Detail your project in our smart form.', 'primary'],
                ['Upload Media', 'Add pics/videos for better accuracy.', 'secondary'],
                ['Receive Bids', 'Get competitive pricing in real-time.', 'primary'],
                ['Hire & Track', 'Choose the best fit and track progress.', 'secondary']
            ];
            foreach ($steps as $i => $s): ?>
                <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-start gap-4 hover:shadow-md transition-all group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $s[2] ?>-500/10 flex items-center justify-center text-<?= $s[2] ?>-500 group-hover:bg-<?= $s[2] ?>-500 group-hover:text-white transition-all">
                        <span class="text-sm font-black"><?= $i + 1 ?></span>
                    </div>
                    <div class="leading-tight">
                        <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $s[0] ?></span>
                        <span class="text-[11px] text-gray-400 font-medium leading-tight"><?= $s[1] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<style>
    #quotations-page .animate-float {
        animation: quote-float 6s ease-in-out infinite;
    }

    #quotations-page .animate-shimmer {
        animation: quote-shimmer 3s linear infinite;
    }

    #quotations-page .animate-slide-r {
        animation: quote-slide-r 1.5s ease-in-out infinite;
    }

    #quotations-page .animate-bounce-subtle {
        animation: quote-bounce-subtle 2s ease-in-out infinite;
    }

    @keyframes quote-float {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-15px) rotate(5deg);
        }
    }

    @keyframes quote-shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    @keyframes quote-slide-r {

        0%,
        100% {
            transform: translateX(0);
        }

        50% {
            transform: translateX(5px);
        }
    }
</style>