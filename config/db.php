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

return [
    'host' => $env['DB_HOST'] ?? 'localhost',
    'dbname' => $env['DB_NAME'] ?? 'aula_php_mvc',
    'user' => $env['DB_USER'] ?? 'root',
    'pass' => $env['DB_PASS'] ?? '',
    'charset' => $env['DB_CHARSET'] ?? 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
