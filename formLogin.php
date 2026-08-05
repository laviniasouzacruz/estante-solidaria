<?php include "header.php" ?>

<?php
    if(isset($_GET['erroLogin'])){
        $erroLogin = $_GET['erroLogin'];

        if($erroLogin == 'dadosInvalidos'){
            echo "<div class='alert alert-warning text-center'>
                    EMAIL ou SENHA inválidos!
                  </div>";
        }
    }
?>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5 col-md-7">


            <div class="d-flex justify-content-center mb-4">

                <h2 style="font-size: 30px;">
                    Acessar o sistema:
                </h2>

            </div>



            <div class="d-flex justify-content-center">

                <form action="actionLogin.php" method="POST" class="was-validated w-100">


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



                    <div class="d-flex justify-content-center mt-4">

                        <button 
                        type="submit" 
                        class="btn btn-primary">

                            Login

                        </button>

                    </div>


                </form>

            </div>



            <div class="d-flex justify-content-center mt-4">

                <p>
                    Ainda não é cadastrado?
                    <a href="formUsuario.php" title="Cadastrar-se">
                        Clique aqui!
                    </a>
                    <i class="bi bi-emoji-smile"></i>
                </p>

            </div>


        </div>

    </div>

</div>


<?php include "footer.php" ?>