<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Task.php';
require_once __DIR__ . '/../Models/TaskStatus.php';
require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';

class ProjectsController
{
    private static function authorize(bool $allowMember = false): array
    {
        $user = $_SESSION['user'] ?? [];

        if (!$user) {
            ResponseService::json(false, 'Unauthorized', [], 401);
        }

        // ✅ allow member for read-only pages
        if ($allowMember) {
            return $user;
        }

        // ❌ strict access (admin only)
        if (
            !userHasRole($user, 'super_admin') &&
            !userHasRole($user, 'admin') &&
            !userHasRole($user, 'instructor')
        ) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        return $user;
    }


    // PAGE: show projects in dashboard (member cannot see)
    public static function index(): void
    {
        self::authorize();

        require_once __DIR__ . '/DashboardController.php';
        DashboardController::index();
    }
    

    // API: create/update (same like member store)
    public static function store(): void
    {
        $user = self::authorize();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $id = (int)($_POST['id'] ?? 0);

        $name = trim((string)($_POST['name'] ?? ''));
        if ($name === '') ResponseService::json(false, 'Project name is required', [], 422);

        // if you want “edit only my project”
        if ($id > 0) {
            $old = Project::find($id);
            if (!$old) ResponseService::json(false, 'Project not found', [], 404);

            if ((int)$old['created_by'] !== (int)$user['id']) {
                ResponseService::json(false, 'Forbidden (not your project)', [], 403);
            }
        }

        $data = [
            'name' => $name,
            'description' => $_POST['description'] ?? null,
            'status' => $_POST['status'] ?? 'active',
            'created_by' => (int)$user['id'],
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null,
        ];

        if ($id <= 0) {
            $newId = Project::create($data);
            if ($newId) ResponseService::json(true, 'Project created', ['id' => $newId]);
            ResponseService::json(false, 'Create failed', [], 500);
        } else {
            unset($data['created_by']); // do not change owner
            $ok = Project::update($id, $data);
            if ($ok) ResponseService::json(true, 'Project updated', []);
            ResponseService::json(false, 'Update failed', [], 500);
        }
    }

    // API: delete (same like member destroy)
    public static function destroy(): void
    {
        $user = self::authorize();

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $id = (int)($_POST['delete_id'] ?? 0);
        if ($id <= 0) ResponseService::json(false, 'ID is required', [], 422);

        $old = Project::find($id);
        if (!$old) ResponseService::json(false, 'Project not found', [], 404);

        if ((int)$old['created_by'] !== (int)$user['id']) {
            ResponseService::json(false, 'Forbidden (not your project)', [], 403);
        }

        $ok = Project::delete($id);
        if ($ok) ResponseService::json(true, 'Project deleted', []);
        ResponseService::json(false, 'Delete failed', [], 500);
    }

    public static function show(int $id): void
    {
        // allow member to view project detail
        $user = self::authorize(true);

        if ($id <= 0) {
            redirect('projects');
        }

        // 🔥 AUTHORIZED project fetch
        $project = Project::findWithTeam($id, (int)$user['id']);

        if (!$project) {
            // not found OR no permission
            redirect('projects');
        }

        // all members (for assign UI)
        $members = User::membersOnly();

        // assigned project members (checkbox checked)
        $assignedIds = Project::getAssignedMemberIds((int)$project['id']);

        // tasks under project (hide done tasks in table)
        $tasks = array_values(array_filter(
            Task::allByProject((int)$project['id']),
            static fn(array $task): bool => strtolower((string)($task['status_name'] ?? '')) !== 'done'
        ));

        // task statuses for actions
        $taskStatuses = TaskStatus::all();

        $token = csrf_token();

        require __DIR__ . '/../../pages/projects/show.php';
    }




    public static function assignMembers(): void
    {
        $user = self::authorize();

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF', [], 403);
        }

        $projectId = (int)($_POST['project_id'] ?? 0);
        $memberIds = $_POST['member_ids'] ?? [];

        if ($projectId <= 0) {
            ResponseService::json(false, 'Project ID required', [], 422);
        }

        if (!is_array($memberIds)) $memberIds = [];

        $ok = Project::assignMembers($projectId, $memberIds);

        if ($ok) {
            ResponseService::json(true, 'Members assigned');
        }

        ResponseService::json(false, 'Assign failed', [], 500);
    }

    public static function assignTeam(): void
    {
        $user = self::authorize(); // admin/instructor/super_admin only

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $projectId = (int)($_POST['project_id'] ?? 0);
        $teamId    = (int)($_POST['team_id'] ?? 0);

        if ($projectId <= 0 || $teamId <= 0) {
            ResponseService::json(false, 'Project ID and Team ID are required', [], 422);
        }

        // optional: check project owner
        $project = Project::find($projectId);
        if (!$project) {
            ResponseService::json(false, 'Project not found', [], 404);
        }

        if ((int)$project['created_by'] !== (int)$user['id']) {
            ResponseService::json(false, 'Forbidden (not your project)', [], 403);
        }

        $ok = Project::assignTeam($projectId, $teamId);

        if ($ok) {
            Project::syncMembersFromTeam($projectId, $teamId);

            ResponseService::json(true, 'Team assigned successfully');
        }

        ResponseService::json(false, 'Assign team failed', [], 500);
    }

    public static function unassignTeam(): void
    {
        $user = self::authorize(); // admin / instructor / super_admin

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $projectId = (int)($_POST['project_id'] ?? 0);

        if ($projectId <= 0) {
            ResponseService::json(false, 'Project ID required', [], 422);
        }

        $project = Project::find($projectId);
        if (!$project) {
            ResponseService::json(false, 'Project not found', [], 404);
        }

        // only project owner can unassign
        if ((int)$project['created_by'] !== (int)$user['id']) {
            ResponseService::json(false, 'Forbidden (not your project)', [], 403);
        }

        $ok = Project::unassignTeam($projectId);

        if ($ok) {
            ResponseService::json(true, 'Project unassigned from team');
        }

        ResponseService::json(false, 'Unassign failed', [], 500);
    }
}
