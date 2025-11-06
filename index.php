<?php
// chamando o arquivo config do composer
require_once __DIR__ . '/vendor/autoload.php';

// chamar a class teste
use App\Controllers\ProdutoController;
use App\teste;
use App\Controllers\CategoriaController;
use App\Controllers\AuthController;
use App\Controllers\UsuarioController;
use App\Database;
use App\Models\Produto;
use App\Models\Categoria;
use App\Views\Render;

// Iniciar sessão para autenticação
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Testar conexão com banco de dados
try {
    Database::getInstance();
} catch (Exception $e) {
    die("Erro de conexão com banco de dados: " . $e->getMessage());
}

// instanciar a class
// new teste();

// variavel basedir 
$basedir = "";

// pega URL crua (sem tratamento)
$uri = $_SERVER["REQUEST_URI"] ?? "/";

// tratamento QUERY STRING (ex: ?parametro=valor)
$uri = strtok($uri, "?");

// remove o prefixo  da frente da uri
if (str_starts_with($uri, $basedir)) {
    // remover a string da uri
    $uri = substr($uri, strlen($basedir));
}

// remover a barra final
$uri = rtrim($uri, '/');

// pegar o método da requisição
$metodo = $_SERVER['REQUEST_METHOD'];

// Rotas públicas (não precisam de autenticação)
if ($metodo == 'GET' && $uri === '/login') {
    echo (new AuthController())->login();
    exit;
}

if ($metodo == 'POST' && $uri === '/api/login') {
    (new AuthController())->autenticar();
    exit;
}

if ($metodo == 'GET' && $uri === '/logout') {
    (new AuthController())->logout();
    exit;
}

// Proteger todas as outras rotas
AuthController::protegerRota();

// criação da rotas de views
if ($metodo == 'GET') {
    // rotas de Categorias
    // página de listagem
    if ($uri === '/categorias') {
        echo (new CategoriaController())->index();
        exit;
    }
    // página de criação
    if ($uri === '/categorias/criar') {
        echo (new CategoriaController())->criar();
        exit;
    }
    // página de visualização
    if ($uri === '/categorias/ver') {
        // pegar o parametro fazendo cast de dados
        $id = (int) ($_GET['id'] ?? 0);
        echo (new CategoriaController())->ver($id);
        exit;
    }
    // rotas de Produtos


    if ($uri === '/produtos') {
        echo (new ProdutoController())->index();
        exit;
    }
    // página de criação
    if ($uri === '/produtos/criar') {
        echo (new ProdutoController())->criar();
        exit;
    }
    // página de visualização
    if ($uri === '/produtos/ver') {
        $id = (int) ($_GET['id'] ?? 0);
        echo (new ProdutoController())->ver($id);
        exit;
    }
    
    // rotas de Usuários (apenas para admin)
    if ($uri === '/usuarios') {
        if (!AuthController::ehAdmin()) {
            header('Location: /produtos?erro=' . urlencode("Acesso negado! Apenas administradores."));
            exit;
        }
        echo (new UsuarioController())->index();
        exit;
    }
    
    if ($uri === '/usuarios/criar') {
        if (!AuthController::ehAdmin()) {
            header('Location: /produtos?erro=' . urlencode("Acesso negado! Apenas administradores."));
            exit;
        }
        echo (new UsuarioController())->criar();
        exit;
    }
    
    if ($uri === '/usuarios/ver') {
        if (!AuthController::ehAdmin()) {
            header('Location: /produtos?erro=' . urlencode("Acesso negado! Apenas administradores."));
            exit;
        }
        $id = (int) ($_GET['id'] ?? 0);
        echo (new UsuarioController())->ver($id);
        exit;
    }
    
    if ($uri === '/usuarios/editar') {
        if (!AuthController::ehAdmin()) {
            header('Location: /produtos?erro=' . urlencode("Acesso negado! Apenas administradores."));
            exit;
        }
        $id = (int) ($_GET['id'] ?? 0);
        echo (new UsuarioController())->editar($id);
        exit;
    }

}

