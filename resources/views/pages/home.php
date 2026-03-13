<?php
// /resources/views/pages/home.php

declare(strict_types=1);
?>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-12 font-sans overflow-hidden">

    <section class="relative text-center mb-32 pt-16">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-400/30 rounded-full blur-[120px] animate-pulse-slow"></div>
        <div class="absolute top-1/2 -right-24 w-80 h-80 bg-secondary-400/20 rounded-full blur-[100px] animate-bounce-slow"></div>

        <div class="relative max-w-4xl mx-auto" data-aos="zoom-out-up" data-aos-duration="1000">
            <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-secondary-50 dark:bg-secondary-900/30 text-secondary-600 dark:text-secondary-400 text-xs font-black uppercase tracking-[0.2em] mb-10 border border-secondary-200 dark:border-secondary-800 shadow-sm animate-soft-pulse">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                </span>
                Global Real Estate Ecosystem — V2.0
            </div>

            <h1 class="text-6xl lg:text-8xl font-black text-secondary-900 dark:text-white leading-[0.95] tracking-tighter mb-8">
                Gonachi <span class="text-primary-500">RE</span><br />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 via-secondary-400 to-primary-500 animate-gradient-x">
                    Connected World.
                </span>
            </h1>

            <p class="text-xl lg:text-2xl text-gray-600 dark:text-gray-400 leading-relaxed max-w-2xl mx-auto mb-12 font-medium">
                The ultimate nexus for
                <span class="text-secondary-600 dark:text-primary-400 font-bold underline decoration-primary-400/30">Landlords</span>,
                <span class="text-secondary-600 dark:text-primary-400 font-bold underline decoration-primary-400/30">Contractors</span>,
                <span class="text-secondary-600 dark:text-primary-400 font-bold underline decoration-primary-400/30">Property Managers</span>,
                <span class="text-secondary-600 dark:text-primary-400 font-bold underline decoration-primary-400/30">Mortgage Brokers</span>,
                <span class="text-secondary-600 dark:text-primary-400 font-bold underline decoration-primary-400/30">Real Estate Agents</span>, and
                <span class="text-secondary-600 dark:text-primary-400 font-bold underline decoration-primary-400/30">Tenants</span>.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
                <a href="<?= $baseUrl . 'register' ?>"
                    class="group relative px-14 py-6 text-xl font-black rounded-2xl bg-secondary-500 text-white shadow-2xl shadow-secondary-500/40 hover:bg-secondary-600 hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <span class="relative z-10">Join the Ecosystem</span>
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-400 to-primary-600 translate-y-[101%] group-hover:translate-y-0 transition-transform duration-300"></div>
                </a>
                <div class="flex flex-col items-start group cursor-pointer">
                    <span class="text-sm font-black text-secondary-900 dark:text-white uppercase tracking-[0.3em] group-hover:text-primary-500 transition-colors">Connect • Grow</span>
                    <div class="h-1 w-full bg-primary-400 mt-1 origin-left animate-grow-x shadow-[0_0_10px_rgba(251,146,60,0.8)]"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-y-16 gap-x-10 mb-32 py-16 px-6 bg-gray-50/50 dark:bg-gray-800/20 rounded-[3rem] border border-gray-200 dark:border-gray-800" data-aos="fade-up">
        <?php
        $stats = [
            ['Verified', 'Stakeholders', 'primary'],
            ['Global', 'Adverts', 'secondary'],
            ['Secure', 'Validations', 'primary'],
            ['Instant', 'Quotations', 'secondary']
        ];
        foreach ($stats as $stat): ?>
            <div class="text-center group cursor-default">
                <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-secondary-900 dark:text-white mb-2 group-hover:scale-105 group-hover:text-primary-500 transition-all duration-300">
                    <?= $stat[0] ?>
                </div>
                <div class="text-[10px] sm:text-xs text-<?= $stat[2] ?>-500 font-black uppercase tracking-[0.15em] lg:tracking-[0.25em]">
                    <?= $stat[1] ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="mb-32">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <h2 class="text-4xl lg:text-6xl font-black text-secondary-900 dark:text-white mb-6 leading-tight">A Global Network <br><span class="text-primary-500 animate-pulse inline-block">Without Borders</span></h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    Gonachi breaks down geographical barriers. Whether you're a landlord in Toronto or a contractor in London, our ecosystem connects you to verified opportunities instantly.
                </p>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 font-bold text-secondary-700 dark:text-gray-300 group">
                        <div class="p-1 rounded-full bg-primary-400 text-white group-hover:animate-bounce shadow-lg shadow-primary-400/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        Real-time cross-border collaboration
                    </li>
                    <li class="flex items-center gap-3 font-bold text-secondary-700 dark:text-gray-300 group">
                        <div class="p-1 rounded-full bg-primary-400 text-white group-hover:animate-bounce shadow-lg shadow-primary-400/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        Multi-currency quotation engine
                    </li>
                </ul>
            </div>
            <div class="relative" data-aos="zoom-in">
                <div class="absolute -top-6 -right-6 z-20 bg-primary-500 text-white p-4 rounded-2xl shadow-[0_0_20px_rgba(251,146,60,0.6)] animate-float font-black text-center">
                    <span class="block text-2xl animate-pulse">LIVE</span>
                    <span class="text-[10px] uppercase tracking-tighter">Network Active</span>
                </div>
                <div class="aspect-square rounded-[3rem] bg-gradient-to-br from-secondary-900 to-secondary-800 flex items-center justify-center border-8 border-white dark:border-gray-800 shadow-2xl overflow-hidden group">
                    <div class="absolute inset-0 opacity-30"></div>
                    <div class="text-primary-400 animate-spin-slow opacity-40">
                        <svg class="w-64 h-64" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <div class="absolute bg-white dark:bg-gray-900 p-8 rounded-full shadow-2xl animate-soft-pulse border-4 border-primary-400 group-hover:border-secondary-400 transition-colors duration-500">
                        <p class="text-primary-500 font-black text-4xl leading-none group-hover:scale-110 transition-transform">200+</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mb-12 text-center" data-aos="fade-up">
        <h2 class="text-4xl lg:text-5xl font-black text-secondary-900 dark:text-white mb-4">Platform Powerhouses</h2>
        <div class="w-24 h-2 bg-primary-400 mx-auto rounded-full"></div>
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-32">
        <?php
        $features = [
            ['Tenant Validation', 'Secure preference checks via previous landlords. Eliminate fraud.', 'primary', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['Industry Mentorship', 'Request guidance from seasoned landlords and agents.', 'secondary', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['Social Live Feed', 'Network, share insights, and follow industry leaders.', 'primary', 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z'],
            ['Instant Quotations', 'Receive competitive quotes from verified contractors.', 'secondary', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['Home Matching', 'Add listings or search for home buyers with AI matching.', 'primary', 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
            ['Global Trust', 'Trust is currency—see community ratings for everyone.', 'secondary', 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.482-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z']
        ];
        foreach ($features as $f): ?>
            <div data-aos="fade-up" class="p-10 rounded-[2.5rem] bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 group">
                <div class="w-16 h-16 rounded-2xl bg-<?= $f[2] ?>-400/10 text-<?= $f[2] ?>-500 flex items-center justify-center mb-8 group-hover:animate-soft-pulse transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $f[3] ?>" />
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-secondary-900 dark:text-white mb-4"><?= $f[0] ?></h3>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed"><?= $f[1] ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="mb-32 py-20 bg-secondary-900 rounded-[4rem] text-white relative overflow-hidden" data-aos="zoom-in">
        <div class="absolute top-0 right-0 p-20 opacity-10">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
            </svg>
        </div>
        <div class="relative z-10 px-12 text-center">
            <h2 class="text-4xl lg:text-5xl font-black mb-16">Seamless Integration</h2>
            <div class="grid md:grid-cols-3 gap-12 mt-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-400 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg shadow-primary-400/20">1</div>
                    <h4 class="text-xl font-bold mb-4 dark:text-white">Create Profile</h4>
                    <p class="text-gray-500 dark:text-gray-400">Join as a Landlord, Agent, or Tenant and verify your identity for a trusted experience.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-400 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg shadow-primary-400/20">2</div>
                    <h4 class="text-xl font-bold mb-4 dark:text-white">Post & Connect</h4>
                    <p class="text-gray-500 dark:text-gray-400">List properties, request quotes, or find your next home within the Gonachi network.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-400 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg shadow-primary-400/20">3</div>
                    <h4 class="text-xl font-bold mb-4 dark:text-white">Grow Portfolio</h4>
                    <p class="text-gray-500 dark:text-gray-400">Leverage community trust and Royal Navy-grade security to scale your real estate business.</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-400 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg shadow-primary-400/20">4</div>
                    <h4 class="text-xl font-bold mb-4 dark:text-white">Join Professional Groups</h4>
                    <p class="text-gray-500 dark:text-gray-400">Create or join specialized groups to network with similar professionals and share market insights.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-400 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg shadow-primary-400/20">5</div>
                    <h4 class="text-xl font-bold mb-4 dark:text-white">Validate & Verify</h4>
                    <p class="text-gray-500 dark:text-gray-400">Utilize our advanced tenant validation system to ensure reliable and secure rental agreements.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-400 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-lg shadow-primary-400/20">6</div>
                    <h4 class="text-xl font-bold mb-4 dark:text-white">Boost with Ads</h4>
                    <p class="text-gray-500 dark:text-gray-400">Deploy targeted adverts to promote your business and drive growth on the Gonachi platform.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="relative rounded-[4rem] p-16 lg:p-28 text-center overflow-hidden bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-2xl" data-aos="flip-up">
        <div class="absolute inset-0 bg-primary-400/5 blur-3xl"></div>
        <div class="relative max-w-3xl mx-auto">
            <h2 class="text-5xl lg:text-7xl font-black text-secondary-900 dark:text-white mb-8 tracking-tighter">
                Find your <span class="text-primary-500">place.</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-12 font-medium">
                The Gonachi Ecosystem is ready to help you navigate the world's most connected property community.
            </p>
            <a href="<?= $baseUrl . 'register' ?>"
                class="inline-flex items-center px-16 py-8 bg-secondary-500 text-white rounded-3xl text-2xl font-black shadow-2xl hover:bg-primary-500 hover:-translate-y-2 transition-all duration-300">
                Get Started for Free
            </a>
        </div>
    </section>

</div>

<style>
    @keyframes gradient-x {

        0%,
        100% {
            background-size: 200% 200%;
            background-position: left center;
        }

        50% {
            background-size: 200% 200%;
            background-position: right center;
        }
    }

    .animate-gradient-x {
        animation: gradient-x 5s ease infinite;
    }

    .animate-spin-slow {
        animation: spin 25s linear infinite;
    }

    @keyframes grow-x {
        from {
            transform: scaleX(0);
        }

        to {
            transform: scaleX(1);
        }
    }

    .animate-grow-x {
        animation: grow-x 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes bounce-slow {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .animate-bounce-slow {
        animation: bounce-slow 4s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(3deg);
        }

        50% {
            transform: translateY(-15px) rotate(-3deg);
        }
    }

    .animate-float {
        animation: float 5s ease-in-out infinite;
    }

    @keyframes soft-pulse {

        0%,
        100% {
            transform: scale(1);
            filter: brightness(1);
        }

        50% {
            transform: scale(1.05);
            filter: brightness(1.1);
        }
    }

    .animate-soft-pulse {
        animation: soft-pulse 3s ease-in-out infinite;
    }

    @keyframes pulse-slow {

        0%,
        100% {
            opacity: 0.2;
            transform: scale(1);
        }

        50% {
            opacity: 0.4;
            transform: scale(1.15);
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 10s ease-in-out infinite;
    }
</style>