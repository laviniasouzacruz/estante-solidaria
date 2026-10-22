# Estante Solidária

A **Estante Solidária** é uma aplicação web desenvolvida com o objetivo de facilitar a doação, o compartilhamento e a solicitação de livros entre estudantes do Instituto Federal do Paraná (IFPR), campus Telêmaco Borba.

O projeto busca incentivar o acesso à leitura, a solidariedade e o reaproveitamento de materiais, oferecendo uma forma organizada de disponibilizar e solicitar livros dentro da comunidade acadêmica.

## Objetivo do projeto

Desenvolver uma plataforma web para organizar a doação e a circulação de livros entre estudantes, facilitando o cadastro dos exemplares disponíveis, as solicitações e o acompanhamento da retirada dos livros.

## Funcionalidades

- Cadastro e organização dos livros disponíveis.
- Visualização dos livros cadastrados.
- Solicitação de livros por estudantes interessados.
- Gerenciamento das solicitações pelas administradoras.
- Organização do processo de entrega dos exemplares.
- Registro da retirada dos livros.

## Tecnologias utilizadas

- **HTML:** estrutura das páginas.
- **CSS:** estilização e apresentação visual.
- **JavaScript:** recursos de interação.
- **PHP:** lógica da aplicação no servidor.
- **MySQL:** armazenamento e gerenciamento dos dados.
- **XAMPP:** ambiente local para execução da aplicação e do banco de dados.

## Como executar o projeto localmente

### Pré-requisitos

- XAMPP instalado.
- Navegador web.
- Git, caso opte por clonar o repositório.

### 1. Baixar o projeto

Clone o repositório utilizando o comando:

```bash
git clone https://github.com/laviniasouzacruz/estante-solidaria.git
```

Se preferir, acesse o repositório no GitHub e utilize a opção **Code → Download ZIP**.

### 2. Configurar a pasta do projeto

Coloque a pasta do projeto dentro do diretório `htdocs` da instalação do XAMPP.

Exemplo no Windows:

```text
C:\xampp\htdocs\estante-solidaria
```

### 3. Iniciar o XAMPP

Abra o painel de controle do XAMPP e inicie os serviços **Apache** e **MySQL**.

### 4. Importar o banco de dados

1. Abra o navegador e acesse http://localhost/phpmyadmin.
2. Crie o banco de dados com o nome esperado pela aplicação, caso ele ainda não exista.
3. Selecione o banco de dados.
4. Clique na opção **Importar**.
5. Selecione o arquivo SQL disponibilizado no repositório. O nome informado é `estante_solidaria.sql`; confira a grafia exata no GitHub.
6. Execute a importação e verifique se as tabelas foram criadas corretamente.

### 5. Configurar a conexão com o banco de dados

Localize o arquivo PHP responsável pela conexão com o banco de dados e confira as configurações necessárias:

- Nome do banco de dados.
- Usuário do MySQL.
- Senha, se aplicável.
- Endereço do servidor.

As configurações devem corresponder ao ambiente local utilizado.

### 6. Acessar a aplicação

Com o Apache e o MySQL em execução e o banco de dados configurado, acesse:

http://localhost/estante-solidaria/

Caso a pasta do projeto tenha outro nome dentro de `htdocs`, ajuste o endereço conforme necessário.

## Template utilizado

O projeto utilizou como base visual o template **Bookly – Tailwind CSS**, disponibilizado pela TemplatesJungle.

Link do template: https://templatesjungle.com/downloads/bookly-tailwind/

O template serviu como referência para a interface visual da aplicação, adaptada à proposta da Estante Solidária.

## Contexto acadêmico

A Estante Solidária foi desenvolvida no Instituto Federal do Paraná (IFPR), campus Telêmaco Borba, no contexto das disciplinas:

- **Banco de Dados** — Professor Jailton Júnior de Sousa Coelho.
- **Programação Web I** — Professor Paulo Ricardo de Souza Silva.

O projeto também foi apresentado no **IFTECH**.

## Autoria e colaboração

O projeto foi desenvolvido por:

- **Lavínia Souza Cruz** — principal responsável pela implementação técnica da plataforma.
- **Alane Beatriz Siqueira** — colaboradora no desenvolvimento da proposta e em contribuições ao projeto.

A Estante Solidária representa a aplicação prática de conhecimentos de desenvolvimento web e banco de dados na construção de uma solução voltada à comunidade acadêmica.

## Finalidade

Este projeto foi desenvolvido para fins acadêmicos, com o propósito de aplicar conhecimentos técnicos, explorar soluções web e incentivar a solidariedade, o acesso à leitura e o reaproveitamento de livros no ambiente educacional.

