<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("conexaoBD.php");


// ======================================================
// VERIFICA LOGIN
// ======================================================

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: formLogin.php");
    exit();
}


// ======================================================
// VERIFICA ADMINISTRADOR
// ======================================================

if (
    !isset($_SESSION['tipoUsuario']) ||
    $_SESSION['tipoUsuario'] !== 'Administrador'
) {
    header("Location: index.php");
    exit();
}


// ======================================================
// VERIFICA DADOS
// ======================================================

if (
    (!isset($_GET['id']) && !isset($_POST['id'])) ||
    (!isset($_GET['acao']) && !isset($_POST['acao']))
) {
    header("Location: visualizarSolicitacoes.php");
    exit();
}


// ======================================================
// PEGA O ID
// ======================================================

if (isset($_POST['id'])) {

    $idSolicitacao = (int) $_POST['id'];

} else {

    $idSolicitacao = (int) $_GET['id'];

}


// ======================================================
// PEGA A AÇÃO
// ======================================================

if (isset($_POST['acao'])) {

    $acao = $_POST['acao'];

} else {

    $acao = $_GET['acao'];

}


// ======================================================
// VERIFICA AÇÃO
// ======================================================

$acoesPermitidas = array(
    'confirmar',
    'cancelar',
    'retirada',
    'naoRetirado'
);

if (!in_array($acao, $acoesPermitidas)) {

    header("Location: visualizarSolicitacoes.php");
    exit();

}


// ======================================================
// CONFIRMAR SOLICITAÇÃO
// ======================================================

if ($acao === 'confirmar') {

    mysqli_begin_transaction($conn);

    try {

        $sqlBusca = "SELECT
                        s.idSolicitacao,
                        s.idLivro,
                        s.idExemplar,
                        s.status AS statusSolicitacao,
                        e.status AS statusExemplar
                     FROM solicitacoes s
                     INNER JOIN exemplares e
                        ON s.idExemplar = e.idExemplar
                     WHERE s.idSolicitacao = $idSolicitacao
                     FOR UPDATE";

        $resultadoBusca = mysqli_query(
            $conn,
            $sqlBusca
        );

        if (!$resultadoBusca) {

            throw new Exception(
                "Erro ao buscar solicitação."
            );

        }

        if (mysqli_num_rows($resultadoBusca) === 0) {

            throw new Exception(
                "Solicitação não encontrada."
            );

        }

        $dados = mysqli_fetch_assoc(
            $resultadoBusca
        );


        // Verifica se ainda está aguardando confirmação
        if (
            $dados['statusSolicitacao'] !==
            'Aguardando confirmação'
        ) {

            throw new Exception(
                "Esta solicitação não está aguardando confirmação."
            );

        }


        // Verifica se o exemplar ainda está disponível
        if ($dados['statusExemplar'] !== 'Disponivel') {

            throw new Exception(
                "Este exemplar não está mais disponível."
            );

        }


        $idExemplar = (int) $dados['idExemplar'];


        // Atualiza a solicitação
        $sqlSolicitacao = "UPDATE solicitacoes
                           SET status = 'Reservado',
                               motivoCancelamento = NULL
                           WHERE idSolicitacao = $idSolicitacao
                           AND status = 'Aguardando confirmação'";

        if (!mysqli_query($conn, $sqlSolicitacao)) {

            throw new Exception(
                "Erro ao confirmar solicitação."
            );

        }


        // Reserva o exemplar
        $sqlExemplar = "UPDATE exemplares
                        SET status = 'Reservado'
                        WHERE idExemplar = $idExemplar
                        AND status = 'Disponivel'";

        if (!mysqli_query($conn, $sqlExemplar)) {

            throw new Exception(
                "Erro ao reservar exemplar."
            );

        }


        // Garante que o exemplar realmente foi atualizado
        if (mysqli_affected_rows($conn) === 0) {

            throw new Exception(
                "O exemplar não está mais disponível."
            );

        }


        mysqli_commit($conn);

    } catch (Exception $e) {

        mysqli_rollback($conn);

        die($e->getMessage());

    }

}


