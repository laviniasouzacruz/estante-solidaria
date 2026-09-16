<?php include "header.php" ?>

<?php

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

$sqlLivros = "WITH livros_categoria AS (

                SELECT
                    l.idLivro,
                    l.titulo,
                    l.autor,
                    l.editora,
                    l.anoPublicacao,
                    l.fotoLivro,
                    l.categoria,
                    ROW_NUMBER() OVER (
                        PARTITION BY l.categoria
                        ORDER BY l.idLivro DESC
                    ) AS ordem_categoria

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
              )

              SELECT
                idLivro,
                titulo,
                autor,
                editora,
                anoPublicacao,
                fotoLivro,
                categoria

              FROM (

                SELECT
                    idLivro,
                    titulo,
                    autor,
                    editora,
                    anoPublicacao,
                    fotoLivro,
                    categoria,
                    0 AS prioridade

                FROM livros_categoria

                WHERE ordem_categoria = 1

                UNION ALL

                SELECT
                    l.idLivro,
                    l.titulo,
                    l.autor,
                    l.editora,
                    l.anoPublicacao,
                    l.fotoLivro,
                    l.categoria,
                    1 AS prioridade

                FROM livros l

                INNER JOIN exemplares e
                    ON e.idLivro = l.idLivro

                WHERE e.status = 'Disponivel'

                  AND NOT EXISTS (
                    SELECT 1

                    FROM livros_categoria lc

                    WHERE lc.ordem_categoria = 1
                      AND lc.idLivro = l.idLivro
                  )

                GROUP BY
                    l.idLivro,
                    l.titulo,
                    l.autor,
                    l.editora,
                    l.anoPublicacao,
                    l.fotoLivro,
                    l.categoria

              ) AS livros_selecionados

              ORDER BY prioridade ASC, idLivro DESC

              LIMIT 12";

$resultadoLivros = mysqli_query($conn, $sqlLivros);

if (!$resultadoLivros) {

    die("Erro ao buscar livros: " . mysqli_error($conn));

}

?>

<section class="relative w-full overflow-hidden">

    <div id="carousel" class="relative w-full">

        <div class="carousel-slide relative min-h-[420px] flex items-center">

            <img
                src="images/livros01.jpg"
                alt="Estante Solidária"
                class="absolute inset-0 w-full h-full object-cover"
            >

            <div class="absolute inset-0 bg-black/45"></div>

            <div class="relative z-10 max-w-6xl mx-auto w-full px-6 md:px-10">

                <div class="max-w-2xl text-white">

                    <h1 class="text-4xl md:text-5xl font-bold mb-5 text-white">

                        Livros que encontram novos leitores

                    </h1>

                    <p class="text-lg md:text-xl mb-7 text-white">

                        Na Estante Solidária, livros que já foram lidos podem ganhar
                        novos leitores e novas histórias. Encontre sua próxima leitura
                        e compartilhe esse propósito com a gente.

                    </p>

                    <a
                        href="livros.php"
                        class="inline-block text-white border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition"
                    >

                        Explorar livros

                    </a>

                </div>

            </div>

        </div>


        <div class="carousel-slide relative min-h-[420px] flex items-center hidden">

            <img
                src="images/livro-aberto.jpg"
                alt="Compartilhe livros"
                class="absolute inset-0 w-full h-full object-cover"
            >

            <div class="absolute inset-0 bg-black/45"></div>

            <div class="relative z-10 max-w-6xl mx-auto w-full px-6 md:px-10">

                <div class="max-w-2xl text-white">

                    <h2 class="text-4xl md:text-5xl font-bold mb-5 text-white">

                        Um livro parado pode fazer a diferença

                    </h2>

                    <p class="text-lg md:text-xl mb-7 text-white">

                        Doe aquele livro que você já não usa e ajude outra pessoa
                        a descobrir uma nova história.

                    </p>

                    <a
                        href="contato.php"
                        class="inline-block text-white border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition"
                    >

                        Quero doar

                    </a>

                </div>

            </div>

        </div>


        <div class="carousel-slide relative min-h-[420px] flex items-center hidden">

            <img
                src="images/livros02.jpg"
                alt="Novas histórias"
                class="absolute inset-0 w-full h-full object-cover"
            >

            <div class="absolute inset-0 bg-black/45"></div>

            <div class="relative z-10 max-w-6xl mx-auto w-full px-6 md:px-10">

                <div class="max-w-2xl text-white">

                    <h2 class="text-4xl md:text-5xl font-bold mb-5 text-white">

                        Toda história merece continuar

                    </h2>

                    <p class="text-lg md:text-xl mb-7 text-white">

                        Explore nossa estante, encontre seu próximo livro e dê a ele
                        a chance de fazer parte de uma nova história.

                    </p>

                    <a
                        href="livros.php"
                        class="inline-block text-white border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition"
                    >

                        Ver livros

                    </a>

                </div>

            </div>

        </div>

    </div>


    <button
        id="prev"
        class="absolute left-4 top-1/2 -translate-y-1/2 z-20
        bg-white/30 hover:bg-white/50 text-white
        w-10 h-10 rounded-full transition"
    >

        ❮

    </button>


    <button
        id="next"
        class="absolute right-4 top-1/2 -translate-y-1/2 z-20
        bg-white/30 hover:bg-white/50 text-white
        w-10 h-10 rounded-full transition"
    >

        ❯

    </button>


    <div
        id="indicators"
        class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex gap-2"
    >

        <button class="indicator w-3 h-3 rounded-full bg-white"></button>

        <button class="indicator w-3 h-3 rounded-full bg-white/50"></button>

        <button class="indicator w-3 h-3 rounded-full bg-white/50"></button>

    </div>

