<?php 
 
if (session_status() === PHP_SESSION_NONE) { 
 
    session_start(); 
 
} 
 
include_once("conexaoBD.php"); 
 
 
// Verifica se o usuário está logado e é administrador 
 
if ( 
    !isset($_SESSION['logado']) || 
    $_SESSION['logado'] !== true || 
    $_SESSION['tipoUsuario'] !== 'Administrador' 
) { 
 
    header("Location: index.php"); 
 
    exit(); 
 
} 
 
 
// Categorias na mesma ordem usada no formulário e no header 
 
$categorias = array( 
 
    'Ficção' => 'Ficção', 
 
    'Poesia' => 'Poesia', 
 
    'Romance' => 'Romance', 
 
    'Fantasia' => 'Fantasia', 
 
    'Suspense e Terror' => 'Suspense e Terror', 
 
    'Automação e Mecânica' => 'Automação e Mecânica', 
 
    'Informática e Tecnologia' => 'Informática e Tecnologia', 
 
    'Livros Educacionais e Didáticos' => 'Livros Educacionais e Didáticos' 
 
); 
 
 
// ====================================================== 
// SALVAR ALTERAÇÕES DO LIVRO 
// ====================================================== 
 
if ( 
    $_SERVER['REQUEST_METHOD'] === 'POST' && 
    isset($_POST['acao']) && 
    $_POST['acao'] === 'editarLivro' 
) { 
 
    $idLivro = isset($_POST['idLivro']) && is_numeric($_POST['idLivro']) 
        ? (int) $_POST['idLivro'] 
        : 0; 
 
    $titulo = isset($_POST['titulo']) 
        ? trim($_POST['titulo']) 
        : ''; 
 
    $autor = isset($_POST['autor']) 
        ? trim($_POST['autor']) 
        : ''; 
 
    $editora = isset($_POST['editora']) 
        ? trim($_POST['editora']) 
        : ''; 
 
    $anoPublicacao = isset($_POST['anoPublicacao']) 
        ? trim($_POST['anoPublicacao']) 
        : ''; 
 
    $categoria = isset($_POST['categoria']) 
        ? trim($_POST['categoria']) 
        : ''; 
 
    $sinopse = isset($_POST['sinopse']) 
        ? trim($_POST['sinopse']) 
        : ''; 
 
 
    $pesquisaRetorno = isset($_POST['pesquisaRetorno']) 
        ? trim($_POST['pesquisaRetorno']) 
        : ''; 
 
    $categoriaRetorno = isset($_POST['categoriaRetorno']) 
        ? trim($_POST['categoriaRetorno']) 
        : ''; 
 
    $paginaRetorno = isset($_POST['paginaRetorno']) && is_numeric($_POST['paginaRetorno']) 
        ? (int) $_POST['paginaRetorno'] 
        : 1; 
 
    $statusRetorno = isset($_POST['statusRetorno']) && is_array($_POST['statusRetorno']) 
        ? $_POST['statusRetorno'] 
        : array( 
            'Disponivel', 
            'Reservado', 
            'Indisponivel' 
        ); 
 
 
    if ($idLivro <= 0) { 
 
        die("Livro inválido."); 
 
    } 
 
 
    if ( 
        $titulo === '' || 
        $autor === '' || 
        $categoria === '' || 
        $sinopse === '' 
    ) { 
 
        die("Preencha todos os campos obrigatórios."); 
 
    } 
 
 
    if (!array_key_exists($categoria, $categorias)) { 
 
        die("Categoria inválida."); 
 
    } 
 
 
    // Valida o ano 
 
    if ($anoPublicacao !== '') { 
 
        if ( 
            !is_numeric($anoPublicacao) || 
            (int) $anoPublicacao < 1000 || 
            (int) $anoPublicacao > 2026 
        ) { 
 
            die("Ano de publicação inválido."); 
 
        } 
 
        $anoPublicacao = (int) $anoPublicacao; 
 
    } else { 
 
        $anoPublicacao = null; 
 
    } 
 
 
    // ====================================================== 
    // BUSCA A FOTO ATUAL 
    // ====================================================== 
 
    $sqlFotoAtual = "SELECT fotoLivro 
                     FROM livros 
                     WHERE idLivro = $idLivro"; 
 
    $resultadoFotoAtual = mysqli_query( 
        $conn, 
        $sqlFotoAtual 
    ); 
 
 
    if ( 
        !$resultadoFotoAtual || 
        mysqli_num_rows($resultadoFotoAtual) === 0 
    ) { 
 
        die("Livro não encontrado."); 
 
    } 
 
 
    $dadosFotoAtual = mysqli_fetch_assoc( 
        $resultadoFotoAtual 
    ); 
 
    $fotoAtual = $dadosFotoAtual['fotoLivro']; 
 
    $fotoLivro = $fotoAtual; 
 
    $novaFotoSalva = false; 
 
 
    // ====================================================== 
    // NOVA FOTO 
    // ====================================================== 
 
    if ( 
        isset($_FILES['fotoLivro']) && 
        $_FILES['fotoLivro']['error'] !== UPLOAD_ERR_NO_FILE 
    ) { 
 
        if ($_FILES['fotoLivro']['error'] !== UPLOAD_ERR_OK) { 
 
            die("Erro ao enviar a nova capa."); 
 
        } 
 
 
        $extensao = strtolower( 
            pathinfo( 
                $_FILES['fotoLivro']['name'], 
                PATHINFO_EXTENSION 
            ) 
        ); 
 
 
        $extensoesPermitidas = array( 
            'jpg', 
            'jpeg', 
            'png', 
            'webp', 
            'gif' 
        ); 
 
 
        if (!in_array($extensao, $extensoesPermitidas)) { 
 
            die("Formato de imagem não permitido."); 
 
        } 
 
 
        $diretorio = "images/livros/"; 
 
 
        if (!is_dir($diretorio)) { 
 
            mkdir($diretorio, 0777, true); 
 
        } 
 
 
        $nomeArquivo = uniqid('livro_', true) . '.' . $extensao; 
 
        $caminhoNovo = $diretorio . $nomeArquivo; 
 
 
        if ( 
            !move_uploaded_file( 
                $_FILES['fotoLivro']['tmp_name'], 
                $caminhoNovo 
            ) 
        ) { 
 
            die("Não foi possível salvar a nova capa."); 
 
        } 
 
 
        $fotoLivro = $caminhoNovo; 
 
        $novaFotoSalva = true; 
 
    } 
 
 
    // ====================================================== 
    // EXEMPLARES EXISTENTES 
    // ====================================================== 
 
    $exemplarIds = isset($_POST['exemplarId']) && 
        is_array($_POST['exemplarId']) 
            ? $_POST['exemplarId'] 
            : array(); 
 
 
    $exemplarEstados = isset($_POST['exemplarEstado']) && 
        is_array($_POST['exemplarEstado']) 
            ? $_POST['exemplarEstado'] 
            : array(); 
 
 
    $exemplarStatus = isset($_POST['exemplarStatus']) && 
        is_array($_POST['exemplarStatus']) 
            ? $_POST['exemplarStatus'] 
            : array(); 
 
 
    // ====================================================== 
    // NOVOS EXEMPLARES 
    // ====================================================== 
 
    $novosEstados = isset($_POST['novoEstado']) && 
        is_array($_POST['novoEstado']) 
            ? $_POST['novoEstado'] 
            : array(); 
 
 
    $novosStatus = isset($_POST['novoStatus']) && 
        is_array($_POST['novoStatus']) 
            ? $_POST['novoStatus'] 
            : array(); 
 
 
    $estadosValidos = array( 
        'Novo', 
        'Bom', 
        'Regular' 
    ); 
 
 
    $statusValidos = array( 
        'Disponivel', 
        'Reservado', 
        'Indisponivel' 
    ); 
 
 
    // ====================================================== 
    // TRANSAÇÃO 
    // ====================================================== 
 
    mysqli_begin_transaction($conn); 
 
 
    try { 
 
        // ================================================== 
        // ATUALIZA O LIVRO 
        // ================================================== 
 
        $tituloBanco = mysqli_real_escape_string( 
            $conn, 
            $titulo 
        ); 
 
        $autorBanco = mysqli_real_escape_string( 
            $conn, 
            $autor 
        ); 
 
        $editoraBanco = mysqli_real_escape_string( 
            $conn, 
            $editora 
        ); 
 
        $categoriaBanco = mysqli_real_escape_string( 
            $conn, 
            $categoria 
        ); 
 
        $sinopseBanco = mysqli_real_escape_string( 
            $conn, 
            $sinopse 
        ); 
 
        $fotoBanco = mysqli_real_escape_string( 
            $conn, 
            $fotoLivro 
        ); 
 
 
        if ($anoPublicacao === null) { 
 
            $anoBanco = "NULL"; 
 
        } else { 
 
            $anoBanco = (int) $anoPublicacao; 
 
        } 
 
 
        $sqlAtualizar = "UPDATE livros 
 
                         SET 
                            titulo = '$tituloBanco', 
                            autor = '$autorBanco', 
                            editora = '$editoraBanco', 
                            anoPublicacao = $anoBanco, 
                            fotoLivro = '$fotoBanco', 
                            categoria = '$categoriaBanco', 
                            sinopse = '$sinopseBanco' 
 
                         WHERE idLivro = $idLivro"; 
 
 
        $resultadoAtualizar = mysqli_query( 
            $conn, 
            $sqlAtualizar 
        ); 
 
 
        if (!$resultadoAtualizar) { 
 
            throw new Exception( 
                "Erro ao atualizar livro: " . 
                mysqli_error($conn) 
            ); 
 
        } 
 
 
        // ================================================== 
        // ATUALIZA EXEMPLARES EXISTENTES 
        // ================================================== 
 
        foreach ($exemplarIds as $indice => $idExemplar) { 
 
            if (!is_numeric($idExemplar)) { 
 
                continue; 
 
            } 
 
 
            $idExemplar = (int) $idExemplar; 
 
 
            if ($idExemplar <= 0) { 
 
                continue; 
 
            } 
 
 
            $estadoExemplar = isset( 
                $exemplarEstados[$indice] 
            ) 
                ? trim($exemplarEstados[$indice]) 
                : 'Bom'; 
 
 
            $statusExemplar = isset( 
                $exemplarStatus[$indice] 
            ) 
                ? trim($exemplarStatus[$indice]) 
                : 'Disponivel'; 
 
 
            if (!in_array( 
                $estadoExemplar, 
                $estadosValidos 
            )) { 
 
                $estadoExemplar = 'Bom'; 
 
            } 
 
 
            if (!in_array( 
                $statusExemplar, 
                $statusValidos 
            )) { 
 
                $statusExemplar = 'Disponivel'; 
 
            } 
 
 
            $estadoBanco = mysqli_real_escape_string( 
                $conn, 
                $estadoExemplar 
            ); 
 
            $statusBanco = mysqli_real_escape_string( 
                $conn, 
                $statusExemplar 
            ); 
 
 
            $sqlVerificarExemplar = "SELECT idExemplar 
 
                                     FROM exemplares 
 
                                     WHERE idExemplar = $idExemplar 
 
                                     AND idLivro = $idLivro"; 
 
 
            $resultadoVerificarExemplar = mysqli_query( 
                $conn, 
                $sqlVerificarExemplar 
            ); 
 
 
            if ( 
                !$resultadoVerificarExemplar || 
                mysqli_num_rows($resultadoVerificarExemplar) === 0 
            ) { 
 
                throw new Exception( 
                    "Exemplar inválido." 
                ); 
 
            } 
 
 
            $sqlAtualizarExemplar = "UPDATE exemplares 
 
                                     SET 
                                        estado = '$estadoBanco', 
                                        status = '$statusBanco' 
 
                                     WHERE idExemplar = $idExemplar 
 
                                     AND idLivro = $idLivro"; 
 
 
            $resultadoAtualizarExemplar = mysqli_query( 
                $conn, 
                $sqlAtualizarExemplar 
            ); 
 
 
            if (!$resultadoAtualizarExemplar) { 
 
                throw new Exception( 
                    "Erro ao atualizar exemplar: " . 
                    mysqli_error($conn) 
                ); 
 
            } 
 
        } 
 
 
        // ================================================== 
        // ADICIONA NOVOS EXEMPLARES 
        // ================================================== 
 
        $quantidadeNovos = max( 
            count($novosEstados), 
            count($novosStatus) 
        ); 
 
 
        for ($i = 0; $i < $quantidadeNovos; $i++) { 
 
            $estadoNovo = isset($novosEstados[$i]) 
                ? trim($novosEstados[$i]) 
                : 'Bom'; 
 
 
            $statusNovo = isset($novosStatus[$i]) 
                ? trim($novosStatus[$i]) 
                : 'Disponivel'; 
 
 
            if (!in_array( 
                $estadoNovo, 
                $estadosValidos 
            )) { 
 
                $estadoNovo = 'Bom'; 
 
            } 
 
 
            if (!in_array( 
                $statusNovo, 
                $statusValidos 
            )) { 
 
                $statusNovo = 'Disponivel'; 
 
            } 
 
 
            $estadoNovoBanco = mysqli_real_escape_string( 
                $conn, 
                $estadoNovo 
            ); 
 
            $statusNovoBanco = mysqli_real_escape_string( 
                $conn, 
                $statusNovo 
            ); 
 
 
            $sqlNovoExemplar = "INSERT INTO exemplares ( 
                                    idLivro, 
                                    estado, 
                                    status 
                                ) 
 
                                VALUES ( 
                                    $idLivro, 
                                    '$estadoNovoBanco', 
                                    '$statusNovoBanco' 
                                )"; 
 
 
            $resultadoNovoExemplar = mysqli_query( 
                $conn, 
                $sqlNovoExemplar 
            ); 
 
 
            if (!$resultadoNovoExemplar) { 
 
                throw new Exception( 
                    "Erro ao adicionar exemplar: " . 
                    mysqli_error($conn) 
                ); 
 
            } 
 
        } 
 
 
        mysqli_commit($conn); 
 
 
        // Exclui a foto antiga somente depois do commit 
 
        if ( 
            $novaFotoSalva && 
            !empty($fotoAtual) && 
            file_exists($fotoAtual) && 
            $fotoAtual !== $fotoLivro 
        ) { 
 
            unlink($fotoAtual); 
 
        } 
 
 
    } catch (Exception $erro) { 
 
        mysqli_rollback($conn); 
 
 
        if ( 
            $novaFotoSalva && 
            !empty($fotoLivro) && 
            $fotoLivro !== $fotoAtual && 
            file_exists($fotoLivro) 
        ) { 
 
            unlink($fotoLivro); 
 
        } 
 
 
        die($erro->getMessage()); 
 
    } 
 
 
    // ====================================================== 
    // RETORNO PARA O ACERVO 
    // ====================================================== 
 
    $parametrosRetorno = array(); 
 
 
    if ($pesquisaRetorno !== '') { 
 
        $parametrosRetorno[] = 
            'pesquisa=' . urlencode($pesquisaRetorno); 
 
    } 
 
 
    if ($categoriaRetorno !== '') { 
 
        $parametrosRetorno[] = 
            'categoria=' . urlencode($categoriaRetorno); 
 
    } 
 
 
    foreach ($statusRetorno as $statusFiltro) { 
 
        $parametrosRetorno[] = 
            'status[]=' . urlencode($statusFiltro); 
 
    } 
 
 
    $parametrosRetorno[] = 
        'pagina=' . $paginaRetorno; 
 
 
    $parametrosRetorno[] = 
        'editado=1'; 
 
 
    header( 
        "Location: acervoLivros.php?" . 
        implode('&', $parametrosRetorno) 
    ); 
 
    exit(); 
 
} 
 
 
// ====================================================== 
// FILTROS 
// ====================================================== 
 
