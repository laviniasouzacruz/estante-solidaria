<?php
    session_start(); //Inicia a sessão
    session_unset(); //Apaga os dados da Sessão
    session_destroy(); //Destroí a Sessão

    header("Location: formLogin.php");
    exit();
?>