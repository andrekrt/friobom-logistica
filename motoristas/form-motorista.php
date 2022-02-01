<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Motorista</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/motoristas.png" alt="">
                </div>
                <div class="title">
                    <h2>Cadastrar Novo Motorista</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-motorista.php" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-12 espaco">
                            <label for="codMotorista"> Código do Motorista </label>
                            <input type="text" required name="codMotorista" class="form-control" id="codMotorista">
                        </div>
                        <div class="form-group col-md-12 espaco">
                            <label for="nomeMotorista"> Nome do Motorista </label>
                            <input type="text" required name="nomeMotorista" class="form-control" id="nomeMotorista">
                        </div>
                        <div class="form-group col-md-12 espaco">
                            <label for="salario"> Salário</label>
                            <input type="text" required name="salario" class="form-control" id="salario">
                        </div>
                        <div class="form-group col-md-12 espaco">
                            <label for="cnh"> CNH </label>
                            <input type="text" name="cnh" class="form-control" id="cnh">
                        </div>
                        <div class="form-group col-md-12 espaco">
                            <label for="validadeCNH"> Validade CNH </label>
                            <input type="date" name="validadeCNH" class="form-control" id="validadeCNH">
                        </div>
                        <div class="form-group col-md-12 espaco">
                            <label for="situacaoToxicologico"> Exame Toxicológico </label>
                            <select name="situacaoToxicologico" required id="situacaoToxicologico" class="form-control">
                                <option value="Aguardando">Aguardando</option>
                                <option value="OK">OK</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 espaco">
                            <label for="validadeToxicologico"> Validade Toxicológico </label>
                            <input type="date" name="validadeToxicologico" required class="form-control" id="validadeToxicologico">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"> Cadastrar </button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script src="../assets/js/jquery.mask.js"></script>
<script >
    $(document).ready(function(){
        $('#salario').mask("###0,00", {reverse: true});
    });
</script>
</body>

</html>