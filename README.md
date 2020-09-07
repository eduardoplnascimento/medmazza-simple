# MedMazza
> Agendamento de consultas médicas online.

Faça com que a expriência do seu paciente seja ainda mais agradável com uma plataforma de gerênciamento de agendamentos de consultas. Toda a jornada de agendar uma consulta em apenas alguns cliques. Demo: https://simple.medmazza.ml/ (usuário: admin@teste.com / senha: 123456)

![](https://i.imgur.com/wthtACc.png)

## Começando

As instruções a seguir vão adicionar uma cópia do projeto na sua máquina local para testes e desenvolvimento.

### Pré-requisitos

- Você precisa dos seguintes serviços instalados no seu computador:

```
GIT
Servidor WEB
PHP 7.3
MySQL
Composer (https://getcomposer.org/)
Laravel 6.x
```

### Instalando

- Primeiramente é necessária uma base de dados, para isso é preciso criar uma:

```
CREATE DATABASE medmazza;
GRANT ALL PRIVILEGES ON medmazza . * TO 'seu_usuario'@'localhost';
```

- Clone o projeto para sua máquina (coloque na pasta do seu servidor WEB):

```
git clone https://github.com/eduardoplnascimento/medmazza.git
```

- Entre no diretório **medmazza**.
- Copie o arquivo .env.example e nomeie .env:

```
cp .env.example .env
```

- Configurar o arquivo .env com as suas informações:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medmazza
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

- Rodar o comando para instalação (pode demorar alguns minutos):

```
composer install
```

- Rodar o comando para gerar a chave do Laravel:

```
php artisan key:generate
```

- Rodar os comandos para migrar o banco de dados com alguns dados de teste:

```
php artisan migrate --seed
```

- Dar permissão para o servidor WEB:

```
sudo chown -Rf $USER:www-data .
```

## Abrir o servidor backend

- Para rodar o servidor backend utilize o comando:

```
php artisan serve
```

## Utilização

- Acessando a URL do projeto, você será direcionado para a *landing page*, onde existem algumas informações sobre o consultório médico e a plataforma de agendamento.

![](https://i.imgur.com/5azYEUV.png)

- A partir dessa página é possível fazer o *login*. Caso queira acessar a plataforma como administrador e o comando de seed foi rodado, é possível com o email **admin@teste.com** e senha **123456**.

![](https://i.imgur.com/ltjjyaC.png)

### Administrador

Como adminitrador dentro da plataforma, é possível:

- Cancelar agendamentos na aba **Dashboard**;
- Agendar consultas para todos os pacientes na aba **Agendamentos**;
- Criar, editar e remover médicos na aba **Médicos**;
- Criar, editar e remover pacientes na aba **Pacientes**;
- Criar e remover administradores na aba **Administradores**;
- Alterar as informações do perfil na aba **Configurações**.

![](https://i.imgur.com/QN4wZTK.png)

### API

É possível receber dados dos médicos pelos endpoins:

- GET /api/doctors
- GET /api/doctor/{id}

É necessário informar um token **Bearer** no parâmetro **Authorization** no cabeçalho da requisição. O token fica armazenado no campo **api_token** do usuário administrador. Ele pode ser recebido no endpoint **POST /api/auth** informando os campos **email** e **password** do usuário.

## Desenvolvimento

### Front-end

- [x] Criação da interface utilizando Bootstrap.

Grande parte da interface da plataforma é feita com Bootstrap, principalmente *grids* e tabelas. A *landing page* também utiliza Bootstrap, porém a maioria do CSS foi feito utilizando SASS.

- [x] CSS utilizando o pré processador SASS.

A estilização do site foi feita com o pré processador SASS utilizando o webpack do Laravel, caso seja necessário alterar algum estilo nos arquivos SCSS, utilize o comando para compilar:

```
npm run dev
```

- [x] Utilizar *plugin* Datatables.

O plugin Datatables foi utilizado para mostrar os pacientes da plataforma, já que podem ser muitos o plugin ajuda nos filtros e paginação.

### Back-end

- [x] Criação do Banco de Dados.

Banco de dados foi criado com as tabelas *users*, *patients*, *doctors* e *appointments*. É possível fazer a migração do banco de dados com alguns dados de teste com o seguinte comando:

```
php artisan migrate --seed
```

- [x] Criação de CRUD para Usuários, Pacientes, Médicos e Agendamentos.

Todos os models possuem uma *controller* e uma *service* com os métodos do CRUD.

- [x] Criação de um recurso de API Rest.

Foi criada uma API para retornar os dados médicos em JSON.

### Infra

- [x] GIT-FLOW.

O seguinte fluxo foi adotado neste repositório:

- master -> feature/hot-fix -> dev -> master

- [x] Hospedagem.

O sistema foi hospedado em um servidor na Digital Ocean, e pode ser conferido no link https://simple.medmazza.ml/