$pesquisa = isset($_GET['pesquisa']) 
    ? trim($_GET['pesquisa']) 
    : ''; 
 
 
$categoriaSelecionada = isset($_GET['categoria']) 
    ? trim($_GET['categoria']) 
    : ''; 
 
 
$statusSelecionados = isset($_GET['status']) && 
    is_array($_GET['status']) 
        ? $_GET['status'] 
        : array( 
            'Disponivel', 
            'Reservado', 
            'Indisponivel' 
        ); 
 
 
// ====================================================== 
// PÁGINA 
// ====================================================== 
 
$paginaAtual = isset($_GET['pagina']) && 
    is_numeric($_GET['pagina']) 
        ? (int) $_GET['pagina'] 
        : 1; 
 
 
if ($paginaAtual < 1) { 
 
    $paginaAtual = 1; 
 
} 
 
 
$livrosPorPagina = 6; 
 
 
// ====================================================== 
// STATUS VÁLIDOS 
// ====================================================== 
 
$statusValidos = array( 
    'Disponivel', 
    'Reservado', 
    'Indisponivel' 
); 
 
 
$statusFiltrados = array(); 
 
 
foreach ($statusSelecionados as $status) { 
 
    if (in_array($status, $statusValidos)) { 
 
        $statusFiltrados[] = $status; 
 
    } 
 
} 
 
 
// ====================================================== 
// CONSULTA BASE 
// ====================================================== 
 
