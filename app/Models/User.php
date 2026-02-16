<?php

class User
{
    public const ROLE_SUPERADMIN = 1;
    public const ROLE_ADMIN = 2;
    public const ROLE_INSTRUCTOR = 3;
    public const ROLE_MEMBER = 4;

    public const ACTIVE = 1;
    public const INACTIVE = 0;

    public const TYPE_FRONTEND = 'Frontend';
    public const TYPE_BACKEND = 'Backend';

    public static function activeLabel(int $isActive): string
    {
        return $isActive === self::ACTIVE ? 'Active' : 'Inactive';
    }

    public static function findById(int $id): ?array
    {
        global $conn;

        $stmt = $conn->prepare('SELECT id, name, email, course, is_active, created_at FROM users WHERE id = ? LIMIT 1');
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        return $row ?: null;
    }

    public static function all(array $currentUser = [], ?int $teamId = null): array
    {
        global $conn;

        $scope = self::buildMemberScope($currentUser, $teamId);
        if ($scope === null) {
            return [];
        }

        $sql = "
            SELECT
                u.id,
                u.name,
                u.email,
                u.course,
                u.is_active,
                u.created_at,
                GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ',') AS roles
            {$scope['from']}
            {$scope['whereSql']}
            GROUP BY u.id
            ORDER BY u.id ASC
        ";

