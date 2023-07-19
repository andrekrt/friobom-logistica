<?php

use Mpdf\Mpdf;
require_once __DIR__ . '/vendor/autoload.php';
require("../conexao.php");

$idViagemn = filter_input(INPUT_GET, 'id');

$sql= $db->query("SELECT * FROM viagem WHERE iddespesas ='$idViagemn' ");
if($sql){

    $dados = $sql->fetchAll();
    foreach($dados as $dado){
        $codVeiculo = $dado['cod_interno_veiculo'];
        $veiculo = $dado['tipo_veiculo'];
        $placa = $dado['placa_veiculo'];
        $codMotorist = $dado['cod_interno_motorista'];
        $motorista = $dado['nome_motorista'];
        $dataCarregamento = date("d/m/Y", strtotime($dado['data_carregamento']));
        $numCarregamento = $dado['num_carregemento'];
        $codRota = $dado['cod_rota'];
        $rota = $dado['nome_rota'];
        $valorTransportado = number_format($dado['valor_transportado'], 2, ",", ".") ;
        $valorLiquido = number_format($dado['valor_liquido'], 2, ",", ".") ;
        $entregas = $dado['qtd_entregas'];
        $cargas = $dado['num_carga'];
        $peso = number_format($dado['peso_carga'], 2, ",", ".") ;
        $combustivelTotal = $dado['litros'];
        $kmRodado = $dado['km_rodado'];
        $kmPorLitroSemTk = $dado['kmPorLtSemTk'];
        $kmSaida = $dado['km_saida'];
        $km1Abastecimento = $dado['km_abast1'];
        $km1Percuso = $dado['km_perc1'];
        $hrTkInicial = $dado['hr_tk_saida'];
        $hrTk1Abast = 0;
        $hrTk2Abast = 0;
        $hrTk3Abast = 0;
        $hrTk4Abast = $dado['hr_tk_abast4'];
        $hrTk1Percusso = 0;
        $hrTk2Percusso = 0;
        $hrTk3Percusso = 0;
        $hrTk4Percusso = $hrTk4Abast-$hrTkInicial;
        $km2Abastecimento = $dado['km_abast2'];
        $km2Percuso = $dado['km_perc2'];
        $km3Abastecimento = $dado['km_abast3'];
        $km3Percuso = $dado['km_abast3'];
        $km4Abastecimento = $dado['km_abast4'];
        $km4Percuso = $dado['km_perc4'];
        $litros1Abast = $dado['lt_abast1'];
        $litros2Abast = $dado['lt_abast2'];
        $litros3Abast = $dado['lt_abast3'];
        $litros4Abast = $dado['lt_abast4'];
        $litrosGeral = $litros1Abast+$litros2Abast+$litros3Abast+$litros4Abast;
        $litroTk1Abast = $hrTk1Percusso*2;
        $litroTk2Abast = 0;
        $litroTk3Abast = 0;
        $litroTk4Abast = $hrTk4Percusso*2;
        $totalLitrosTk = $litroTk1Abast+$litroTk2Abast+$litroTk3Abast+$litroTk4Abast;
        $litroSemTk = $litros1Abast-$litroTk1Abast;
        $litroSemTk2 = $litros2Abast-$litroTk2Abast;
        $litroSemTk3 = $litros3Abast-$litroTk3Abast;
        $litroSemTk4 = $litros4Abast-$litroTk4Abast;
        $hrTk4Abast = $dado['hr_tk_abast4'];
        $litrosComTK = ($hrTk4Abast-$hrTkInicial)*2;
        $totalLitrosSemTk = $litrosGeral-$litrosComTK;
        if($totalLitrosSemTk==0){
            $mediaComTk=0;
        }else{
            $mediaComTk = number_format($kmRodado/$totalLitrosSemTk,2);
        }
        
        $mediaSemTk = $dado['mediaSemTk'];
        $kmPorLitro ;
        if($km1Percuso==0){
            $kmPorLitro = 0;
        }else{
            $kmPorLitro = number_format($km1Percuso/$litroSemTk,2) ;
        }
        
        $kmPorLitro2   ;
        if($km2Percuso==0){
            $kmPorLitro2 =0;
        }else{
           $kmPorLitro2 = number_format($km2Percuso/$litroSemTk2,2);
        }
        $kmPorLitro3   ;
        if($km3Percuso==0){
            $kmPorLitro3 =0;
        }else{
           $kmPorLitro3 = number_format($km3Percuso/$litroSemTk3,2);
        }

        $kmPorLitro4;
        if($km4Percuso==0 || $litroSemTk4==0){
            $kmPorLitro4=0;
        }else{
           $kmPorLitro4 = number_format($km4Percuso/$litroSemTk4,2);
        }
        $valorAbastecimento1 = $dado['valor_abast1'];
        $valorAbastecimento2 = $dado['valor_abast2'];
        $valorAbastecimento3 = $dado['valor_abast3'];
        $valorAbastecimento4 = $dado['valor_abast4'];
        $valorTotalAbastecimento = $dado['valor_total_abast'];
        $diariasTotalMotorista = $dado['diarias_motoristas']*$dado['dias_motorista'];
        $diariasTotalAjudante = $dado['diarias_ajudante']*$dado['dias_ajudante'];
        $diariasAjudaCusto = $diariasTotalAjudante+$dado['outros_gastos_ajudante'];
        $diariasTotalChapa = $dado['dias_chapa']*$dado['diarias_chapa'];
        $diariasTotalGeral = $diariasTotalAjudante+$diariasTotalMotorista+$dado['outros_gastos_ajudante']+$diariasTotalChapa;
        $tomada = $dado['tomada'];
        $descarga = $dado['descarga'];
        $travessia = $dado['travessia'];
        $outrosServicos = $dado['outros_servicos'];
        $gastosViagens = $diariasTotalGeral+$valorTotalAbastecimento+$tomada+
        $tomada+$descarga+$travessia+$outrosServicos;
        $percDaCarga = number_format(($gastosViagens/$valorTransportado)*100,2);

        $totalFichaDeViagem = $valorTotalAbastecimento+$diariasTotalMotorista+$diariasTotalAjudante+$dado['outros_gastos_ajudante']+$diariasTotalChapa+$tomada+$descarga+$travessia;

        $valorDespesaMotorista = $valorAbastecimento1+$valorAbastecimento2+$valorAbastecimento3+$diariasTotalMotorista+$diariasTotalAjudante+$dado['outros_gastos_ajudante']+$tomada+$descarga+$travessia+$outrosServicos+$diariasTotalChapa;

    }

}

