# PRÉ-REQUISITOS

- <a href="https://www.docker.com/">Docker</a>
- <a href="https://docs.docker.com/compose/">Docker Compose</a>

---

# STARTUP

Rode `docker compose up -d --build` para subir as aplicações.
Após este comando você terá na porta 8001 a aplicação de `API` e na porta 8002 a aplicação `WEB`.

---

### DICAS

Utilize a flag `--build` apenas na primeira vez, para construir as imagens necessárias, nas futuras utilizações apenas `docker compose up -d` será suficiente.

Este repositório está preparado e testado para conectar o Xdebug com o VS Code, edite o arquivo `/confs/php/xdebug.ini` alterando o valor de `xdebug.client_host` para o IP ddo seu PC, assim você poderá debugar a aplicação facilmente.
