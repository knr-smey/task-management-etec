<?php

/**
 * expects:
 * - $members
 * - $user
 */
?>

<style>
    /* Clean animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table-row-enter {
        animation: fadeInUp 0.3s ease-out;
    }

    .action-btn {
        position: relative;
        overflow: hidden;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.4s, height 0.4s;
    }

    .action-btn:active::before {
        width: 100px;
        height: 100px;
    }

    /* Smooth avatar transition */
    .table-row:hover .member-avatar {
        transform: scale(1.05);
    }

    .member-avatar {
        transition: transform 0.2s ease;
    }

    /* Tooltip */
    .tooltip {
        position: relative;
    }

    .tooltip .tooltip-text {
        visibility: hidden;
        width: 100px;
        background-color: #1f2937;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 6px 8px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -50px;
        opacity: 0;
        transition: opacity 0.2s;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
    }

    .tooltip .tooltip-text::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #1f2937 transparent transparent transparent;
    }

    .tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }

    /* Clean scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="bg-white rounded-lg overflow-hidden border border-gray-200">
    <!-- Simple Header matching the page style -->
    <div class="bg-blue-600 px-6 py-4 border-b border-blue-700">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <div>
                    <h2 class="text-white font-semibold text-lg">Team Members</h2>
                    <p class="text-blue-200 text-sm"><?= count($members) ?> total members</p>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="bg-white/10 text-white px-3 py-1.5 rounded-md text-sm font-medium border border-white/20">
                    Active: <?= count(array_filter($members, fn($m) => $m['is_active'])) ?>
                </span>
                <span class="bg-pink-500 text-white px-3 py-1.5 rounded-md text-sm font-medium">
                    Inactive: <?= count(array_filter($members, fn($m) => !$m['is_active'])) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Responsive wrapper with horizontal scroll -->
    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-white border-b border-gray-200">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        # ID
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Course
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Role
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Created
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody id="membersTableBody" class="divide-y divide-gray-100 bg-white invisible">
                <?php foreach ($members as $index => $m): ?>
                    <tr class="table-row hover:bg-gray-50 transition-colors duration-150 group"
                        data-id="<?= (int)$m['id'] ?>"
                        data-name="<?= e($m['name']) ?>"
                        data-email="<?= e($m['email']) ?>"
                        data-course="<?= e($m['course'] ?? '') ?>"
                        data-role="<?= e($m['roles']) ?>"
                        data-active="<?= (int)$m['is_active'] ?>"
                        style="animation-delay: <?= $index * 0.03 ?>s;">

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-blue-600">
                                #<?= str_pad((int)$m['id'], 4, '0', STR_PAD_LEFT) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="member-avatar relative flex-shrink-0 h-10 w-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                    <?= strtoupper(substr(e($m['name']), 0, 1)) ?>
                                    <?php if ($m['is_active']): ?>
                                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></div>
                                    <?php endif; ?>
                                </div>
                                <span class="text-sm font-medium text-gray-900">
                                    <?= e($m['name']) ?>
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                <span class="text-sm text-gray-600">
                                    <?= e($m['email']) ?>
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if (!empty($m['course'])): ?>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                    <span class="text-sm text-gray-700">
                                        <?= e($m['course']) ?>
                                    </span>
                                </div>
                            <?php else: ?>
                                <span class="text-sm text-gray-400 italic">No course</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                <?= e($m['roles']) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <?= User::activeLabel((int)$m['is_active']) ?>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <?= e($m['created_at']) ?>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <?php if (!userHasRole($user, 'member')): ?>
                                <div class="flex items-center justify-end gap-2">
                                    <!-- EDIT -->
                                    <button
                                        type="button"
                                        class="editBtn action-btn tooltip inline-flex items-center justify-center w-9 h-9 rounded-lg text-white bg-green-500 hover:bg-green-600 transition-colors duration-150 cursor-pointer"
                                        data-id="<?= (int)$m['id'] ?>"
                                        title="Edit member">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="tooltip-text">Edit</span>
                                    </button>

                                    <!-- DELETE -->
                                    <button
                                        type="button"
                                        class="deleteBtn action-btn tooltip inline-flex items-center justify-center w-9 h-9 rounded-lg text-white bg-red-500 hover:bg-red-600 transition-colors duration-150 cursor-pointer"
                                        data-id="<?= $m['id'] ?>"
                                        data-name="<?= e($m['name']) ?>"
                                        data-url="<?= e(BASE_URL) ?>delete-member"
                                        title="Delete member">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="tooltip-text">Delete</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="bg-white px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="text-sm text-gray-600">
                Showing <span class="font-semibold text-gray-900" id="showingStart">1</span> to
                <span class="font-semibold text-gray-900" id="showingEnd">5</span> of
                <span class="font-semibold text-gray-900"><?= count($members) ?></span> members
            </div>
            <div id="membersPagination" class="flex gap-2 flex-wrap"></div>
        </div>
    </div>

    <!-- Mobile Scroll Indicator -->
    <div class="bg-gray-50 px-6 py-2 border-t border-gray-200 text-center lg:hidden">
        <p class="text-xs text-gray-500">
            ← Scroll horizontally to see more →
        </p>
    </div>
</div>

<script>
    $(function() {
        // Initialize paginator
        Paginator({
            itemsSelector: "table tbody tr",
            searchInputSelector: "#membersSearch",
            paginationSelector: "#membersPagination",
            itemsPerPage: 5
        });

        // Add row animation class and show table
        $("#membersTableBody tr").each(function(index) {
            $(this).addClass('table-row-enter');
        });

        $("#membersTableBody").removeClass("invisible");

        // Add ripple effect to action buttons
        $('.action-btn').on('click', function(e) {
            const $button = $(this);
            const ripple = $('<span class="ripple"></span>');
            $button.append(ripple);
            setTimeout(() => ripple.remove(), 400);
        });

        // Update showing count on pagination
        $(document).on('paginationUpdated', function(e, data) {
            if (data.totalItems > 0) {
                $('#showingStart').text(data.startItem);
                $('#showingEnd').text(data.endItem);
            }
        });
    });
</script>