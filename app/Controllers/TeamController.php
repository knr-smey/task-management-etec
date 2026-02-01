<?php
declare(strict_types=1);

require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';

require_once __DIR__ . '/../Models/Team.php';
require_once __DIR__ . '/../Models/TeamSession.php';

class TeamController
{
    private static function authorize(): array
    {
        $user = $_SESSION['user'] ?? null;

        if (!$user) redirect('login');

        if (
            !userHasRole($user, 'super_admin') &&
            !userHasRole($user, 'admin') &&
            !userHasRole($user, 'instructor')
        ) {
            redirect('dashboard');
        }

        return $user;
    }

    /**
     * PAGE: Team list + create modal
     */
   public static function index(): void
    {
        $user  = self::authorize();
        $token = csrf_token();

        // 🔹 raw join data
        $rows = TeamSession::allWithSessionsByCreator((int)$user['id']);

        // 🔹 group teams + sessions
        $teams = [];

        foreach ($rows as $row) {
            $teamId = (int)$row['team_id'];

            if (!isset($teams[$teamId])) {
                $teams[$teamId] = [
                    'id'        => $teamId,
                    'name'      => $row['team_name'],
                    'team_type' => $row['team_type'],
                    'created_at'=> $row['created_at'],
                    'sessions'  => [],
                ];
            }

            if (!empty($row['session_id'])) {
                $teams[$teamId]['sessions'][] = [
                    'day'   => $row['day_of_week'],
                    'start' => $row['start_time'],
                    'end'   => $row['end_time'],
                ];
            }
        }

        // reindex array for foreach in view
        $teams = array_values($teams);

        require __DIR__ . '/../../pages/team/index.php';
    }


    /**
     * ACTION: Create team (MODAL SUBMIT)
     * Insert into 2 tables: teams + team_sessions (ONE session only)
     */
    public static function store(): void
    {
        $user = self::authorize();

        // CSRF
        if (function_exists('verify_csrf')) {
            verify_csrf($_POST['csrf'] ?? '');
        }

        $name     = trim((string)($_POST['name'] ?? ''));
        $teamType = trim((string)($_POST['team_type'] ?? 'backend'));

        // SINGLE value (not array)
        $day   = trim((string)($_POST['day'] ?? ''));
        $start = trim((string)($_POST['start_time'] ?? ''));
        $end   = trim((string)($_POST['end_time'] ?? ''));

        // basic validation
        if ($name === '' || $day === '' || $start === '' || $end === '') {
            redirect('team');
        }

        // validate time
        if (strtotime($start) >= strtotime($end)) {
            redirect('team');
        }

        // Use transaction to ensure both inserts succeed
        global $conn;

        try {
            $conn->begin_transaction();

            // 1) Create team
            $teamId = Team::create([
                'name'       => $name,
                'team_type'  => $teamType,
                'created_by' => (int)$user['id'],
            ]);

            if (!$teamId) {
                throw new Exception("Create team failed");
            }

            // 2) Create ONE session row
            $ok = TeamSession::create([
                'team_id'    => (int)$teamId,
                'day_of_week'=> $day,
                'start_time' => $start,
                'end_time'   => $end,
            ]);

            if (!$ok) {
                throw new Exception("Create team session failed");
            }

            $conn->commit();
            ResponseService::json(true, 'Team created successfully', ['team_id' => (int)$teamId], 200);

        } catch (Throwable $e) {
            $conn->rollback();
            ResponseService::json(false, 'Create failed', [], 500);
        }
    }
}
