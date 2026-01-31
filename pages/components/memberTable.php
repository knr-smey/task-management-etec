<?php

/**
 * expects:
 * - $members
 * - $user
 */
?>

<div class="bg-white rounded-xl overflow-hidden border border-gray-100">
    <!-- Responsive wrapper with horizontal scroll -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Course</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                <?php foreach ($members as $m): ?>
                    <tr class="hover:bg-blue-50/50 transition-colors duration-150"
                        data-id="<?= (int)$m['id'] ?>"
                        data-name="<?= e($m['name']) ?>"
                        data-email="<?= e($m['email']) ?>"
                        data-course="<?= e($m['course'] ?? '') ?>"
                        data-role="<?= e($m['roles']) ?>"
                        data-active="<?= (int)$m['is_active'] ?>">

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900"><?= (int)$m['id'] ?></span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    <?= strtoupper(substr(e($m['name']), 0, 1)) ?>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-900"><?= e($m['name']) ?></span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600"><?= e($m['email']) ?></span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">
                                <?= !empty($m['course']) ? e($m['course']) : '<span class="text-gray-400">-</span>' ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= e($m['roles']) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <?= User::activeLabel((int)$m['is_active']) ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= e($m['created_at']) ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <?php if (!userHasRole($user, 'member')): ?>
                                <div class="flex items-center justify-end gap-2">
                                    <!-- EDIT -->
                                    <button
                                        type="button"
                                        class="editBtn inline-flex items-center justify-center w-8 h-8 rounded-lg text-white bg-green-500 hover:bg-green-600 transition-colors duration-150 shadow-sm hover:shadow cursor-pointer"
                                        data-id="<?= (int)$m['id'] ?>"
                                        title="Edit member">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <!-- DELETE -->
                                    <button
                                        type="button"
                                        class="deleteBtn inline-flex items-center justify-center w-8 h-8 rounded-lg text-white bg-red-500 hover:bg-red-600 transition-colors duration-150 shadow-sm hover:shadow cursor-pointer"
                                        data-id="<?= $m['id'] ?>"
                                        data-name="<?= e($m['name']) ?>"
                                        data-url="<?= e(BASE_URL) ?>delete-member"
                                        title="Delete member">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
    <div class="flex justify-end mt-4 pb-4">
        <div id="membersPagination" class="flex gap-2 flex-wrap"></div>
    </div>
    <!-- Optional: Scroll indicator for mobile -->
    <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-center lg:hidden">
        <p class="text-xs text-gray-500">← Scroll horizontally to see more →</p>
    </div>
</div>
<script>
    $(function() {
        Paginator({
            itemsSelector: "table tbody tr",
            searchInputSelector: "#membersSearch", // remove if you don't have search
            paginationSelector: "#membersPagination",
            itemsPerPage: 5
        });
    });
</script>