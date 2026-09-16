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
 * PAGINAÇÃO
 */

$livrosPorPagina = 16;

$paginaAtual = isset($_GET['pagina']) && is_numeric($_GET['pagina'])
    ? (int) $_GET['pagina']
    : 1;

if ($paginaAtual < 1) {
    $paginaAtual = 1;
}


/**
 * Quantidade total de livros disponíveis
 */

$sqlTotalLivros = "SELECT
                    COUNT(DISTINCT l.idLivro) AS total

                   FROM livros l

                   INNER JOIN exemplares e
                       ON e.idLivro = l.idLivro

                   WHERE e.status = 'Disponivel'";


$resultadoTotalLivros = mysqli_query($conn, $sqlTotalLivros);

if (!$resultadoTotalLivros) {

    die("Erro ao contar livros: " . mysqli_error($conn));

}


$totalLivros = (int) mysqli_fetch_assoc($resultadoTotalLivros)['total'];

$totalPaginas = max(1, (int) ceil($totalLivros / $livrosPorPagina));


if ($paginaAtual > $totalPaginas) {
    $paginaAtual = $totalPaginas;
}


$offset = ($paginaAtual - 1) * $livrosPorPagina;


/**
 * Busca os livros da página atual
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

              GROUP BY
                  l.idLivro,
                  l.titulo,
                  l.autor,
                  l.editora,
                  l.anoPublicacao,
                  l.fotoLivro,
                  l.categoria

              ORDER BY l.idLivro DESC

              LIMIT $offset, $livrosPorPagina";


$resultadoLivros = mysqli_query($conn, $sqlLivros);

if (!$resultadoLivros) {

    die("Erro ao buscar livros: " . mysqli_error($conn));

}


/**
 * Categorias na mesma ordem do header
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
 * Quantidade de livros disponíveis em cada categoria
 */

$quantidadesCategorias = [];

$sqlCategorias = "SELECT
                    l.categoria,
                    COUNT(DISTINCT l.idLivro) AS quantidade

                  FROM livros l

                  INNER JOIN exemplares e
                      ON e.idLivro = l.idLivro

                  WHERE e.status = 'Disponivel'

                  GROUP BY l.categoria";


$resultadoCategorias = mysqli_query($conn, $sqlCategorias);

if (!$resultadoCategorias) {

    die("Erro ao buscar categorias: " . mysqli_error($conn));

}


while ($categoria = mysqli_fetch_assoc($resultadoCategorias)) {

    $quantidadesCategorias[$categoria['categoria']] = $categoria['quantidade'];

}

?>


<section
    id="books-page"
    class="position-relative"
    style="
        padding-top: 0;
        padding-bottom: 0;
        margin-top: -25px;
        margin-bottom: -80px;
    "
