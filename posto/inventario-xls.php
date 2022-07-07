<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 8 || $_SESSION['tipoUsuario'] == 99)){

        $arquivo = 'inventario.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">Data Inventario</td>';
        $html .= '<td class="text-center font-weight-bold"> Volume Encontrado (Litros) </td>';
        $html .= '<td class="text-center font-weight-bold">' .utf8_decode('Usuário que Lançou'). '</td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM combustivel_inventario LEFT JOIN usuarios ON combustivel_inventario.usuario = usuarios.idusuarios");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['idinventario']. '</td>';
            $html .= '<td>'.date("d/m/Y", strtotime($dado['data_inventario'])). '</td>';
            $html .= '<td>'.number_format($dado['qtd_encontrada'],2,",","."). '</td>';
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