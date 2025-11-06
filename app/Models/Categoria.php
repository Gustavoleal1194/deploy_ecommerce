<?php

namespace App\Models;

use App\Database;

class Categoria extends Model
{
    protected static string $table = 'categorias';
    protected static string $primaryKey = 'id';

    /**
     * Buscar todas as categorias ordenadas por nome
     */
    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM categorias ORDER BY nome");
    }

    /**
     * Buscar categoria por nome (para validação de duplicatas)
     */
    public static function findByNome(string $nome): array|false
    {
        return Database::fetchOne(
            "SELECT * FROM categorias WHERE nome = ?",
            [trim($nome)]
        );
    }

    /**
     * Buscar categorias com busca por nome
     */
    public static function buscar(string $termo): array
    {
        return Database::fetchAll(
            "SELECT * FROM categorias WHERE nome LIKE ? ORDER BY nome",
            ["%{$termo}%"]
        );
    }

    /**
     * Criar nova categoria
     */
    public static function criar(string $nome): int
    {
        // Validar nome vazio
        if (empty(trim($nome))) {
            throw new \Exception("Nome da categoria é obrigatório!");
        }

        // Validar se já existe
        if (self::findByNome($nome)) {
            throw new \Exception("Categoria '{$nome}' já existe!");
        }

        return self::create(['nome' => trim($nome)]);
    }

    /**
     * Atualizar categoria
     */
    public static function atualizar(int $id, string $nome): bool
    {
        // Validar se já existe (exceto a própria categoria)
        $existing = self::findByNome($nome);
        if ($existing && $existing['id'] != $id) {
            throw new \Exception("Categoria '{$nome}' já existe!");
        }

        return self::update($id, ['nome' => trim($nome)]);
    }

    /**
     * Verificar se categoria pode ser deletada (não tem produtos)
     */
    public static function podeDeletar(int $id): bool
    {
        $produtos = Database::fetchOne(
            "SELECT COUNT(*) as total FROM produtos WHERE categoria_id = ?",
            [$id]
        );

        return (int) $produtos['total'] === 0;
    }

    /**
     * Deletar categoria (apenas se não tiver produtos)
     */
    public static function deletar(int $id): bool
    {
        if (!self::podeDeletar($id)) {
            throw new \Exception("Não é possível deletar categoria que possui produtos!");
        }

        return self::delete($id);
    }

    /**
     * Buscar produtos de uma categoria
     */
    public static function getProdutos(int $categoriaId): array
    {
        return Database::fetchAll(
            "SELECT * FROM produtos WHERE categoria_id = ? ORDER BY nome",
            [$categoriaId]
        );
    }
}
