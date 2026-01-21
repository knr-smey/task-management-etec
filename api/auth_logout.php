<?php
declare(strict_types=1);

require __DIR__ . '/../includes/helpers.php';
csrf_check();

session_destroy();
redirect('login');
