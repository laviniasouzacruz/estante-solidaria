<?php include "header.php" ?>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6 col-md-8">


            <div class="d-flex justify-content-center mb-4">

                <h2 style="font-size: 30px;">
                    Cadastre-se:
                </h2>

            </div>


            <form action="actionUsuario.php" method="POST" class="was-validated">


                <div class="form-floating mt-3 mb-3">

                    <input 
                    type="text" 
                    name="nomeUsuario" 
                    id="nomeUsuario"
                    placeholder="Nome Completo"
                    class="form-control"
                    required>

                    <label for="nomeUsuario">
                        Nome Completo
                    </label>

                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>

                </div>



                <div class="form-floating mt-3 mb-3">

                    <input 
                    type="text" 
                    name="matriculaUsuario" 
                    id="matriculaUsuario"
                    placeholder="Número de Matrícula"
                    class="form-control"
                    required>

                    <label for="matriculaUsuario">
                        Número de Matrícula
                    </label>

                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>

                </div>



                <div class="form-floating mt-3 mb-3">

                    <input 
                    type="date" 
                    name="dataNascimentoUsuario" 
                    id="dataNascimentoUsuario"
                    class="form-control"
                    required>

                    <label for="dataNascimentoUsuario">
                        Data de Nascimento
                    </label>

                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>

                </div>



                <div class="form-floating mt-3 mb-3">

                    <input 
                    type="email" 
                    name="emailUsuario" 
                    id="emailUsuario"
                    placeholder="Email"
                    class="form-control"
                    required>

                    <label for="emailUsuario">
                        Email
                    </label>

                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>

                </div>



                <div class="form-floating mt-3 mb-3">

                    <input 
                    type="password" 
                    name="senhaUsuario" 
                    id="senhaUsuario"
                    placeholder="Senha"
                    class="form-control"
                    minlength="3"
                    maxlength="8"
                    required>

                    <label for="senhaUsuario">
                        Senha
                    </label>

                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>

                </div>



                <div class="form-floating mt-3 mb-3">

                    <input 
                    type="password" 
                    name="confirmarSenhaUsuario" 
                    id="confirmarSenhaUsuario"
                    placeholder="Confirmar Senha"
                    class="form-control"
                    minlength="3"
                    maxlength="8"
                    required>

                    <label for="confirmarSenhaUsuario">
                        Confirmar Senha
                    </label>

                    <div class="valid-feedback"></div>
                    <div class="invalid-feedback"></div>

                </div>




                <div class="py-3">

                    <label>

                        <input 
                        type="checkbox" 
                        required 
                        class="d-inline">

                        <span class="label-body">

                            Concordo com a 
                            <a href="#" class="fw-bold">
                                Política de Privacidade
                            </a>

                        </span>

                    </label>

                </div>




                <button 
                type="submit" 
                class="btn btn-primary w-100 my-3">

                    Cadastrar

                </button>



            </form>



            <div class="text-center mt-3">

                <p>
                    Já possui uma conta?
                    <a href="formLogin.php" class="fw-bold">
                        Faça login!
                    </a>
                </p>

            </div>


        </div>

    </div>

</div>


<?php include "footer.php" ?>