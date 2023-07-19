<?php

session_start();
require("../conexao-on.php");
include "funcoes.php";
$tipoUsuario = $_SESSION['tipoUsuario'];
    
if($_SESSION['tipoUsuario'] == 99 ||$_SESSION['tipoUsuario'] == 11){

    $arquivo = 'premiacoes.xls';
    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td class="text-center font-weight-bold">Data Saída </td>';
    $html .= '<td class="text-center font-weight-bold"> Data Retorno </td>';
    $html .= '<td class="text-center font-weight-bold"> Dias em Rota </td>';
    $html .= '<td class="text-center font-weight-bold"> Carga </td>';
    $html .= '<td class="text-center font-weight-bold">Placa</td>';
    $html .= '<td class="text-center font-weight-bold"> Cubagem </td>';
    $html .= '<td class="text-center font-weight-bold">Rota</td>';
    $html .= '<td class="text-center font-weight-bold">Motorista</td>';
    $html .= '<td class="text-center font-weight-bold">Total de Entregas </td>';
    $html .= '<td class="text-center font-weight-bold">Devoluções</td>';
    $html .= '<td class="text-center font-weight-bold">Entregas Liquídas</td>';
    $html .= '<td class="text-center font-weight-bold">Ocorrências Mau Uso do Fusion</td>';
    $html .= '<td class="text-center font-weight-bold"> Uso do Fusion</td>';
    $html .= '<td class="text-center font-weight-bold">Avaria Carro</td>';
    $html .= '<td class="text-center font-weight-bold">Média Combustível</td>';
    $html .= '<td class="text-center font-weight-bold">Devolução</td>';
    $html .= '<td class="text-center font-weight-bold">% Dias em Rota</td>';
    $html .= '<td class="text-center font-weight-bold">Velocidade Máxima</td>';
    $html .= '<td class="text-center font-weight-bold">Prêmio Total</td>';
    $html .= '<td class="text-center font-weight-bold">Prêmio Real</td>';
    $html .= '<td class="text-center font-weight-bold">% Prêmio</td>';
    $html .= '<td class="text-center font-weight-bold">Pago?</td>';
    $html .= '</tr>';

    $sql = $db->query("SELECT iddespesas, data_saida, data_chegada, dias_em_rota, num_carregemento, viagem.placa_veiculo, viagem.nome_rota, viagem.nome_motorista, qtd_entregas, media_comtk, veiculos.meta_combustivel, rotas.meta_dias FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo LEFT JOIN rotas ON viagem.cod_rota=rotas.cod_rota WHERE data_saida > '2022-01-01'");
    $dados = $sql->fetchAll();
    $teste = devolucoes($dados);
    
    // for($i=0;$i<count($dados); $i++){
    //     echo $teste[$i]. " - ". $dados[$i]['num_carregemento'] ."<br>";
    // }
    foreach($dados as $dado){
      
        // $devolucoes = devolucoes($dado['num_carregemento']);
        // $percDev = $devolucoes==0?1:0;
        // $entregasLiq = $dado['qtd_entregas']-$devolucoes;
        // $fusion = mauUsoFusion($dado['num_carregemento']);
        // $checklist = checklist($dado['num_carregemento']);
        // //$mediaComb = $dado['media_comtk']>0?$dado['media_comtk']:1;
        // $metaComb = $dado['media_comtk']/$dado['meta_combustivel'];
        // $velocidade = ocorrenciasVel($dado['num_carregemento']);
        // $metaDias = $dado['meta_dias']?$dado['meta_dias']:1;
        // $diasEmRota =$dado['dias_em_rota']>=1?$dado['dias_em_rota']:1;
        // $percDias = $metaDias/$diasEmRota;

        // if($fusion['percentual']<1){
        //     $valorPago = 0;
        // }elseif($fusion['percentual']>=1){
        //     $valorFusion = 0.5*$entregasLiq;
        //     $valorCheck = $checklist*0.1*$entregasLiq;
        //     $valorComb = $metaComb>=1?1*0.1*$entregasLiq:0;
        //     $valorDev = $percDev*0.1*$entregasLiq;
        //     $valorDias = $percDias>=1?1*0.1*$entregasLiq:0;
        //     $valorVeloc = 0.1*$velocidade*$entregasLiq;
        //     $valorPago = $valorFusion+$valorCheck+$valorComb+$valorDev+$valorDias+$valorVeloc;
        // }

        // $html .= '<tr>';
        $html .= '<td>'.$dado['cod_interno_veiculo'] .'</td>';
        // $html .= '<td>'. utf8_decode($dado['tipo_veiculo'])  .'</td>';
        // $html .= '<td>'. utf8_decode($dado['categoria']) .'</td>';
        // $html .= '<td>'. $dado['placa_veiculo'] .'</td>';
        // $html .= '<td>'. $dado['peso_maximo'] .'</td>';
        // $html .= '<td>'. $dado['cubagem'] .'</td>';
        // $html .= '<td>'. date("d/m/Y", strtotime($dado['data_revisao_oleo']))  .'</td>';
        // $html .= '<td>'. $dado['km_ultima_revisao'] .'</td>';
        // $html .= '<td>'. date("d/m/Y", strtotime($dado['data_revisao_diferencial']))  .'</td>';
        // $html .= '<td>'. $dado['km_revisao_diferencial'] .'</td>';
        // $html .= '<td>'. $dado['km_atual']  .'</td>';
        // $html .= '<td>'. $kmRestante .'</td>';
        // $html .= '<td>'. utf8_decode($situacao)  .'</td>';
        // $html .= '<td>'. $kmRestanteAlinhamento .'</td>';
        // $html .= '<td>'. utf8_decode($situacaoAlinhamento) .'</td>';
        // $html .= '<td>'. utf8_decode($ativo) .'</td>';
        // $html .= '</tr>';
    }
    $html .= '</table>';

    // header('Content-Type: application/vnd.ms-excel');
    // header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    // header('Cache-Control: max-age=0');
    // header('Cache-Control: max-age=1');

    echo $html;

}

?>