<?php
// /resources/views/pages/contact.php

declare(strict_types=1);
?>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-20 lg:py-32 font-sans transition-colors duration-300">

    <div class="mb-16" data-aos="fade-down">
        <h1 class="text-4xl lg:text-6xl font-black text-secondary-900 dark:text-white mb-4 tracking-tighter">
            Get in <span class="text-primary-500">Touch.</span>
        </h1>
        <p class="text-xl text-secondary-500 dark:text-secondary-400 max-w-2xl font-medium leading-relaxed">
            Have questions about accessing a quote or joining our network of professional inspectors? We're ready to help.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-12">
            
            <div class="p-10 rounded-[2.5rem] bg-white dark:bg-secondary-900 border border-secondary-100 dark:border-secondary-800 shadow-sm relative overflow-hidden group" data-aos="fade-right" data-aos-delay="100">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary-500/5 rounded-full blur-2xl"></div>

                <div class="space-y-10 relative z-10">
                    <div class="group/item">
                        <p class="text-[10px] font-black text-secondary-400 dark:text-secondary-500 uppercase tracking-[0.3em] mb-3">Support Email</p>
                        <a href="mailto:support@roofingquotes.com" class="text-secondary-900 dark:text-white font-bold text-xl hover:text-primary-500 transition-colors break-words">
                            support@roofingquotes.com
                        </a>
                    </div>

                    <div class="pt-10 border-t border-secondary-50 dark:border-secondary-800">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                            </span>
                            <h4 class="font-black text-xs uppercase tracking-widest text-secondary-900 dark:text-white">Platform Status</h4>
                        </div>
                        <p class="text-secondary-500 dark:text-secondary-400 text-sm font-medium leading-relaxed">
                            Systems are fully operational. Access code generation is currently instant.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-10 rounded-[2.5rem] bg-secondary-900 dark:bg-primary-950 text-white shadow-2xl border border-secondary-800 relative overflow-hidden" data-aos="fade-right" data-aos-delay="200">
                <svg class="absolute right-0 bottom-0 opacity-10 w-32 h-32 translate-x-6 translate-y-6 rotate-12" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                </svg>

                <p class="text-[10px] font-black text-primary-400 uppercase tracking-[0.3em] mb-3">Response Time</p>
                <div class="flex items-baseline gap-3">
                    <span class="text-5xl font-black italic tracking-tighter text-white">24</span>
                    <span class="text-sm font-bold uppercase tracking-widest text-secondary-400">Hour Turnaround</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8" data-aos="fade-left">
            <div class="bg-white dark:bg-secondary-900 p-8 lg:p-16 rounded-[3rem] border border-secondary-100 dark:border-secondary-800 shadow-sm">
                <form id="contact-form" class="grid grid-cols-1 gap-8" novalidate>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-widest text-secondary-400 dark:text-secondary-500 ml-2 mb-3 block">Your Name</label>
                            <input type="text" name="full_name"
                                class="w-full px-6 py-5 rounded-2xl border border-secondary-100 dark:border-secondary-800 bg-secondary-50/50 dark:bg-secondary-950/50 text-secondary-900 dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-bold text-base placeholder-secondary-300 dark:placeholder-secondary-700"
                                placeholder="John Doe" required>
                        </div>

                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-widest text-secondary-400 dark:text-secondary-500 ml-2 mb-3 block">Email Address</label>
                            <input type="email" name="email"
                                class="w-full px-6 py-5 rounded-2xl border border-secondary-100 dark:border-secondary-800 bg-secondary-50/50 dark:bg-secondary-950/50 text-secondary-900 dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-bold text-base placeholder-secondary-300 dark:placeholder-secondary-700"
                                placeholder="john@example.com" required>
                        </div>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black uppercase tracking-widest text-secondary-400 dark:text-secondary-500 ml-2 mb-3 block">Inquiry Type</label>
                        <input type="text" name="subject"
                            class="w-full px-6 py-5 rounded-2xl border border-secondary-100 dark:border-secondary-800 bg-secondary-50/50 dark:bg-secondary-950/50 text-secondary-900 dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none font-bold text-base placeholder-secondary-300 dark:placeholder-secondary-700"
                            placeholder="e.g. Inspector Account Request" required>
                    </div>

                    <div class="group">
                        <label class="text-[10px] font-black uppercase tracking-widest text-secondary-400 dark:text-secondary-500 ml-2 mb-3 block">How can we help?</label>
                        <textarea name="message" rows="6"
                            class="w-full px-6 py-5 rounded-2xl border border-secondary-100 dark:border-secondary-800 bg-secondary-50/50 dark:bg-secondary-950/50 text-secondary-900 dark:text-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none resize-none font-bold text-base placeholder-secondary-300 dark:placeholder-secondary-700"
                            placeholder="Tell us more about your project..." required></textarea>
                    </div>

                    <div class="pt-6">
                        <button type="submit" id="contact-submit"
                            class="group w-full sm:w-auto inline-flex items-center justify-center py-5 px-12 rounded-2xl shadow-xl shadow-secondary-900/10 text-white bg-secondary-900 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 transition-all duration-300 font-black uppercase tracking-[0.2em] text-xs active:scale-[0.95]">
                            <span class="flex items-center gap-4">
                                Dispatch Message
                                <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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