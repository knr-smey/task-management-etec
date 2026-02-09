<?php

declare(strict_types=1);

// boot
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// controller
require_once __DIR__ . '/../app/Controllers/AttendanceController.php';

$route  = trim((string)($_GET['url'] ?? ''), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($route === 'team/attendance' && $method === 'GET') {
    AttendanceController::page();
    exit;
}

if ($route === 'attendance/save' && $method === 'POST') {
    AttendanceController::save();
    exit;
}

ResponseService::json(false, 'Not found', [], 404);
