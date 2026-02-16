<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layouts/app.php';
require_once __DIR__ . '/../../app/Models/TaskStatus.php';

$selectedProjectId = $selectedProjectId ?? 0;
$projects = $projects ?? [];
$logs = $logs ?? [];
$selectedProject = $selectedProject ?? null;

$actionLabels = [
    'created' => 'Created task',
    'updated' => 'Updated task',
    'deleted' => 'Deleted task',
    'assigned' => 'Assigned task',
    'status_changed' => 'Changed status',
    'time_logged' => 'Logged time',
    'project_created' => 'Created project',
    'project_updated' => 'Updated project',
    'closed' => 'Closed task',
];

$actionTone = [
    'created' => [
        'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'dot' => 'bg-emerald-500',
    ],
    'updated' => [
        'badge' => 'bg-amber-50 text-amber-700 border-amber-200',
        'dot' => 'bg-amber-500',
    ],
    'deleted' => [
        'badge' => 'bg-rose-50 text-rose-700 border-rose-200',
        'dot' => 'bg-rose-500',
    ],
    'assigned' => [
        'badge' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
        'dot' => 'bg-cyan-500',
    ],
    'status_changed' => [
        'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'dot' => 'bg-indigo-500',
    ],
    'time_logged' => [
        'badge' => 'bg-violet-50 text-violet-700 border-violet-200',
        'dot' => 'bg-violet-500',
    ],
    'project_created' => [
        'badge' => 'bg-green-50 text-green-700 border-green-200',
        'dot' => 'bg-green-500',
    ],
    'project_updated' => [
        'badge' => 'bg-sky-50 text-sky-700 border-sky-200',
        'dot' => 'bg-sky-500',
    ],
    'closed' => [
        'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
        'dot' => 'bg-slate-500',
    ],
];

$fieldLabels = [
    'title' => 'Title',
    'description' => 'Description',
    'status_id' => 'Status',
    'priority' => 'Priority',
    'estimate_hours' => 'Estimate Hours',
    'due_date' => 'Due Date',
    'assignee_id' => 'Assignee',
    'project_id' => 'Project',
];

$statusNameById = [];
foreach (TaskStatus::all() as $statusRow) {
    $statusId = (int)($statusRow['id'] ?? 0);
    if ($statusId > 0) {
        $statusNameById[$statusId] = ucwords((string)($statusRow['name'] ?? ''));
    }
}

$totalProjects = count($projects);
$totalLogs = count($logs);
?>

