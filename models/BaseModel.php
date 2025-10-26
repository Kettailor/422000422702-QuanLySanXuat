<?php

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(string $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $columns = array_keys($data);
        $columnString = implode(', ', $columns);

        $placeholders = [];
        $bindings = [];

        foreach ($columns as $index => $column) {
            $placeholder = ':p' . $index;
            $placeholders[] = $placeholder;
            $bindings[$placeholder] = $data[$column];
        }

        $placeholderString = implode(', ', $placeholders);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$columnString}) VALUES ({$placeholderString})");
        foreach ($bindings as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        Logger::info("Tạo bản ghi mới trong {$this->table} với dữ liệu: " . json_encode($data));
        return $stmt->execute();
    }

    public function update(string $id, array $data): bool
    {
        $columns = array_keys($data);
        $setParts = [];
        $bindings = [];

        foreach ($columns as $index => $column) {
            $placeholder = ':p' . $index;
            $setParts[] = "{$column} = {$placeholder}";
            $bindings[$placeholder] = $data[$column];
        }

        $setClause = implode(', ', $setParts);
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id");

        foreach ($bindings as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        $stmt->bindValue(':id', $id);
        Logger::info("Cập nhật bản ghi {$id} trong {$this->table} với dữ liệu: " . json_encode($data));
        return $stmt->execute();
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindValue(':id', $id);
        Logger::info("Xóa bản ghi {$id} khỏi {$this->table}");
        return $stmt->execute();
    }
}
