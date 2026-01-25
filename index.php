<?php
declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/logs/php-error.log');

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), microphone=(), camera=()");

ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
if (!empty($_SERVER['HTTPS'])) {
    ini_set('session.cookie_secure', '1');
}

session_start();

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/db.php';

// Get requested URL
$url = $_GET['url'] ?? '';
$url = trim((string)$url, '/');

// Load public/auth routes
$routes = require __DIR__ . '/routes/auth.php';

// Current user
$user = $_SESSION['user'] ?? null;

// Merge role-based routes if logged in (WITH INHERITANCE)
if ($user) {
    // DO NOT change route file names (as you asked)
    $roleRouteFiles = [
        'super_admin' => __DIR__ . '/routes/superAdmin.php',
        'admin'       => __DIR__ . '/routes/admin.php',
        'instructor'  => __DIR__ . '/routes/instructor.php',
        'member'      => __DIR__ . '/routes/member.php',
    ];

    // get roles as array
    $userRoles = is_array($user['roles']) ? $user['roles'] : explode(',', (string)$user['roles']);
    $userRoles = array_map('trim', $userRoles);

    // find highest role
    $highest = highestRole($userRoles);

    // merge only allowed route groups (inheritance)
    if ($highest) {
        $allowedRoles = accessRoles($highest); // returns list of route groups to merge
        foreach ($allowedRoles as $r) {
            if (isset($roleRouteFiles[$r]) && file_exists($roleRouteFiles[$r])) {
                $routes = array_merge($routes, require $roleRouteFiles[$r]);
            }
        }
    }
}

// Redirect "/" to default dashboard for logged-in users
if ($url === '' && $user) {
    $dashboard = getDefaultDashboard($user);
    header("Location: $dashboard");
    exit;
}

// Find route file
$file = $routes[$url] ?? null;

// Route exists and file exists
if ($file && file_exists(__DIR__ . '/' . $file)) {
    require __DIR__ . '/' . $file;
    exit;
}

// Logged-in but no route in allowed list => 403
if ($user) {
    http_response_code(403);
    if (file_exists(__DIR__ . '/errors/403.php')) {
        require __DIR__ . '/errors/403.php';
    } else {
        echo "<h1>403 - Access Denied</h1><p>You do not have permission to access this page.</p>";
    }
    exit;
}

// Public route not found -> 404
http_response_code(404);
if (file_exists(__DIR__ . '/errors/404.php')) {
    require __DIR__ . '/errors/404.php';
} else {
    echo "<h1>404 - Page Not Found</h1><p>The page you requested does not exist.</p>";
}
