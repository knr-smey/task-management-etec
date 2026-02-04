<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/TaskStatus.php';
require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';

class TaskController
{
    /**
     * Only super_admin / admin / instructor
     */
    private static function authorize(): array
    {
        $user = $_SESSION['user'] ?? [];

        if (
            !userHasRole($user, 'super_admin') &&
            !userHasRole($user, 'admin') &&
            !userHasRole($user, 'instructor')
        ) {
            // Page access denied
            redirect('dashboard');
        }

        return $user;
    }

    /**
     * Any logged-in user
     */
    private static function authorizeAny(): array
    {
        $user = $_SESSION['user'] ?? [];

        if (!$user) {
            ResponseService::json(false, 'Unauthorized', [], 401);
        }

        return $user;
    }

    private static function canManageTask(array $user, array $task): bool
    {
        if (
            userHasRole($user, 'super_admin') ||
            userHasRole($user, 'admin') ||
            userHasRole($user, 'instructor')
        ) {
            return true;
        }

        return (int)($task['created_by'] ?? 0) === (int)($user['id'] ?? 0);
    }

    private static function ensureProjectAccess(int $projectId, array $user): ?array
    {
        if ($projectId <= 0) {
            return null;
        }

        return Project::findWithTeam($projectId, (int)$user['id']);
    }

    /**
     * PAGE: Task page
     * URL: /task
     */
    public static function index(): void
    {
        $user = self::authorize();

        // CSRF for future forms
        $token = csrf_token();

        // Just render page (no model yet)
        require __DIR__ . '/../../pages/tasks/index.php';
    }

    /**
     * API: list tasks by project
     */
    public static function listByProject(): void
    {
        $user = self::authorizeAny();

        $projectId = (int)($_GET['project_id'] ?? 0);
        $project = self::ensureProjectAccess($projectId, $user);
        if (!$project) {
            ResponseService::json(false, 'Project not found or forbidden', [], 403);
        }

        $tasks = Task::allByProject($projectId);
        ResponseService::json(true, 'OK', ['tasks' => $tasks]);
    }

    /**
     * API: create task
     */
    public static function create(): void
    {
        $user = self::authorizeAny();

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $projectId = (int)($_POST['project_id'] ?? 0);
        $project = self::ensureProjectAccess($projectId, $user);
        if (!$project) {
            ResponseService::json(false, 'Project not found or forbidden', [], 403);
        }

        $title = trim((string)($_POST['title'] ?? ''));
        if ($title === '') {
            ResponseService::json(false, 'Title is required', [], 422);
        }

        $assigneeId = (int)($_POST['assignee_id'] ?? 0);

        if ($assigneeId > 0) {
            $assignedIds = Project::getAssignedMemberIds($projectId);
            if (!in_array($assigneeId, $assignedIds, true) && $assigneeId !== (int)$user['id']) {
                ResponseService::json(false, 'Assignee not in this project', [], 422);
            }
        }

        $data = [
            'project_id'     => $projectId,
            'title'          => $title,
            'description'    => $_POST['description'] ?? null,
            'status_id'      => (int)($_POST['status_id'] ?? 1),
            'priority'       => $_POST['priority'] ?? 'medium',
            'estimate_hours' => $_POST['estimate_hours'] ?? null,
            'due_date'       => $_POST['due_date'] ?? null,
            'created_by'     => (int)$user['id'],
        ];

        $newId = Task::create($data);
        if ($newId > 0) {
            $finalAssigneeId = $assigneeId > 0 ? $assigneeId : (int)$user['id'];
            Task::assignSingle($newId, $finalAssigneeId, (int)$user['id']);
            ResponseService::json(true, 'Task created', ['id' => $newId]);
        }

        ResponseService::json(false, 'Create failed', [], 500);
    }

