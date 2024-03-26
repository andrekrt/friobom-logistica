<?php

session_start();
require("../../conexao.php");

$idModudulo = 12;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $idUsuario = $_SESSION['idUsuario'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];
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
    <title>Registro de Manutenção</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <div class="container-fluid corpo">
        <?php require('../../menu-lateral02.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../../assets/images/icones/pneu.png" alt="">
                </div>
                <div class="title">
                    <h2>Registro de Manutenção</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-manutencao.php" class="despesas" enctype="multipart/form-data" method="post" id="">
                    <div id="formulario">
                         
                        <div class="form-row">
                            <div class="form-grupo col-md-2 espaco">
                                <label for="pneu">Pneu (Nº Fogo)</label>
                                <select required name="pneu" id="pneu" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sql=$db->query("SELECT * FROM pneus WHERE uso = 1");
                                    $pneus = $sql->fetchAll();
                                    foreach($pneus as $pneu):
                                    ?>
                                    <option value="<?=$pneu['idpneus'] ?>"><?=$pneu['num_fogo']?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="reparo">Tipo de Reparo</label>
                                <input type="text" name="reparo" id="reparo" class="form-control" required>
                            </div>
                            <div class="form-group col-md-2 espaco ">
                                <label for="kmManutencao">Km Manutenção</label>
                                <input type="text" required name="kmManutencao" id="kmManutencao" class="form-control">
                            </div>
                            <div class="form-group col-md-1 espaco ">
                                <label for="valor">Valor (R$)</label>
                                <input type="text" required name="valor" id="valor" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco ">
                                <label for="nf">Nº NF</label>
                                <input type="text" required name="nf" id="nf" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco ">
                                <label for="fornecedor">Fornecedor </label>
                                <input type="text" required name="fornecedor" id="fornecedor" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-1 espaco">
                                <label for="suco01">Suco 01</label>
                                <input type="text" required name="suco01" class="form-control" id="suco01">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="suco02">Suco 02</label>
                                <input type="text" required name="suco02" class="form-control" id="suco02">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="suco03">Suco 03</label>
                                <input type="text" required name="suco03" class="form-control" id="suco03">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="suco04">Suco 04</label>
                                <input type="text" required name="suco04" class="form-control" id="suco04">
                            </div>
                        </div>
                    
                    </div>
                    <div class="form-group">
                        <button type="submit" name="entradas" class="btn btn-primary"> Registrar Manutenção</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/menu.js"></script>
    <script src="../../assets/js/jquery.mask.js"></script>
    <script>
        $(document).ready(function() {
            $('#pneu').select2({
                theme: 'bootstrap4'
            });
            $('#valor').mask('000.000.000.000.000,00', {reverse: true});
        });
    </script>
</body>

</html>