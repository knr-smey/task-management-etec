<?php
declare(strict_types=1);

class Team
{
    public static function allByCreator(int $userId): array
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT 
                t.*,
                COUNT(tm.member_id) AS member_count
            FROM teams t
            LEFT JOIN team_members tm 
                ON tm.team_id = t.id
            WHERE t.created_by = ?
            GROUP BY t.id
            ORDER BY t.id DESC
        ");
        $stmt->bind_param('i', $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function all(): array
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT 
                t.*,
                COUNT(tm.member_id) AS member_count
            FROM teams t
            LEFT JOIN team_members tm ON tm.team_id = t.id
            GROUP BY t.id
            ORDER BY t.id DESC
        ");
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function find(int $id): ?array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM teams WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $res = $stmt->get_result()->fetch_assoc();
        return $res ?: null;
    }

    public static function create(array $data): ?int
    {
        global $conn;

        $stmt = $conn->prepare("
            INSERT INTO teams (name, team_type, created_by, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        if (!$stmt) return null;

        $stmt->bind_param(
            'ssi',
            $data['name'],
            $data['team_type'],
            $data['created_by']
        );

        if (!$stmt->execute()) return null;
        return $conn->insert_id;
    }

    public static function update(int $id, array $data): bool
    {
        global $conn;

        $stmt = $conn->prepare("
            UPDATE teams SET
                name = ?,
                team_type = ?
            WHERE id = ?
        ");
        if (!$stmt) return false;

        $stmt->bind_param(
            'ssi',
            $data['name'],
            $data['team_type'],
            $id
        );

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        global $conn;

        // If you have FK ON DELETE CASCADE for team_sessions/team_members, they will auto delete.
        $stmt = $conn->prepare("DELETE FROM teams WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public static function countMembers(int $teamId): int
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM team_members
            WHERE team_id = ?
        ");
        $stmt->bind_param("i", $teamId);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }

    /**
     * Replace all team members (same pattern like Project::assignMembers)
     */
    public static function assignMembers(int $teamId, array $memberIds): bool
    {
        global $conn;

        $conn->begin_transaction();

        try {
            // remove all old members first
            $stmt = $conn->prepare("DELETE FROM team_members WHERE team_id = ?");
            $stmt->bind_param("i", $teamId);
            $stmt->execute();

            // insert new selected members
            if (!empty($memberIds)) {
                $stmt = $conn->prepare("INSERT INTO team_members (team_id, member_id, joined_at) VALUES (?, ?, NOW())");

                foreach ($memberIds as $mid) {
                    $mid = (int)$mid;
                    $stmt->bind_param("ii", $teamId, $mid);
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

    public static function getAssignedMemberIds(int $teamId): array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT member_id FROM team_members WHERE team_id = ?");
        $stmt->bind_param("i", $teamId);
        $stmt->execute();

        $res = $stmt->get_result();

        $ids = [];
        while ($row = $res->fetch_assoc()) {
            $ids[] = (int)$row['member_id'];
        }

        return $ids;
    }

    
}
