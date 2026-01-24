<?php
class User
{
    public const ROLE_SUPERADMIN = 1;
    public const ROLE_ADMIN      = 2;
    public const ROLE_INSTRUCTOR = 3;
    public const ROLE_MEMBER     = 4;

    public const STATUS_APPROVE = 1;
    public const STATUS_REJECT  = 2;
    public const STATUS_PENDING = 3;

    public const TYPE_FRONTEND = 'Frontend';
    public const TYPE_BACKEND = 'Backend';


    public static function all(): array
    {
        global $conn;
        $sql = "SELECT u.id, u.name, u.email, u.course, u.is_active,u.created_at,
                       GROUP_CONCAT(r.name) AS roles
                FROM users u
                LEFT JOIN user_roles ur ON ur.user_id = u.id
                LEFT JOIN roles r ON r.id = ur.role_id
                GROUP BY u.id
                ORDER BY u.id ASC";
        $result = $conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // public static function find(int $id): ?array
    // {
    //     global $conn;
    //     $stmt = $conn->prepare("
    //         SELECT u.id, u.name, u.email, u.course, u.is_active,
    //                GROUP_CONCAT(r.name) AS roles
    //         FROM users u
    //         LEFT JOIN user_roles ur ON ur.user_id = u.id
    //         LEFT JOIN roles r ON r.id = ur.role_id
    //         WHERE u.id = ?
    //         GROUP BY u.id
    //         LIMIT 1
    //     ");
    //     $stmt->bind_param('i', $id);
    //     $stmt->execute();
    //     $res = $stmt->get_result();
    //     return $res->num_rows ? $res->fetch_assoc() : null;
    // }

    public static function create(array $data): ?int
    {
        global $conn;

        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $password = password_hash($data['password'] ?? '', PASSWORD_BCRYPT);
        $status = $data['status'] ?? 1;
        $type = $data['type'] ?? 1;

        $stmt = $conn->prepare("
            INSERT INTO users (name, email, password_hash,course, is_active)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssssi', $name, $email, $password,$type, $status);

        if ($stmt->execute()) {
            $userId = $conn->insert_id;
            $stmtRole = $conn->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
            $stmtRole->bind_param('ii', $userId, $data['role']);
            $stmtRole->execute();
            return true;
        }

        return null;
    }

    public static function update(int $id, array $data): bool
    {
        global $conn;

        $fields = [];
        $params = [];
        $types = '';

        if (!empty($data['name'])) {
            $fields[] = 'name = ?';
            $params[] = $data['name'];
            $types .= 's';
        }
        if (!empty($data['email'])) {
            $fields[] = 'email = ?';
            $params[] = $data['email'];
            $types .= 's';
        }
        if (!empty($data['course'])) {
            $fields[] = 'course = ?';
            $params[] = $data['course'];
            $types .= 's';
        }
        if (isset($data['is_active'])) {
            $fields[] = 'is_active = ?';
            $params[] = (int)$data['is_active'];
            $types .= 'i';
        }
        if (!empty($data['password'])) {
            $fields[] = 'password_hash = ?';
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
            $types .= 's';
        }

        if (empty($fields)) return false;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $params[] = $id;
        $types .= 'i';

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
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