// criação das rotas de apis
// api de listar
if ($uri === '/api/categorias' && $metodo == 'GET') {
    echo (new CategoriaController())->list();
    exit;
}

// api de detalhes categoria
if ($uri === '/api/categorias/ver' && $metodo == 'GET') {
    $id = (int) ($_GET['id'] ?? 0);
    echo (new CategoriaController())->detalhesApi($id);
    exit;
}
//api de criar
if ($uri === '/api/categorias' && $metodo == 'POST') {
    echo (new CategoriaController())->create();
    exit;
}
// api de deletar
if ($uri === '/api/categorias/deletar' && $metodo == 'POST') {
    // pegar o paramentro
    $id = (int) ($_POST['id']);
    echo (new CategoriaController())->delete($id);
    // redirecionar a tela
    header('Location: /produtos');
    exit;
}
if ($uri === '/api/produtos' && $metodo == 'GET') {
    echo (new ProdutoController())->list();
    exit;
}

// api de detalhes produto
if ($uri === '/api/produtos/ver' && $metodo == 'GET') {
    $id = (int) ($_GET['id'] ?? 0);
    echo (new ProdutoController())->detalhesApi($id);
    exit;
}
//api de criar
if ($uri === '/api/produtos' && $metodo == 'POST') {
    echo (new ProdutoController())->create();
    exit;
}
// api de deletar
if ($uri === '/api/produtos/deletar' && $metodo == 'POST') {
    // pegar o paramentro
    $id = (int) ($_POST['id']);
    echo (new ProdutoController())->delete($id);
    // redirecionar a tela
    header('Location: /produtos');
    exit;
}

// rotas de API para Usuários (apenas admin)
if ($uri === '/api/usuarios' && $metodo == 'GET') {
    if (!AuthController::ehAdmin()) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['erro' => 'Acesso negado! Apenas administradores.']);
        exit;
    }
    echo (new UsuarioController())->list();
    exit;
}

if ($uri === '/api/usuarios/ver' && $metodo == 'GET') {
    if (!AuthController::ehAdmin()) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['erro' => 'Acesso negado! Apenas administradores.']);
        exit;
    }
    $id = (int) ($_GET['id'] ?? 0);
    echo (new UsuarioController())->detalhesApi($id);
    exit;
}

if ($uri === '/api/usuarios' && $metodo == 'POST') {
    if (!AuthController::ehAdmin()) {
        header('Location: /produtos?erro=' . urlencode("Acesso negado! Apenas administradores."));
        exit;
    }
    echo (new UsuarioController())->create();
    exit;
}

if ($uri === '/api/usuarios/editar' && $metodo == 'POST') {
    if (!AuthController::ehAdmin()) {
        header('Location: /produtos?erro=' . urlencode("Acesso negado! Apenas administradores."));
        exit;
    }
    $id = (int) ($_POST['id']);
    echo (new UsuarioController())->update($id);
    exit;
}

if ($uri === '/api/usuarios/deletar' && $metodo == 'POST') {
    if (!AuthController::ehAdmin()) {
        header('Location: /produtos?erro=' . urlencode("Acesso negado! Apenas administradores."));
        exit;
    }
    $id = (int) ($_POST['id']);
    echo (new UsuarioController())->delete($id);
    exit;
}

if ($uri === '/api/usuarios/buscar' && $metodo == 'GET') {
    if (!AuthController::ehAdmin()) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['erro' => 'Acesso negado! Apenas administradores.']);
        exit;
    }
    echo (new UsuarioController())->buscar();
    exit;
}

// rotas de edição - GET
if ($uri === '/categorias/editar') {
    $id = (int) ($_GET['id'] ?? 0);
    echo (new CategoriaController())->editar($id);
    exit;
}

if ($uri === '/produtos/editar') {
    $id = (int) ($_GET['id'] ?? 0);
    echo (new ProdutoController())->editar($id);
    exit;
}