</section>


<script>

    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.indicator');

    let currentSlide = 0;

    function showSlide(index) {

        slides.forEach((slide, i) => {

            slide.classList.toggle('hidden', i !== index);

        });

        indicators.forEach((indicator, i) => {

            indicator.classList.toggle('bg-white', i === index);
            indicator.classList.toggle('bg-white/50', i !== index);

        });

        currentSlide = index;

    }

    document.getElementById('next').addEventListener('click', () => {

        showSlide((currentSlide + 1) % slides.length);

    });

    document.getElementById('prev').addEventListener('click', () => {

        showSlide((currentSlide - 1 + slides.length) % slides.length);

    });

    indicators.forEach((indicator, index) => {

        indicator.addEventListener('click', () => {

            showSlide(index);

        });

    });

    setInterval(() => {

        showSlide((currentSlide + 1) % slides.length);

    }, 7000);

</script>


<section
    id="company-services"
    class="padding-large"
    style="
        padding-top: 55px;
        padding-bottom: 25px;
    "
>

    <div class="container">

        <div class="row">


            <div class="col-lg-3 col-md-6 pb-3 pb-lg-0">

                <div class="icon-box d-flex">

                    <div
                        class="icon-box-icon pe-3 pb-3"
                        style="color: #501919;"
                    >

                        <svg
                            width="32"
                            height="32"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >

                            <circle cx="9" cy="20" r="1"></circle>

                            <circle cx="18" cy="20" r="1"></circle>

                            <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 8H6"></path>

                        </svg>

                    </div>

                    <div class="icon-box-content">

                        <h4 class="card-title mb-1 text-capitalize text-dark">

                            Tudo em um só lugar

                        </h4>

                        <p>

                            Consulte livros disponíveis e faça suas solicitações diretamente pela plataforma.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 pb-3 pb-lg-0">

                <div class="icon-box d-flex">

                    <div
                        class="icon-box-icon pe-3 pb-3"
                        style="color: #501919;"
                    >

                        <svg
                            width="32"
                            height="32"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >

                            <circle cx="12" cy="8" r="4"></circle>

                            <path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"></path>

                        </svg>

                    </div>

                    <div class="icon-box-content">

                        <h4 class="card-title mb-1 text-capitalize text-dark">

                            Conecte-se através dos livros

                        </h4>

                        <p>

                            Encontre pessoas que estão doando livros que podem ser do seu interesse.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 pb-3 pb-lg-0">

                <div class="icon-box d-flex">

                    <div
                        class="icon-box-icon pe-3 pb-3"
                        style="color: #501919;"
                    >

                        <svg
                            width="32"
                            height="32"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >

                            <path d="M20 12v8H4v-8"></path>

                            <path d="M2 7h20v5H2z"></path>

                            <path d="M12 7v13"></path>

                            <path d="M12 7H7.5A2.5 2.5 0 1 1 10 4.5C10 6 12 7 12 7z"></path>

                            <path d="M12 7h4.5A2.5 2.5 0 1 0 14 4.5C14 6 12 7 12 7z"></path>

                        </svg>

                    </div>

                    <div class="icon-box-content">

                        <h4 class="card-title mb-1 text-capitalize text-dark">

                            Diversas categorias

                        </h4>

                        <p>

                            Explore livros de diferentes categorias e encontre opções que combinam com você.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6 pb-3 pb-lg-0">

                <div class="icon-box d-flex">

                    <div
                        class="icon-box-icon pe-3 pb-3"
                        style="color: #501919;"
                    >

                        <svg
                            width="32"
                            height="32"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >

                            <path d="M20.8 8.7c0 5.5-8.8 10.3-8.8 10.3S3.2 14.2 3.2 8.7A4.7 4.7 0 0 1 12 6.1a4.7 4.7 0 0 1 8.8 2.6z"></path>

                        </svg>

                    </div>

                    <div class="icon-box-content">

                        <h4 class="card-title mb-1 text-capitalize text-dark">

                            Descubra novas leituras

                        </h4>

                        <p>

                            Conheça livros diferentes e encontre sua próxima leitura.

                        </p>

                    </div>

                </div>

            </div>


        </div>

    </div>