$mpdf = new Mpdf();
$mpdf->AddPage('L');
$mpdf->WriteHTML("
<!DOCTYPE html>
<html lang='pt-br'>
    
    <body>
        <img src='../assets/images/logo.png' alt='' style='height:80px; margin-top:-50px;margin-left:600px'>
        <table style='width:100%'>
            <thead >
                <tr >
                    <th style='font-size:8pt;border:1px solid black;'>Código Veículo</th>
                    <th  style='font-size:8pt;border:1px solid black;'>Veículo</th>
                    <th  style='font-size:8pt;border:1px solid black;'>Placa</th>
                    <th  style='font-size:8pt;border:1px solid black;'>Código Motorista</th>
                    <th  style='font-size:8pt;border:1px solid black;'>Motorista</th>
                </tr>
            </thead>
            <tbody>
                <tr style='text-align: center;'>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> $codVeiculo </td>
                    <td style='text-align: center;font-size:8pt;border:1px solid black;'> $veiculo </td>
                    <td style='text-align: center;font-size:8pt;border:1px solid black;'>$placa</td>
                    <td style='text-align: center;font-size:8pt;border:1px solid black;'> $codMotorist </td>
                    <td style='text-align: center;font-size:8pt;border:1px solid black;'>$motorista</td>
                </tr>
            </tbody>
            <thead>
                <tr>
                    <th style='font-size:8pt;border:1px solid black;'>Data Carregamento</th>
                    <th style='font-size:8pt;border:1px solid black;'>Nº Carregamento</th>
                    <th style='font-size:8pt;border:1px solid black;'>Código Rota</th>
                    <th style='font-size:8pt;border:1px solid black;'>Rota</th>
                    <th style='font-size:8pt;border:1px solid black;'>Valor Transportado</th>
                    <th style='font-size:8pt;border:1px solid black;'>Valor Liquído</th>
                    <th style='font-size:8pt;border:1px solid black;'>Entregas</th>
                    <th style='font-size:8pt;border:1px solid black;'>Cargas</th>
                    <th style='font-size:8pt;border:1px solid black;'>Peso</th>
                    <th style='font-size:8pt;border:1px solid black;'>Combustível (Lt)</th>
                    <th style='font-size:8pt;border:1px solid black;'>Km Rodado</th>
                    <th style='font-size:8pt;border:1px solid black;'>Km/L s/Tk</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$dataCarregamento</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$numCarregamento</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$codRota</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$rota</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> R$  $valorTransportado</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> R$ $valorLiquido</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$entregas</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$cargas</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$peso</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$combustivelTotal</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$kmRodado</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>$mediaSemTk</td>
                </tr>
            </tbody>
        </table>
        <table style='margin-top:10px; width:100%'>
            <thead>
                <tr>
                    <th colspan='2' style='font-size:10pt;border:1px solid black;'>Despesas Logistica</th>
                    <th style='width:100px'></th>
                    
                    <th colspan='2' style='font-size:10pt;border:1px solid black;'> 1º Abast. Externo</th>
                    <th colspan='2' style='font-size:10pt;border:1px solid black;'> 2º Abast. Externo</th>
                    <th colspan='2' style='font-size:10pt;border:1px solid black;'> 3º Abast. Externo</th>
                    <th colspan='2' style='font-size:10pt;border:1px solid black;'> Abastecimento Interno</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style=' font-size:8pt;border:1px solid black;'> Abastecimento</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>R$ $valorTotalAbastecimento</td>
                    <td style='text-align: center; font-size:8pt'></td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>KM's</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>Hr TK.</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>KM's</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>Hor TK.</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>KM's</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>Hr TK.</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>KM's</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>Hr TK.</td>
                </tr>
                <tr>
                    
                    <td style=' font-size:8pt;border:1px solid black;'>Diarias</td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'>R$ $diariasTotalGeral</td>
                    <td style='font-weight:bold; text-align: right; font-size:8pt;border:1px solid black;'> Inicial </td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> $kmSaida </td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> $hrTkInicial </td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> $km1Abastecimento </td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> 0 </td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> $km2Abastecimento </td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> 0 </td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> $km4Abastecimento </td>
                    <td style='text-align: center; font-size:8pt;border:1px solid black;'> $hrTk4Abast </td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'>Tomada</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>R$ $tomada</td>
                    <td style='font-weight:bold; font-size:8pt; text-align:right;border:1px solid black;'>  Abastecimento </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $km1Abastecimento </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $hrTk1Abast </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $km2Abastecimento </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> 0 </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $km3Abastecimento </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $hrTk1Abast </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $km4Abastecimento </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $hrTk1Abast</td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'>Descarga</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>R$ $descarga</td>
                    <td style='font-size:8pt; text-align:right; font-weight:bold;border:1px solid black;'>  Percurso </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $km1Percuso </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> 0 </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $km2Percuso </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> 0 </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $km3Percuso </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>0 </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $km4Percuso </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $hrTk4Percusso </td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;' >Travessia</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>R$ $travessia</td>
                    <td style='font-weight:bold; text-align:right;font-size:8pt;border:1px solid black;'> Litros </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>  $litros1Abast</td>
                    <td></td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>  $litros2Abast</td>
                    <td style='font-size:8pt; text-align:center;'></td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>  $litros3Abast</td>
                    <td style='font-size:8pt; text-align:center'></td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>  $litros4Abast</td>
                    <td></td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Serviços / Peças </td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> R$ $outrosServicos </td>
                    <td style='font-weight:bold; font-size:8pt; text-align:right;border:1px solid black;'> Litro (TK) </td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> 0 </td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> KM/L </td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> 0 </td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> KM/L</td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> 0 </td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> KM/L </td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> 0 </td>
                    <td style='font-size:8pt;text-align:center;border:1px solid black;'> KM/L </td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'>TOTAL</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>R$ $gastosViagens</td>
                    <td> </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $litroSemTk </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $kmPorLitro </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $litroSemTk2 </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $kmPorLitro2</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $litroSemTk3 </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $kmPorLitro3</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $litroSemTk4 </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>$kmPorLitro4 </td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Perc. Carga (%) </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $percDaCarga%</td>
                </tr>
                <tr>
                    <td>  </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <th style='font-size:8pt; text-align:center;border:1px solid black;'> Lt/Tk </th>
                    <th style='font-size:8pt; text-align:center;border:1px solid black;'> Litro Geral </th>
                    <th style='font-size:8pt; text-align:center;border:1px solid black;'> Litro - Lt/Tk </th>
                    <th style='font-size:8pt; text-align:center;border:1px solid black;'> Média Com TK </th>
                </tr>
                <tr>
                    <td>  </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td>  </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $litrosComTK</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $litrosGeral</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>  $totalLitrosSemTk</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;' > $mediaComTk</td>
                </tr>
            </tbody>
        </table>
        <table style=' margin-top:10px; margin-bottom:30px;width:100%'> 
            <thead>
                <tr>
                    <th colspan='2' style='font-size:9pt; text-align:center;border:1px solid black;'>
                        Ficha de Viagens
                    </th>
                    <th></th>
                    <th></th>
                    <th colspan='4' style='font-size:8pt; font-weight:bold; text-align:center;border:1px solid black;'> Diarias Motorista e Ajudante </th>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Abastecimento 1 </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $valorAbastecimento1</td>
                    <td></td>
                    <td></td>
                    <td style='font-size:8pt; font-weight:bold; text-align:center;border:1px solid black;'> Mot/Ajud </td>
                    <td style='font-size:8pt; font-weight:bold; text-align:center;border:1px solid black;'> Dias </td>
                    <td style='font-size:8pt; font-weight:bold;text-align:center; border:1px solid black;'> Valor </td>
                    <td style='font-size:8pt; font-weight:bold; text-align:center;border:1px solid black;'> Total </td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Abastecimento 2 </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $valorAbastecimento2</td>
                    <td> </td>
                    <td>  </td>
                    <td style='font-size:8pt;border:1px solid black;'> Diarias do Motorista </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $dado[dias_motorista] </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $dado[diarias_motoristas] </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>  $diariasTotalMotorista</td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Abastecimento 3 </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $valorAbastecimento3</td>
                    <td></td>
                    <td></td>
                    <td style='font-size:8pt;border:1px solid black;'> Diarias Ajudante </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $dado[dias_ajudante] </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $dado[diarias_ajudante] </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $diariasTotalAjudante </td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Abastecimento 4 </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $valorAbastecimento4</td>
                    <td> </td>
                    <td>  </td>
                    <td colspan='3' style='font-size:8pt;border:1px solid black;'> Outros Gastos </td>
                    <td  style='font-size:8pt; text-align:center;border:1px solid black;'> $dado[outros_gastos_ajudante] </td>
                    
                    
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Diarias Motorista </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $diariasTotalMotorista</td>
                    <td style='font-size:8pt;border:1px solid black;'> $dado[dias_motorista] </td>
                    <td></td>
                    <td style='font-size:8pt;border:1px solid black;'> Chapa </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $dado[dias_chapa] </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $dado[diarias_chapa] </td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'> $diariasTotalChapa </td>
                    
                    
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Diarias Chapa  </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $diariasTotalChapa</td>
                    <td style='font-size:8pt;border:1px solid black'> $dado[dias_chapa] </td>
                    <td>  </td>
                    <td colspan='3' style='font-size:8pt; font-weight:bold;border:1px solid black;'> Total Mot/Ajud</td>
                    <td style='font-size:8pt; text-align:center;border:1px solid black;'>R$ $diariasTotalGeral </td>
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Diarias Ajudante </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $diariasTotalAjudante </td>
                    <td style='font-size:8pt;border:1px solid black;'> $dado[dias_ajudante] </td>
                    
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Outros Gastos Ajudante </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $dado[outros_gastos_ajudante]</td>
                    
                </tr>
                <tr>
                    <td style='font-size:8pt;border:1px solid black;'> Soma Total </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $totalFichaDeViagem</td>
                </tr>
                <tr>
                    <td style='font-size:8pt'>  </td>
                    <td style='font-size:8pt'>  </td>
                    <td > </td>
                    <td> </td>
                    
                </tr>
                <tr style='margin-bottom:10px'>
                    <td style='font-size:8pt;border:1px solid black;'> Valor da Despesa Motorista </td>
                    <td style='font-size:8pt;border:1px solid black;'> R$ $valorDespesaMotorista</td>
                </tr>
            </thead>
        </table>
        <span > __________________________________ <br>$motorista</span>
        <p style='margin-left:700px; margin-top:-40px; text-align:center'> __________________________________ <br>SETOR TRANSPORTE</p>
    </body>
</html>
");

$mpdf->Output();

?>