>

    <div class="container-fluid px-4 px-lg-5">

        <div class="text-center mb-4">

            <h1
                style="
                    color: #841717;
                    font-size: 2.5rem;
                    margin-bottom: 5px;
                "
            >

                Nossos livros

            </h1>

            <p
                style="
                    max-width: 700px;
                    margin: 0 auto;
                    font-size: 1.1rem;
                    line-height: 1.5;
                "
            >

                Encontre um livro disponível na Estante Solidária
                e escolha a história que você deseja levar para casa.

            </p>

        </div>


        <div class="row g-4 align-items-start">

            <!-- CARD DE CATEGORIAS -->

            <div class="col-auto">

                <div
                    class="card p-4 rounded-3"
                    style="
                        width: 290px;
                        border: 1px solid #841717;
                        box-shadow: 4px 4px 0px #841717;
                    "
                >

                    <h5
                        class="fw-bold mb-2"
                        style="
                            color: #841717;
                            line-height: 1.4;
                        "
                    >

                        Quer pesquisar de maneira mais fácil?

                    </h5>


                    <p
                        class="mb-4"
                        style="
                            font-size: 0.95rem;
                            line-height: 1.5;
                        "
                    >

                        Escolha uma categoria e encontre livros
                        de um jeito mais rápido.

                    </p>


                    <div>

                        <?php foreach ($categorias as $slug => $nomeCategoria): ?>

                            <?php

                            $quantidadeCategoria = 0;

                            if (isset($quantidadesCategorias[$nomeCategoria])) {

                                $quantidadeCategoria = $quantidadesCategorias[$nomeCategoria];

                            }

                            ?>


                            <a
                                href="categoria.php?categoria=<?php echo $slug; ?>"
                                class="d-flex justify-content-between align-items-center text-decoration-none py-2"
                                style="
                                    color: #272727;
                                    border-bottom: 1px solid #eeeeee;
                                "
                            >

                                <span>

                                    <?php echo htmlspecialchars($nomeCategoria); ?>

                                </span>


                                <span
                                    style="
                                        color: #841717;
                                        font-weight: 700;
                                    "
                                >

                                    <?php echo $quantidadeCategoria; ?>

                                </span>

                            </a>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>


            <!-- ÁREA DOS LIVROS -->

            <div class="col">

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


                                    <div
                                        style="
                                            height: 125px;
                                            padding-top: 16px;
                                            overflow: hidden;
                                        "
                                    >

                                        <h6 class="mb-0 fw-bold">

                                            <a
                                                href="livro.php?id=<?php echo $livro['idLivro']; ?>"
                                                title="<?php echo htmlspecialchars($livro['titulo']); ?>"
                                            >

                                                <?php echo htmlspecialchars(limitarTexto($livro['titulo'], 28)); ?>

                                            </a>

                                        </h6>


                                        <p
                                            class="my-2 fs-6 text-black-50"
                                            title="<?php echo htmlspecialchars($livro['autor']); ?>"
                                        >

                                            <?php echo htmlspecialchars(limitarTexto($livro['autor'], 19)); ?>

                                        </p>


                                        <span
                                            class="text-primary fw-bold mb-2 fs-6"
                                            title="<?php echo htmlspecialchars($livro['categoria']); ?>"
                                        >

                                            <?php echo htmlspecialchars($livro['categoria']); ?>

                                        </span>

                                    </div>


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


                    <!-- PAGINAÇÃO -->

                    <?php if ($totalPaginas > 1): ?>

                        <div
                            class="d-flex justify-content-center align-items-center flex-wrap"
                            style="
                                gap: 8px;
                                margin-top: 45px;
                                margin-bottom: 20px;
                            "
                        >

                            <!-- SETA ANTERIOR -->

                            <?php if ($paginaAtual > 1): ?>

                                <a
                                    href="livros.php?pagina=<?php echo $paginaAtual - 1; ?>"
                                    class="d-flex align-items-center justify-content-center text-decoration-none"
                                    style="
                                        width: 40px;
                                        height: 40px;
                                        background-color: #841717;
                                        color: #fff;
                                        border-radius: 6px;
                                        font-size: 20px;
                                        font-weight: 600;
                                    "
                                >

                                    ‹

                                </a>

                            <?php endif; ?>


                            <?php

                            $paginasExibidas = [];


                            if ($totalPaginas <= 7) {

                                for ($i = 1; $i <= $totalPaginas; $i++) {

                                    $paginasExibidas[] = $i;

                                }

                            } else {

                                $paginasExibidas[] = 1;


                                if ($paginaAtual <= 4) {

                                    $paginasExibidas[] = 2;
                                    $paginasExibidas[] = 3;
                                    $paginasExibidas[] = 4;
                                    $paginasExibidas[] = 5;
                                    $paginasExibidas[] = '...';

                                } elseif ($paginaAtual >= $totalPaginas - 3) {

                                    $paginasExibidas[] = '...';
                                    $paginasExibidas[] = $totalPaginas - 4;
                                    $paginasExibidas[] = $totalPaginas - 3;
                                    $paginasExibidas[] = $totalPaginas - 2;
                                    $paginasExibidas[] = $totalPaginas - 1;

                                } else {

                                    $paginasExibidas[] = '...';
                                    $paginasExibidas[] = $paginaAtual - 2;
                                    $paginasExibidas[] = $paginaAtual - 1;
                                    $paginasExibidas[] = $paginaAtual;
                                    $paginasExibidas[] = $paginaAtual + 1;
                                    $paginasExibidas[] = $paginaAtual + 2;
                                    $paginasExibidas[] = '...';

                                }


                                $paginasExibidas[] = $totalPaginas;

                            }


                            foreach ($paginasExibidas as $pagina): ?>

                                <?php if ($pagina === '...'): ?>

                                    <span
                                        class="d-flex align-items-center justify-content-center"
                                        style="
                                            width: 40px;
                                            height: 40px;
                                            color: #841717;
                                            font-weight: 700;
                                            font-size: 18px;
                                        "
                                    >

                                        ...

                                    </span>

                                <?php else: ?>

                                    <a
                                        href="livros.php?pagina=<?php echo $pagina; ?>"
                                        class="d-flex align-items-center justify-content-center text-decoration-none"
                                        style="
                                            width: 40px;
                                            height: 40px;
                                            border: 1px solid #841717;
                                            border-radius: 6px;
                                            background-color: <?php echo ($pagina == $paginaAtual) ? '#841717' : '#fff'; ?>;
                                            color: <?php echo ($pagina == $paginaAtual) ? '#fff' : '#841717'; ?>;
                                            font-weight: 600;
                                        "
                                    >

                                        <?php echo $pagina; ?>

                                    </a>

                                <?php endif; ?>

                            <?php endforeach; ?>


                            <!-- SETA PRÓXIMA -->

                            <?php if ($paginaAtual < $totalPaginas): ?>

                                <a
                                    href="livros.php?pagina=<?php echo $paginaAtual + 1; ?>"
                                    class="d-flex align-items-center justify-content-center text-decoration-none"
                                    style="
                                        width: 40px;
                                        height: 40px;
                                        background-color: #841717;
                                        color: #fff;
                                        border-radius: 6px;
                                        font-size: 20px;
                                        font-weight: 600;
                                    "
                                >

                                    ›

                                </a>

                            <?php endif; ?>

                        </div>

                    <?php endif; ?>


                <?php else: ?>

                    <div class="text-center py-5">

                        <h4 style="color: #841717;">

                            Nenhum livro disponível

                        </h4>

                        <p class="mb-0">

                            No momento, não há livros disponíveis para solicitação.

                        </p>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>


<?php include_once("footer.php"); ?>