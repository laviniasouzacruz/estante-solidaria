<?php include "header.php" ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">

            <div style="
                padding: 30px;
                background-color: #fff;
                border: 5px solid #841717;
                border-radius: 18px;
                box-shadow: 6px 6px 0px #841717;
            ">

                <div class="d-flex justify-content-center mb-4">

                    <h2 style="font-size: 30px;">

                        Cadastrar Livro:

                    </h2>

                </div>

                <form
                    action="actionLivro.php"
                    method="POST"
                    enctype="multipart/form-data"
                    class="was-validated"
                >

                    <div class="form-floating mt-3 mb-3">

                        <input
                            type="file"
                            name="fotoLivro"
                            id="fotoLivro"
                            class="form-control"
                            accept="image/*"
                            required
                        >

                        <label for="fotoLivro">

                            Foto do Livro

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback">

                            Selecione uma foto.

                        </div>

                    </div>

                    <div class="form-floating mt-3 mb-3">

                        <input
                            type="text"
                            name="titulo"
                            id="titulo"
                            placeholder="Título do Livro"
                            class="form-control"
                            required
                        >

                        <label for="titulo">

                            Título do Livro

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback"></div>

                    </div>

                    <div class="form-floating mt-3 mb-3">

                        <input
                            type="text"
                            name="autor"
                            id="autor"
                            placeholder="Autor"
                            class="form-control"
                            required
                        >

                        <label for="autor">

                            Autor

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback"></div>

                    </div>

                    <div class="form-floating mt-3 mb-3">

                        <input
                            type="text"
                            name="editora"
                            id="editora"
                            placeholder="Editora"
                            class="form-control"
                            required
                        >

                        <label for="editora">

                            Editora

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback"></div>

                    </div>

                    <div class="form-floating mt-3 mb-3">

                        <select
                            name="categoria"
                            id="categoria"
                            class="form-select"
                            required
                        >

                            <option value="" selected disabled>

                                Selecione a categoria

                            </option>

                            <option value="Ficção">

                                Ficção

                            </option>

                            <option value="Poesia">

                                Poesia

                            </option>

                            <option value="Romance">

                                Romance

                            </option>

                            <option value="Fantasia">

                                Fantasia

                            </option>

                            <option value="Suspense e Terror">

                                Suspense e Terror

                            </option>

                            <option value="Automação e Mecânica">

                                Automação e Mecânica

                            </option>

                            <option value="Informática e Tecnologia">

                                Informática e Tecnologia

                            </option>

                            <option value="Livros Educacionais e Didáticos">

                                Livros Educacionais e Didáticos

                            </option>

                        </select>

                        <label for="categoria">

                            Categoria

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback"></div>

                    </div>

                    <div class="form-floating mt-3 mb-3">

                        <textarea
                            name="sinopse"
                            id="sinopse"
                            placeholder="Sinopse do Livro"
                            class="form-control"
                            style="height: 150px;"
                            required
                        ></textarea>

                        <label for="sinopse">

                            Sinopse do Livro

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback"></div>

                    </div>

                    <div class="form-floating mt-3 mb-3">

                        <input
                            type="number"
                            name="anoPublicacao"
                            id="anoPublicacao"
                            placeholder="Ano de Publicação"
                            class="form-control"
                            min="1000"
                            max="2026"
                            required
                        >

                        <label for="anoPublicacao">

                            Ano de Publicação

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback"></div>

                    </div>

                    <div class="form-floating mt-3 mb-3">

                        <select
                            name="estado"
                            id="estado"
                            class="form-select"
                            required
                        >

                            <option value="" selected disabled>

                                Selecione o estado

                            </option>

                            <option value="Novo">

                                Novo

                            </option>

                            <option value="Bom">

                                Bom

                            </option>

                            <option value="Regular">

                                Regular

                            </option>

                        </select>

                        <label for="estado">

                            Estado do Livro

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback"></div>

                    </div>

                    <div class="form-floating mt-3 mb-3">

                        <input
                            type="number"
                            name="quantidade"
                            id="quantidade"
                            placeholder="Quantidade de Exemplares"
                            class="form-control"
                            min="1"
                            required
                        >

                        <label for="quantidade">

                            Quantidade de Exemplares

                        </label>

                        <div class="valid-feedback"></div>

                        <div class="invalid-feedback"></div>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-100 my-3"
                    >

                        Cadastrar Livro

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include "footer.php" ?>