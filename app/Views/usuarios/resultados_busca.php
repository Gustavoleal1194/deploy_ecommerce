<?php include __DIR__ . '/../header.php'; ?>

<h1>Resultados da Busca: "<?= htmlspecialchars($busca) ?>"</h1>

<p>Encontrados <?= count($usuarios) ?> usuário(s):</p>

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

            <a href="/usuarios/editar?id=<?= $usuario['id'] ?>">Editar</a>
            <a href="/usuarios" style="margin-left: 10px;">Voltar</a>

            <hr>
        </li>
    <?php endforeach; ?>
</ul>
