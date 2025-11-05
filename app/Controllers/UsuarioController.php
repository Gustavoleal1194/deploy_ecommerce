<?php
namespace App\Controllers;

use App\Views\Render;
use App\Models\Usuario;
use App\Database;

class UsuarioController
{
    // funções de views
    public function index(): string
    {
        $title = "Usuários";
        $usuarios = Usuario::all();
        return (new Render())->render(
            'usuarios/index',
            compact('title', 'usuarios')
        );
    }

    public function criar(): string
    {
        $title = "Novo Usuário";
        return (new Render())->render(
            'usuarios/criar',
            compact('title')
        );
    }

    public function ver(int $id): string
    {
        $title = 'Ver Usuário';
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            header('Location: /aula_php/aula7/usuarios?erro=' . urlencode("Usuário não encontrado!"));
            exit;
        }
        
        // Não mostrar senha
        unset($usuario['senha']);
        
        return (new Render())->render(
            'usuarios/ver',
            compact('title', 'usuario')
        );
    }

    public function editar(int $id): string
    {
        $title = 'Editar Usuário';
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            header('Location: /aula_php/aula7/usuarios?erro=' . urlencode("Usuário não encontrado!"));
            exit;
        }
        
        // Não mostrar senha
        unset($usuario['senha']);
        
        return (new Render())->render(
            'usuarios/editar',
            compact('title', 'usuario')
        );
    }

    // funções de apis
    public function list()
    {
        header('Content-Type: application/json; charset=utf-8');
        $usuarios = Usuario::all();
        return json_encode($usuarios);
    }

    public function detalhesApi(int $id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            http_response_code(404);
            return json_encode(['erro' => 'Usuário não encontrado']);
        }
        
        // Não retornar senha
        unset($usuario['senha']);
        
        return json_encode($usuario);
    }

    public function create()
    {
        if (isset($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['tipo'])) {
            try {
                Usuario::criar(
                    $_POST['nome'],
                    $_POST['email'],
                    $_POST['senha'],
                    $_POST['tipo']
                );
                header('Location: /aula_php/aula7/usuarios?mensagem=' . urlencode("Usuário criado com sucesso!"));
                exit;
            } catch (\Exception $e) {
                header('Location: /aula_php/aula7/usuarios/criar?erro=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /aula_php/aula7/usuarios/criar?erro=' . urlencode("Dados incompletos!"));
            exit;
        }
    }

    public function update(int $id)
    {
        if (isset($_POST['nome'], $_POST['email'], $_POST['tipo'])) {
            try {
                $senha = !empty($_POST['senha']) ? $_POST['senha'] : null;
                Usuario::atualizar($id, $_POST['nome'], $_POST['email'], $senha);
                header('Location: /aula_php/aula7/usuarios?mensagem=' . urlencode("Usuário atualizado com sucesso!"));
                exit;
            } catch (\Exception $e) {
                header('Location: /aula_php/aula7/usuarios/editar?id=' . $id . '&erro=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /aula_php/aula7/usuarios/editar?id=' . $id . '&erro=' . urlencode("Dados incompletos!"));
            exit;
        }
    }

    public function delete(int $id)
    {
        try {
            // Não permitir deletar o próprio usuário logado
            $usuarioLogado = \App\Controllers\AuthController::usuarioLogado();
            if ($usuarioLogado && $usuarioLogado['id'] == $id) {
                header('Location: /aula_php/aula7/usuarios?erro=' . urlencode("Você não pode deletar seu próprio usuário!"));
                exit;
            }
            
            Usuario::delete($id);
            header('Location: /aula_php/aula7/usuarios?mensagem=' . urlencode("Usuário removido com sucesso!"));
            exit;
        } catch (\Exception $e) {
            header('Location: /aula_php/aula7/usuarios?erro=' . urlencode($e->getMessage()));
            exit;
        }
    }

    public function buscar()
    {
        $termo = $_GET['nome'] ?? '';
        $usuarios = Database::fetchAll(
            "SELECT id, nome, email, tipo, created_at, updated_at FROM usuarios 
             WHERE nome LIKE ? OR email LIKE ? ORDER BY nome",
            ["%{$termo}%", "%{$termo}%"]
        );
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($usuarios);
    }

    public function resultadosBusca(array $usuarios, string $termo): string
    {
        $title = "Resultados da Busca: " . htmlspecialchars($termo);
        $busca = $termo;
        
        return (new Render())->render(
            'usuarios/resultados_busca',
            compact('title', 'usuarios', 'busca')
        );
    }
}
