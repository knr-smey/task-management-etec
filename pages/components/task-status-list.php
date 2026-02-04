<?php
/**
 * Reusable status list component
 * Expects: $taskStatuses, $t
 */
?>
<div class="max-h-64 overflow-y-auto py-2">
    <?php foreach (($taskStatuses ?? []) as $s): ?>
        <button
            type="button"
            class="status-item group w-full px-4 py-3 text-sm hover:bg-green-50 transition-colors flex items-center gap-3 <?= ((int)($t['status_id'] ?? 0) === (int)$s['id']) ? 'bg-green-50' : '' ?>"
            data-task-id="<?= (int)$t['id'] ?>"
            data-status-id="<?= (int)$s['id'] ?>">
            <div class="w-8 h-8 rounded-lg bg-green-100 group-hover:bg-green-200 flex items-center justify-center flex-shrink-0 transition-colors">
                <span class="w-2 h-2 rounded-full bg-green-500"></span>
            </div>
            <div class="flex-1 text-left">
                <div class="font-medium text-gray-900"><?= e($s['name']) ?></div>
            </div>
            <?php if (((int)($t['status_id'] ?? 0)) === ((int)$s['id'])): ?>
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 rounded-full bg-green-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            <?php endif; ?>
            <svg class="status-spinner hidden w-5 h-5 text-green-500 animate-spin" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path>
            </svg>
        </button>
    <?php endforeach; ?>
</div>
