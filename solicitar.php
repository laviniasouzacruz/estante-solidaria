<?php

include_once("conexaoBD.php");

// Verifica se o ID do livro foi enviado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    die("Livro não encontrado.");

}

$idLivro = (int) $_GET['id'];

// Busca os dados do livro e dos exemplares
$sql = "SELECT
            l.idLivro,
            l.titulo,
            l.autor,
            l.editora,
            l.anoPublicacao,
            l.fotoLivro,
            l.categoria,
            l.sinopse,
            l.idUsuario,

            COUNT(e.idExemplar) AS quantidade,

            SUM(
                CASE
                    WHEN e.status = 'Disponivel' THEN 1
                    ELSE 0
                END
            ) AS quantidadeDisponivel,

            SUM(
                CASE
                    WHEN e.estado = 'Novo' THEN 1
                    ELSE 0
                END
            ) AS quantidadeNovo,

            SUM(
                CASE
                    WHEN e.estado = 'Bom' THEN 1
                    ELSE 0
                END
            ) AS quantidadeBom,

            SUM(
                CASE
                    WHEN e.estado = 'Regular' THEN 1
                    ELSE 0
                END
            ) AS quantidadeRegular

        FROM livros l

        LEFT JOIN exemplares e
            ON e.idLivro = l.idLivro

        WHERE l.idLivro = $idLivro

        GROUP BY
            l.idLivro,
            l.titulo,
            l.autor,
            l.editora,
            l.anoPublicacao,
            l.fotoLivro,
            l.categoria,
            l.sinopse,
            l.idUsuario";

$resultado = mysqli_query($conn, $sql);

if (!$resultado) {

    die("Erro ao buscar livro: " . mysqli_error($conn));

}

// Verifica se o livro existe
if (mysqli_num_rows($resultado) == 0) {

    die("Livro não encontrado.");

}

$livro = mysqli_fetch_assoc($resultado);

$quantidade = (int) $livro['quantidade'];
$quantidadeDisponivel = (int) $livro['quantidadeDisponivel'];
$quantidadeNovo = (int) $livro['quantidadeNovo'];
$quantidadeBom = (int) $livro['quantidadeBom'];
$quantidadeRegular = (int) $livro['quantidadeRegular'];


// Define o estado exibido na página
if ($quantidade == 0) {

    $estadoLivro = "Não informado";

} elseif ($quantidadeNovo == $quantidade) {

    $estadoLivro = "Novo";

} elseif ($quantidadeBom == $quantidade) {

    $estadoLivro = "Bom";

} elseif ($quantidadeRegular == $quantidade) {

    $estadoLivro = "Regular";

} else {

    $estadoLivro = "Vários estados";

}


// Define o status exibido na página
if ($quantidadeDisponivel > 0) {

    $statusLivro = "Disponivel";

} else {

    $statusLivro = "Indisponivel";

}

?>

<?php include_once("header.php"); ?>

<!-- DETALHES DO LIVRO -->

<section style="
    padding: 40px 0 0;
    margin-top: -1.5rem;
    margin-bottom: -70px;
