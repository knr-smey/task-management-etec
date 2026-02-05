<?php

declare(strict_types=1);

// boot
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

// controller
require_once __DIR__ . '/../app/Controllers/TaskController.php';

$route = trim((string)($_GET['url'] ?? ''), '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// allow /api/task/* or /task/*
$route = preg_replace('#^api/task/?#', '', $route);
$route = preg_replace('#^task/?#', '', $route);

// API: list by project (GET)
if ($route === 'list' && $method === 'GET') {
	TaskController::listByProject();
	exit;
}

// API: create
if ($route === 'create' && $method === 'POST') {
	TaskController::create();
	exit;
}

// API: update
if ($route === 'update' && $method === 'POST') {
	TaskController::update();
	exit;
}

// API: delete
if ($route === 'delete' && $method === 'POST') {
	TaskController::destroy();
	exit;
}

// API: assign
if ($route === 'assign' && $method === 'POST') {
	TaskController::assign();
	exit;
}

// API: task status
if ($route === 'status' && $method === 'POST') {
	TaskController::updateTaskStatus();
	exit;
}

// API: log time
if ($route === 'log-time' && $method === 'POST') {
	TaskController::logTime();
	exit;
}

ResponseService::json(false, 'Not found', [], 404);
