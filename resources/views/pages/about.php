<?php
// /resources/views/pages/about.php
declare(strict_types=1);
?>

<div class="min-h-screen bg-white dark:bg-secondary-950 transition-colors duration-300 font-sans overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-12">

        <section class="text-center mb-20 relative pt-12">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-64 bg-gradient-to-b from-red-500/10 to-transparent rounded-[3rem] blur-3xl -z-10"></div>

            <div class="relative" data-aos="zoom-out">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-[0.3em] mb-6 border border-red-100 dark:border-red-800 animate-soft-pulse">
                    The <?= $appName ?> Story
                </div>

                <p class="text-lg lg:text-xl text-secondary-500 dark:text-secondary-400 max-w-3xl mx-auto leading-relaxed font-medium mb-10">
                    We bridge the gap between roofing inspectors and property owners with a secure, streamlined environment for instant quote delivery.
                </p>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-24">
            <div class="relative group p-10 rounded-[2.5rem] bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 shadow-sm hover:shadow-xl transition-all duration-500" data-aos="fade-right">
                <div class="w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center mb-6 shadow-lg shadow-red-500/30 group-hover:rotate-12 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-secondary-900 dark:text-white mb-3">The Mission</h3>
                <p class="text-secondary-500 dark:text-secondary-400 leading-relaxed">
                    Eliminating the "lost in the inbox" frustration. We provide professionals with a dedicated tool to secure and share assessments with ease.
                </p>
            </div>

            <div class="relative group p-10 rounded-[2.5rem] bg-secondary-900 text-white shadow-2xl border border-secondary-800 hover:-translate-y-1 transition-all duration-500" data-aos="fade-left">
                <div class="w-12 h-12 bg-white text-secondary-900 rounded-xl flex items-center justify-center mb-6 shadow-lg group-hover:-rotate-12 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-red-500 mb-3">The Vision</h3>
                <p class="text-secondary-300 leading-relaxed">
                    Becoming the industry standard where every property owner knows exactly where to find their secure digital roofing documentation.
                </p>
            </div>
        </section>

        <section class="mb-24">
            <div class="text-center mb-12 mt-12" data-aos="fade-up">
                <h2 class="text-[10px] font-black uppercase tracking-[0.5em] text-red-600 mb-4">Our Core Pillars</h2>
                <div class="h-1 w-12 bg-secondary-900 dark:bg-white mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $pillars = [
                    ['Data Security', 'Unique access codes ensure private data stays private.', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                    ['High Fidelity', 'Full PDF integration for professional branding.', 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ['Instant Retrieval', 'No accounts needed for clients. Enter code, see quote.', 'M13 10V3L4 14h7v7l9-11h-7z'],
                    ['Industry Focus', 'Built specifically for the roofing inspector workflow.', 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['Eco Friendly', 'Digital-first documentation reducing paper waste.', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['History Logging', 'Maintain a digital trail for every property quote.', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']
                ];
                foreach ($pillars as $index => $p): ?>
                    <div data-aos="fade-up" data-aos-delay="<?= $index * 50 ?>" class="p-8 rounded-[2rem] bg-gray-50 dark:bg-secondary-900 border border-gray-100 dark:border-secondary-800 hover:border-red-500 transition-all duration-300 group">
                        <div class="w-10 h-10 rounded-xl bg-red-600/10 text-red-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="<?= $p[2] ?>" /></svg>
                        </div>
                        <h4 class="text-xl font-black text-secondary-900 dark:text-white mb-2"><?= $p[0] ?></h4>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-relaxed"><?= $p[1] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </div>
</div>

<style>
    @keyframes soft-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.03); }
    }
    .animate-soft-pulse { animation: soft-pulse 4s ease-in-out infinite; }
</style>