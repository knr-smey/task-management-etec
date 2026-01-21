<?php
declare(strict_types=1);

require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../includes/auth.php';
csrf_check();

$id = isset($_POST['id']) ? (int)$_POST['id'] : -1;
$tasks = $_SESSION['tasks'] ?? [];
if (isset($tasks[$id])) {
    unset($tasks[$id]);
    $_SESSION['tasks'] = array_values($tasks);
}

redirect('tasks');
