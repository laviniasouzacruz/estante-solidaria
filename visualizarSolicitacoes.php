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



// Verifica se é administrador

if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'Administrador') {

    header("Location: index.php");

    exit();

}



// Busca as solicitações

$sql = "SELECT

            s.idSolicitacao,

            s.dataSolicitacao,

            s.status,

            s.motivoCancelamento,

            u.nomeUsuario,

            u.emailUsuario,

            l.titulo,

            l.autor

        FROM solicitacoes s

        INNER JOIN usuarios u

            ON s.idUsuario = u.idUsuario

        INNER JOIN livros l

            ON s.idLivro = l.idLivro

        ORDER BY s.dataSolicitacao ASC";



$resultado = mysqli_query($conn, $sql);

if (!$resultado) {

    die("Erro ao buscar solicitações: " . mysqli_error($conn));

}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualizar solicitações</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >



    <style>

        /* ======================================================
           TÍTULO
           ====================================================== */

        .titulo-solicitacoes {

            color: #841717;

            font-size: 2.5rem;

            font-weight: 600;

            margin-top: 0;

            margin-bottom: 3px;

        }



        .descricao-solicitacoes {

            color: #555;

            font-size: 1.2rem;

            margin-bottom: 0;

        }



        /* ======================================================
           FILTROS
           ====================================================== */

        .filtro-solicitacoes {

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
           CABEÇALHO DA TABELA
           ====================================================== */

        .tabela-solicitacoes thead th {

            color: #800020;

            font-weight: 600;

        }



        /* ======================================================
           ESPAÇO ANTES DO FOOTER
           ====================================================== */

        .table-responsive {

            margin-bottom: 0 !important;

        }



        .tabela-solicitacoes {

            margin-bottom: 0 !important;

        }



        .container {

            margin-bottom: -3rem;

        }



        /* ======================================================
           MODAIS
           ====================================================== */

        .modal-overlay {

            display: none;

            position: fixed;

            z-index: 1050;

            inset: 0;

            background-color: rgba(0, 0, 0, 0.5);

            align-items: center;

            justify-content: center;

            padding: 20px;

        }



        .modal-conteudo {

            position: relative;

            background-color: white;

            width: 100%;

            max-width: 850px;

            border-radius: 14px;

            padding: 40px 45px;

            max-height: 90vh;

            overflow-y: auto;

        }



        .modal-fechar {

            position: absolute;

            top: 15px;

            right: 20px;

            border: none;

            background: transparent;

            color: #800020;

            font-size: 30px;

            font-weight: 600;

            line-height: 1;

            cursor: pointer;

            padding: 0;

        }



        .modal-fechar:hover {

            color: #5f0018;

        }



        .modal-titulo {

            color: #800020;

            font-size: 1.8rem;

            text-align: center;

            margin-bottom: 30px;

        }



        .modal-detalhes-grid {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 0;

            margin: 0 auto;

            max-width: 720px;

        }



        .modal-coluna {

            padding: 0 30px;

        }



        .modal-coluna:first-child {

            border-right: 1px solid #dddddd;

        }



        .modal-informacao {

            margin-bottom: 22px;

            line-height: 1.5;

        }



        .modal-informacao strong {

            color: #800020;

        }



        .modal-acoes {

            display: flex;

            justify-content: center;

            align-items: center;

            gap: 12px;

            flex-wrap: wrap;

            margin-top: 30px;

        }



        .motivo-cancelamento {

            background-color: #f8f8f8;

            border-radius: 8px;

            padding: 12px;

            margin: 20px auto 0;

            max-width: 720px;

        }



        .solicitacao-oculta {

            display: none;

        }



        @media (max-width: 768px) {

            .modal-conteudo {

                max-width: 95%;

                padding: 35px 25px;

            }



            .modal-detalhes-grid {

                grid-template-columns: 1fr;

            }



            .modal-coluna {

                padding: 0;

            }



            .modal-coluna:first-child {

                border-right: none;

                border-bottom: 1px solid #dddddd;

                padding-bottom: 10px;

                margin-bottom: 10px;

            }

        }

    </style>

</head>



<body>



<?php include_once("header.php"); ?>



<div
    class="container"
    style="
        padding-top: 0;
        padding-bottom: 0;
        position: relative;
        top: 20px;
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

        <h1 class="titulo-solicitacoes">

            Visualizar solicitações

        </h1>



        <p class="descricao-solicitacoes">

            Consulte e gerencie as solicitações de livros feitas pelos alunos.

        </p>

    </div>



    <!-- FILTROS -->

    <div class="filtro-solicitacoes">

        <button
            type="button"
            class="filtro-btn ativo"
            data-filtro="Todas"
        >

            Todas

        </button>



        <button
            type="button"
            class="filtro-btn"
            data-filtro="Aguardando confirmação"
        >

            Aguardando confirmação

        </button>



        <button
            type="button"
            class="filtro-btn"
            data-filtro="Reservado"
        >

            Reservadas

        </button>



        <button
            type="button"
            class="filtro-btn"
            data-filtro="Retirado"
        >

            Retiradas

        </button>



        <button
            type="button"
            class="filtro-btn"
            data-filtro="Cancelado"
        >

            Canceladas

        </button>

    </div>



    <!-- TABELA -->

    <div class="table-responsive">

        <table class="table table-hover align-middle tabela-solicitacoes">

            <thead>

                <tr>

                    <th>Aluno</th>

                    <th>E-mail</th>

                    <th>Livro</th>

                    <th>Data da solicitação</th>

                    <th>Status</th>

                    <th>Ações</th>

                </tr>

            </thead>



            <tbody>

                <?php if (mysqli_num_rows($resultado) > 0): ?>

                    <?php while ($solicitacao = mysqli_fetch_assoc($resultado)): ?>

                        <?php

                        $idSolicitacao = (int) $solicitacao['idSolicitacao'];

                        $status = $solicitacao['status'];

                        $dataFormatada = date(
                            'd/m/Y \à\s H\:i',
                            strtotime($solicitacao['dataSolicitacao'])
                        );



                        $tituloLivro = $solicitacao['titulo'];

                        if (mb_strlen($tituloLivro) > 28) {

                            $tituloLivro = mb_substr(
                                $tituloLivro,
                                0,
                                28
                            ) . '...';

                        }



                        $autorLivro = $solicitacao['autor'];

                        if (mb_strlen($autorLivro) > 20) {

                            $autorLivro = mb_substr(
                                $autorLivro,
                                0,
                                20
                            ) . '...';

                        }

                        ?>



                        <tr
                            class="solicitacao-linha"
                            data-status="<?php echo htmlspecialchars($status); ?>"
                        >

                            <td>

                                <?php echo htmlspecialchars(
                                    $solicitacao['nomeUsuario']
                                ); ?>

                            </td>



                            <td>

                                <?php echo htmlspecialchars(
                                    $solicitacao['emailUsuario']
                                ); ?>

                            </td>



                            <td>

                                <strong>

                                    <?php echo htmlspecialchars(
                                        $tituloLivro
                                    ); ?>

                                </strong>

                                <br>

                                <small style="color: #000;">

                                    <?php echo htmlspecialchars(
                                        $autorLivro
                                    ); ?>

                                </small>

                            </td>



                            <td>

                                <?php echo $dataFormatada; ?>

                            </td>



                            <td>

                                <?php if (
                                    $status === 'Aguardando confirmação'
                                ): ?>

                                    <span class="badge text-bg-warning">

                                        Aguardando confirmação

                                    </span>



                                <?php elseif (
                                    $status === 'Reservado'
                                ): ?>

                                    <span class="badge text-bg-primary">

                                        Reservado

                                    </span>



                                <?php elseif (
                                    $status === 'Retirado'
                                ): ?>

                                    <span class="badge text-bg-success">

                                        Retirado

                                    </span>



                                <?php elseif (
                                    $status === 'Cancelado'
                                ): ?>

                                    <span class="badge text-bg-danger">

                                        Cancelado

                                    </span>



                                <?php else: ?>

                                    <span class="badge text-bg-secondary">

                                        <?php echo htmlspecialchars($status); ?>

                                    </span>

                                <?php endif; ?>

                            </td>



                            <td>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-dark"
                                    onclick="abrirDetalhes(
                                        <?php echo $idSolicitacao; ?>
                                    )"
                                >

                                    Ver detalhes

                                </button>

                            </td>

                        </tr>



                    <?php endwhile; ?>



                <?php else: ?>

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-4"
                        >

                            Nenhuma solicitação encontrada.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>



