<?php 

session_start();
require("../conexao.php");

$idModudulo = 16;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $id = filter_input(INPUT_GET, 'id');
    
}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FRIOBOM - LOGÍSTICA</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <!-- cdns para SELECT2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
       
    </head>
    <body>
        <div class="container-fluid corpo">
            <?php require('../menu-lateral.php') ?>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../assets/images/icones/rotas.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Saída Fusion</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-fusion.php" class="despesas" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="saida">Data de Saída</label>
                                <input type="date" class="form-control" required name="saida" id="saida">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="carregamento">Carregamento</label>
                                <input type="text" class="form-control" required name="carregamento" id="carregamento">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="veiculo">Veículo</label>
                                <select name="veiculo" id="veiculo" class="form-control" required>
                                    <option value=""></option>
                                    <?php
                                    $sqlVeiculos = $db->query("SELECT cod_interno_veiculo, placa_veiculo FROM veiculos WHERE ativo = 1 AND filial=$filial");
                                    $veiculos=$sqlVeiculos->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($veiculos as $veiculo):
                                    ?>
                                    <option value="<?=$veiculo['cod_interno_veiculo']?>"><?=$veiculo['placa_veiculo']?></option>
                                    <?php  endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="rota">Rota</label>
                                <select name="rota" required id="rota" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sqlRotas = $db->query("SELECT cod_rota, nome_rota FROM rotas WHERE filia=$filial ORDER BY nome_rota ASC");
                                    $rotas=$sqlRotas->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($rotas as $rota):
                                    ?>
                                    <option value="<?=$rota['cod_rota']?>"><?=$rota['nome_rota']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="motorista">Motorista</label>
                                <select name="motorista" required id="motorista" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sqlMotoristas = $db->query("SELECT cod_interno_motorista, nome_motorista FROM motoristas WHERE ativo=1 AND filial=$filial ORDER BY nome_motorista ASC");
                                    $motoristas=$sqlMotoristas->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($motoristas as $motorista):
                                    ?>
                                    <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="entregas"> Nº Entregas </label>
                                <input type="text" class="form-control"  name="entregas" id="entregas" >
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"> Registrar </button>
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
            $(document).ready(function(){
                $('#rota').select2();
                $('#motorista').select2();
                $('#veiculo').select2();
            });
        </script>
    </body>
</html> 