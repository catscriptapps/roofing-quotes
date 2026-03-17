<?php
// /resources/views/pages/contact.php
declare(strict_types=1);
?>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 lg:py-16 font-sans transition-colors duration-300">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        
        <div class="lg:col-span-5" data-aos="fade-right">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-[0.3em] mb-6 border border-red-100 dark:border-red-800">
                Contact Support
            </div>
            <h1 class="text-5xl lg:text-7xl font-black text-secondary-900 dark:text-white mb-6 tracking-tighter leading-tight">
                Get in <span class="text-red-600">Touch.</span>
            </h1>
            <p class="text-lg text-secondary-500 dark:text-secondary-400 max-w-md font-medium leading-relaxed mb-8">
                Questions about accessing a quote? Send a message and our team will get back to you as soon as possible.
            </p>
            
            <div class="flex items-center gap-3 py-4 px-6 bg-gray-50 dark:bg-secondary-900/50 rounded-2xl border border-gray-100 dark:border-secondary-800 inline-flex">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest text-secondary-900 dark:text-white">Systems Operational</span>
            </div>
        </div>

        <div class="lg:col-span-7" data-aos="fade-left">
            <div class="bg-white dark:bg-secondary-900 p-8 lg:p-12 rounded-[2.5rem] border border-secondary-100 dark:border-secondary-800 shadow-2xl">
                <form id="contact-form" class="grid grid-cols-1 gap-6" novalidate>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-widest text-secondary-400 dark:text-secondary-500 ml-2 mb-2 block">Your Name</label>
                            <input type="text" name="full_name"
                                class="w-full px-5 py-4 rounded-xl border border-secondary-100 dark:border-secondary-800 bg-secondary-50/50 dark:bg-secondary-950/50 text-secondary-900 dark:text-white focus:ring-4 focus:ring-red-500/10 focus:border-red-600 transition-all outline-none font-bold text-base placeholder-secondary-300 dark:placeholder-secondary-700"
                                placeholder="John Doe" required>
                        </div>

                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-widest text-secondary-400 dark:text-secondary-500 ml-2 mb-2 block">Email Address</label>
                            <input type="email" name="email"
                                class="w-full px-5 py-4 rounded-xl border border-secondary-100 dark:border-secondary-800 bg-secondary-50/50 dark:bg-secondary-950/50 text-secondary-900 dark:text-white focus:ring-4 focus:ring-red-500/10 focus:border-red-600 transition-all outline-none font-bold text-base placeholder-secondary-300 dark:placeholder-secondary-700"
                                placeholder="john@example.com" required>
                        </div>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black uppercase tracking-widest text-secondary-400 dark:text-secondary-500 ml-2 mb-2 block">Inquiry Type</label>
                        <input type="text" name="subject"
                            class="w-full px-5 py-4 rounded-xl border border-secondary-100 dark:border-secondary-800 bg-secondary-50/50 dark:bg-secondary-950/50 text-secondary-900 dark:text-white focus:ring-4 focus:ring-red-500/10 focus:border-red-600 transition-all outline-none font-bold text-base placeholder-secondary-300 dark:placeholder-secondary-700"
                            placeholder="e.g. Inspector Account Request" required>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black uppercase tracking-widest text-secondary-400 dark:text-secondary-500 ml-2 mb-2 block">Message</label>
                        <textarea name="message" rows="4"
                            class="w-full px-5 py-4 rounded-xl border border-secondary-100 dark:border-secondary-800 bg-secondary-50/50 dark:bg-secondary-950/50 text-secondary-900 dark:text-white focus:ring-4 focus:ring-red-500/10 focus:border-red-600 transition-all outline-none resize-none font-bold text-base placeholder-secondary-300 dark:placeholder-secondary-700"
                            placeholder="How can we help?" required></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" id="contact-submit"
                            class="group w-full inline-flex items-center justify-center py-4 px-10 rounded-xl shadow-xl shadow-red-600/10 text-white bg-red-600 hover:bg-red-700 transition-all duration-300 font-black uppercase tracking-[0.2em] text-xs active:scale-[0.98]">
                            <span class="flex items-center gap-3">
                                Dispatch Message
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>