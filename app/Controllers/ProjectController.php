<?php

declare(strict_types=1);

require_once __DIR__ . '/../Models/Project.php';

class ProjectsController
{
    private static function authorize(): array
    {
        $user = $_SESSION['user'] ?? null;

        if (!$user) {
            ResponseService::json(false, 'Unauthenticated', [], 401);
        }

        $role = $user['role'] ?? '';
        $allowed = ['admin', 'super_admin', 'instructor'];

        if (!in_array($role, $allowed, true)) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        return $user;
    }

    private static function clean(?string $v): ?string
    {
        $v = isset($v) ? trim($v) : null;
        return $v === '' ? null : $v;
    }

    public static function index(): void
    {
        self::authorize();
        global $db;

        $projects = Project::all($db);
        ResponseService::json(true, 'Projects list', $projects, 200);
    }

    public static function show(int $id): void
    {
        self::authorize();
        global $db;

        $project = Project::find($db, $id);
        if (!$project) {
            ResponseService::json(false, 'Project not found', [], 404);
        }

        ResponseService::json(true, 'Project detail', $project, 200);
    }

    public static function store(): void
    {
        $user = self::authorize();
        global $db;

        $name = self::clean($_POST['name'] ?? null);
        if (!$name) {
            ResponseService::json(false, 'Name is required', [], 422);
        }

        $data = [
            'name' => $name,
            'description' => self::clean($_POST['description'] ?? null),
            'status' => self::clean($_POST['status'] ?? 'active') ?? 'active',
            'created_by' => (int)$user['id'],
            'start_date' => self::clean($_POST['start_date'] ?? null),
            'end_date' => self::clean($_POST['end_date'] ?? null),
        ];

        $newId = Project::create($db, $data);
        $created = Project::find($db, $newId);

        ResponseService::json(true, 'Project created', $created ?? ['id' => $newId], 201);
    }

    public static function update(int $id): void
    {
        self::authorize();
        global $db;

        $project = Project::find($db, $id);
        if (!$project) {
            ResponseService::json(false, 'Project not found', [], 404);
        }

        // accept PUT/PATCH body (x-www-form-urlencoded or querystring style)
        $raw = file_get_contents('php://input');
        parse_str($raw, $put);

        $name = self::clean($put['name'] ?? $project['name']);
        if (!$name) {
            ResponseService::json(false, 'Name is required', [], 422);
        }

        $data = [
            'name' => $name,
            'description' => array_key_exists('description', $put) ? self::clean($put['description']) : ($project['description'] ?? null),
            'status' => array_key_exists('status', $put) ? (self::clean($put['status']) ?? 'active') : ($project['status'] ?? 'active'),
            'start_date' => array_key_exists('start_date', $put) ? self::clean($put['start_date']) : ($project['start_date'] ?? null),
            'end_date' => array_key_exists('end_date', $put) ? self::clean($put['end_date']) : ($project['end_date'] ?? null),
        ];

        Project::update($db, $id, $data);
        $updated = Project::find($db, $id);

        ResponseService::json(true, 'Project updated', $updated ?? [], 200);
    }

    public static function destroy(int $id): void
    {
        self::authorize();
        global $db;

        $project = Project::find($db, $id);
        if (!$project) {
            ResponseService::json(false, 'Project not found', [], 404);
        }

        Project::delete($db, $id);
        ResponseService::json(true, 'Project deleted', ['id' => $id], 200);
    }
}
