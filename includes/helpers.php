<?php

declare(strict_types=1);

/**
 * helpers.php
 * - Always include config/app.php BEFORE this file (BASE_URL must exist)
 * - Use require_once to avoid redeclare
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escape output */
if (!function_exists('e')) {
    function e(?string $str): string
    {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

/** Redirect */
if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
        exit;
    }
}

/** JSON response */
if (!function_exists('json_response')) {
    function json_response(bool $ok, string $message = '', array $data = [], int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status'  => $ok,
            'message' => $message,
            'data'    => $data
        ]);
        exit;
    }
}

/** CSRF token generator */
if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return (string) $_SESSION['csrf'];
    }
}

/** CSRF hard check (exit on fail) */
if (!function_exists('csrf_check')) {
    function csrf_check(): void
    {
        $sess = $_SESSION['csrf'] ?? '';
        $post = $_POST['csrf'] ?? '';

        if (!$sess || !$post || !hash_equals((string)$sess, (string)$post)) {
            http_response_code(403);
            exit('CSRF blocked');
        }
    }
}

/** CSRF verify (return bool) */
if (!function_exists('verify_csrf')) {
    function verify_csrf(string $token): bool
    {
        $sess = $_SESSION['csrf'] ?? '';
        if (!$sess || !$token) return false;
        return hash_equals((string)$sess, (string)$token);
    }
}

/** Role helpers */
if (!function_exists('userHasRole')) {
    function userHasRole(array $user, string $role): bool
    {
        if (!isset($user['roles'])) return false;

        $roles = $user['roles'];

        // from SQL: "admin,member"
        if (is_string($roles)) {
            $roles = array_map('trim', explode(',', $roles));
        }

        return in_array($role, $roles, true);
    }
}


/**
 * Determine highest role from roles array
 * Priority: super_admin > admin > instructor > member
 */
if (!function_exists('highestRole')) {
    function highestRole(array $roles): ?string
    {
        $priority = ['super_admin', 'admin', 'instructor', 'member'];
        foreach ($priority as $r) {
            if (in_array($r, $roles, true)) return $r;
        }
        return null;
    }
}

if (!function_exists('accessRoles')) {
    function accessRoles(string $highestRole): array
    {
        $map = [
            'super_admin' => ['super_admin', 'admin', 'instructor', 'member'],
            'admin'       => ['admin', 'instructor', 'member'],
            'instructor'  => ['instructor', 'member'],
            'member'      => ['member'],
        ];
        return $map[$highestRole] ?? [];
    }
}

/** Merge routes based on roles (optional) */
if (!function_exists('mergeRoleRoutes')) {
    function mergeRoleRoutes(array $userRoles, array $roleRouteFiles): array
    {
        $routes = [];
        foreach ($userRoles as $role) {
            $role = trim((string)$role);
            if (isset($roleRouteFiles[$role]) && file_exists($roleRouteFiles[$role])) {
                $routes = array_merge($routes, require $roleRouteFiles[$role]);
            }
        }
        return $routes;
    }
}

/** Default dashboard path */
if (!function_exists('getDefaultDashboard')) {
    function getDefaultDashboard(array $user): string
    {
        if (userHasRole($user, 'super_admin')) return BASE_URL . 'dashboard';
        if (userHasRole($user, 'admin'))       return BASE_URL . 'dashboard';
        if (userHasRole($user, 'instructor'))  return BASE_URL . 'dashboard';
        if (userHasRole($user, 'member'))      return BASE_URL . 'dashboard';

        return BASE_URL . 'login';
    }
}

if (!function_exists('currentPath')) {
    function currentPath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // remove project base folder (e.g. /task-management-etec)
        $base = parse_url(BASE_URL, PHP_URL_PATH);

        if ($base && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }

        return '/' . trim($path, '/');
    }
}

if (!function_exists('isActive')) {
    function isActive(string $path): bool
    {
        return currentPath() === '/' . trim($path, '/');
    }
}

if (!function_exists('layout')) {
    function layout(string $name): string
    {
        return __DIR__ . "/../layouts/{$name}.php";
    }
}

if (!function_exists('full_url')) {
    function full_url(string $path): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            ? 'https'
            : 'http';

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        return $scheme . '://' . $host . $path;
    }
}

/** Normalize roles to array */
if (!function_exists('getUserRoles')) {
    function getUserRoles(array $user): array
    {
        $roles = $user['roles'] ?? [];

        if (is_string($roles)) {
            $roles = array_filter(array_map('trim', explode(',', $roles)));
        }

        if (!is_array($roles)) return [];

        // remove empty + duplicates
        $roles = array_values(array_unique(array_filter($roles)));
        return $roles;
    }
}

/** True if user has ONLY member role */
if (!function_exists('isMemberOnly')) {
    function isMemberOnly(array $user): bool
    {
        $roles = getUserRoles($user);
        return count($roles) === 1 && $roles[0] === 'member';
    }
}
