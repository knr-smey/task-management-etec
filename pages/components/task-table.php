<!-- Tasks Section -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-visible">
    <!-- Tasks Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-slate-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Tasks</h2>
                    <p class="text-sm text-gray-500">Manage and track project tasks</p>
                </div>
            </div>
            <button
                type="button"
                id="openCreateTaskBtn"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 shadow-sm hover:shadow-md transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Task
            </button>
        </div>
    </div>

    <!-- Task Table -->
    <div class="overflow-x-auto overflow-y-visible">
        <table class="w-full min-w-max">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    <th class="px-6 py-3.5">Task</th>
                    <th class="px-6 py-3.5">Status</th>
                    <th class="px-6 py-3.5">Priority</th>
                    <th class="px-6 py-3.5">Assign</th>
                    <th class="px-6 py-3.5">Due Date</th>
                    <th class="px-6 py-3.5">Created By</th>
                    <th class="px-6 py-3.5">Created</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-900 font-medium mb-1">No tasks yet</p>
                                    <p class="text-sm text-gray-500">Get started by creating your first task</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tasks as $t): ?>
                        <?php
                        $priority = strtolower((string)($t['priority'] ?? 'medium'));
                        $priorityColors = [
                            'high' => 'bg-red-100 text-red-700 border-red-200',
                            'medium' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'low' => 'bg-gray-100 text-gray-700 border-gray-200'
                        ];
                        $priorityClass = $priorityColors[$priority] ?? $priorityColors['medium'];
                        ?>
                        <tr class="hover:bg-indigo-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 mb-0.5"><?= e($t['title'] ?? '-') ?></div>
                                        <?php if (!empty($t['description'])): ?>
                                            <div class="text-sm text-gray-500 line-clamp-2"><?= e($t['description']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    <?= e($t['status_name'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border <?= $priorityClass ?>">
                                    <?= ucfirst($priority) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!empty($t['assignee_name'])): ?>
                                    <span class="text-sm font-medium text-gray-800"><?= e($t['assignee_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">Unassigned</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!empty($t['due_date'])): ?>
                                    <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <?= e($t['due_date']) ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?= e($t['creator_name'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?= e($t['created_at'] ?? '-') ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="relative inline-block task-action-wrapper">
                                    <button
                                        type="button"
                                        class="taskActionToggle inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-all duration-200 group"
                                        data-task-id="<?= (int)$t['id'] ?>"
                                        aria-label="Task actions">
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="1" fill="currentColor" />
                                            <circle cx="12" cy="5" r="1" fill="currentColor" />
                                            <circle cx="12" cy="19" r="1" fill="currentColor" />
                                        </svg>
                                    </button>

                                    <div class="taskActionMenu hidden absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden z-50 transform opacity-0 scale-95 transition-all duration-200">
                                        <!-- Main Menu -->
                                        <div class="menu-main">
                                            <!-- Header -->
                                            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-slate-50 text-left">
                                                <h3 class="text-sm font-semibold text-gray-900">Task Actions</h3>
                                                <p class="text-xs text-gray-500 mt-0.5">Manage this task</p>
                                            </div>

                                            <!-- Menu Items -->
                                            <div class="py-2">
                                                <button class="menu-item group w-full text-left px-4 py-3 text-sm hover:bg-indigo-50 transition-colors flex items-center gap-3" data-menu="assign">
                                                    <div class="w-9 h-9 rounded-lg bg-blue-100 group-hover:bg-blue-200 flex items-center justify-center transition-colors">
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 text-left">
                                                        <div class="font-medium text-gray-900">Assign Member</div>
                                                        <div class="text-xs text-gray-500">Change task assignee</div>
                                                    </div>
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </button>

                                                <button class="menu-item group w-full text-left px-4 py-3 text-sm hover:bg-indigo-50 transition-colors flex items-center gap-3" data-menu="status">
                                                    <div class="w-9 h-9 rounded-lg bg-green-100 group-hover:bg-green-200 flex items-center justify-center transition-colors">
                                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 text-left">
                                                        <div class="font-medium text-gray-900">Update Status</div>
                                                        <div class="text-xs text-gray-500">Change task status</div>
                                                    </div>
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </button>

                                                <div class="border-t border-gray-100 my-2"></div>

                                                <button class="menu-log group w-full text-left px-4 py-3 text-sm hover:bg-purple-50 transition-colors flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-lg bg-purple-100 group-hover:bg-purple-200 flex items-center justify-center transition-colors">
                                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 text-left">
                                                        <div class="font-medium text-gray-900">View Activity Log</div>
                                                        <div class="text-xs text-gray-500">See task history</div>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Assign Submenu -->
                                        <div class="menu-assign hidden">
                                            <!-- Header -->
                                            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50 flex items-start justify-between">
                                                <button class="menu-back flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                    Back
                                                </button>
                                                <div class="text-right">
                                                    <h3 class="text-sm font-semibold text-gray-900">Assign Member</h3>
                                                    <p class="text-xs text-gray-500 mt-0.5">Select a team member</p>
                                                </div>
                                            </div>

                                            <!-- Search -->
                                            <div class="px-3 py-2 border-b border-gray-100">
                                                <div class="relative">
                                                    <input
                                                        type="text"
                                                        class="assign-search w-full pl-9 pr-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                                                        placeholder="Search members...">
                                                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Members List -->
                                            <?php include __DIR__ . '/task-assign-list.php'; ?>
                                        </div>

                                        <!-- Status Submenu -->
                                        <div class="menu-status hidden">
                                            <!-- Header -->
                                            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50 flex items-start justify-between">
                                                <button class="menu-back flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                    Back
                                                </button>
                                                <div class="text-right">
                                                    <h3 class="text-sm font-semibold text-gray-900">Update Status</h3>
                                                    <p class="text-xs text-gray-500 mt-0.5">Change task status</p>
                                                </div>
                                            </div>

                                            <!-- Status List -->
                                            <?php include __DIR__ . '/task-status-list.php'; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>