<?php

session_start();
require("conexao.php");
$erro= "";

if(isset($_POST['cpf']) && isset($_POST['senha'])){
    $cpf = filter_input(INPUT_POST, 'cpf');
    $senha = md5(filter_input(INPUT_POST, 'senha'));

    $sql = $db->prepare("SELECT * FROM usuarios WHERE (cpf = :cpf OR email = :cpf) AND senha = :senha");
    $sql->bindValue(':cpf', $cpf);
    $sql->bindValue(':senha', $senha);
    $sql->execute();
  
    if($sql->rowCount()>0){
        $dado = $sql->fetch();

        $_SESSION['idUsuario'] = $dado['idusuarios'];
        header("Location:index.php");

    }else{
        $erro = "<div class='alert alert-danger' role='alert'>
                    CPF ou  Senha Incorreta!
                </div>"; 
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Friobom - Logística</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <div class="container-fluid login">
            <div class="area-title-login">
                <img src="assets/images/logo.png" alt="">
            </div>
            <div class="area-login">
                <div class="cabecalho">
                    <img src="assets/images/login.png" class="img-fluid" alt="">
                    <p>Entre com seu Login e Senha</p>
                </div>
                <form action="" method="post">
                    <div class="form-grupo espaco">
                        <label for="cpf">CPF ou E-MAIL:</label>
                        <input type="text" required name="cpf" class="form-control" id="cpf">
                    </div>
                    <div class="form-grupo espaco">
                        <label for="senha">SENHA:</label>
                        <input type="password" required name="senha" class="form-control" id="senha">
                    </div>
                    <button type="submit" class="btn btn-primary"> Entrar</button> <br><br>
                    <p class="erro"> <?php  echo $erro;  ?> </p> </p>
                </form> <br> <br>
            </div>
        </div>  
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/menu.js"></script>
        <script src="assets/js/jquery.mask.js"></script>
        <script>
           /* $(document).ready(function(){
                $('#cpf').mask('999.999.999-99');
            });*/
        </script>
    </body>
</html>