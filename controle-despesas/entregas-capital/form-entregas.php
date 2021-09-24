<?php 

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==10 ){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    
}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../../index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lançar Entregas</title>
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
                        <img src="../../assets/images/icones/despesas.png" alt="">
                    </div>
                    <div class="title">
                        <h2> Lançar Entrega </h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-entregas.php" class="despesas" method="post">
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="carga">Carga</label>
                                <input type="text" required name="carga" class="form-control" id="carga">
                            </div>
                            <div class="form-group col-md-1 espaco">
                                <label for="sequencia">Sequência</label>
                                <input type="text" required name="sequencia" class="form-control" id="sequencia">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="dataEntrega">Data Entrega</label>
                                <input type="date" required name="dataEntrega" class="form-control" id="dataEntrega">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="motorista">Motorista</label>
                                <select name="motorista" required id="motorista" class="form-control">
                                    <option value=""></option>
                                    <?php $sql = $db->query("SELECT * FROM motoristas ORDER BY nome_motorista ASC");
                                    $motoristas=$sql->fetchAll();
                                    foreach($motoristas as $motorista):
                                    ?>
                                    <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="veiculo">Veículo</label>
                                <select name="veiculo" required id="veiculo" class="form-control">
                                    <option value=""></option>
                                    <?php $sql = $db->query("SELECT * FROM veiculos ORDER BY placa_veiculo ASC");
                                    $veiculos=$sql->fetchAll();
                                    foreach($veiculos as $veiculo):
                                    ?>
                                    <option value="<?=$veiculo['cod_interno_veiculo']?>"><?=$veiculo['placa_veiculo']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="veiculoDefeito">Veículo C/ Defeito</label>
                                <select name="veiculoDefeito" required required id="veiculoDefeito" class="form-control">
                                    <option value=""></option>
                                    <option value="SIM">SIM</option>
                                    <option value="NÃO">NÃO</option>
                                </select>
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="nEntregas">Nº Entregas</label>
                                <input type="text" name="nEntregas" required id="nEntregas" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="nEntregue">Entregas Realizada</label>
                                <input type="text" name="nEntregue" required id="nEntregue" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="hrSaida">Hora Saída</label>
                                <input type="time" name="hrSaida" required id="hrSaida" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="hrChegada">Hora Chegada</label>
                                <input type="time" name="hrChegada" required id="hrChegada" class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="kmSaida">Km Saída</label>
                                <input type="text" name="kmSaida" id="kmSaida" required class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="kmChegada">Km Chegada</label>
                                <input type="text" name="kmChegada" id="kmChegada" required class="form-control">
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="ltAbastec">Litros Abastec.</label>
                                <input type="text" name="ltAbastec" id="ltAbastec" required class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vlAbastec">Valor Abastec.</label>
                                <input type="text" name="vlAbastec"  id="vlAbastec" required class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vlDiariaMot">Diária Motorista (R$)</label>
                                <input type="text" name="vlDiariaMot" id="vlDiariaMot" required class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vlDiariaAux">Diária Auxiliar (R$)</label>
                                <input type="text" name="vlDiariaAux" id="vlDiariaAux" required class="form-control">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="vlOutrosGastos">Outros Gastos (R$)</label>
                                <input type="text" name="vlOutrosGastos" required id="vlOutrosGastos" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"> Lançar </button>
                    </form>
                </div>
            </div>
        </div>

        <script src="../../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/js/menu.js"></script>
        <script src="../../assets/js/jquery.mask.js"></script>
        <script>
            $(document).ready(function() {
                $('#motorista').select2();
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#veiculo').select2();
            });
            $('#vlAbastec').mask('#.##0,00', {reverse: true});
            $('#ltAbastec').mask('#.##0,00', {reverse: true});
            $('#vlDiariaMot').mask('#.##0,00', {reverse: true});
            $('#vlDiariaAux').mask('#.##0,00', {reverse: true});
            $('#vlOutrosGastos').mask('#.##0,00', {reverse: true});
            $('#consumo').mask('#.##0,00', {reverse: true});
        </script>
        
    </body>
</html>