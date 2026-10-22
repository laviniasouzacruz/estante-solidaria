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

Se preferir, acesse o repositório no GitHub e selecione **Code → Download ZIP**.

### 2. Configurar a pasta do projeto

Coloque a pasta do projeto dentro do diretório `htdocs` da instalação do XAMPP.

Exemplo no Windows:

```text
C:\xampp\htdocs\estante-solidaria
```

### 3. Iniciar os serviços do XAMPP

Abra o painel de controle do XAMPP e inicie os serviços:

- Apache
- MySQL

### 4. Criar o banco de dados

1. Acesse o phpMyAdmin pelo endereço http://localhost/phpmyadmin.
2. Clique em **Novo** para criar um banco de dados.
3. Informe exatamente o seguinte nome:

   `estante_solidaria`

4. Confirme a criação do banco de dados.

**Importante:** utilize esse nome para manter a compatibilidade com as configurações de conexão utilizadas pelo projeto. Caso escolha outro nome, será necessário atualizar as configurações correspondentes no código PHP.

### 5. Importar o banco de dados

Depois de criar o banco `estante_solidaria`:

1. Selecione o banco de dados no phpMyAdmin.
2. Acesse a opção **Importar**.
3. Selecione o arquivo SQL disponibilizado no repositório GitHub, identificado como `estante_solidaria.sql`.
4. Execute a importação.
5. Verifique se as tabelas foram criadas corretamente.

O arquivo SQL contém a estrutura e os dados incluídos no script disponibilizado para a configuração inicial do banco de dados.

### 6. Conferir as configurações de conexão

Localize o arquivo PHP responsável pela conexão com o banco de dados e confira se as configurações correspondem ao seu ambiente local.

Verifique especialmente:

- **Servidor (host):** normalmente `localhost` no ambiente local do XAMPP.
- **Banco de dados:** `estante_solidaria`.
- **Usuário do MySQL:** confira o usuário configurado no seu ambiente.
- **Senha do MySQL:** confira a senha definida para esse usuário.

Em instalações padrão do XAMPP, o usuário `root` pode estar configurado sem senha. Entretanto, isso depende da configuração da instalação utilizada.

Se o servidor, o usuário ou a senha forem diferentes dos valores definidos no código, ajuste as configurações no arquivo PHP responsável pela conexão.

**Atenção:** manter o nome do banco de dados igual ao utilizado pela aplicação evita alterações desnecessárias no código. Se o nome for alterado, confira todas as configurações e referências ao banco que dependam desse nome.

### 7. Acessar a aplicação

Com o Apache e o MySQL em execução e o banco de dados configurado, abra o navegador e acesse:

http://localhost/estante-solidaria/

Se você utilizou outro nome para a pasta dentro de `htdocs`, ajuste o endereço conforme necessário.

## Template utilizado

O projeto utilizou como base visual o template **Bookly – Tailwind CSS**, disponibilizado pela TemplatesJungle.

Link do template: https://templatesjungle.com/downloads/bookly-tailwind/

O template serviu como referência para a interface visual, adaptada à proposta da Estante Solidária.

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
