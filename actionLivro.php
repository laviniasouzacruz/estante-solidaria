<?php include "header.php" ?>

<?php

    // Verifica se o método de envio do formulário é POST

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Cria variáveis para armazenar as informações do formulário

        $fotoLivro = $titulo = $autor = $editora = $categoria = $sinopse = $anoPublicacao = $estado = $quantidade = $status = "";

        // Variável para controle de erros de preenchimento

        $erroPreenchimento = false;


        if(empty($_POST["titulo"])){

            echo "<div class='alert alert-warning text-center'>

                    O <strong>TÍTULO DO LIVRO</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $titulo = filtrar_entrada($_POST["titulo"]);
        }


        if(empty($_POST["autor"])){

            echo "<div class='alert alert-warning text-center'>

                    O campo <strong>AUTOR</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $autor = filtrar_entrada($_POST["autor"]);
        }


        if(empty($_POST["editora"])){

            echo "<div class='alert alert-warning text-center'>

                    O campo <strong>EDITORA</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $editora = filtrar_entrada($_POST["editora"]);
        }


        if(empty($_POST["categoria"])){

            echo "<div class='alert alert-warning text-center'>

                    O campo <strong>CATEGORIA</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $categoria = filtrar_entrada($_POST["categoria"]);
        }


        if(empty($_POST["sinopse"])){

            echo "<div class='alert alert-warning text-center'>

                    O campo <strong>SINOPSE DO LIVRO</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $sinopse = filtrar_entrada($_POST["sinopse"]);
        }


        if(empty($_POST["anoPublicacao"])){

            echo "<div class='alert alert-warning text-center'>

                    O campo <strong>ANO DE PUBLICAÇÃO</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $anoPublicacao = filtrar_entrada($_POST["anoPublicacao"]);

            if($anoPublicacao < 1000 || $anoPublicacao > 2026){

                echo "<div class='alert alert-warning text-center'>

                        O <strong>ANO DE PUBLICAÇÃO</strong> deve estar entre 1000 e 2026!

                      </div>";

                $erroPreenchimento = true;
            }
        }


        if(empty($_POST["estado"])){

            echo "<div class='alert alert-warning text-center'>

                    O campo <strong>ESTADO DO LIVRO</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $estado = filtrar_entrada($_POST["estado"]);
        }


        if(empty($_POST["quantidade"])){

            echo "<div class='alert alert-warning text-center'>

                    O campo <strong>QUANTIDADE DE EXEMPLARES</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $quantidade = filtrar_entrada($_POST["quantidade"]);

            if($quantidade < 1){

                echo "<div class='alert alert-warning text-center'>

                        A <strong>QUANTIDADE</strong> deve ser maior que zero!

                      </div>";

                $erroPreenchimento = true;
            }
        }


        if(empty($_POST["status"])){

            echo "<div class='alert alert-warning text-center'>

                    O campo <strong>STATUS</strong> é obrigatório!

                  </div>";

            $erroPreenchimento = true;
        }
        else{

            $status = filtrar_entrada($_POST["status"]);
        }


        // Diretório onde as fotos dos livros serão armazenadas

        $diretorio = "images/livros/";

        // Monta o caminho da imagem

        $fotoLivro = $diretorio . basename($_FILES["fotoLivro"]["name"]);

        // Pega a extensão da imagem

        $tipoDaImagem = strtolower(
            pathinfo($fotoLivro, PATHINFO_EXTENSION)
        );

        // Controle de erros do upload

        $erroUpload = false;


        // Verifica se uma foto foi enviada

        if($_FILES["fotoLivro"]["size"] != 0){

            // Verifica se o tamanho da foto é maior que 5MB

            if($_FILES["fotoLivro"]["size"] > 5000000){

                echo "<div class='alert alert-warning text-center'>

                        O tamanho da <strong>FOTO</strong> deve ser menor do que 5MB!

                      </div>";

                $erroUpload = true;
            }


            // Verifica o formato da imagem

            if(
                $tipoDaImagem != "jpg" &&
                $tipoDaImagem != "jpeg" &&
                $tipoDaImagem != "png" &&
                $tipoDaImagem != "webp"
            ){

                echo "<div class='alert alert-warning text-center'>

                        A <strong>FOTO</strong> deve estar nos formatos JPG, JPEG, PNG ou WEBP!

                      </div>";

                $erroUpload = true;
            }


            // Move a imagem para images/livros/

            if(!$erroUpload){

                if(!move_uploaded_file(
                    $_FILES["fotoLivro"]["tmp_name"],
                    $fotoLivro
                )){

                    echo "<div class='alert alert-danger text-center'>

                            Erro ao tentar mover a <strong>FOTO</strong> para o diretório $diretorio!

                          </div>";

                    $erroUpload = true;
                }
            }
        }
        else{

            echo "<div class='alert alert-warning text-center'>

                    A <strong>FOTO DO LIVRO</strong> é obrigatória!

                  </div>";

            $erroUpload = true;
        }


        if(!$erroPreenchimento && !$erroUpload){

            // Inclui a conexão com o banco

            include "conexaoBD.php";


            // Insere os dados do livro no banco

            $inserirLivro = "INSERT INTO livros

                            (

                                fotoLivro,

                                titulo,

                                autor,

                                editora,

                                categoria,

                                sinopse,

                                anoPublicacao,

                                estado,

                                quantidade,

                                status,

                                idUsuario

                            )

                            VALUES

                            (

                                '$fotoLivro',

                                '$titulo',

                                '$autor',

                                '$editora',

                                '$categoria',

                                '$sinopse',

                                '$anoPublicacao',

                                '$estado',

                                '$quantidade',

                                '$status',

                                '$idUsuario'

                            )";


            // Executa o INSERT

            if(mysqli_query($conn, $inserirLivro)){

                echo "<div class='alert alert-success text-center'>

                        O <strong>LIVRO</strong> foi cadastrado com sucesso!

                      </div>";


                // Exibe os dados cadastrados

                echo "

                    <div class='container mt-3 mb-3'>

                        <div class='container mt-3 mb-3 text-center'>

                            <img

                                src='$fotoLivro'

                                title='Foto de $titulo'

                                style='width:150px'

                                class='img-thumbnail'>

                        </div>


                        <table class='table'>

                            <tr>

                                <th>TÍTULO DO LIVRO</th>

                                <td>$titulo</td>

                            </tr>

                            <tr>

                                <th>AUTOR</th>

                                <td>$autor</td>

                            </tr>

                            <tr>

                                <th>EDITORA</th>

                                <td>$editora</td>

                            </tr>

                            <tr>

                                <th>CATEGORIA</th>

                                <td>$categoria</td>

                            </tr>

                            <tr>

                                <th>SINOPSE DO LIVRO</th>

                                <td>$sinopse</td>

                            </tr>

                            <tr>

                                <th>ANO DE PUBLICAÇÃO</th>

                                <td>$anoPublicacao</td>

                            </tr>

                            <tr>

                                <th>ESTADO DO LIVRO</th>

                                <td>$estado</td>

                            </tr>

                            <tr>

                                <th>QUANTIDADE DE EXEMPLARES</th>

                                <td>$quantidade</td>

                            </tr>

                            <tr>

                                <th>STATUS</th>

                                <td>$status</td>

                            </tr>

                            <tr>

                                <th>ID DO USUÁRIO</th>

                                <td>$idUsuario</td>

                            </tr>

                        </table>

                    </div>

                ";

            }

            else{

                echo "<div class='alert alert-danger text-center'>

                        Erro ao tentar cadastrar <strong>LIVRO</strong>

                        no banco de dados!

                      </div>";

            }

        }

    }

    else{

        // Se não for POST, volta para o formulário

        header("location:formLivros.php");

    }


    function filtrar_entrada($dado){

        $dado = trim($dado);

        $dado = stripslashes($dado);

        $dado = htmlspecialchars($dado);

        return($dado);

    }

?>

<?php include "footer.php" ?>
