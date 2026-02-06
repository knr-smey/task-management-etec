<?php

declare(strict_types=1);

require __DIR__ . '/../../config/app.php';
require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layouts/app.php';

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
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Activity</h1>
        <p class="text-sm text-slate-500">Select a project to view task history and member activity.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="px-4 py-3 border-b border-slate-200">
                    <h2 class="text-sm font-semibold text-slate-600 uppercase tracking-wider">Projects</h2>
                </div>
                <div class="p-3 space-y-2">
                    <?php if (empty($projects)): ?>
                        <p class="text-sm text-slate-500 px-2 py-2">No projects available.</p>
                    <?php else: ?>
                        <?php foreach ($projects as $project): ?>
                            <?php
                                $isActive = (int)$selectedProjectId === (int)$project['id'];
                                $link = e(BASE_URL) . 'activity?project_id=' . (int)$project['id'];
                            ?>
                            <a href="<?= e($link) ?>"
                               class="block px-3 py-2.5 rounded-xl border text-sm font-medium transition-all
                               <?= $isActive
                                    ? 'bg-blue-600 text-white border-blue-600 shadow'
                                    : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-white hover:border-blue-200'
                               ?>">
                                <div class="flex items-center justify-between">
                                    <span class="truncate"><?= e($project['name']) ?></span>
                                    <span class="text-xs <?= $isActive ? 'text-blue-100' : 'text-slate-400' ?>">
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
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Project Activity</h2>
                        <p class="text-sm text-slate-500">
                            <?= $selectedProject ? e($selectedProject['name'] ?? '') : 'No project selected' ?>
                        </p>
                    </div>
                </div>

                <div class="p-6">
                    <?php if (!$selectedProject): ?>
                        <div class="text-center py-12">
                            <div class="text-slate-500">Choose a project from the left to view activity.</div>
                        </div>
                    <?php elseif (empty($logs)): ?>
                        <div class="text-center py-12">
                            <div class="text-slate-500">No activity yet for this project.</div>
                        </div>
                    <?php else: ?>
                        <ul class="space-y-4">
                            <?php foreach ($logs as $log): ?>
                                <?php
                                    $oldValue = json_decode($log['old_value'] ?? '', true) ?? [];
                                    $newValue = json_decode($log['new_value'] ?? '', true) ?? [];

                                    $action = (string)($log['action'] ?? 'updated');
                                    $label = $actionLabels[$action] ?? ucwords(str_replace('_', ' ', $action));

                                    $taskTitle = $log['task_title']
                                        ?? ($newValue['title'] ?? null)
                                        ?? ($oldValue['title'] ?? null);

                                    $entityText = $taskTitle ? "Task: {$taskTitle}" : "Task #" . (int)$log['entity_id'];
                                    if (($log['entity_type'] ?? '') === 'project') {
                                        $entityText = 'Project: ' . ($log['project_name'] ?? ('#' . (int)$log['entity_id']));
                                    }
                                ?>
                                <li class="flex gap-4">
                                    <div class="w-2.5 h-2.5 mt-2 rounded-full bg-blue-500"></div>
                                    <div class="flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-semibold text-slate-800"><?= e($log['actor_name'] ?? 'User') ?></span>
                                            <span class="text-slate-500 text-sm"><?= e($label) ?></span>
                                            <span class="text-slate-400 text-xs">• <?= e(date('M d, Y H:i', strtotime($log['created_at'] ?? 'now'))) ?></span>
                                        </div>
                                        <div class="text-sm text-slate-600 mt-1"><?= e($entityText) ?></div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/layouts/app-footer.php'; ?>
