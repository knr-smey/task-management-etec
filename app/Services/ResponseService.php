<?php

declare(strict_types=1);

class ResponseService
{
    public static function json(
        bool $status,
        string $message = '',
        array $data = [],
        int $code = 200
    ): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ]);

        exit;
    }
}
