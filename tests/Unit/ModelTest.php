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

    public function testCategoriaValidationNomeVazio(): void
    {
        // Teste: tentar criar categoria com nome vazio deve lançar exceção
        $this->expectException(\Exception::class);
        Categoria::criar('');
    }

    public function testProdutoValidationPrecoInvalido(): void
    {
        // Teste: preço inválido deve lançar exceção
        $this->expectException(\Exception::class);
        Produto::criar('Produto Teste', -100, 1);
    }

    public function testProdutoValidationNomeVazio(): void
    {
        // Teste: nome vazio deve lançar exceção
        $this->expectException(\Exception::class);
        Produto::criar('', 100.50, 1);
    }
}

