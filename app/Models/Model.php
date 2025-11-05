<?php

namespace App\Models;

use App\Database;

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    /**
     * Buscar todos os registros
     */
    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM " . static::$table . " ORDER BY " . static::$primaryKey);
    }

    /**
     * Buscar um registro por ID
     */
    public static function find(int $id): array|false
    {
        return Database::fetchOne(
            "SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?",
            [$id]
        );
    }

    /**
     * Criar um novo registro
     */
    public static function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        Database::query(
            "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})",
            array_values($data)
        );

        return (int) Database::lastInsertId();
    }

    /**
     * Atualizar um registro
     */
    public static function update(int $id, array $data): bool
    {
        $setClause = implode(' = ?, ', array_keys($data)) . ' = ?';

        $result = Database::query(
            "UPDATE " . static::$table . " SET {$setClause} WHERE " . static::$primaryKey . " = ?",
            array_merge(array_values($data), [$id])
        );

        return $result->rowCount() > 0;
    }

    /**
     * Deletar um registro
     */
    public static function delete(int $id): bool
    {
        $result = Database::query(
            "DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?",
            [$id]
        );

        return $result->rowCount() > 0;
    }

    /**
     * Buscar registros com condições
     */
    public static function where(string $column, string $operator, mixed $value): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . static::$table . " WHERE {$column} {$operator} ? ORDER BY " . static::$primaryKey,
            [$value]
        );
    }

    /**
     * Buscar registros com LIKE
     */
    public static function like(string $column, string $value): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . static::$table . " WHERE {$column} LIKE ? ORDER BY " . static::$primaryKey,
            ["%{$value}%"]
        );
    }

    /**
     * Contar registros
     */
    public static function count(): int
    {
        $result = Database::fetchOne("SELECT COUNT(*) as total FROM " . static::$table);
        return (int) $result['total'];
    }

    /**
     * Verificar se um registro existe
     */
    public static function exists(int $id): bool
    {
        $result = Database::fetchOne(
            "SELECT 1 FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?",
            [$id]
        );

        return $result !== false;
    }
}