<!-- ======================================================
     MODAIS
     ====================================================== -->

<?php

mysqli_data_seek($resultado, 0);

?>



<?php while ($solicitacao = mysqli_fetch_assoc($resultado)): ?>

    <?php

    $idSolicitacao = (int) $solicitacao['idSolicitacao'];

    $status = $solicitacao['status'];

    $dataFormatada = date(
        'd/m/Y \à\s H\:i',
        strtotime($solicitacao['dataSolicitacao'])
    );

    ?>



    <!-- MODAL DE DETALHES -->

    <div
        class="modal-overlay"
        id="modalDetalhes<?php echo $idSolicitacao; ?>"
    >

        <div class="modal-conteudo">



            <!-- X -->

            <button
                type="button"
                class="modal-fechar"
                onclick="fecharDetalhes(
                    <?php echo $idSolicitacao; ?>
                )"
                aria-label="Fechar"
            >

                ×

            </button>



            <!-- TÍTULO -->

            <h4 class="modal-titulo">

                Detalhes da solicitação

            </h4>



            <!-- INFORMAÇÕES -->

            <div class="modal-detalhes-grid">



                <!-- COLUNA ESQUERDA -->

                <div class="modal-coluna">

                    <p class="modal-informacao">

                        <strong>Aluno:</strong><br>

                        <?php echo htmlspecialchars(
                            $solicitacao['nomeUsuario']
                        ); ?>

                    </p>



                    <p class="modal-informacao">

                        <strong>E-mail:</strong><br>

                        <?php echo htmlspecialchars(
                            $solicitacao['emailUsuario']
                        ); ?>

                    </p>



                    <p class="modal-informacao">

                        <strong>Livro:</strong><br>

                        <?php echo htmlspecialchars(
                            $solicitacao['titulo']
                        ); ?>

                    </p>

                </div>



                <!-- COLUNA DIREITA -->

                <div class="modal-coluna">

                    <p class="modal-informacao">

                        <strong>Autor:</strong><br>

                        <?php echo htmlspecialchars(
                            $solicitacao['autor']
                        ); ?>

                    </p>



                    <p class="modal-informacao">

                        <strong>Data da solicitação:</strong><br>

                        <?php echo $dataFormatada; ?>

                    </p>



                    <p class="modal-informacao">

                        <strong>Status:</strong><br>

                        <?php echo htmlspecialchars($status); ?>

                    </p>

                </div>

            </div>



            <?php if (
                $status === 'Cancelado' &&
                !empty($solicitacao['motivoCancelamento'])
            ): ?>

                <div class="motivo-cancelamento">

                    <strong style="color: #800020;">

                        Motivo do cancelamento:

                    </strong>

                    <br>

                    <?php echo htmlspecialchars(
                        $solicitacao['motivoCancelamento']
                    ); ?>

                </div>

            <?php endif; ?>



            <!-- BOTÕES -->

            <?php if (
                $status === 'Aguardando confirmação'
            ): ?>

                <div class="modal-acoes">

                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="abrirCancelamento(
                            <?php echo $idSolicitacao; ?>
                        )"
                    >

                        Cancelar solicitação

                    </button>



                    <a
                        href="processarSolicitacao.php?acao=confirmar&id=<?php echo $idSolicitacao; ?>"
                        class="btn btn-success"
                    >

                        Confirmar solicitação

                    </a>

                </div>

            <?php endif; ?>



            <?php if ($status === 'Reservado'): ?>

                <div class="modal-acoes">

                    <button
                        type="button"
                        class="btn btn-warning"
                        onclick="abrirNaoRetirado(
                            <?php echo $idSolicitacao; ?>
                        )"
                    >

                        Não foi retirado

                    </button>



                    <a
                        href="processarSolicitacao.php?acao=retirada&id=<?php echo $idSolicitacao; ?>"
                        class="btn btn-success"
                    >

                        Confirmar retirada

                    </a>

                </div>

            <?php endif; ?>



        </div>

    </div>



    <!-- MODAL DE CANCELAMENTO -->

    <?php if (
        $status === 'Aguardando confirmação' ||
        $status === 'Reservado'
    ): ?>

        <div
            class="modal-overlay"
            id="modalCancelamento<?php echo $idSolicitacao; ?>"
        >

            <div class="modal-conteudo">



                <button
                    type="button"
                    class="modal-fechar"
                    onclick="fecharCancelamento(
                        <?php echo $idSolicitacao; ?>
                    )"
                    aria-label="Fechar"
                >

                    ×

                </button>



                <h4 class="modal-titulo">

                    Cancelar solicitação

                </h4>



                <p>

                    Informe o motivo do cancelamento:

                </p>



                <form
                    action="processarSolicitacao.php"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $idSolicitacao; ?>"
                    >



                    <input
                        type="hidden"
                        name="acao"
                        value="cancelar"
                    >



                    <textarea
                        name="motivoCancelamento"
                        class="form-control"
                        rows="4"
                        placeholder="Digite o motivo do cancelamento"
                        required
                    ></textarea>



                    <div class="modal-acoes">

                        <button
                            type="submit"
                            class="btn btn-danger"
                        >

                            Confirmar cancelamento

                        </button>



                        <button
                            type="button"
                            class="btn btn-secondary"
                            onclick="fecharCancelamento(
                                <?php echo $idSolicitacao; ?>
                            )"
                        >

                            Voltar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    <?php endif; ?>



    <!-- MODAL NÃO FOI RETIRADO -->

    <?php if ($status === 'Reservado'): ?>

        <div
            class="modal-overlay"
            id="modalNaoRetirado<?php echo $idSolicitacao; ?>"
        >

            <div class="modal-conteudo">



                <button
                    type="button"
                    class="modal-fechar"
                    onclick="fecharNaoRetirado(
                        <?php echo $idSolicitacao; ?>
                    )"
                    aria-label="Fechar"
                >

                    ×

                </button>



                <h4 class="modal-titulo">

                    Livro não retirado

                </h4>



                <p>

                    Ao confirmar esta opção, a reserva será cancelada
                    e o livro ficará novamente disponível para outros alunos.

                </p>



                <p>

                    Esta ação não poderá ser desfeita pelo sistema.

                </p>



                <form
                    action="processarSolicitacao.php"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo $idSolicitacao; ?>"
                    >



                    <input
                        type="hidden"
                        name="acao"
                        value="naoRetirado"
                    >



                    <div class="modal-acoes">

                        <button
                            type="submit"
                            class="btn btn-warning"
                        >

                            Confirmar

                        </button>



                        <button
                            type="button"
                            class="btn btn-secondary"
                            onclick="fecharNaoRetirado(
                                <?php echo $idSolicitacao; ?>
                            )"
                        >

                            Voltar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    <?php endif; ?>

