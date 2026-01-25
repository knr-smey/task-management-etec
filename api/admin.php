<?php

declare(strict_types=1);

// boot files
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// controller
require_once __DIR__ . '/../app/Controllers/AdminController.php';

// detect route from router (?url=...)
$route = trim((string)($_GET['url'] ?? ''), '/');

if ($route === 'create-project') {
    AdminController::createProject();
    exit;
}


// fallback
ResponseService::json(false, 'Not found', [], 404);
