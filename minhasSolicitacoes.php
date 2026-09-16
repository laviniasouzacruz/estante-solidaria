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



$idUsuario = (int) $_SESSION['idUsuario'];

$filtro = isset($_GET['filtro'])
    ? $_GET['filtro']
    : 'Todas';



// ======================================================
// BUSCA AS SOLICITAÇÕES DO USUÁRIO
// ======================================================

$sqlReservas = "SELECT

                    s.idSolicitacao,
                    s.dataSolicitacao,
                    s.status,
                    s.motivoCancelamento,

                    l.idLivro,
                    l.titulo,
                    l.autor,
                    l.fotoLivro

                FROM solicitacoes s

                INNER JOIN livros l
                    ON s.idLivro = l.idLivro

                WHERE s.idUsuario = $idUsuario";



if ($filtro !== 'Todas') {

    $filtroSeguro = mysqli_real_escape_string(
        $conn,
        $filtro
    );

    $sqlReservas .= " AND s.status = '$filtroSeguro'";

}



$sqlReservas .= "
    ORDER BY s.dataSolicitacao DESC
";



$resultadoReservas = mysqli_query(
    $conn,
    $sqlReservas
);



if (!$resultadoReservas) {

    die(
        "Erro ao buscar suas solicitações: " .
        mysqli_error($conn)
    );

}

?>



<?php include_once("header.php"); ?>



<style>

/* ======================================================
   TÍTULO
   ====================================================== */

.titulo-reservas {

    color: #841717;

    font-size: 2.5rem;

    font-weight: 600;

    margin-top: 0;

    margin-bottom: 3px;

}



.subtitulo-reservas {

    color: #555;

    font-size: 1.2rem;

    margin-bottom: 15px;

}



/* ======================================================
   FILTROS
   ====================================================== */

.filtro-reservas {

    display: flex;

    gap: 8px;

    flex-wrap: wrap;

    margin-bottom: 25px;

}



.filtro-btn {

    border: 1px solid #dee2e6;

    background-color: white;

    color: #333;

    border-radius: 8px;

    padding: 8px 15px;

    cursor: pointer;

    transition: 0.2s;

    text-decoration: none;

}



.filtro-btn:hover {

    background-color: #f8e6e6;

    border-color: #800020;

    color: #800020;

}



.filtro-btn.ativo {

    background-color: #800020;

    color: white;

    border-color: #800020;

}



/* ======================================================
   CARDS DAS SOLICITAÇÕES
   ====================================================== */

.card-reserva {

    border: 1px solid #e2e2e2;

    border-radius: 12px;

    background-color: #fff;

    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);

    margin-bottom: 15px;

    overflow: hidden;

}



.conteudo-reserva {

    padding: 22px;

}



/* ======================================================
   IMAGEM
   ====================================================== */

.imagem-reserva {

    width: 115px;

    height: 160px;

    object-fit: cover;

    border-radius: 8px;

}



.sem-imagem {

    width: 115px;

    height: 160px;

    border-radius: 8px;

    background-color: #f1f1f1;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #777;

    font-size: 14px;

    text-align: center;

}



/* ======================================================
   INFORMAÇÕES DO LIVRO
   ====================================================== */

.titulo-livro {

    color: #800020;

    font-size: 1.35rem;

    font-weight: 600;

    margin-bottom: 7px;

}



.autor-livro {

    color: #555;

    font-size: 1rem;

    margin-bottom: 15px;

}



.data-reserva {

    color: #666;

    font-size: 0.95rem;

    margin-bottom: 0;

}



/* ======================================================
   STATUS
   ====================================================== */

.status-reserva {

    display: inline-block;

    padding: 9px 16px;

    border-radius: 20px;

    font-size: 0.9rem;

    font-weight: 600;

    white-space: nowrap;

}



.status-aguardando {

    background-color: #fff3cd;

    color: #856404;

}



.status-reservado {

    background-color: #e8d5df;

    color: #800020;

}



.status-retirado {

    background-color: #d1e7dd;

    color: #0f5132;

}



