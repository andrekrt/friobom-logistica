<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $idDespesa = filter_input(INPUT_GET, 'id');

    $sql = $db->prepare("SELECT * FROM viagem WHERE iddespesas = :idDespesa");
    $sql->bindValue(":idDespesa", $idDespesa);
    $sql->execute();
    $despesa = $sql->fetch();
    
    
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
        <title>Editar Despesa</title>
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
                        <img src="../assets/images/icones/despesas.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Lançar Nova Despesa</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="atualiza-despesa.php" class="despesas" method="post">
                        <input type="hidden" value="<?=$idDespesa?>" name="idDespesa">
                        <div class="form-row"> 
                            <div class="form-group col-md-2 espaco">
                                <label for="codVeiculo">Código do Veículo</label>
                                <input type="text" required name="codVeiculo" class="form-control" id="codVeiculo" value="<?=$despesa['cod_interno_veiculo']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="tipoVeiculo">Tipo do Veículo</label>
                                <input type="text" required name="tipoVeiculo" class="form-control" id="tipoVeiculo" value="<?=$despesa['tipo_veiculo']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="placaVeiculo">Placa do Veículo</label>
                                <input type="text" required name="placaVeiculo" class="form-control" id="placaVeiculo" value="<?=$despesa['placa_veiculo']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="codMotorista">Código do Motorista</label>
                                <input type="text" required name="codMotorista" class="form-control" id="codMotorista" value="<?=$despesa['cod_interno_motorista']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="nomeMotorista">Nome do Motorista</label>
                                <input type="text" required name="motorista" class="form-control" id="nomeMotorista" value="<?=$despesa['nome_motorista']?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="nCarregamento">Nº Carregamento</label>
                                <input type="text" required name="nCarregamento" class="form-control" id="nCarregamento" value="<?=$despesa['num_carregemento']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataCarregamento">Data do Carregamento</label>
                                <input type="datetime-local" required name="dataCarregamento" class="form-control" id="dataCarregamento" value="<?=date("Y-m-d\TH:i", strtotime($despesa['data_carregamento']))?>">
                            </div>
                            
                            <div class="form-group col-md-3 espaco">
                                <label for="dataSaida">Data e hora de Saída</label>
                                <input type="datetime-local" required name="dataSaida" class="form-control" id="dataSaida" value="<?=date("Y-m-d\TH:i", strtotime($despesa['data_saida']))?>" >
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataChegada">Data e Hora de Chegada</label>
                                <input type="datetime-local" required name="dataChegada" class="form-control" id="dataChegada" value="<?=date("Y-m-d\TH:i", strtotime($despesa['data_chegada']))?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="codRota">Código da Rota</label>
                                <input type="text" required name="codRota" class="form-control" id="codRota" value="<?=$despesa['cod_rota']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="rota">Rota</label>
                                <input type="text" required name="rota" class="form-control" id="rota" value="<?=$despesa['nome_rota']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vlTransp">Valor Transportado (R$)</label>
                                <input type="text" required name="vlTransp" class="form-control" id="vlTransp" value="<?=$despesa['valor_transportado']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vlDev">Valor Devolvido (R$)</label>
                                <input type="text" required name="vlDev" class="form-control" id="vlDev" value="<?=$despesa['valor_devolvido']?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="qtdEntrega">Qtde de Entregas</label>
                                <input type="text" required name="qtdEntrega" class="form-control" id="qtdEntrega" value="<?=$despesa['qtd_entregas']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="nCarga">Nº da Carga</label>
                                <input type="text" required name="nCarga" class="form-control" id="nCarga" value="<?=$despesa['num_carga']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="pesoCarga">Peso da Carga</label>
                                <input type="text" required name="pesoCarga" class="form-control" id="pesoCarga" value="<?=$despesa['peso_carga']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="kmSaida">KM de Saída</label>
                                <input type="text" required name="kmSaida" class="form-control" id="kmSaida" value="<?=$despesa['km_saida']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrTkSaida">HR TK Saída</label>
                                <input type="text" name="hrTkSaida" class="form-control" id="hrTkSaida" value="<?=$despesa['hr_tk_saida']?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="km1Abast">KM 1º Abastescimento Externo</label>
                                <input type="text" required name="km1Abast" class="form-control" id="km1Abast" value="<?=$despesa['km_abast1']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrKm1Abast">HR TK 1º Abastescimento Externo</label>
                                <input type="text" name="hrKm1Abast" class="form-control" id="hrKm1Abast" value="<?=$despesa['hr_tk_abast1']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="lt1Abast">Litros 1º Abastescimento Externo</label>
                                <input type="text" required name="lt1Abast" class="form-control" id="lt1Abast" value="<?=$despesa['lt_abast1']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vl1Abast">Valor 1º Abastescimento Externo</label>
                                <input type="text" name="vl1Abast" class="form-control" id="vl1Abast" value="<?=$despesa['valor_abast1']?>">
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco">
                                <label for="local1Abast">Posto / Cidade 1º Abastecimento Externo</label>
                                <input type="text" name="local1Abast" class="form-control" id="local1Abast" value="<?=$despesa['localAbast1']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="km2Abast">KM 2º Abastescimento</label>
                                <input type="text" name="km2Abast" class="form-control" id="km2Abast" value="<?=$despesa['km_abast2']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrKm2Abast">HR TK 2º Abastescimento Externo</label>
                                <input type="text" name="hrKm2Abast" class="form-control" id="hrKm2Abast" value="<?=$despesa['hr_tk_abast2']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="lt2Abast">Litros 2º Abastescimento Externo</label>
                                <input type="text" name="lt2Abast" class="form-control" id="lt2Abast" value="<?=$despesa['lt_abast2']?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="vl2Abast">Valor 2º Abastescimento Externo</label>
                                <input type="text" name="vl2Abast" class="form-control" id="vl2Abast" value="<?=$despesa['valor_abast2']?>">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="local2Abast">Posto / Cidade 2º Abastecimento Externo</label>
                                <input type="text" name="local2Abast" class="form-control" id="local2Abast" value="<?=$despesa['localAbast2']?>">
                            </div>
                            <div class="form-group col-md-5 espaco">
                                <label for="km3Abast">KM 3º Abastescimento Externo</label>
                                <input type="text" name="km3Abast" class="form-control" id="km3Abast" value="<?=$despesa['km_abast3']?>"> 
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrKm3Abast">HR TK 3º Abastescimento Externo</label>
                                <input type="text" name="hrKm3Abast" class="form-control" id="hrKm3Abast" value="<?=$despesa['hr_tk_abast3']?>">
                            </div>
                            <div class="form-group col-md-5 espaco">
                                <label for="lt3Abast">Litros 3º Abastescimento Externo</label>
                                <input type="text" name="lt3Abast" class="form-control" id="lt3Abast" value="<?=$despesa['lt_abast3']?>">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="vl3Abast">Valor 3º Abastescimento Externo</label>
                                <input type="text" name="vl3Abast" class="form-control" id="vl3Abast" value="<?=$despesa['valor_abast3']?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco">
                                <label for="local3Abast">Posto / Cidade 3º Abastecimento Externo</label>
                                <input type="text" name="local3Abast" class="form-control" id="local3Abast" value="<?=$despesa['localAbast3']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="km4Abast">KM Abastescimento Interno</label>
                                <input type="text" name="km4Abast" class="form-control" id="km4Abast" value="<?=$despesa['km_abast4']?>">
                            </div>
                            <div class="form-group col-md-5 espaco">
                                <label for="hrKm4Abast">HR TK Abastescimento  Interno</label>
                                <input type="text" name="hrKm4Abast" class="form-control" id="hrKm4Abast" value="<?=$despesa['hr_tk_abast4']?>">
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="lt4Abast">Litros Abastescimento  Interno</label>
                                <input type="text" name="lt4Abast" class="form-control" id="lt4Abast" value="<?=$despesa['lt_abast4']?>">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vl4Abast">Valor Abastescimento Interno</label>
                                <input type="text" name="vl4Abast" class="form-control" id="vl4Abast" value="<?=$despesa['valor_abast4']?>">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="local4Abast">Posto / Cidade Abastecimento Interno</label>
                                <input type="text" required name="local4Abast" class="form-control" id="local4Abast" value="<?=$despesa['localAbast4']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasMot">Valor Diária Motorista</label>
                                <input type="text" required name="diariasMot" class="form-control" id="diariasMot" value="<?=$despesa['diarias_motoristas']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRota">Dias em Rota Motorista</label>
                                <input type="text" required name="diasRota" class="form-control" id="diasRota" value="<?=$despesa['dias_motorista']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasAjud">Valor Diária Ajudante </label>
                                <input type="text" required name="diariasAjud" class="form-control" id="diariasAjud" value="<?=$despesa['diarias_ajudante']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRotaAjud">Dias em Rota Ajudante</label>
                                <input type="text" required name="diasRotaAjud" class="form-control" id="diasRotaAjud" value="<?=$despesa['dias_ajudante']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasChapa">Valor Diária Chapa</label>
                                <input type="text" required name="diariasChapa" class="form-control" id="diariasChapa" value="<?=$despesa['diarias_chapa']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRotaChapa">Dias em Rota Chapa</label>
                                <input type="text" required name="diasRotaChapa" class="form-control" id="diasRotaChapa" value="<?=$despesa['dias_chapa']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="gastosAjud">Outros Gastos</label>
                                <input type="text" name="gastosAjud" class="form-control" id="gastosAjud" value="<?=$despesa['outros_gastos_ajudante']?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="tomada">Tomada</label>
                                <input type="text"  name="tomada" class="form-control" id="tomada" value="<?=$despesa['tomada']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="descarga">Descarga</label>
                                <input type="text" name="descarga" class="form-control" id="descarga" value="<?=$despesa['descarga']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="travessia">Travessia</label>
                                <input type="text" name="travessia" class="form-control" id="travessia" value="<?=$despesa['travessia']?>">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="servicos">Serviços</label>
                                <input type="text" name="servicos" class="form-control" id="servicos" value="<?=$despesa['outros_servicos']?>">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="nomeAjud">Nome Ajudante</label>
                                <input type="text" name="nomeAjud" class="form-control" id="nomeAjud" value="<?=$despesa['nome_ajudante']?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"> Atualizar </button>
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="personalizado.js"></script>
        <script type="text/javascript" src="motoristas.js"></script>
        <script type="text/javascript" src="rotas.js"></script>
    </body>
</html>