<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 4 ){

        $arquivo = 'revisoes.xls';
        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Km Revisão').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Data Revisão').'</td>';
        $html .= '</tr>';

        $revisao = $db->query("SELECT * FROM revisao_veiculos");
        $dados = $revisao->fetchAll();
        foreach($dados as $dado){
            $html .= '<tr>';
            $html .= '<td>'.$dado['placa_veiculo'] .'</td>';
            $html .= '<td>'. $dado['km_revisao'] .'</td>';
            $html .= '<td>'. $dado['data_revisao'] .'</td>';
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