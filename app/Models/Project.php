<?php

declare(strict_types=1);

class Project
{
    public static function all(PDO $db): array
    {
        $stmt = $db->query("SELECT * FROM projects ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function find(PDO $db, int $id): ?array
    {
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(PDO $db, array $data): int
    {
        $sql = "INSERT INTO projects (name, description, status, created_by, start_date, end_date, created_at)
                VALUES (:name, :description, :status, :created_by, :start_date, :end_date, NOW())";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return (int)$db->lastInsertId();
    }

    public static function update(PDO $db, int $id, array $data): bool
    {
        $sql = "UPDATE projects SET
                    name = :name,
                    description = :description,
                    status = :status,
                    start_date = :start_date,
                    end_date = :end_date
                WHERE id = :id";
        $data['id'] = $id;

        $stmt = $db->prepare($sql);
        return $stmt->execute($data);
    }

    public static function delete(PDO $db, int $id): bool
    {
        $stmt = $db->prepare("DELETE FROM projects WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
