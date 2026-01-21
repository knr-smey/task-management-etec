<?php
declare(strict_types=1);

require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../config/app.php';
csrf_check();

$email = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');

if ($email === 'admin@example.com' && $password === 'admin123') {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => 1,
        'name' => 'Admin',
        'email' => $email,
        'role' => 'admin',
    ];
    redirect('dashboard');
}

redirect('login');
