<?php
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if($_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'pecas.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Descrição')  .'</td>';
        $html .= '<td class="text-center font-weight-bold"> Categoria </td>';
        $html .= '<td class="text-center font-weight-bold"> Medida </td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM peca_reparo");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['id_peca_reparo']. '</td>';
            $html .= '<td>'. utf8_decode($dado['descricao']) . '</td>';
            $html .= '<td>'.utf8_decode($dado['categoria']). '</td>';
            $html .= '<td>'. utf8_decode($dado['un_medida']) . '</td>';
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