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
    
    if($tipoUsuario==3 || $tipoUsuario==4 || $tipoUsuario==99){

        $arquivo = 'solicitacoes.xls';
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID  </td>';
        $html .= '<td class="text-center font-weight-bold"> Data </td>';
        $html .= '<td class="text-center font-weight-bold"> Servico/Peça </td>';
        $html .= '<td class="text-center font-weight-bold"> Categoria </td>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold"> Local Reparo </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor </td>';
        $html .= '<td class="text-center font-weight-bold"> Situacao </td>';
        $html .= '<td class="text-center font-weight-bold"> Observações </td>';
        $html .= '</tr>';

        $revisao = $db->query("sELECT solicitacoes.*, categoria_peca.* from solicitacoes LEFT JOIN categoria_peca ON solicitacoes.categoria_idcategoria = categoria_peca.idcategoria");
        $dados = $revisao->fetchAll();
        foreach($dados as $dado){
            
            $html .= '<tr>';
            $html .= '<td>'.$dado['id'] .'</td>';
            $html .= '<td>'. $dado['dataAtual'] .'</td>';
            $html .= '<td>'. $dado['servico'] .'</td>';
            $html .= '<td>'. $dado['nome_categoria'] .'</td>';
            $html .= '<td>'. $dado['placarVeiculo'] .'</td>';
            $html .= '<td>'. $dado['localReparo']  .'</td>';
            $html .= '<td>'. $dado['valor']  .'</td>';
            $html .= '<td>'. $dado['statusSolic'] .'</td>';
            $html .= '<td>'. $dado['obs']  .'</td>';
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