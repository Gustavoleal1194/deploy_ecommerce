<?php

namespace App\Models;

use App\Database;

class Usuario extends Model
{
    protected static string $table = 'usuarios';
    protected static string $primaryKey = 'id';
    
    /**
     * Buscar usuário por email
     */
    public static function findByEmail(string $email): array|false
    {
        return Database::fetchOne(
            "SELECT * FROM usuarios WHERE email = ?",
            [trim($email)]
        );
    }
    
    /**
     * Verificar credenciais de login
     */
    public static function verificarCredenciais(string $email, string $senha): array|false
    {
        $usuario = self::findByEmail($email);
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        
        return false;
    }
    
    /**
     * Criar novo usuário
     */
    public static function criar(string $nome, string $email, string $senha, string $tipo = 'usuario'): int
    {
        // Validar dados
        if (empty(trim($nome))) {
            throw new \Exception("Nome é obrigatório!");
        }
        
        if (empty(trim($email)) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email inválido!");
        }
        
        if (empty($senha) || strlen($senha) < 6) {
            throw new \Exception("Senha deve ter no mínimo 6 caracteres!");
        }
        
        // Verificar se email já existe
        if (self::findByEmail($email)) {
            throw new \Exception("Email já cadastrado!");
        }
        
        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        
        return self::create([
            'nome' => trim($nome),
            'email' => trim($email),
            'senha' => $senhaHash,
            'tipo' => $tipo
        ]);
    }
    
    /**
     * Atualizar usuário
     */
    public static function atualizar(int $id, string $nome, string $email, ?string $senha = null): bool
    {
        $data = [
            'nome' => trim($nome),
            'email' => trim($email)
        ];
        
        if ($senha !== null) {
            if (strlen($senha) < 6) {
                throw new \Exception("Senha deve ter no mínimo 6 caracteres!");
            }
            $data['senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }
        
        // Verificar se email já existe (exceto o próprio usuário)
        $existing = self::findByEmail($email);
        if ($existing && $existing['id'] != $id) {
            throw new \Exception("Email já cadastrado!");
        }
        
        return self::update($id, $data);
    }
    
    /**
     * Buscar todos os usuários
     */
    public static function all(): array
    {
        return Database::fetchAll("SELECT id, nome, email, tipo, created_at, updated_at FROM usuarios ORDER BY nome");
    }
}