    /**
     * API: update task
     */
    public static function update(): void
    {
        $user = self::authorizeAny();

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $taskId = (int)($_POST['id'] ?? 0);
        if ($taskId <= 0) {
            ResponseService::json(false, 'Task ID is required', [], 422);
        }

        $task = Task::find($taskId);
        if (!$task) {
            ResponseService::json(false, 'Task not found', [], 404);
        }

        $project = self::ensureProjectAccess((int)$task['project_id'], $user);
        if (!$project) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        if (!self::canManageTask($user, $task)) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        $title = trim((string)($_POST['title'] ?? ''));
        if ($title === '') {
            ResponseService::json(false, 'Title is required', [], 422);
        }

        $data = [
            'title'          => $title,
            'description'    => $_POST['description'] ?? null,
            'status_id'      => (int)($_POST['status_id'] ?? $task['status_id']),
            'priority'       => $_POST['priority'] ?? $task['priority'] ?? 'medium',
            'estimate_hours' => $_POST['estimate_hours'] ?? $task['estimate_hours'] ?? null,
            'due_date'       => $_POST['due_date'] ?? $task['due_date'] ?? null,
        ];

        $ok = Task::update($taskId, $data);
        if ($ok) {
            ResponseService::json(true, 'Task updated');
        }

        ResponseService::json(false, 'Update failed', [], 500);
    }

    /**
     * API: delete task
     */
    public static function destroy(): void
    {
        $user = self::authorizeAny();

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $taskId = (int)($_POST['id'] ?? 0);
        if ($taskId <= 0) {
            ResponseService::json(false, 'Task ID is required', [], 422);
        }

        $task = Task::find($taskId);
        if (!$task) {
            ResponseService::json(false, 'Task not found', [], 404);
        }

        $project = self::ensureProjectAccess((int)$task['project_id'], $user);
        if (!$project) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        if (!self::canManageTask($user, $task)) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        $ok = Task::delete($taskId);
        if ($ok) {
            ResponseService::json(true, 'Task deleted');
        }

        ResponseService::json(false, 'Delete failed', [], 500);
    }

    /**
     * API: assign task to member
     */
    public static function assign(): void
    {
        $user = self::authorizeAny();

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $taskId = (int)($_POST['task_id'] ?? 0);
        $assigneeId = (int)($_POST['assignee_id'] ?? 0);

        if ($taskId <= 0 || $assigneeId <= 0) {
            ResponseService::json(false, 'Task and assignee are required', [], 422);
        }

        $task = Task::find($taskId);
        if (!$task) {
            ResponseService::json(false, 'Task not found', [], 404);
        }

        $project = Project::findWithTeam((int)$task['project_id'], (int)$user['id']);
        if (!$project) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        $teamMembers = $project['team']['members'] ?? [];
        $allowedIds = array_map('intval', array_column($teamMembers, 'id'));

        if (empty($allowedIds) || !in_array($assigneeId, $allowedIds, true)) {
            ResponseService::json(false, 'Assignee not in this team', [], 422);
        }

        $ok = Task::assignSingle($taskId, $assigneeId, (int)$user['id']);
        if ($ok) {
            ResponseService::json(true, 'Task assigned');
        }

        ResponseService::json(false, 'Assign failed', [], 500);
    }

    /**
     * API: update issue status by task
     */
    public static function updateTaskStatus(): void
    {
        $user = self::authorizeAny();

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $taskId = (int)($_POST['task_id'] ?? 0);
        $statusId = (int)($_POST['status_id'] ?? 0);

        if ($taskId <= 0 || $statusId <= 0) {
            ResponseService::json(false, 'Task and status are required', [], 422);
        }

        if (!TaskStatus::exists($statusId)) {
            ResponseService::json(false, 'Invalid status', [], 422);
        }

        $task = Task::find($taskId);
        if (!$task) {
            ResponseService::json(false, 'Task not found', [], 404);
        }

        $project = Project::findWithTeam((int)$task['project_id'], (int)$user['id']);
        if (!$project) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        $ok = Task::update((int)$task['id'], [
            'title'          => $task['title'],
            'description'    => $task['description'] ?? null,
            'status_id'      => $statusId,
            'priority'       => $task['priority'] ?? 'medium',
            'estimate_hours' => $task['estimate_hours'] ?? null,
            'due_date'       => $task['due_date'] ?? null,
        ]);
        if ($ok) {
            ResponseService::json(true, 'Status updated');
        }

        ResponseService::json(false, 'Update failed', [], 500);
    }
}
