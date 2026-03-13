<?php
// /resources/views/pages/home.php

declare(strict_types=1);
?>

<div class="max-w-7xl mx-auto px-6 lg:px-10 py-16 font-sans">

    <!-- Header / Navigation -->
    <div class="flex items-center justify-between mb-16">
        <div class="text-2xl font-black text-secondary-900">
            Roofing<span class="text-primary-500">Quotes</span>
        </div>

        <a href="<?= $baseUrl . 'login' ?>"
            class="px-6 py-3 rounded-xl bg-secondary-900 text-white font-bold hover:bg-primary-500 transition">
            Inspector Login
        </a>
    </div>

    <!-- HERO -->
    <section class="text-center mb-24">

        <h1 class="text-5xl lg:text-6xl font-black text-secondary-900 mb-6">
            Access Your <span class="text-primary-500">Roofing Quote</span>
        </h1>

        <p class="text-lg text-gray-600 max-w-xl mx-auto mb-12">
            If your inspector has provided you with an access code, enter it below
            to securely view your roofing quote.
        </p>

        <!-- ACCESS CODE PORTAL -->
        <div class="max-w-xl mx-auto">

            <form method="POST" action="<?= $baseUrl ?>quote/lookup">

                <div class="flex shadow-2xl rounded-full overflow-hidden border border-gray-200">

                    <input
                        type="text"
                        name="access_code"
                        maxlength="6"
                        placeholder="Enter 6-digit access code"
                        class="flex-1 px-8 py-6 text-lg font-semibold outline-none focus:ring-2 focus:ring-primary-400">

                    <button
                        type="submit"
                        class="px-10 bg-primary-500 text-white font-black text-lg hover:bg-primary-600 transition">
                        View Quote
                    </button>

                </div>

            </form>

            <p class="text-sm text-gray-500 mt-4">
                Your inspector will provide this access code once your quote is ready.
            </p>

        </div>

    </section>

    <!-- FEATURES -->
    <section class="grid md:grid-cols-3 gap-10 mb-24">

        <div class="p-8 rounded-3xl border border-gray-200 hover:shadow-xl transition">
            <div class="text-primary-500 mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6h13M9 5l-7 7 7 7" />
                </svg>
            </div>

            <h3 class="text-xl font-black text-secondary-900 mb-3">
                Secure Access
            </h3>

            <p class="text-gray-600">
                Quotes are protected by a unique 6-digit access code so only the
                intended client can view the document.
            </p>
        </div>


        <div class="p-8 rounded-3xl border border-gray-200 hover:shadow-xl transition">

            <div class="text-primary-500 mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16V4m10 16V8M3 20h18" />
                </svg>
            </div>

            <h3 class="text-xl font-black text-secondary-900 mb-3">
                Professional Quotes
            </h3>

            <p class="text-gray-600">
                Inspectors upload finalized roofing quotes in PDF format
                so clients can easily view or download them anytime.
            </p>

        </div>


        <div class="p-8 rounded-3xl border border-gray-200 hover:shadow-xl transition">

            <div class="text-primary-500 mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c0-3.866 3.582-7 8-7v14c-4.418 0-8-3.134-8-7zM4 4h4v16H4z" />
                </svg>
            </div>

            <h3 class="text-xl font-black text-secondary-900 mb-3">
                Fast Delivery
            </h3>

            <p class="text-gray-600">
                Once your quote is ready, your inspector will send you
                a simple access code to retrieve it instantly.
            </p>

        </div>

    </section>


    <!-- HOW IT WORKS -->
    <section class="bg-secondary-900 text-white rounded-[3rem] py-20 px-10 mb-24">

        <h2 class="text-4xl font-black text-center mb-16">
            How It Works
        </h2>

        <div class="grid md:grid-cols-3 gap-12 text-center">

            <div>
                <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center font-black text-xl mx-auto mb-6">
                    1
                </div>

                <h4 class="font-bold text-lg mb-3">Inspector Creates Quote</h4>

                <p class="text-gray-300">
                    Your inspector creates a quote entry and uploads the
                    finalized roofing quote PDF.
                </p>
            </div>

            <div>
                <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center font-black text-xl mx-auto mb-6">
                    2
                </div>

                <h4 class="font-bold text-lg mb-3">Access Code Generated</h4>

                <p class="text-gray-300">
                    A secure 6-digit access code is generated and shared with you.
                </p>
            </div>

            <div>
                <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center font-black text-xl mx-auto mb-6">
                    3
                </div>

                <h4 class="font-bold text-lg mb-3">View Your Quote</h4>

                <p class="text-gray-300">
                    Enter the access code above to instantly view or download your quote.
                </p>
            </div>

        </div>

    </section>


    <!-- INSPECTOR CTA -->
    <section class="text-center">

        <h2 class="text-4xl font-black text-secondary-900 mb-6">
            For Roofing Inspectors
        </h2>

        <p class="text-gray-600 mb-10 max-w-xl mx-auto">
            Upload and manage roofing quotes in one place. Generate secure
            access codes and give clients an easy way to retrieve their documents.
        </p>

        <a href="<?= $baseUrl ?>login"
            class="inline-block px-12 py-5 bg-primary-500 text-white font-black rounded-2xl hover:bg-primary-600 transition text-lg">
            Inspector Login
        </a>

    </section>

</div>