<?php include __DIR__ . '/../header.php'; ?>

<h1>Editar Categoria</h1>

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

<form method="POST" action="/aula_php/aula7/api/categorias/editar">
    <input type="hidden" name="id" value="<?= $categoria['id'] ?>">

    <div>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($categoria['nome']) ?>" required>
    </div>

    <div>
        <button type="submit">Atualizar</button>
        <a href="/aula_php/aula7/categorias">Cancelar</a>
    </div>
</form>