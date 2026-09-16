<?php

include_once("header.php");
include_once("conexaoBD.php");


function limitarTexto($texto, $limite) {

    $contador = 0;
    $resultado = '';

    $caracteres = preg_split('//u', $texto, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($caracteres as $caractere) {

        if (!preg_match('/\s/u', $caractere)) {
            $contador++;
        }

        if ($contador > $limite) {

            $resultado .= '...';

            break;

        }

        $resultado .= $caractere;

    }

    return $resultado;

}


/**
 * Categorias disponíveis
 */

$categorias = [

    'ficcao' => 'Ficção',

    'poesia' => 'Poesia',

    'romance' => 'Romance',

    'fantasia' => 'Fantasia',

    'suspense-terror' => 'Suspense e Terror',

    'automacao-mecanica' => 'Automação e Mecânica',

    'informatica-tecnologia' => 'Informática e Tecnologia',

    'educacionais-didaticos' => 'Livros Educacionais e Didáticos'

];


/**
 * Pega a categoria enviada pela URL
 */

$categoriaUrl = isset($_GET['categoria']) ? $_GET['categoria'] : '';


/**
 * Verifica se a categoria existe
 */

if (!isset($categorias[$categoriaUrl])) {

    header("Location: livros.php");

    exit;

}


$categoriaSelecionada = $categorias[$categoriaUrl];


/**
 * Busca somente os livros da categoria selecionada
 * que possuem pelo menos um exemplar disponível
 * e coloca em ordem alfabética pelo título
 */

$sqlLivros = "SELECT
                l.idLivro,
                l.titulo,
                l.autor,
                l.editora,
                l.anoPublicacao,
                l.fotoLivro,
                l.categoria

              FROM livros l

              INNER JOIN exemplares e
                  ON e.idLivro = l.idLivro

              WHERE e.status = 'Disponivel'

                AND l.categoria = ?

              GROUP BY
                  l.idLivro,
                  l.titulo,
                  l.autor,
                  l.editora,
                  l.anoPublicacao,
                  l.fotoLivro,
                  l.categoria

              ORDER BY l.titulo ASC";


$stmt = mysqli_prepare($conn, $sqlLivros);

if (!$stmt) {

    die("Erro ao preparar consulta: " . mysqli_error($conn));

}


mysqli_stmt_bind_param($stmt, "s", $categoriaSelecionada);

mysqli_stmt_execute($stmt);

$resultadoLivros = mysqli_stmt_get_result($stmt);

if (!$resultadoLivros) {

    die("Erro ao buscar livros: " . mysqli_error($conn));

}

?>


<section
    id="category-page"
    class="position-relative"
    style="
        padding-top: 0;
        padding-bottom: 5px;
        margin-top: -40px;
        margin-bottom: -80px;
    "
>

    <div class="container">


        <!-- TÍTULO DA CATEGORIA -->

        <div class="text-center mb-4">

            <h1
                style="
                    color: #841717;
                    font-size: 2.5rem;
                    margin-top: 20px;
                    margin-bottom: 12px;
                "
            >

                <?php echo htmlspecialchars($categoriaSelecionada); ?>

            </h1>


            <p
                style="
                    max-width: 700px;
                    margin: 0 auto;
                    font-size: 1.1rem;
                    line-height: 1.5;
                "
            >

                Confira os livros disponíveis na categoria
                <?php echo htmlspecialchars($categoriaSelecionada); ?>.

            </p>

        </div>


        <!-- LIVROS -->

        <?php if (mysqli_num_rows($resultadoLivros) > 0): ?>

            <div class="row g-4">


                <?php while ($livro = mysqli_fetch_assoc($resultadoLivros)): ?>

                    <div class="col-md-6 col-lg-3">


                        <div
                            class="card position-relative p-4 rounded-3 h-100 d-flex flex-column"
                            style="
                                height: 100%;
                                border: 1px solid #841717;
                                box-shadow: 4px 4px 0px #841717;
                            "
                        >


                            <!-- CAPA -->

                            <img
                                src="<?php echo htmlspecialchars($livro['fotoLivro']); ?>"
                                class="img-fluid"
                                alt="Capa do livro <?php echo htmlspecialchars($livro['titulo']); ?>"
                                style="
                                    height: 320px;
                                    width: 100%;
                                    object-fit: contain;
                                    box-shadow: 0 4px 10px rgba(107, 31, 42, 0.38);
                                "
                            >


                            <!-- INFORMAÇÕES -->

                            <div
                                style="
                                    height: 125px;
                                    padding-top: 16px;
                                    overflow: hidden;
                                "
                            >


                                <!-- TÍTULO -->

                                <h6 class="mb-0 fw-bold">

                                    <a
                                        href="livro.php?id=<?php echo $livro['idLivro']; ?>"
                                        title="<?php echo htmlspecialchars($livro['titulo']); ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            limitarTexto($livro['titulo'], 28)
                                        );

                                        ?>

                                    </a>

                                </h6>


                                <!-- AUTOR -->

                                <p
                                    class="my-2 fs-6 text-black-50"
                                    title="<?php echo htmlspecialchars($livro['autor']); ?>"
                                >

                                    <?php

                                    echo htmlspecialchars(
                                        limitarTexto($livro['autor'], 19)
                                    );

                                    ?>

                                </p>


                                <!-- CATEGORIA -->

                                <span
                                    class="text-primary fw-bold mb-2 fs-6"
                                    title="<?php echo htmlspecialchars($livro['categoria']); ?>"
                                >

                                    <?php echo htmlspecialchars($livro['categoria']); ?>

                                </span>


                            </div>


                            <!-- BOTÃO -->

                            <div
                                class="pt-3 text-center"
                                style="
                                    margin-top: 10px;
                                "
                            >

                                <a
                                    href="solicitar.php?id=<?php echo $livro['idLivro']; ?>"
                                    class="btn"
                                >

                                    Solicitar livro

                                </a>

                            </div>


                        </div>


                    </div>


                <?php endwhile; ?>


            </div>


        <?php else: ?>


            <!-- NENHUM LIVRO -->

            <div class="text-center py-5">

                <h4 style="color: #841717;">

                    Nenhum livro disponível

                </h4>


                <p class="mb-4">

                    No momento, não há livros disponíveis
                    nesta categoria.

                </p>


                <a
                    href="livros.php"
                    class="btn"
                >

                    Ver todos os livros

                </a>

            </div>


        <?php endif; ?>


    </div>

</section>


<?php include_once("footer.php"); ?>