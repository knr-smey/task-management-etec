<?php
declare(strict_types=1);

function require_role(array $roles): void {
    $role = $_SESSION['user']['role'] ?? null;
    if (!$role || !in_array($role, $roles, true)) {
        http_response_code(403);
        exit('403 Forbidden');
    }
}
