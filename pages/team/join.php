<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="min-h-[70vh] flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-lg">

        <!-- Title -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Join Team</h1>
            <p class="text-gray-500 mt-1">You’ve been invited to join a team.</p>
        </div>

        <?php if (!empty($error)): ?>
            <!-- Error Card -->
            <div class="bg-white border border-red-200 rounded-2xl shadow-sm p-6">
                <div class="flex items-start gap-3">
                    <div class="mt-1 flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600">
                        <!-- icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01M10.29 3.86l-8.01 13.86A2 2 0 004 21h16a2 2 0 001.72-3.28L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h2 class="text-lg font-semibold text-gray-900">Invite Problem</h2>
                        <p class="text-sm text-red-600 mt-1"><?= e($error) ?></p>

                        <a href="<?= e(BASE_URL) ?>dashboard"
                           class="inline-flex mt-5 items-center justify-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- Invite Card -->
            <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-gray-50">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                            <?= e(mb_strtoupper(mb_substr(($team['name'] ?? 'T'), 0, 1))) ?>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">Team</p>
                            <h2 class="text-xl font-bold text-gray-900"><?= e($team['name'] ?? '-') ?></h2>
                            <p class="text-sm text-gray-600 mt-1">
                                Type: <span class="font-medium text-gray-800"><?= e($team['team_type'] ?? '-') ?></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <p class="text-sm text-gray-600">
                        Click <span class="font-semibold">Join Now</span> to become a member of this team.
                    </p>

                    <form class="mt-6 flex items-center gap-3" method="post" action="<?= e(BASE_URL) ?>team/join-confirm">
                        <input type="hidden" name="csrf" value="<?= e($tokenCsrf) ?>">
                        <input type="hidden" name="token" value="<?= e($inviteToken) ?>">

                        <button type="submit"
                                class="inline-flex flex-1 items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                            Join Now
                        </button>

                        <a href="<?= e(BASE_URL) ?>dashboard"
                           class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                    </form>

                    <p class="text-xs text-gray-400 mt-4">
                        If you didn’t request this invite, you can safely ignore this page.
                    </p>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
