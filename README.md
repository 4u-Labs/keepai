# Keep AI

App de notas e organização da 4u-Labs com sincronização opcional pelo Google Drive e recursos de IA.

- App: https://4u.ia.br/app/keepai/
- Portal: https://4u.ia.br/
- Organização: https://github.com/4u-Labs

## Configuração segura

`api/config.php` carrega a configuração privada em `/home/fabiano/.config/4u-labs/keepai-config.php`. Crie esse arquivo no servidor com as constantes de runtime exigidas pela instalação, fora da raiz pública, com diretório `0700` e arquivo `0600`. Não publique chaves, tokens, bancos de dados ou logs no repositório. O banco local `database/keepai.db` é ignorado pelo Git.

A autenticação Google usa verificação de tokens no backend. O token de sessão no navegador fica em `sessionStorage`, de modo que uma nova sessão do navegador requer login novamente.
