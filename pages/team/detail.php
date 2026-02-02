<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="max-w-6xl mx-auto p-6">
    <a class="text-blue-600 underline" href="<?= e(BASE_URL) ?>team">&larr; Back</a>

    <h1 class="text-3xl font-bold mt-3"><?= e($team['name'] ?? 'Team') ?></h1>
    <p class="text-gray-600 mt-1">Team type: <?= e($team['team_type'] ?? '-') ?> · Members: <?= (int)$memberCount ?></p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-white border rounded-xl p-5">
            <h2 class="text-lg font-bold mb-3">Schedule</h2>
            <?php if (empty($sessions)): ?>
                <p class="text-gray-500">No sessions</p>
            <?php else: ?>
                <ul class="space-y-2">
                    <?php foreach ($sessions as $s): ?>
                        <li class="inline-block bg-blue-600 text-white px-3 py-1 rounded">
                            <?= strtoupper(e($s['day_of_week'])) ?> · <?= e($s['start_time']) ?> - <?= e($s['end_time']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="bg-white border rounded-xl p-5">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-bold">Members</h2>
                <span class="text-gray-500"><?= (int)$memberCount ?></span>
            </div>

            <?php if (empty($members)): ?>
                <p class="text-gray-500">No members</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($members as $m): ?>
                        <div class="border rounded-lg p-3">
                            <div class="font-semibold"><?= e($m['name'] ?? '-') ?></div>
                            <div class="text-sm text-gray-500"><?= e($m['email'] ?? '-') ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>