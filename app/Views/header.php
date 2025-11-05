<?php
$usuario = \App\Controllers\AuthController::usuarioLogado();
?>
<!-- Menu de Navegação -->
<div style="background: #f8f9fa; padding: 10px; margin-bottom: 20px; border: 1px solid #dee2e6; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <a href="/aula_php/aula7/categorias" style="margin-right: 15px; <?= strpos($_SERVER['REQUEST_URI'] ?? '', 'categorias') !== false ? 'font-weight: bold; color: #007bff;' : 'color: #6c757d;' ?>">Categorias</a>
        <a href="/aula_php/aula7/produtos" style="margin-right: 15px; <?= strpos($_SERVER['REQUEST_URI'] ?? '', 'produtos') !== false ? 'font-weight: bold; color: #007bff;' : 'color: #6c757d;' ?>">Produtos</a>
        <?php if (\App\Controllers\AuthController::ehAdmin()): ?>
            <a href="/aula_php/aula7/usuarios" style="margin-right: 15px; <?= strpos($_SERVER['REQUEST_URI'] ?? '', 'usuarios') !== false ? 'font-weight: bold; color: #007bff;' : 'color: #6c757d;' ?>">Usuários</a>
        <?php endif; ?>
    </div>
    <div style="display: flex; align-items: center; gap: 15px;">
        <?php if ($usuario): ?>
            <span style="color: #6c757d;">
                Olá, <strong><?= htmlspecialchars($usuario['nome']) ?></strong>
                <?php if ($usuario['tipo'] === 'admin'): ?>
                    <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 3px; font-size: 12px; margin-left: 5px;">Admin</span>
                <?php endif; ?>
            </span>
            <a href="/aula_php/aula7/logout" style="color: #dc3545; text-decoration: none;">Sair</a>
        <?php endif; ?>
    </div>
</div>
