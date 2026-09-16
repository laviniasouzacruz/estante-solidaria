<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {

    $idUsuario = $_SESSION['idUsuario'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $emailUsuario = $_SESSION['emailUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];

    $nomeCompleto = explode(' ', $nomeUsuario);
    $primeiroNome = $nomeCompleto[0];
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

<title>Estante Solidária | IFPR-TB</title>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<meta
    name="format-detection"
    content="telephone=no"
>

<meta
    name="apple-mobile-web-app-capable"
    content="yes"
>

<meta
    name="author"
    content=""
>

<meta
    name="keywords"
    content=""
>

<meta
    name="description"
    content=""
>


<!-- Bootstrap -->

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
>


<!-- CSS principal -->

<link
    rel="stylesheet"
    type="text/css"
    href="css/style.css"
>


<!-- Swiper -->

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css"
>


<!-- Google Fonts -->

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
    rel="stylesheet"
>


<!-- Tailwind -->

<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


<style>

    /* Logo principal */

    .navbar-brand .logo {
        width: 280px;
        height: auto;
    }


    /* Logo do menu mobile */

    .offcanvas-header .logo {
        width: 280px;
        height: auto;
    }


    /* Letras do menu */

    #navbar .nav-link {
        font-size: 0.98rem;
    }


    /* Ícones de busca e perfil */

    .user-items .search,
    .user-items .user {
        width: 32px;
        height: 32px;
    }


    /* Espaço vertical do header */

    #header-nav {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }

</style>

</head>

<body>

<!-- Ícones SVG -->

<svg
    xmlns="http://www.w3.org/2000/svg"
    style="display: none;"
>

    <symbol
        id="search"
        viewBox="0 0 24 24"
    >

        <path
            fill="currentColor"
            fill-rule="evenodd"
            d="M11.5 2.75a8.75 8.75 0 1 0 0 17.5a8.75 8.75 0 0 0 0-17.5M1.25 11.5c0-5.66 4.59-10.25 10.25-10.25S21.75 5.84 21.75 11.5c0 2.56-.939 4.902-2.491 6.698l3.271 3.272a.75.75 0 1 1-1.06 1.06l-3.272-3.271A10.21 10.21 0 0 1 11.5 21.75c-5.66 0-10.25-4.59-10.25-10.25"
            clip-rule="evenodd"
        />

    </symbol>


    <symbol
        id="user"
        viewBox="0 0 24 24"
    >

        <path
            fill="currentColor"
            fill-rule="evenodd"
            d="M12 1.25a4.75 4.75 0 1 0 0 9.5a4.75 4.75 0 0 0 0-9.5M8.75 6a3.25 3.25 0 1 1 6.5 0a3.25 3.25 0 0 1-6.5 0M12 12.25c-2.313 0-4.445.526-6.024 1.414C4.42 14.54 3.25 15.866 3.25 17.5v.102c-.001 1.162-.002 2.62 1.277 3.662c.629.512 1.51.877 2.7 1.117c1.192.242 2.747.369 4.773.369s3.58-.127 4.774-.369c1.19-.24 2.07-.605 2.7-1.117c1.279-1.042 1.277-2.5 1.276-3.662V17.5c0-1.634-1.17-2.96-2.725-3.836c-1.58-.888-3.711-1.414-6.025-1.414M4.75 17.5c0-.851.622-1.775 1.961-2.528c1.316-.74 3.184-1.222 5.29-1.222c2.104 0 3.972.482 5.288 1.222c1.34.753 1.961 1.677 1.961 2.528c0 1.308-.04 2.044-.724 2.6c-.37.302-.99.597-2.05.811c-1.057.214-2.502.339-4.476.339c-1.974 0-3.42-.125-4.476-.339c-1.06-.214-1.68-.509-2.05-.81c-.684-.557-.724-1.293-.724-2.601"
            clip-rule="evenodd"
        />

    </symbol>


    <symbol
        id="navbar-icon"
        viewBox="0 0 16 16"
    >

        <path
            d="M14 10.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-7a.5.5 0 0 0 0 1h7a.5.5 0 0 0 .5-.5zm0-3a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0 0 1h11a.5.5 0 0 0 .5-.5z"
        />

    </symbol>

</svg>


<!-- PRELOADER -->

<div
    id="preloader"
    class="preloader-container"
>

    <div class="book">

        <div class="inner">

            <div class="left"></div>

            <div class="middle"></div>

            <div class="right"></div>

        </div>


        <ul>

            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>

        </ul>

    </div>

</div>


<!-- BUSCA -->

<div class="search-popup">

    <div class="search-popup-container">

        <form
            role="search"
            method="get"
            class="search-form"
            action=""
        >

            <input
                type="search"
                id="search-form"
                class="search-field"
                placeholder="Type and press enter"
                value=""
                name="s"
            >

            <button
                type="submit"
                class="search-submit"
            >

                <svg class="search">

                    <use xlink:href="#search"></use>

                </svg>

            </button>

        </form>


        <h5 class="cat-list-title">
            Browse Categories
        </h5>


        <ul class="cat-list">

            <li class="cat-list-item">

                <a
                    href="categoria.php?categoria=romance"
                    title="Romance"
                >
                    Romance
                </a>

            </li>


            <li class="cat-list-item">

                <a
                    href="categoria.php?categoria=fantasia"
                    title="Fantasia"
                >
                    Fantasia
                </a>

            </li>


            <li class="cat-list-item">

                <a
                    href="categoria.php?categoria=suspense-terror"
                    title="Suspense e Terror"
                >
                    Suspense e Terror
                </a>

            </li>


            <li class="cat-list-item">

                <a
                    href="categoria.php?categoria=ficcao"
                    title="Ficção"
                >
                    Ficção
                </a>

            </li>


            <li class="cat-list-item">

                <a
                    href="categoria.php?categoria=poesia"
                    title="Poesia"
                >
                    Poesia
                </a>

            </li>


            <li class="cat-list-item">

                <a
                    href="categoria.php?categoria=automacao-mecanica"
                    title="Automação e Mecânica"
                >
                    Automação e Mecânica
                </a>

            </li>


            <li class="cat-list-item">

                <a
                    href="categoria.php?categoria=informatica-tecnologia"
                    title="Informática e Tecnologia"
                >
                    Informática e Tecnologia
                </a>

            </li>


            <li class="cat-list-item">

                <a
                    href="categoria.php?categoria=educacionais-didaticos"
                    title="Livros Educacionais e Didáticos"
                >
                    Livros Educacionais e Didáticos
                </a>

            </li>

        </ul>

    </div>

</div>


<!-- HEADER -->

<header
    id="header"
    class="site-header"
>

    <nav
        id="header-nav"
        class="navbar navbar-expand-lg"
    >

        <div class="container">


            <!-- LOGO -->

            <a
                class="navbar-brand"
                href="index.php"
            >

                <img
                    src="images/logo-principal.png"
                    class="logo"
                    width="280"
                    alt="Estante Solidária"
                >

            </a>


            <!-- BOTÃO MENU MOBILE -->

            <button
                class="navbar-toggler d-flex d-lg-none order-3 p-2"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#bdNavbar"
                aria-controls="bdNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >

                <svg class="navbar-icon">

                    <use xlink:href="#navbar-icon"></use>

                </svg>

            </button>


            <!-- MENU -->

            <div
                class="offcanvas offcanvas-end"
                tabindex="-1"
                id="bdNavbar"
                aria-labelledby="bdNavbarOffcanvasLabel"
            >


                <!-- CABEÇALHO MENU MOBILE -->

                <div class="offcanvas-header px-4 pb-0">

                    <a
                        class="navbar-brand"
                        href="index.php"
                    >

                        <img
                            src="images/logo-principal.png"
                            class="logo"
                            alt="Estante Solidária"
                        >

                    </a>


                    <button
                        type="button"
                        class="btn-close btn-close-black"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"
                    ></button>

                </div>


                <!-- CORPO DO MENU -->

                <div class="offcanvas-body">

                    <ul
                        id="navbar"
                        class="navbar-nav text-uppercase justify-content-start justify-content-lg-center align-items-start align-items-lg-center flex-grow-1"
                    >

                        <li class="nav-item">

                            <a
                                class="nav-link me-4 active"
                                href="index.php"
                            >
                                Início
                            </a>

                        </li>


                        <li class="nav-item">

                            <a
                                class="nav-link me-4"
                                href="sobre.php"
                            >
                                Sobre
                            </a>

                        </li>


                        <li class="nav-item">

                            <a
                                class="nav-link me-4"
                                href="livros.php"
                            >
                                Livros
                            </a>

                        </li>


                        <!-- CATEGORIAS -->

                        <li class="nav-item dropdown">

                            <a
                                class="nav-link me-4 dropdown-toggle"
                                href="#"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Categorias
                            </a>


                            <ul class="dropdown-menu border">

                                <li>

                                    <a
                                        href="categoria.php?categoria=ficcao"
                                        class="dropdown-item"
                                    >
                                        Ficção
                                    </a>

                                </li>


                                <li>

                                    <a
                                        href="categoria.php?categoria=poesia"
                                        class="dropdown-item"
                                    >
                                        Poesia
                                    </a>

                                </li>


                                <li>

                                    <a
                                        href="categoria.php?categoria=romance"
                                        class="dropdown-item"
                                    >
                                        Romance
                                    </a>

                                </li>


                                <li>

                                    <a
                                        href="categoria.php?categoria=fantasia"
                                        class="dropdown-item"
                                    >
                                        Fantasia
                                    </a>

                                </li>


                                <li>

                                    <a
                                        href="categoria.php?categoria=suspense-terror"
                                        class="dropdown-item"
                                    >
                                        Suspense e Terror
                                    </a>

                                </li>


                                <li>

                                    <a
                                        href="categoria.php?categoria=automacao-mecanica"
                                        class="dropdown-item"
                                    >
                                        Automação e Mecânica
                                    </a>

                                </li>


                                <li>

                                    <a
                                        href="categoria.php?categoria=informatica-tecnologia"
                                        class="dropdown-item"
                                    >
                                        Informática e Tecnologia
                                    </a>

                                </li>


                                <li>

                                    <a
                                        href="categoria.php?categoria=educacionais-didaticos"
                                        class="dropdown-item"
                                    >
                                        Livros Educacionais e Didáticos
                                    </a>

                                </li>

                            </ul>

                        </li>


                        <li class="nav-item">

                            <a
                                class="nav-link me-4"
                                href="contato.php"
                            >
                                Contato
                            </a>

                        </li>

                    </ul>

                </div>

            </div>


            <!-- ÍCONES -->

            <div class="user-items d-flex">

                <ul class="d-flex justify-content-end list-unstyled mb-0">


                    <!-- BUSCA -->

                    <li class="search-item pe-3">

                        <a
                            href="#"
                            class="search-button"
                        >

                            <svg class="search">

                                <use xlink:href="#search"></use>

                            </svg>

                        </a>

                    </li>


                    <!-- PERFIL -->

                    <li class="pe-3">

                        <?php if (
                            isset($_SESSION['logado']) &&
                            $_SESSION['logado'] === true
                        ): ?>

                            <div class="dropdown">

                                <a
                                    href="#"
                                    class="dropdown-toggle"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                >

                                    <svg class="user">

                                        <use xlink:href="#user"></use>

                                    </svg>

                                </a>


                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>

                                        <h6 class="dropdown-header">

                                            Olá,
                                            <?php
                                            echo htmlspecialchars(
                                                $primeiroNome
                                            );
                                            ?>!

                                        </h6>

                                    </li>


                                    <?php if ($tipoUsuario === 'Aluno'): ?>

                                        <li>

                                            <a
                                                class="dropdown-item"
                                                href="minhasSolicitacoes.php"
                                            >
                                                Minhas solicitações
                                            </a>

                                        </li>

                                    <?php endif; ?>


                                    <?php if (
                                        $tipoUsuario === 'Administrador'
                                    ): ?>

                                        <li>

                                            <hr class="dropdown-divider">

                                        </li>


                                        <li>

                                            <a
                                                class="dropdown-item"
                                                href="formLivros.php"
                                            >
                                                Cadastrar livro
                                            </a>

                                        </li>


                                        <li>

                                            <a
                                                class="dropdown-item"
                                                href="visualizarSolicitacoes.php"
                                            >
                                                Visualizar solicitações
                                            </a>

                                        </li>


                                        <li>

                                            <a
                                                class="dropdown-item"
                                                href="acervoLivros.php"
                                            >
                                                Acervo de livros
                                            </a>

                                        </li>

                                    <?php endif; ?>


                                    <li>

                                        <hr class="dropdown-divider">

                                    </li>


                                    <li>

                                        <a
                                            class="dropdown-item"
                                            href="logout.php"
                                        >
                                            Sair
                                        </a>

                                    </li>

                                </ul>

                            </div>

                        <?php else: ?>

                            <a href="formLogin.php">

                                <svg class="user">

                                    <use xlink:href="#user"></use>

                                </svg>

                            </a>

                        <?php endif; ?>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

</header>


<!-- Bootstrap JavaScript -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<!-- Esconde o preloader quando a página terminar de carregar -->

<script>

    window.addEventListener('load', function () {

        const preloader = document.getElementById('preloader');

        if (preloader) {

            preloader.style.display = 'none';

        }

    });

</script>
