# 🚀 Elite Sync — Keep AI
# Script para sincronização segura sem travar o ambiente de desenvolvimento

$source = Read-Host "Digite o caminho da ORIGEM"
$destination = "c:\Users\fab\Downloads\bkp"

Write-Host "Iniciando Sincronização Segura..." -ForegroundColor Cyan

# Flags:
# /MIR - Espelhar (Cuidado: deleta no destino se não estiver na origem)
# /MT:8 - Multi-threaded (mais rápido)
# /XD - Excluir Diretórios (Protege inteligência e backups)
# /XF - Excluir Arquivos (Protege o core ativo para evitar locks)

robocopy $source $destination /MIR /MT:8 /Z /R:3 /W:5 `
    /XD .agent legacy .git `
    /XF *.js *.css index.html .htaccess

Write-Host "Sincronização Concluída com Sucesso!" -ForegroundColor Green
pause
