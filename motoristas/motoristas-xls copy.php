<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];

    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'motoristas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Código Motorista').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Motorista </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('CNH Veículo').' </td>';
        $html .= '<td class="text-center font-weight-bold">Validade CNH </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Toxicológico').'</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Validade Toxicológico').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Salário').'  </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM motoristas WHERE ativo = 1 ORDER BY nome_motorista");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['cod_interno_motorista']. '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_motorista']) . '</td>';
            $html .= '<td>'. $dado['cnh'] . '</td>';
            $html .= '<td>'. date("d/m/Y", strtotime($dado['validade_cnh']) ) . '</td>';
            $html .= '<td>'. utf8_decode($dado['toxicologico'])  .'</td>';
            $html .= '<td>'. date("d/m/Y", strtotime($dado['validade_toxicologico']) ). '</td>';
            $html .= '<td>'.number_format($dado['salario'], 2, ",", ".") . '</td>';
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