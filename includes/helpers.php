<?php
declare(strict_types=1);

if (!function_exists('e')) {
    function e(?string $str): string {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void {
        $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
        header('Location: ' . $base . '/' . ltrim($path, '/'));
        exit;
    }
}

if (!function_exists('json_response')) {
    function json_response(bool $ok, string $message = '', array $data = []): void {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $ok, 'message' => $message, 'data' => $data]);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return (string)$_SESSION['csrf'];
    }
}

if (!function_exists('csrf_check')) {
    function csrf_check(): void {
        $sess = $_SESSION['csrf'] ?? '';
        $post = $_POST['csrf'] ?? '';
        if (!$sess || !$post || !hash_equals((string)$sess, (string)$post)) {
            http_response_code(403);
            exit('CSRF blocked');
        }
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf(string $token): bool {
        $sess = $_SESSION['csrf'] ?? '';
        if (!$sess || !$token) return false;
        return hash_equals((string)$sess, (string)$token);
    }
}

function userHasRole(array $user, string $role): bool {
    $roles = $user['roles'] ?? [];
    if (!is_array($roles)) $roles = explode(',', (string)$roles);
    $roles = array_map('trim', $roles);
    return in_array($role, $roles, true);
}

/**
 * Determine highest role from a roles array
 * Priority: super_admin > admin > instructor > member
 */
function highestRole(array $roles): ?string {
    $priority = ['super_admin', 'admin', 'instructor', 'member'];
    foreach ($priority as $r) {
        if (in_array($r, $roles, true)) return $r;
    }
    return null;
}

function accessRoles(string $highestRole): array {
    $map = [
        'super_admin' => ['super_admin', 'admin', 'instructor', 'member'],
        'admin'       => ['admin', 'instructor', 'member'],
        'instructor'  => ['instructor', 'member'],
        'member'      => ['member'],
    ];
    return $map[$highestRole] ?? [];
}

function mergeRoleRoutes(array $userRoles, array $roleRouteFiles): array {
    // (still available if you want)
    $routes = [];
    foreach ($userRoles as $role) {
        $role = trim((string)$role);
        if (isset($roleRouteFiles[$role]) && file_exists($roleRouteFiles[$role])) {
            $routes = array_merge($routes, require $roleRouteFiles[$role]);
        }
    }
    return $routes;
}

function getDefaultDashboard(array $user): string {
    $base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';

    // You can keep /dashboard for all if that's your project
    // But this version supports different dashboards if you have them
    if (userHasRole($user, 'super_admin')) return $base . 'dashboard';
    if (userHasRole($user, 'admin'))       return $base . 'dashboard';
    if (userHasRole($user, 'instructor'))  return $base . 'dashboard';
    if (userHasRole($user, 'member'))      return $base . 'dashboard';

    return $base . 'login';
}
