# Script para gerar senhas seguras para o Cloud-Init
# Execute: powershell -ExecutionPolicy Bypass -File gerar-senhas.ps1

Write-Host ""
Write-Host "=== GERADOR DE SENHAS PARA CLOUD-INIT ===" -ForegroundColor Green
Write-Host ""

# Gerar senha para usuário deploy (16 caracteres)
$caracteres = (65..90) + (97..122) + (48..57)
$senhaDeploy = -join ($caracteres | Get-Random -Count 16 | ForEach-Object {[char]$_})

# Gerar senha para MySQL root (20 caracteres com símbolos)
$caracteresCompleto = (65..90) + (97..122) + (48..57) + (33..47) + (58..64)
$senhaRoot = -join ($caracteresCompleto | Get-Random -Count 20 | ForEach-Object {[char]$_})

# Gerar senha para MySQL app_user (20 caracteres com símbolos)
$senhaApp = -join ($caracteresCompleto | Get-Random -Count 20 | ForEach-Object {[char]$_})

Write-Host "1. SENHA para usuário DEPLOY (16 caracteres):" -ForegroundColor Cyan
Write-Host "   $senhaDeploy" -ForegroundColor White
Write-Host ""

Write-Host "2. SENHA para MySQL ROOT (20 caracteres):" -ForegroundColor Cyan
Write-Host "   $senhaRoot" -ForegroundColor White
Write-Host ""

Write-Host "3. SENHA para MySQL APP_USER (20 caracteres):" -ForegroundColor Cyan
Write-Host "   $senhaApp" -ForegroundColor White
Write-Host ""

Write-Host "=== COMO USAR ===" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. ANOTE estas senhas em local seguro!" -ForegroundColor Red
Write-Host ""
Write-Host "2. No arquivo COLAR_NO_PAINEL.txt, substitua:" -ForegroundColor White
Write-Host "   - root_password_here -> $senhaRoot" -ForegroundColor Gray
Write-Host "   - senha_forte_db_aqui -> $senhaApp" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Para a senha do deploy, você pode:" -ForegroundColor White
Write-Host "   - Deixar vazio (passwd: """")" -ForegroundColor Gray
Write-Host "   - Configurar depois via SSH: passwd" -ForegroundColor Gray
Write-Host ""
Write-Host "Veja COMO_GERAR_SENHAS.md para mais detalhes!" -ForegroundColor Yellow
Write-Host ""

# Salvar em arquivo (opcional)
$senhas = @"
=== SENHAS GERADAS ===
Data: $(Get-Date -Format "yyyy-MM-dd HH:mm:ss")

1. Usuário deploy: $senhaDeploy
2. MySQL root: $senhaRoot
3. MySQL app_user: $senhaApp

GUARDE ESTE ARQUIVO EM LOCAL SEGURO!
"@

$senhas | Out-File -FilePath "senhas-geradas.txt" -Encoding UTF8
Write-Host "✅ Senhas também salvas em: senhas-geradas.txt" -ForegroundColor Green
Write-Host ""

