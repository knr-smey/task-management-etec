<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_login(string $redirectTo = 'login'): void
{
    if (empty($_SESSION['user'])) {
        redirect($redirectTo);
    }
}
