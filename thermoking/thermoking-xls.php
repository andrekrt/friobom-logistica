<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];

if($_SESSION['tipoUsuario'] == 99 ){

    $arquivo = 'thermoking.xls';
    $html = '';
    $html .= '<table border="1">';
    $html .= '<tr>';
    $html .= '<td class="text-center font-weight-bold"> ID  </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Placa de Veículo').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Modelo de Veículo').' </td>';
    $html .= '<td class="text-center font-weight-bold"> Tipo TK </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Horímetro').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Horímetro Última Revisão').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Data Última Revisão').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Horímetro Restante').' </td>';
    $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Situação').' </td>';
    $html .= '</tr>';

    $revisao = $db->query("SELECT * FROM thermoking LEFT JOIN veiculos ON thermoking.veiculo  = veiculos.cod_interno_veiculo");
    $dados = $revisao->fetchAll();
    foreach($dados as $dado){
        
        $html .= '<tr>';
        $html .= '<td>'.$dado['idthermoking'] .'</td>';
        $html .= '<td>'. $dado['placa_veiculo'] .'</td>';
        $html .= '<td>'. utf8_decode($dado['tipo_veiculo'])  .'</td>';
        $html .= '<td>'. utf8_decode($dado['tipo_tk'])  .'</td>';
        $html .= '<td>'. $dado['hora_atual'] .'</td>';
        $html .= '<td>'. $dado['hora_ultima_revisao'] .'</td>';
        $html .= '<td>'. date("d/m/Y", strtotime($dado['ultima_revisao_tk']))  .'</td>';
        $html .= '<td>'. $dado['hora_restante'] .'</td>';
        $html .= '<td>'. utf8_decode($dado['situacao'])  .'</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$arquivo.'"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');

    echo $html;

}

?>