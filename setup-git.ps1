# Script de inicialização do repositório Git
# Execute este script após instalar o Git

Write-Host "🚀 Inicializando repositório Git para Caxias Tem Turismo" -ForegroundColor Cyan
Write-Host ""

# Verificar se Git está instalado
try {
    $gitVersion = git --version
    Write-Host "✓ Git encontrado: $gitVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ Git não encontrado!" -ForegroundColor Red
    Write-Host "  Instale o Git em: https://git-scm.com/download/win" -ForegroundColor Yellow
    Write-Host "  Depois reinicie o terminal e execute este script novamente." -ForegroundColor Yellow
    exit 1
}

Write-Host ""

# Configurar Git (se ainda não configurado)
$gitUserName = git config --global user.name
$gitUserEmail = git config --global user.email

if ([string]::IsNullOrWhiteSpace($gitUserName) -or [string]::IsNullOrWhiteSpace($gitUserEmail)) {
    Write-Host "⚙️  Configurando Git pela primeira vez..." -ForegroundColor Yellow
    
    $userName = Read-Host "Digite seu nome"
    $userEmail = Read-Host "Digite seu email"
    
    git config --global user.name "$userName"
    git config --global user.email "$userEmail"
    
    Write-Host "✓ Git configurado!" -ForegroundColor Green
} else {
    Write-Host "✓ Git já configurado:" -ForegroundColor Green
    Write-Host "  Nome: $gitUserName" -ForegroundColor Gray
    Write-Host "  Email: $gitUserEmail" -ForegroundColor Gray
}

Write-Host ""

# Inicializar repositório
Write-Host "📁 Inicializando repositório local..." -ForegroundColor Cyan

if (Test-Path ".git") {
    Write-Host "  Repositório Git já existe!" -ForegroundColor Yellow
} else {
    git init
    Write-Host "✓ Repositório inicializado!" -ForegroundColor Green
}

Write-Host ""

# Adicionar arquivos
Write-Host "📦 Adicionando arquivos ao staging..." -ForegroundColor Cyan
git add .
Write-Host "✓ Arquivos adicionados!" -ForegroundColor Green

Write-Host ""

# Commit inicial
Write-Host "💾 Criando commit inicial..." -ForegroundColor Cyan
git commit -m "Initial commit: Site Caxias Tem Turismo"
Write-Host "✓ Commit criado!" -ForegroundColor Green

Write-Host ""
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "✓ Repositório local pronto!" -ForegroundColor Green
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""
Write-Host "📋 PRÓXIMOS PASSOS:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Acesse: https://github.com/new" -ForegroundColor White
Write-Host "2. Crie um repositório chamado 'caxiastemturismo'" -ForegroundColor White
Write-Host "3. NÃO adicione README, .gitignore ou licença (já temos!)" -ForegroundColor White
Write-Host "4. Após criar, execute os comandos abaixo:" -ForegroundColor White
Write-Host ""
Write-Host "   git remote add origin https://github.com/SEU-USUARIO/caxiastemturismo.git" -ForegroundColor Cyan
Write-Host "   git branch -M main" -ForegroundColor Cyan
Write-Host "   git push -u origin main" -ForegroundColor Cyan
Write-Host ""
Write-Host "   (Substitua SEU-USUARIO pelo seu nome de usuário do GitHub)" -ForegroundColor Gray
Write-Host ""
Write-Host "5. Ative GitHub Pages em Settings → Pages" -ForegroundColor White
Write-Host ""
Write-Host "📖 Consulte DEPLOY.md para mais detalhes!" -ForegroundColor Yellow
Write-Host ""
