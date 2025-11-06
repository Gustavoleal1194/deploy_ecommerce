# Script para visualizar chave privada SSH
# Use com cuidado - NUNCA compartilhe sua chave privada!

$chavePath = "$env:USERPROFILE\.ssh\id_rsa"

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  VISUALIZADOR DE CHAVE PRIVADA SSH" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

if (Test-Path $chavePath) {
    Write-Host "✅ Chave privada encontrada!" -ForegroundColor Green
    Write-Host "Localização: $chavePath" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "⚠️  ATENÇÃO: Esta é sua chave PRIVADA!" -ForegroundColor Red
    Write-Host "   NUNCA compartilhe com ninguém!" -ForegroundColor Red
    Write-Host ""
    
    $opcao = Read-Host "Deseja visualizar a chave completa? (S/N)"
    
    if ($opcao -eq "S" -or $opcao -eq "s") {
        Write-Host ""
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host "  CONTEÚDO DA CHAVE PRIVADA" -ForegroundColor Cyan
        Write-Host "========================================" -ForegroundColor Cyan
        Write-Host ""
        Get-Content $chavePath
        Write-Host ""
        Write-Host "========================================" -ForegroundColor Cyan
    } else {
        Write-Host "Operação cancelada." -ForegroundColor Yellow
    }
    
    Write-Host ""
    Write-Host "Informações da chave pública:" -ForegroundColor Cyan
    $chavePubPath = "$env:USERPROFILE\.ssh\id_rsa.pub"
    if (Test-Path $chavePubPath) {
        ssh-keygen -lf $chavePubPath 2>$null
    }
} else {
    Write-Host "❌ Chave privada não encontrada em: $chavePath" -ForegroundColor Red
    Write-Host ""
    Write-Host "Deseja gerar uma nova chave SSH?" -ForegroundColor Yellow
    $opcao = Read-Host "Digite S para gerar ou N para cancelar"
    
    if ($opcao -eq "S" -or $opcao -eq "s") {
        $email = Read-Host "Digite seu email para identificar a chave"
        ssh-keygen -t rsa -b 4096 -C $email -f $chavePath
        Write-Host ""
        Write-Host "✅ Chave gerada com sucesso!" -ForegroundColor Green
        Write-Host "Chave pública: $chavePubPath" -ForegroundColor Yellow
    }
}

Write-Host ""
Write-Host "Pressione qualquer tecla para sair..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

