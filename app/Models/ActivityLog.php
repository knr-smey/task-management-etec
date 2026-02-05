<?php

declare(strict_types=1);

class ActivityLog
{
    public static function record(
        int $actorUserId,
        string $entityType,
        int $entityId,
        string $action,
        ?array $oldValue = null,
        ?array $newValue = null
    ): bool {
        global $conn;

        $stmt = $conn->prepare(
            "INSERT INTO activity_logs (actor_user_id, entity_type, entity_id, action, old_value, new_value, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );

        if (!$stmt) {
            return false;
        }

        $oldJson = $oldValue ? json_encode($oldValue) : null;
        $newJson = $newValue ? json_encode($newValue) : null;

        $stmt->bind_param('isisss', $actorUserId, $entityType, $entityId, $action, $oldJson, $newJson);
        return $stmt->execute();
    }

    public static function listByProject(int $projectId, int $limit = 200): array
    {
        global $conn;

        $stmt = $conn->prepare(
            "SELECT
                al.*,
                u.name AS actor_name,
                u.email AS actor_email,
                t.title AS task_title,
                t.project_id AS task_project_id,
                p.name AS project_name
            FROM activity_logs al
            JOIN users u ON u.id = al.actor_user_id
            LEFT JOIN tasks t ON al.entity_type = 'task' AND al.entity_id = t.id
            LEFT JOIN projects p
                ON (al.entity_type = 'project' AND al.entity_id = p.id)
                OR (al.entity_type = 'task' AND t.project_id = p.id)
            WHERE (al.entity_type = 'project' AND al.entity_id = ?)
               OR (al.entity_type = 'task' AND t.project_id = ?)
            ORDER BY al.created_at DESC
            LIMIT ?"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('iii', $projectId, $projectId, $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }
}
