<?php
// /resources/views/pages/about.php

declare(strict_types=1);
?>

<div class="min-h-screen bg-white dark:bg-secondary-950 transition-colors duration-300 font-sans overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 py-16">

        <section class="text-center mb-40 relative pt-24">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-gradient-to-b from-primary-400/10 to-transparent rounded-[3rem] blur-3xl -z-10"></div>

            <div class="relative" data-aos="zoom-out" data-aos-duration="1200">
                <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 text-[10px] font-black uppercase tracking-[0.3em] mb-10 border border-primary-100 dark:border-primary-800 animate-soft-pulse">
                    The Roofing Quotes Story
                </div>
                
                <h1 class="text-6xl lg:text-8xl font-black text-secondary-900 dark:text-white tracking-tighter mb-10 leading-[0.9]">
                    Transparency in <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-primary-600">Every Shingle.</span>
                </h1>

                <p class="text-xl lg:text-2xl text-secondary-500 dark:text-secondary-400 max-w-4xl mx-auto leading-relaxed font-medium mb-16">
                    We bridge the gap between roofing inspectors and property owners. Our platform provides a secure, streamlined environment to deliver and retrieve professional quotes instantly.
                </p>

                <div class="w-1 h-24 bg-gradient-to-b from-primary-500 to-transparent mx-auto rounded-full animate-grow-y"></div>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-16 mb-48">
            <div class="relative group p-14 rounded-[3.5rem] bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 shadow-sm hover:shadow-2xl transition-all duration-500" data-aos="fade-right">
                <div class="w-16 h-16 bg-primary-500 text-white rounded-2xl flex items-center justify-center mb-10 shadow-lg shadow-primary-500/30 group-hover:rotate-12 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-4xl font-black text-secondary-900 dark:text-white mb-6">The Mission</h3>
                <p class="text-lg text-secondary-500 dark:text-secondary-400 leading-relaxed">
                    To eliminate the "lost in the inbox" frustration. We provide roofing professionals with a dedicated tool to upload, secure, and share their assessments with total confidence and ease.
                </p>
            </div>

            <div class="relative group p-14 rounded-[3.5rem] bg-secondary-900 text-white shadow-2xl border border-secondary-800 hover:-translate-y-2 transition-all duration-500" data-aos="fade-left">
                <div class="w-16 h-16 bg-white text-secondary-900 rounded-2xl flex items-center justify-center mb-10 shadow-lg group-hover:-rotate-12 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-4xl font-black text-primary-400 mb-6">The Vision</h3>
                <p class="text-lg text-secondary-200 leading-relaxed font-medium">
                    To become the industry standard for roofing documentation delivery. We envision a world where every property owner knows exactly where to go to find their secure digital quote.
                </p>
            </div>
        </section>

        <div class="text-center mb-20" data-aos="fade-up">
            <h2 class="text-xs font-black uppercase tracking-[0.5em] text-primary-500 mb-6">Our Core Pillars</h2>
            <div class="h-1 w-24 bg-secondary-900 dark:bg-white mx-auto"></div>
        </div>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-48">
            <?php
            $pillars = [
                ['Data Security', 'Every quote is locked behind a unique access code, ensuring private property data stays private.', 'primary', 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                ['High Fidelity', 'We support full PDF integration so your quotes retain their professional branding and formatting.', 'secondary', 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ['Instant Retrieval', 'No accounts needed for clients. Enter a code, see your quote. It is that simple.', 'primary', 'M13 10V3L4 14h7v7l9-11h-7z'],
                ['Inspector Focus', 'Tools designed specifically for the roofing industry workflow, not a generic file sharing site.', 'secondary', 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['Eco Friendly', 'Moving the industry toward digital-first documentation, reducing unnecessary paper waste.', 'primary', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['History Logging', 'Keep a digital trail of quotes generated for every property, accessible whenever you need them.', 'secondary', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z']
            ];
            foreach ($pillars as $index => $p): ?>
                <div data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" class="p-12 rounded-[3rem] bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 hover:border-primary-400 transition-all duration-500 group">
                    <div class="w-14 h-14 rounded-2xl bg-<?= $p[2] ?>-500/10 text-primary-500 flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="<?= $p[3] ?>" />
                        </svg>
                    </div>
                    <h4 class="text-2xl font-black text-secondary-900 dark:text-white mb-4"><?= $p[0] ?></h4>
                    <p class="text-secondary-500 dark:text-secondary-400 leading-relaxed font-medium"><?= $p[1] ?></p>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="text-center pb-32" data-aos="flip-up">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-5xl lg:text-7xl font-black text-secondary-900 dark:text-white mb-10 tracking-tighter">
                    Built for <span class="text-primary-500 italic">Accuracy.</span>
                </h2>
                <p class="text-xl text-secondary-500 dark:text-secondary-400 mb-14">
                    Ready to modernize your roofing business? Join our network of professional inspectors today.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
                    <a href="<?= $baseUrl . 'register' ?>"
                        class="w-full sm:w-auto px-14 py-7 bg-secondary-900 dark:bg-white text-white dark:text-secondary-900 text-xl font-black rounded-3xl hover:bg-primary-500 dark:hover:bg-primary-500 dark:hover:text-white hover:-translate-y-2 transition-all duration-300 shadow-2xl">
                        Get Started
                    </a>
                    <p class="text-sm font-bold text-secondary-400 uppercase tracking-widest">— Roofing Quotes Team</p>
                </div>
            </div>
        </section>

    </div>
</div>

<style>
    @keyframes grow-y {
        0% { transform: scaleY(0); transform-origin: top; }
        100% { transform: scaleY(1); transform-origin: top; }
    }
    .animate-grow-y {
        animation: grow-y 2.5s cubic-bezier(0.16, 1, 0.3, 1) infinite alternate;
    }
    @keyframes soft-pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.03); }
    }
    .animate-soft-pulse {
        animation: soft-pulse 4s ease-in-out infinite;
    }
</style>