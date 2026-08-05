<?php

    $hostBD   = "localhost"; //Define o local do servidor de BD
    $userBD   = "root"; //Define o usuário do BD (Padrão: root)
    $senhaBD  = ""; //Define a senha do BD (Padrão: "" [Em branco]);
    $database = "estante_solidaria"; //Define com qual base será realizada a conexão

    //Função do PHP para estabelecer a conexão com o BD
    $conn = mysqli_connect($hostBD, $userBD, $senhaBD, $database);

    //Verifica se há conexão com BD
    if(!$conn){
        echo "<p>Erro ao tentar conectar a aplicação à base de dados <strong>$database</strong>!</p>";
        exit();
    }

?>