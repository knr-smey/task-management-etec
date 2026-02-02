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
$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');

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

defined('BASE_URL') || define('BASE_URL', $base);