<?php

declare(strict_types=1);

class Task
{
    /**
     * Get all tasks by project
     */
    public static function allByProject(int $projectId): array
    {
        global $conn;

        $sql = "
            SELECT
                t.*,
                u.name AS creator_name,
                s.name AS status_name,
                au.name AS assignee_name,
                ta.user_id AS assignee_id
            FROM tasks t
            LEFT JOIN users u ON u.id = t.created_by
            LEFT JOIN task_statuses s ON s.id = t.status_id
            LEFT JOIN (
                SELECT task_id, MAX(assigned_at) AS max_assigned_at
                FROM task_assignees
                GROUP BY task_id
            ) latest ON latest.task_id = t.id
            LEFT JOIN task_assignees ta ON ta.task_id = t.id AND ta.assigned_at = latest.max_assigned_at
            LEFT JOIN users au ON au.id = ta.user_id
            WHERE t.project_id = ?
            ORDER BY t.created_at DESC
        ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param('i', $projectId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    /**
     * Find single task
     */
    public static function find(int $taskId): ?array
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT *
            FROM tasks
            WHERE id = ?
            LIMIT 1
        ");
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $taskId);
        $stmt->execute();

        $task = $stmt->get_result()->fetch_assoc();

        return $task ?: null;
    }

    /**
     * Create task
     */
    public static function create(array $data): int
    {
        global $conn;

        $sql = "
            INSERT INTO tasks (
                project_id,
                title,
                description,
                status_id,
                priority,
                estimate_hours,
                due_date,
                created_by,
                created_at
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, NOW()
            )
        ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return 0;
        }

        $projectId = (int)$data['project_id'];
        $title = (string)$data['title'];
        $description = $data['description'] ?? null;
        $statusId = (int)$data['status_id'];
        $priority = (string)($data['priority'] ?? 'medium');
        $estimateHours = $data['estimate_hours'] ?? null;
        $dueDate = $data['due_date'] ?? null;
        $createdBy = (int)$data['created_by'];

        $stmt->bind_param(
            'ississsi',
            $projectId,
            $title,
            $description,
            $statusId,
            $priority,
            $estimateHours,
            $dueDate,
            $createdBy
        );

        $ok = $stmt->execute();

        return $ok ? (int)$conn->insert_id : 0;
    }

    /**
     * Update task
     */
    public static function update(int $taskId, array $data): bool
    {
        global $conn;

        $sql = "
            UPDATE tasks SET
                title = ?,
                description = ?,
                status_id = ?,
                priority = ?,
                estimate_hours = ?,
                due_date = ?,
                updated_at = NOW()
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $title = (string)$data['title'];
        $description = $data['description'] ?? null;
        $statusId = (int)$data['status_id'];
        $priority = (string)($data['priority'] ?? 'medium');
        $estimateHours = $data['estimate_hours'] ?? null;
        $dueDate = $data['due_date'] ?? null;

        $stmt->bind_param(
            'ssisssi',
            $title,
            $description,
            $statusId,
            $priority,
            $estimateHours,
            $dueDate,
            $taskId
        );

        return $stmt->execute();
    }

    /**
     * Delete task
     */
    public static function delete(int $taskId): bool
    {
        global $conn;

        $stmt = $conn->prepare("
            DELETE FROM tasks
            WHERE id = ?
            LIMIT 1
        ");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $taskId);
        return $stmt->execute();
    }

    /**
     * Assign users to task
     */
    public static function assignUsers(int $taskId, array $userIds, int $assignedBy): bool
    {
        global $conn;

        if ($taskId <= 0 || empty($userIds)) {
            return false;
        }

        $userIds = array_values(array_unique(array_filter(array_map('intval', $userIds))));
        if (empty($userIds)) {
            return false;
        }

        $stmt = $conn->prepare(
            "INSERT IGNORE INTO task_assignees (task_id, user_id, assigned_by, assigned_at)
             VALUES (?, ?, ?, NOW())"
        );
        if (!$stmt) {
            return false;
        }

        foreach ($userIds as $uid) {
            $stmt->bind_param('iii', $taskId, $uid, $assignedBy);
            $stmt->execute();
        }

        return true;
    }

    /**
     * Assign single user (replace existing)
     */
    public static function assignSingle(int $taskId, int $userId, int $assignedBy): bool
    {
        global $conn;

        if ($taskId <= 0 || $userId <= 0) {
            return false;
        }

        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("DELETE FROM task_assignees WHERE task_id = ?");
            $stmt->bind_param('i', $taskId);
            $stmt->execute();

            $stmt = $conn->prepare(
                "INSERT INTO task_assignees (task_id, user_id, assigned_by, assigned_at)
                 VALUES (?, ?, ?, NOW())"
            );
            $stmt->bind_param('iii', $taskId, $userId, $assignedBy);
            $stmt->execute();

            $conn->commit();
            return true;
        } catch (Throwable $e) {
            $conn->rollback();
            return false;
        }
    }

    /**
     * Count tasks by project
     */
    public static function countByProject(int $projectId): int
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM tasks 
            WHERE project_id = ?
        ");
        if (!$stmt) {
            return 0;
        }

        $stmt->bind_param('i', $projectId);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        return (int)($row['total'] ?? 0);
    }
}
