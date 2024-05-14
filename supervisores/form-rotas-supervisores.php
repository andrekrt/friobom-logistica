<?php 

session_start();
require("../conexao.php");

$idModudulo = 14;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    
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
        <title>Cadastro de Rota</title>
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
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
                        <h2>Rota Diária</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-rota-supervisor.php" class="despesas" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="dataSaida">Data e Hora de Saída</label>
                                <input type="datetime-local" class="form-control" required name="dataSaida" id="dataSaida">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="dataChegada">Data e Hora de Chegada</label>
                                <input type="datetime-local" class="form-control" required name="dataChegada" id="dataChegada">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="supervisor">Supervisor</label>
                                <select name="supervisor" required id="supervisor" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $supervisores = $db->query("SELECT * FROM supervisores WHERE filial = $filial ORDER BY nome_supervisor  ASC");
                                    $supervisores=$supervisores->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($supervisores as $supervisor):
                                    ?>
                                    <option value="<?=$supervisor['idsupervisor']?>"><?=$supervisor['nome_supervisor']?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="velMax">Velocidade Máxima</label>
                                <input type="text" class="form-control" required name="velMax" id="velMax" >
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="visitas">Nº de Visitas</label>
                                <input type="text" id="visitas" name="visitas" class="form-control" required>
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="rca1"> RCA 1 </label>
                                <input type="text" class="form-control"  name="rca1" id="rca1" >
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="kmRodado"> Km Rodado </label>
                                <input type="text" class="form-control"  name="kmRodado" id="kmRodado" >
                            </div>
                        </div>
                        <div class="form-row">
                            
                            <div class="form-group col-md-2 espaco">
                                <label for="rca2"> RCA 2 </label>
                                <input type="text" class="form-control"  name="rca2" id="rca2">
                            </div>
                            <div class="form-group col-md-10 espaco">
                                <label for="cidades"> Cidades Visitadas </label>
                                <input type="text" class="form-control"  name="cidades" id="cidades">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="horaAlmoco">Horas de Almoço</label>
                                <input type="text" name="horaAlmoco" id="horaAlmoco" class="form-control">
                            </div>
                            <div class="form-group col-md-10 espaco">
                                <label for="obs">Obs.</label>
                                <input type="text" class="form-control" required name="obs" id="obs" >
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
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
            $(document).ready(function(){
                $('#residencia').select2({
                    theme: 'bootstrap4'
                });
                $('#cidadeChegada').select2({
                    theme: 'bootstrap4'
                });
                $('#cidadeSaida').select2({
                    theme: 'bootstrap4'
                });
                $('#supervisor').select2({
                    theme: 'bootstrap4'
                });
                $('#veiculo').select2({
                    theme: 'bootstrap4'
                });
                $('#diarias').mask("#.##0,0", {reverse: true});
                $('#rca1').mask("#", {reverse: true});
                $('#velMax').mask("#", {reverse: true});
                $('#rca2').mask("#", {reverse: true});
            });
        </script>
    </body>
</html> 