<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Services/ResponseService.php';

class AuthController
{
    public static function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        // CSRF
        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            ResponseService::json(false, 'Email and password are required', [], 422);
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
        }

        if ((int)$user['is_active'] !== 1) {
            ResponseService::json(false, 'Account is disabled', [], 403);
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

        // Session
        $_SESSION['user'] = [
            'id'    => (int)$user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'roles' => $roles
        ];

        ResponseService::json(true, 'Login successful', [
            'redirect' => 'dashboard'
        ]);
    }

    public static function logout(): void
    {
        session_destroy();
        ResponseService::json(true, 'Logged out');
    }
}
