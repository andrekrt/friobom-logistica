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
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Data Saída','ISO-8859-1', 'UTF-8') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Qtd </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Peça','ISO-8859-1', 'UTF-8').'  </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Valor Médio','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Solicitante </td>';
        $html .= '<td class="text-center font-weight-bold"> Placa </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding(' Observações','ISO-8859-1', 'UTF-8').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. mb_convert_encoding('Saída', 'ISO-8859-1', 'UTF-8').' </td>';
        
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM `saida_estoque` LEFT JOIN peca_estoque ON saida_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $valorMedio = $dado['valor_total']/$dado['total_estoque'];

            $html .= '<tr>';
            $html .= '<td>'.$dado['idsaida_estoque']. '</td>';
            $html .= '<td>'.$dado['data_saida']. '</td>';
            $html .= '<td>'. number_format($dado['qtd'],2,",",".") . '</td>';
            $html .= '<td>'.$dado['idpeca']." - ". mb_convert_encoding($dado['descricao_peca'],'ISO-8859-1', 'UTF-8') . '</td>';
            $html .= '<td>'. number_format($valorMedio,2,",",".") . '</td>';
            $html .= '<td>'. mb_convert_encoding($dado['solicitante'],'ISO-8859-1', 'UTF-8')  . '</td>';
            $html .= '<td>'. $dado['placa'] . '</td>';
            $html .= '<td>'. mb_convert_encoding($dado['obs'],'ISO-8859-1', 'UTF-8')  . '</td>';
            $html .= '<td>'. mb_convert_encoding($dado['nome_usuario'],'ISO-8859-1', 'UTF-8')  . '</td>';
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