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
    <title>Rodízio de Pneus</title>
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
                    <h2>Registrar Rodízio</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-rodizio.php" class="despesas" enctype="multipart/form-data" method="post" id="">
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
                            <div class="form-group col-md-2 espaco">
                                <label for="kmFinal">Km Final do Veículo Anterior</label>
                                <input type="text" name="kmFinal" id="kmFinal" class="form-control" required>
                            </div>
                            <div class="form-grupo col-md-2 espaco">
                                <label for="novoVeiculo">Novo Veículo</label>
                                <select required name="novoVeiculo" id="novoVeiculo" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sql=$db->query("SELECT * FROM veiculos");
                                    $veiculos = $sql->fetchAll();
                                    foreach($veiculos as $veiculo):
                                    ?>
                                    <option value="<?=$veiculo['placa_veiculo'] ?>"><?=$veiculo['placa_veiculo']?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="kmInicial">Km Atual Novo Veículo</label>
                                <input type="text" required name="kmInicial" class="form-control" id="kmInicial">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="localizacao">Localização Atual</label>
                                <input type="text" required name="localizacao" class="form-control" id="localizacao">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="posicao">Posição Atual</label>
                                <input type="text" required name="posicao" class="form-control" id="posicao">
                            </div>
                        </div>                    
                    </div>
                    <div class="form-group">
                        <button type="submit" name="entradas" class="btn btn-primary"> Realizar Rodízio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/menu.js"></script>
    <script>
        $(document).ready(function() {
            $('#novoVeiculo').select2({
                theme: 'bootstrap4'
            });
            $('#pneu').select2({
                theme: 'bootstrap4'
            });
        });
    </script>
</body>

</html>