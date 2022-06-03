<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'saidas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Data Saída') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Qtd </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Peça').'  </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Valor Médio').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Solicitante </td>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode(' Observações').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Saída').' </td>';
        
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM `saida_estoque` LEFT JOIN peca_estoque ON saida_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $valorMedio = $dado['valor_total']/$dado['total_estoque'];

            $html .= '<tr>';
            $html .= '<td>'.$dado['idsaida_estoque']. '</td>';
            $html .= '<td>'.$dado['data_saida']. '</td>';
            $html .= '<td>'. number_format($dado['qtd'],2,",",".") . '</td>';
            $html .= '<td>'. utf8_decode($dado['descricao_peca']) . '</td>';
            $html .= '<td>'. number_format($valorMedio,2,",",".") . '</td>';
            $html .= '<td>'. utf8_decode($dado['solicitante'],2,",",".")  . '</td>';
            $html .= '<td>'. $dado['placa'] . '</td>';
            $html .= '<td>'. utf8_decode($dado['obs'])  . '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_usuario'])  . '</td>';
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