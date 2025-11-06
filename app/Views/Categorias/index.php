<?php include __DIR__ . '/../header.php'; ?>

<h1>Categorias</h1>

<a href="/categorias/criar">Adicionar Nova Categoria</a>

<hr>

<!-- Campo de busca -->
<form method="GET" action="/categorias/buscar">
    <input type="text" name="busca" placeholder="Buscar categorias..."
        value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
    <button type="submit">Buscar</button>
    <a href="/categorias">Limpar</a>
</form>

<hr>

<!-- Mensagens -->
<?php if (isset($_GET['mensagem'])): ?>
    <div style="color: green; background: #d4edda; padding: 10px; border: 1px solid #c3e6cb; margin: 10px 0;">
        <?= htmlspecialchars($_GET['mensagem']) ?>
    </div>
<?php endif; ?>

<?php if (isset($_GET['erro'])): ?>
    <div style="color: red; background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; margin: 10px 0;">
        <?= htmlspecialchars($_GET['erro']) ?>
    </div>
<?php endif; ?>

<ul>
    <?php foreach ($categorias as $item): ?>
        <li>
            <h3>
                <a href="/categorias/ver?id=<?= $item['id'] ?>">
                    <?= htmlspecialchars($item['nome']) ?>
                </a>
            </h3>

            <a href="/categorias/editar?id=<?= $item['id'] ?>">Editar</a>

            <form action="/api/categorias/deletar" method="POST" style="display: inline;">
                <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                <button type="submit">Excluir</button>
            </form>

            <!-- Produtos desta categoria -->
            <?php
            $produtosController = new \App\Controllers\ProdutoController();
            $produtosCategoria = $produtosController->getProdutosByCategoriaId($item['id']);
            ?>

            <?php if (!empty($produtosCategoria)): ?>
                <h4>Produtos desta categoria:</h4>
                <ul style="margin-left: 20px;">
                    <?php foreach ($produtosCategoria as $produto): ?>
                        <li>
                            <a href="/produtos/ver?id=<?= $produto['id'] ?>">
                                <?= htmlspecialchars($produto['nome']) ?>
                            </a>
                            - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color: #666; font-style: italic;">Nenhum produto nesta categoria</p>
            <?php endif; ?>

            <hr>
        </li>
    <?php endforeach; ?>
</ul>