<?php
// /resources/views/pages/home.php

declare(strict_types=1);
?>

<div class="min-h-screen bg-white dark:bg-secondary-950 transition-colors duration-300 font-sans selection:bg-primary-500/30">

    <div class="max-w-7xl mx-auto px-6 lg:px-10 pb-24">

        <nav class="flex items-center justify-between py-10 mb-12" data-aos="fade-down">
            <div class="text-2xl font-black text-secondary-900 dark:text-white tracking-tight">
                Roofing<span class="text-primary-500">Quotes</span>
            </div>

            <a href="<?= $baseUrl . 'login' ?>"
                class="px-6 py-3 rounded-xl bg-secondary-900 dark:bg-primary-500 text-white font-bold hover:scale-105 transition-transform active:scale-95">
                Inspector Login
            </a>
        </nav>

        <section class="text-center py-16 lg:py-24 mb-12" data-aos="fade-up">

            <h1 class="text-5xl lg:text-7xl font-black text-secondary-900 dark:text-white mb-8 tracking-tighter leading-tight">
                Access Your <br class="hidden md:block">
                <span class="text-primary-500">Roofing Quote</span>
            </h1>

            <p class="text-xl text-secondary-500 dark:text-secondary-400 max-w-2xl mx-auto mb-16 leading-relaxed">
                If your inspector has provided you with an access code, enter it below
                to securely view your professional roofing assessment.
            </p>

            <div class="max-w-2xl mx-auto" data-aos="zoom-in" data-aos-delay="200">
                <form method="POST" action="<?= $baseUrl ?>quote/lookup" class="relative group">
                    <div class="flex flex-col md:flex-row shadow-2xl rounded-3xl md:rounded-full overflow-hidden border border-secondary-100 dark:border-secondary-800 bg-white dark:bg-secondary-900 p-2">
                        
                        <input
                            type="text"
                            name="access_code"
                            maxlength="6"
                            placeholder="Enter 6-digit access code"
                            class="flex-1 px-8 py-5 text-xl font-bold outline-none bg-transparent text-secondary-900 dark:text-white placeholder:text-secondary-300 dark:placeholder:text-secondary-600">

                        <button
                            type="submit"
                            class="px-12 py-5 bg-primary-500 text-white font-black text-xl hover:bg-primary-600 transition-colors rounded-2xl md:rounded-full shadow-lg shadow-primary-500/20">
                            View Quote
                        </button>
                    </div>
                </form>

                <p class="text-sm text-secondary-400 dark:text-secondary-500 mt-6 italic">
                    <span class="inline-block w-2 h-2 bg-primary-500 rounded-full mr-2"></span>
                    Your inspector will provide this code once your quote is finalized.
                </p>
            </div>
        </section>

        <section class="grid md:grid-cols-3 gap-12 py-20 mb-12">

            <div class="group p-10 rounded-[2.5rem] border border-secondary-100 dark:border-secondary-800 bg-white dark:bg-secondary-900/50 hover:border-primary-500/50 transition-all duration-500" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 bg-primary-50 dark:bg-primary-500/10 text-primary-500 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-secondary-900 dark:text-white mb-4">Secure Access</h3>
                <p class="text-secondary-500 dark:text-secondary-400 leading-relaxed">
                    Quotes are protected by a unique 6-digit access code so only the intended client can view the document.
                </p>
            </div>

            <div class="group p-10 rounded-[2.5rem] border border-secondary-100 dark:border-secondary-800 bg-white dark:bg-secondary-900/50 hover:border-primary-500/50 transition-all duration-500" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 bg-primary-50 dark:bg-primary-500/10 text-primary-500 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-secondary-900 dark:text-white mb-4">Pro Documentation</h3>
                <p class="text-secondary-500 dark:text-secondary-400 leading-relaxed">
                    Inspectors upload finalized roofing quotes in high-quality PDF format for instant viewing or download.
                </p>
            </div>

            <div class="group p-10 rounded-[2.5rem] border border-secondary-100 dark:border-secondary-800 bg-white dark:bg-secondary-900/50 hover:border-primary-500/50 transition-all duration-500" data-aos="fade-up" data-aos-delay="300">
                <div class="w-14 h-14 bg-primary-50 dark:bg-primary-500/10 text-primary-500 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-secondary-900 dark:text-white mb-4">Fast Delivery</h3>
                <p class="text-secondary-500 dark:text-secondary-400 leading-relaxed">
                    Skip the email tag. Enter your code and get your quote the second it is published by your inspector.
                </p>
            </div>
        </section>

        <section class="bg-secondary-900 dark:bg-secondary-900 text-white rounded-[4rem] py-24 px-10 mb-32 relative overflow-hidden" data-aos="fade-up">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl"></div>
            
            <h2 class="text-4xl lg:text-5xl font-black text-center mb-20 relative z-10">
                Simple <span class="text-primary-400">Process</span>
            </h2>

            <div class="grid md:grid-cols-3 gap-16 text-center relative z-10">
                <div class="space-y-6">
                    <div class="w-16 h-16 bg-primary-500 text-white rounded-2xl flex items-center justify-center font-black text-2xl mx-auto rotate-3">1</div>
                    <h4 class="font-bold text-xl">Inspector Creates Quote</h4>
                    <p class="text-secondary-400 leading-relaxed">The inspector uploads the quote details and a PDF through their secure dashboard.</p>
                </div>

                <div class="space-y-6">
                    <div class="w-16 h-16 bg-white text-secondary-900 rounded-2xl flex items-center justify-center font-black text-2xl mx-auto -rotate-3">2</div>
                    <h4 class="font-bold text-xl">Secure Code Issued</h4>
                    <p class="text-secondary-400 leading-relaxed">A unique 6-digit access code is generated automatically for your specific property.</p>
                </div>

                <div class="space-y-6">
                    <div class="w-16 h-16 bg-primary-500 text-white rounded-2xl flex items-center justify-center font-black text-2xl mx-auto rotate-6">3</div>
                    <h4 class="font-bold text-xl">Instant Access</h4>
                    <p class="text-secondary-400 leading-relaxed">Enter your code on the homepage to view, download, or print your roofing quote.</p>
                </div>
            </div>
        </section>

        <section class="text-center py-12" data-aos="zoom-in">
            <h2 class="text-4xl font-black text-secondary-900 dark:text-white mb-6">For Roofing Inspectors</h2>
            <p class="text-secondary-500 dark:text-secondary-400 mb-12 max-w-2xl mx-auto text-lg">
                Streamline your workflow. Manage all your quotes in one place and provide your clients with a modern, secure retrieval experience.
            </p>

            <a href="<?= $baseUrl ?>login"
                class="inline-block px-16 py-6 bg-secondary-900 dark:bg-white text-white dark:text-secondary-950 font-black rounded-2xl hover:bg-primary-500 dark:hover:bg-primary-500 dark:hover:text-white transition-all text-xl shadow-xl">
                Start Inspected Now
            </a>
        </section>

    </div>
</div>