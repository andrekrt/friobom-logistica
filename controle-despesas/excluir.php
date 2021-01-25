<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] ==99){
    $idDespesa = filter_input(INPUT_GET, 'id');
    
    $sql = $db->query("DELETE FROM viagem WHERE iddespesas = '$idDespesa' ");

    if($sql){
        echo "<script> alert('Deletado com Sucesso!')</script>";
        echo "<script> window.location.href='despesas.php' </script>";
    }
}else{
    header("Location:../index.php");
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lançar Despesas</title>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

    </head>
    <body>
        <div class="container-fluid">
            <div class="cabecalho">
                <a href="despesas.php"><img src="../assets/images/logo.png" alt="Logo Friobom"></a> 
                <div class="titulo">Basto Mesquita Dist e Logistica</div>
            </div>
            <div class="lancamento-despesas">
                <form action="atualiza-despesas.php" method="post">
                    <input type="hidden" name="idDespesa" value="<?php echo $dado['iddespesas'] ?>">
                    <div class="form-row"> 
                        <div class="form-group col-md-2 espaco">
                            <label for="codVeiculo">Código do Veículo</label>
                            <input type="text" value="<?php echo $dado['cod_interno_veiculo']; ?>" required name="codVeiculo" class="form-control" id="codVeiculo">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="tipoVeiculo">Tipo do Veículo</label>
                            <input type="text" value="<?php echo $dado['tipo_veiculo']; ?>" required name="tipoVeiculo" class="form-control"  id="tipoVeiculo">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="placaVeiculo">Placa do Veículo</label>
                            <input type="text" value="<?php echo $dado['placa_veiculo']; ?>" required name="placaVeiculo" class="form-control" id="placaVeiculo">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="codMotorista">Código do Motorista</label>
                            <input type="text" value="<?php echo $dado['cod_interno_motorista']; ?>" required name="codMotorista" class="form-control" id="codMotorista">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="nomeMotorista">Nome do Motorista</label>
                            <input type="text" value="<?php echo $dado['nome_motorista']; ?>" required name="motorista" class="form-control" id="nomeMotorista">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco">
                            <label for="nCarregamento">Nº Carregamento</label>
                            <input type="text" value="<?php echo $dado['num_carregemento']; ?>" required name="nCarregamento" class="form-control" id="nCarregamento">
                        </div>
                        <?php //$data = new DateTime($dado['data_carregamento']);
                        //echo $data->format('Y-m-d H:i');
                        ?>
                        <div class="form-group col-md-3 espaco">
                            <label for="dataCarregamento">Data do Carregamento</label>
                            <input type="DateTime-Local" required name="dataCarregamento" class="form-control" id="dataCarregamento">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="dataSaida">Data e hora de Saída</label>
                            <input type="DateTime-Local"  required name="dataSaida" class="form-control" id="dataSaida">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="dataChegada">Data e Hora de Chegada</label>
                            <input type="DateTime-Local"  required name="dataChegada" class="form-control" id="dataChegada">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco">
                            <label for="codRota">Código da Rota</label>
                            <input type="text" value="<?php echo $dado['cod_rota'] ?>" required name="codRota" class="form-control" id="codRota">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="rota">Rota</label>
                            <input type="text" value="<?php echo $dado['nome_rota'] ?>" required name="rota" class="form-control" id="rota">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="vlTransp">Valor Transportado (R$)</label>
                            <input type="text" value="<?php echo $dado['valor_transportado'] ?>" required name="vlTransp" class="form-control" id="vlTransp">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="vlDev">Valor Devolvido (R$)</label>
                            <input type="text" value="<?php echo $dado['valor_devolvido'] ?>" required name="vlDev" class="form-control" id="vlDev">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2 espaco">
                            <label for="qtdEntrega">Qtde de Entregas</label>
                            <input type="text" value="<?php echo $dado['qtd_entregas'] ?>" required name="qtdEntrega" class="form-control" id="qtdEntrega">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="nCarga">Nº da Carga</label>
                            <input type="text" value="<?php echo $dado['num_carga'] ?>" required name="nCarga" class="form-control" id="nCarga">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="pesoCarga">Peso da Carga</label>
                            <input type="text" value="<?php echo $dado['peso_carga'] ?>" required name="pesoCarga" class="form-control" id="pesoCarga">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="kmSaida">KM de Saída</label>
                            <input type="text" value="<?php echo $dado['km_saida'] ?>" required name="kmSaida" class="form-control" id="kmSaida">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="hrTkSaida">HR TK Saída</label>
                            <input type="text" value="<?php echo $dado['hr_tk_saida'] ?>" name="hrTkSaida" class="form-control" id="hrTkSaida">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco">
                            <label for="km1Abast">KM 1º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['km_abast1'] ?>" required name="km1Abast" class="form-control" id="km1Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="hrKm1Abast">HR TK 1º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['hr_tk_abast1'] ?>" name="hrKm1Abast" class="form-control" id="hrKm1Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="lt1Abast">Litros 1º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['lt_abast1'] ?>" required name="lt1Abast" class="form-control" id="lt1Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="vl1Abast">Valor 1º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['valor_abast1'] ?>" required name="vl1Abast" class="form-control" id="vl1Abast">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco">
                            <label for="km2Abast">KM 2º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['km_abast2'] ?>" name="km2Abast" class="form-control" id="km2Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="hrKm2Abast">HR TK 2º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['hr_tk_abast2'] ?>" name="hrKm2Abast" class="form-control" id="hrKm2Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="lt2Abast">Litros 2º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['lt_abast2'] ?>" name="lt2Abast" class="form-control" id="lt2Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="vl2Abast">Valor 2º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['valor_abast2'] ?>" name="vl2Abast" class="form-control" id="vl2Abast">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco">
                            <label for="km3Abast">KM 3º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['km_abast3'] ?>" name="km3Abast" class="form-control" id="km3Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="hrKm3Abast">HR TK 3º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['hr_tk_abast3'] ?>" name="hrKm3Abast" class="form-control" id="hrKm3Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="lt3Abast">Litros 3º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['lt_abast3'] ?>" name="lt3Abast" class="form-control" id="lt3Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="vl3Abast">Valor 3º Abastescimento</label>
                            <input type="text" value="<?php echo $dado['valor_abast3'] ?>" name="vl3Abast" class="form-control" id="vl3Abast">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3 espaco">
                            <label for="km4Abast">KM Abastescimento Final</label>
                            <input type="text" value="<?php echo $dado['km_abast4'] ?>" name="km4Abast" class="form-control" id="km4Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="hrKm4Abast">HR TK Abastescimento Final</label>
                            <input type="text" value="<?php echo $dado['hr_tk_abast4'] ?>" name="hrKm4Abast" class="form-control" id="hrKm4Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="lt4Abast">Litros Abastescimento Final</label>
                            <input type="text" value="<?php echo $dado['lt_abast4'] ?>" name="lt4Abast" class="form-control" id="lt4Abast">
                        </div>
                        <div class="form-group col-md-3 espaco">
                            <label for="vl4Abast">Valor Abastescimento Final</label>
                            <input type="text" value="<?php echo $dado['valor_abast4'] ?>" name="vl4Abast" class="form-control" id="vl4Abast">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2 espaco">
                            <label for="diariasMot">Valor Diária Motorista</label>
                            <input type="text" value="<?php echo $dado['diarias_motoristas'] ?>" required name="diariasMot" class="form-control" id="diariasMot">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="diasRota">Dias em Rota Motorista</label>
                            <input type="text" value="<?php echo $dado['dias_motorista'] ?>" required name="diasRota" class="form-control" id="diasRota">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="diariasAjud">Valor Diária Ajudante </label>
                            <input type="text" value="<?php echo $dado['diarias_ajudante'] ?>" required name="diariasAjud" class="form-control" id="diariasAjud">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="diasRotaAjud">Dias em Rota Ajudante</label>
                            <input type="text" value="<?php echo $dado['dias_ajudante'] ?>" required name="diasRotaAjud" class="form-control" id="diasRotaAjud">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="diariasChapa">Valor Diária Chapa</label>
                            <input type="text" value="<?php echo $dado['diarias_chapa'] ?>" required name="diariasChapa" class="form-control" id="diariasChapa">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="diasRotaChapa">Dias em Rota Chapa</label>
                            <input type="text" value="<?php echo $dado['dias_chapa'] ?>" required name="diasRotaChapa" class="form-control" id="diasRotaChapa">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2 espaco">
                            <label for="gastosAjud">Outros Gastos</label>
                            <input type="text" value="<?php echo $dado['outros_gastos_ajudante'] ?>" name="gastosAjud" class="form-control" id="gastosAjud">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="tomada">Tomada</label>
                            <input type="text" value="<?php echo $dado['tomada'] ?>" name="tomada" class="form-control" id="tomada">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="descarga">Descarga</label>
                            <input type="text" value="<?php echo $dado['descarga'] ?>" name="descarga" class="form-control" id="descarga">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="travessia">Travessia</label>
                            <input type="text" value="<?php echo $dado['travessia'] ?>" name="travessia" class="form-control" id="travessia">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="servicos">Serviços</label>
                            <input type="text" value="<?php echo $dado['outros_servicos'] ?>" name="servicos" class="form-control" id="servicos">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="nomeAjud">Nome Ajudante</label>
                            <input type="text" value="<?php echo $dado['nome_ajudante'] ?>" name="nomeAjud" class="form-control" id="nomeAjud">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"> Atualizar </button>
                </form>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="personalizado.js"></script>
        <script type="text/javascript" src="motoristas.js"></script>
        <script type="text/javascript" src="rotas.js"></script>
    </body>
</html>