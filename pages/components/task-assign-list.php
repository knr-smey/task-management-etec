<?php
/**
 * Reusable assign list component
 * Expects: $teamMembers, $t
 */
?>
<div class="max-h-64 overflow-y-auto assign-list">
    <?php if (!empty($teamMembers)): ?>
        <?php foreach ($teamMembers as $m): ?>
            <button
                type="button"
                class="assign-member group w-full px-4 py-2.5 text-sm hover:bg-indigo-50 transition-colors flex items-center gap-3 <?= ($t['assignee_id'] ?? 0) == $m['id'] ? 'bg-indigo-50' : '' ?>"
                data-task-id="<?= (int)$t['id'] ?>"
                data-user-id="<?= (int)$m['id'] ?>"
                data-name="<?= e($m['name']) ?>">
                <div class="flex-1 text-left min-w-0">
                    <div class="font-medium text-gray-900 truncate"><?= e($m['name']) ?></div>
                    <?php if (!empty($m['course'])): ?>
                        <div class="text-xs text-gray-500 truncate"><?= e($m['course']) ?></div>
                    <?php else: ?>
                        <div class="text-xs text-gray-400 truncate">No course</div>
                    <?php endif; ?>
                </div>
                <?php if (($t['assignee_id'] ?? 0) == $m['id']): ?>
                    <div class="flex-shrink-0">
                        <div class="w-6 h-6 rounded-full bg-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                <?php endif; ?>
                <svg class="assign-spinner hidden w-5 h-5 text-indigo-500 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v3a5 5 0 00-5 5H4z"></path>
                </svg>
            </button>
        <?php endforeach; ?>

        <!-- No Results -->
        <div class="assign-no-results hidden px-4 py-8 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900 mb-1">No members found</p>
            <p class="text-xs text-gray-500">Try a different search term</p>
        </div>
    <?php else: ?>
        <div class="px-4 py-8 text-center">
            <div class="w-12 h-12 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-900 mb-1">No team assigned</p>
            <p class="text-xs text-gray-500">Add a team to assign members</p>
        </div>
    <?php endif; ?>
</div>
