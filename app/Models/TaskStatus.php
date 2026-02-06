<?php

declare(strict_types=1);

class TaskStatus
{
    public static function all(): array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT id, name FROM task_statuses ORDER BY id ASC");
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function exists(int $id): bool
    {
        global $conn;

        $stmt = $conn->prepare("SELECT 1 FROM task_statuses WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return (bool)$stmt->get_result()->fetch_row();
    }

    public static function findIdByName(string $name): ?int
    {
        global $conn;

        $stmt = $conn->prepare("SELECT id FROM task_statuses WHERE name = ? LIMIT 1");
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('s', $name);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row ? (int)$row['id'] : null;
    }
}
