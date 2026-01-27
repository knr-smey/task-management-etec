<?php
declare(strict_types=1);

// boot
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// controller
require_once __DIR__ . '/../app/Controllers/ProjectsController.php';

// route
$route  = trim((string)($_GET['url'] ?? ''), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// list
if ($route === 'projects' && $method === 'GET') {
    ProjectsController::index();
    exit;
}

// create
if ($route === 'create-project' && $method === 'POST') {
    ProjectsController::store();
    exit;
}

// show
if ($route === 'project' && $method === 'GET') {
    $id = (int)($_GET['id'] ?? 0);
    ProjectsController::show($id);
    exit;
}

// update
if ($route === 'update-project' && $method === 'POST') {
    ProjectsController::update();
    exit;
}

// delete
if ($route === 'delete-project' && $method === 'POST') {
    ProjectsController::destroy();
    exit;
}

ResponseService::json(false, 'Not found', [], 404);
