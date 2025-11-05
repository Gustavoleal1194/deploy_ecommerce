<?php
namespace App\Controllers;

use App\Views\Render;
use App\Models\Produto;
use App\Models\Categoria;

class ProdutoController
{
    public function index(): string
    {
        $title = "produtos";
        $produtos = Produto::all();
        $categorias = Categoria::all();

        return (new Render())->render(
            'produtos/index',
            compact('title', 'produtos', 'categorias')
        );
    }

    public function criar(): string
    {
        $title = "Novo Produto";
        $categorias = Categoria::all();
        return (new Render())->render(
            'produtos/criar',
            compact('title', 'categorias')
        );
    }
    public function ver(int $id): string
    {
        $title = 'Ver produto';
        $produto = Produto::find($id);

        if (!$produto) {
            header('Location: /aula_php/aula7/produtos?erro=' . urlencode("Produto não encontrado!"));
            exit;
        }

        return (new Render())->render(
            'produtos/ver',
            compact('title', 'produto')
        );
    }

    public function list()
    {
        header('Content-Type: application/json; charset=utf-8');
        $produtos = Produto::all();
        return json_encode($produtos);
    }

    public function detalhesApi(int $id)
    {
        header('Content-Type: application/json; charset=utf-8');
        $produto = Produto::find($id);
        
        if (!$produto) {
            http_response_code(404);
            return json_encode(['erro' => 'Produto não encontrado']);
        }
        
        return json_encode($produto);
    }
    public function create()
    {
        if (isset($_POST['nome'], $_POST['preco'], $_POST['categoria_id'])) {
            try {
                Produto::criar($_POST['nome'], (float) $_POST['preco'], (int) $_POST['categoria_id']);
                header('Location: /aula_php/aula7/produtos?mensagem=' . urlencode("Produto adicionado com sucesso!"));
                exit;
            } catch (\Exception $e) {
                header('Location: /aula_php/aula7/produtos/criar?erro=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /aula_php/aula7/produtos/criar?erro=' . urlencode("Dados incompletos!"));
            exit;
        }
    }
    public function delete(int $id)
    {
        try {
            Produto::deletar($id);
            header('Location: /aula_php/aula7/produtos?mensagem=' . urlencode("Produto removido com sucesso!"));
            exit;
        } catch (\Exception $e) {
            header('Location: /aula_php/aula7/produtos?erro=' . urlencode($e->getMessage()));
            exit;
        }
    }
    public function editar(int $id): string
    {
        $title = 'Editar Produto';
        $produto = Produto::find($id);

        if (!$produto) {
            header('Location: /aula_php/aula7/produtos?erro=' . urlencode("Produto não encontrado!"));
            exit;
        }

        $categorias = Categoria::all();
        return (new Render())->render(
            'produtos/editar',
            compact('title', 'produto', 'categorias')
        );
    }

    public function update(int $id)
    {
        if (isset($_POST['nome'], $_POST['preco'], $_POST['categoria_id'])) {
            try {
                Produto::atualizar($id, $_POST['nome'], (float) $_POST['preco'], (int) $_POST['categoria_id']);
                header('Location: /aula_php/aula7/produtos?mensagem=' . urlencode("Produto atualizado com sucesso!"));
                exit;
            } catch (\Exception $e) {
                header('Location: /aula_php/aula7/produtos/editar?id=' . $id . '&erro=' . urlencode($e->getMessage()));
                exit;
            }
        } else {
            header('Location: /aula_php/aula7/produtos/editar?id=' . $id . '&erro=' . urlencode("Dados incompletos!"));
            exit;
        }
    }

    public function getProdutosByCategoriaId(int $categoria_id): array
    {
        return Produto::findByCategoria($categoria_id);
    }
    public function buscar()
    {
        $nome = $_GET['nome'] ?? '';
        $produtos = Produto::buscar($nome);
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($produtos);
    }

    public function resultadosBusca(array $produtos, string $termo): string
    {
        $title = "Resultados da Busca: " . htmlspecialchars($termo);
        $categorias = Categoria::all();

        return (new Render())->render(
            'produtos/resultados_busca',
            compact('title', 'produtos', 'categorias', 'termo')
        );
    }

}