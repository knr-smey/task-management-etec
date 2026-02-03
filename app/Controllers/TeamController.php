<?php

declare(strict_types=1);

require_once __DIR__ . '/../Services/ResponseService.php';
require_once __DIR__ . '/../../includes/helpers.php';

require_once __DIR__ . '/../Models/Team.php';
require_once __DIR__ . '/../Models/TeamSession.php';
require_once __DIR__ . '/../Models/TeamInvite.php';
require_once __DIR__ . '/../Models/TeamMember.php';

class TeamController
{
    // Admin only
    private static function authorizeAdmin(): array
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) redirect('login');

        if (
            !userHasRole($user, 'super_admin') &&
            !userHasRole($user, 'admin') &&
            !userHasRole($user, 'instructor')
        ) {
            die("You don't have permission.");
        }

        return $user;
    }

    // Any logged-in user (member included)
    private static function authorizeAny(): array
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) redirect('login');
        return $user;
    }

    /**
     * PAGE: Team list (ADMIN ONLY)
     */
    public static function index(): void
    {
        $user  = self::authorizeAny();
        $token = csrf_token();

        $isAdmin = (
            userHasRole($user, 'super_admin') ||
            userHasRole($user, 'admin') ||
            userHasRole($user, 'instructor')
        );

        // admin sees his created teams, member sees joined teams
        $teams = $isAdmin
            ? Team::allByCreator((int)$user['id'])
            : Team::allByMember((int)$user['id']);

        // permission flag for UI
        $canCreateTeam = $isAdmin;

        require __DIR__ . '/../../pages/team/index.php';
    }


    /**
     * ACTION: Create team (ADMIN ONLY)
     * Insert into teams + team_sessions (one session)
     */
    public static function store(): void
    {
        $user = self::authorizeAdmin();

        if (function_exists('verify_csrf')) {
            verify_csrf($_POST['csrf'] ?? '');
        }

        $name     = trim((string)($_POST['name'] ?? ''));
        $teamType = trim((string)($_POST['team_type'] ?? 'unknown-type'));

        $day   = trim((string)($_POST['day'] ?? ''));
        $start = trim((string)($_POST['start_time'] ?? ''));
        $end   = trim((string)($_POST['end_time'] ?? ''));

        if ($name === '' || $day === '' || $start === '' || $end === '') {
            ResponseService::json(false, 'Missing fields', [], 422);
            return;
        }

        if (strtotime($start) >= strtotime($end)) {
            ResponseService::json(false, 'Invalid time range', [], 422);
            return;
        }

        global $conn;

        try {
            $conn->begin_transaction();

            $teamId = Team::create([
                'name'       => $name,
                'team_type'  => $teamType,
                'created_by' => (int)$user['id'],
            ]);

            if (!$teamId) throw new Exception("Create team failed");

            $ok = TeamSession::create([
                'team_id'     => (int)$teamId,
                'day_of_week' => $day,
                'start_time'  => $start,
                'end_time'    => $end,
            ]);

            if (!$ok) throw new Exception("Create team session failed");

            $conn->commit();
            ResponseService::json(true, 'Team created successfully', ['team_id' => (int)$teamId], 200);
        } catch (Throwable $e) {
            $conn->rollback();
            ResponseService::json(false, 'Create failed', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * PARTIAL: Team cards (ADMIN ONLY)
     */
    public static function cards(): void
    {
        $user = self::authorizeAdmin();

        $rows = TeamSession::allWithSessionsByCreator((int)$user['id']);

        $teams = [];
        foreach ($rows as $row) {
            $teamId = (int)$row['team_id'];

            if (!isset($teams[$teamId])) {
                $teams[$teamId] = [
                    'id'         => $teamId,
                    'name'       => $row['team_name'],
                    'team_type'  => $row['team_type'],
                    'created_at' => $row['created_at'],
                    'sessions'   => [],
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

        $teams = array_values($teams);

        ob_start();
        require __DIR__ . '/../../pages/components/team-cards.php';
        $html = ob_get_clean();

        ResponseService::json(true, 'ok', ['html' => $html], 200);
    }

    /**
     * ACTION: Create invite link (ADMIN ONLY)
     */
    public static function createInvite(): void
    {
        $user = self::authorizeAdmin();

        if (function_exists('verify_csrf')) {
            verify_csrf($_POST['csrf'] ?? '');
        }

        $teamId = (int)($_POST['team_id'] ?? 0);
        if ($teamId <= 0) {
            ResponseService::json(false, "Invalid team", [], 422);
            return;
        }

        $expiresAt = date('Y-m-d H:i:s', strtotime('+2 days'));

        $token = TeamInvite::create($teamId, (int)$user['id'], $expiresAt, 50);
        if (!$token) {
            ResponseService::json(false, "Cannot create invite", [], 500);
            return;
        }

        // send this link to member
        $link = full_url(BASE_URL . "team/join?token=" . $token);

        ResponseService::json(true, "Invite created", [
            "link" => $link,
            "expires_at" => $expiresAt
        ], 200);
    }

    /**
     * PAGE: Join page (MEMBER ALLOWED)
     * URL: /team/join?token=xxxx
     */
    public static function joinPage(): void
    {
        $inviteToken = trim((string)($_GET['token'] ?? ''));

        // if not logged in → save token and go login
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            if ($inviteToken !== '') {
                $_SESSION['join_invite_token'] = $inviteToken;
            }
            redirect('login');
        }

        // now logged in => show join page
        $invite = TeamInvite::findByToken($inviteToken);

        $error = '';
        $team  = null;

        if (!$invite || !TeamInvite::isValid($invite)) {
            $error = 'Invite invalid or expired';
        } else {
            $team = Team::find((int)$invite['team_id']);
            if (!$team) $error = 'Team not found';
        }

        $tokenCsrf = csrf_token();
        require __DIR__ . '/../../pages/team/join.php';
    }


    /**
     * ACTION: Join confirm (MEMBER ALLOWED)
     * POST: /team/join-confirm
     */
    public static function joinConfirm(): void
    {
        $user = self::authorizeAny();

        if (function_exists('verify_csrf')) {
            verify_csrf($_POST['csrf'] ?? '');
        }

        $token = trim((string)($_POST['token'] ?? ''));
        if ($token === '') redirect('dashboard');

        $invite = TeamInvite::findByToken($token);
        if (!$invite || !TeamInvite::isValid($invite)) {
            die("Invite invalid or expired");
        }

        $teamId   = (int)$invite['team_id'];
        $memberId = (int)$user['id'];

        if (!TeamMember::exists($teamId, $memberId)) {
            TeamMember::add($teamId, $memberId);
            TeamInvite::incrementUsage((int)$invite['id']);
        }

        redirect("team/detail?id=" . $teamId);
    }

    /**
     * PAGE: Team detail (OWNER OR JOINED MEMBER)
     * URL: /team/detail?id=XX
     */
    public static function detail(): void
    {
        $user = self::authorizeAny();

        $teamId = (int)($_GET['id'] ?? 0);
        if ($teamId <= 0) redirect('dashboard');

        $team = Team::find($teamId);
        if (!$team) redirect('dashboard');

        $isOwner  = ((int)$team['created_by'] === (int)$user['id']);
        $isMember = TeamMember::exists($teamId, (int)$user['id']);

        // roles: super_admin, admin, instructor
        $isAdmin = (
            userHasRole($user, 'super_admin') ||
            userHasRole($user, 'admin') ||
            userHasRole($user, 'instructor')
        );

        if (!$isOwner && !$isMember) {
            die("You don't have permission.");
        }

        // team data
        $sessions = TeamSession::allByTeam($teamId);
        $members  = TeamMember::allByTeam($teamId);
        $memberCount = count($members);

        // project data
        require_once __DIR__ . '/../Models/Project.php';

        // ✅ projects shown under "Projects"
        $projects = Project::allByTeam($teamId);

        // ✅ projects shown in Assign Project modal (ADMIN ONLY)
        $assignableProjects = [];
        if ($isAdmin) {
            $assignableProjects = Project::allByCreator((int)$user['id']);
        }

        $token = csrf_token();

        require __DIR__ . '/../../pages/team/detail.php';
    }
}
