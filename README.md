<div align="center">

# Sistema de Gestão Escolar e Acompanhamento Infantil

Plataforma web para organizar a rotina escolar e aproximar escolas, professores e responsáveis.

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php&logoColor=white)

</div>

## Sobre o projeto

O Educa+ centraliza informações de escolas, turmas e alunos. A equipe escolar registra frequência e acontecimentos do dia; os responsáveis acompanham essas informações, consultam boletins e recebem comunicados. Cada pessoa acessa somente as áreas compatíveis com seu perfil e seus vínculos com escolas, turmas ou alunos.

## Funcionalidades

### Administração

- Cadastro e manutenção de escolas, turmas, alunos e usuários.
- Aprovação ou recusa de solicitações de cadastro de professores e responsáveis.
- Associação de professores a escolas e turmas, e de responsáveis aos alunos.
- Painel com indicadores de alunos, turmas, profissionais, registros e pendências.
- Relatórios de turmas e alunos em PDF.

### Professores

- Consulta às próprias turmas e aos alunos vinculados.
- Lançamento e consulta do histórico de frequência, com justificativa de falta.
- Criação, edição e consulta de registros diários dos alunos, incluindo observações, alimentação, sono, fraldas e ingestão de líquidos.
- Emissão e consulta de boletins, com opção de anexar um PDF.
- Envio de e-mails aos responsáveis dos alunos vinculados.

### Responsáveis

- Painel com situação diária dos filhos vinculados, frequência e indicação de registro do dia.
- Consulta à frequência e aos registros diários, com detalhes do acompanhamento.
- Consulta e download de boletins.
- Visualização de comunicados recebidos.
- Geração de relatório do aluno em PDF, com filtros por período.

### Conta e acesso

- Cadastro de professores e responsáveis pela tela de registro.
- Aprovação administrativa obrigatória antes do primeiro acesso dessas contas.
- Login, encerramento de sessão e fluxo de redefinição de senha.

## Como utilizar

1. Acesse a aplicação pelo endereço configurado e entre com seu e-mail e senha.
2. Após o login, o sistema direciona você ao painel conforme o perfil: administrador, professor ou responsável.
3. O administrador prepara a estrutura cadastrando escolas, turmas e alunos; depois cadastra usuários ou aprova solicitações e realiza os vínculos necessários.
4. O professor seleciona uma turma para lançar a frequência e escolhe um aluno para criar o registro diário. Também pode consultar históricos, emitir boletins e contatar responsáveis.
5. O responsável acessa os alunos vinculados à sua conta para acompanhar registros, frequência, comunicados e boletins.

> **Atenção:** o cadastro público cria contas de professor ou responsável com status pendente. Um administrador precisa aprová-las e associá-las às turmas ou aos alunos antes do uso. O projeto não fornece uma conta administrativa padrão; o primeiro administrador deve ser provisionado pelo responsável pela instalação.

## Requisitos

- PHP 8.3 ou superior e extensões exigidas pelo Laravel.
- Composer.
- Node.js e npm.
- MySQL/MariaDB ou SQLite.

O projeto utiliza Laravel 13, Vite e `barryvdh/laravel-dompdf` para geração de documentos PDF.

## Instalação local

### 1. Instale as dependências

Na pasta do projeto, execute:

```bash
composer install
npm install
```

### 2. Configure o ambiente

Copie `.env.example` para `.env` e gere a chave da aplicação:

```bash
cp .env.example .env
php artisan key:generate
```

No Windows, a cópia também pode ser feita pelo PowerShell:

```powershell
Copy-Item .env.example .env
```

Configure no `.env` o nome da aplicação e a conexão com o banco. Exemplo para MySQL/MariaDB (crie antes o banco `educa_plus`):

```dotenv
APP_NAME="Sistema Escolar"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=educa_plus
DB_USERNAME=root
DB_PASSWORD=
```

Se preferir SQLite, configure `DB_CONNECTION=sqlite` e crie o arquivo `database/database.sqlite` antes das migrações. Não mantenha as configurações de exemplo em produção.

### 3. Prepare o banco e os arquivos públicos

```bash
php artisan migrate
php artisan storage:link
npm run build
```

As migrações criam as tabelas necessárias. Não execute `migrate:fresh` em um banco com dados que deseja preservar: esse comando apaga as tabelas antes de recriá-las.

### 4. Inicie o ambiente de desenvolvimento

Execute cada comando em um terminal separado:

```bash
php artisan serve
```

```bash
npm run dev
```

A aplicação ficará disponível, por padrão, em `http://127.0.0.1:8000`.

## E-mail e redefinição de senha

O `.env.example` usa `MAIL_MAILER=log`: mensagens são registradas nos logs da aplicação, não entregues a uma caixa postal. Para envio real, configure `MAIL_MAILER`, host, porta, credenciais e remetente conforme o provedor de e-mail utilizado. A configuração também é necessária para testar o recebimento de links de redefinição de senha.

## Testes

```bash
php artisan test
```

## Tecnologias

- **Backend:** PHP, Laravel e Eloquent ORM.
- **Interface:** Blade, CSS, JavaScript e Vite.
- **Banco de dados:** MySQL/MariaDB ou SQLite.
- **Documentos:** geração de PDFs com DomPDF.

## Segurança e dados

As rotas autenticadas são protegidas por perfil, e os acessos a turmas e alunos consideram os vínculos do usuário. Use senhas fortes, mantenha as credenciais fora do controle de versão e configure `APP_DEBUG=false` em produção. Dados escolares e de crianças devem ser tratados com acesso restrito e conforme a legislação aplicável.

## Licença

Este repositório contém uma aplicação construída com Laravel. Consulte os arquivos e avisos de licença das dependências utilizadas antes de redistribuir o projeto.
