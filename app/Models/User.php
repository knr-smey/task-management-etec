<?php

class User
{
    public const ROLE_SUPERADMIN = 1;
    public const ROLE_ADMIN      = 2;
    public const ROLE_INSTRUCTOR = 3;
    public const ROLE_MEMBER     = 4;

    // is_active meaning
    public const ACTIVE   = 1;
    public const INACTIVE = 0;

    public const TYPE_FRONTEND = 'Frontend';
    public const TYPE_BACKEND  = 'Backend';

    public static function activeLabel(int $isActive): string
    {
        return $isActive === self::ACTIVE ? 'Active' : 'Inactive';
    }

    public static function all(array $currentUser = []): array
    {
        global $conn;

        $sql = "SELECT u.id, u.name, u.email, u.course, u.is_active, u.created_at,
                       GROUP_CONCAT(r.name) AS roles
                FROM users u
                LEFT JOIN user_roles ur ON ur.user_id = u.id
                LEFT JOIN roles r ON r.id = ur.role_id";

        $params = [];
        $types  = '';
        $where  = [];

        if (userHasRole($currentUser, 'super_admin')) {
            // see all
        } elseif (userHasRole($currentUser, 'admin')) {
            $where[] = "r.name IN ('instructor', 'member')";
        } elseif (userHasRole($currentUser, 'instructor')) {
            $where[] = "r.name = 'member'";
            // if you have instructor_id column in users, keep this:
            $where[] = "u.instructor_id = ?";
            $params[] = (int)$currentUser['id'];
            $types .= 'i';
        } else {
            return [];
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " GROUP BY u.id ORDER BY u.id ASC";

        if (!empty($params)) {
            $stmt = $conn->prepare($sql);
            if (!$stmt) return [];
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $res = $stmt->get_result();
            return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        }

        $res = $conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function create(array $data): ?int
    {
        global $conn;

        $name  = trim((string)($data['name'] ?? ''));
        $email = trim((string)($data['email'] ?? ''));
        $rawPassword = (string)($data['password'] ?? '');
        $roleId = (int)($data['role'] ?? 0);

        // ✅ is_active: 1/0
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : self::ACTIVE;

        // ✅ course rules:
        // - member: must have course
        // - admin/super/s instructor: course = NULL
        $course = $data['course'] ?? null;

        if ($roleId === self::ROLE_MEMBER) {
            $course = trim((string)$course);
            if ($course === '') return null; // member must have course
        } else {
            $course = null; // other roles must be NULL
        }

        if ($name === '' || $email === '' || $rawPassword === '' || $roleId <= 0) {
            return null;
        }

        $passwordHash = password_hash($rawPassword, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password_hash, course, is_active)
            VALUES (?, ?, ?, ?, ?)
        ");
        if (!$stmt) return null;

        // course can be NULL (mysqli allows null with 's')
        $stmt->bind_param('ssssi', $name, $email, $passwordHash, $course, $isActive);

        if (!$stmt->execute()) {
            error_log("Create execute failed: " . $stmt->error);
            return null;
        }

        $userId = (int)$conn->insert_id;

        $stmtRole = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
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

        // ✅ course rules on update too
        if ($roleId > 0) {
            if ($roleId === self::ROLE_MEMBER) {
                $course = trim((string)($data['course'] ?? ''));
                // member can’t be null
                $fields[] = 'course = ?';
                $params[] = $course;
                $types .= 's';
            } else {
                $fields[] = 'course = ?';
                $params[] = null; // admin/super -> null
                $types .= 's';
            }
        }

        // ✅ is_active: 1/0
        if ($isActive !== null) {
            $fields[] = 'is_active = ?';
            $params[] = $isActive;
            $types .= 'i';
        }

        // ✅ if password typed, hash it
        if (!empty($data['password'])) {
            $fields[] = 'password_hash = ?';
            $params[] = password_hash((string)$data['password'], PASSWORD_BCRYPT);
            $types .= 's';
        }

        if (empty($fields)) return false;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $id;
        $types .= 'i';

        $stmt = $conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param($types, ...$params);
        $ok = $stmt->execute();

        // ✅ update role link (if role provided)
        if ($roleId > 0) {
            $stmtRole = $conn->prepare("DELETE FROM user_roles WHERE user_id = ?");
            if ($stmtRole) {
                $stmtRole->bind_param('i', $id);
                $stmtRole->execute();
            }
            $stmtRoleInsert = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
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

        $stmt = $conn->prepare("DELETE FROM user_roles WHERE user_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
