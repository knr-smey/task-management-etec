<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../Models/User.php';

class MemberController
{
    private static function authorize(): array
    {
        $currentUser = $_SESSION['user'] ?? [];

        if (
            !userHasRole($currentUser, 'super_admin') &&
            !userHasRole($currentUser, 'admin')
        ) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        return $currentUser;
    }

    public static function index(): void
    {
        $currentUser = self::authorize();

        $teamFilterId = null;
        if (!userHasRole($currentUser, 'super_admin')) {
            $teamFilterId = (int)($_GET['team_id'] ?? 0);
            $teamFilterId = $teamFilterId > 0 ? $teamFilterId : null;
        }

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 7;

        $roleFilter = trim((string)($_GET['role'] ?? ''));
        if (!in_array($roleFilter, ['super_admin', 'admin', 'instructor', 'member'], true)) {
            $roleFilter = '';
        }

        $statusFilter = (string)($_GET['status'] ?? '');
        if ($statusFilter !== '0' && $statusFilter !== '1') {
            $statusFilter = '';
        }

        $memberFilters = [
            'role' => $roleFilter,
            'status' => $statusFilter,
        ];

        $result = User::paginate($currentUser, $teamFilterId, $memberFilters, $page, $perPage);

        $members = $result['items'];
        $membersPagination = $result['pagination'];
        $memberStats = $result['stats'];

        require __DIR__ . '/../../pages/superAdmin/member.php';
    }

    public static function store(): void
    {
        self::authorize();

        $id = $_POST['hide_id'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $role = (int)($_POST['role'] ?? 0);
        $course = $_POST['type'] ?? null;
        $isActive = isset($_POST['status']) ? (int)$_POST['status'] : User::ACTIVE;

        if ($name === '') {
            ResponseService::json(false, 'Name is required', [], 422);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ResponseService::json(false, 'Invalid email', [], 422);
        }
        if ($role <= 0) {
            ResponseService::json(false, 'Role is required', [], 422);
        }

        if ($id === '' && $password === '') {
            ResponseService::json(false, 'Password is required', [], 422);
        }

        if ($role === User::ROLE_MEMBER) {
            if (trim((string)$course) === '') {
                ResponseService::json(false, 'Course is required for Member', [], 422);
            }
        } else {
            $course = null;
        }

        $request = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'course' => $course,
            'is_active' => $isActive,
        ];

        if ($id === '') {
            $newId = User::create($request);
            if ($newId) {
                ResponseService::json(true, 'Create user successful', ['id' => $newId]);
            }
            ResponseService::json(false, 'Create failed', [], 500);
        }

        if ($password === '') {
            unset($request['password']);
        }

        $ok = User::update((int)$id, $request);
        if ($ok) {
            ResponseService::json(true, 'Update user successful', []);
        }
        ResponseService::json(false, 'Update failed', [], 500);
    }

    public static function destroy(): void
    {
        self::authorize();

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $id = (int)($_POST['delete_id'] ?? 0);
        if ($id <= 0) {
            ResponseService::json(false, 'ID is required', [], 422);
        }

        $ok = User::delete($id);
        if ($ok) {
            ResponseService::json(true, 'Delete user successful', []);
        }
        ResponseService::json(false, 'Delete failed', [], 500);
    }
}
