<?php
declare(strict_types=1);

require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../includes/auth.php';
csrf_check();

$id = isset($_POST['id']) ? (int)$_POST['id'] : -1;
$title = trim((string)($_POST['title'] ?? ''));
$status = (string)($_POST['status'] ?? 'todo');
$assignee = trim((string)($_POST['assignee'] ?? ''));

$tasks = $_SESSION['tasks'] ?? [];
if (!isset($tasks[$id])) {
    redirect('tasks');
}

$tasks[$id] = ['title' => $title, 'status' => $status, 'assignee' => $assignee];
$_SESSION['tasks'] = $tasks;

redirect('tasks');