$sqlBase = "FROM livros l 
            WHERE 1 = 1"; 
 
 
// Pesquisa 
 
if ($pesquisa !== '') { 
 
    $pesquisaBanco = mysqli_real_escape_string( 
        $conn, 
        $pesquisa 
    ); 
 
 
    $sqlBase .= " AND ( 
                    l.titulo LIKE '%$pesquisaBanco%' 
                    OR l.autor LIKE '%$pesquisaBanco%' 
                    OR l.categoria LIKE '%$pesquisaBanco%' 
                  )"; 
 
} 
 
 
// Categoria 
 
if ($categoriaSelecionada !== '') { 
 
    $categoriaBanco = mysqli_real_escape_string( 
        $conn, 
        $categoriaSelecionada 
    ); 
 
 
    $sqlBase .= " 
        AND l.categoria = '$categoriaBanco' 
    "; 
 
} 
 
 
// Status baseado nos exemplares 
 
if (count($statusFiltrados) > 0) { 
 
    $statusPermitidos = array(); 
 
 
    foreach ($statusFiltrados as $status) { 
 
        $statusPermitidos[] = "'" . 
            mysqli_real_escape_string( 
                $conn, 
                $status 
            ) . 
            "'"; 
 
    } 
 
 
    $sqlBase .= " 
        AND EXISTS ( 
            SELECT 1 
 
            FROM exemplares eFiltro 
 
            WHERE eFiltro.idLivro = l.idLivro 
 
            AND eFiltro.status IN ( 
                " . 
                implode( 
                    ", ", 
                    $statusPermitidos 
                ) . 
                " 
            ) 
        ) 
    "; 
 
} else { 
 
    $sqlBase .= " AND 1 = 0"; 
 
} 
 
 
// ====================================================== 
// TOTAL 
// ====================================================== 
 
$sqlQuantidade = "SELECT 
                    COUNT(*) AS total 
 
                  $sqlBase"; 
 
 
$resultadoQuantidade = mysqli_query( 
    $conn, 
    $sqlQuantidade 
); 
 
 
if (!$resultadoQuantidade) { 
 
    die( 
        "Erro ao contar livros: " . 
        mysqli_error($conn) 
    ); 
 
} 
 
 
$dadosQuantidade = mysqli_fetch_assoc( 
    $resultadoQuantidade 
); 
 
 
$totalLivros = (int) $dadosQuantidade['total']; 
 
 
$totalPaginas = $totalLivros > 0 
    ? (int) ceil( 
        $totalLivros / $livrosPorPagina 
    ) 
    : 0; 
 
 
if ( 
    $totalPaginas > 0 && 
    $paginaAtual > $totalPaginas 
) { 
 
    $paginaAtual = $totalPaginas; 
 
} 
 
 
$inicio = ($paginaAtual - 1) * 
    $livrosPorPagina; 
 
 
// ====================================================== 
// BUSCA LIVROS 
// ====================================================== 
 
$sqlLivros = "SELECT 
 
                l.idLivro, 
                l.titulo, 
                l.autor, 
                l.editora, 
                l.anoPublicacao, 
                l.fotoLivro, 
                l.categoria, 
                l.sinopse, 
 
                ( 
                    SELECT COUNT(*) 
 
                    FROM exemplares eQuantidade 
 
                    WHERE eQuantidade.idLivro = l.idLivro 
 
                ) AS quantidade, 
 
                ( 
                    SELECT COUNT(*) 
 
                    FROM exemplares eDisponivel 
 
                    WHERE eDisponivel.idLivro = l.idLivro 
 
                    AND eDisponivel.status = 'Disponivel' 
 
                ) AS quantidadeDisponivel, 
 
                CASE 
 
                    WHEN EXISTS ( 
 
                        SELECT 1 
 
                        FROM exemplares eStatus 
 
                        WHERE eStatus.idLivro = l.idLivro 
 
                        AND eStatus.status = 'Disponivel' 
 
                    ) 
 
                    THEN 'Disponivel' 
 
 
                    WHEN EXISTS ( 
 
                        SELECT 1 
 
                        FROM exemplares eStatus 
 
                        WHERE eStatus.idLivro = l.idLivro 
 
                        AND eStatus.status = 'Reservado' 
 
                    ) 
 
                    THEN 'Reservado' 
 
 
                    ELSE 'Indisponivel' 
 
                END AS status 
 
              $sqlBase 
 
              ORDER BY l.titulo ASC 
 
              LIMIT $inicio, $livrosPorPagina"; 
 
 
$resultadoLivros = mysqli_query( 
    $conn, 
    $sqlLivros 
); 
 
 
if (!$resultadoLivros) { 
 
    die( 
        "Erro ao buscar livros: " . 
        mysqli_error($conn) 
    ); 
 
} 
 
 
// ====================================================== 
// LINKS DA PAGINAÇÃO 
// ====================================================== 
 
function montarLinkPagina( 
    $pagina, 
    $pesquisa, 
    $categoriaSelecionada, 
    $statusSelecionados 
) { 
 
    $parametros = array(); 
 
 
    if ($pesquisa !== '') { 
 
        $parametros[] = 
            'pesquisa=' . urlencode($pesquisa); 
 
    } 
 
 
    if ($categoriaSelecionada !== '') { 
 
        $parametros[] = 
            'categoria=' . 
            urlencode($categoriaSelecionada); 
 
    } 
 
 
    foreach ($statusSelecionados as $status) { 
 
        $parametros[] = 
            'status[]=' . urlencode($status); 
 
    } 
 
 
    $parametros[] = 
        'pagina=' . $pagina; 
 
 
    return 'acervoLivros.php?' . 
        implode('&', $parametros); 
 
} 
 
 
// ====================================================== 
// MENSAGEM 
// ====================================================== 
 
$livroEditado = isset($_GET['editado']) && 
    $_GET['editado'] == '1'; 
 
 
// ====================================================== 
// GUARDA OS LIVROS DA PÁGINA 
// ====================================================== 
 
