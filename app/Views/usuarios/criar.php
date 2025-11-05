<?php include __DIR__ . '/../header.php'; ?>

<h1>Novo Usuário</h1>

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

<form method="POST" action="/aula_php/aula7/api/usuarios">
    <div style="margin-bottom: 15px;">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required style="width: 300px; padding: 8px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required style="width: 300px; padding: 8px;">
    </div>

    <div style="margin-bottom: 15px;">
        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required minlength="6" style="width: 300px; padding: 8px;">
        <small style="color: #666;">Mínimo 6 caracteres</small>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="tipo">Tipo:</label><br>
        <select id="tipo" name="tipo" required style="width: 300px; padding: 8px;">
            <option value="usuario">Usuário</option>
            <option value="admin">Administrador</option>
        </select>
    </div>

    <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">Criar Usuário</button>
    <a href="/aula_php/aula7/usuarios" style="margin-left: 10px;">Cancelar</a>
</form>
