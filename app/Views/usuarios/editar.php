<?php include __DIR__ . '/../header.php'; ?>

<h1>Editar Usuário</h1>

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

<?php if (!$usuario): ?>
    <p>Usuário não encontrado.</p>
    <a href="/usuarios">Voltar</a>
<?php else: ?>
    <form method="POST" action="/api/usuarios/editar">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        <div style="margin-bottom: 15px;">
            <label for="nome">Nome:</label><br>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required style="width: 300px; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required style="width: 300px; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="senha">Nova Senha (deixe em branco para não alterar):</label><br>
            <input type="password" id="senha" name="senha" minlength="6" style="width: 300px; padding: 8px;">
            <small style="color: #666;">Mínimo 6 caracteres (opcional)</small>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="tipo">Tipo:</label><br>
            <select id="tipo" name="tipo" required style="width: 300px; padding: 8px;">
                <option value="usuario" <?= $usuario['tipo'] === 'usuario' ? 'selected' : '' ?>>Usuário</option>
                <option value="admin" <?= $usuario['tipo'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
            </select>
        </div>

        <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">Atualizar Usuário</button>
        <a href="/usuarios" style="margin-left: 10px;">Cancelar</a>
    </form>
<?php endif; ?>
