<?php
declare(strict_types=1);

class TeamSession
{
    public static function allByTeam(int $teamId): array
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT *
            FROM team_sessions
            WHERE team_id = ?
            ORDER BY FIELD(day_of_week,'mon','tue','wed','thu','fri','sat','sun'), start_time
        ");
        $stmt->bind_param("i", $teamId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function allWithSessionsByCreator(int $userId): array
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT
                t.id            AS team_id,
                t.name          AS team_name,
                t.team_type,
                t.created_at,

                ts.id           AS session_id,
                ts.day_of_week,
                ts.start_time,
                ts.end_time
            FROM teams t
            LEFT JOIN team_sessions ts
                ON ts.team_id = t.id
            WHERE t.created_by = ?
            ORDER BY 
                t.id DESC,
                FIELD(ts.day_of_week,'mon','tue','wed','thu','fri','sat','sun'),
                ts.start_time
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function allWithSessionsByMember(int $userId): array
    {
        global $conn;

        $stmt = $conn->prepare("
            SELECT
                t.id            AS team_id,
                t.name          AS team_name,
                t.team_type,
                t.created_at,

                ts.id           AS session_id,
                ts.day_of_week,
                ts.start_time,
                ts.end_time
            FROM teams t
            INNER JOIN team_members tm
                ON tm.team_id = t.id AND tm.member_id = ?
            LEFT JOIN team_sessions ts
                ON ts.team_id = t.id
            ORDER BY
                t.id DESC,
                FIELD(ts.day_of_week,'mon','tue','wed','thu','fri','sat','sun'),
                ts.start_time
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function find(int $id): ?array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM team_sessions WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public static function create(array $data): bool
    {
        global $conn;

        $stmt = $conn->prepare("
            INSERT INTO team_sessions (team_id, day_of_week, start_time, end_time, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NULL)
        ");
        if (!$stmt) return false;

        $teamId = (int)$data['team_id'];
        $day    = (string)$data['day_of_week'];
        $start  = (string)$data['start_time'];
        $end    = (string)$data['end_time'];

        $stmt->bind_param("isss", $teamId, $day, $start, $end);
        return $stmt->execute();
    }

    public static function update(int $id, array $data): bool
    {
        global $conn;

        $stmt = $conn->prepare("
            UPDATE team_sessions SET
                day_of_week = ?,
                start_time = ?,
                end_time = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        if (!$stmt) return false;

        $stmt->bind_param(
            "sssi",
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time'],
            $id
        );

        return $stmt->execute();
    }

    public static function delete(int $id): bool
    {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM team_sessions WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public static function deleteByTeam(int $teamId): bool
    {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM team_sessions WHERE team_id = ?");
        $stmt->bind_param("i", $teamId);
        return $stmt->execute();
    }

    public static function saveMany(int $teamId, array $sessions): bool
    {
        global $conn;

        $conn->begin_transaction();

        try {
            // remove old sessions
            $stmt = $conn->prepare("DELETE FROM team_sessions WHERE team_id = ?");
            $stmt->bind_param("i", $teamId);
            $stmt->execute();

            if (!empty($sessions)) {
                $stmt = $conn->prepare("
                    INSERT INTO team_sessions (team_id, day_of_week, start_time, end_time, created_at, updated_at)
                    VALUES (?, ?, ?, ?, NOW(), NULL)
                ");

                foreach ($sessions as $s) {
                    $day = (string)($s['day_of_week'] ?? '');
                    $start = (string)($s['start_time'] ?? '');
                    $end = (string)($s['end_time'] ?? '');

                    if ($day === '' || $start === '' || $end === '') continue;
                    if (strtotime($start) >= strtotime($end)) continue;

                    $stmt->bind_param("isss", $teamId, $day, $start, $end);
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
