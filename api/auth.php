<?php

declare(strict_types=1);

// boot files
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// controller
require_once __DIR__ . '/../app/Controllers/AuthController.php';

// detect route from router (?url=...)
$route = trim((string)($_GET['url'] ?? ''), '/');

if ($route === 'api/auth/login') {
    AuthController::login();
    exit;
}
if ($route === 'api/auth/register') {
    AuthController::register();
    exit;
}

if ($route === 'api/auth/logout') {
    AuthController::logout(); // make sure method name matches your controller
    exit;
}

// fallback
ResponseService::json(false, 'Not found', [], 404);
