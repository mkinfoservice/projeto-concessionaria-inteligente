# Concessionaria Bem - Render

Base limpa para deploy Docker no Render.

## Deploy

1. Crie um repositorio Git usando esta pasta como raiz.
2. Envie os arquivos para o branch `main`.
3. No Render, crie um Blueprint usando o `render.yaml`.
4. Copie do projeto atual as variaveis marcadas como `sync: false`.
   O Render gera automaticamente `APP_SECRET` e `ASAAS_WEBHOOK_TOKEN`.
5. Importe `database/schema.sql` no banco MySQL caso o banco ainda esteja vazio.

O container usa `FallbackResource /index.php`. Nenhum arquivo `.htaccess` e
necessario ou permitido na imagem Docker.

## Persistencia

O filesystem do servico web do Render e efemero. Para manter uploads apos
redeploys, configure armazenamento persistente compativel com o plano escolhido
ou migre os arquivos enviados para um servico de objetos.
