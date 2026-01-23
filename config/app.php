<?php

declare(strict_types=1);

defined('APP_NAME') || define('APP_NAME', 'Task Management');

// ✅ auto BASE_URL
$path = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\') . '/';
defined('BASE_URL') || define('BASE_URL', $path === '//' ? '/' : $path);
