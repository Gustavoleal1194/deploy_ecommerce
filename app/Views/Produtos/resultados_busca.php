<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Resultados da Busca</title>
</head>

<body>

    <?php include __DIR__ . '/../header.php'; ?>

    <h1>Resultados da Busca: "<?= htmlspecialchars($termo) ?>"</h1>

    <p>Encontrados <?= count($produtos) ?> produto(s):</p>

    <a href="/aula_php/aula7/produtos">← Voltar para Produtos</a>

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
                <h3>
                    <a href="/aula_php/aula7/produtos/ver?id=<?= $item['id'] ?>">
                        <?= htmlspecialchars($item['nome']) ?>
                    </a>
                </h3>
                <p>Preço: R$ <?= number_format($item['preco'], 2, ',', '.') ?></p>

                <?php
                $categoria = array_values(array_filter($categorias, fn($cat) => $cat['id'] == $item['categoria_id']))[0] ?? null;
                if ($categoria):
                    ?>
                    <p>Categoria:
                        <a href="/aula_php/aula7/categorias/ver?id=<?= $categoria['id'] ?>">
                            <?= htmlspecialchars($categoria['nome']) ?>
                        </a>
                    </p>
                <?php endif; ?>

                <a href="/aula_php/aula7/produtos/editar?id=<?= $item['id'] ?>">Editar</a>
                <a href="/aula_php/aula7/produtos/ver?id=<?= $item['id'] ?>">Ver Detalhes</a>

                <hr>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>