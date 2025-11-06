<?php include __DIR__ . '/../header.php'; ?>

<div>
    <?php if (!$usuario): ?>
        <p>Usuário não encontrado.</p>
        <a href="/usuarios">Voltar</a>
    <?php else: ?>
        <h2>Usuário #<?= $usuario['id'] ?></h2>
        
        <p><strong>Nome:</strong> <?= htmlspecialchars($usuario['nome']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        <p><strong>Tipo:</strong> 
            <span style="background: <?= $usuario['tipo'] === 'admin' ? '#007bff' : '#6c757d' ?>; color: white; padding: 2px 8px; border-radius: 3px;">
                <?= ucfirst($usuario['tipo']) ?>
            </span>
        </p>
        <p><strong>Criado em:</strong> <?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></p>
        <p><strong>Última atualização:</strong> <?= date('d/m/Y H:i', strtotime($usuario['updated_at'])) ?></p>

        <hr>

        <a href="/usuarios/editar?id=<?= $usuario['id'] ?>">Editar</a>
        <a href="/usuarios" style="margin-left: 10px;">Voltar</a>
    <?php endif; ?>
</div>
