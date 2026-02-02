<?php

declare(strict_types=1);

class TeamInvite
{
    public static function create(int $teamId, int $createdBy, ?string $expiresAt = null, int $maxUses = 50): ?string
    {
        global $conn;

        $token = bin2hex(random_bytes(32)); // 64 chars

        $stmt = $conn->prepare("
            INSERT INTO team_invites (team_id, token, expires_at, max_uses, used_count, created_by)
            VALUES (?, ?, ?, ?, 0, ?)
        ");
        $stmt->bind_param("issii", $teamId, $token, $expiresAt, $maxUses, $createdBy);

        if (!$stmt->execute()) return null;
        return $token;
    }

    public static function findByToken(string $token): ?array
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM team_invites WHERE token = ? LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public static function isValid(array $invite): bool
    {
        if (!empty($invite['expires_at']) && strtotime((string)$invite['expires_at']) < time()) return false;
        if ((int)$invite['used_count'] >= (int)$invite['max_uses']) return false;
        return true;
    }

    public static function incrementUsage(int $inviteId): bool
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE team_invites SET used_count = used_count + 1 WHERE id = ?");
        $stmt->bind_param("i", $inviteId);
        return $stmt->execute();
    }
}
