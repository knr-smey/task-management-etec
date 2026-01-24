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
require_once __DIR__ . '/config/db.php';

$url = $_GET['url'] ?? '';
$url = trim((string)$url, '/');

/**
 * Routes (add your own)
 * Map URL -> PHP file inside pages/
 */
$routes = [
    

    'dashboard' => 'pages/dashboard/index.php',
    'tasks' => 'pages/tasks/list.php',
    'tasks/create' => 'pages/tasks/create.php',
    'tasks/edit' => 'pages/tasks/edit.php',

    // API endpoints (POST recommended)
    'api/auth/login' => 'api/auth.php',
    'api/auth/register' => 'api/auth.php',
    'api/auth/logout' => 'api/auth.php',
];
$authRoutes = require __DIR__ . '/routes/auth.php';
$superAdminRoutes=require __DIR__ . '/routes/superAdmin.php';
$routes = array_merge($routes, $authRoutes,$superAdminRoutes);
$file = $routes[$url] ?? null;
if ($file && file_exists(__DIR__ . '/' . $file)) {
    require __DIR__ . '/' . $file;
    exit;
}

http_response_code(404);
require __DIR__ . '/errors/404.php';
