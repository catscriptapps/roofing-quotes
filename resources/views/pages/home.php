<?php
// /resources/views/pages/home.php
declare(strict_types=1);
?>

<div class="min-h-screen bg-white dark:bg-secondary-950 transition-colors duration-300 font-sans selection:bg-red-500/30">

    <div class="max-w-7xl mx-auto px-6 lg:px-10 pb-12">

        <section class="py-12 lg:py-20 flex flex-col lg:flex-row items-center gap-12 lg:gap-20 border-b border-gray-100 dark:border-gray-800/50 mb-12" data-aos="fade-up">
            
            <div class="lg:w-1/2 text-center lg:text-left">
                <h1 class="text-5xl lg:text-6xl font-black text-secondary-900 dark:text-white mb-6 tracking-tighter leading-tight">
                    Access Your <br class="hidden md:block">
                    <span class="text-red-600">Roofing Quote</span>
                </h1>
                <p class="text-lg text-secondary-500 dark:text-secondary-400 max-w-xl mx-auto lg:mx-0 mb-8 leading-relaxed">
                    Enter your 6-digit access code below to securely view your professional roofing assessment.
                </p>

                <div id="quote-pdf-lookup-form" class="relative group max-w-lg mx-auto lg:mx-0">
                    <div class="flex shadow-2xl rounded-2xl overflow-hidden border border-secondary-100 dark:border-secondary-800 bg-white dark:bg-secondary-900 p-1.5 focus-within:border-red-500 transition-colors">
                        <input
                            type="text"
                            id="access-code"
                            name="access_code"
                            maxlength="6"
                            placeholder="6-digit code" required 
                            class="flex-1 px-5 py-3 text-lg font-bold outline-none bg-transparent text-secondary-900 dark:text-white placeholder:text-secondary-300">
                        <button
                            id="access-code-submit-btn"
                            class="px-8 py-3 bg-red-600 text-white font-black text-lg hover:bg-red-700 transition-colors rounded-xl shadow-lg shadow-red-500/20">
                            View
                        </button>
                    </div>
                    <p class="text-red-500 validation-msg mt-1">* Access code is required.</p>
                </div>
            </div>

            <div class="lg:w-1/2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-6 rounded-3xl bg-gray-50 dark:bg-secondary-900/40 border border-gray-100 dark:border-secondary-800">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="font-bold text-secondary-900 dark:text-white">Secure Access</h3>
                </div>
                <div class="p-6 rounded-3xl bg-gray-50 dark:bg-secondary-900/40 border border-gray-100 dark:border-secondary-800">
                    <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 text-red-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="font-bold text-secondary-900 dark:text-white">Pro PDF</h3>
                </div>
                <div class="p-6 rounded-3xl bg-gray-50 dark:bg-secondary-900/40 border border-gray-100 dark:border-secondary-800 sm:col-span-2">

                    <section class="text-center py-6" data-aos="zoom-in">
                        <h2 class="text-2xl font-black text-secondary-900 dark:text-white mb-4">Inspector Portal</h2>
                        <a href="<?= $baseUrl ?>login" data-login-button
                            class="inline-block px-10 py-4 bg-secondary-900 dark:bg-white text-white dark:text-secondary-950 font-black rounded-xl hover:bg-red-600 dark:hover:bg-red-600 dark:hover:text-white transition-all shadow-xl">
                            Launch <?= $appName ?>
                        </a>
                    </section>
                </div>
            </div>
        </section>

        <section class="bg-secondary-900 dark:bg-black text-white rounded-[2.5rem] py-12 px-8 mb-16 relative overflow-hidden" data-aos="fade-up">
            <div class="grid md:grid-cols-3 gap-8 items-center">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center font-black text-xl">1</div>
                    <div class="text-left">
                        <h4 class="font-bold">Inspect</h4>
                        <p class="text-xs text-secondary-400">PDF uploaded to secure vault.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 border-y md:border-y-0 md:border-x border-secondary-800 py-6 md:py-0 md:px-8">
                    <div class="flex-shrink-0 w-12 h-12 bg-white text-secondary-900 rounded-xl flex items-center justify-center font-black text-xl">2</div>
                    <div class="text-left">
                        <h4 class="font-bold">Generate</h4>
                        <p class="text-xs text-secondary-400">Unique 6-digit code issued.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center font-black text-xl">3</div>
                    <div class="text-left">
                        <h4 class="font-bold">Access</h4>
                        <p class="text-xs text-secondary-400">View, download, or print.</p>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>