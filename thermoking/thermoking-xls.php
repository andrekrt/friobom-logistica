<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veículos</title>
    
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
    <?php
    
    if($_SESSION['tipoUsuario'] == 99 ){

        $arquivo = 'thermoking.xls';
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID  </td>';
        $html .= '<td class="text-center font-weight-bold"> Placa de Veículo </td>';
        $html .= '<td class="text-center font-weight-bold"> Modelo de Veículo </td>';
        $html .= '<td class="text-center font-weight-bold"> Tipo TK </td>';
        $html .= '<td class="text-center font-weight-bold"> Horímetro </td>';
        $html .= '<td class="text-center font-weight-bold"> Horímetro Última Revisão </td>';
        $html .= '<td class="text-center font-weight-bold"> Data Última Revisão </td>';
        $html .= '<td class="text-center font-weight-bold"> Horímetro Restante </td>';
        $html .= '<td class="text-center font-weight-bold"> Situação </td>';
        $html .= '</tr>';

        $revisao = $db->query("SELECT * FROM thermoking LEFT JOIN veiculos ON thermoking.veiculo  = veiculos.cod_interno_veiculo");
        $dados = $revisao->fetchAll();
        foreach($dados as $dado){
            
            $html .= '<tr>';
            $html .= '<td>'.$dado['idthermoking'] .'</td>';
            $html .= '<td>'. $dado['placa_veiculo'] .'</td>';
            $html .= '<td>'. $dado['tipo_veiculo'] .'</td>';
            $html .= '<td>'. $dado['tipo_tk'] .'</td>';
            $html .= '<td>'. $dado['hora_atual'] .'</td>';
            $html .= '<td>'. $dado['hora_ultima_revisao'] .'</td>';
            $html .= '<td>'. date("d/m/Y", strtotime($dado['ultima_revisao_tk']))  .'</td>';
            $html .= '<td>'. $dado['hora_restante'] .'</td>';
            $html .= '<td>'. $dado['situacao']  .'</td>';
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
</body>
</html>