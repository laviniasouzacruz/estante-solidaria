<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("conexaoBD.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: formLogin.php");
    exit();
}

// Verifica se o ID do livro foi enviado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Livro não encontrado.");
}

$idLivro = (int) $_GET['id'];
$idUsuario = (int) $_SESSION['idUsuario'];

// Inicia uma transação
mysqli_begin_transaction($conn);

try {

    // Busca os dados do livro
    $sqlLivro = "SELECT
                    idLivro,
                    titulo
                 FROM livros
                 WHERE idLivro = $idLivro
                 FOR UPDATE";

    $resultadoLivro = mysqli_query($conn, $sqlLivro);

    if (!$resultadoLivro) {
        throw new Exception("Erro ao buscar livro.");
    }

    // Verifica se o livro existe
    if (mysqli_num_rows($resultadoLivro) == 0) {
        throw new Exception("Livro não encontrado.");
    }

    $livro = mysqli_fetch_assoc($resultadoLivro);


    // Verifica se o usuário já possui uma solicitação aguardando confirmação
    $sqlVerificar = "SELECT idSolicitacao
                     FROM solicitacoes
                     WHERE idLivro = $idLivro
                     AND idUsuario = $idUsuario
                     AND status = 'Aguardando confirmação'";

    $resultadoVerificar = mysqli_query($conn, $sqlVerificar);

    if (!$resultadoVerificar) {
        throw new Exception("Erro ao verificar solicitação.");
    }

    if (mysqli_num_rows($resultadoVerificar) > 0) {
        throw new Exception("Você já solicitou este livro.");
    }


    // Busca um exemplar disponível
    // FOR UPDATE impede que outro usuário pegue o mesmo exemplar
    // enquanto esta transação estiver sendo realizada.
    $sqlExemplar = "SELECT
                        idExemplar
                    FROM exemplares
                    WHERE idLivro = $idLivro
                    AND status = 'Disponivel'
                    LIMIT 1
                    FOR UPDATE";

    $resultadoExemplar = mysqli_query($conn, $sqlExemplar);

    if (!$resultadoExemplar) {
        throw new Exception("Erro ao verificar os exemplares.");
    }

    // Verifica se existe exemplar disponível
    if (mysqli_num_rows($resultadoExemplar) == 0) {
        throw new Exception("Este livro não está mais disponível.");
    }

    $exemplar = mysqli_fetch_assoc($resultadoExemplar);
    $idExemplar = (int) $exemplar['idExemplar'];


    // Registra a solicitação vinculando ao exemplar
    $sqlSolicitacao = "INSERT INTO solicitacoes
                       (idLivro, idExemplar, idUsuario, status)
                       VALUES
                       ($idLivro, $idExemplar, $idUsuario, 'Aguardando confirmação')";

    if (!mysqli_query($conn, $sqlSolicitacao)) {
        throw new Exception("Erro ao registrar solicitação.");
    }


    // Confirma a operação
    mysqli_commit($conn);

    $sucesso = true;

} catch (Exception $e) {

    // Desfaz a operação caso alguma coisa dê errado
    mysqli_rollback($conn);

    $erro = $e->getMessage();
    $sucesso = false;
}

?>

<?php include_once("header.php"); ?>

<section style="padding: 10px 0 0; margin-bottom: -70px;">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-7 col-md-9">

                <?php if ($sucesso): ?>

                    <div
                        style="
                            padding: 40px 45px;
                            background-color: #fff;
                            border: 5px solid #841717;
                            border-radius: 18px;
                            box-shadow: 6px 6px 0px #841717;
                            text-align: center;
                        "
                    >

                        <!-- CHECK -->
                        <div
                            style="
                                font-size: 55px;
                                line-height: 1;
                                color: #841717;
                                margin-bottom: 25px;
                            "
                        >
                            ✓
                        </div>


                        <!-- TÍTULO -->
                        <h1
                            style="
                                color: #841717;
                                font-size: 2.2rem;
                                line-height: 1.2;
                                margin-bottom: 30px;
                            "
                        >
                            Solicitação realizada com sucesso!
                        </h1>


                        <!-- LIVRO SOLICITADO -->
                        <p
                            style="
                                font-size: 1.15rem;
                                color: #272727;
                                margin-bottom: 8px;
                            "
                        >
                            Você solicitou o livro:
                        </p>

                        <h3
                            style="
                                color: #272727;
                                font-size: 1.5rem;
                                margin-bottom: 30px;
                            "
                        >
                            <?php echo htmlspecialchars($livro['titulo']); ?>
                        </h3>


                        <!-- EXPLICAÇÃO -->
                        <p
                            style="
                                font-size: 1.1rem;
                                line-height: 1.65;
                                color: #555;
                                margin-bottom: 28px;
                            "
                        >
                            Sua solicitação foi enviada para análise das coordenadoras
                            responsáveis. Após a confirmação, você receberá um e-mail
                            no endereço informado em seu cadastro com as orientações
                            para combinar a retirada do livro na biblioteca.
                        </p>


                        <a
                            href="livros.php"
                            class="btn mt-2"
                        >
                            Voltar para os livros
                        </a>

                    </div>

                <?php else: ?>

                    <div
                        style="
                            padding: 40px;
                            background-color: #fff;
                            border: 5px solid #841717;
                            border-radius: 18px;
                            box-shadow: 6px 6px 0px #841717;
                            text-align: center;
                        "
                    >

                        <h1
                            style="
                                color: #841717;
                                font-size: 2rem;
                                margin-bottom: 20px;
                            "
                        >
                            Não foi possível realizar a solicitação
                        </h1>

                        <p
                            style="
                                font-size: 1.1rem;
                                color: #272727;
                            "
                        >
                            <?php echo htmlspecialchars($erro); ?>
                        </p>

                        <a
                            href="livros.php"
                            class="btn mt-3"
                        >
                            Voltar para os livros
                        </a>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>

<?php include_once("footer.php"); ?>