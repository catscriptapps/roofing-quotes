<?php
// /resources/views/pages/listings.php

$pageIcon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
?>

<div id="listings-page" class="min-h-screen bg-gray-50 dark:bg-gray-950 font-sans pb-12 transition-colors duration-300 overflow-hidden">

    <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">

        <div class="grid lg:grid-cols-12 gap-6 items-stretch">

            <div class="lg:col-span-7 relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-gray-900 shadow-xl border border-gray-200/60 dark:border-white/5 p-8 lg:p-12" data-aos="fade-right">
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-500/10 border border-primary-500/20 text-primary-600 dark:text-primary-400 text-[9px] font-black uppercase tracking-[0.2em] mb-6">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary-500"></span>
                        </span>
                        Marketplace v3.0 Live
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-black tracking-tighter text-secondary-900 dark:text-white leading-[0.9] mb-6">
                        Premier <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-secondary-500">Property Hub</span>
                    </h1>

                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 leading-snug font-medium max-w-md">
                        Explore trending rentals, exclusive sales, and distinct professional services tailored to your location.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                        <button class="w-full sm:w-auto px-8 py-4 bg-primary-600 hover:bg-primary-500 text-white font-black rounded-xl shadow-lg transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            Browse Listings
                            <svg class="w-4 h-4 animate-slide-r" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>

                        <button class="w-full sm:w-auto px-8 py-4 bg-secondary-900 dark:bg-white/5 text-white dark:text-gray-300 font-black rounded-xl border border-transparent dark:border-white/10 transition-all hover:bg-secondary-800 flex items-center justify-center text-sm">
                            Post New
                        </button>
                    </div>
                </div>

                <div class="absolute -bottom-12 -right-12 opacity-5 dark:opacity-10 transform -rotate-12 pointer-events-none">
                    <?= preg_replace('/(<svg[^>]*)(>)/i', '$1 class="w-72 h-72"$2', $pageIcon) ?>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col gap-6">
                <div class="flex-1 rounded-[2.5rem] bg-secondary-950 p-8 border border-white/5 relative overflow-hidden group" data-aos="fade-left">
                    <div class="absolute top-0 right-0 p-6">
                        <div class="px-3 py-1 rounded-full bg-primary-500/20 text-primary-400 text-[8px] font-black uppercase tracking-widest border border-primary-500/20">Market View</div>
                    </div>
                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-[0.2em]">Available Properties</span>
                    <div class="text-6xl font-black text-white mt-2 tracking-tighter">4,300<span class="text-primary-500">+</span></div>
                    <div class="w-full h-2 bg-white/5 rounded-full mt-4 overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary-500 to-secondary-500 w-[88%] animate-shimmer" style="background-size: 200% 100%;"></div>
                    </div>
                    <p class="mt-6 text-xs text-gray-400 italic">"Real-time liquidity across 12+ premium locations."</p>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 shadow-sm" data-aos="fade-up" data-aos-delay="100">
                        <span class="text-primary-500 text-[9px] font-black uppercase tracking-widest leading-none">Avg. Price</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-1">$2.4k</div>
                    </div>
                    <div class="rounded-[2rem] bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 p-6 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <span class="text-secondary-400 text-[9px] font-black uppercase tracking-widest leading-none">Locations</span>
                        <div class="text-2xl font-black text-secondary-900 dark:text-white mt-1">12+</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <?php
            $features = [
                ['Geo-Tagging', 'Filtered by your location.', 'primary'],
                ['Multi-Category', 'Rentals, sales, and more.', 'secondary'],
                ['Instant Verify', 'Trusted landlord status.', 'primary'],
                ['Direct Chat', 'Connect via social hub.', 'secondary']
            ];
            foreach ($features as $i => $f): ?>
                <div class="p-5 rounded-3xl bg-white dark:bg-white/5 border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:shadow-md transition-all group">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-<?= $f[2] ?>-500/10 flex items-center justify-center text-<?= $f[2] ?>-500 group-hover:bg-<?= $f[2] ?>-500 group-hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <span class="block text-sm font-black text-secondary-900 dark:text-white mb-0.5"><?= $f[0] ?></span>
                        <span class="text-[11px] text-gray-400 font-medium leading-tight"><?= $f[1] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12 p-10 rounded-[2.5rem] bg-gradient-to-br from-primary-600 to-primary-700 text-center relative overflow-hidden group" data-aos="zoom-in">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
            <h3 class="relative z-10 text-3xl font-black text-white mb-4">Ready to find your next space?</h3>
            <p class="relative z-10 text-primary-100 mb-8 max-w-xl mx-auto">Join thousands of verified users already trading and renting in our ecosystem.</p>
            <button class="relative z-10 px-10 py-4 bg-white text-primary-600 font-black rounded-xl shadow-xl hover:scale-105 transition-transform">
                Launch Marketplace
            </button>
        </div>

    </div>
</div>

<style>
    #listings-page .animate-shimmer {
        animation: list-shimmer 3s linear infinite;
    }

    #listings-page .animate-slide-r {
        animation: list-slide-r 1.5s ease-in-out infinite;
    }

    @keyframes list-shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    @keyframes list-slide-r {

        0%,
        100% {
            transform: translateX(0);
        }

        50% {
            transform: translateX(5px);
        }
    }
</style>