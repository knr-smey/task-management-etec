<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';

require_once __DIR__ . '/../../includes/layouts/app.php';
?>

<div class="min-h-screen bg-slate-50">
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Team Members</h1>
            <p class="text-sm text-slate-600 mt-1">
                <?= e($team['name'] ?? 'Team') ?> · <?= (int)($memberCount ?? 0) ?> members
            </p>
        </div>
        <a
            href="<?= e(BASE_URL) ?>team/detail?id=<?= (int)($team['id'] ?? 0) ?>"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
            Back to Team
        </a>
    </div>

    <?php
    $groupedMembers = $groupedMembers ?? [];
    require_once __DIR__ . '/../components/team-members.php';
    ?>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>