$livrosPagina = array(); 
 
 
while ($livro = mysqli_fetch_assoc($resultadoLivros)) { 
 
    $livro['exemplares'] = array(); 
 
 
    $idLivroAtual = (int) $livro['idLivro']; 
 
 
    $sqlExemplares = "SELECT 
                        idExemplar, 
                        estado, 
                        status 
 
                      FROM exemplares 
 
                      WHERE idLivro = $idLivroAtual 
 
                      ORDER BY idExemplar ASC"; 
 
 
    $resultadoExemplares = mysqli_query( 
        $conn, 
        $sqlExemplares 
    ); 
 
 
    if (!$resultadoExemplares) { 
 
        die( 
            "Erro ao buscar exemplares: " . 
            mysqli_error($conn) 
        ); 
 
    } 
 
 
    while ($exemplar = mysqli_fetch_assoc( 
        $resultadoExemplares 
    )) { 
 
        $livro['exemplares'][] = $exemplar; 
 
    } 
 
 
    $livrosPagina[] = $livro; 
 
} 
 
?> 
 
 
<?php include_once("header.php"); ?> 
 
 
<!-- ACERVO DE LIVROS --> 
 
<section 
    style=" 
        padding-top: 0; 
        padding-bottom: 0; 
        margin-top: 0; 
        margin-bottom: -5rem; 
        position: relative; 
        top: -20px; 
    " 
