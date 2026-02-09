<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';
require_once __DIR__ . '/../Models/Attendance.php';
require_once __DIR__ . '/../Models/Team.php';
require_once __DIR__ . '/../Models/TeamMember.php';

class AttendanceController
{
    private static function authorizeAny(): array
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) redirect('login');
        return $user;
    }

    private static function ensureTeamAccess(int $teamId, array $user): array
    {
        $team = Team::findWithOwner($teamId);
        if (!$team) {
            redirect('team');
        }

        $isOwner = ((int)$team['created_by'] === (int)$user['id']);
        $isMember = TeamMember::exists($teamId, (int)$user['id']);

        if (!$isOwner && !$isMember) {
            die("You don't have permission.");
        }

        return $team;
    }

    public static function page(): void
    {
        $user = self::authorizeAny();

        $teamId = (int)($_GET['id'] ?? 0);
        if ($teamId <= 0) {
            redirect('team');
        }

        $team = self::ensureTeamAccess($teamId, $user);

        $members = TeamMember::allByTeam($teamId);

        $attendanceDate = trim((string)($_GET['date'] ?? ''));
        if ($attendanceDate === '') {
            $attendanceDate = date('Y-m-d');
        }

        $rows = Attendance::allByTeamAndDate($teamId, $attendanceDate);
        $attendanceMap = [];
        foreach ($rows as $row) {
            $attendanceMap[(int)$row['user_id']] = $row;
        }

        $token = csrf_token();

        require __DIR__ . '/../../pages/team/attendance.php';
    }

    public static function save(): void
    {
        $user = self::authorizeAny();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseService::json(false, 'Invalid request method', [], 405);
        }

        if (!verify_csrf($_POST['csrf'] ?? '')) {
            ResponseService::json(false, 'Invalid CSRF token', [], 403);
        }

        $teamId = (int)($_POST['team_id'] ?? 0);
        $attendanceDate = trim((string)($_POST['attendance_date'] ?? ''));
        $recordsRaw = (string)($_POST['records'] ?? '');

        if ($teamId <= 0 || $attendanceDate === '') {
            ResponseService::json(false, 'Missing required fields', [], 422);
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $attendanceDate)) {
            ResponseService::json(false, 'Invalid date format', [], 422);
        }

        self::ensureTeamAccess($teamId, $user);

        $records = json_decode($recordsRaw, true);
        if (!is_array($records)) {
            ResponseService::json(false, 'Invalid records payload', [], 422);
        }

        $allowedStatuses = ['present', 'permission', 'absence'];

        $members = TeamMember::allByTeam($teamId);
        $allowedMemberIds = array_map(static fn(array $m): int => (int)$m['id'], $members);
        $allowedMemberIds = array_flip($allowedMemberIds);

        $cleanRecords = [];
        foreach ($records as $record) {
            $userId = (int)($record['user_id'] ?? 0);
            $status = trim((string)($record['status'] ?? ''));
            $reason = trim((string)($record['reason'] ?? ''));

            if ($userId <= 0 || !isset($allowedMemberIds[$userId])) {
                continue;
            }

            if (!in_array($status, $allowedStatuses, true)) {
                ResponseService::json(false, 'Invalid status', [], 422);
            }

            if ($status === 'permission' && $reason === '') {
                ResponseService::json(false, 'Reason is required for permission', [], 422);
            }

            if ($status !== 'permission') {
                $reason = '';
            }

            $cleanRecords[] = [
                'user_id' => $userId,
                'status' => $status,
                'reason' => $reason,
            ];
        }

        if (empty($cleanRecords)) {
            ResponseService::json(false, 'No attendance records to save', [], 422);
        }

        $ok = Attendance::upsertMany($teamId, $attendanceDate, $cleanRecords);
        if ($ok) {
            ResponseService::json(true, 'Attendance saved', [], 200);
        }

        ResponseService::json(false, 'Save failed', [], 500);
    }
}
