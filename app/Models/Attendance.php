<?php

declare(strict_types=1);

class Attendance
{
    public static function allByTeamAndDate(int $teamId, string $date): array
    {
        global $conn;

        $stmt = $conn->prepare('
            SELECT *
            FROM attendance
            WHERE team_id = ? AND attendance_date = ?
            ORDER BY id DESC
        ');
        $stmt->bind_param('is', $teamId, $date);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function upsertMany(int $teamId, string $date, array $records): bool
    {
        global $conn;

        $conn->begin_transaction();

        try {
            $existing = self::allByTeamAndDate($teamId, $date);
            $existingMap = [];
            foreach ($existing as $row) {
                $existingMap[(int)$row['user_id']] = (int)$row['id'];
            }

            $updateStmt = $conn->prepare('
                UPDATE attendance
                SET status = ?, reason = ?, updated_at = NOW()
                WHERE id = ?
            ');
            $insertStmt = $conn->prepare('
                INSERT INTO attendance (team_id, user_id, attendance_date, status, reason, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ');

            foreach ($records as $record) {
                $userId = (int)($record['user_id'] ?? 0);
                $status = (string)($record['status'] ?? '');
                $reason = isset($record['reason']) ? (string)$record['reason'] : null;

                if ($userId <= 0 || $status === '') {
                    continue;
                }

                if (isset($existingMap[$userId])) {
                    $id = $existingMap[$userId];
                    $updateStmt->bind_param('ssi', $status, $reason, $id);
                    if (!$updateStmt->execute()) {
                        throw new Exception('Update failed');
                    }
                } else {
                    $insertStmt->bind_param('iisss', $teamId, $userId, $date, $status, $reason);
                    if (!$insertStmt->execute()) {
                        throw new Exception('Insert failed');
                    }
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
