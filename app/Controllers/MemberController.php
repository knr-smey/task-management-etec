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

        // ✅ Use your existing role helper (same as User::all rules)
        if (
            !userHasRole($currentUser, 'super_admin') &&
            !userHasRole($currentUser, 'admin') &&
            !userHasRole($currentUser, 'instructor')
        ) {
            ResponseService::json(false, 'Forbidden', [], 403);
        }

        return $currentUser;
    }

    // ✅ PAGE: show member page (same as old)
    public static function index(): void
    {
        $currentUser = self::authorize();
        $members = User::all($currentUser);

        require __DIR__ . '/../../pages/superAdmin/member.php';
    }

    // ✅ API: create/update (same as old create())
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

        $name  = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $role = (int)($_POST['role'] ?? 0);

        // ✅ course (type) only for member
        $course = $_POST['type'] ?? null;

        // ✅ is_active: 1/0
        $isActive = isset($_POST['status']) ? (int)$_POST['status'] : User::ACTIVE;

        if ($name === '') ResponseService::json(false, 'Name is required', [], 422);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) ResponseService::json(false, 'Invalid email', [], 422);
        if ($role <= 0) ResponseService::json(false, 'Role is required', [], 422);

        // password required ONLY on create
        if ($id === '' && $password === '') {
            ResponseService::json(false, 'Password is required', [], 422);
        }

        // ✅ course rules:
        if ($role === User::ROLE_MEMBER) {
            if (trim((string)$course) === '') {
                ResponseService::json(false, 'Course is required for Member', [], 422);
            }
        } else {
            $course = null;
        }

        $request = [
            'name'      => $name,
            'email'     => $email,
            'password'  => $password,  // raw (model hashes)
            'role'      => $role,
            'course'    => $course,
            'is_active' => $isActive
        ];

        if ($id === '') {
            $newId = User::create($request);
            if ($newId) ResponseService::json(true, 'Create user successful', ['id' => $newId]);
            ResponseService::json(false, 'Create failed', [], 500);
        } else {
            if ($password === '') unset($request['password']);

            $ok = User::update((int)$id, $request);
            if ($ok) ResponseService::json(true, 'Update user successful', []);
            ResponseService::json(false, 'Update failed', [], 500);
        }
    }

    // ✅ API: delete (same as old deleteMember())
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
        if ($ok) ResponseService::json(true, 'Delete user successful', []);
        ResponseService::json(false, 'Delete failed', [], 500);
    }
}
