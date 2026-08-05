<?php include "header.php" ?>

<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $nomeUsuario = $matriculaUsuario = $dataNascimentoUsuario = $emailUsuario = $senhaUsuario = $confirmarSenhaUsuario = "";

    $erroPreenchimento = false;


    // Validação nomeUsuario
    if(empty($_POST["nomeUsuario"])){

        echo "<div class='alert alert-warning text-center'>O campo <strong>NOME</strong> é obrigatório!</div>";
        $erroPreenchimento = true;

    } else {

        $nomeUsuario = filtrar_entrada($_POST["nomeUsuario"]);

        if(!preg_match('/^[\p{L} ]+$/u', $nomeUsuario)){

            echo "<div class='alert alert-warning text-center'>O campo <strong>NOME</strong> deve conter apenas letras!</div>";
            $erroPreenchimento = true;

        }
    }


    // Validação matriculaUsuario
    if(empty($_POST["matriculaUsuario"])){

        echo "<div class='alert alert-warning text-center'>O campo <strong>MATRÍCULA</strong> é obrigatório!</div>";
        $erroPreenchimento = true;

    } else {

        $matriculaUsuario = filtrar_entrada($_POST["matriculaUsuario"]);

    }


    // Validação dataNascimentoUsuario
    if(empty($_POST["dataNascimentoUsuario"])){

        echo "<div class='alert alert-warning text-center'>O campo <strong>DATA DE NASCIMENTO</strong> é obrigatório!</div>";
        $erroPreenchimento = true;

    } else {

        $dataNascimentoUsuario = filtrar_entrada($_POST["dataNascimentoUsuario"]);

        if(strlen($dataNascimentoUsuario) != 10){

            echo "<div class='alert alert-warning text-center'><strong>DATA INVÁLIDA!</strong></div>";
            $erroPreenchimento = true;

        }
    }


    // Validação emailUsuario
    if(empty($_POST["emailUsuario"])){

        echo "<div class='alert alert-warning text-center'>O campo <strong>EMAIL</strong> é obrigatório!</div>";
        $erroPreenchimento = true;

    } else {

        $emailUsuario = filtrar_entrada($_POST["emailUsuario"]);

    }


    // Validação senhaUsuario
    if(empty($_POST["senhaUsuario"])){

        echo "<div class='alert alert-warning text-center'>O campo <strong>SENHA</strong> é obrigatório!</div>";
        $erroPreenchimento = true;

    } else {

        $senhaUsuario = md5(filtrar_entrada($_POST["senhaUsuario"]));

    }


    // Validação confirmarSenhaUsuario
    if(empty($_POST["confirmarSenhaUsuario"])){

        echo "<div class='alert alert-warning text-center'>O campo <strong>CONFIRMAR SENHA</strong> é obrigatório!</div>";
        $erroPreenchimento = true;

    } else {

        $confirmarSenhaUsuario = md5(filtrar_entrada($_POST["confirmarSenhaUsuario"]));

        if($senhaUsuario != $confirmarSenhaUsuario){

            echo "<div class='alert alert-warning text-center'>As <strong>SENHAS</strong> informadas são diferentes!</div>";
            $erroPreenchimento = true;

        }

    }



    // Se não houver erros, cadastra no banco
    if(!$erroPreenchimento){


        $inserirUsuario = "
            INSERT INTO Usuarios 
            (nomeUsuario, matriculaUsuario, dataNascimentoUsuario, emailUsuario, senhaUsuario)

            VALUES

            ('$nomeUsuario', '$matriculaUsuario', '$dataNascimentoUsuario', '$emailUsuario', '$senhaUsuario')
        ";


        include "conexaoBD.php";


        if(mysqli_query($conn, $inserirUsuario)){


            echo "
            <div class='alert alert-success text-center'>
                Os dados do <strong>USUÁRIO</strong> foram cadastrados com sucesso!
            </div>


            <div class='container mt-3 mb-3'>

                <table class='table'>

                    <tr>
                        <th>NOME</th>
                        <td>$nomeUsuario</td>
                    </tr>

                    <tr>
                        <th>MATRÍCULA</th>
                        <td>$matriculaUsuario</td>
                    </tr>

                    <tr>
                        <th>DATA DE NASCIMENTO</th>
                        <td>$dataNascimentoUsuario</td>
                    </tr>

                    <tr>
                        <th>EMAIL</th>
                        <td>$emailUsuario</td>
                    </tr>

                </table>

            </div>
            ";

        } else {


            echo "
            <div class='alert alert-danger text-center'>
                Erro ao tentar cadastrar <strong>USUÁRIO</strong> no banco de dados!
            </div>
            ";

        }

    }


} else {

    header("location:formUsuario.php");

}



// Função para filtrar entrada
function filtrar_entrada($dado){

    $dado = trim($dado);
    $dado = stripslashes($dado);
    $dado = htmlspecialchars($dado);

    return $dado;

}

?>

<?php include "footer.php" ?>