// rotas de edição - POST
if ($uri === '/api/categorias/editar' && $metodo == 'POST') {
    $id = (int) ($_POST['id']);
    echo (new CategoriaController())->update($id);
    exit;
}

if ($uri === '/api/produtos/editar' && $metodo == 'POST') {
    $id = (int) ($_POST['id']);
    echo (new ProdutoController())->update($id);
    exit;
}

// rotas de busca
if ($uri === '/api/produtos/buscar' && $metodo == 'GET') {
    echo (new ProdutoController())->buscar();
    exit;
}

if ($uri === '/api/categorias/buscar' && $metodo == 'GET') {
    echo (new CategoriaController())->buscar();
    exit;
}

// rotas de busca com redirecionamento
if ($uri === '/produtos/buscar' && $metodo == 'GET') {
    $busca = $_GET['busca'] ?? '';
    $produtos = Produto::buscar($busca);

    if (!empty($produtos)) {
        if (count($produtos) === 1) {
            // Se encontrou apenas um produto, redireciona direto para ele
            header('Location: /produtos/ver?id=' . $produtos[0]['id']);
            exit;
        } else {
            // Se encontrou múltiplos, mostra página de resultados
            echo (new ProdutoController())->resultadosBusca($produtos, $busca);
            exit;
        }
    } else {
        header('Location: /produtos?erro=' . urlencode("Nenhum produto encontrado com o termo: " . htmlspecialchars($busca)));
        exit;
    }
}

if ($uri === '/categorias/buscar' && $metodo == 'GET') {
    $busca = $_GET['busca'] ?? '';
    $categorias = Categoria::buscar($busca);

    if (!empty($categorias)) {
        if (count($categorias) === 1) {
            // Se encontrou apenas uma categoria, redireciona direto para ela
            header('Location: /categorias/ver?id=' . $categorias[0]['id']);
            exit;
        } else {
            // Se encontrou múltiplas, mostra página de resultados
            echo (new CategoriaController())->resultadosBusca($categorias, $busca);
            exit;
        }
    } else {
        header('Location: /categorias?erro=' . urlencode("Nenhuma categoria encontrada com o termo: " . htmlspecialchars($busca)));
        exit;
    }
}

if ($uri === '/usuarios/buscar' && $metodo == 'GET') {
    if (!AuthController::ehAdmin()) {
        header('Location: /produtos?erro=' . urlencode("Acesso negado! Apenas administradores."));
        exit;
    }
    $busca = $_GET['busca'] ?? '';
    $usuarios = Database::fetchAll(
        "SELECT id, nome, email, tipo, created_at, updated_at FROM usuarios 
         WHERE nome LIKE ? OR email LIKE ? ORDER BY nome",
        ["%{$busca}%", "%{$busca}%"]
    );

    if (!empty($usuarios)) {
        if (count($usuarios) === 1) {
            header('Location: /usuarios/ver?id=' . $usuarios[0]['id']);
            exit;
        } else {
            // Mostrar resultados de busca
            echo (new UsuarioController())->resultadosBusca($usuarios, $busca);
            exit;
        }
    } else {
        header('Location: /usuarios?erro=' . urlencode("Nenhum usuário encontrado com o termo: " . htmlspecialchars($busca)));
        exit;
    }
}

// APIs JSON para Categorias
if ($uri === '/api/categorias' && $metodo == 'PUT') {
    $id = (int) ($_GET['id'] ?? 0);
    echo (new CategoriaController())->update($id);
    exit;
}

if ($uri === '/api/categorias' && $metodo == 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    echo (new CategoriaController())->delete($id);
    exit;
}

// APIs JSON para Produtos
if ($uri === '/api/produtos' && $metodo == 'PUT') {
    $id = (int) ($_GET['id'] ?? 0);
    echo (new ProdutoController())->update($id);
    exit;
}

if ($uri === '/api/produtos' && $metodo == 'DELETE') {
    $id = (int) ($_GET['id'] ?? 0);
    echo (new ProdutoController())->delete($id);
    exit;
}


?>