<?php endwhile; ?>



<script>

    // ======================================================
    // FILTRO
    // ======================================================

    const botoesFiltro =
        document.querySelectorAll('.filtro-btn');



    const linhasSolicitacoes =
        document.querySelectorAll(
            '.solicitacao-linha'
        );



    botoesFiltro.forEach(function(botao) {

        botao.addEventListener(
            'click',
            function() {

                const filtro =
                    this.getAttribute('data-filtro');



                botoesFiltro.forEach(
                    function(item) {

                        item.classList.remove(
                            'ativo'
                        );

                    }
                );



                this.classList.add('ativo');



                linhasSolicitacoes.forEach(
                    function(linha) {

                        const status =
                            linha.getAttribute(
                                'data-status'
                            );



                        if (
                            filtro === 'Todas' ||
                            status === filtro
                        ) {

                            linha.classList.remove(
                                'solicitacao-oculta'
                            );

                        } else {

                            linha.classList.add(
                                'solicitacao-oculta'
                            );

                        }

                    }
                );

            }
        );

    });



    // ======================================================
    // DETALHES
    // ======================================================

    function abrirDetalhes(id) {

        const modal =
            document.getElementById(
                'modalDetalhes' + id
            );



        if (modal) {

            modal.style.display = 'flex';

        }

    }



    function fecharDetalhes(id) {

        const modal =
            document.getElementById(
                'modalDetalhes' + id
            );



        if (modal) {

            modal.style.display = 'none';

        }

    }



    // ======================================================
    // CANCELAMENTO
    // ======================================================

    function abrirCancelamento(id) {

        fecharDetalhes(id);



        const modal =
            document.getElementById(
                'modalCancelamento' + id
            );



        if (modal) {

            modal.style.display = 'flex';

        }

    }



    function fecharCancelamento(id) {

        const modal =
            document.getElementById(
                'modalCancelamento' + id
            );



        if (modal) {

            modal.style.display = 'none';

        }

    }



    // ======================================================
    // NÃO FOI RETIRADO
    // ======================================================

    function abrirNaoRetirado(id) {

        fecharDetalhes(id);



        const modal =
            document.getElementById(
                'modalNaoRetirado' + id
            );



        if (modal) {

            modal.style.display = 'flex';

        }

    }



    function fecharNaoRetirado(id) {

        const modal =
            document.getElementById(
                'modalNaoRetirado' + id
            );



        if (modal) {

            modal.style.display = 'none';

        }

    }



    // ======================================================
    // FECHAR MODAL CLICANDO FORA
    // ======================================================

    window.addEventListener(
        'click',
        function(event) {

            if (
                event.target.classList.contains(
                    'modal-overlay'
                )
            ) {

                event.target.style.display =
                    'none';

            }

        }
    );

</script>



</body>

</html>



<?php

include_once("footer.php");

?>