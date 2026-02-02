<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-2">Join Team</h1>

    <?php if (!empty($error)): ?>
        <div class="p-4 bg-red-100 text-red-700 rounded"><?= e($error) ?></div>
        <a class="inline-block mt-4 text-blue-600 underline" href="<?= e(BASE_URL) ?>dashboard">Back</a>
    <?php else: ?>
        <div class="p-4 bg-white border rounded">
            <p class="text-gray-700">Team:</p>
            <h2 class="text-xl font-semibold"><?= e($team['name'] ?? '-') ?></h2>
            <p class="text-sm text-gray-500 mt-1">Type: <?= e($team['team_type'] ?? '-') ?></p>
        </div>

        <form class="mt-4" method="post" action="<?= e(BASE_URL) ?>team/join-confirm">
            <input type="hidden" name="csrf" value="<?= e($tokenCsrf) ?>">
            <input type="hidden" name="token" value="<?= e($inviteToken) ?>">

            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Join Now
            </button>
            <a class="ml-3 text-gray-600 underline" href="<?= e(BASE_URL) ?>dashboard">Cancel</a>
        </form>
    <?php endif; ?>
</div>