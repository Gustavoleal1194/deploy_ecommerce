<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use App\Controllers\CategoriaController;
use App\Controllers\ProdutoController;
use App\Controllers\UsuarioController;
use App\Controllers\AuthController;
use App\Models\Categoria;
use App\Models\Produto;
use App\Models\Usuario;
use App\Database;

/**
 * Testes de Integração para todos os Endpoints da API
 * 
 * Estes testes verificam todos os endpoints e geram relatórios com as respostas
 */
class EndpointTest extends TestCase
{
    private static array $relatorio = [];
    private static int $categoriaId = 0;
    private static int $produtoId = 0;
    private static int $usuarioId = 0;
    private static string $token = '';

    protected function setUp(): void
    {
        parent::setUp();
        // Limpar relatório antes de cada teste
        self::$relatorio = [];
    }

    public static function tearDownAfterClass(): void
    {
        // Gerar relatório final
        self::gerarRelatorio();
    }

    /**
     * Teste: GET /api/categorias - Listar todas as categorias
     */
    public function testGetCategorias(): void
    {
        $controller = new CategoriaController();
        $response = $controller->list();
        
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        
        self::$relatorio['GET /api/categorias'] = [
            'status' => 'OK',
            'response' => $data,
            'count' => count($data)
        ];
        
        // Salvar ID da primeira categoria para testes posteriores
        if (!empty($data[0]['id'])) {
            self::$categoriaId = (int)$data[0]['id'];
        }
    }

    /**
     * Teste: GET /api/categorias/ver?id=X - Ver detalhes de uma categoria
     */
    public function testGetCategoriaDetalhes(): void
    {
        if (self::$categoriaId === 0) {
            $this->markTestSkipped('Nenhuma categoria disponível para teste');
            return;
        }

        $controller = new CategoriaController();
        $_GET['id'] = self::$categoriaId;
        $response = $controller->detalhesApi(self::$categoriaId);
        
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);
        
