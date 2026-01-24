<?php

declare(strict_types=1);

// boot files
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// controller
require_once __DIR__ . '/../app/Controllers/SuperAdminController.php';

// detect route from router (?url=...)
$route = trim((string)($_GET['url'] ?? ''), '/');

if ($route === 'member') {
    SuperAdminController::getMember();
    exit;
}
if ($route === 'create-member') {
    SuperAdminController::create();
    exit;
}

// fallback
ResponseService::json(false, 'Not found', [], 404);
