<?php

declare(strict_types=1);

// boot files
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';

require_once __DIR__ . '/../app/Services/ResponseService.php';
require_once __DIR__ . '/../app/Controllers/TeamController.php';

$route = trim((string)($_GET['url'] ?? ''), '/');

if ($route === 'team') {
    TeamController::index();
    exit;
}
if ($route === 'create-team') {
    TeamController::store();
    exit;
}
if ($route === 'update-team') {
    TeamController::update();
    exit;
}
if ($route === 'delete-team') {
    TeamController::destroy();
    exit;
}
if ($route === 'team-cards') {
    TeamController::cards();
    exit;
}
if ($route === 'create-invite') {
    TeamController::createInvite();
    exit;
}

if ($route === 'team/join') {
    TeamController::joinPage();
    exit;
}
if ($route === 'team/join-confirm') {
    TeamController::joinConfirm();
    exit;
}
if ($route === 'team/detail') {
    TeamController::detail();
    exit;
}

ResponseService::json(false, 'Not found', [], 404);