> 
 
    <div class="container"> 
 
 
        <!-- TÍTULO --> 
 
        <div 
            style=" 
                text-align: center; 
                margin-bottom: 20px; 
                margin-top: 0; 
            " 
        > 
 
            <h1 
                style=" 
                    color: #841717; 
                    font-size: 2.5rem; 
                    font-weight: 600; 
                    margin-top: 0; 
                    margin-bottom: 3px; 
                " 
            > 
 
                Acervo de livros 
 
            </h1> 
 
 
            <p 
                style=" 
                    color: #555; 
                    font-size: 1.2rem; 
                    margin-bottom: 0; 
                " 
            > 
 
                Consulte e edite os livros cadastrados no acervo. 
 
            </p> 
 
        </div> 
 
 
        <?php if ($livroEditado): ?> 
 
            <div 
                style=" 
                    background-color: #e9f7ef; 
                    color: #26734d; 
                    border: 1px solid #b7dfc9; 
                    border-radius: 8px; 
                    padding: 12px 16px; 
                    margin-bottom: 20px; 
                    text-align: center; 
                " 
            > 
 
                Livro atualizado com sucesso. 
 
            </div> 
 
        <?php endif; ?> 
 
 
        <!-- FILTROS --> 
 
        <form 
            method="GET" 
            action="acervoLivros.php" 
            style=" 
                margin-bottom: 25px; 
            " 
        > 
 
            <div 
                style=" 
                    display: flex; 
                    align-items: center; 
                    justify-content: space-between; 
                    gap: 25px; 
                    flex-wrap: wrap; 
                " 
            > 
 
 
                <!-- CATEGORIA --> 
 
                <div 
                    style=" 
                        display: flex; 
                        align-items: center; 
                        gap: 10px; 
                    " 
                > 
 
                    <span 
                        style=" 
                            font-size: 1.1rem; 
                            font-weight: 600; 
                            color: #333; 
                            white-space: nowrap; 
                        " 
                    > 
 
                        Selecionar categoria: 
 
                    </span> 
 
 
                    <div class="dropdown"> 
 
                        <button 
                            class="btn dropdown-toggle" 
                            type="button" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false" 
                            style=" 
                                background-color: #841717; 
                                color: #fff; 
                                padding: 9px 18px; 
                                font-size: 1rem; 
                            " 
                        > 
 
                            <?php echo $categoriaSelecionada !== '' 
                                ? htmlspecialchars($categoriaSelecionada) 
                                : 'Todas'; ?> 
 
                        </button> 
 
 
                        <ul 
                            class="dropdown-menu" 
                            style=" 
                                max-height: 320px; 
                                overflow-y: auto; 
                            " 
                        > 
 
                            <li> 
 
                                <a 
                                    class="dropdown-item" 
                                    href="<?php echo montarLinkPagina( 
                                        1, 
                                        $pesquisa, 
                                        '', 
                                        $statusSelecionados 
                                    ); ?>" 
                                > 
 
                                    Todas 
 
                                </a> 
 
                            </li> 
 
 
                            <?php foreach ( 
                                $categorias as $valorCategoria => 
                                $nomeCategoria 
                            ): ?> 
 
                                <li> 
 
                                    <a 
                                        class="dropdown-item" 
                                        href="<?php echo montarLinkPagina( 
                                            1, 
                                            $pesquisa, 
                                            $valorCategoria, 
                                            $statusSelecionados 
                                        ); ?>" 
                                    > 
 
                                        <?php echo htmlspecialchars( 
                                            $nomeCategoria 
                                        ); ?> 
 
                                    </a> 
 
                                </li> 
 
                            <?php endforeach; ?> 
 
                        </ul> 
 
                    </div> 
 
                </div> 
 
 
                <!-- STATUS --> 
 
                <div 
                    style=" 
                        display: flex; 
                        align-items: center; 
                        gap: 12px; 
                        flex-wrap: nowrap; 
                        white-space: nowrap; 
                    " 
                > 
 
                    <span 
                        style=" 
                            font-size: 1.1rem; 
                            font-weight: 600; 
                            color: #333; 
                        " 
                    > 
 
                        Status: 
 
                    </span> 
 
 
                    <div class="form-check form-check-inline mb-0"> 
 
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="status[]" 
                            value="Disponivel" 
                            id="statusDisponivel" 
 
                            <?php echo in_array( 
                                'Disponivel', 
                                $statusSelecionados 
                            ) 
                                ? 'checked' 
                                : ''; ?> 
                        > 
 
 
                        <label 
                            class="form-check-label" 
                            for="statusDisponivel" 
                        > 
 
                            Disponível 
 
                        </label> 
 
                    </div> 
 
 
                    <div class="form-check form-check-inline mb-0"> 
 
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="status[]" 
                            value="Reservado" 
                            id="statusReservado" 
 
                            <?php echo in_array( 
                                'Reservado', 
                                $statusSelecionados 
                            ) 
                                ? 'checked' 
                                : ''; ?> 
                        > 
 
 
                        <label 
                            class="form-check-label" 
                            for="statusReservado" 
                        > 
 
                            Reservado 
 
                        </label> 
 
                    </div> 
 
 
                    <div class="form-check form-check-inline mb-0"> 
 
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="status[]" 
                            value="Indisponivel" 
                            id="statusIndisponivel" 
 
                            <?php echo in_array( 
                                'Indisponivel', 
                                $statusSelecionados 
                            ) 
                                ? 'checked' 
                                : ''; ?> 
                        > 
 
 
                        <label 
                            class="form-check-label" 
                            for="statusIndisponivel" 
                        > 
 
                            Indisponível 
 
                        </label> 
 
                    </div> 
 
                </div> 
 
 
                <!-- PESQUISA --> 
 
                <div 
                    style=" 
                        display: flex; 
                        align-items: center; 
                        gap: 8px; 
                        margin-left: auto; 
                    " 
                > 
 
                    <input 
                        type="text" 
                        name="pesquisa" 
                        value="<?php echo htmlspecialchars($pesquisa); ?>" 
                        placeholder="Pesquisar livro..." 
                        class="form-control" 
                        style=" 
                            width: 250px; 
                            height: 42px; 
                        " 
                    > 
 
 
                    <?php if ($categoriaSelecionada !== ''): ?> 
 
                        <input 
                            type="hidden" 
                            name="categoria" 
                            value="<?php echo htmlspecialchars( 
                                $categoriaSelecionada 
                            ); ?>" 
                        > 
 
                    <?php endif; ?> 
 
 
                    <button 
                        type="submit" 
                        class="btn" 
                        style=" 
                            height: 42px; 
                            padding: 0 20px; 
                        " 
                    > 
 
                        Pesquisar 
 
                    </button> 
 
                </div> 
 
            </div> 
 
        </form> 
 
 
        <!-- LISTAGEM --> 
 
        <div 
            class="table-responsive" 
            style=" 
                margin-bottom: 0; 
            " 
        > 
 
            <table 
                class="table tabela-acervo align-middle" 
                style=" 
                    margin-bottom: 0; 
                    border-bottom: none; 
                " 
            > 
 
                <thead> 
 
                    <tr> 
 
                        <th style=" 
                            color: #841717; 
                            width: 70px; 
                            border-bottom: 2px solid #841717; 
                        "> 
 
                            ID 
 
                        </th> 
 
 
                        <th style=" 
                            color: #841717; 
                            width: 100px; 
                            border-bottom: 2px solid #841717; 
                        "> 
 
                            Capa 
 
                        </th> 
 
 
                        <th style=" 
                            color: #841717; 
                            border-bottom: 2px solid #841717; 
                        "> 
 
                            Título 
 
                        </th> 
 
 
                        <th style=" 
                            color: #841717; 
                            border-bottom: 2px solid #841717; 
                        "> 
 
                            Autor 
 
                        </th> 
 
 
                        <th style=" 
                            color: #841717; 
                            border-bottom: 2px solid #841717; 
                        "> 
 
                            Categoria 
 
                        </th> 
 
 
                        <th style=" 
                            color: #841717; 
                            width: 100px; 
                            border-bottom: 2px solid #841717; 
                        "> 
 
                            Quantidade 
 
                        </th> 
 
 
                        <th style=" 
                            color: #841717; 
                            width: 130px; 
                            border-bottom: 2px solid #841717; 
                        "> 
 
                            Status 
 
                        </th> 
 
 
                        <th style=" 
                            color: #841717; 
                            width: 110px; 
                            border-bottom: 2px solid #841717; 
                        "> 
 
                            Ação 
 
                        </th> 
 
                    </tr> 
 
                </thead> 
 
 
                <tbody> 
 
 
                    <?php if (count($livrosPagina) > 0): ?> 
 
                        <?php foreach ($livrosPagina as $livro): ?> 
 
                            <tr> 
 
                                <td> 
 
                                    <?php echo $livro['idLivro']; ?> 
 
                                </td> 
 
 
                                <td> 
 
                                    <?php if (!empty($livro['fotoLivro'])): ?> 
 
                                        <img 
                                            src="<?php echo htmlspecialchars( 
                                                $livro['fotoLivro'] 
                                            ); ?>" 
                                            alt="Capa de <?php echo htmlspecialchars( 
                                                $livro['titulo'] 
                                            ); ?>" 
                                            style=" 
                                                width: 65px; 
                                                height: 90px; 
                                                object-fit: contain; 
                                                box-shadow: 0 3px 8px rgba(107, 31, 42, 0.25); 
                                            " 
                                        > 
 
                                    <?php else: ?> 
 
                                        <div 
                                            class="d-flex align-items-center justify-content-center bg-light" 
                                            style=" 
                                                width: 65px; 
                                                height: 90px; 
                                                font-size: 0.75rem; 
                                                text-align: center; 
                                            " 
                                        > 
 
                                            Sem capa 
 
                                        </div> 
 
                                    <?php endif; ?> 
 
                                </td> 
 
 
                                <td> 
 
                                    <strong> 
 
                                        <?php echo htmlspecialchars( 
                                            $livro['titulo'] 
                                        ); ?> 
 
                                    </strong> 
 
                                </td> 
 
 
                                <td> 
 
                                    <?php echo htmlspecialchars( 
                                        $livro['autor'] 
                                    ); ?> 
 
                                </td> 
 
 
                                <td> 
 
                                    <?php echo htmlspecialchars( 
                                        $livro['categoria'] 
                                    ); ?> 
 
                                </td> 
 
 
                                <td> 
 
                                    <?php echo (int) $livro['quantidade']; ?> 
 
                                </td> 
 
 
                                <td> 
 
                                    <?php if ( 
                                        $livro['quantidadeDisponivel'] > 0 
                                    ): ?> 
 
                                        <span 
                                            style=" 
                                                color: green; 
                                                font-weight: 600; 
                                            " 
                                        > 
 
                                            Disponível 
 
                                        </span> 
 
 
                                    <?php elseif ( 
                                        $livro['quantidade'] > 0 && 
                                        $livro['status'] === 'Reservado' 
                                    ): ?> 
 
                                        <span 
                                            style=" 
                                                color: #d47b00; 
                                                font-weight: 600; 
                                            " 
                                        > 
 
                                            Reservado 
 
                                        </span> 
 
 
                                    <?php else: ?> 
 
                                        <span 
                                            style=" 
                                                color: #C40404; 
                                                font-weight: 600; 
                                            " 
                                        > 
 
                                            Indisponível 
 
                                        </span> 
 
                                    <?php endif; ?> 
 
                                </td> 
 
 
                                <td> 
 
                                    <button 
                                        type="button" 
                                        class="btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditarLivro<?php echo $livro['idLivro']; ?>" 
                                        style=" 
                                            padding: 8px 18px; 
                                        " 
                                    > 
 
                                        Editar 
 
                                    </button> 
 
                                </td> 
 
                            </tr> 
 
                        <?php endforeach; ?> 
 
 
                    <?php else: ?> 
 
                        <tr> 
 
                            <td 
                                colspan="8" 
                                style=" 
                                    text-align: center; 
                                    padding: 40px 20px; 
                                    color: #666; 
                                    border-bottom: none; 
                                " 
                            > 
 
                                Nenhum livro encontrado. 
 
                            </td> 
 
                        </tr> 
 
                    <?php endif; ?> 
 
                </tbody> 
 
            </table> 
 
        </div> 
 
 
        <!-- PAGINAÇÃO --> 
 
        <?php if ($totalPaginas > 1): ?> 
 
            <div 
                style=" 
                    display: flex; 
                    justify-content: center; 
                    margin-top: 22px; 
                    margin-bottom: -20px; 
                " 
            > 
 
                <nav aria-label="Navegação de páginas"> 
 
                    <ul 
                        class="pagination mb-0" 
                        style=" 
                            display: flex; 
                            align-items: center; 
                            gap: 7px; 
                            margin: 0; 
                        " 
                    > 
 
 
                        <?php if ($paginaAtual > 1): ?> 
 
                            <li class="page-item"> 
 
                                <a 
                                    class="page-link pagina-seta" 
                                    href="<?php echo montarLinkPagina( 
                                        $paginaAtual - 1, 
                                        $pesquisa, 
                                        $categoriaSelecionada, 
                                        $statusSelecionados 
                                    ); ?>" 
                                > 
 
                                    ‹ 
 
                                </a> 
 
                            </li> 
 
                        <?php endif; ?> 
 
 
                        <?php 
 
                        $paginasParaMostrar = array(); 
 
 
                        if ($totalPaginas <= 7) { 
 
                            for ( 
                                $i = 1; 
                                $i <= $totalPaginas; 
                                $i++ 
                            ) { 
 
                                $paginasParaMostrar[] = $i; 
 
                            } 
 
                        } else { 
 
                            $paginasParaMostrar[] = 1; 
 
 
                            if ($paginaAtual <= 4) { 
 
                                for ($i = 2; $i <= 5; $i++) { 
 
                                    $paginasParaMostrar[] = $i; 
 
                                } 
 
                                $paginasParaMostrar[] = '...'; 
 
                                $paginasParaMostrar[] = $totalPaginas; 
 
                            } elseif ( 
                                $paginaAtual < $totalPaginas - 3 
                            ) { 
 
                                $paginasParaMostrar[] = '...'; 
 
                                $paginasParaMostrar[] = 
                                    $paginaAtual - 1; 
 
                                $paginasParaMostrar[] = 
                                    $paginaAtual; 
 
                                $paginasParaMostrar[] = 
                                    $paginaAtual + 1; 
 
                                $paginasParaMostrar[] = '...'; 
 
                                $paginasParaMostrar[] = 
                                    $totalPaginas; 
 
                            } else { 
 
                                $paginasParaMostrar[] = '...'; 
 
                                for ( 
                                    $i = $totalPaginas - 4; 
                                    $i <= $totalPaginas; 
                                    $i++ 
                                ) { 
 
                                    $paginasParaMostrar[] = $i; 
 
                                } 
 
                            } 
 
                        } 
 
 
                        foreach ( 
                            $paginasParaMostrar as $pagina 
                        ): 
 
                        ?> 
 
                            <?php if ($pagina === '...'): ?> 
 
                                <li class="page-item"> 
 
                                    <span class="pagina-reticencias"> 
 
                                        ... 
 
                                    </span> 
 
                                </li> 
 
 
                            <?php else: ?> 
 
                                <li class="page-item"> 
 
                                    <a 
                                        class="page-link pagina-numero <?php echo $pagina == $paginaAtual 
                                            ? 'pagina-ativa' 
                                            : ''; ?>" 
                                        href="<?php echo montarLinkPagina( 
                                            $pagina, 
                                            $pesquisa, 
                                            $categoriaSelecionada, 
                                            $statusSelecionados 
                                        ); ?>" 
                                    > 
 
                                        <?php echo $pagina; ?> 
 
                                    </a> 
 
                                </li> 
 
                            <?php endif; ?> 
 
                        <?php endforeach; ?> 
 
 
                        <?php if ( 
                            $paginaAtual < $totalPaginas 
                        ): ?> 
 
                            <li class="page-item"> 
 
                                <a 
                                    class="page-link pagina-seta" 
                                    href="<?php echo montarLinkPagina( 
                                        $paginaAtual + 1, 
                                        $pesquisa, 
                                        $categoriaSelecionada, 
                                        $statusSelecionados 
                                    ); ?>" 
                                > 
 
                                    › 
 
                                </a> 
 
                            </li> 
 
                        <?php endif; ?> 
 
 
                    </ul> 
 
                </nav> 
 
            </div> 
 
        <?php endif; ?> 
 
    </div> 
 
