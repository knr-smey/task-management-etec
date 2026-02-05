<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Services/ResponseService.php';

class AuthController
{
    public static function login(): void
    {
        // ✅ make sure session exists
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
            return;
        }

        // CSRF
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
            return;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            ResponseService::json(false, 'Email and password are required', [], 422);
            return;
        }

        global $conn;

        // Fetch user
        $stmt = $conn->prepare("
        SELECT id, name, email, password_hash, is_active
        FROM users
        WHERE email = ?
        LIMIT 1
    ");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            ResponseService::json(false, 'Invalid email or password', [], 401);
            return;
        }

        if ((int)$user['is_active'] !== 1) {
            ResponseService::json(false, 'Account is disabled', [], 403);
            return;
        }

        // Load roles
        $stmt = $conn->prepare("
        SELECT r.name
        FROM roles r
        JOIN user_roles ur ON ur.role_id = r.id
        WHERE ur.user_id = ?
    ");
        $stmt->bind_param('i', $user['id']);
        $stmt->execute();

        $roles = [];
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $roles[] = $row['name'];
        }

        // Session user
        $_SESSION['user'] = [
            'id'    => (int)$user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'roles' => $roles
        ];

        // ✅ IMPORTANT: if user came from invite link, redirect back to join
        if (!empty($_SESSION['join_invite_token'])) {
            $token = $_SESSION['join_invite_token'];
            unset($_SESSION['join_invite_token']);

            ResponseService::json(true, 'Login successful', [
                'redirect' => 'team/join?token=' . urlencode($token)
            ]);
            return;
        }

        // default redirect
        ResponseService::json(true, 'Login successful', [
            'redirect' => 'dashboard'
        ]);
    }

    public static function register(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }
        $name     = htmlspecialchars(trim($_POST['name'] ?? ''));
        $emailRaw = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $course   = htmlspecialchars(trim($_POST['course'] ?? ''));
        if ($name === '') {
            ResponseService::json(false, 'Name is required', [], 422);
        }
        if (!filter_var($emailRaw, FILTER_VALIDATE_EMAIL)) {
            ResponseService::json(false, 'Invalid email format', [], 422);
        }
        $email = $emailRaw;
        if (!preg_match('/@etec\.com$/i', $email)) {
            ResponseService::json(false, 'Email must be @etec.com', [], 422);
        }

        if ($password === '') {
            ResponseService::json(false, 'Password is required', [], 422);
        }
        if (strlen($password) < 6 || preg_match('/[A-Z]/', $password) || preg_match('/[^a-z0-9]/', $password)) {
            ResponseService::json(false, 'Password must be weak: lowercase letters or numbers only (min 6)', [], 422);
        }
        if ($course === '') {
            ResponseService::json(false, 'Course is required', [], 422);
        }
        global $conn;
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        if (!$stmt) {
            ResponseService::json(false, 'Database error: ' . $conn->error, [], 500);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            ResponseService::json(false, 'Email already exists', [], 422);
        }
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password_hash, course, is_active)
            VALUES (?, ?, ?, ?, 1)
        ");
        if (!$stmt) {
            ResponseService::json(false, 'Database error: ' . $conn->error, [], 500);
        }
        $stmt->bind_param('ssss', $name, $email, $passwordHash, $course);
        if ($stmt->execute()) {
            $userId = $conn->insert_id;
            $roleId = 4;
            $stmtRole = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            if ($stmtRole) {
                $stmtRole->bind_param('ii', $userId, $roleId);
                $stmtRole->execute();
            }
            ResponseService::json(true, 'Register successful', [
                'redirect' => 'login'
            ]);
        } else {
            ResponseService::json(false, 'Database error: ' . $stmt->error, [], 500);
        }
    }
    public static function logout(): void
    {
        session_destroy();
        ResponseService::json(true, 'Logged out');
    }

    public static function changePassword(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
            return;
        }

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
            return;
        }

        $user = $_SESSION['user'] ?? null;
        if (!$user || empty($user['id'])) {
            ResponseService::json(false, 'Unauthorized', [], 401);
            return;
        }

        $new = (string)($_POST['new_password'] ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');

        if ($new === '' || $confirm === '') {
            ResponseService::json(false, 'New password and confirmation are required', [], 422);
            return;
        }

        if ($new !== $confirm) {
            ResponseService::json(false, 'Password confirmation does not match', [], 422);
            return;
        }

        if (strlen($new) < 6) {
            ResponseService::json(false, 'New password must be at least 6 characters', [], 422);
            return;
        }

        global $conn;

        $userId = (int)$user['id'];

        $newHash = password_hash($new, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ? LIMIT 1");
        if (!$stmt) {
            ResponseService::json(false, 'Database error', [], 500);
            return;
        }
        $stmt->bind_param('si', $newHash, $userId);

        if ($stmt->execute()) {
            ResponseService::json(true, 'Password updated');
            return;
        }

        ResponseService::json(false, 'Update failed', [], 500);
    }
}
