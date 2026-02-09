<?php

// Old
// declare(strict_types=1);

// defined('APP_NAME') || define('APP_NAME', 'Task Management');

// // auto BASE_URL
// $path = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\') . '/';
// defined('BASE_URL') || define('BASE_URL', $path === '//' ? '/' : $path);


// New 
declare(strict_types=1);

// App name
defined('APP_NAME') || define('APP_NAME', 'Task Management');

/**
 * BASE_URL (ALWAYS project root)
 * - Works on local & deploy
 * - Fixes /api/api bug when request is inside /api/*.php
 * - Keeps the name BASE_URL (so you don't need to change 91 files)
 *
 * Examples:
 *  /task-management-etec/team                => BASE_URL = /task-management-etec/
 *  /task-management-etec/api/team.php        => BASE_URL = /task-management-etec/
 *  /api/team.php (project at domain root)    => BASE_URL = /
 */
$baseOverride = $_ENV['APP_BASE_URL'] ?? $_SERVER['APP_BASE_URL'] ?? '';
$host = (string)($_SERVER['HTTP_HOST'] ?? '');
if ($baseOverride === '' && $host !== '' && str_ends_with($host, 'byethost7.com')) {
    $baseOverride = '/task-management-etec/';
}
if (is_string($baseOverride) && trim($baseOverride) !== '') {
    $base = '/' . trim((string)$baseOverride, '/') . '/';
} else {
    $script = str_replace('\\', '/', (string)($_SERVER['PHP_SELF'] ?? $_SERVER['SCRIPT_NAME'] ?? '/'));
    if (!preg_match('#\.php$#', $script)) {
        $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    }
    if (!preg_match('#\.php$#', $script)) {
        $script = '/index.php';
    }

    // If called from /api/*.php, remove that part to keep project root
    $base = preg_replace('#/api/[^/]*\.php$#', '/', $script);

    // If called from /index.php, normalize to folder root
    $base = preg_replace('#/index\.php$#', '/', $base);

    // Normalize slashes and ensure trailing slash
    $base = rtrim($base, '/') . '/';

    // Special case: root "/" should stay "/"
    if ($base === '//') {
        $base = '/';
    }
}

defined('BASE_URL') || define('BASE_URL', $base);