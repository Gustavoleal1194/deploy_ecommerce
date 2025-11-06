<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Usuario;

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

    public function testUsuarioModelExists(): void
    {
        $this->assertTrue(class_exists(Usuario::class));
    }

    public function testUsuarioValidationNomeVazio(): void
    {
        // Teste: nome vazio deve lançar exceção
        $this->expectException(\Exception::class);
        Usuario::criar('', 'teste@teste.com', '123456');
    }

    public function testUsuarioValidationEmailInvalido(): void
    {
        // Teste: email inválido deve lançar exceção
        $this->expectException(\Exception::class);
        Usuario::criar('Usuario Teste', 'email-invalido', '123456');
    }

    public function testUsuarioValidationSenhaCurta(): void
    {
        // Teste: senha menor que 6 caracteres deve lançar exceção
        $this->expectException(\Exception::class);
        Usuario::criar('Usuario Teste', 'teste@teste.com', '12345');
    }
}

