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

        $quotedColumns = [];
        $placeholders = [];
        $bindings = [];

        foreach ($columns as $index => $column) {
            $quotedColumns[] = $this->quoteIdentifier($column);
            $placeholder = ':p' . $index;
            $placeholders[] = $placeholder;
            $bindings[$placeholder] = $data[$column];
        }

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->quoteIdentifier($this->table),
            implode(', ', $quotedColumns),
            implode(', ', $placeholders)
        );

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }

        return $stmt->execute();
    }

    public function update(string $id, array $data): bool
    {
        $columns = array_keys($data);
        $assignments = [];
        $bindings = [];

        foreach ($columns as $index => $column) {
            $placeholder = ':p' . $index;
            $assignments[] = sprintf('%s = %s', $this->quoteIdentifier($column), $placeholder);
            $bindings[$placeholder] = $data[$column];
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = :primary_id',
            $this->quoteIdentifier($this->table),
            implode(', ', $assignments),
            $this->quoteIdentifier($this->primaryKey)
        );

        $stmt = $this->db->prepare($sql);
        foreach ($bindings as $placeholder => $value) {
            $stmt->bindValue($placeholder, $value);
        }
        $stmt->bindValue(':primary_id', $id);

        return $stmt->execute();
    }

    protected function quoteIdentifier(string $identifier): string
    {
        $identifier = trim($identifier);

        if ($identifier === '*') {
            return $identifier;
        }

        $parts = explode('.', $identifier);

        $quotedParts = array_map(static function (string $part): string {
            $part = trim($part, "` ");
            return '`' . str_replace('`', '``', $part) . '`';
        }, $parts);

        return implode('.', $quotedParts);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}
