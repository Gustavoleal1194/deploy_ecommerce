<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Novo Produto</title>
</head>

<body>

    <?php include __DIR__ . '/../header.php'; ?>

    <h1>Novo Produto</h1>

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

    <form method="POST" action="/aula_php/aula7/api/produtos">
        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" placeholder="Nome do produto" required />
        </div>

        <div>
            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" placeholder="Ex: 99.90" step="0.01" min="0.01" required />
        </div>

        <div>
            <label for="categoria_id">Categoria:</label>
            <select id="categoria_id" name="categoria_id" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id'] ?>">
                        <?= htmlspecialchars($categoria['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <button type="submit">Salvar</button>
            <a href="/aula_php/aula7/produtos">Cancelar</a>
        </div>
    </form>

</body>

</html>