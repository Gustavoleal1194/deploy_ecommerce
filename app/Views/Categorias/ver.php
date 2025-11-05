<?php include __DIR__ . '/../header.php'; ?>

<div>
    <?php if (!$categoria): ?>
        <p>Categoria não encontrada.</p>
    <?php else: ?>
        <h2>Categoria #<?= $categoria['id'] ?></h2>
        <p>Nome: <?= htmlspecialchars($categoria['nome']) ?></p>

        <a href="/aula_php/aula7/categorias/editar?id=<?= $categoria['id'] ?>">Editar</a>
        <a href="/aula_php/aula7/categorias">Voltar</a>

        <hr>

        <h3>Produtos desta categoria:</h3>
        <?php
        $produtosController = new \App\Controllers\ProdutoController();
        $produtos = $produtosController->getProdutosByCategoriaId($categoria['id']);
        ?>

        <?php if (empty($produtos)): ?>
            <p>Nenhum produto encontrado nesta categoria.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($produtos as $produto): ?>
                    <li>
                        <a href="/aula_php/aula7/produtos/ver?id=<?= $produto['id'] ?>">
                            <?= htmlspecialchars($produto['nome']) ?>
                        </a>
                        - R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</div>