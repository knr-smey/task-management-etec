<?php
declare(strict_types=1);

class Project
{
    public static function allByCreator(int $userId): array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM projects WHERE created_by = ? ORDER BY id DESC");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function find(int $id): ?array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_assoc();
        return $res ?: null;
    }

    public static function create(array $data): ?int
    {
        global $conn;

        $stmt = $conn->prepare("
            INSERT INTO projects (name, description, status, created_by, start_date, end_date, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        if (!$stmt) return null;

        $stmt->bind_param(
            'sssiss',
            $data['name'],
            $data['description'],
            $data['status'],
            $data['created_by'],
            $data['start_date'],
            $data['end_date']
        );

        if (!$stmt->execute()) return null;
        return $conn->insert_id;
    }

    public static function update(int $id, array $data): bool
    {
        global $conn;

        $stmt = $conn->prepare("
            UPDATE projects SET
                name = ?, description = ?, status = ?,
                start_date = ?, end_date = ?
            WHERE id = ?
        ");
        if (!$stmt) return false;

        $stmt->bind_param(
            'sssssi',
            $data['name'],
            $data['description'],
            $data['status'],
            $data['start_date'],
            $data['end_date'],
            $id
        );

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    
    public static function assignMembers(int $projectId, array $memberIds): bool
    {
        global $conn;

        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("DELETE FROM project_members WHERE project_id = ?");
            $stmt->bind_param("i", $projectId);
            $stmt->execute();

            if (!empty($memberIds)) {
                $stmt = $conn->prepare("
                INSERT INTO project_members (project_id, user_id)
                VALUES (?, ?)
            ");

                foreach ($memberIds as $uid) {
                    $uid = (int)$uid;
                    $stmt->bind_param("ii", $projectId, $uid);
                    $stmt->execute();
                }
            }

            $conn->commit();
            return true;
        } catch (Throwable $e) {
            $conn->rollback();
            return false;
        }
    }
}
