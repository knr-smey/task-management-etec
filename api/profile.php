<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../app/Controllers/ProfileController.php';

$route = trim((string)($_GET['url'] ?? ''), '/');

if ($route === 'api/profile/update') {
    ProfileController::update();
    exit;
}

ProfileController::show();
