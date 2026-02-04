<?php

declare(strict_types=1);

class IssueStatus
{
    public static function all(): array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT id, name FROM issue_statuses ORDER BY id ASC");
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function exists(int $id): bool
    {
        global $conn;

        $stmt = $conn->prepare("SELECT 1 FROM issue_statuses WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        return (bool)$stmt->get_result()->fetch_row();
    }
}
