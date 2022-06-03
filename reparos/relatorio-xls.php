<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if( $_SESSION['tipoUsuario'] !=  3){

        $arquivo = 'pecas-solicitadas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold"> Data </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Serviço').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Descrição').' </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Placa Veículo').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Solicitante </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Situação').' </td>';
        $html .= '<td class="text-center font-weight-bold"> Obs. </td>';
        $html .= '<td class="text-center font-weight-bold"> Valor </td>';
        $html .= '<td class="text-center font-weight-bold"> Local </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM solicitacoes");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>' .$dado['id'].  '</td>';
            $html .= '<td>' .$dado['dataAtual'].  '</td>';
            $html .= '<td>' . utf8_decode($dado['servico']) .  '</td>';
            $html .= '<td>' . utf8_decode($dado['descricao']) .  '</td>';
            $html .= '<td>' .$dado['placarVeiculo'].  '</td>';
            $html .= '<td>' .$dado['idSolic'].  '</td>';
            $html .= '<td>' . utf8_decode($dado['statusSolic']) .  '</td>';
            $html .= '<td>' . utf8_decode($dado['obs']) .  '</td>';
            $html .= '<td>' . number_format($dado['valor'],2,",",".") .  '</td>';
            $html .= '<td>' . utf8_decode($dado['localReparo']) .  '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$arquivo.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        echo $html;
        exit;

    }

?>