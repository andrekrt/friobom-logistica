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
    <?php
    
    if($tipoUsuario==1 || $tipoUsuario==99){

        $arquivo = 'veiculos.xls';
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> Código  </td>';
        $html .= '<td class="text-center font-weight-bold"> Tipo </td>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold"> Peso Máximo </td>';
        $html .= '<td class="text-center font-weight-bold"> Cubagem </td>';
        $html .= '<td class="text-center font-weight-bold"> DAta Última Revisão </td>';
        $html .= '<td class="text-center font-weight-bold"> Km Última Revisão </td>';
        $html .= '<td class="text-center font-weight-bold"> Km Atual </td>';
        $html .= '<td class="text-center font-weight-bold"> Km Restante </td>';
        $html .= '</tr>';

        $revisao = $db->query("SELECT * FROM veiculos");
        $dados = $revisao->fetchAll();
        foreach($dados as $dado){
            $kmRestante = $dado['km_atual']-$dado['km_ultima_revisao'];
            $html .= '<tr>';
            $html .= '<td>'.$dado['cod_interno_veiculo'] .'</td>';
            $html .= '<td>'. $dado['tipo_veiculo'] .'</td>';
            $html .= '<td>'. $dado['placa_veiculo'] .'</td>';
            $html .= '<td>'. $dado['peso_maximo'] .'</td>';
            $html .= '<td>'. $dado['cubagem'] .'</td>';
            $html .= '<td>'. date("d/m/Y", strtotime($dado['data_revisao']))  .'</td>';
            $html .= '<td>'. $dado['km_ultima_revisao'] .'</td>';
            $html .= '<td>'. $dado['km_atual']  .'</td>';
            $html .= '<td>'. $kmRestante .'</td>';
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