">

    <div class="container">

        <div class="row justify-content-center">

            <!-- CARD -->

            <div class="col-lg-12 col-md-12">

                <div style="
                    padding: 40px;
                    background-color: #fff;
                    border: 5px solid #841717;
                    border-radius: 18px;
                    box-shadow: 6px 6px 0px #841717;
                ">

                    <div class="row align-items-center">

                        <!-- FOTO DO LIVRO -->

                        <div class="col-md-4 text-center" style="padding-right: 10px;">

                            <?php if (!empty($livro['fotoLivro'])): ?>

                                <img
                                    src="<?php echo htmlspecialchars($livro['fotoLivro']); ?>"
                                    alt="<?php echo htmlspecialchars($livro['titulo']); ?>"
                                    style="
                                        height: 620px;
                                        width: auto;
                                        max-width: 100%;
                                        object-fit: contain;
                                    "
                                >

                            <?php else: ?>

                                <div
                                    class="d-flex align-items-center justify-content-center bg-light"
                                    style="
                                        height: 620px;
                                        border-radius: 10px;
                                    "
                                >

                                    <span>Sem imagem disponível</span>

                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- ÁREA DAS INFORMAÇÕES -->

                        <div class="col-md-8" style="padding-left: 20px;">

                            <!-- CATEGORIA, TÍTULO E AUTOR -->

                            <div
                                class="text-center"
                                style="
                                    margin-bottom: 30px;
                                "
                            >

                                <!-- CATEGORIA -->

                                <?php if (!empty($livro['categoria'])): ?>

                                    <p
                                        class="text-uppercase mb-2"
                                        style="
                                            color: #841717;
                                            font-size: 1.15rem;
                                            letter-spacing: 0.08em;
                                        "
                                    >

                                        <?php echo htmlspecialchars($livro['categoria']); ?>

                                    </p>

                                <?php endif; ?>


                                <!-- TÍTULO -->

                                <h1
                                    class="mb-2"
                                    style="
                                        font-size: 2.3rem;
                                        line-height: 1.2;
                                        color: #272727;
                                    "
                                >

                                    <?php echo htmlspecialchars($livro['titulo']); ?>

                                </h1>


                                <!-- AUTOR -->

                                <h4
                                    class="mb-0"
                                    style="
                                        font-size: 1.3rem;
                                        color: #841717;
                                    "
                                >

                                    <?php echo htmlspecialchars($livro['autor']); ?>

                                </h4>

                            </div>


                            <!-- DUAS COLUNAS -->

                            <div class="row">

                                <!-- COLUNA DA ESQUERDA: INFORMAÇÕES -->

                                <div
                                    style="
                                        width: 29%;
                                        font-size: 1.1rem;
                                        line-height: 1.6;
                                        padding-right: 20px;
                                    "
                                >

                                    <?php if (!empty($livro['editora'])): ?>

                                        <p class="mb-2">

                                            <strong style="color: #841717;">

                                                Editora:

                                            </strong>

                                            <?php echo htmlspecialchars($livro['editora']); ?>

                                        </p>

                                    <?php endif; ?>


                                    <?php if (!empty($livro['anoPublicacao'])): ?>

                                        <p class="mb-2">

                                            <strong style="color: #841717;">

                                                Ano de publicação:

                                            </strong>

                                            <?php echo htmlspecialchars($livro['anoPublicacao']); ?>

                                        </p>

                                    <?php endif; ?>


                                    <?php if (!empty($estadoLivro)): ?>

                                        <p class="mb-2">

                                            <strong style="color: #841717;">

                                                Estado:

                                            </strong>

                                            <?php echo htmlspecialchars($estadoLivro); ?>

                                        </p>

                                    <?php endif; ?>


                                    <p class="mb-2">

                                        <strong style="color: #841717;">

                                            Quantidade de exemplares:

                                        </strong>

                                        <?php echo $quantidade; ?>

                                    </p>


                                    <p class="mb-2">

                                        <strong style="color: #841717;">

                                            Quantidade disponível:

                                        </strong>

                                        <?php echo $quantidadeDisponivel; ?>

                                    </p>


                                    <p class="mb-0">

                                        <strong style="color: #841717;">

                                            Status:

                                        </strong>

                                        <?php if ($statusLivro == 'Disponivel'): ?>

                                            <span style="color: green;">

                                                Disponível

                                            </span>

                                        <?php else: ?>

                                            <span style="color: #C40404;">

                                                Indisponível

                                            </span>

                                        <?php endif; ?>

                                    </p>

                                </div>


                                <!-- COLUNA DA DIREITA: SINOPSE -->

                                <div
                                    style="
                                        width: 71%;
                                        padding-left: 30px;
                                        border-left: 1px solid #ddd;
                                        margin-top: -8px;
                                    "
                                >

                                    <?php if (!empty($livro['sinopse'])): ?>

                                        <div
                                            style="
                                                font-size: 1.1rem;
                                                line-height: 1.55;
                                            "
                                        >

                                            <p
                                                class="mb-2"
                                                style="
                                                    color: #841717;
                                                    font-weight: 600;
                                                "
                                            >

                                                Sinopse

                                            </p>


                                            <p
                                                class="mb-0"
                                                style="
                                                    color: #272727;
                                                    text-align: justify;
                                                "
                                            >

                                                <?php echo nl2br(htmlspecialchars($livro['sinopse'])); ?>

                                            </p>

                                        </div>

                                    <?php endif; ?>

                                </div>

                            </div>


                            <!-- BOTÃO -->

                            <div
                                class="mt-4"
                                style="
                                    width: 100%;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                "
                            >

                                <?php if ($statusLivro == 'Disponivel' && $quantidadeDisponivel > 0): ?>

                                    <button
                                        type="button"
                                        class="btn"
                                        onclick="mostrarConfirmacao()"
                                        style="
                                            padding: 14px 38px;
                                            font-size: 1.15rem;
                                            transform: translateX(-25px);
                                        "
                                    >

                                        Solicitar livro

                                    </button>

                                <?php else: ?>

                                    <button
                                        class="btn"
                                        disabled
                                        style="
                                            padding: 14px 38px;
                                            font-size: 1.15rem;
                                            transform: translateX(-25px);
                                        "
                                    >

                                        Livro indisponível

                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- SOBREPOSIÇÃO DE CONFIRMAÇÃO -->

<div
    id="confirmacao"
    style="
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    "
>

    <!-- CARD DE CONFIRMAÇÃO -->

    <div
        style="
            width: 100%;
            max-width: 500px;
            padding: 40px;
            background-color: #fff;
            border: 5px solid #841717;
            border-radius: 18px;
            box-shadow: 8px 8px 0px #841717;
            text-align: center;
        "
    >

        <h2
            style="
                color: #841717;
                font-size: 2rem;
                line-height: 1.2;
                margin-bottom: 20px;
            "
        >

            Confirmar solicitação

        </h2>


        <p
            style="
                color: #272727;
                font-size: 1.15rem;
                line-height: 1.5;
                margin-bottom: 10px;
            "
        >

            Tem certeza que deseja solicitar este livro?

        </p>


        <p
            style="
                color: #841717;
                font-size: 1.1rem;
                font-weight: 600;
                margin-bottom: 30px;
            "
        >

            <?php echo htmlspecialchars($livro['titulo']); ?>

        </p>


        <div
            class="d-flex justify-content-center gap-3"
            style="flex-wrap: wrap;"
        >

            <button
                type="button"
                class="btn"
                onclick="cancelarConfirmacao()"
                style="
                    background-color: #6c757d;
                    color: #fff;
                "
            >

                Cancelar

            </button>


            <a
                href="confirmarSolicitacao.php?id=<?php echo $livro['idLivro']; ?>"
                class="btn"
            >

                Confirmar solicitação

            </a>

        </div>

    </div>

</div>


<script>

function mostrarConfirmacao() {

    document.getElementById("confirmacao").style.display = "flex";

}

function cancelarConfirmacao() {

    document.getElementById("confirmacao").style.display = "none";

}

</script>


<?php include_once("footer.php"); ?>