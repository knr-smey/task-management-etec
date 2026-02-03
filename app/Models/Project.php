<?php
declare(strict_types=1);

require_once __DIR__ . '/Team.php';
class Project
{
   public static function allByCreator(int $userId): array
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT 
                p.*,
                COUNT(pm.user_id) AS member_count
            FROM projects p
            LEFT JOIN project_members pm 
                ON pm.project_id = p.id
            WHERE p.created_by = ?
            GROUP BY p.id
            ORDER BY p.id DESC
        ");
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
            // remove all old members first
            $stmt = $conn->prepare("DELETE FROM project_members WHERE project_id = ?");
            $stmt->bind_param("i", $projectId);
            $stmt->execute();

            // insert new selected members
            if (!empty($memberIds)) {
                $stmt = $conn->prepare("INSERT INTO project_members (project_id, user_id) VALUES (?, ?)");

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

    public static function getAssignedMemberIds(int $projectId): array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT user_id FROM project_members WHERE project_id = ?");
        $stmt->bind_param("i", $projectId);
        $stmt->execute();

        $res = $stmt->get_result();

        $ids = [];
        while ($row = $res->fetch_assoc()) {
            $ids[] = (int)$row['user_id'];
        }

        return $ids;
    }

    public static function countMembers(int $projectId): int
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM project_members
            WHERE project_id = ?
        ");
        $stmt->bind_param("i", $projectId);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }
    
    public static function assignTeam(int $projectId, int $teamId): bool
    {
        global $conn;

        $stmt = $conn->prepare("
            UPDATE projects
            SET team_id = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ii", $teamId, $projectId);

        return $stmt->execute();
    }

    public static function allByTeam(int $teamId): array
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT id, name, description, status, start_date, end_date
            FROM projects
            WHERE team_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->bind_param("i", $teamId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function unassignTeam(int $projectId): bool
    {
        global $conn;

        $stmt = $conn->prepare("
            UPDATE projects
            SET team_id = NULL
            WHERE id = ?
        ");

        $stmt->bind_param("i", $projectId);

        return $stmt->execute();
    }

    /**
     * Get project detail with team + team members
     */
    public static function findWithTeam(int $projectId, int $userId): ?array
    {
        global $conn;

        // 1. Project + team (authorized)
        $stmt = $conn->prepare("
            SELECT 
                p.*,
                t.id   AS team_id,
                t.name AS team_name,
                t.team_type,
                t.created_by AS team_created_by
            FROM projects p
            LEFT JOIN teams t ON t.id = p.team_id
            LEFT JOIN team_members tm ON tm.team_id = p.team_id
            WHERE p.id = ?
            AND (
                    p.created_by = ?
                    OR tm.member_id = ?
            )
            GROUP BY p.id
            LIMIT 1
        ");

        $stmt->bind_param("iii", $projectId, $userId, $userId);
        $stmt->execute();

        $project = $stmt->get_result()->fetch_assoc();
        if (!$project) {
            return null;
        }

        // 2. Attach team members
        if (!empty($project['team_id'])) {
            $project['team'] = Team::findWithMembers((int)$project['team_id']);
        } else {
            $project['team'] = null;
        }

        return $project;
    }

    public static function syncMembersFromTeam(int $projectId, int $teamId): bool
    {
        global $conn;

        $conn->begin_transaction();

        try {
            // 1. Clear old project members
            $stmt = $conn->prepare("
                DELETE FROM project_members
                WHERE project_id = ?
            ");
            $stmt->bind_param("i", $projectId);
            $stmt->execute();

            // 2. Insert team members into project_members
            $stmt = $conn->prepare("
                INSERT INTO project_members (project_id, user_id, created_at)
                SELECT ?, tm.member_id, NOW()
                FROM team_members tm
                WHERE tm.team_id = ?
            ");
            $stmt->bind_param("ii", $projectId, $teamId);
            $stmt->execute();

            $conn->commit();
            return true;
        } catch (Throwable $e) {
            $conn->rollback();
            return false;
        }
    }
}
