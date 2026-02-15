<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .animate-scale-in {
        animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    
    .shimmer-border {
        position: relative;
        overflow: hidden;
    }
    
    .shimmer-border::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        animation: shimmer 3s infinite;
    }
</style>

<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">

        <?php if (!empty($error)): ?>
            <!-- Error State -->
            <div class="animate-scale-in">
                <!-- Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center">
                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">បញ្ហាការអញ្ជើញ</h1>
                    <p class="text-sm text-gray-600">តំណអញ្ជើញនេះហាក់ដូចជាមិនត្រឹមត្រូវ</p>
                </div>

                <!-- Error Card -->
                <div class="glass-card rounded-3xl border border-gray-200 p-6 mb-6 shadow-xl">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-1 h-12 bg-red-500 rounded-full"></div>
                        <p class="text-sm text-gray-700 leading-relaxed"><?= e($error) ?></p>
                    </div>
                </div>

                <a href="<?= e(BASE_URL) ?>dashboard"
                   class="flex items-center justify-center gap-2 w-full rounded-2xl bg-gray-900 px-6 py-4 text-sm font-semibold text-white hover:bg-gray-800 transition-all duration-200 hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    ត្រឡប់ទៅផ្ទាំងគ្រប់គ្រង
                </a>
            </div>

        <?php else: ?>
            <!-- Success State -->
            <div class="animate-fade-in-up font-khmer">
                <!-- Header -->
                <div class="text-center mb-8 ">
                    <h1 class="text-2xl text-gray-900 mb-2">អញ្ជើញចូលមកបើស្រលាញ់</h1>
                    <p class="text-sm text-gray-600">អ្នកត្រូវបានអញ្ជើញចូលរួមក្រុមស្មោះ</p>
                </div>

                <!-- Main Card -->
                <div class="glass-card rounded-3xl border border-gray-200 shadow-2xl overflow-hidden shimmer-border mb-6">
                    <!-- Team Info Header -->
                    <div class="px-8 pt-8 pb-6">
                        <div class="flex items-start gap-4 mb-6">
                            <!-- Team Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                                    <span class="text-2xl font-bold text-white">
                                        <?= e(mb_strtoupper(mb_substr(($team['name'] ?? 'T'), 0, 1))) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Team Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">ក្រុម</span>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900 mb-2 truncate">
                                    <?= e($team['name'] ?? '-') ?>
                                </h2>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-50 border border-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700"><?= e($team['team_type'] ?? '-') ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Info Message -->
                        <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                            <p class="text-sm text-gray-700 leading-relaxed">
                                ចុច <span class="font-semibold text-gray-900">ចូលមក</span> កុំនៅយូរពេកគេចាំ តែកុំចាំគេលឺនៅ😒
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-8 pb-8">
                        <form method="post" action="<?= e(BASE_URL) ?>team/join-confirm" class="space-y-3">
                            <input type="hidden" name="csrf" value="<?= e($tokenCsrf) ?>">
                            <input type="hidden" name="token" value="<?= e($inviteToken) ?>">

                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-4 text-sm text-white hover:bg-blue-700 transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/30 active:scale-[0.98]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                ចូលអត់នឹង 😒?
                            </button>

                            <button type="button"
                                    onclick="window.location.href='<?= e(BASE_URL) ?>dashboard'"
                                    class="w-full flex items-center justify-center gap-2 rounded-2xl bg-white border-2 border-gray-200 px-6 py-4 text-sm text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 active:scale-[0.98]">
                                តាហ៊ានចុចផាកលុយ 5$ 
                            </button>
                        </form>
                    </div>

                    <!-- Footer Note -->
                    <div class="px-8 pb-6 pt-4 border-t border-gray-100">
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                ប្រសិនបើអ្នកមិនចូលទេសង្សារអ្នកនឹងសុំបែក តែបើអ្នកអត់សង្សារអ្នកនឹងអត់មានរហូត
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>