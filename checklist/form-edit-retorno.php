<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && ($_SESSION['tipoUsuario'] == 2 || $_SESSION['tipoUsuario']==99)){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $idCheck = filter_input(INPUT_GET, 'id');
    $sql = $db->prepare("SELECT * FROM checklist LEFT JOIN veiculos ON checklist.veiculo = veiculos.cod_interno_veiculo LEFT JOIN rotas ON checklist.rota = rotas.cod_rota LEFT JOIN motoristas ON checklist.motorista = motoristas.cod_interno_motorista WHERE idchecklist = :id");
    $sql->bindValue(":id", $idCheck);
    $sql->execute();
    $check = $sql->fetch();
    
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
        <title>Lançar Nova Despesa</title>
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
                        <img src="../assets/images/icones/icone-checklist.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Check-List de Retorno</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="atualiza-retorno.php" class="despesas" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?=$idCheck?>">
                        <div class="form-row"> 
                            <div class="form-group col-md-2 espaco">
                                <label for="veiculo">Veículo</label>
                                <input type="text" name="veiculo" id="veiculo" class="form-control" value="<?=$check['placa_veiculo']?>" readonly>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="saida">Data Saída</label>
                                <input type="date" readonly value="<?=$check['saida']?>" name="saida" class="form-control" id="saida">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-1 espaco">
                                <label for="qtdNf">Qtd NF's</label>
                                <input type="text" required name="qtdNf" id="qtdNf" class="form-control" value="<?=$check['qtdnf']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vlCarga">Valor da Carga(R$)</label>
                                <input type="text" value="<?=$check['vl_carga']?>" required name="vlCarga" class="form-control"  id="vlCarga">
                               
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="kmSaida">Km Saída</label>
                                <input type="text" value="<?=$check['km_saida']?>" required name="kmSaida" class="form-control"  id="kmSaida">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="hrSaida">Hora Saída</label>
                                <input type="time" value="<?=$check['hora_saida']?>" required name="hrSaida" class="form-control"  id="hrSaida">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="prevChegada">Previsão de Chegada</label>
                                <input type="date" value="<?=$check['previsao_chegada']?>" required name="prevChegada" class="form-control"  id="prevChegada">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="horimetro">Horímetro</label>
                                <input type="text" value="<?=$check['horimetro']?>" name="horimetro" class="form-control"  id="horimetro">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="rota">Rota</label>
                                <select name="rota" id="rota" class="form-control" required>
                                    <option value="<?=$check['rota']?>"><?=$check['nome_rota']?></option>
                                    <?php
                                    $sql = $db->query("SELECT * FROM rotas ORDER BY nome_rota ASC");
                                    if ($sql->rowCount() > 0) {
                                        $rotas = $sql->fetchAll();
                                        foreach ($rotas as $rota) {
                                            echo "<option value='$rota[cod_rota]'>" . $rota['nome_rota'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="peso">Peso da Carga</label>
                                <input type="text" value="<?=$check['peso_carga']?>" required name="peso" class="form-control"  id="peso">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="carregamento">Nº Carregamento</label>
                                <input type="text" value="<?=$check['carregamento']?>" required name="carregamento" class="form-control"  id="carregamento">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="motorista">Motorista</label>
                                <select name="motorista" id="motorista" class="form-control" required>
                                    <option value="<?=$check['motorista']?>"><?=$check['nome_motorista']?></option>
                                    <?php
                                    $sql = $db->query("SELECT * FROM motoristas ORDER BY nome_motorista ASC");
                                    if ($sql->rowCount() > 0) {
                                        $motoristas = $sql->fetchAll();
                                        foreach ($motoristas as $motorista) {
                                            echo "<option value='$motorista[cod_interno_motorista]'>" . $motorista['nome_motorista'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>                               
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="ajudante">Ajudante</label>
                                <input type="text" value="<?=$check['ajudante']?>" required name="ajudante" class="form-control"  id="ajudante">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="dataChegada">Data Da Chegada</label>
                                <input type="date" value="<?=$check['chegada']?>" required name="dataChegada" class="form-control"  id="dataChegada">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="kmRota">Km Rota</label>
                                <input type="text" value="<?=$check['km_rota']?>" required name="kmRota" class="form-control"  id="kmRota">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="ltAbastecido">Litros Abastecido</label>
                                <input type="text" value="<?=$check['litros_abastecido']?>" required name="ltAbastecido" class="form-control"  id="ltAbastecido">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vlAbastecido">Valor Abastecido (R$)</label>
                                <input type="text" value="<?=$check['valor_abastecido']?>" required name="vlAbastecido" class="form-control"  id="vlAbastecido">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="fotos" class="form-label ">Fotos da Chegada do Veículo</label>
                                <input class="form-control" type="file" id="fotos" name="fotos[]" multiple>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12 espaco">
                                <label for="obs">Obs. da Rota</label>
                                <textarea name="obs" id="obs" class="form-control" rows="5"><?=$check['obs']?></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"> Atualizar </button>
                    </form>
                </div>
            </div>
        </div>
 
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
             jQuery(function($){
                $("#vlCarga").mask('###0,00', {reverse: true});
                $("#peso").mask('###0,00', {reverse: true});
                $("#ltAbastecido").mask('###0,00', {reverse: true});
                $("#vlAbastecido").mask('###0,00', {reverse: true});
            });

            $(document).ready(function() {
                $('#motorista').select2();
                $('#rota').select2();
            });
        </script>
    </body>
</html>