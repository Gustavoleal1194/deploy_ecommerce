<?php include __DIR__ . '/../header.php'; ?>

<h1>Usuários</h1>

<a href="/usuarios/criar">Adicionar Novo Usuário</a>

<hr>

<!-- Campo de busca -->
<form method="GET" action="/usuarios/buscar">
    <input type="text" name="busca" placeholder="Buscar usuários..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
    <button type="submit">Buscar</button>
    <a href="/usuarios">Limpar</a>
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
    <?php foreach ($usuarios as $usuario): ?>
        <li>
            <h3>
                <a href="/usuarios/ver?id=<?= $usuario['id'] ?>">
                    <?= htmlspecialchars($usuario['nome']) ?>
                </a>
            </h3>
            
            <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
            <p><strong>Tipo:</strong> 
                <span style="background: <?= $usuario['tipo'] === 'admin' ? '#007bff' : '#6c757d' ?>; color: white; padding: 2px 8px; border-radius: 3px; font-size: 12px;">
                    <?= ucfirst($usuario['tipo']) ?>
                </span>
            </p>
            <p><strong>Criado em:</strong> <?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></p>

            <a href="/usuarios/editar?id=<?= $usuario['id'] ?>">Editar</a>

            <?php
            $usuarioLogado = \App\Controllers\AuthController::usuarioLogado();
            // Não permitir deletar o próprio usuário
            if ($usuarioLogado && $usuarioLogado['id'] != $usuario['id']):
            ?>
                <form action="/api/usuarios/deletar" method="POST" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>" />
                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                </form>
            <?php else: ?>
                <span style="color: #666; font-style: italic;">(Não é possível excluir seu próprio usuário)</span>
            <?php endif; ?>

            <hr>
        </li>
    <?php endforeach; ?>
</ul>
