<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Produtos</title>
</head>

<body>

    <?php include __DIR__ . '/../header.php'; ?>

    <h1>Produtos</h1>

    <a href="/produtos/criar">Adicionar Novo Produto</a>

    <hr>

    <!-- Campo de busca -->
    <form method="GET" action="/produtos/buscar">
        <input type="text" name="busca" placeholder="Buscar produtos..."
            value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
        <button type="submit">Buscar</button>
        <a href="/produtos">Limpar</a>
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
        <?php foreach ($produtos as $item): ?>
            <li>
                <a href="/produtos/ver?id=<?= $item['id'] ?>">
                    <?= htmlspecialchars($item['nome']) ?>
                </a>
                - R$ <?= number_format($item['preco'], 2, ',', '.') ?>

                <a href="/produtos/editar?id=<?= $item['id'] ?>">Editar</a>

                <form action="/api/produtos/deletar" method="POST" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $item['id'] ?>" />
                    <button type="submit">Excluir</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>