<?php

declare(strict_types=1);

if (!function_exists('e')) {
    function e(?string $str): string
    {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        $base = defined('BASE_URL') ? BASE_URL : '/';
        header('Location: ' . rtrim($base, '/') . '/' . ltrim($path, '/'));
        exit;
    }
}

if (!function_exists('json_response')) {
    function json_response(bool $ok, string $message = '', array $data = []): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $ok, 'message' => $message, 'data' => $data]);
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return (string)$_SESSION['csrf'];
    }
}

if (!function_exists('csrf_check')) {
    function csrf_check(): void
    {
        $sess = $_SESSION['csrf'] ?? '';
        $post = $_POST['csrf'] ?? '';
        if (!$sess || !$post || !hash_equals((string)$sess, (string)$post)) {
            http_response_code(403);
            exit('CSRF blocked');
        }
    }
}
