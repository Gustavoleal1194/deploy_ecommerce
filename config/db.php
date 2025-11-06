<?php

// Carregar variáveis do arquivo .env
$envFile = __DIR__ . '/../.env';
$env = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Ignorar comentários
        }
        if (strpos($line, '=') !== false) {
            [$key, $value] = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }
}

// Variáveis de ambiente do sistema têm precedência (útil para CI/CD)
return [
    'host' => getenv('DB_HOST') ?: ($env['DB_HOST'] ?? 'localhost'),
    'dbname' => getenv('DB_NAME') ?: ($env['DB_NAME'] ?? 'aula_php_mvc'),
    'user' => getenv('DB_USER') ?: ($env['DB_USER'] ?? 'root'),
    'pass' => getenv('DB_PASS') ?: ($env['DB_PASS'] ?? ''),
    'charset' => getenv('DB_CHARSET') ?: ($env['DB_CHARSET'] ?? 'utf8mb4'),
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
