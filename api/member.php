<?php

declare(strict_types=1);

// boot
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// controller
require_once __DIR__ . '/../app/Controllers/MemberController.php';

// route
$route  = trim((string)($_GET['url'] ?? ''), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// page (optional)
if ($route === 'member' && $method === 'GET') {
    MemberController::index();
    exit;
}

// create/update
if ($route === 'create-member' && $method === 'POST') {
    MemberController::store();
    exit;
}

// delete
if ($route === 'delete-member' && $method === 'POST') {
    MemberController::destroy();
    exit;
}

ResponseService::json(false, 'Not found', [], 404);
