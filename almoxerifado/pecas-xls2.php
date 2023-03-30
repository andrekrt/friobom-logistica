<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'estoque.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Descrição')  .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Medida </td>';
        $html .= '<td class="text-center font-weight-bold"> Grupo </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Estoque Mínimo').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Total Entrada </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Total Saída').'  </td>';
        $html .= '<td class="text-center font-weight-bold"> Total Estoque </td>';
        $html .= '<td class="text-center font-weight-bold"> Total Comprado</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Situação').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Data Cadastro </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Usuário que Cadastrou').'</td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM `peca_estoque` LEFT JOIN usuarios ON peca_estoque.id_usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['idpeca']. '</td>';
            $html .= '<td>'. utf8_decode($dado['descricao_peca']) . '</td>';
            $html .= '<td>'.$dado['un_medida']. '</td>';
            $html .= '<td>'. utf8_decode($dado['grupo_peca']) . '</td>';
            $html .= '<td>'. number_format($dado['estoque_minimo'],1,",",".") . '</td>';
            $html .= '<td>'. number_format($dado['total_entrada'],1,",",".") . '</td>';
            $html .= '<td>'. number_format($dado['total_saida'],1,",",".") . '</td>';
            $html .= '<td>'. number_format($dado['total_estoque'],1,",","."). '</td>';
            $html .= '<td>'. number_format($dado['valor_total'],2,",",".") . '</td>';
            $html .= '<td>'. utf8_decode($dado['situacao']) . '</td>';
            $html .= '<td>'.date("d/m/Y",strtotime($dado['data_cadastro'])).  '</td>';
            $html .= '<td>'. utf8_decode($dado['nome_usuario']) . '</td>';
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