</section> 
 
 
<!-- ====================================================== 
     MODAIS DE EDIÇÃO 
     ====================================================== --> 
 
<?php foreach ($livrosPagina as $livro): ?> 
 
 
    <div 
        class="modal fade" 
        id="modalEditarLivro<?php echo $livro['idLivro']; ?>" 
        tabindex="-1" 
        aria-hidden="true" 
    > 
 
        <div class="modal-dialog modal-xl modal-dialog-centered"> 
 
            <div class="modal-content"> 
 
 
                <!-- CABEÇALHO --> 
 
                <div 
                    class="modal-header" 
                    style=" 
                        border-bottom: 2px solid #841717; 
                        padding: 18px 25px; 
                    " 
                > 
 
                    <h5 
                        class="modal-title" 
                        style=" 
                            color: #841717; 
                            font-weight: 600; 
                        " 
                    > 
 
                        Editar livro 
 
                    </h5> 
 
 
                    <button 
                        type="button" 
                        class="btn-close" 
                        data-bs-dismiss="modal" 
                        aria-label="Fechar" 
                    ></button> 
 
                </div> 
 
 
                <form 
                    method="POST" 
                    action="acervoLivros.php" 
                    enctype="multipart/form-data" 
                > 
 
                    <div 
                        class="modal-body" 
                        style=" 
                            max-height: 70vh; 
                            overflow-y: auto; 
                            padding: 22px 25px; 
                        " 
                    > 
 
 
                        <input 
                            type="hidden" 
                            name="acao" 
                            value="editarLivro" 
                        > 
 
 
                        <input 
                            type="hidden" 
                            name="idLivro" 
                            value="<?php echo $livro['idLivro']; ?>" 
                        > 
 
 
                        <input 
                            type="hidden" 
                            name="pesquisaRetorno" 
                            value="<?php echo htmlspecialchars( 
                                $pesquisa 
                            ); ?>" 
                        > 
 
 
                        <input 
                            type="hidden" 
                            name="categoriaRetorno" 
                            value="<?php echo htmlspecialchars( 
                                $categoriaSelecionada 
                            ); ?>" 
                        > 
 
 
                        <input 
                            type="hidden" 
                            name="paginaRetorno" 
                            value="<?php echo $paginaAtual; ?>" 
                        > 
 
 
                        <?php foreach ( 
                            $statusSelecionados as $statusRetorno 
                        ): ?> 
 
                            <input 
                                type="hidden" 
                                name="statusRetorno[]" 
                                value="<?php echo htmlspecialchars( 
                                    $statusRetorno 
                                ); ?>" 
                            > 
 
                        <?php endforeach; ?> 
 
 
                        <div class="row g-3"> 
 
 
                            <!-- CAPA --> 
 
                            <div class="col-md-3 text-center"> 
 
                                <?php if (!empty($livro['fotoLivro'])): ?> 
 
                                    <img 
                                        src="<?php echo htmlspecialchars( 
                                            $livro['fotoLivro'] 
                                        ); ?>" 
                                        alt="Capa do livro" 
                                        style=" 
                                            width: 180px; 
                                            height: 245px; 
                                            object-fit: contain; 
                                            border-radius: 8px; 
                                            box-shadow: 0 4px 12px rgba(0,0,0,0.15); 
                                            margin-bottom: 12px; 
                                        " 
                                    > 
 
                                <?php endif; ?> 
 
 
                                <div class="text-start"> 
 
                                    <label 
                                        for="fotoLivro<?php echo $livro['idLivro']; ?>" 
                                        class="form-label fw-semibold" 
                                    > 
 
                                        Alterar capa 
 
                                    </label> 
 
 
                                    <input 
                                        type="file" 
                                        class="form-control" 
                                        name="fotoLivro" 
                                        id="fotoLivro<?php echo $livro['idLivro']; ?>" 
                                        accept=".jpg,.jpeg,.png,.webp,.gif" 
                                    > 
 
                                </div> 
 
                            </div> 
 
 
                            <!-- DADOS DO LIVRO --> 
 
                            <div class="col-md-9"> 
 
                                <div class="row g-3"> 
 
 
                                    <!-- TÍTULO --> 
 
                                    <div class="col-12"> 
 
                                        <label 
                                            for="titulo<?php echo $livro['idLivro']; ?>" 
                                            class="form-label fw-semibold" 
                                        > 
 
                                            Título 
 
                                        </label> 
 
 
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="titulo" 
                                            id="titulo<?php echo $livro['idLivro']; ?>" 
                                            value="<?php echo htmlspecialchars( 
                                                $livro['titulo'] 
                                            ); ?>" 
                                            required 
                                        > 
 
                                    </div> 
 
 
                                    <!-- AUTOR --> 
 
                                    <div class="col-md-6"> 
 
                                        <label 
                                            for="autor<?php echo $livro['idLivro']; ?>" 
                                            class="form-label fw-semibold" 
                                        > 
 
                                            Autor 
 
                                        </label> 
 
 
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="autor" 
                                            id="autor<?php echo $livro['idLivro']; ?>" 
                                            value="<?php echo htmlspecialchars( 
                                                $livro['autor'] 
                                            ); ?>" 
                                            required 
                                        > 
 
                                    </div> 
 
 
                                    <!-- EDITORA --> 
 
                                    <div class="col-md-6"> 
 
                                        <label 
                                            for="editora<?php echo $livro['idLivro']; ?>" 
                                            class="form-label fw-semibold" 
                                        > 
 
                                            Editora 
 
                                        </label> 
 
 
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="editora" 
                                            id="editora<?php echo $livro['idLivro']; ?>" 
                                            value="<?php echo htmlspecialchars( 
                                                $livro['editora'] 
                                            ); ?>" 
                                        > 
 
                                    </div> 
 
 
                                    <!-- ANO --> 
 
                                    <div class="col-md-4"> 
 
                                        <label 
                                            for="anoPublicacao<?php echo $livro['idLivro']; ?>" 
                                            class="form-label fw-semibold" 
                                        > 
 
                                            Ano de publicação 
 
                                        </label> 
 
 
                                        <input 
                                            type="number" 
                                            class="form-control" 
                                            name="anoPublicacao" 
                                            id="anoPublicacao<?php echo $livro['idLivro']; ?>" 
                                            value="<?php echo htmlspecialchars( 
                                                $livro['anoPublicacao'] 
                                            ); ?>" 
                                            min="1000" 
                                            max="2026" 
                                        > 
 
                                    </div> 
 
 
                                    <!-- CATEGORIA --> 
 
                                    <div class="col-md-8"> 
 
                                        <label 
                                            for="categoria<?php echo $livro['idLivro']; ?>" 
                                            class="form-label fw-semibold" 
                                        > 
 
                                            Categoria 
 
                                        </label> 
 
 
                                        <select 
                                            class="form-control" 
                                            name="categoria" 
                                            id="categoria<?php echo $livro['idLivro']; ?>" 
                                            required 
                                        > 
 
                                            <?php foreach ( 
                                                $categorias 
                                                as $valorCategoria => 
                                                $nomeCategoria 
                                            ): ?> 
 
                                                <option 
                                                    value="<?php echo htmlspecialchars( 
                                                        $valorCategoria 
                                                    ); ?>" 
 
                                                    <?php echo $livro['categoria'] === $valorCategoria 
                                                        ? 'selected' 
                                                        : ''; ?> 
                                                > 
 
                                                    <?php echo htmlspecialchars( 
                                                        $nomeCategoria 
                                                    ); ?> 
 
                                                </option> 
 
                                            <?php endforeach; ?> 
 
                                        </select> 
 
                                    </div> 
 
 
                                    <!-- SINOPSE --> 
 
                                    <div class="col-12"> 
 
                                        <label 
                                            for="sinopse<?php echo $livro['idLivro']; ?>" 
                                            class="form-label fw-semibold" 
                                        > 
 
                                            Sinopse 
 
                                        </label> 
 
 
                                        <textarea 
                                            class="form-control" 
                                            name="sinopse" 
                                            id="sinopse<?php echo $livro['idLivro']; ?>" 
                                            rows="5" 
                                            required 
                                        ><?php echo htmlspecialchars( 
                                            $livro['sinopse'] 
                                        ); ?></textarea> 
 
                                    </div> 
 
 
                                </div> 
 
                            </div> 
 
 
                            <!-- EXEMPLARES --> 
 
                            <div class="col-12"> 
 
                                <div 
                                    style=" 
                                        border-top: 1px solid #ddd; 
                                        padding-top: 20px; 
                                        margin-top: 8px; 
                                    " 
                                > 
 
                                    <div 
                                        style=" 
                                            display: flex; 
                                            align-items: center; 
                                            justify-content: space-between; 
                                            gap: 15px; 
                                            margin-bottom: 15px; 
                                        " 
                                    > 
 
                                        <div> 
 
                                            <h6 
                                                style=" 
                                                    color: #841717; 
                                                    font-weight: 600; 
                                                    margin-bottom: 3px; 
                                                " 
                                            > 
 
                                                Exemplares 
 
                                            </h6> 
 
 
                                            <small 
                                                style=" 
                                                    color: #666; 
                                                " 
                                            > 
 
                                                Gerencie o estado e o status de cada exemplar deste livro. 
 
                                            </small> 
 
                                        </div> 
 
 
                                        <button 
                                            type="button" 
                                            class="btn" 
                                            onclick="adicionarExemplar(<?php echo $livro['idLivro']; ?>)" 
                                        > 
 
                                            + Adicionar exemplar 
 
                                        </button> 
 
                                    </div> 
 
 
                                    <div 
                                        id="exemplares<?php echo $livro['idLivro']; ?>" 
                                    > 
 
 
                                        <?php if ( 
                                            count($livro['exemplares']) > 0 
                                        ): ?> 
 
 
                                            <?php foreach ( 
                                                $livro['exemplares'] 
                                                as $indiceExemplar => 
                                                $exemplar 
                                            ): ?> 
 
                                                <div 
                                                    class="exemplar-existente" 
                                                    style=" 
                                                        border: 1px solid #e5e5e5; 
                                                        border-radius: 8px; 
                                                        padding: 15px; 
                                                        margin-bottom: 10px; 
                                                    " 
                                                > 
 
                                                    <div 
                                                        style=" 
                                                            display: flex; 
                                                            justify-content: space-between; 
                                                            align-items: center; 
                                                            margin-bottom: 12px; 
                                                        " 
                                                    > 
 
                                                        <strong 
                                                            style=" 
                                                                color: #841717; 
                                                            " 
                                                        > 
 
                                                            Exemplar <?php echo $indiceExemplar + 1; ?> 
 
                                                        </strong> 
 
                                                        <span 
                                                            style=" 
                                                                font-size: 0.85rem; 
                                                                color: #777; 
                                                            " 
                                                        > 
 
                                                            Já cadastrado 
 
                                                        </span> 
 
                                                    </div> 
 
 
                                                    <input 
                                                        type="hidden" 
                                                        name="exemplarId[]" 
                                                        value="<?php echo $exemplar['idExemplar']; ?>" 
                                                    > 
 
 
                                                    <div class="row g-3"> 
 
                                                        <div class="col-md-6"> 
 
                                                            <label 
                                                                class="form-label small fw-semibold" 
                                                            > 
 
                                                                Estado 
 
                                                            </label> 
 
 
                                                            <select 
                                                                class="form-control" 
                                                                name="exemplarEstado[]" 
                                                            > 
 
                                                                <option 
                                                                    value="Novo" 
 
                                                                    <?php echo $exemplar['estado'] === 'Novo' 
                                                                        ? 'selected' 
                                                                        : ''; ?> 
                                                                > 
 
                                                                    Novo 
 
                                                                </option> 
 
 
                                                                <option 
                                                                    value="Bom" 
 
                                                                    <?php echo $exemplar['estado'] === 'Bom' 
                                                                        ? 'selected' 
                                                                        : ''; ?> 
                                                                > 
 
                                                                    Bom 
 
                                                                </option> 
 
 
                                                                <option 
                                                                    value="Regular" 
 
                                                                    <?php echo $exemplar['estado'] === 'Regular' 
                                                                        ? 'selected' 
                                                                        : ''; ?> 
                                                                > 
 
                                                                    Regular 
 
                                                                </option> 
 
                                                            </select> 
 
                                                        </div> 
 
 
                                                        <div class="col-md-6"> 
 
                                                            <label 
                                                                class="form-label small fw-semibold" 
                                                            > 
 
                                                                Status 
 
                                                            </label> 
 
 
                                                            <select 
                                                                class="form-control" 
                                                                name="exemplarStatus[]" 
                                                            > 
 
                                                                <option 
                                                                    value="Disponivel" 
 
                                                                    <?php echo $exemplar['status'] === 'Disponivel' 
                                                                        ? 'selected' 
                                                                        : ''; ?> 
                                                                > 
 
                                                                    Disponível 
 
                                                                </option> 
 
 
                                                                <option 
                                                                    value="Reservado" 
 
                                                                    <?php echo $exemplar['status'] === 'Reservado' 
                                                                        ? 'selected' 
                                                                        : ''; ?> 
                                                                > 
 
                                                                    Reservado 
 
                                                                </option> 
 
 
                                                                <option 
                                                                    value="Indisponivel" 
 
                                                                    <?php echo $exemplar['status'] === 'Indisponivel' 
                                                                        ? 'selected' 
                                                                        : ''; ?> 
                                                                > 
 
                                                                    Indisponível 
 
                                                                </option> 
 
                                                            </select> 
 
                                                        </div> 
 
                                                    </div> 
 
                                                </div> 
 
                                            <?php endforeach; ?> 
 
 
                                        <?php else: ?> 
 
 
                                            <div 
                                                style=" 
                                                    background-color: #f8f8f8; 
                                                    border-radius: 8px; 
                                                    padding: 15px; 
                                                    text-align: center; 
                                                    color: #666; 
                                                " 
                                            > 
 
                                                Este livro ainda não possui exemplares cadastrados. 
 
                                            </div> 
 
                                        <?php endif; ?> 
 
 
                                    </div> 
 
                                </div> 
 
                            </div> 
 
 
                        </div> 
 
                    </div> 
 
 
                    <!-- RODAPÉ --> 
 
                    <div 
                        class="modal-footer" 
                        style=" 
                            border-top: 1px solid #ddd; 
                            padding: 14px 25px; 
                            background-color: #fff; 
                        " 
                    > 
 
                        <button 
                            type="button" 
                            class="btn" 
                            data-bs-dismiss="modal" 
                            style=" 
                                background-color: #6c757d; 
                                color: #fff; 
                                padding: 9px 20px; 
                            " 
                        > 
 
                            Cancelar 
 
                        </button> 
 
 
                        <button 
                            type="submit" 
                            class="btn" 
                            style=" 
                                padding: 9px 22px; 
                            " 
                        > 
 
                            Salvar alterações 
 
                        </button> 
 
                    </div> 
 
                </form> 
 
            </div> 
 
        </div> 
 
    </div> 
 
 