        if (!$scope['params']) {
            $res = $conn->query($sql);
            return $res ? ($res->fetch_all(MYSQLI_ASSOC) ?? []) : [];
        }

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return [];
        }

        $stmt->bind_param($scope['types'], ...$scope['params']);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public static function paginate(
        array $currentUser = [],
        ?int $teamId = null,
        array $filters = [],
        int $page = 1,
        int $perPage = 7
    ): array {
        global $conn;

        $scope = self::buildMemberScope($currentUser, $teamId, $filters);
        if ($scope === null) {
            return [
                'items' => [],
                'pagination' => [
                    'page' => 1,
                    'per_page' => max(1, min(100, $perPage)),
                    'total' => 0,
                    'total_pages' => 0,
                    'start' => 0,
                    'end' => 0,
                ],
                'stats' => [
                    'active' => 0,
                    'inactive' => 0,
                ],
            ];
        }

        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        $countSql = "
            SELECT
                COUNT(DISTINCT u.id) AS total,
                COUNT(DISTINCT CASE WHEN u.is_active = 1 THEN u.id END) AS active_total,
                COUNT(DISTINCT CASE WHEN u.is_active = 0 THEN u.id END) AS inactive_total
            {$scope['from']}
            {$scope['whereSql']}
        ";

        $stmtCount = $conn->prepare($countSql);
        if (!$stmtCount) {
            return [
                'items' => [],
                'pagination' => [
                    'page' => 1,
                    'per_page' => $perPage,
                    'total' => 0,
                    'total_pages' => 0,
                    'start' => 0,
                    'end' => 0,
                ],
                'stats' => [
                    'active' => 0,
                    'inactive' => 0,
                ],
            ];
        }

        if ($scope['params']) {
            $stmtCount->bind_param($scope['types'], ...$scope['params']);
        }

        $stmtCount->execute();
        $countRow = $stmtCount->get_result()->fetch_assoc() ?: [];

        $total = (int)($countRow['total'] ?? 0);
        $totalPages = $total > 0 ? (int)ceil($total / $perPage) : 0;

        if ($totalPages > 0 && $page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT
                u.id,
                u.name,
                u.email,
                u.course,
                u.is_active,
                u.created_at,
                GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ',') AS roles
            {$scope['from']}
            {$scope['whereSql']}
            GROUP BY u.id
            ORDER BY u.id ASC
            LIMIT ?
            OFFSET ?
        ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return [
                'items' => [],
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                    'start' => 0,
                    'end' => 0,
                ],
                'stats' => [
                    'active' => (int)($countRow['active_total'] ?? 0),
                    'inactive' => (int)($countRow['inactive_total'] ?? 0),
                ],
            ];
        }

        $types = $scope['types'] . 'ii';
        $params = $scope['params'];
        $params[] = $perPage;
        $params[] = $offset;

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];

        $start = $total > 0 ? ($offset + 1) : 0;
        $end = $total > 0 ? min($offset + $perPage, $total) : 0;

        return [
            'items' => $items,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'start' => $start,
                'end' => $end,
            ],
            'stats' => [
                'active' => (int)($countRow['active_total'] ?? 0),
                'inactive' => (int)($countRow['inactive_total'] ?? 0),
            ],
        ];
    }

    private static function buildMemberScope(array $currentUser, ?int $teamId = null, array $filters = []): ?array
    {
        $from = "
            FROM users u
            LEFT JOIN user_roles ur ON ur.user_id = u.id
            LEFT JOIN roles r ON r.id = ur.role_id
            LEFT JOIN team_members tm ON tm.member_id = u.id
        ";

        $where = [];
        $params = [];
        $types = '';

        if (userHasRole($currentUser, 'super_admin')) {
            // See all users.
        } elseif (userHasRole($currentUser, 'admin')) {
            $where[] = "r.name IN ('instructor','member')";
        } elseif (userHasRole($currentUser, 'instructor')) {
            $where[] = "r.name = 'member'";
            $where[] = 'tm.team_id IN (SELECT team_id FROM team_members WHERE member_id = ?)';
            $params[] = (int)($currentUser['id'] ?? 0);
            $types .= 'i';
        } elseif (userHasRole($currentUser, 'member')) {
            $where[] = 'tm.team_id IN (SELECT team_id FROM team_members WHERE member_id = ?)';
            $params[] = (int)($currentUser['id'] ?? 0);
            $types .= 'i';
        } else {
            return null;
        }

        if ($teamId !== null && $teamId > 0) {
            $where[] = 'tm.team_id = ?';
            $params[] = $teamId;
            $types .= 'i';
        }

        $role = trim((string)($filters['role'] ?? ''));
        if (in_array($role, ['super_admin', 'admin', 'instructor', 'member'], true)) {
            $where[] = 'r.name = ?';
            $params[] = $role;
            $types .= 's';
        }

        $statusRaw = (string)($filters['status'] ?? '');
        if ($statusRaw === '0' || $statusRaw === '1') {
            $where[] = 'u.is_active = ?';
            $params[] = (int)$statusRaw;
            $types .= 'i';
        }

        $whereSql = $where ? (' WHERE ' . implode(' AND ', $where)) : '';

        return [
            'from' => $from,
            'whereSql' => $whereSql,
            'types' => $types,
            'params' => $params,
        ];
    }

    public static function create(array $data): ?int
    {
        global $conn;

        $name = trim((string)($data['name'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));
        $rawPassword = (string)($data['password'] ?? '');
        $roleId = (int)($data['role'] ?? 0);

        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : self::ACTIVE;

        $course = $data['course'] ?? null;

        if ($roleId === self::ROLE_MEMBER) {
            $course = trim((string)$course);
            if ($course === '') {
                return null;
            }
        } else {
            $course = null;
        }

        if ($name === '' || $email === '' || $rawPassword === '' || $roleId <= 0) {
            return null;
        }

        $passwordHash = password_hash($rawPassword, PASSWORD_BCRYPT);

        $stmt = $conn->prepare('INSERT INTO users (name, email, password_hash, course, is_active) VALUES (?, ?, ?, ?, ?)');
        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('ssssi', $name, $email, $passwordHash, $course, $isActive);

        if (!$stmt->execute()) {
            error_log('Create execute failed: ' . $stmt->error);
            return null;
        }

        $userId = (int)$conn->insert_id;

        $stmtRole = $conn->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)');
        if ($stmtRole) {
            $stmtRole->bind_param('ii', $userId, $roleId);
            $stmtRole->execute();
        }

        return $userId;
    }

    public static function update(int $id, array $data): bool
    {
        global $conn;

        $roleId = (int)($data['role'] ?? 0);
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : null;

        $fields = [];
        $params = [];
        $types = '';

        if (!empty($data['name'])) {
            $fields[] = 'name = ?';
            $params[] = trim((string)$data['name']);
            $types .= 's';
        }

        if (!empty($data['email'])) {
            $fields[] = 'email = ?';
            $params[] = trim((string)$data['email']);
            $types .= 's';
        }

        if ($roleId > 0) {
            if ($roleId === self::ROLE_MEMBER) {
                $course = trim((string)($data['course'] ?? ''));
                $fields[] = 'course = ?';
                $params[] = $course;
                $types .= 's';
            } else {
                $fields[] = 'course = ?';
                $params[] = null;
                $types .= 's';
            }
        }

        if ($isActive !== null) {
            $fields[] = 'is_active = ?';
            $params[] = $isActive;
            $types .= 'i';
        }

        if (!empty($data['password'])) {
            $fields[] = 'password_hash = ?';
            $params[] = password_hash((string)$data['password'], PASSWORD_BCRYPT);
            $types .= 's';
        }

        if (empty($fields)) {
            return false;
        }

        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $params[] = $id;
        $types .= 'i';

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param($types, ...$params);
        $ok = $stmt->execute();

        if ($roleId > 0) {
            $stmtRole = $conn->prepare('DELETE FROM user_roles WHERE user_id = ?');
            if ($stmtRole) {
                $stmtRole->bind_param('i', $id);
                $stmtRole->execute();
            }

            $stmtRoleInsert = $conn->prepare('INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)');
            if ($stmtRoleInsert) {
                $stmtRoleInsert->bind_param('ii', $id, $roleId);
                $stmtRoleInsert->execute();
            }
        }

        return $ok;
    }

    public static function delete(int $id): bool
    {
        global $conn;

        $stmt = $conn->prepare('DELETE FROM user_roles WHERE user_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bind_param('i', $id);

        return $stmt->execute();
    }

    public static function membersOnly(): array
    {
        global $conn;

        $sql = "
            SELECT
                u.id,
                u.name,
                u.email
            FROM users u
            INNER JOIN user_roles ur ON ur.user_id = u.id
            INNER JOIN roles r ON r.id = ur.role_id
            WHERE r.name = 'member'
            ORDER BY u.name ASC
        ";

        $res = $conn->query($sql);
        return $res ? ($res->fetch_all(MYSQLI_ASSOC) ?? []) : [];
    }
}
