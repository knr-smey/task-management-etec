<?php
declare(strict_types=1);

/**
 * Normal PHP Task Management Template (No framework)
 * Router + security headers + session hardening
 */

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
require __DIR__ . '/config/app.php';

$url = $_GET['url'] ?? '';
$url = trim((string)$url, '/');

/**
 * Routes (add your own)
 * Map URL -> PHP file inside pages/
 */
$routes = [
    '' => 'pages/auth/login.php',
    'login' => 'pages/auth/login.php',
    'logout' => 'pages/auth/logout.php',

    'dashboard' => 'pages/dashboard/index.php',
    'tasks' => 'pages/tasks/list.php',
    'tasks/create' => 'pages/tasks/create.php',
    'tasks/edit' => 'pages/tasks/edit.php',

    // API endpoints (POST recommended)
    'api/login' => 'api/auth_login.php',
    'api/logout' => 'api/auth_logout.php',
    'api/task/create' => 'api/task_create.php',
    'api/task/update' => 'api/task_update.php',
    'api/task/delete' => 'api/task_delete.php',
];

$file = $routes[$url] ?? null;

if ($file && file_exists(__DIR__ . '/' . $file)) {
    require __DIR__ . '/' . $file;
    exit;
}

http_response_code(404);
require __DIR__ . '/errors/404.php';
