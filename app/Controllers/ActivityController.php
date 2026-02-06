<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Models/Project.php';
require_once __DIR__ . '/../Models/ActivityLog.php';

class ActivityController
{
    private static function authorizeAny(): array
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            redirect('login');
        }

        return $user;
    }

    public static function index(): void
    {
        $user = self::authorizeAny();

        $projects = Project::allForUser((int)$user['id']);
        $selectedProjectId = (int)($_GET['project_id'] ?? 0);

        $selectedProject = null;
        $logs = [];

        if ($selectedProjectId > 0) {
            $selectedProject = Project::findWithTeam($selectedProjectId, (int)$user['id']);
            if ($selectedProject) {
                $logs = ActivityLog::listByProject($selectedProjectId);
            } else {
                $selectedProjectId = 0;
            }
        }

        require __DIR__ . '/../../pages/activity/index.php';
    }
}
