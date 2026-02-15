<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Models/User.php';

class ProfileController
{
    private const ALLOWED_COURSES = ['Frontend', 'Backend', 'Full-Stack'];

    private static function authorizeAny(): array
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            redirect('login');
        }

        return $user;
    }

    public static function show(): void
    {
        $sessionUser = self::authorizeAny();
        $token = csrf_token();

        $profileUser = User::findById((int)$sessionUser['id']) ?? $sessionUser;
        $profileUser['roles'] = $sessionUser['roles'] ?? [];

        require __DIR__ . '/../../pages/profile.php';
    }

    public static function update(): void
    {
        $sessionUser = self::authorizeAny();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        if (!verify_csrf((string)($_POST['csrf'] ?? ''))) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $course = trim((string)($_POST['course'] ?? ''));

        if ($name === '') {
            ResponseService::json(false, 'Full name is required', [], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ResponseService::json(false, 'Valid email is required', [], 422);
        }
        if (!preg_match('/@etec\.com$/i', $email)) {
            ResponseService::json(false, 'Email must be @etec.com', [], 422);
        }

        if (!in_array($course, self::ALLOWED_COURSES, true)) {
            ResponseService::json(false, 'Course must be Frontend, Backend, or Full-Stack', [], 422);
        }

        global $conn;
        $userId = (int)$sessionUser['id'];

        $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1");
        if (!$check) {
            ResponseService::json(false, 'Database error', [], 500);
        }
        $check->bind_param('si', $email, $userId);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        if ($exists) {
            ResponseService::json(false, 'Email already in use', [], 422);
        }

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, course = ? WHERE id = ? LIMIT 1");
        if (!$stmt) {
            ResponseService::json(false, 'Database error', [], 500);
        }
        $stmt->bind_param('sssi', $name, $email, $course, $userId);

        if (!$stmt->execute()) {
            ResponseService::json(false, 'Profile update failed', [], 500);
        }

        $_SESSION['user']['name'] = $name;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['course'] = $course;

        ResponseService::json(true, 'Profile updated successfully', [
            'name' => $name,
            'email' => $email,
            'course' => $course
        ]);
    }
}
