<?php

namespace App\Models;

use App\Database;

class Produto extends Model
{
    protected static string $table = 'produtos';
    protected static string $primaryKey = 'id';

    /**
     * Buscar todos os produtos com informações da categoria
     */
    public static function all(): array
    {
        return Database::fetchAll(
            "SELECT p.*, c.nome as categoria_nome 
             FROM produtos p 
             LEFT JOIN categorias c ON p.categoria_id = c.id 
             ORDER BY p.nome"
        );
    }

    /**
     * Buscar produto por ID com informações da categoria
     */
    public static function find(int $id): array|false
    {
        return Database::fetchOne(
            "SELECT p.*, c.nome as categoria_nome 
             FROM produtos p 
             LEFT JOIN categorias c ON p.categoria_id = c.id 
             WHERE p.id = ?",
            [$id]
        );
    }

    /**
     * Buscar produtos por categoria
     */
    public static function findByCategoria(int $categoriaId): array
    {
        return Database::fetchAll(
            "SELECT p.*, c.nome as categoria_nome 
             FROM produtos p 
             LEFT JOIN categorias c ON p.categoria_id = c.id 
             WHERE p.categoria_id = ? 
             ORDER BY p.nome",
            [$categoriaId]
        );
    }

    /**
     * Buscar produtos com busca por nome
     */
    public static function buscar(string $termo): array
    {
        return Database::fetchAll(
            "SELECT p.*, c.nome as categoria_nome 
             FROM produtos p 
             LEFT JOIN categorias c ON p.categoria_id = c.id 
             WHERE p.nome LIKE ? 
             ORDER BY p.nome",
            ["%{$termo}%"]
        );
    }

    /**
     * Criar novo produto
     */
    public static function criar(string $nome, float $preco, int $categoriaId): int
    {
        // Validar dados
        if (empty(trim($nome))) {
            throw new \Exception("Nome do produto é obrigatório!");
        }

        if ($preco <= 0) {
            throw new \Exception("Preço deve ser maior que zero!");
        }

        // Verificar se categoria existe
        if (!Categoria::exists($categoriaId)) {
            throw new \Exception("Categoria não encontrada!");
        }

        return self::create([
            'nome' => trim($nome),
            'preco' => $preco,
            'categoria_id' => $categoriaId
        ]);
    }

    /**
     * Atualizar produto
     */
    public static function atualizar(int $id, string $nome, float $preco, int $categoriaId): bool
    {
        // Validar dados
        if (empty(trim($nome))) {
            throw new \Exception("Nome do produto é obrigatório!");
        }

        if ($preco <= 0) {
            throw new \Exception("Preço deve ser maior que zero!");
        }

        // Verificar se categoria existe
        if (!Categoria::exists($categoriaId)) {
            throw new \Exception("Categoria não encontrada!");
        }

        return self::update($id, [
            'nome' => trim($nome),
            'preco' => $preco,
            'categoria_id' => $categoriaId
        ]);
    }

    /**
     * Deletar produto
     */
    public static function deletar(int $id): bool
    {
        return self::delete($id);
    }

    /**
     * Estatísticas dos produtos
     */
    public static function getEstatisticas(): array
    {
        $total = Database::fetchOne("SELECT COUNT(*) as total FROM produtos")['total'];
        $valorTotal = Database::fetchOne("SELECT SUM(preco) as total FROM produtos")['total'] ?? 0;
        $precoMedio = Database::fetchOne("SELECT AVG(preco) as media FROM produtos")['media'] ?? 0;

        return [
            'total_produtos' => (int) $total,
            'valor_total' => (float) $valorTotal,
            'preco_medio' => (float) $precoMedio
        ];
    }

    /**
     * Produtos por categoria (estatísticas)
     */
    public static function getProdutosPorCategoria(): array
    {
        return Database::fetchAll(
            "SELECT c.nome as categoria, COUNT(p.id) as quantidade, SUM(p.preco) as valor_total
             FROM categorias c 
             LEFT JOIN produtos p ON c.id = p.categoria_id 
             GROUP BY c.id, c.nome 
             ORDER BY quantidade DESC"
        );
    }
}
