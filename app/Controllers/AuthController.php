<?php
namespace App\Controllers;

use App\Views\Render;
use App\Models\Usuario;

class AuthController
{
    /**
     * Exibir página de login
     */
    public function login(): string
    {
        // Se já estiver logado, redireciona
        if ($this->estaAutenticado()) {
            header('Location: /produtos');
            exit;
        }

        $title = "Entrar";
        return (new Render())->render('auth/login', compact('title'));
    }

    /**
     * Processar login
     */
    public function autenticar(): void
    {
        if (!isset($_POST['email']) || !isset($_POST['senha'])) {
            header('Location: /login?erro=' . urlencode("Email e senha são obrigatórios!"));
            exit;
        }

        $email = trim($_POST['email']);
        $senha = $_POST['senha'];

        try {
            $usuario = Usuario::verificarCredenciais($email, $senha);

            if ($usuario) {
                // Iniciar sessão
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_tipo'] = $usuario['tipo'];

                header('Location: /produtos?mensagem=' . urlencode("Login realizado com sucesso!"));
                exit;
            } else {
                header('Location: /login?erro=' . urlencode("Email ou senha incorretos!"));
                exit;
            }
        } catch (\Exception $e) {
            header('Location: /login?erro=' . urlencode($e->getMessage()));
            exit;
        }
    }

    /**
     * Fazer logout
     */
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();

        header('Location: /login?mensagem=' . urlencode("Logout realizado com sucesso!"));
        exit;
    }

    /**
     * Verificar se usuário está autenticado
     */
    public static function estaAutenticado(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Obter usuário logado
     */
    public static function usuarioLogado(): ?array
    {
        if (!self::estaAutenticado()) {
            return null;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return [
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome'],
            'email' => $_SESSION['usuario_email'],
            'tipo' => $_SESSION['usuario_tipo']
        ];
    }

    /**
     * Verificar se é administrador
     */
    public static function ehAdmin(): bool
    {
        $usuario = self::usuarioLogado();
        return $usuario && $usuario['tipo'] === 'admin';
    }

    /**
     * Proteger rota (redireciona para login se não autenticado)
     */
    public static function protegerRota(): void
    {
        if (!self::estaAutenticado()) {
            header('Location: /login?erro=' . urlencode("Faça login para acessar esta página!"));
            exit;
        }
    }
}