<?php endforeach; ?> 
 
 
<style> 
 
.tabela-acervo { 
 
    border-collapse: collapse; 
 
} 
 
 
.tabela-acervo th, 
.tabela-acervo td { 
 
    vertical-align: middle; 
 
    border-bottom: 1px solid #e5e5e5; 
 
} 
 
 
.tabela-acervo tbody tr:last-child td { 
 
    border-bottom: none !important; 
 
} 
 
 
.tabela-acervo tbody tr:hover { 
 
    background-color: #fafafa; 
 
} 
 
 
.form-check-input:checked { 
 
    background-color: #841717; 
 
    border-color: #841717; 
 
} 
 
 
.pagina-numero, 
.pagina-seta { 
 
    display: flex; 
 
    align-items: center; 
 
    justify-content: center; 
 
    width: 40px; 
 
    height: 40px; 
 
    padding: 0; 
 
    border-radius: 8px !important; 
 
    font-weight: 600; 
 
    text-decoration: none; 
 
} 
 
 
.pagina-numero { 
 
    background-color: #fff; 
 
    color: #841717; 
 
    border: 1px solid #d8d8d8; 
 
} 
 
 
.pagina-numero:hover { 
 
    background-color: #f5e8e8; 
 
    color: #841717; 
 
    border-color: #841717; 
 
} 
 
 
.pagina-numero.pagina-ativa { 
 
    background-color: #841717; 
 
    color: #fff; 
 
    border-color: #841717; 
 
} 
 
 
.pagina-numero.pagina-ativa:hover { 
 
    background-color: #841717; 
 
    color: #fff; 
 
    border-color: #841717; 
 
} 
 
 
.pagina-seta { 
 
    background-color: #841717; 
 
    color: #fff; 
 
    border: 1px solid #841717; 
 
    font-size: 1.3rem; 
 
} 
 
 
.pagina-seta:hover { 
 
    background-color: #6f1313; 
 
    color: #fff; 
 
    border-color: #6f1313; 
 
} 
 
 
.pagina-reticencias { 
 
    display: flex; 
 
    align-items: center; 
 
    justify-content: center; 
 
    width: 28px; 
 
    height: 40px; 
 
    color: #666; 
 
    font-weight: 600; 
 
} 
 
 
.exemplar-existente { 
 
    transition: 0.2s; 
 
} 
 
 
.exemplar-existente:hover { 
 
    border-color: #841717 !important; 
 
    background-color: #fffafa; 
 
} 
 
 
.exemplar-existente select { 
 
    height: 38px; 
 
    border-radius: 6px; 
 
    padding: 6px 12px; 
 
    background-color: #fff; 
 
} 
 
 
</style> 
 
 
<script> 
 
