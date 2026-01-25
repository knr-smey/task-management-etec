<?php
declare(strict_types=1);

// session_start(); 
session_destroy(); 


$base = defined('BASE_URL') ? rtrim(BASE_URL, '/') : '';
header('Location: ' . $base . '/login');
exit;