<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';

class TaskController
{
    /**
     * Only super_admin / admin / instructor
     */
    private static function authorize(): array
    {
        $user = $_SESSION['user'] ?? [];

        if (
            !userHasRole($user, 'super_admin') &&
            !userHasRole($user, 'admin') &&
            !userHasRole($user, 'instructor')
        ) {
            // Page access denied
            redirect('dashboard');
        }

        return $user;
    }

    /**
     * PAGE: Task page
     * URL: /task
     */
    public static function index(): void
    {
        $user = self::authorize();

        // CSRF for future forms
        $token = csrf_token();

        // Just render page (no model yet)
        require __DIR__ . '/../../pages/tasks/index.php';
    }
}
