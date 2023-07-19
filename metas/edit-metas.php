<?php

session_start();
require("../conexao.php");
include_once "funcao.php";

$idModudulo = 15;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $token = filter_input(INPUT_GET, 'token');
    $sql = $db->prepare("SELECT * FROM metas WHERE token =:token");
    $sql->bindValue(':token', $token);
    $sql->execute();
    $metas = $sql->fetchAll();
   
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
    <title>Cadastro de Meta</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/icone-metas.png" alt="">
                </div>
                <div class="title">
                    <h2>Lançar Meta Alcançada</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-valor.php" class="despesas" enctype="multipart/form-data" method="post" id="">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-grupo col-md-6 espaco">
                                <label for="tipoMeta">Tipo da Meta</label>
                                <input type="text" class="form-control" id="tipoMeta" value="<?=$metas[0]['tipo_meta']?>" readonly>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="mesAno">Mês/Ano</label>
                                <input type="month" class="form-control" name="mesAno" id="mesAno" required value="<?=date("Y-m", strtotime($metas[0]['data_meta'])) ?>" readonly>
                            </div>
                        </div>
                        <div class="form-row" id="datas">
                        
                        <?php
                        foreach($metas as $meta):
                            $diasSemanas = [
                                "Domingo",
                                "Segunda",
                                "Terça",
                                "Quarta",
                                "Quinta",
                                "Sexta",
                                "Sábado"
                            ];
                            $numDiaSemana = date('w', strtotime($meta['data_meta']));

                            $qtd = buscarMeta($metas[0]['tipo_meta'], $meta['data_meta']);

                        ?>
                            <input type="hidden" value="<?=$meta['idmetas']?>" name="id[]">
                            <div class="form-group col-md-2 espaco">
                                <label for="meta">Meta <?=date('d/m/y', strtotime($meta['data_meta'])) . " - ". $diasSemanas[$numDiaSemana] ?> </label>
                                <input type="meta" name="meta[]" id="meta" class="form-control" required value="<?=$meta['valor_meta']?>" readonly>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="valor">Alcançado <?=date('d/m/y', strtotime($meta['data_meta'])) . " - ". $diasSemanas[$numDiaSemana] ?> </label>
                                <input type="valor" name="valor[]" id="valor" class="form-control" required value="<?=$qtd?>">
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                    <?php if($_SESSION['tipoUsuario'] ==99): ?>
                    <div class="form-group">
                        <button type="submit" name="entradas" class="btn btn-primary"> Registrar</button>
                    </div>
                <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    
</body>

</html>