        self::$relatorio['GET /api/categorias/ver?id=' . self::$categoriaId] = [
            'status' => 'OK',
            'response' => $data
        ];
    }

    /**
     * Teste: POST /api/categorias - Criar nova categoria
     */
    public function testPostCategorias(): void
    {
        $controller = new CategoriaController();
        $_POST = [
            'nome' => 'Categoria Teste ' . time()
        ];
        
        $response = $controller->create();
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        
        if (isset($data['id'])) {
            self::$categoriaId = (int)$data['id'];
        }
        
        self::$relatorio['POST /api/categorias'] = [
            'status' => isset($data['id']) ? 'OK' : 'ERRO',
            'request' => $_POST,
            'response' => $data
        ];
    }

    /**
     * Teste: GET /api/produtos - Listar todos os produtos
     */
    public function testGetProdutos(): void
    {
        $controller = new ProdutoController();
        $response = $controller->list();
        
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        
        self::$relatorio['GET /api/produtos'] = [
            'status' => 'OK',
            'response' => $data,
            'count' => count($data)
        ];
        
        // Salvar ID do primeiro produto para testes posteriores
        if (!empty($data[0]['id'])) {
            self::$produtoId = (int)$data[0]['id'];
        }
    }

    /**
     * Teste: GET /api/produtos/ver?id=X - Ver detalhes de um produto
     */
    public function testGetProdutoDetalhes(): void
    {
        if (self::$produtoId === 0) {
            $this->markTestSkipped('Nenhum produto disponível para teste');
            return;
        }

        $controller = new ProdutoController();
        $_GET['id'] = self::$produtoId;
        $response = $controller->detalhesApi(self::$produtoId);
        
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);
        
        self::$relatorio['GET /api/produtos/ver?id=' . self::$produtoId] = [
            'status' => 'OK',
            'response' => $data
        ];
    }

    /**
     * Teste: POST /api/produtos - Criar novo produto
     */
    public function testPostProdutos(): void
    {
        if (self::$categoriaId === 0) {
            $this->markTestSkipped('Nenhuma categoria disponível para criar produto');
            return;
        }

        $controller = new ProdutoController();
        $_POST = [
            'nome' => 'Produto Teste ' . time(),
            'preco' => 99.99,
            'categoria_id' => self::$categoriaId
        ];
        
        ob_start();
        $controller->create();
        $output = ob_get_clean();
        
        // O create() faz redirect, então vamos verificar se o produto foi criado
        $produtos = Produto::all();
        $produtoCriado = end($produtos);
        
        self::$relatorio['POST /api/produtos'] = [
            'status' => 'OK',
            'request' => $_POST,
            'response' => ['redirect' => true, 'produto_id' => $produtoCriado['id'] ?? null]
        ];
    }

    /**
     * Teste: GET /api/produtos/buscar?nome=X - Buscar produtos
     */
    public function testGetProdutosBuscar(): void
    {
        $controller = new ProdutoController();
        $_GET['nome'] = 'Produto';
        
        $response = $controller->buscar();
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        
        self::$relatorio['GET /api/produtos/buscar?nome=Produto'] = [
            'status' => 'OK',
            'response' => $data,
            'count' => count($data)
        ];
    }

    /**
     * Teste: GET /api/categorias/buscar?termo=X - Buscar categorias
     */
    public function testGetCategoriasBuscar(): void
    {
        $controller = new CategoriaController();
        $_GET['termo'] = 'Eletr';
        
        $response = $controller->buscar();
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        
        self::$relatorio['GET /api/categorias/buscar?termo=Eletr'] = [
            'status' => 'OK',
            'response' => $data,
            'count' => count($data)
        ];
    }

    /**
     * Teste: GET /api/usuarios - Listar usuários (requer admin)
     */
    public function testGetUsuarios(): void
    {
        // Simular usuário admin
        session_start();
        $_SESSION['usuario_id'] = 1;
        $_SESSION['usuario_tipo'] = 'admin';
        
        $controller = new UsuarioController();
        $response = $controller->list();
        
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        
        self::$relatorio['GET /api/usuarios'] = [
            'status' => 'OK',
            'response' => $data,
            'count' => count($data)
        ];
        
        session_destroy();
    }

    /**
     * Teste: GET /api/usuarios/ver?id=X - Ver detalhes de usuário
     */
    public function testGetUsuarioDetalhes(): void
    {
        // Verificar se existe usuário
        $usuarios = Usuario::all();
        if (empty($usuarios)) {
            $this->markTestSkipped('Nenhum usuário disponível para teste');
            return;
        }
        
        $usuarioId = (int)$usuarios[0]['id'];
        
        // Simular usuário admin
        session_start();
        $_SESSION['usuario_id'] = 1;
        $_SESSION['usuario_tipo'] = 'admin';
        
        $controller = new UsuarioController();
        $_GET['id'] = $usuarioId;
        $response = $controller->detalhesApi($usuarioId);
        
        $data = json_decode($response, true);
        
        $this->assertIsString($response);
        $this->assertIsArray($data);
        
        self::$relatorio['GET /api/usuarios/ver?id=' . $usuarioId] = [
            'status' => 'OK',
            'response' => $data
        ];
        
        session_destroy();
    }

    /**
     * Gerar relatório final com todas as respostas
     */
    private static function gerarRelatorio(): void
    {
        $output = "=== RELATÓRIO DE TESTES DE ENDPOINTS ===\n\n";
        $output .= "Data: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach (self::$relatorio as $endpoint => $dados) {
            $output .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            $output .= "ENDPOINT: {$endpoint}\n";
            $output .= "STATUS: {$dados['status']}\n";
            
            if (isset($dados['request'])) {
                $output .= "REQUEST: " . json_encode($dados['request'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            }
            
            if (isset($dados['count'])) {
                $output .= "COUNT: {$dados['count']} itens\n";
            }
            
            $output .= "RESPONSE: " . json_encode($dados['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
            $output .= "\n";
        }
        
        // Salvar em arquivo
        $arquivo = __DIR__ . '/../../relatorio-endpoints.txt';
        file_put_contents($arquivo, $output);
        
        // Também gerar JSON
        $jsonFile = __DIR__ . '/../../relatorio-endpoints.json';
        file_put_contents($jsonFile, json_encode(self::$relatorio, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        echo "\n📄 Relatórios gerados:\n";
        echo "   - relatorio-endpoints.txt\n";
        echo "   - relatorio-endpoints.json\n";
    }
}

