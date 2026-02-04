<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/app.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../../includes/auth.php';

class DashboardController
{
    public static function index(): void
    {
        require_login();

        $user = $_SESSION['user'] ?? [];

        $canSeeProjects = (
            userHasRole($user, 'super_admin') ||
            userHasRole($user, 'admin') ||
            userHasRole($user, 'instructor')
        );

        $projects = [];
        if ($canSeeProjects) {
            require_once __DIR__ . '/../Models/Project.php';
            $projects = Project::allByCreator((int)$user['id']);
        }

        $activeMembers = 0;
        $totalTasks = 0;
        $doneTasks = 0;
        $projectGrowthPercent = 0;

        $fetchCount = function (string $sql, string $types = '', array $params = []): int {
            global $conn;

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                return 0;
            }

            if ($types !== '' && $params) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            return (int)($row['total'] ?? 0);
        };

        if (userHasRole($user, 'super_admin')) {
            $activeMembers = $fetchCount("
                SELECT COUNT(DISTINCT u.id) AS total
                FROM users u
                INNER JOIN user_roles ur ON ur.user_id = u.id
                INNER JOIN roles r ON r.id = ur.role_id
                WHERE r.name = 'member' AND u.is_active = 1
            ");

            $totalTasks = $fetchCount("SELECT COUNT(*) AS total FROM tasks");
            $doneTasks = $fetchCount("
                SELECT COUNT(*) AS total
                FROM tasks t
                INNER JOIN task_statuses s ON s.id = t.status_id
                WHERE s.name = 'done'
            ");
        } elseif (userHasRole($user, 'admin') || userHasRole($user, 'instructor')) {
            $activeMembers = $fetchCount("
                SELECT COUNT(DISTINCT tm.member_id) AS total
                FROM teams t
                INNER JOIN team_members tm ON tm.team_id = t.id
                INNER JOIN users u ON u.id = tm.member_id
                WHERE t.created_by = ? AND u.is_active = 1
            ", 'i', [(int)$user['id']]);

            $totalTasks = $fetchCount("
                SELECT COUNT(*) AS total
                FROM tasks t
                INNER JOIN projects p ON p.id = t.project_id
                WHERE p.created_by = ?
            ", 'i', [(int)$user['id']]);

            $doneTasks = $fetchCount("
                SELECT COUNT(*) AS total
                FROM tasks t
                INNER JOIN projects p ON p.id = t.project_id
                INNER JOIN task_statuses s ON s.id = t.status_id
                WHERE p.created_by = ? AND s.name = 'done'
            ", 'i', [(int)$user['id']]);
        }

        if ($canSeeProjects) {
            $currentProjects = $fetchCount(
                "SELECT COUNT(*) AS total FROM projects WHERE created_by = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)",
                'i',
                [(int)$user['id']]
            );

            $previousProjects = $fetchCount(
                "SELECT COUNT(*) AS total FROM projects WHERE created_by = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)",
                'i',
                [(int)$user['id']]
            );

            if ($previousProjects > 0) {
                $projectGrowthPercent = (int)round((($currentProjects - $previousProjects) / $previousProjects) * 100);
            } elseif ($currentProjects > 0) {
                $projectGrowthPercent = 100;
            } else {
                $projectGrowthPercent = 0;
            }
        }

        $completionRate = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100) : 0;
        $performanceScore = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100, 1) : 0.0;

        require __DIR__ . '/../../pages/dashboard/index.php';
    }
}
