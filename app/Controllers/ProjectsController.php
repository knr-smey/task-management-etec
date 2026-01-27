<?php
declare(strict_types=1);

require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';

class ProjectsController
{
    private static function authorize(): array
    {
        $user = $_SESSION['user'] ?? [];

        if (
            !userHasRole($user, 'super_admin') &&
            !userHasRole($user, 'admin') &&
            !userHasRole($user, 'instructor')
        ) {
            // if this is a PAGE request -> you can redirect instead
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        return $user;
    }

    // ✅ PAGE: show projects in dashboard (member cannot see)
    public static function index(): void
    {
        $user = self::authorize();

        // only show projects created by himself
        $projects = Project::allByCreator((int)$user['id']);

        // ✅ choose your page path (example)
        require __DIR__ . '/../../pages/dashboard/index.php';
    }

    // ✅ API: create/update (same like member store)
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

        // ✅ if you want “edit only my project”
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

    // ✅ API: delete (same like member destroy)
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
}
