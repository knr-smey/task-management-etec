<!-- Tasks Section -->
<style>
    .taskActionMenu .menu-submenu {
        position: absolute;
        top: 0;
        right: 100%;
        margin-right: 8px;
        width: 18rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
            0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        z-index: 60;
    }
</style>
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
                    <th class="px-6 py-3.5">Log Time (hrs)</th>
                    <th class="px-6 py-3.5">Created By</th>
                    <th class="px-6 py-3.5">Created</th>
                    <th class="px-6 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="tasksTableBody" class="divide-y divide-gray-100">
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
                        $isDone = strtolower((string)($t['status_name'] ?? '')) === 'done';
                        ?>
                        <tr class="taskRow hover:bg-indigo-50/50 transition-colors">
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
                            <td class="px-6 py-4">
                                <?php if ($t['estimate_hours'] !== null && $t['estimate_hours'] !== ''): ?>
                                    <span class="text-sm font-medium text-gray-800"><?= e((string)$t['estimate_hours']) ?></span>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">No-Log</span>
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

                                    <div class="taskActionMenu hidden absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-200 overflow-visible z-50 transform opacity-0 scale-95 transition-all duration-200">
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
                                    
                                                <button class="menu-close-task group w-full text-left px-4 py-3 text-sm hover:bg-emerald-50 transition-colors flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-lg bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center transition-colors">
                                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 text-left">
                                                        <div class="font-medium text-gray-900">Close Task</div>
                                                        <div class="text-xs text-gray-500">Mark as done</div>
                                                    </div>
                                                </button>
                                                

                                                <button class="menu-log-time group w-full text-left px-4 py-3 text-sm hover:bg-amber-50 transition-colors flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-lg bg-amber-100 group-hover:bg-amber-200 flex items-center justify-center transition-colors">
                                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 text-left">
                                                        <div class="font-medium text-gray-900">Log Time</div>
                                                        <div class="text-xs text-gray-500">Add hours worked</div>
                                                    </div>
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
                                        <div class="menu-assign hidden menu-submenu">
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
                                        <div class="menu-status hidden menu-submenu">
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

    <div class="px-6 py-4 border-t border-gray-100">
        <div id="tasksPagination" class="flex gap-1.5 flex-wrap"></div>
    </div>
</div>

<!-- Log Time Modal -->
<div id="logTimeModal" class="fixed bg-black/50 backdrop-blur-sm inset-0 hidden items-center justify-center z-50 p-4">
    <div id="logTimeModalContent"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">Log Time</h3>
                <p class="text-sm text-gray-500 mt-1">Add hours worked for this task</p>
            </div>
            <button type="button" id="closeLogTimeBtn"
                class="text-gray-400 hover:text-gray-600 hover:bg-white/70 rounded-lg p-2 transition-all duration-200"
                aria-label="Close log time modal">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="logTimeForm" class="p-6 space-y-5">
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="task_id" id="logTimeTaskId" value="">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Hours Worked <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input
                        id="logTimeHours"
                        type="number"
                        name="estimate_hours"
                        min="0"
                        step="0.25"
                        placeholder="e.g. 2.5"
                        required
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200 bg-white">
                </div>
                <p class="text-xs text-gray-500 mt-2">Use decimal format, for example: 1.5 or 2.25</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-5 mt-1 border-t border-gray-200">
                <button type="button" id="cancelLogTimeBtn"
                    class="cursor-pointer flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                    Cancel
                </button>
                <button type="submit"
                    class="cursor-pointer flex-1 bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 px-6 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-800 shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                    Save Time
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    #logTimeModal.flex #logTimeModalContent {
        animation: logTimeModalSlideIn 0.3s ease-out forwards;
    }

    @keyframes logTimeModalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
<script>
    $(function() {
        Paginator({
            itemsSelector: "#tasksTableBody tr.taskRow",
            paginationSelector: "#tasksPagination",
            itemsPerPage: 5
        });

        $("#tasksTableBody").removeClass("invisible");
    });
</script>
