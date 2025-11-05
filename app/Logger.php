<?php

namespace App;

/**
 * Classe simples para logging estruturado
 */
class Logger
{
    private static string $logFile = 'logs/app.log';
    private static array $levels = [
        'DEBUG' => 0,
        'INFO' => 1,
        'WARNING' => 2,
        'ERROR' => 3,
        'CRITICAL' => 4
    ];

    /**
     * Garante que o diretório de logs existe
     */
    private static function ensureLogDirectory(): void
    {
        $logDir = dirname(self::$logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    /**
     * Registra uma mensagem no log
     */
    private static function log(string $level, string $message, array $context = []): void
    {
        self::ensureLogDirectory();

        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        $logMessage = "[{$timestamp}] [{$level}] {$message}{$contextStr}" . PHP_EOL;

        file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }

    /**
     * Log de debug
     */
    public static function debug(string $message, array $context = []): void
    {
        self::log('DEBUG', $message, $context);
    }

    /**
     * Log de informação
     */
    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    /**
     * Log de warning
     */
    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    /**
     * Log de erro
     */
    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }

    /**
     * Log crítico
     */
    public static function critical(string $message, array $context = []): void
    {
        self::log('CRITICAL', $message, $context);
    }

    /**
     * Registra métricas de performance
     */
    public static function metric(string $name, float $value, string $unit = 'ms'): void
    {
        self::info("Metric: {$name}", [
            'value' => $value,
            'unit' => $unit,
            'type' => 'metric'
        ]);
    }

    /**
     * Registra requisição HTTP
     */
    public static function request(string $method, string $uri, int $statusCode, float $duration): void
    {
        self::info("HTTP Request", [
            'method' => $method,
            'uri' => $uri,
            'status_code' => $statusCode,
            'duration_ms' => $duration * 1000,
            'type' => 'http_request'
        ]);
    }
}