function adicionarExemplar(idLivro) { 
 
    const container = document.getElementById( 
        'exemplares' + idLivro 
    ); 
 
 
    if (!container) { 
 
        return; 
 
    } 
 
 
    const quantidadeExistente = 
        container.querySelectorAll( 
            '.exemplar-existente' 
        ).length; 
 
 
    const quantidadeNovos = 
        container.querySelectorAll( 
            '.exemplar-novo' 
        ).length; 
 
 
    const numeroExemplar = 
        quantidadeExistente + 
        quantidadeNovos + 
        1; 
 
 
    const item = document.createElement('div'); 
 
    item.className = 'exemplar-novo'; 
 
    item.style.border = '2px dashed #841717'; 
 
    item.style.borderRadius = '8px'; 
 
    item.style.padding = '15px'; 
 
    item.style.marginBottom = '10px'; 
 
    item.style.backgroundColor = '#fffafa'; 
 
 
    item.innerHTML = ` 
 
        <div 
            style=" 
                display: flex; 
                justify-content: space-between; 
                align-items: center; 
                margin-bottom: 12px; 
            " 
        > 
 
            <strong 
                style=" 
                    color: #841717; 
                " 
            > 
 
                Exemplar ${numeroExemplar} 
 
            </strong> 
 
 
            <span 
                style=" 
                    font-size: 0.85rem; 
                    color: #841717; 
                    font-weight: 600; 
                " 
            > 
 
                Novo 
 
            </span> 
 
        </div> 
 
 
        <div class="row g-3"> 
 
            <div class="col-md-6"> 
 
                <label 
                    class="form-label small fw-semibold" 
                > 
 
                    Estado 
 
                </label> 
 
 
                <select 
                    class="form-control" 
                    name="novoEstado[]" 
                > 
 
                    <option value="Novo"> 
 
                        Novo 
 
                    </option> 
 
 
                    <option value="Bom" selected> 
 
                        Bom 
 
                    </option> 
 
 
                    <option value="Regular"> 
 
                        Regular 
 
                    </option> 
 
                </select> 
 
            </div> 
 
 
            <div class="col-md-6"> 
 
                <label 
                    class="form-label small fw-semibold" 
                > 
 
                    Status 
 
                </label> 
 
 
                <select 
                    class="form-control" 
                    name="novoStatus[]" 
                > 
 
                    <option 
                        value="Disponivel" 
                        selected 
                    > 
 
                        Disponível 
 
                    </option> 
 
 
                    <option value="Reservado"> 
 
                        Reservado 
 
                    </option> 
 
 
                    <option value="Indisponivel"> 
 
                        Indisponível 
 
                    </option> 
 
                </select> 
 
            </div> 
 
        </div> 
 
    `; 
 
 
    container.appendChild(item); 
 
} 
 
</script> 
 
 
<?php include_once("footer.php"); ?>