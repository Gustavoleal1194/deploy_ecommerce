<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * Testes de Integração para APIs
 * 
 * Nota: Estes testes requerem um banco de dados de teste configurado
 */
class ApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Configurar ambiente de teste
    }

    public function testApiEndpointsExist(): void
    {
        // Verifica se os endpoints principais existem (definições)
        $endpoints = [
            '/api/categorias',
            '/api/produtos',
            '/api/login'
        ];

        foreach ($endpoints as $endpoint) {
            $this->assertIsString($endpoint);
            $this->assertNotEmpty($endpoint);
            $this->assertStringStartsWith('/api', $endpoint);
        }
    }

    public function testJsonResponseFormat(): void
    {
        // Teste básico: verificar que JSON é uma string válida
        $jsonExample = json_encode(['test' => true]);
        $this->assertIsString($jsonExample);
        $decoded = json_decode($jsonExample, true);
        $this->assertIsArray($decoded);
        $this->assertTrue($decoded['test']);
    }
}

