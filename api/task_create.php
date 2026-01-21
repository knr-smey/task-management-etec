<?php
declare(strict_types=1);

require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../config/app.php';
require __DIR__ . '/../includes/auth.php';
csrf_check();

$title = trim((string)($_POST['title'] ?? ''));
$status = (string)($_POST['status'] ?? 'todo');
$assignee = trim((string)($_POST['assignee'] ?? ''));

if ($title === '') {
    redirect('tasks/create');
}

$tasks = $_SESSION['tasks'] ?? [];
$tasks[] = ['title' => $title, 'status' => $status, 'assignee' => $assignee];
$_SESSION['tasks'] = $tasks;

redirect('tasks');
