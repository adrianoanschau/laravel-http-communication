## Pré-requisitos

<p>Você precisa ter instalado apenas o Docker e o Docker Compose para rodar este projeto.</p>

- <a href="https://www.docker.com/">Docker</a>
- <a href="https://docs.docker.com/compose/">Docker Compose</a>

---

## Antes de começar

<p>Antes de mais nada, você precisa criar 3 arquivos com as variáveis de ambiente das aplicações.</p>

<p>Deixei arquivos exemplos para você usar.</p>

<ul>
    <li>Neste diretório, duplique <a href="/.env.example">.env.example</a> para `.env`.</li>
    <li>No diretório `api`, duplique <a href="/api/.env.example">.env.example</a> para `api/.env`.</li>
    <li>No diretório `web`, duplique <a href="/web/.env.example">.env.example</a> para `web/.env`.</li>
</ul>

<p>Fique livre para modificar alguma informação se julgar necessário, mas algumas delas somente surtem efeito se o fizer antes de construir as imagens, como as credenciais de acesso do banco de dados, neste arquivo: <a href="/.env.example">.env.example</a>.</p>

## Na primeira vez

<p>O seguinte comando irá produzir as imagens necessárias para rodar os containers do Docker:</p>

```
docker compose build
```

### Em seguida

<p>Excute o comando a seguir e nas oportunidades futuras quando quiser rodar os containers com as imagens produzidas anteriormente:</p>

```
docker compose up -d
```

<p>Com isso você terá `5 containers` a disposição, conforme listado a seguir:</p>

<ul>
    <li>
        Banco de dados
        <ul>
            <li>Nome: database</li>
            <li>Porta: 5432</li>
            <li>Tabela: postgres</li>
            <li>Usuário: postgres</li>
            <li>Senha: postgres</li>
        </ul>
        <p>As credenciais de acesso ao banco estão definidos no arquivo <a href="#">`.env`</a> do presente diretório. Antes de construir as imagens, você pode editá-los se preferir.</p>
        <p>Caso queira conectar um cliente ao banco de dados, como o <a href="https://dbeaver.io/download/">DBeaver</a>, por exemplo, como `host` você deve definir `localhost`, e a porta `5432`.</p>
    </li>
    <li>
        Proxy do backend
        <ul>
            <li>Nome: api-server</li>
            <li>Porta: 8001</li>
            <li>Diretório: <a href="/api">api</a></li>
        </ul>
    </li>
    <li>
        Proxy do frontend
        <ul>
            <li>Nome: web-server</li>
            <li>Porta: 8002</li>
            <li>Diretório: <a href="/web">web</a></li>
        </ul>
    </li>
    <li>
        APP do backend
        <ul>
            <li>Nome: api</li>
            <li>Porta: 9001</li>
            <li>Diretório: <a href="/api">api</a></li>
        </ul>
    </li>
    <li>
        APP do frontend
        <ul>
            <li>Nome: web</li>
            <li>Porta: 9002</li>
            <li>Diretório: <a href="/web">web</a></li>
        </ul>
    </li>
</ul>

### Dependências

<p>
Precisamos instalar as dependências das aplicações que estamos rodando, os seguintes comandos irão suprir as necessidades:
</p>

```
docker compose exec api composer install
docker compose exec web composer install
docker compose exec web yarn
```

## Antes de mais nada

<p>
    A nossa base de dados ainda precisa de mais, precisamos colocar alguns dados nela. O seguinte comando deve ser executado na primeira vez, logo após construir as imagens. Ele irá criar as tabelas necessárias e irá preencher com uma lista de usuários para teste.
</p>

```
docker compose exec api php artisan migrate --seed
```

<p>Agora, para a aplicação de frontend rodar, é preciso de um comando que você irá executar todas as vezes:</p>

```
docker compose exec web yarn dev
```

<p>O comando acima colocará no ar uma aplicação `Node` que irá compilar e fornecer o necessário para o frontend funcionar.</p>

---

#### ATENÇÃO

<p>Uma aplicação Laravel precisa ainda de mais um pouco antes de funcionar perfeitamente, execute os seguintes comandos para evitarmos problemas:</p>

```
docker compose exec api chmod -R 777 storage
docker compose exec web chmod -R 777 storage
```

<p>Assim as devidas permissões serão dadas para que tudo funcione, agora podemos seguir.</p>

---

## E finalmente

<p><a href="/API%20Collection">Aqui</a> tenho uma coleção para usar em um programa de teste de API. Utilizei o <a href="https://www.usebruno.com/">Bruno</a> para montar, mas deve funcionar também com <a href="https://insomnia.rest/">Imsomnia</a>. Não testei com <a href="https://www.postman.com/">Postman</a>.</p>

<p>Acesse a aplicação no navegador com <a href="http://localhost:8002/">http://localhost:8002/</a> que a mesma lhe conduzirá para a tela de autenticação.</p>

<p>
    Utilize os seguintes dados para acessar:
    <ul>
        <li>Usuário: `root`</li>
        <li>Senha: `password`</li>
    </ul>
</p>

<p>Em seguida a autenticação, você será conduzido para a tela de `Dashboard`, que apenas lhe dirá que você está autenticado. Ao topo você terá uma barra e o link para a tela com a lista de usuários: <a href="http://localhost:8002/list/users">/list/users</a>. Ao topo, na direita, um menu do usuário autenticado e o acesso ao seu <a href="http://localhost:8002/profile">Perfil</a> e ao botão de <a href="http://localhost:8002/logout">Logout</a>.</p>

<p>Se você estiver usando um perfil com permissão de administrador, você verá na lista de usuários algumas possibilidades extras, como criação de novos usuários, deleção em massa de itens da listagem, edição de usuário e deleção individual em cada linha da tabela.</p>

<p>O usuário padrão `root`, tem acesso de administrador, se quiser testar a aplicação utilizando usuário normal, deslogue do sistema e na tela de login você terá acesso a um botão de <a href="http://localhost:8002/register">Registro</a>. Crie um novo usuário através do formulário apresentado e autentique-se com ele.</p>

---

---

## Considerações finais

### Possíveis melhorias

<p>
Produzi esta aplicação, desta forma, com intuito de se aproximar de uma possível realidade para casos de uso de um CRUD de usuários.
</p>

<p>
Por isso, quando um usuário administrador irá criar um novo usuário, ele somente poderá informar Nome, Sobrenome e Email; a criação de Nome de Usuário e Senha, fica por conta do dono do e-mail. Algo que fica para uma possível melhoria futura.
</p>

<p>
A nível técnico esta aplicação poderia receber uma documentação extra da API, indicando com detalhe cada rota e as suas especificações e também um tratamento melhorado das exceções. Trabalhei aqui com o básico com o intuito de ser simples e objetivo, onde tenho o necessário: Quando acontece o erro, sei de onde ele vem.
</p>

### Testes unitários

<p>
Nos testes unitários fui econômico, criei apenas alguns com intuito de mostrar como os testes seriam escritos, na API com teste das `Models` em uso e testes de `Feature` nas rotas de autenticação.
</p>
