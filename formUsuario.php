<?php include "header.php" ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <h2 class="text-center mb-4">Cadastre-se</h2>

            <form action="actionUsuario.php" method="POST" enctype="multipart/form-data">

                <div class="form-group py-2">
                    <label class="mb-2" for="nomeUsuario">Nome Completo:</label>
                    <input type="text" name="nomeUsuario" id="nomeUsuario"
                        placeholder="Digite seu nome completo"
                        class="form-control w-100 rounded-3 p-3" required>
                </div>

                <div class="form-group py-2">
                    <label class="mb-2" for="matriculaUsuario">Número de Matrícula:</label>
                    <input type="text" name="nomeUsuario" id="nomeUsuario"
                        placeholder="Digite seu número de matrícula"
                        class="form-control w-100 rounded-3 p-3" required>
                </div>

                <div class="form-group py-2">
                    <label class="mb-2" for="dataNascimentoUsuario">Data de Nascimento:</label>
                    <input type="date" name="dataNascimentoUsuario" id="dataNascimentoUsuario"
                        class="form-control w-100 rounded-3 p-3" required>
                </div>

                <div class="form-group py-2">
                    <label class="mb-2" for="emailUsuario">E-mail:</label>
                    <input type="email" name="emailUsuario" id="emailUsuario"
                        placeholder="Digite seu e-mail"
                        class="form-control w-100 rounded-3 p-3" required>
                </div>

                <div class="form-group py-2">
                    <label class="mb-2" for="senhaUsuario">Senha:</label>
                    <input type="password" name="senhaUsuario" id="senhaUsuario"
                        placeholder="Crie uma senha"
                        class="form-control w-100 rounded-3 p-3"
                        minlength="3" maxlength="8" required>
                </div>

                <div class="form-group py-2">
                    <label class="mb-2" for="confirmarSenhaUsuario">Confirmar Senha:</label>
                    <input type="password" name="confirmarSenhaUsuario" id="confirmarSenhaUsuario"
                        placeholder="Confirme sua senha"
                        class="form-control w-100 rounded-3 p-3"
                        minlength="3" maxlength="8" required>
                </div>

                <div class="py-3">
                    <label>
                        <input type="checkbox" required class="d-inline">
                        <span class="label-body">
                            Concordo com a <a href="#" class="fw-bold">Política de Privacidade</a>
                        </span>
                    </label>
                </div>

                <button type="submit" class="btn btn-dark w-100 my-3">
                    Cadastrar
                </button>

            </form>

        </div>
    </div>
</div>

<?php include "footer.php" ?>