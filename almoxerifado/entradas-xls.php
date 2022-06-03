<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'entradas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">Data NF</td>';
        $html .= '<td class="text-center font-weight-bold">' . utf8_decode('Nº NF') . '</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Nº Pedido')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Peça') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Preço') .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Quantidade </td>';
        $html .= '<td class="text-center font-weight-bold"> Desconto </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Observações').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Fornecedor </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor Total </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM `entrada_estoque` LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['identrada_estoque']. '</td>';
            $html .= '<td>'.$dado['data_nf']. '</td>';
            $html .= '<td>'.$dado['num_nf']. '</td>';
            $html .= '<td>'.$dado['num_pedido']. '</td>';
            $html .= '<td>'. utf8_decode($dado['descricao_peca']) . '</td>';
            $html .= '<td>'. number_format($dado['preco_custo'],2,",",".")  . '</td>';
            $html .= '<td>'. number_format($dado['qtd'],2,",",".")  . '</td>';
            $html .= '<td>'. number_format($dado['desconto'],2,",",".")  . '</td>';
            $html .= '<td>'. utf8_decode($dado['obs'])  . '</td>';
            $html .= '<td>'. utf8_decode($dado['apelido'])  . '</td>';
            $html .= '<td>'. number_format($dado['vl_total_comprado'],2,",",".")  . '</td>';
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