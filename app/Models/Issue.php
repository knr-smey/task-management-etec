<?php

declare(strict_types=1);

class Issue
{
    public static function findByTask(int $taskId): ?array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM issues WHERE task_id = ? LIMIT 1");
        $stmt->bind_param('i', $taskId);
        $stmt->execute();

        $issue = $stmt->get_result()->fetch_assoc();
        return $issue ?: null;
    }

    public static function upsertByTask(array $task, int $statusId, int $userId): bool
    {
        global $conn;

        $taskId = (int)($task['id'] ?? 0);
        if ($taskId <= 0) {
            return false;
        }

        $existing = self::findByTask($taskId);

        if ($existing) {
            $stmt = $conn->prepare("UPDATE issues SET status_id = ?, updated_at = NOW() WHERE task_id = ? LIMIT 1");
            $stmt->bind_param('ii', $statusId, $taskId);
            return $stmt->execute();
        }

        $projectId = (int)($task['project_id'] ?? 0);
        $title = (string)($task['title'] ?? 'Task Issue');
        $description = $task['description'] ?? null;

        $stmt = $conn->prepare(
            "INSERT INTO issues (project_id, task_id, title, description, status_id, created_by, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        $stmt->bind_param('iissii', $projectId, $taskId, $title, $description, $statusId, $userId);

        return $stmt->execute();
    }
}
