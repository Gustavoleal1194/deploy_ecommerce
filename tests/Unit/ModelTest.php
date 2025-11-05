<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Categoria;
use App\Models\Produto;

class ModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Setup de banco de dados de teste se necessário
    }

    public function testCategoriaModelExists(): void
    {
        $this->assertTrue(class_exists(Categoria::class));
    }

    public function testProdutoModelExists(): void
    {
        $this->assertTrue(class_exists(Produto::class));
    }

    public function testCategoriaValidation(): void
    {
        $categoria = new Categoria();

        // Teste: nome vazio deve retornar erro
        $result = $categoria->validate(['nome' => '']);
        $this->assertNotEmpty($result);
        $this->assertStringContainsString('Nome', $result);
    }

    public function testProdutoValidation(): void
    {
        $produto = new Produto();

        // Teste: preço inválido
        $result = $produto->validate([
            'nome' => 'Produto Teste',
            'preco' => -100,
            'categoria_id' => 1
        ]);
        $this->assertNotEmpty($result);

        // Teste: preço válido
        $result = $produto->validate([
            'nome' => 'Produto Teste',
            'preco' => 100.50,
            'categoria_id' => 1
        ]);
        $this->assertEmpty($result);
    }
}

