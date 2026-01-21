<?php

declare(strict_types=1);

// TODO: Fill with your ByetHost MySQL info
$DB_HOST = 'localhost';
$DB_NAME = 'task_management_db';
$DB_USER = 'root';
$DB_PASS = '';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    http_response_code(500);
    die('Database connection failed.');
}
$conn->set_charset('utf8mb4');
