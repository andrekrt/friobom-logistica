<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'servicos.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">' . utf8_decode('Descrição') . '</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Categoria')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Lançado'). '</td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM servicos_almoxarifado LEFT JOIN usuarios ON servicos_almoxarifado.usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['idservicos']. '</td>';
            $html .= '<td>'.utf8_decode($dado['descricao']). '</td>';
            $html .= '<td>'.utf8_decode($dado['categoria']). '</td>';
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