<?php

declare(strict_types=1);

class TeamMember
{
    public static function exists(int $teamId, int $memberId): bool
    {
        global $conn;

        $stmt = $conn->prepare("SELECT 1 FROM team_members WHERE team_id = ? AND member_id = ? LIMIT 1");
        $stmt->bind_param("ii", $teamId, $memberId);
        $stmt->execute();

        return (bool)$stmt->get_result()->fetch_row();
    }

    public static function add(int $teamId, int $memberId): bool
    {
        global $conn;

        $stmt = $conn->prepare("
            INSERT INTO team_members (team_id, member_id, joined_at)
            VALUES (?, ?, NOW())
        ");
        $stmt->bind_param("ii", $teamId, $memberId);

        return $stmt->execute();
    }

    public static function allByTeam(int $teamId): array
    {
        global $conn;

        $stmt = $conn->prepare("
        SELECT u.id, u.name, u.email, tm.joined_at
        FROM team_members tm
        JOIN users u ON u.id = tm.member_id
        WHERE tm.team_id = ?
        ORDER BY tm.joined_at DESC
    ");
        $stmt->bind_param("i", $teamId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }
}
