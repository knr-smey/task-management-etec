<?php

/**
 * expects:
 * - $projects
 * - $user
 */
?>

<style>
    .table-row-hover {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .table-row-hover:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .action-btn {
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .status-badge {
        position: relative;
        overflow: hidden;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .status-badge:hover::before {
        left: 100%;
    }

    .member-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        transition: all 0.2s ease;
    }

    .member-badge:hover {
        transform: scale(1.05);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-row {
        animation: fadeIn 0.4s ease-out forwards;
    }

    .table-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }
</style>

<div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-max">
            <thead>
                <tr class="table-header-gradient text-white">
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                            </svg>
                            ID
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                            Project
                        </div>
                    </th>
                    <th class="py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Members
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Description
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Status
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Start
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            End
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Created
                        </div>
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider">
                        <div class="flex items-center justify-end gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                            Actions
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody id="projectTableBody" class="divide-y divide-gray-100 bg-white invisible">
                <?php foreach ($projects as $index => $p): ?>
                    <tr class="projectRow table-row-hover hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 cursor-pointer fade-in-row"
                        style="animation-delay: <?= $index * 0.05 ?>s"
                        data-href="<?= e(BASE_URL) ?>project-detail?id=<?= (int)$p['id'] ?>"
                        data-id="<?= (int)$p['id'] ?>"
                        data-name="<?= e($p['name']) ?>"
                        data-description="<?= e($p['description'] ?? '') ?>"
                        data-status="<?= e($p['status'] ?? 'active') ?>"
                        data-start="<?= e($p['start_date'] ?? '') ?>"
                        data-end="<?= e($p['end_date'] ?? '') ?>">
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 text-white text-xs font-bold shadow-md">
                                    <?= (int)$p['id'] ?>
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900"><?= e($p['name']) ?></div>
                                    <div class="text-xs text-gray-500">Project #<?= (int)$p['id'] ?></div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="member-badge inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-semibold shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <?= (int)$p['member_count'] ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 max-w-xs">
                            <div class="text-sm text-gray-700 line-clamp-2">
                                <?php if (!empty($p['description'])): ?>
                                    <span class="inline-flex items-start gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?= e($p['description']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">No description</span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $status = (string)($p['status'] ?? 'active');
                            $statusConfig = [
                                'active' => [
                                    'class' => 'bg-gradient-to-r from-green-500 to-emerald-500',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                                ],
                                'pending' => [
                                    'class' => 'bg-gradient-to-r from-yellow-500 to-orange-500',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                                ],
                                'completed' => [
                                    'class' => 'bg-gradient-to-r from-blue-500 to-indigo-500',
                                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                                ]
                            ];
                            $config = $statusConfig[$status] ?? $statusConfig['active'];
                            ?>
                            <span class="status-badge inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold text-white shadow-lg <?= $config['class'] ?>">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?= $config['icon'] ?>
                                </svg>
                                <?= e(ucfirst($status)) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">
                                    <?= !empty($p['start_date']) ? e($p['start_date']) : '-' ?>
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-medium">
                                    <?= !empty($p['end_date']) ? e($p['end_date']) : '-' ?>
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">
                                    <?= e($p['created_at'] ?? '-') ?>
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <?php if (!userHasRole($user, 'member')): ?>
                                <div class="flex items-center justify-end gap-3">
                                    <!-- EDIT -->
                                    <button
                                        type="button"
                                        class="editProjectBtn action-btn group relative inline-flex items-center justify-center w-10 h-10 rounded-xl text-white bg-gradient-to-br from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 shadow-lg hover:shadow-xl transition-all duration-200"
                                        title="Edit project">
                                        <svg class="w-5 h-5 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                            Edit
                                        </span>
                                    </button>

                                    <!-- DELETE -->
                                    <button
                                        type="button"
                                        class="deleteBtn action-btn group relative inline-flex items-center justify-center w-10 h-10 rounded-xl text-white bg-gradient-to-br from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 shadow-lg hover:shadow-xl transition-all duration-200"
                                        data-id="<?= $p['id'] ?>"
                                        data-url="<?= e(BASE_URL) ?>delete-project"
                                        data-title="Delete Project"
                                        data-message="Are you sure you want to delete this project?"
                                        title="Delete project">
                                        <svg class="w-5 h-5 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                            Delete
                                        </span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-semibold">Showing projects</span>
            </div>
            <div id="projectPagenation" class="flex gap-2 flex-wrap"></div>
        </div>
    </div>

    <!-- Mobile Scroll Hint -->
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-3 border-t border-gray-200 text-center lg:hidden">
        <div class="flex items-center justify-center gap-2 text-xs text-gray-600">
            <svg class="w-4 h-4 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
            </svg>
            <span class="font-medium">Scroll horizontally to see more</span>
            <svg class="w-4 h-4 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </div>
    </div>
</div>

<script>
    $(function() {
        Paginator({
            itemsSelector: "table tbody tr",
            // searchInputSelector: "", // remove if you don't have search
            paginationSelector: "#projectPagenation",
            itemsPerPage: 5
        });

        $("#projectTableBody").removeClass("invisible");
    });
</script>