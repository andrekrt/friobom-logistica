<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 8 || $_SESSION['tipoUsuario'] == 99)){

        $arquivo = 'entradas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">Data Entrada</td>';
        $html .= '<td class="text-center font-weight-bold"> Valor por Litro </td>';
        $html .= '<td class="text-center font-weight-bold">Litros</td>';
        $html .= '<td class="text-center font-weight-bold">Valor Total</td>';
        $html .= '<td class="text-center font-weight-bold">Fornecedor</td>';
        $html .= '<td class="text-center font-weight-bold"> Qualidade </td>';
        $html .= '<td class="text-center font-weight-bold">' .utf8_decode('Usuário que Lançou'). '</td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM combustivel_entrada LEFT JOIN usuarios ON combustivel_entrada.usuario = usuarios.idusuarios LEFT JOIN fornecedores ON combustivel_entrada.fornecedor = fornecedores.id");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['idcombustivel_entrada']. '</td>';
            $html .= '<td>'.date("d/m/Y", strtotime($dado['data_entrada'])). '</td>';
            $html .= '<td>'.number_format($dado['valor_litro'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['total_litros'],2,",","."). '</td>';
            $html .= '<td>'.number_format($dado['valor_total'],2,",",".") . '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_fantasia'])  . '</td>';
            $html .= '<td>'. utf8_decode($dado['qualidade'])  . '</td>';
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