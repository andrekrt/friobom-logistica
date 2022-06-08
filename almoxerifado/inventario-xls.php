<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipoUsuario'];
        
    if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4){

        $arquivo = 'inventario.xls';

        $html = '';
        $html .= '<table border="1">';
        $html .= '<tr>';
        $html .= '<td class="text-center font-weight-bold"> ID </td>';
        $html .= '<td class="text-center font-weight-bold">Data</td>';
        $html .= '<td class="text-center font-weight-bold">' . utf8_decode('Peça') . '</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Grupo')  .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Medida') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Qtd') .'</td>';
        $html .= '<td class="text-center font-weight-bold">'. utf8_decode('Lançado'). '</td>';
        $html .= '</tr>';

        $sql = $db->query("SELECT * FROM inventario_almoxarifado LEFT JOIN usuarios ON inventario_almoxarifado.usuario = usuarios.idusuarios LEFT JOIN peca_estoque ON inventario_almoxarifado.peca = peca_estoque.idpeca");
        $dados = $sql->fetchAll();
        foreach($dados as $dado){

            $html .= '<tr>';
            $html .= '<td>'.$dado['idinventario']. '</td>';
            $html .= '<td>'. date("d/m/Y", strtotime($dado['data_inv'])). '</td>';
            $html .= '<td>'.utf8_decode($dado['descricao_peca']). '</td>';
            $html .= '<td>'.utf8_decode($dado['grupo_peca']). '</td>';
            $html .= '<td>'. utf8_decode($dado['un_medida']) . '</td>';
            $html .= '<td>'. number_format($dado['qtd'],2,",",".")  . '</td>';
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