.status-cancelado {

    background-color: #f8d7da;

    color: #842029;

}



/* ======================================================
   MOTIVO DO CANCELAMENTO
   ====================================================== */

.motivo-cancelamento {

    margin-top: 14px;

    padding: 11px 13px;

    background-color: #f8f8f8;

    border-left: 3px solid #800020;

    border-radius: 4px;

    color: #555;

    font-size: 0.9rem;

}



/* ======================================================
   NENHUMA SOLICITAÇÃO
   ====================================================== */

.nenhuma-reserva {

    text-align: center;

    padding: 45px 20px;

    border: 1px solid #e2e2e2;

    border-radius: 12px;

    background-color: #fff;

    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);

    margin-bottom: 0;

}



.nenhuma-reserva h4 {

    color: #800020;

    margin-bottom: 10px;

}



.nenhuma-reserva p {

    color: #666;

    margin-bottom: 20px;

}



.botao-livros {

    background-color: #800020;

    color: #fff;

    border: none;

    padding: 10px 22px;

    border-radius: 6px;

    text-decoration: none;

    display: inline-block;

    transition: 0.2s;

}



.botao-livros:hover {

    background-color: #660019;

    color: #fff;

}



/* ======================================================
   ESPAÇAMENTO DA PÁGINA
   ====================================================== */

.pagina-reservas {

    margin-bottom: -3rem;

}



/* ======================================================
   RESPONSIVIDADE
   ====================================================== */

@media (max-width: 768px) {

    .pagina-reservas {

        margin-bottom: -2rem;

    }



    .titulo-reservas {

        font-size: 2rem;

    }



    .subtitulo-reservas {

        font-size: 1.05rem;

    }



    .filtro-reservas {

        gap: 8px;

    }



    .filtro-btn {

        padding: 8px 15px;

        font-size: 0.95rem;

    }



    .imagem-reserva,

    .sem-imagem {

        width: 90px;

        height: 125px;

    }



    .titulo-livro {

        font-size: 1.15rem;

    }



    .autor-livro {

        font-size: 0.95rem;

    }



    .status-reserva {

        font-size: 0.85rem;

        padding: 8px 13px;

    }

}

</style>



<section
    class="position-relative pagina-reservas"
    style="
        padding-top: 0;
        padding-bottom: 0;
        background-color: #fff;
    "
