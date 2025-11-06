<?php

namespace PHPUnit\Framework;

/**
 * Stub para PHPUnit\Framework\TestCase
 * Este arquivo permite que o linter reconheça a classe PHPUnit
 * sem precisar instalar as dependências localmente
 */
class TestCase
{
    protected function setUp(): void {}
    protected function tearDown(): void {}
    
    public function assertTrue($condition, string $message = ''): void {}
    public function assertFalse($condition, string $message = ''): void {}
    public function assertEquals($expected, $actual, string $message = ''): void {}
    public function assertNotEmpty($actual, string $message = ''): void {}
    public function assertEmpty($actual, string $message = ''): void {}
    public function assertIsString($actual, string $message = ''): void {}
    public function assertIsArray($actual, string $message = ''): void {}
    public function assertStringContainsString(string $needle, string $haystack, string $message = ''): void {}
    public function assertStringStartsWith(string $prefix, string $string, string $message = ''): void {}
    
    public function expectException(string $exception): void {}
}

