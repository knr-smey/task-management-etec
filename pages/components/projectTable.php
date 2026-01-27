<?php
/**
 * expects:
 * - $projects
 * - $user
 */
?>

<div class="bg-white rounded-xl overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Start</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">End</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                <?php foreach ($projects as $p): ?>
                    <tr class="hover:bg-blue-50/50 transition-colors duration-150"
                        data-id="<?= (int)$p['id'] ?>"
                        data-name="<?= e($p['name']) ?>"
                        data-description="<?= e($p['description'] ?? '') ?>"
                        data-status="<?= e($p['status'] ?? 'active') ?>"
                        data-start="<?= e($p['start_date'] ?? '') ?>"
                        data-end="<?= e($p['end_date'] ?? '') ?>"
                    >
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900"><?= (int)$p['id'] ?></span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    <?= strtoupper(substr(e($p['name']), 0, 1)) ?>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-900"><?= e($p['name']) ?></span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">
                                <?= !empty($p['description']) ? e($p['description']) : '-' ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $status = (string)($p['status'] ?? 'active');
                            $statusClass =
                                $status === 'active' ? 'bg-green-100 text-green-800' :
                                ($status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-blue-100 text-blue-800');
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                <?= e($status) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= !empty($p['start_date']) ? e($p['start_date']) : '-' ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= !empty($p['end_date']) ? e($p['end_date']) : '-' ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= e($p['created_at'] ?? '-') ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <?php if (!userHasRole($user, 'member')): ?>
                                <div class="flex items-center justify-end gap-2">
                                    <!-- EDIT -->
                                    <button
                                        type="button"
                                        class="editProjectBtn inline-flex items-center justify-center w-8 h-8 rounded-lg text-white bg-green-500 hover:bg-green-600 transition-colors duration-150 shadow-sm hover:shadow cursor-pointer"
                                        title="Edit project">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <!-- DELETE -->
                                    <button
                                        type="button"
                                        class="deleteProjectBtn inline-flex items-center justify-center w-8 h-8 rounded-lg text-white bg-red-500 hover:bg-red-600 transition-colors duration-150 shadow-sm hover:shadow cursor-pointer"
                                        data-id="<?= (int)$p['id'] ?>"
                                        title="Delete project">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-center lg:hidden">
        <p class="text-xs text-gray-500">← Scroll horizontally to see more →</p>
    </div>
</div>

<style>
    .overflow-x-auto::-webkit-scrollbar { height: 8px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #a0aec0; }
</style>
