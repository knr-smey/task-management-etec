<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Models/User.php';

class ProfileController
{
    private static function authorizeAny(): array
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            redirect('login');
        }

        return $user;
    }

    public static function show(): void
    {
        $sessionUser = self::authorizeAny();
        $token = csrf_token();

        $profileUser = User::findById((int)$sessionUser['id']) ?? $sessionUser;
        $profileUser['roles'] = $sessionUser['roles'] ?? [];

        require __DIR__ . '/../../pages/profile.php';
    }
}
