<?php

include "conexaoBD.php"; // Inclui conexão com o banco
session_start(); // Inicia sessão


// Recebe e filtra os dados enviados pelo formulário
$emailUsuario = mysqli_real_escape_string($conn, $_POST['emailUsuario']);
$senhaUsuario = mysqli_real_escape_string($conn, $_POST['senhaUsuario']);


// Consulta o usuário no banco
$buscarLogin = "SELECT *
                FROM Usuarios
                WHERE emailUsuario = '$emailUsuario'
                AND senhaUsuario = md5('$senhaUsuario')";


// Executa a consulta
$efetuarLogin = mysqli_query($conn, $buscarLogin);


// Verifica se encontrou o usuário
if($registro = mysqli_fetch_assoc($efetuarLogin)){


    // Criando variáveis de sessão
    $_SESSION['idUsuario'] = $registro['id_usuario'];
    $_SESSION['nomeUsuario'] = $registro['nomeUsuario'];
    $_SESSION['matriculaUsuario'] = $registro['matriculaUsuario'];
    $_SESSION['emailUsuario'] = $registro['emailUsuario'];
    $_SESSION['logado'] = true;


    // Redireciona para página inicial
    header("Location: index.php");
    exit();


}
else{


    // Caso login esteja incorreto
    header("Location: formLogin.php?erroLogin=dadosInvalidos");
    exit();


}

?>