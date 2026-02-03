<?php

declare(strict_types=1);

// boot
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// controller
require_once __DIR__ . '/../app/Controllers/ProjectsController.php';

$route  = trim((string)($_GET['url'] ?? ''), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// PAGE: list projects
if ($route === 'projects' && $method === 'GET') {
    ProjectsController::index();
    exit;
}

// PAGE: show project detail
if ($route === 'project-detail' && $method === 'GET') {
    $id = (int)($_GET['id'] ?? 0);
    // echo $id;
    ProjectsController::show($id);
    exit;
}

// API
if (($route === 'create-project' || $route === 'update-project') && $method === 'POST') {
    ProjectsController::store();
    exit;
}

if ($route === 'delete-project' && $method === 'POST') {
    ProjectsController::destroy();
    exit;
}

if ($route === 'assign-project-members' && $method === 'POST') {
    ProjectsController::assignMembers();
    exit;
}

if ($route === 'assign-project-team' && $method === 'POST') {
    ProjectsController::assignTeam();
    exit;
}

if ($route === 'unassign-project-team' && $method === 'POST') {
    ProjectsController::unassignTeam();
    exit;
}

ResponseService::json(false, 'Not found', [], 404);