<div class="max-w-7xl mx-auto space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-gradient-to-r from-white to-slate-50 p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#274193] text-white shadow-lg shadow-[#274193]/25">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4V7m2 12H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Activity</h1>
                </div>
                <p class="mt-2 text-sm text-slate-500">Track task updates, assignments, and project changes in one place.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700">
                    <span class="h-2 w-2 rounded-full bg-[#274193]"></span>
                    <?= (int)$totalProjects ?> Projects
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    <?= (int)$totalLogs ?> Logs
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <aside class="lg:col-span-1">
            <div class="sticky top-24 rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-4 py-3">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-600">Projects</h2>
                    <p class="mt-1 text-xs text-slate-500">Choose one project to view timeline.</p>
                </div>

                <div class="border-b border-slate-100 p-3">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            id="activityProjectSearch"
                            type="text"
                            placeholder="Search project..."
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-700 outline-none transition focus:border-[#274193] focus:bg-white focus:ring-2 focus:ring-[#274193]/20">
                    </div>
                </div>

                <div id="activityProjectList" class="max-h-[520px] space-y-2 overflow-auto p-3">
                    <?php if (empty($projects)): ?>
                        <p class="px-2 py-2 text-sm text-slate-500">No projects available.</p>
                    <?php else: ?>
                        <?php foreach ($projects as $project): ?>
                            <?php
                                $isActive = (int)$selectedProjectId === (int)$project['id'];
                                $link = e(BASE_URL) . 'activity?project_id=' . (int)$project['id'];
                            ?>
                            <a href="<?= e($link) ?>"
                               data-project-item="true"
                               class="group block rounded-xl border px-3 py-2.5 text-sm font-medium transition-all
                               <?= $isActive
                                    ? 'border-[#274193] bg-[#274193] text-white shadow'
                                    : 'border-slate-200 bg-slate-50 text-slate-700 hover:border-[#274193]/30 hover:bg-white'
                               ?>">
                                <div class="flex items-center justify-between">
                                    <span class="truncate"><?= e($project['name']) ?></span>
                                    <span class="text-xs <?= $isActive ? 'text-blue-100' : 'text-slate-400 group-hover:text-[#274193]' ?>">
                                        <?= e($project['status'] ?? 'active') ?>
                                    </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <section class="lg:col-span-3">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-6 py-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Project Timeline</h2>
                        <p class="text-sm text-slate-500">
                            <?= $selectedProject ? e($selectedProject['name'] ?? '') : 'No project selected' ?>
                        </p>
                    </div>
                    <?php if ($selectedProject): ?>
                        <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700">
                            <span class="h-2 w-2 rounded-full bg-[#274193]"></span>
                            <?= (int)$totalLogs ?> Activities
                        </span>
                    <?php endif; ?>
                </div>

                <div class="p-6">
                    <?php if (!$selectedProject): ?>
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 py-16 text-center">
                            <div class="mx-auto mb-3 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="text-slate-500">Choose a project from the left to view activity.</div>
                        </div>
                    <?php elseif (empty($logs)): ?>
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 py-16 text-center">
                            <div class="mx-auto mb-3 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4V7m2 12H5a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="text-slate-500">No activity yet for this project.</div>
                        </div>
                    <?php else: ?>
                        <ol class="relative ml-3 space-y-5 border-l border-slate-200 pl-6">
                            <?php foreach ($logs as $log): ?>
                                <?php
                                    $oldValue = json_decode($log['old_value'] ?? '', true) ?? [];
                                    $newValue = json_decode($log['new_value'] ?? '', true) ?? [];

                                    $action = (string)($log['action'] ?? 'updated');
                                    $label = $actionLabels[$action] ?? ucwords(str_replace('_', ' ', $action));

                                    $taskTitle = $log['task_title']
                                        ?? ($newValue['title'] ?? null)
                                        ?? ($oldValue['title'] ?? null);

                                    $entityText = $taskTitle ? "Task: {$taskTitle}" : 'Task #' . (int)$log['entity_id'];
                                    if (($log['entity_type'] ?? '') === 'project') {
                                        $entityText = 'Project: ' . ($log['project_name'] ?? ('#' . (int)$log['entity_id']));
                                    }

                                    $statusDetailText = null;
                                    if (array_key_exists('status_id', $oldValue) || array_key_exists('status_id', $newValue)) {
                                        $oldStatusId = (int)($oldValue['status_id'] ?? 0);
                                        $newStatusId = (int)($newValue['status_id'] ?? 0);

                                        $oldStatusName = $statusNameById[$oldStatusId] ?? ($oldStatusId > 0 ? ('#' . $oldStatusId) : 'N/A');
                                        $newStatusName = $statusNameById[$newStatusId] ?? ($newStatusId > 0 ? ('#' . $newStatusId) : 'N/A');

                                        if ($oldStatusName !== $newStatusName) {
                                            $statusDetailText = 'Status: ' . $oldStatusName . ' -> ' . $newStatusName;
                                        } elseif ($newStatusName !== 'N/A') {
                                            $statusDetailText = 'Status: ' . $newStatusName;
                                        }
                                    }

                                    $tone = $actionTone[$action] ?? [
                                        'badge' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'dot' => 'bg-slate-500',
                                    ];

                                    $actorName = (string)($log['actor_name'] ?? 'User');
                                    $actorInitial = strtoupper(substr($actorName, 0, 1));

                                    $changedKeys = [];
                                    if (!empty($newValue)) {
                                        foreach ($newValue as $key => $value) {
                                            $old = $oldValue[$key] ?? null;
                                            if ($old !== $value) {
                                                $changedKeys[] = (string)$key;
                                            }
                                        }
                                    }
                                    $changedKeys = array_slice(array_values(array_unique($changedKeys)), 0, 3);
                                ?>
                                <li class="relative">
                                    <span class="absolute -left-[33px] top-4 h-3 w-3 rounded-full ring-4 ring-white <?= e($tone['dot']) ?>"></span>
                                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md">
                                        <div class="flex flex-wrap items-start justify-between gap-3">
                                            <div class="flex items-start gap-3">
                                                <div class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-xs font-bold text-slate-700">
                                                    <?= e($actorInitial) ?>
                                                </div>
                                                <div>
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <span class="font-semibold text-slate-800"><?= e($actorName) ?></span>
                                                        <span class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-semibold <?= e($tone['badge']) ?>">
                                                            <?= e($label) ?>
                                                        </span>
                                                    </div>
                                                    <div class="mt-1 text-sm text-slate-600"><?= e($entityText) ?></div>
                                                    <?php if (!empty($statusDetailText)): ?>
                                                        <div class="mt-1 text-xs font-medium text-indigo-600"><?= e($statusDetailText) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <span class="text-xs text-slate-400">
                                                <?= e(date('M d, Y H:i', strtotime($log['created_at'] ?? 'now'))) ?>
                                            </span>
                                        </div>

                                        <?php if (!empty($changedKeys)): ?>
                                            <div class="mt-3 flex flex-wrap items-center gap-1.5">
                                                <?php foreach ($changedKeys as $field): ?>
                                                    <?php
                                                        $fieldLabel = $fieldLabels[$field] ?? ucwords(str_replace('_', ' ', (string)$field));
                                                    ?>
                                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-[11px] font-medium text-slate-600">
                                                        <?= e($fieldLabel) ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>

<script>
(() => {
    const input = document.getElementById('activityProjectSearch');
    const list = document.getElementById('activityProjectList');
    if (!input || !list) return;

    input.addEventListener('input', () => {
        const query = input.value.trim().toLowerCase();
        const items = list.querySelectorAll('[data-project-item="true"]');
        items.forEach((item) => {
            const text = (item.textContent || '').toLowerCase();
            item.classList.toggle('hidden', query !== '' && !text.includes(query));
        });
    });
})();
</script>
