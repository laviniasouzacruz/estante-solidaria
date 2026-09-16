-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Set-2026 às 22:15
-- Versão do servidor: 8.0.29
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `estante_solidaria`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `exemplares`
--

CREATE TABLE `exemplares` (
  `idExemplar` int NOT NULL,
  `idLivro` int NOT NULL,
  `estado` enum('Novo','Bom','Regular') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Bom',
  `status` enum('Disponivel','Reservado','Indisponivel') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Disponivel'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `exemplares`
--

INSERT INTO `exemplares` (`idExemplar`, `idLivro`, `estado`, `status`) VALUES
(1, 5, 'Bom', 'Disponivel'),
(2, 6, 'Bom', 'Indisponivel'),
(3, 7, 'Novo', 'Disponivel'),
(4, 8, 'Bom', 'Disponivel'),
(5, 9, 'Novo', 'Disponivel'),
(6, 10, 'Bom', 'Disponivel'),
(7, 11, 'Bom', 'Disponivel'),
(8, 12, 'Bom', 'Disponivel'),
(9, 13, 'Bom', 'Disponivel'),
(10, 14, 'Bom', 'Disponivel'),
(11, 15, 'Novo', 'Disponivel'),
(12, 16, 'Bom', 'Disponivel'),
(13, 17, 'Bom', 'Disponivel'),
(14, 18, 'Novo', 'Disponivel'),
(15, 19, 'Regular', 'Disponivel'),
(16, 20, 'Bom', 'Disponivel'),
(17, 21, 'Bom', 'Disponivel'),
(18, 22, 'Bom', 'Disponivel'),
(19, 23, 'Regular', 'Disponivel'),
(20, 24, 'Bom', 'Disponivel'),
(21, 25, 'Bom', 'Disponivel'),
(22, 26, 'Bom', 'Disponivel'),
(23, 27, 'Novo', 'Disponivel'),
(24, 28, 'Regular', 'Disponivel'),
(25, 29, 'Novo', 'Disponivel'),
(26, 30, 'Bom', 'Disponivel'),
(27, 31, 'Bom', 'Disponivel'),
(28, 32, 'Bom', 'Disponivel'),
(29, 33, 'Bom', 'Disponivel'),
(30, 34, 'Regular', 'Disponivel'),
(31, 35, 'Regular', 'Disponivel'),
(32, 36, 'Bom', 'Disponivel'),
(33, 37, 'Bom', 'Disponivel'),
(64, 28, 'Bom', 'Disponivel'),
(65, 14, 'Regular', 'Disponivel');

-- --------------------------------------------------------

--
-- Estrutura da tabela `livros`
--

CREATE TABLE `livros` (
  `idLivro` int NOT NULL,
  `titulo` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `autor` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `editora` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anoPublicacao` year DEFAULT NULL,
  `fotoLivro` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `categoria` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sinopse` text COLLATE utf8mb4_general_ci,
  `idUsuario` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `livros`
--

INSERT INTO `livros` (`idLivro`, `titulo`, `autor`, `editora`, `anoPublicacao`, `fotoLivro`, `categoria`, `sinopse`, `idUsuario`) VALUES
(5, 'Manual de Assassinato para Boas Garotas', 'Holly Jackson', 'Intrínseca', 2019, 'images/livros/manual-assasinato.jpg', 'Suspense e Terror', 'Manual de Assassinato para Boas Garotas, de Holly Jackson, acompanha Pip Fitz-Amobi, uma estudante que decide investigar o assassinato de Andie Bell, uma jovem de sua cidade que teria sido morta pelo namorado, Sal Singh. Apesar de o caso ser considerado encerrado, Pip percebe que existem informações que não se encaixam na versão oficial e começa a investigar por conta própria. Conforme reúne pistas e descobre novos segredos, ela percebe que está cada vez mais próxima de uma verdade perigosa.', 1),
(6, 'Sem Defeitos', 'Elsie Silver', 'Arqueiro', 2024, 'images/livros/sem-defeitos.jpg', 'Romance', 'Summer Hamilton chega a Chestnut Springs para trabalhar como assistente de Rhett Eaton, um famoso astro de rodeio que está enfrentando dificuldades para controlar sua imagem após um acidente. O problema é que Rhett é teimoso, impulsivo e pouco disposto a seguir regras, enquanto Summer precisa garantir que ele permaneça longe de confusões. Obrigados a passar cada vez mais tempo juntos, os dois começam a perceber que a convivência pode esconder uma atração difícil de ignorar. Entre provocações, conflitos e sentimentos inesperados, Summer e Rhett precisam descobrir se conseguem transformar essa proximidade em algo mais.', 1),
(7, 'Mecânica Automotiva Básica - Um Guia Ilustrado para Iniciantes', 'Anderson Glatz Ferreira', 'Clube de Autores', 2026, 'images/livros/mecanica-automotiva-basica.png', 'Automação e Mecânica', 'Mecânica Automotiva Básica – Um Guia Ilustrado para Iniciantes, de Anderson Glatz Ferreira, apresenta os principais conceitos da mecânica de automóveis de forma simples e acessível. A obra aborda componentes e sistemas fundamentais dos veículos, seu funcionamento, manutenção preventiva e cuidados básicos, utilizando explicações e ilustrações para facilitar a compreensão. É voltada especialmente para quem deseja conhecer melhor o funcionamento de um carro e adquirir conhecimentos básicos sobre manutenção automotiva.', 1),
(8, 'Tecnologias para Automação: Circuitos Pneumáticos – Óleo-Hidráulicos – Controladores Lógicos Programáveis (CLP) e Microcontrolador', 'Valdemir Martins Lira, Alexandre Acácio de Andrade e Carlos Eduardo Capovilla', 'Blucher', 2024, 'images/livros/tecnologias-para-automacao.jpg', 'Automação e Mecânica', 'Tecnologias para Automação: Circuitos Pneumáticos e Hidráulicos, de Valdemir Martins Lira, Alexandre Acácio de Andrade e colaboradores, apresenta os fundamentos das tecnologias utilizadas em sistemas de automação industrial. A obra aborda conceitos, componentes e funcionamento de circuitos pneumáticos e hidráulicos, relacionando teoria e aplicações práticas. Com linguagem técnica e recursos ilustrativos, o livro auxilia na compreensão, montagem e análise desses sistemas, sendo útil para estudantes e profissionais da área de automação e manutenção industrial.', 1),
(9, 'Frankenstein', 'Mary Shelley', 'Aleph', 2024, 'images/livros/frankenstein.jpg', 'Ficção', 'Frankenstein, de Mary Shelley, conta a história de Victor Frankenstein, um jovem cientista que se dedica a um experimento ousado: criar vida a partir de partes de corpos humanos. Ao conseguir dar vida à sua criatura, Victor fica horrorizado com o resultado e a abandona. Rejeitada pela sociedade e tomada pela solidão, a criatura passa a buscar respostas sobre sua própria existência, enquanto o conflito entre ela e seu criador se intensifica. A obra aborda temas como ambição, responsabilidade, solidão, preconceito e os limites da ciência.', 1),
(10, 'A Máquina do Tempo', 'H. G. Wells', 'Companhia das Letras / Editora Seguinte', 2018, 'images/livros/a-maquina-do-tempo.jpg', 'Ficção', 'A Máquina do Tempo, de H. G. Wells, acompanha um cientista conhecido como Viajante do Tempo que constrói uma máquina capaz de atravessar as eras. Ao viajar para um futuro distante, ele encontra um mundo muito diferente daquele que conhecia, habitado pelos pacíficos Elois e pelos misteriosos Morlocks. Enquanto tenta compreender essa sociedade e encontrar uma forma de retornar ao seu tempo, ele descobre um futuro marcado pelas consequências da evolução e das desigualdades sociais.', 1),
(11, 'A Guerra dos Mundos', 'H. G. Wells', 'Principis', 2020, 'images/livros/a-guerra-dos-mundos.jpeg', 'Ficção', 'A Guerra dos Mundos, de H. G. Wells, narra a invasão da Terra por marcianos tecnologicamente superiores, que chegam ao planeta com máquinas de guerra e começam a destruir tudo ao seu redor. A história acompanha a luta dos seres humanos pela sobrevivência diante de uma ameaça desconhecida, enquanto explora temas como medo, resistência, ciência e os limites da humanidade diante de uma força muito mais poderosa.', 1),
(12, 'Duna', 'Frank Herbert', 'Aleph', 2010, 'images/livros/duna.jpg', 'Ficção', 'Duna, de Frank Herbert, acompanha Paul Atreides, jovem herdeiro da Casa Atreides que se muda com sua família para Arrakis, um planeta desértico e de enorme importância por ser a única fonte da especiaria, recurso essencial para o universo conhecido. Após uma traição que coloca sua família em perigo, Paul precisa sobreviver em um ambiente hostil e se aproximar dos Fremen, habitantes do deserto. Entre disputas políticas, conflitos pelo controle da especiaria e descobertas sobre seu próprio destino, Paul se vê envolvido em acontecimentos que podem transformar o futuro de toda a humanidade.', 1),
(13, 'Os Lusíadas', 'Luís de Camões', 'Faro Editorial', 2024, 'images/livros/os-lusiadas.jpg', 'Poesia', 'Os Lusíadas, de Luís de Camões, é um poema épico que narra a viagem de Vasco da Gama e dos navegadores portugueses rumo às Índias, destacando as aventuras, desafios e conquistas enfrentados durante a jornada. A obra também apresenta episódios da história de Portugal e elementos da mitologia greco-romana, com deuses que interferem no destino dos navegadores. Ao longo dos cantos, Camões celebra a expansão marítima portuguesa e os feitos de seu povo, construindo uma das principais obras da literatura em língua portuguesa.', 1),
(14, 'A Princesa Salva a Si Mesma Neste Livro', 'Amanda Lovelace', 'LeYa', 2017, 'images/livros/a-princesa-salva-a-si-mesma-nesse-livro.jpg', 'Poesia', 'A Princesa Salva a Si Mesma Neste Livro, de Amanda Lovelace, é uma coletânea de poemas que ressignifica os contos de fadas tradicionais a partir de temas como amor, perda, abuso, empoderamento e superação. Dividida em quatro partes, a obra acompanha uma jornada de dor e reconstrução, mostrando uma princesa que deixa de esperar por um salvador e aprende a encontrar a própria força.', 1),
(15, 'Textos para Tocar Cicatrizes', 'Igor Pires', 'Globo Alt', 2022, 'images/livros/textos-para-tocar-cicatrizes.jpg', 'Poesia', 'Textos para Tocar Cicatrizes, de Igor Pires, reúne poemas e textos curtos que exploram sentimentos como amor, saudade, perda, solidão e recomeço. Com uma escrita sensível e intimista, a obra transforma experiências dolorosas em palavras, abordando as marcas deixadas por relações, despedidas e momentos difíceis. O livro convida o leitor a reconhecer suas próprias cicatrizes e encontrar, na escrita, uma forma de acolhimento e reflexão.', 1),
(16, 'Todas as Flores que Não Te Enviei', 'Felipe Rocha', 'Astral Cultural', 2018, 'images/livros/todas-as-flores-que-nao-te-enviei.jpg', 'Poesia', 'Todas as Flores que Não Te Enviei, de Felipe Rocha, reúne poemas e textos que exploram sentimentos como amor, saudade, ausência e as palavras que muitas vezes permanecem guardadas. Com uma escrita delicada e intimista, a obra transforma emoções e experiências em versos, refletindo sobre encontros, despedidas, relações e tudo aquilo que ficou por dizer.', 1),
(17, 'Um Homem de Sorte', 'Nicholas Sparks', 'Arqueiro', 2011, 'images/livros/um-homem-de-sorte.jpg', 'Romance', 'Um Homem de Sorte, de Nicholas Sparks, acompanha Logan Thibault, um ex-soldado que retorna aos Estados Unidos após encontrar, durante a guerra, uma fotografia de uma mulher desconhecida que acredita ter sido responsável por sua sorte e sobrevivência. Determinado a encontrá-la, ele parte em uma jornada que o leva até Beth, uma mulher que vive com o filho e a avó. O encontro entre os dois desperta sentimentos inesperados, mas Logan precisa lidar com segredos do passado e com as consequências de suas escolhas antes de descobrir se o amor pode realmente mudar seu destino.', 1),
(18, 'A Hipótese do Amor', 'Ali Hazelwood', 'Arqueiro', 2022, 'images/livros/a-hipotese-do-amor.jpg', 'Romance', 'A Hipótese do Amor, de Ali Hazelwood, acompanha Olive Smith, uma doutoranda em Biologia que, para convencer sua melhor amiga de que está seguindo em frente, acaba entrando em um relacionamento de mentira com Adam Carlsen, um professor conhecido por ser exigente e intimidador. O que começa como um acordo conveniente logo se complica quando a convivência entre os dois desperta uma atração inesperada. Enquanto Olive tenta equilibrar a vida acadêmica, suas inseguranças e os sentimentos por Adam, ela percebe que talvez essa relação não seja tão falsa quanto imaginava.', 1),
(19, 'É Assim que Acaba', 'Colleen Hoover', 'Galera Record', 2018, 'images/livros/e-assim-que-acaba.jpg', 'Romance', 'É Assim que Acaba, de Colleen Hoover, acompanha Lily Bloom, uma jovem que constrói uma nova vida em Boston e se envolve com Ryle Kincaid, um neurocirurgião atraente e determinado. O relacionamento dos dois parece promissor, mas acontecimentos dolorosos fazem Lily questionar a relação que está construindo. Quando Atlas Corrigan, seu primeiro amor, reaparece, ela precisa confrontar lembranças do passado e tomar decisões difíceis sobre o futuro. A obra aborda relacionamentos abusivos, violência, amor, escolhas e os ciclos que podem se repetir ao longo das gerações.', 1),
(20, 'Os Sete Maridos de Evelyn Hugo', 'Taylor Jenkins Reid', 'Paralela', 2019, 'images/livros/os-sete-maridos-de-evelyn-hugo.jpg', 'Romance', 'Os Sete Maridos de Evelyn Hugo, de Taylor Jenkins Reid, acompanha Monique Grant, uma jornalista desconhecida que recebe uma proposta inesperada da lendária atriz Evelyn Hugo. Já idosa e afastada dos holofotes, Evelyn decide contar toda a história de sua vida, incluindo os detalhes de seus sete casamentos e os segredos que escondeu durante décadas. Ao longo dos relatos, Monique descobre que a vida da atriz foi muito mais complexa do que a imagem criada pela imprensa e que sua própria história está ligada à de Evelyn de uma maneira que jamais imaginou.', 1),
(21, 'O Fabricante de Lágrimas', 'Erin Doom', 'Faro Editorial', 2023, 'images/livros/o-fabricante-de-lagrimas.jpg', 'Romance', 'O Fabricante de Lágrimas, de Erin Doom, acompanha Nica, uma jovem órfã que cresceu em um orfanato e sempre ouviu a lenda sobre o Fabricante de Lágrimas, uma figura misteriosa responsável por criar os medos e as angústias dos seres humanos. Aos dezessete anos, Nica finalmente é adotada por uma família, mas precisa dividir sua nova casa com Rigel, outro jovem do orfanato que também foi adotado. Entre os dois existe uma relação intensa e complicada, marcada por conflitos, segredos e sentimentos que eles não conseguem ignorar.', 1),
(22, 'Uma Mulher no Escuro', 'Raphael Montes', 'Companhia das Letras', 2019, 'images/livros/uma-mulher-no-escuro.jpg', 'Suspense e Terror', 'Uma Mulher no Escuro, de Raphael Montes, acompanha Victoria Bravo, uma jovem que sobreviveu a uma tragédia familiar quando ainda era criança. Anos depois, vivendo de forma isolada e tentando reconstruir a própria vida, Victoria começa a receber mensagens e a perceber acontecimentos estranhos que despertam lembranças do passado. Conforme os mistérios se intensificam, ela passa a questionar em quem pode confiar e precisa enfrentar os traumas que tentou deixar para trás para descobrir a verdade sobre o que aconteceu com sua família.', 1),
(23, 'Bom Dia, Verônica', 'Ilana Casoy e Raphael Montes', 'DarkSide Books', 2016, 'images/livros/bom-dia-veronica.jpg', 'Suspense e Terror', 'Bom Dia, Verônica, de Ilana Casoy e Raphael Montes, acompanha Verônica Torres, uma secretária da polícia que decide investigar por conta própria o caso de uma mulher vítima de um golpista. Ao mesmo tempo, ela se depara com uma denúncia anônima envolvendo uma mulher mantida em uma relação abusiva. Conforme avança nas investigações, Verônica descobre uma série de crimes e se aproxima de um criminoso perigoso. Entre segredos, violência e ameaças, ela precisa enfrentar situações cada vez mais arriscadas para descobrir a verdade.', 1),
(24, 'O Homem de Giz', 'C. J. Tudor', 'Intrínseca', 2018, 'images/livros/o-homem-de-giz.jpg', 'Suspense e Terror', 'O Homem de Giz, de C. J. Tudor, acompanha Eddie e seus amigos, que, na infância, usavam desenhos feitos com giz para criar um código secreto entre eles. Tudo muda quando os desenhos os levam até o corpo de uma garota morta. Trinta anos depois, Eddie recebe uma mensagem contendo um desenho de um homem de giz, fazendo com que as lembranças daquele acontecimento voltem à tona. Ao investigar o passado, ele percebe que os segredos daquela época ainda não foram completamente revelados e que alguém parece estar disposto a impedir que a verdade venha à tona.', 1),
(25, 'O Programador Pragmático', 'David Thomas e Andrew Hunt', 'Bookman', 2019, 'images/livros/o-programador-pragmatico.jpg', 'Informática e Tecnologia', 'O Programador Pragmático, de David Thomas e Andrew Hunt, apresenta princípios e práticas para desenvolver software de forma mais eficiente, organizada e profissional. A obra aborda temas como responsabilidade do programador, manutenção de código, testes, automação, depuração, arquitetura e gerenciamento de projetos. Com exemplos práticos e reflexões sobre o trabalho na área de programação, o livro busca ajudar desenvolvedores a aprimorar suas habilidades e a criar sistemas mais flexíveis, confiáveis e fáceis de manter.', 1),
(26, 'Entendendo Algoritmos', 'Aditya Y. Bhargava', 'Novatec', 2017, 'images/livros/entendendo-algoritmos.jpg', 'Informática e Tecnologia', 'Entendendo Algoritmos, de Aditya Y. Bhargava, apresenta os principais conceitos de algoritmos e estruturas de dados de forma didática e acessível. A obra utiliza exemplos práticos e ilustrações para explicar temas como pesquisa, ordenação, recursão, tabelas hash, grafos e algoritmos de caminho mínimo. Voltado especialmente para quem está começando na programação, o livro ajuda o leitor a compreender como os algoritmos funcionam e a desenvolver estratégias para resolver problemas de maneira mais eficiente.', 1),
(27, 'Inteligência Artificial para Leigos', 'John Paul Mueller e Luca Massaron', 'Alta Books', 2019, 'images/livros/inteligencia-artificial.jpg', 'Informática e Tecnologia', 'Inteligência Artificial para Leigos, de John Paul Mueller e Luca Massaron, apresenta os principais conceitos e aplicações da inteligência artificial de maneira acessível para quem está começando no assunto. A obra explica como funcionam tecnologias como aprendizado de máquina, redes neurais, processamento de linguagem natural e robótica, além de abordar suas aplicações no cotidiano e no mercado de trabalho. O livro também apresenta os desafios, limitações e impactos da inteligência artificial na sociedade.', 1),
(28, 'Cercanía Joven - Volume Único', 'Ludmila Coimbra, Luiza Santana Chaves e Pedro Luis Barcia', 'Edições SM', 2014, 'images/livros/cercania-jovem.png', 'Livros Educacionais e Didáticos', 'Cercanía Joven, de Ludmila Coimbra, Luiza Santana Chaves e Pedro Luis Barcia, é um livro didático de língua espanhola voltado ao Ensino Médio. Em volume único, a obra apresenta conteúdos de língua espanhola por meio de textos, diálogos, músicas, imagens e diferentes gêneros discursivos, abordando também aspectos culturais e sociais dos países de língua espanhola. A proposta integra o desenvolvimento das habilidades de leitura, escrita, compreensão oral e expressão oral ao contato com diferentes manifestações culturais, estimulando a reflexão crítica e a formação cidadã dos estudantes.', 1),
(29, 'Moderna Plus Química – Na abordagem do cotidiano', 'Eduardo Leite do Canto, Laura Celloto Canto Leite e Luiza Celloto Canto', 'Moderna', 2024, 'images/livros/moderna-plus-quimica.png', 'Livros Educacionais e Didáticos', 'Moderna Plus Química – Na Abordagem do Cotidiano, de Eduardo Leite do Canto e Laura Celloto Canto Leite, apresenta os principais conceitos da Química relacionados a situações e fenômenos do dia a dia. A obra aborda temas como matéria, transformações químicas, substâncias, reações, soluções, energia e propriedades dos materiais, relacionando os conteúdos teóricos a exemplos práticos e aplicações cotidianas. Assim, o livro busca facilitar a compreensão da Química e mostrar sua presença em diferentes aspectos da vida e da sociedade.', 1),
(30, 'English Vibes for Brazilian Learners', 'Claudio Franco e Kátia Tavares', 'FTD', 2020, 'images/livros/english-vibes.jpg', 'Livros Educacionais e Didáticos', 'English Vibes for Brazilian Learners, de Claudio Franco e Kátia Tavares, é um livro didático voltado ao ensino e à aprendizagem da língua inglesa por estudantes brasileiros. A obra trabalha vocabulário, gramática, leitura, compreensão oral e produção escrita e oral por meio de textos e situações do cotidiano. Com uma abordagem contextualizada, busca desenvolver a comunicação em inglês e ampliar o contato dos estudantes com diferentes aspectos culturais e sociais relacionados à língua.', 1),
(31, 'Learn and Share in English', 'Amadeu Marques e Ana Carolina Cardoso', 'Ática', 2016, 'images/livros/learn-and-share-english.png', 'Livros Educacionais e Didáticos', 'Learn and Share in English, de Amadeu Marques e Ana Carolina Cardoso, é um livro didático voltado ao aprendizado da língua inglesa, com foco no desenvolvimento das habilidades de leitura, escrita, compreensão oral e comunicação. A obra apresenta textos, atividades e situações contextualizadas que permitem ao estudante ampliar o vocabulário e compreender diferentes aspectos da língua e das culturas de países de língua inglesa, promovendo também a reflexão sobre temas do cotidiano e da sociedade.', 1),
(32, 'Tudo que meu coração grita desde o dia em que você (o) partiu', 'Gabriela Freitas', 'Crivo Editorial', 2020, 'images/livros/tudo-que-meu-coracao-grita.jpg', 'Poesia', '\"Talvez eu nunca consiga entender sinceramente e verdadeiramente os seus motivos. Talvez eu nunca aceite definitivamente a sua escolha de ir. Talvez eu sempre me lembre da gente pensando em tudo o que a gente não foi. Mas não importa. Nada mais do que passou importa. Agora eu olho pra frente. Eu olho pra quem eu me tornei depois de tudo. Depois de você. Depois de nós. E eu olho com orgulho. Orgulho porque eu precisei matar um leão por dia, mas eu consegui. Orgulho porque aquela menina que falava do amor com fé ainda está aqui e ela acredita que final feliz pode sim acontecer. E que, apesar de o nosso ter sido meio triste, valeu a pena apostar na nossa história.\" Tudo que meu coração grita desde o dia em que você (o) partiu fala de finais e de como eles são necessários para a caminhada pessoal. Temáticas como recomeço, reencontros e amor-próprio permeiam e dão o envolvente tom desta obra.', 1),
(33, 'A Cidade dos Ossos', 'Cassandra Clare', 'Galera Record', 2010, 'images/livros/a-cidade-dos-ossos.jpg', 'Fantasia', 'A Cidade dos Ossos, de Cassandra Clare, acompanha Clary Fray, uma adolescente que presencia um assassinato cometido por jovens que ninguém mais parece conseguir enxergar. Ao descobrir que eles são Caçadores de Sombras, guerreiros responsáveis por proteger o mundo dos humanos contra demônios e outras criaturas sobrenaturais, Clary é lançada em uma realidade que desconhecia. Enquanto tenta entender a ligação de sua mãe com esse universo, ela se envolve em uma perigosa batalha e descobre segredos sobre sua própria origem.', 1),
(34, 'A Guerra dos Tronos: As Crônicas de Gelo e Fogo – Livro I', 'George R. R. Martin', 'LeYa', 2014, 'images/livros/as-cronicas-de-gelo-e-fogo.jpg', 'Fantasia', 'A Guerra dos Tronos, de George R. R. Martin, inicia a saga As Crônicas de Gelo e Fogo em um mundo marcado por disputas políticas, alianças e conflitos entre grandes famílias. Quando o rei Robert Baratheon pede a Eddard Stark que assuma uma importante posição na corte, a família Stark se envolve cada vez mais nas intrigas pelo poder. Enquanto diferentes casas lutam pelo Trono de Ferro, ameaças antigas também começam a surgir além das fronteiras dos Sete Reinos. A história reúne batalhas, traições, segredos e disputas familiares em uma narrativa de fantasia marcada por personagens complexos e acontecimentos imprevisíveis.', 1),
(35, 'Harry Potter e a Pedra Filosofal', 'J. K. Rowling', 'Rocco', 2000, 'images/livros/harry-potter-e-a-pedra-filosofal.jpg', 'Fantasia', 'Harry Potter e a Pedra Filosofal, de J. K. Rowling, acompanha Harry Potter, um garoto que descobre, aos onze anos, que é um bruxo e que possui um lugar na Escola de Magia e Bruxaria de Hogwarts. Ao começar seus estudos, ele faz amizade com Rony Weasley e Hermione Granger e conhece um mundo completamente diferente daquele em que cresceu. Entre aulas de magia, descobertas e aventuras, os três amigos percebem que há algo misterioso escondido em Hogwarts e acabam envolvidos em uma perigosa tentativa de proteger a Pedra Filosofal.', 1),
(36, 'O Senhor dos Anéis: A Sociedade do Anel', 'J. R. R. Tolkien', 'Martins Fontes', 2002, 'images/livros/o-senhor-dos-aneis-a-sociedade-do-anel.jpg', 'Fantasia', 'O Senhor dos Anéis: A Sociedade do Anel, de J. R. R. Tolkien, acompanha Frodo Bolseiro, um hobbit que recebe a missão de levar o Um Anel até Mordor para destruí-lo, impedindo que o poderoso objeto volte às mãos de Sauron. Para cumprir essa tarefa, Frodo parte do Condado acompanhado por seus amigos e outros representantes dos povos da Terra-média. Juntos, eles formam a Sociedade do Anel e enfrentam perigos, criaturas e inimigos enquanto percorrem um caminho cada vez mais distante de casa.', 1),
(37, 'O Príncipe Cruel', 'Holly Black', 'Galera Record', 2018, 'images/livros/o-principe-cruel.jpg', 'Fantasia', 'O Príncipe Cruel, de Holly Black, acompanha Jude, uma garota humana que, ainda criança, é levada para o mundo das fadas junto com suas irmãs. Anos depois, vivendo na corte de Elfhame, ela deseja conquistar seu espaço entre seres que desprezam os humanos. Para isso, Jude se envolve nas intrigas da corte e entra em conflito com Cardan, um príncipe cruel e provocador. Enquanto tenta conquistar poder e reconhecimento, ela se vê envolvida em uma trama de alianças, traições e disputas pelo trono que pode colocar sua própria vida em risco.', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `solicitacoes`
--

CREATE TABLE `solicitacoes` (
  `idSolicitacao` int NOT NULL,
  `idLivro` int NOT NULL,
  `idExemplar` int DEFAULT NULL,
  `idUsuario` int NOT NULL,
  `dataSolicitacao` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(30) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Aguardando confirmação',
  `motivoCancelamento` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `solicitacoes`
--

INSERT INTO `solicitacoes` (`idSolicitacao`, `idLivro`, `idExemplar`, `idUsuario`, `dataSolicitacao`, `status`, `motivoCancelamento`) VALUES
(1, 6, 2, 3, '2026-09-16 14:40:02', 'Retirado', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int NOT NULL,
  `nomeUsuario` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `matriculaUsuario` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `dataNascimentoUsuario` date NOT NULL,
  `emailUsuario` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `senhaUsuario` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tipoUsuario` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nomeUsuario`, `matriculaUsuario`, `dataNascimentoUsuario`, `emailUsuario`, `senhaUsuario`, `tipoUsuario`) VALUES
(1, 'Lane', '20241tbor00200137', '2009-08-11', 'estantesolidaria@ifpr.edu.br', '0c5e2f674970840e5675917e980dfc5a', 'Administrador'),
(3, 'Laís', '20241tbot002002', '2009-09-09', 'lais@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Aluno');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `exemplares`
--
ALTER TABLE `exemplares`
  ADD PRIMARY KEY (`idExemplar`),
  ADD KEY `idx_exemplar_livro` (`idLivro`);

--
-- Índices para tabela `livros`
--
ALTER TABLE `livros`
  ADD PRIMARY KEY (`idLivro`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Índices para tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD PRIMARY KEY (`idSolicitacao`),
  ADD KEY `fk_solicitacao_livro` (`idLivro`),
  ADD KEY `fk_solicitacao_usuario` (`idUsuario`),
  ADD KEY `idx_solicitacao_exemplar` (`idExemplar`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `matriculaUsuario` (`matriculaUsuario`),
  ADD UNIQUE KEY `emailUsuario` (`emailUsuario`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `exemplares`
--
ALTER TABLE `exemplares`
  MODIFY `idExemplar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de tabela `livros`
--
ALTER TABLE `livros`
  MODIFY `idLivro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  MODIFY `idSolicitacao` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `exemplares`
--
ALTER TABLE `exemplares`
  ADD CONSTRAINT `fk_exemplar_livro` FOREIGN KEY (`idLivro`) REFERENCES `livros` (`idLivro`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `livros`
--
ALTER TABLE `livros`
  ADD CONSTRAINT `livros_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Limitadores para a tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD CONSTRAINT `fk_solicitacao_exemplar` FOREIGN KEY (`idExemplar`) REFERENCES `exemplares` (`idExemplar`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_solicitacao_livro` FOREIGN KEY (`idLivro`) REFERENCES `livros` (`idLivro`),
  ADD CONSTRAINT `fk_solicitacao_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