// ======================================================
// CANCELAR SOLICITAÇÃO
// ======================================================

if ($acao === 'cancelar') {

    if (!isset($_POST['motivoCancelamento'])) {

        die(
            "O motivo do cancelamento não foi informado."
        );

    }

    $motivoCancelamento = trim(
        $_POST['motivoCancelamento']
    );

    if ($motivoCancelamento === '') {

        die(
            "O motivo do cancelamento não pode ficar vazio."
        );

    }

    $motivoCancelamento = mysqli_real_escape_string(
        $conn,
        $motivoCancelamento
    );


    mysqli_begin_transaction($conn);

    try {

        // Busca a solicitação e o exemplar vinculado
        $sqlBusca = "SELECT
                        s.idSolicitacao,
                        s.idExemplar,
                        s.status
                     FROM solicitacoes s
                     WHERE s.idSolicitacao = $idSolicitacao
                     FOR UPDATE";

        $resultadoBusca = mysqli_query(
            $conn,
            $sqlBusca
        );

        if (!$resultadoBusca) {

            throw new Exception(
                "Erro ao buscar solicitação: " .
                mysqli_error($conn)
            );

        }

        if (mysqli_num_rows($resultadoBusca) === 0) {

            throw new Exception(
                "Solicitação não encontrada."
            );

        }

        $solicitacao = mysqli_fetch_assoc(
            $resultadoBusca
        );


        // Só pode cancelar solicitações aguardando ou reservadas
        if (
            $solicitacao['status'] !== 'Aguardando confirmação' &&
            $solicitacao['status'] !== 'Reservado'
        ) {

            throw new Exception(
                "Esta solicitação não pode mais ser cancelada."
            );

        }


        // Cancela a solicitação
        $sql = "UPDATE solicitacoes
                SET status = 'Cancelado',
                    motivoCancelamento = '$motivoCancelamento'
                WHERE idSolicitacao = $idSolicitacao
                AND status IN (
                    'Aguardando confirmação',
                    'Reservado'
                )";

        if (!mysqli_query($conn, $sql)) {

            throw new Exception(
                "Erro ao cancelar solicitação: " .
                mysqli_error($conn)
            );

        }


        // Se estava reservada, libera o exemplar
        if ($solicitacao['status'] === 'Reservado') {

            $idExemplar = (int) $solicitacao['idExemplar'];


            $sqlExemplar = "UPDATE exemplares
                            SET status = 'Disponivel'
                            WHERE idExemplar = $idExemplar
                            AND status = 'Reservado'";

            if (!mysqli_query($conn, $sqlExemplar)) {

                throw new Exception(
                    "Erro ao liberar exemplar: " .
                    mysqli_error($conn)
                );

            }

        }


        mysqli_commit($conn);

    } catch (Exception $e) {

        mysqli_rollback($conn);

        die($e->getMessage());

    }

}


// ======================================================
// CONFIRMAR RETIRADA
// ======================================================