</section>


<section
    id="best-selling-items"
    class="position-relative padding-large"
    style="
        padding-top: 15px;
        padding-bottom: 70px;
    "
>

    <div class="container">

        <div class="section-title d-md-flex justify-content-between align-items-center mb-4">

            <h3 class="d-flex align-items-center" style="color: #841717;">

                Livros disponíveis

            </h3>

            <a href="livros.php" class="btn">

                Ver todos

            </a>

        </div>


        <div class="row g-4">

            <?php while ($livro = mysqli_fetch_assoc($resultadoLivros)) { ?>

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

                        <h6 class="mt-4 mb-0 fw-bold">

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

                            <?php echo htmlspecialchars(limitarTexto($livro['autor'], 20)); ?>

                        </p>


                        <span class="text-primary fw-bold mb-2 fs-6">

                            <?php echo htmlspecialchars($livro['categoria']); ?>

                        </span>


                        <div class="mt-auto pt-3 text-center">

                            <a
                                href="solicitar.php?id=<?php echo $livro['idLivro']; ?>"
                                class="btn"
                            >

                                Solicitar livro

                            </a>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</section>


<section
    id="customers-reviews"
    class="position-relative"
    style="
        background-image: url(images/banner01.jpeg);
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: -25px;
        margin-bottom: -60px;
    "
>

    <div class="container">

        <div class="row justify-content-center align-items-center">

            <div class="col-lg-8 col-md-10">


                <div class="position-absolute top-50 start-0 translate-middle-y swiper-prev testimonial-button-prev">

                    <svg
                        class="chevron-back-circle d-flex justify-content-center align-items-center p-2"
                        width="80"
                        height="80"
                    >

                        <use xlink:href="#alt-arrow-left-outline"></use>

                    </svg>

                </div>


                <div class="position-absolute top-50 end-0 translate-middle-y swiper-next testimonial-button-next">

                    <svg
                        class="chevron-forward-circle d-flex justify-content-center align-items-center p-2"
                        width="80"
                        height="80"
                    >

                        <use xlink:href="#alt-arrow-right-outline"></use>

                    </svg>

                </div>


                <div class="section-title text-center mb-4">

                    <h3 style="font-size: 40px; color: var(--white-color);">

                        Avaliações dos usuários

                    </h3>

                </div>


                <div class="swiper testimonial-swiper">

                    <div class="swiper-wrapper">


                        <!-- Luiz Henrique -->

                        <div class="swiper-slide">

                            <div class="card position-relative text-left p-5 border rounded-3">

                                <blockquote style="font-size: 22px;">

                                    "Achei bem prática a ideia. Dá pra procurar um livro que você quer, ver as informações e solicitar sem muita complicação. Também é uma boa forma de passar pra frente os livros que a gente já terminou."

                                </blockquote>


                                <div class="rating d-flex align-items-center" style="gap: 2px;">

                                    <?php for ($i = 0; $i < 5; $i++) { ?>

                                        <span style="color: #ffc107; font-size: 30px; line-height: 1;">★</span>

                                    <?php } ?>

                                </div>


                                <h5 class="mt-1 fw-normal">

                                    Luiz Henrique Martins

                                </h5>

                            </div>

                        </div>


                        <!-- Ana Luiza -->

                        <div class="swiper-slide">

                            <div class="card position-relative text-left p-5 border rounded-3">

                                <blockquote style="font-size: 22px;">

                                    "Eu gostei bastante da proposta! É muito legal poder encontrar livros que eu tenho vontade de ler e, ao mesmo tempo, compartilhar os meus. Acho que facilita muito para quem gosta de ler."

                                </blockquote>


                                <div class="rating d-flex align-items-center" style="gap: 2px;">

                                    <?php for ($i = 0; $i < 5; $i++) { ?>

                                        <span style="color: #ffc107; font-size: 30px; line-height: 1;">★</span>

                                    <?php } ?>

                                </div>


                                <h5 class="mt-1 fw-normal">

                                    Ana Luiza Camargo

                                </h5>

                            </div>

                        </div>


                        <!-- Gabriel Siqueira -->

                        <div class="swiper-slide">

                            <div class="card position-relative text-left p-5 border rounded-3">

                                <blockquote style="font-size: 22px;">

                                    "Curti bastante a plataforma. É simples de usar e facilita muito na hora de encontrar algum livro específico. Também achei legal poder doar livros que estão parados em casa."

                                </blockquote>


                                <div class="rating d-flex align-items-center" style="gap: 2px;">

                                    <?php for ($i = 0; $i < 4; $i++) { ?>

                                        <span style="color: #ffc107; font-size: 30px; line-height: 1;">★</span>

                                    <?php } ?>

                                </div>


                                <h5 class="mt-1 fw-normal">

                                    Gabriel Siqueira

                                </h5>

                            </div>

                        </div>


                        <!-- Kauan Carneiro -->

                        <div class="swiper-slide">

                            <div class="card position-relative text-left p-5 border rounded-3">

                                <blockquote style="font-size: 22px;">

                                    "Achei uma ideia muito boa. Em vez de deixar os livros parados, dá pra colocar na plataforma e outra pessoa pode aproveitar. E pra procurar algum livro também é bem mais fácil."

                                </blockquote>


                                <div class="rating d-flex align-items-center" style="gap: 2px;">

                                    <?php for ($i = 0; $i < 5; $i++) { ?>

                                        <span style="color: #ffc107; font-size: 30px; line-height: 1;">★</span>

                                    <?php } ?>

                                </div>


                                <h5 class="mt-1 fw-normal">

                                    Kauan Carneiro

                                </h5>

                            </div>

                        </div>


                        <!-- Daniel Viana -->

                        <div class="swiper-slide">

                            <div class="card position-relative text-left p-5 border rounded-3">

                                <blockquote style="font-size: 22px;">

                                    "Gostei da ideia porque deixa tudo mais organizado. Você consegue procurar o livro, ver se está disponível e fazer a solicitação. É bem melhor do que ficar perguntando de um lado pro outro."

                                </blockquote>


                                <div class="rating d-flex align-items-center" style="gap: 2px;">

                                    <?php for ($i = 0; $i < 5; $i++) { ?>

                                        <span style="color: #ffc107; font-size: 30px; line-height: 1;">★</span>

                                    <?php } ?>

                                </div>


                                <h5 class="mt-1 fw-normal">

                                    Daniel Viana

                                </h5>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<?php include "footer.php" ?>