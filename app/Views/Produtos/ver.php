<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Ver Produto</title>
</head>

<body>

    <?php include __DIR__ . '/../header.php'; ?>

    <?php if (!$produto): ?>
        <h1>Produto não encontrado.</h1>
    <?php else: ?>

        <h1>Produto: <?= htmlspecialchars($produto['nome']) ?></h1>

        <p><strong>ID:</strong> <?= $produto['id'] ?></p>
        <p><strong>Nome:</strong> <?= htmlspecialchars($produto['nome']) ?></p>
        <p><strong>Preço:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
        <p><strong>Categoria:</strong>
            <?php if ($categoria): ?>
                <a href="/aula_php/aula7/categorias/ver?id=<?= $categoria['id'] ?>">
                    <?= htmlspecialchars($categoria['nome']) ?>
                </a>
            <?php else: ?>
                Não informada
            <?php endif; ?>
        </p>

        <hr>

        <a href="/aula_php/aula7/produtos/editar?id=<?= $produto['id'] ?>">Editar Produto</a>
        <a href="/aula_php/aula7/produtos">Voltar para a lista</a>

    <?php endif; ?>

</body>

</html>