if ($acao === 'retirada') {

    mysqli_begin_transaction($conn);

    try {

        $sqlBusca = "SELECT
                        s.idSolicitacao,
                        s.idExemplar,
                        s.status AS statusSolicitacao,
                        e.status AS statusExemplar
                     FROM solicitacoes s
                     INNER JOIN exemplares e
                        ON s.idExemplar = e.idExemplar
                     WHERE s.idSolicitacao = $idSolicitacao
                     FOR UPDATE";

        $resultadoBusca = mysqli_query(
            $conn,
            $sqlBusca
        );

        if (!$resultadoBusca) {

            throw new Exception(
                "Erro ao buscar solicitação."
            );

        }

        if (mysqli_num_rows($resultadoBusca) === 0) {

            throw new Exception(
                "Solicitação não encontrada."
            );

        }

        $dados = mysqli_fetch_assoc(
            $resultadoBusca
        );


        // Só pode registrar retirada de uma reserva
        if ($dados['statusSolicitacao'] !== 'Reservado') {

            throw new Exception(
                "Esta solicitação não está reservada."
            );

        }


        // O exemplar precisa continuar reservado
        if ($dados['statusExemplar'] !== 'Reservado') {

            throw new Exception(
                "O exemplar vinculado a esta solicitação não está reservado."
            );

        }


        $idExemplar = (int) $dados['idExemplar'];


        // Atualiza a solicitação
        $sqlSolicitacao = "UPDATE solicitacoes
                           SET status = 'Retirado'
                           WHERE idSolicitacao = $idSolicitacao
                           AND status = 'Reservado'";

        if (!mysqli_query($conn, $sqlSolicitacao)) {

            throw new Exception(
                "Erro ao registrar retirada."
            );

        }


        // O exemplar foi retirado e deixa de estar disponível
        $sqlExemplar = "UPDATE exemplares
                        SET status = 'Indisponivel'
                        WHERE idExemplar = $idExemplar
                        AND status = 'Reservado'";

        if (!mysqli_query($conn, $sqlExemplar)) {

            throw new Exception(
                "Erro ao atualizar exemplar."
            );

        }


        mysqli_commit($conn);

    } catch (Exception $e) {

        mysqli_rollback($conn);

        die($e->getMessage());

    }

}


// ======================================================
// NÃO FOI RETIRADO
// ======================================================

if ($acao === 'naoRetirado') {

    mysqli_begin_transaction($conn);

    try {

        $sqlBusca = "SELECT
                        s.idSolicitacao,
                        s.idExemplar,
                        s.status AS statusSolicitacao,
                        e.status AS statusExemplar
                     FROM solicitacoes s
                     INNER JOIN exemplares e
                        ON s.idExemplar = e.idExemplar
                     WHERE s.idSolicitacao = $idSolicitacao
                     FOR UPDATE";

        $resultadoBusca = mysqli_query(
            $conn,
            $sqlBusca
        );

        if (!$resultadoBusca) {

            throw new Exception(
                "Erro ao buscar solicitação."
            );

        }

        if (mysqli_num_rows($resultadoBusca) === 0) {

            throw new Exception(
                "Solicitação não encontrada."
            );

        }

        $dados = mysqli_fetch_assoc(
            $resultadoBusca
        );


        // Só pode marcar "Não foi retirado"
        // quando a solicitação estiver reservada
        if ($dados['statusSolicitacao'] !== 'Reservado') {

            throw new Exception(
                "Esta solicitação não está reservada."
            );

        }


        // O exemplar também precisa estar reservado
        if ($dados['statusExemplar'] !== 'Reservado') {

            throw new Exception(
                "O exemplar vinculado a esta solicitação não está reservado."
            );

        }


        $motivo = "Reserva cancelada porque o livro não foi retirado pelo aluno.";

        $motivo = mysqli_real_escape_string(
            $conn,
            $motivo
        );


        // Cancela a solicitação
        $sqlSolicitacao = "UPDATE solicitacoes
                           SET status = 'Cancelado',
                               motivoCancelamento = '$motivo'
                           WHERE idSolicitacao = $idSolicitacao
                           AND status = 'Reservado'";

        if (!mysqli_query($conn, $sqlSolicitacao)) {

            throw new Exception(
                "Erro ao cancelar reserva."
            );

        }


        // Libera o exemplar
        $idExemplar = (int) $dados['idExemplar'];

        $sqlExemplar = "UPDATE exemplares
                        SET status = 'Disponivel'
                        WHERE idExemplar = $idExemplar
                        AND status = 'Reservado'";

        if (!mysqli_query($conn, $sqlExemplar)) {

            throw new Exception(
                "Erro ao liberar exemplar."
            );

        }


        mysqli_commit($conn);

    } catch (Exception $e) {

        mysqli_rollback($conn);

        die($e->getMessage());

    }

}


// ======================================================
// VOLTA PARA O PAINEL
// ======================================================

header("Location: visualizarSolicitacoes.php");
exit();

?>