>

    <div
        class="container"
        style="
            padding-top: 0;
            padding-bottom: 0;
            position: relative;
            top: -25px;
        "
    >



        <!-- TÍTULO -->

        <div
            style="
                text-align: center;
                margin-bottom: 20px;
                margin-top: 0;
            "
        >

            <h1 class="titulo-reservas">

                Minhas Solicitações

            </h1>



            <p class="subtitulo-reservas">

                Acompanhe suas solicitações e veja o status de cada reserva.

            </p>

        </div>



        <!-- FILTROS -->

        <div class="filtro-reservas">

            <a
                href="minhasReservas.php?filtro=Todas"
                class="filtro-btn <?php echo $filtro === 'Todas'
                    ? 'ativo'
                    : ''; ?>"
            >

                Todas

            </a>



            <a
                href="minhasReservas.php?filtro=Aguardando%20confirma%C3%A7%C3%A3o"
                class="filtro-btn <?php echo $filtro === 'Aguardando confirmação'
                    ? 'ativo'
                    : ''; ?>"
            >

                Aguardando confirmação

            </a>



            <a
                href="minhasReservas.php?filtro=Reservado"
                class="filtro-btn <?php echo $filtro === 'Reservado'
                    ? 'ativo'
                    : ''; ?>"
            >

                Reservadas

            </a>



            <a
                href="minhasReservas.php?filtro=Retirado"
                class="filtro-btn <?php echo $filtro === 'Retirado'
                    ? 'ativo'
                    : ''; ?>"
            >

                Retiradas

            </a>



            <a
                href="minhasReservas.php?filtro=Cancelado"
                class="filtro-btn <?php echo $filtro === 'Cancelado'
                    ? 'ativo'
                    : ''; ?>"
            >

                Canceladas

            </a>

        </div>



        <!-- SOLICITAÇÕES -->

        <?php if (mysqli_num_rows($resultadoReservas) > 0): ?>

            <?php while ($reserva = mysqli_fetch_assoc($resultadoReservas)): ?>

                <div class="card-reserva">

                    <div class="conteudo-reserva">

                        <div class="row align-items-center">



                            <!-- FOTO -->

                            <div class="col-md-auto mb-3 mb-md-0">

                                <?php if (!empty($reserva['fotoLivro'])): ?>

                                    <img
                                        src="<?php echo htmlspecialchars(
                                            $reserva['fotoLivro']
                                        ); ?>"
                                        alt="<?php echo htmlspecialchars(
                                            $reserva['titulo']
                                        ); ?>"
                                        class="imagem-reserva"
                                    >

                                <?php else: ?>

                                    <div class="sem-imagem">

                                        Sem imagem

                                    </div>

                                <?php endif; ?>

                            </div>



                            <!-- INFORMAÇÕES -->

                            <div class="col-md">

                                <div class="titulo-livro">

                                    <?php echo htmlspecialchars(
                                        $reserva['titulo']
                                    ); ?>

                                </div>



                                <div class="autor-livro">

                                    Autor:
                                    <?php echo htmlspecialchars(
                                        $reserva['autor']
                                    ); ?>

                                </div>



                                <p class="data-reserva">

                                    <strong>

                                        Solicitação realizada em:

                                    </strong>

                                    <?php

                                    echo date(
                                        'd/m/Y \à\s H\:i',
                                        strtotime(
                                            $reserva['dataSolicitacao']
                                        )
                                    );

                                    ?>

                                </p>



                                <?php if (
                                    $reserva['status'] === 'Cancelado' &&
                                    !empty($reserva['motivoCancelamento'])
                                ): ?>

                                    <div class="motivo-cancelamento">

                                        <strong>

                                            Motivo do cancelamento:

                                        </strong>

                                        <?php echo htmlspecialchars(
                                            $reserva['motivoCancelamento']
                                        ); ?>

                                    </div>

                                <?php endif; ?>

                            </div>



                            <!-- STATUS -->

                            <div
                                class="col-md-auto mt-3 mt-md-0 text-md-end"
                            >

                                <?php

                                $classeStatus =
                                    'status-aguardando';



                                if (
                                    $reserva['status'] === 'Reservado'
                                ) {

                                    $classeStatus =
                                        'status-reservado';

                                }

                                elseif (
                                    $reserva['status'] === 'Retirado'
                                ) {

                                    $classeStatus =
                                        'status-retirado';

                                }

                                elseif (
                                    $reserva['status'] === 'Cancelado'
                                ) {

                                    $classeStatus =
                                        'status-cancelado';

                                }

                                ?>



                                <span
                                    class="status-reserva <?php echo $classeStatus; ?>"
                                >

                                    <?php echo htmlspecialchars(
                                        $reserva['status']
                                    ); ?>

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>



        <?php else: ?>



            <!-- NENHUMA SOLICITAÇÃO -->

            <div class="nenhuma-reserva">

                <h4>

                    <?php

                    if ($filtro === 'Todas') {

                        echo "Você ainda não possui solicitações.";

                    }

                    else {

                        echo "Nenhuma solicitação encontrada.";

                    }

                    ?>

                </h4>



                <p>

                    <?php

                    if ($filtro === 'Todas') {

                        echo "Quando você solicitar um livro, ele aparecerá aqui para que possa acompanhar o status.";

                    }

                    else {

                        echo "Não há solicitações com o status selecionado.";

                    }

                    ?>

                </p>



                <?php if ($filtro === 'Todas'): ?>

                    <a
                        href="livros.php"
                        class="botao-livros"
                    >

                        Explorar livros

                    </a>

                <?php endif; ?>

            </div>

        <?php endif; ?>



    </div>

</section>



<?php include_once("footer.php"); ?>