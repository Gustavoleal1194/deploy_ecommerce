<?php
namespace App\Controllers;

use App\Views\Render;
use App\Models\Categoria;

class CategoriaController
{
    // funções de views
    public function index(): string
    {
        $title = "Categorias";
        $categorias = Categoria::all();
        return (new Render())->render(
            'Categorias/index',
            compact('title', 'categorias')
        );
    }

    public function criar(): string
    {
        $title = "Nova Categoria";
        return (new Render())->render(
            'Categorias/criar',
            compact('title')
        );
    }

    public function ver(int $id): string
    {
        $title = 'Ver categoria';
        $categoria = Categoria::find($id);

        if (!$categoria) {
            header('Location: /categorias?erro=' . urlencode("Categoria não encontrada!"));
            exit;
        }

        return (new Render())->render(
            'Categorias/ver',
            compact('title', 'categoria')
        );
    }

    // funções de apis
    public function list()
    {
        //  vamos retornar um json
        header('Content-Type: application/json; charset=utf-8');
        $categorias = Categoria::all();
        return json_encode($categorias);
    }

    public function detalhesApi(int $id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $categoria = Categoria::find($id);
        
        if (!$categoria) {
            http_response_code(404);
            return json_encode(['erro' => 'Categoria não encontrada']);
        }
        
        return json_encode($categoria);
    }
    public function create()
    {
        if (isset($_POST['nome']) && !empty(trim($_POST['nome']))) {
            try {
                Categoria::criar($_POST['nome']);
                header('Location: /categorias?mensagem=' . urlencode("Categoria criada com sucesso!"));
                exit;
            } catch (\Exception $e) {
                header('Location: /categorias/criar?erro=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /categorias/criar?erro=' . urlencode("Nome da categoria é obrigatório!"));
            exit;
        }
    }
    public function delete(int $id)
    {
        try {
            Categoria::deletar($id);
            header('Location: /categorias?mensagem=' . urlencode("Categoria removida com sucesso!"));
            exit;
        } catch (\Exception $e) {
            header('Location: /categorias?erro=' . urlencode($e->getMessage()));
            exit;
        }
    }
    public function editar(int $id): string
    {
        $title = 'Editar Categoria';
        $categoria = Categoria::find($id);

        if (!$categoria) {
            header('Location: /categorias?erro=' . urlencode("Categoria não encontrada!"));
            exit;
        }

        return (new Render())->render(
            'Categorias/editar',
            compact('title', 'categoria')
        );
    }

    public function update(int $id)
    {
        if (isset($_POST['nome']) && !empty(trim($_POST['nome']))) {
            try {
                Categoria::atualizar($id, $_POST['nome']);
                header('Location: /categorias?mensagem=' . urlencode("Categoria atualizada com sucesso!"));
                exit;
            } catch (\Exception $e) {
                header('Location: /categorias/editar?id=' . $id . '&erro=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /categorias/editar?id=' . $id . '&erro=' . urlencode("Nome da categoria é obrigatório!"));
            exit;
        }
    }

    public function buscar()
    {
        $nome = $_GET['nome'] ?? '';
        $categorias = Categoria::buscar($nome);
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($categorias);
    }

    public function getProdutosByCategoriaId(int $categoria_id): array
    {
        return Categoria::getProdutos($categoria_id);
    }

    public function resultadosBusca(array $categorias, string $termo): string
    {
        $title = "Resultados da Busca: " . htmlspecialchars($termo);

        return (new Render())->render(
            'Categorias/resultados_busca',
            compact('title', 'categorias', 'termo')
        );
    }
}